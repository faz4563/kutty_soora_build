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

require_once 'db_config.php';
require_once 'jwt_auth.php';

// Validate token and return user data
$payload = JWTAuth::requireAuth();

// Get user data from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$payload['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
    exit;
}

// Return user data with token validation success
echo json_encode([
    "valid" => true,
    "user" => $user,
    "token_expires_at" => date('Y-m-d H:i:s', $payload['exp'])
]);
?>