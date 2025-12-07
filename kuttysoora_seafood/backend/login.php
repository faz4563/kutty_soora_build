<?php
/**
 * Secure Login API with Enhanced Security
 * - Rate limiting
 * - IP blacklist checking
 * - Input validation
 * - Security logging
 * - Brute force protection
 */

// CORS headers - must be first
require_once 'cors_headers.php';

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

// Initialize security manager
require_once __DIR__ . '/security_manager.php';
require_once __DIR__ . '/input_validator.php';
SecurityManager::init($pdo);

// Check IP blacklist
$clientIP = SecurityManager::getClientIP();
if (SecurityManager::isIPBlacklisted($clientIP)) {
    http_response_code(403);
    echo json_encode(["error" => "Access denied. Your IP has been blocked due to security violations."]);
    SecurityManager::logSecurityEvent($clientIP, null, 'blocked_access_attempt', 'warning', 
        'Blocked IP attempted to access login');
    exit();
}

// Check rate limiting (10 requests per minute for login)
if (!SecurityManager::checkRateLimit('/login.php', 10, 100)) {
    http_response_code(429);
    echo json_encode(["error" => "Too many requests. Please try again later."]);
    exit();
}

// Parse request body
$input = file_get_contents('php://input');
try {
    $data = InputValidator::validateJSON($input);
    InputValidator::validateRequiredFields($data, ['mobile', 'name']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}

// Validate and sanitize inputs
try {
    $mobile = InputValidator::validatePhone($data['mobile']);
    $name = InputValidator::validateName($data['name']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
    SecurityManager::logSecurityEvent($clientIP, null, 'invalid_input', 'warning', 
        'Invalid input in login: ' . $e->getMessage());
    exit();
}

// Check if account is locked due to failed attempts
if (SecurityManager::isAccountLocked($mobile)) {
    http_response_code(403);
    echo json_encode(["error" => "Account temporarily locked due to multiple failed login attempts. Please try again after 15 minutes."]);
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
                SecurityManager::recordFailedLogin($mobile, $clientIP);
                http_response_code(401);
                echo json_encode(["error" => "Password required for admin"]);
                exit();
            }
            
            if (empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
                SecurityManager::recordFailedLogin($mobile, $clientIP);
                SecurityManager::logSecurityEvent($clientIP, $user['id'], 'admin_login_failed', 'warning', 
                    'Failed admin login attempt');
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
    
    // Clear failed login attempts on successful login
    SecurityManager::clearFailedLogins($mobile, $clientIP);
    
    // Log successful login
    SecurityManager::logSecurityEvent($clientIP, $user['id'], 'login_success', 'info', 
        'User logged in successfully', ['mobile' => $mobile, 'role' => $user['role']]);
    
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
