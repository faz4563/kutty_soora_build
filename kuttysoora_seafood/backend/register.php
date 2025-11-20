<?php
// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db_config.php';
require_once 'jwt_auth.php';

$data = json_decode(file_get_contents('php://input'), true);
$mobile = isset($data['mobile']) ? trim($data['mobile']) : '';
$name = isset($data['name']) ? trim($data['name']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$role = isset($data['role']) ? trim($data['role']) : 'user';
$password = isset($data['password']) ? $data['password'] : null;

if (!$mobile || !$name) {
	http_response_code(400);
	echo json_encode(["error" => "Mobile and name required."]);
	exit;
}

// If registering as admin, password is required
if (strtolower($role) === 'admin') {
	if (!$password) {
		http_response_code(400);
		echo json_encode(["error" => "Password required for admin registration."]);
		exit;
	}
}

// Check if user already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE mobile = ?");
$stmt->execute([$mobile]);
$existingUser = $stmt->fetch();

if ($existingUser) {
	http_response_code(409);
	echo json_encode(["error" => "User already exists with this mobile number."]);
	exit;
}

// Hash password if admin
$password_hash = null;
if (strtolower($role) === 'admin' && $password) {
	$password_hash = password_hash($password, PASSWORD_ARGON2ID);
}

// Insert user with password hash if admin
if ($password_hash) {
	$stmt = $pdo->prepare("INSERT INTO users (mobile, name, role, password_hash) VALUES (?, ?, ?, ?)");
	$stmt->execute([$mobile, $name, $role, $password_hash]);
} else {
	$stmt = $pdo->prepare("INSERT INTO users (mobile, name, role) VALUES (?, ?, ?)");
	$stmt->execute([$mobile, $name, $role]);
}
$user_id = $pdo->lastInsertId();

$user = [
	'id' => $user_id,
	'mobile' => $mobile,
	'name' => $name,
	'role' => $role,
	'address' => '',
	'house' => '',
	'street' => '',
	'area' => '',
	'city' => '',
	'pin_code' => '',
	'landmark' => '',
	'referral' => '',
	'created_at' => date('Y-m-d H:i:s')
];

// Generate JWT token
$token = JWTAuth::generateToken($user['id'], $user['mobile']);

echo json_encode([
	"user" => $user,
	"token" => $token,
	"expires_in" => 24 * 60 * 60 // 24 hours in seconds
]);
?>
