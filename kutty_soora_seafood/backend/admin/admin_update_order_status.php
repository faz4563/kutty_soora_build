<?php
// Update order status (admin only)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

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
loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../jwt_auth.php';

// Make $pdo available globally for JWT authentication
$GLOBALS['pdo'] = $pdo;

$payload = JWTAuth::requireAdmin();

$data = json_decode(file_get_contents('php://input'), true);
$orderId = isset($data['order_id']) ? intval($data['order_id']) : null;
$status = isset($data['status']) ? trim($data['status']) : null;

if (!$orderId || !$status) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "order_id and status required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $orderId]);

    // Log admin action
    $adminId = isset($payload['user_id']) ? $payload['user_id'] : 'unknown';
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $entry = date('c') . " | admin:$adminId | action:update_order_status | target:$orderId | status:$status | ip:$ip" . PHP_EOL;
    @file_put_contents(__DIR__ . '/actions.log', $entry, FILE_APPEND | LOCK_EX);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

?>
