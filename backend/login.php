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

// Only allow POST method for actual login
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed. Only POST is supported."]);
    exit();
}

require_once 'db_config.php';
require_once 'jwt_auth.php';

$data = json_decode(file_get_contents('php://input'), true);
$mobile = isset($data['mobile']) ? trim($data['mobile']) : '';
$name = isset($data['name']) ? trim($data['name']) : '';
if (!$mobile || !$name) {
	http_response_code(400);
	echo json_encode(["error" => "Mobile and name required."]);
	exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE mobile = ?");
$stmt->execute([$mobile]);
$user = $stmt->fetch();
if (!$user) {
	// Create user
	// default role will be 'user' as per schema
	$stmt = $pdo->prepare("INSERT INTO users (mobile, name, role) VALUES (?, ?, 'user')");
	$stmt->execute([$mobile, $name]);
	$user_id = $pdo->lastInsertId();
	$user = [
		'id' => $user_id,
		'mobile' => $mobile,
		'name' => $name,
		'role' => 'user',
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
} else {
	// Update name if changed
	if ($user['name'] !== $name) {
		$stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
		$stmt->execute([$name, $user['id']]);
		$user['name'] = $name;
	}

	// If the user is admin, require password
	if (isset($user['role']) && strtolower($user['role']) === 'admin') {
		$password = isset($data['password']) ? $data['password'] : null;
		if (!$password) {
			http_response_code(401);
			echo json_encode(["error" => "Password required for admin login"]);
			exit;
		}

		// Ensure password_hash exists and verify
		if (empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
			http_response_code(401);
			echo json_encode(["error" => "Invalid admin credentials"]);
			exit;
		}
	}
}

// Generate JWT token
$token = JWTAuth::generateToken($user['id'], $user['mobile'], isset($user['role']) ? $user['role'] : null);

echo json_encode([
	"user" => $user,
	"token" => $token,
	"expires_in" => 24 * 60 * 60 // 24 hours in seconds
]);
?>
