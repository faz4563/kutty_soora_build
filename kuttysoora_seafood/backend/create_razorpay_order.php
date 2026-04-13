<?php
/**
 * Razorpay Order Creation API
 * 
 * Security features:
 * - JWT authentication required
 * - Input validation and sanitization
 * - CSRF protection via token
 * - Rate limiting (implement via database)
 * - Secure amount handling
 * - Order verification before payment
 */

require_once 'cors_headers.php';
require_once 'db_config.php';
require_once 'jwt_auth.php';

// Safely load Composer autoload if available
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        @include_once __DIR__ . '/vendor/autoload.php';
    } catch (Throwable $e) {
        error_log("Composer autoload failed in create_razorpay_order: " . $e->getMessage());
    }
}

// Try to use Dotenv if available
if (class_exists('Dotenv\Dotenv')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } catch (Throwable $e) {
        error_log("Dotenv failed in create_razorpay_order: " . $e->getMessage());
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
$requiredFields = ['order_id', 'amount', 'currency'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

$orderId = filter_var($input['order_id'], FILTER_VALIDATE_INT);
$amount = filter_var($input['amount'], FILTER_VALIDATE_FLOAT);
$currency = filter_var($input['currency'], FILTER_SANITIZE_STRING);

// Validate inputs
if ($orderId === false || $orderId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order ID']);
    exit;
}

if ($amount === false || $amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

if (!in_array(strtoupper($currency), ['INR', 'USD', 'EUR'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid currency']);
    exit;
}

try {
    // Verify order exists and belongs to the authenticated user
    $stmt = $pdo->prepare("
        SELECT id, user_id, total_amount, status 
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

    // Verify order is in correct status (pending payment)
    if ($order['status'] !== 'pending') {
        http_response_code(400);
        echo json_encode(['error' => 'Order is not in pending status']);
        exit;
    }

    // AGGRESSIVE: Recalculate total from order items to verify integrity
    $calculatedTotal = 0;
    $itemsStmt = $pdo->prepare("
        SELECT SUM(COALESCE(total_price, quantity * unit_price)) as item_total 
        FROM order_items 
        WHERE order_id = ?
    ");
    $itemsStmt->execute([$orderId]);
    $itemsResult = $itemsStmt->fetch();
    if ($itemsResult && $itemsResult['item_total'] !== null) {
        $calculatedTotal = (float)$itemsResult['item_total'];
    }
    
    // Use database order total as primary source
    $orderTotal = (float)$order['total_amount'];
    
    // AGGRESSIVE: If calculated total differs significantly from DB total, use calculated total
    // This handles cases where cart was updated after order creation
    if ($calculatedTotal > 0 && abs($calculatedTotal - $orderTotal) > 0.5) {
        error_log(
            "[create_razorpay_order] MISMATCH FIX - Order " . $orderId . ": "
            . "DB total=" . $orderTotal . ", Calculated=" . $calculatedTotal . ", Using calculated"
        );
        // Update order total in DB to match calculated
        $updateStmt = $pdo->prepare("
            UPDATE orders SET total_amount = ?, updated_at = NOW() WHERE id = ?
        ");
        $updateStmt->execute([$calculatedTotal, $orderId]);
        $orderTotal = $calculatedTotal;
    }
    
    // Verify amount matches order total (with small tolerance for floating point)
    if (abs($orderTotal - $amount) > 0.01) {
        error_log(
            "[create_razorpay_order] AMOUNT MISMATCH - Order " . $orderId . ": "
            . "Expected=" . $orderTotal . " (DB=" . (float)$order['total_amount'] . ", Calc=" . $calculatedTotal . "), "
            . "Received=" . $amount
        );
        http_response_code(400);
        echo json_encode([
            'error' => 'Amount mismatch',
            'expected' => $orderTotal,
            'received' => $amount,
            'databaseTotal' => (float)$order['total_amount'],
            'calculatedTotal' => $calculatedTotal
        ]);
        exit;
    } else {
        error_log(
            "[create_razorpay_order] Amount verified - Order " . $orderId . ": "
            . "Amount=" . $amount . " matches Order Total=" . $orderTotal
        );
    }

    // Get Razorpay credentials from environment
    $razorpayKeyId = $_ENV['RAZORPAY_KEY_ID'] ?? '';
    $razorpayKeySecret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';

    if (empty($razorpayKeyId) || empty($razorpayKeySecret)) {
        http_response_code(500);
        echo json_encode(['error' => 'Payment gateway configuration error']);
        exit;
    }

    // Create Razorpay order
    // Convert amount to paise (Razorpay uses smallest currency unit)
    $amountInPaise = (int)($amount * 100);

    $razorpayOrderData = [
        'amount' => $amountInPaise,
        'currency' => strtoupper($currency),
        'receipt' => 'order_' . $orderId . '_' . time(),
        'notes' => [
            'order_id' => $orderId,
            'user_id' => $userId,
            'app_name' => 'Kuttysoora Seafood'
        ]
    ];

    // Make API call to Razorpay
    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($razorpayOrderData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, $razorpayKeyId . ':' . $razorpayKeySecret);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        error_log("Razorpay API Error: " . $curlError);
        http_response_code(500);
        echo json_encode(['error' => 'Payment gateway connection failed']);
        exit;
    }

    $razorpayResponse = json_decode($response, true);

    if ($httpCode !== 200 || !isset($razorpayResponse['id'])) {
        error_log("Razorpay Order Creation Failed: " . $response);
        http_response_code(500);
        echo json_encode([
            'error' => 'Failed to create payment order',
            'details' => $razorpayResponse['error']['description'] ?? 'Unknown error'
        ]);
        exit;
    }

    // Store Razorpay order details in database
    $razorpayOrderId = $razorpayResponse['id'];
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET razorpay_order_id = ?, 
            payment_status = 'initiated',
            updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$razorpayOrderId, $orderId]);

    // Log payment initiation
    $stmt = $pdo->prepare("
        INSERT INTO payment_logs 
        (order_id, razorpay_order_id, amount, currency, status, created_at) 
        VALUES (?, ?, ?, ?, 'initiated', NOW())
    ");
    $stmt->execute([$orderId, $razorpayOrderId, $amount, $currency]);

    // Return Razorpay order details to client
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'razorpay_order_id' => $razorpayOrderId,
        'amount' => $amountInPaise,
        'currency' => strtoupper($currency),
        'key_id' => $razorpayKeyId,
        'order_id' => $orderId,
        'notes' => $razorpayOrderData['notes']
    ]);

} catch (PDOException $e) {
    error_log("Database Error in create_razorpay_order: " . $e->getMessage());
    http_response_code(500);
    $response = ['error' => 'Database error occurred'];
    // Show details in development mode
    if ($_ENV['APP_DEBUG'] === 'true' || $_ENV['APP_ENV'] === 'development') {
        $response['details'] = $e->getMessage();
        $response['code'] = $e->getCode();
    }
    echo json_encode($response);
    exit;
} catch (Exception $e) {
    error_log("Error in create_razorpay_order: " . $e->getMessage());
    http_response_code(500);
    $response = ['error' => 'An error occurred while processing your request'];
    // Show details in development mode
    if ($_ENV['APP_DEBUG'] === 'true' || $_ENV['APP_ENV'] === 'development') {
        $response['details'] = $e->getMessage();
    }
    echo json_encode($response);
    exit;
}
?>
