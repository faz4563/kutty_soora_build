<?php
// Promote a user to admin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../jwt_auth.php';

$payload = JWTAuth::requireAdmin();

$data = json_decode(file_get_contents('php://input'), true);
$userId = isset($data['user_id']) ? intval($data['user_id']) : null;
$password = isset($data['password']) ? $data['password'] : null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "user_id required"]);
    exit;
}

try {
    if ($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET role = 'admin', password_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
        $stmt->execute([$userId]);
    }

    // Log admin action
    $adminId = isset($payload['user_id']) ? $payload['user_id'] : 'unknown';
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $entry = date('c') . " | admin:$adminId | action:promote | target:$userId | ip:$ip" . PHP_EOL;
    @file_put_contents(__DIR__ . '/actions.log', $entry, FILE_APPEND | LOCK_EX);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

?>
