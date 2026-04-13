<?php
// cancel_order.php - Standalone API for cancelling an order
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log('cancel_order.php script started');
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

// Load .env file for JWT_SECRET and other configs
loadEnv(__DIR__ . '/.env');
// Uncomment the next line to confirm script is running at all
// die('cancel_order.php reached start');
require_once __DIR__ . '/cors_headers.php';
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/jwt_auth.php';

header('Content-Type: application/json');

try {
    $tokenPayload = JWTAuth::requireAuth();
    $user_id = $tokenPayload['user_id'];
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Authentication failed', 'details' => $e->getMessage()]);
    exit;
}

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);
$order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;

if (!$order_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'order_id required']);
    exit;
}

// Adding aggressive logging and debugging
error_log('cancel_order.php: Received input: ' . $rawInput);

try {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();
    error_log('cancel_order.php: Order fetch result: ' . json_encode($order));

    if (!$order) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Order not found']);
        exit;
    }
    if ($order['status'] === 'cancelled') {
        echo json_encode(['success' => false, 'error' => 'Order already cancelled']);
        exit;
    }
    if ($order['status'] === 'delivered') {
        echo json_encode(['success' => false, 'error' => 'Cannot cancel delivered order']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled', payment_status = 'canceled', updated_at = NOW() WHERE id = ?");
    $result = $stmt->execute([$order_id]);
    error_log('cancel_order.php: Update result: ' . json_encode($result));

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
    } else {
        $errInfo = $stmt->errorInfo();
        error_log('cancel_order.php: Update error info: ' . json_encode($errInfo));
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to cancel order', 'details' => $errInfo]);
    }
} catch (Exception $e) {
    error_log('cancel_order.php: Exception: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Exception in cancel order', 'details' => $e->getMessage()]);
}
