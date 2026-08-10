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
// SECURITY: Role is always forced to 'user'. Admin accounts can ONLY be
// created by an existing admin via the admin endpoints (promote_user.php).
// Accepting a client-supplied role here would allow anyone to register as
// an administrator.
$role = 'user';
$createdAtInput = isset($data['created_at']) ? trim($data['created_at']) : '';

if (!$mobile || !$name) {
	http_response_code(400);
	echo json_encode(["error" => "Mobile and name required."]);
	exit;
}

if (!$createdAtInput) {
	http_response_code(400);
	echo json_encode(["error" => "created_at required."]);
	exit;
}

$createdAtDate = date_create($createdAtInput);
if (!$createdAtDate) {
	http_response_code(400);
	echo json_encode(["error" => "Invalid created_at format."]);
	exit;
}
$createdAt = $createdAtDate->format('Y-m-d H:i:s');

// Check if user already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE mobile = ?");
$stmt->execute([$mobile]);
$existingUser = $stmt->fetch();

if ($existingUser) {
	http_response_code(409);
	echo json_encode(["error" => "User already exists with this mobile number."]);
	exit;
}

// Insert user - always as regular 'user' role
$stmt = $pdo->prepare("INSERT INTO users (mobile, name, role, created_at) VALUES (?, ?, ?, ?)");
$stmt->execute([$mobile, $name, $role, $createdAt]);
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
	'created_at' => $createdAt
];

// Generate JWT token with role
$token = JWTAuth::generateToken($user['id'], $user['mobile'], $role);

echo json_encode([
	"user" => $user,
	"token" => $token,
	"expires_in" => 24 * 60 * 60 // 24 hours in seconds
]);
?>
