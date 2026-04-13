<?php
/**
 * Razorpay Payment Verification API
 * 
 * Security features:
 * - JWT authentication required
 * - Razorpay signature verification
 * - Order ownership verification
 * - Idempotency protection (prevent duplicate processing)
 * - Transaction logging
 * - Secure database updates
 */

require_once 'cors_headers.php';
require_once 'db_config.php';
require_once 'jwt_auth.php';

// Safely load Composer autoload if available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        @include_once __DIR__ . '/vendor/autoload.php';
    } catch (Throwable $e) {
        error_log("Composer autoload failed in verify_razorpay_payment: " . $e->getMessage());
    }
}

// Try to use Dotenv if available
if (class_exists('Dotenv\Dotenv')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } catch (Throwable $e) {
        error_log("Dotenv failed in verify_razorpay_payment: " . $e->getMessage());
    }
}

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
$requiredFields = ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature', 'order_id'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

$razorpayPaymentId = filter_var($input['razorpay_payment_id'], FILTER_SANITIZE_STRING);
$razorpayOrderId = filter_var($input['razorpay_order_id'], FILTER_SANITIZE_STRING);
$razorpaySignature = filter_var($input['razorpay_signature'], FILTER_SANITIZE_STRING);
$orderId = filter_var($input['order_id'], FILTER_VALIDATE_INT);

// Validate order ID
if ($orderId === false || $orderId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order ID']);
    exit;
}

try {
    // Start transaction for data consistency
    $pdo->beginTransaction();

    // Verify order exists and belongs to the authenticated user
    $stmt = $pdo->prepare("
        SELECT id, user_id, total_amount, status, payment_status, razorpay_order_id 
        FROM orders 
        WHERE id = ? AND user_id = ? 
        FOR UPDATE
    ");
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch();

    if (!$order) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['error' => 'Order not found or access denied']);
        exit;
    }

    // Verify Razorpay order ID matches
    if ($order['razorpay_order_id'] !== $razorpayOrderId) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['error' => 'Order ID mismatch']);
        exit;
    }

    // Check if payment already verified (idempotency)
    if ($order['payment_status'] === 'paid') {
        $pdo->rollBack();
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Payment already verified',
            'order_id' => $orderId,
            'payment_id' => $razorpayPaymentId
        ]);
        exit;
    }

    // Get Razorpay secret for signature verification
    $razorpayKeySecret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';

    if (empty($razorpayKeySecret)) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Payment gateway configuration error']);
        exit;
    }

    // Verify Razorpay signature
    $generatedSignature = hash_hmac(
        'sha256',
        $razorpayOrderId . '|' . $razorpayPaymentId,
        $razorpayKeySecret
    );

    if ($generatedSignature !== $razorpaySignature) {
        // Log failed verification attempt
        $stmt = $pdo->prepare("
            INSERT INTO payment_logs 
            (order_id, razorpay_order_id, razorpay_payment_id, status, error_message, created_at) 
            VALUES (?, ?, ?, 'signature_failed', 'Invalid signature', NOW())
        ");
        $stmt->execute([$orderId, $razorpayOrderId, $razorpayPaymentId]);

        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(['error' => 'Payment verification failed - invalid signature']);
        exit;
    }

    // Signature is valid - Fetch payment details from Razorpay for additional verification
    $razorpayKeyId = $_ENV['RAZORPAY_KEY_ID'] ?? '';
    
    $ch = curl_init("https://api.razorpay.com/v1/payments/$razorpayPaymentId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $razorpayKeyId . ':' . $razorpayKeySecret);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $paymentResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $paymentDetails = json_decode($paymentResponse, true);

    // Verify payment status from Razorpay
    if ($httpCode !== 200 || !isset($paymentDetails['status'])) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to verify payment with gateway']);
        exit;
    }

    if ($paymentDetails['status'] !== 'captured' && $paymentDetails['status'] !== 'authorized') {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode([
            'error' => 'Payment not completed',
            'status' => $paymentDetails['status']
        ]);
        exit;
    }

    // Verify amount
    $paidAmount = $paymentDetails['amount'] / 100; // Convert from paise to rupees
    $orderAmount = (float)$order['total_amount'];

    if (abs($paidAmount - $orderAmount) > 0.01) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode([
            'error' => 'Payment amount mismatch',
            'expected' => $orderAmount,
            'received' => $paidAmount
        ]);
        exit;
    }

    // All verifications passed - Update order status
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET payment_status = 'paid',
            razorpay_payment_id = ?,
            razorpay_signature = ?,
            status = 'confirmed',
            updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$razorpayPaymentId, $razorpaySignature, $orderId]);

    // Log successful payment
    $stmt = $pdo->prepare("
        INSERT INTO payment_logs 
        (order_id, razorpay_order_id, razorpay_payment_id, amount, currency, status, payment_method, created_at) 
        VALUES (?, ?, ?, ?, ?, 'success', ?, NOW())
    ");
    $stmt->execute([
        $orderId,
        $razorpayOrderId,
        $razorpayPaymentId,
        $orderAmount,
        $paymentDetails['currency'] ?? 'INR',
        $paymentDetails['method'] ?? 'unknown'
    ]);

    // Commit transaction
    $pdo->commit();

    // Send success response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Payment verified successfully',
        'order_id' => $orderId,
        'payment_id' => $razorpayPaymentId,
        'order_status' => 'confirmed',
        'payment_status' => 'paid'
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Database Error in verify_razorpay_payment: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error in verify_razorpay_payment: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while processing your request']);
    exit;
}
?>
