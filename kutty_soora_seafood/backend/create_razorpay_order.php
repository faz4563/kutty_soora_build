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
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables manually (no composer dependency)
function loadEnv($file) {
    if (!file_exists($file)) {
        return false;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
    return true;
}

// Load .env file for JWT_SECRET and RAZORPAY credentials
loadEnv(__DIR__ . '/.env');

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
$createdAtInput = isset($input['created_at']) ? trim($input['created_at']) : '';

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

if (empty($createdAtInput)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required field: created_at']);
    exit;
}

$createdAtDate = date_create($createdAtInput);
if (!$createdAtDate) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid created_at format']);
    exit;
}
$createdAt = $createdAtDate->format('Y-m-d H:i:s');

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

    // Verify amount matches order total (with small tolerance for floating point)
    $orderTotal = (float)$order['total_amount'];
    if (abs($orderTotal - $amount) > 0.01) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Amount mismatch',
            'expected' => $orderTotal,
            'received' => $amount
        ]);
        exit;
    }

    // Get Razorpay credentials based on mode sent by the app
    // Debug builds send 'test', release builds send 'production'
    $mode = isset($input['mode']) ? strtolower(trim($input['mode'])) : '';
    if (!in_array($mode, ['test', 'production', 'live'], true)) {
        $mode = ''; // unknown / not provided -> infer below
    }
    if ($mode === 'live') {
        $mode = 'production';
    }

    $defaultKey = $_ENV['RAZORPAY_KEY_ID'] ?? '';

    if ($mode === 'test') {
        // STRICTLY use test keys. Never fall back to a live key.
        $razorpayKeyId = $_ENV['RAZORPAY_TEST_KEY_ID'] ?? '';
        $razorpayKeySecret = $_ENV['RAZORPAY_TEST_KEY_SECRET'] ?? '';
        // Fall back to the generic key ONLY if it is actually a test key
        if (empty($razorpayKeyId) && strpos($defaultKey, 'rzp_test_') === 0) {
            $razorpayKeyId = $defaultKey;
        }
        if (empty($razorpayKeySecret) && !empty($razorpayKeyId)) {
            $razorpayKeySecret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';
        }
    } elseif ($mode === 'production') {
        // STRICTLY use live keys. Never fall back to a test key.
        $razorpayKeyId = $_ENV['RAZORPAY_LIVE_KEY_ID'] ?? '';
        $razorpayKeySecret = $_ENV['RAZORPAY_LIVE_KEY_SECRET'] ?? '';
        // Fall back to the generic key ONLY if it is actually a live key
        if (empty($razorpayKeyId) && strpos($defaultKey, 'rzp_live_') === 0) {
            $razorpayKeyId = $defaultKey;
        }
        if (empty($razorpayKeySecret) && !empty($razorpayKeyId)) {
            $razorpayKeySecret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';
        }
    } else {
        // Mode not provided: infer from the default key
        $razorpayKeyId = '';
        $razorpayKeySecret = '';
        if (strpos($defaultKey, 'rzp_test_') === 0) {
            $razorpayKeyId = $defaultKey;
            $razorpayKeySecret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';
            $mode = 'test';
        } elseif (strpos($defaultKey, 'rzp_live_') === 0) {
            $razorpayKeyId = $defaultKey;
            $razorpayKeySecret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';
            $mode = 'production';
        }
    }

    if (empty($razorpayKeyId) || empty($razorpayKeySecret)) {
        error_log("No Razorpay credentials configured for mode: " . ($mode ?: 'unknown'));
        http_response_code(500);
        $details = ($mode === 'test')
            ? 'Razorpay TEST keys missing. Add RAZORPAY_TEST_KEY_ID and RAZORPAY_TEST_KEY_SECRET to backend/.env'
            : (($mode === 'production')
                ? 'Razorpay LIVE keys missing. Add RAZORPAY_LIVE_KEY_ID and RAZORPAY_LIVE_KEY_SECRET to backend/.env'
                : 'Razorpay keys missing. Add RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET to backend/.env');
        echo json_encode([
            'error' => 'Payment gateway configuration error',
            'details' => $details,
            'mode' => $mode ?: 'unknown'
        ]);
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
        VALUES (?, ?, ?, ?, 'initiated', ?)
    ");
    $stmt->execute([$orderId, $razorpayOrderId, $amount, $currency, $createdAt]);

    // Return Razorpay order details to client
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'razorpay_order_id' => $razorpayOrderId,
        'amount' => $amountInPaise,
        'currency' => strtoupper($currency),
        'key_id' => $razorpayKeyId,
        'order_id' => $orderId,
        'mode' => $mode,
        'is_test_mode' => ($mode === 'test'),
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