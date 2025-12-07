<?php
require_once 'cors_headers.php';
require_once 'db_config.php';
require_once 'jwt_auth.php';
require_once 'vendor/autoload.php';

use Razorpay\Api\Api;

header('Content-Type: application/json');

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

// Verify JWT token
$user = verifyJWT();
if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Check if user is admin (only admins can process refunds)
if ($user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Only admins can process refunds']);
    exit;
}

try {
    $refund_request_id = $data['refund_request_id'] ?? null;
    
    if (!$refund_request_id) {
        throw new Exception('Refund request ID is required');
    }

    // Get refund request details
    $stmt = $pdo->prepare("
        SELECT rr.*, o.razorpay_payment_id, o.order_number, o.total_amount as order_amount
        FROM refund_requests rr
        JOIN orders o ON rr.order_id = o.id
        WHERE rr.id = ?
    ");
    $stmt->execute([$refund_request_id]);
    $refund_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$refund_request) {
        throw new Exception('Refund request not found');
    }

    if ($refund_request['status'] === 'completed') {
        throw new Exception('Refund already processed');
    }

    if (empty($refund_request['razorpay_payment_id'])) {
        throw new Exception('No Razorpay payment ID found for this order');
    }

    // Load Razorpay credentials from .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $razorpay_key_id = $_ENV['RAZORPAY_KEY_ID'];
    $razorpay_key_secret = $_ENV['RAZORPAY_KEY_SECRET'];

    // Initialize Razorpay API
    $api = new Api($razorpay_key_id, $razorpay_key_secret);

    // Create refund
    $payment = $api->payment->fetch($refund_request['razorpay_payment_id']);
    
    // Refund amount in paise (multiply by 100)
    $refund_amount_paise = (int)($refund_request['amount'] * 100);

    $refund = $payment->refund([
        'amount' => $refund_amount_paise,
        'speed' => 'normal',
        'notes' => [
            'order_number' => $refund_request['order_number'],
            'refund_request_id' => $refund_request_id,
            'reason' => $refund_request['reason']
        ]
    ]);

    // Update refund request with Razorpay refund ID
    $update_stmt = $pdo->prepare("
        UPDATE refund_requests 
        SET status = 'completed',
            razorpay_refund_id = ?,
            admin_notes = CONCAT(COALESCE(admin_notes, ''), '\nRefund processed via Razorpay on ', NOW()),
            updated_at = NOW()
        WHERE id = ?
    ");
    $update_stmt->execute([$refund->id, $refund_request_id]);

    // Update order payment status to refunded
    $order_update = $pdo->prepare("
        UPDATE orders 
        SET payment_status = 'refunded',
            admin_notes = CONCAT(COALESCE(admin_notes, ''), '\nRefund completed: ', ?),
            updated_at = NOW()
        WHERE id = ?
    ");
    $order_update->execute([$refund->id, $refund_request['order_id']]);

    echo json_encode([
        'success' => true,
        'message' => 'Refund processed successfully',
        'refund_id' => $refund->id,
        'amount' => $refund_request['amount'],
        'status' => $refund->status
    ]);

} catch (Exception $e) {
    error_log("Razorpay Refund Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
