<?php
// Public endpoint: check if a given mobile number is an admin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db_config.php';

$data = json_decode(file_get_contents('php://input'), true);
$mobile = isset($data['mobile']) ? trim($data['mobile']) : '';

if (!$mobile) {
    http_response_code(400);
    echo json_encode(["error" => "mobile required"]);
    exit;
}

$stmt = $pdo->prepare("SELECT role FROM users WHERE mobile = ?");
$stmt->execute([$mobile]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["is_admin" => false]);
    exit;
}

echo json_encode(["is_admin" => (isset($user['role']) && strtolower($user['role']) === 'admin')]);

?>
