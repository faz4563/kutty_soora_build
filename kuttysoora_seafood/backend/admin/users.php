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

// Make $pdo available globally for JWT authentication
$GLOBALS['pdo'] = $pdo;

$payload = JWTAuth::requireAdmin();

try {
    // Fetch all user information including all available fields
    $stmt = $pdo->prepare("SELECT id, name, email, mobile, role, is_admin, email_verified, mobile_verified, status, last_login, login_attempts, created_at, updated_at, address, house, street, area, city, pin_code, landmark, referral FROM users ORDER BY id DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();

    echo json_encode(["success" => true, "users" => $users]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

?>
