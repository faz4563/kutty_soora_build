<?php
// List users for admin management
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../jwt_auth.php';

$payload = JWTAuth::requireAdmin();

try {
    $stmt = $pdo->prepare("SELECT id, mobile, name, role, created_at FROM users ORDER BY id DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();

    echo json_encode(["success" => true, "users" => $users]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

?>
