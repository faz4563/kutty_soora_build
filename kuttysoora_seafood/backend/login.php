<?php
// CORS headers - must be first
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
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

// Load .env
if (!loadEnv(__DIR__ . '/.env')) {
    http_response_code(500);
    echo json_encode(["error" => "Configuration file not found"]);
    exit();
}

// Get DB credentials
$host = $_ENV['DB_HOST'] ?? '';
$db = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';

if (empty($host) || empty($db) || empty($user)) {
    http_response_code(500);
    echo json_encode(["error" => "Database configuration incomplete"]);
    exit();
}

// Connect to database
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database connection failed",
        "details" => $e->getMessage()
    ]);
    exit();
}

// Parse request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON"]);
    exit();
}

$mobile = isset($data['mobile']) ? trim($data['mobile']) : '';
$name = isset($data['name']) ? trim($data['name']) : '';

if (empty($mobile) || empty($name)) {
    http_response_code(400);
    echo json_encode(["error" => "Mobile and name required"]);
    exit();
}

// Check if user exists
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE mobile = ?");
    $stmt->execute([$mobile]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Create new user
        $placeholder_email = $mobile . "@placeholder.local";
        $stmt = $pdo->prepare(
            "INSERT INTO users (mobile, name, email, role, created_at) VALUES (?, ?, ?, 'user', NOW())"
        );
        $stmt->execute([$mobile, $name, $placeholder_email]);
        $user_id = $pdo->lastInsertId();
        
        $user = [
            'id' => (int)$user_id,
            'mobile' => $mobile,
            'name' => $name,
            'email' => $placeholder_email,
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
        // Update name if different
        if ($user['name'] !== $name) {
            $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
            $stmt->execute([$name, $user['id']]);
            $user['name'] = $name;
        }
        
        // Check admin password
        if (isset($user['role']) && strtolower($user['role']) === 'admin') {
            $password = isset($data['password']) ? $data['password'] : '';
            if (empty($password)) {
                http_response_code(401);
                echo json_encode(["error" => "Password required for admin"]);
                exit();
            }
            
            if (empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
                http_response_code(401);
                echo json_encode(["error" => "Invalid admin credentials"]);
                exit();
            }
        }
        
        // Convert numeric fields
        $user['id'] = (int)$user['id'];
    }
    
    // Use JWTAuth class for consistent token generation
    require_once __DIR__ . '/jwt_auth.php';
    
    $token = JWTAuth::generateToken(
        $user['id'],
        $user['mobile'],
        $user['role'] ?? 'user'
    );
    
    // Return success
    http_response_code(200);
    echo json_encode([
        'user' => $user,
        'token' => $token,
        'expires_in' => 24 * 60 * 60
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Database error",
        "details" => $e->getMessage()
    ]);
    exit();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Server error",
        "details" => $e->getMessage()
    ]);
    exit();
}
?>
