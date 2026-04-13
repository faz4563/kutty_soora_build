<?php
/**
 * Payment Cancellation API
 * 
 * Security features:
 * - JWT authentication required
 * - Order ownership verification
 * - Payment status validation
 * - Prevents cancellation of already completed payments
 */

require_once 'cors_headers.php';
require_once 'db_config.php';
require_once 'jwt_auth.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Authenticate user via JWT
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

if (empty($authHeader) || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

$token = $matches[1];
$decoded = JWTAuth::verifyToken($token);

if (!$decoded) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid or expired token']);
    exit;
}

$userId = $decoded['user_id'];

// Get and validate input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

// Required fields validation
if (!isset($input['order_id']) || empty($input['order_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required field: order_id']);
    exit;
}

$orderId = filter_var($input['order_id'], FILTER_VALIDATE_INT);

if ($orderId === false || $orderId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order ID']);
    exit;
}

try {
    // Fetch order to verify ownership and payment status
    $stmt = $pdo->prepare("
        SELECT id, user_id, payment_status, status 
        FROM orders 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch();

    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found or access denied']);
        exit;
    }

    // Prevent cancellation of already paid or completed orders
    if ($order['payment_status'] === 'paid') {
        http_response_code(400);
        echo json_encode([
            'error' => 'Cannot cancel paid orders. Contact support for refunds.',
            'payment_status' => $order['payment_status']
        ]);
        exit;
    }

    // Prevent double cancellation
    if ($order['payment_status'] === 'canceled' || $order['status'] === 'cancelled') {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Order is already canceled',
            'order_id' => $orderId,
            'payment_status' => 'canceled'
        ]);
        exit;
    }

    // Mark payment as canceled
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET payment_status = 'canceled', 
            status = 'cancelled',
            updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$orderId]);

    // Log payment cancellation
    $stmt = $pdo->prepare("
        INSERT INTO payment_logs 
        (order_id, status, error_message, created_at) 
        VALUES (?, 'canceled', 'Payment canceled by user', NOW())
    ");
    $stmt->execute([$orderId]);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Payment canceled successfully',
        'order_id' => $orderId,
        'payment_status' => 'canceled'
    ]);

} catch (PDOException $e) {
    error_log("Database Error in cancel_payment: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    error_log("Error in cancel_payment: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while processing your request']);
    exit;
}
?>
