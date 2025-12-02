<?php
// Delete user - Admin only
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

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
loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../jwt_auth.php';

// Make $pdo available globally for JWT authentication
$GLOBALS['pdo'] = $pdo;

// Verify admin access
$payload = JWTAuth::requireAdmin();
$adminUserId = $payload['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Method not allowed"]);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['user_id'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "User ID is required"]);
        exit();
    }
    
    $userIdToDelete = (int)$input['user_id'];
    
    // Prevent admin from deleting themselves
    if ($userIdToDelete === $adminUserId) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "Cannot delete your own account"]);
        exit();
    }
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, name, role FROM users WHERE id = ?");
    $stmt->execute([$userIdToDelete]);
    $userToDelete = $stmt->fetch();
    
    if (!$userToDelete) {
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "User not found"]);
        exit();
    }
    
    // Start transaction for safe deletion
    $pdo->beginTransaction();
    
    try {
        // Delete user's orders first (to maintain referential integrity)
        $stmt = $pdo->prepare("DELETE FROM orders WHERE user_id = ?");
        $stmt->execute([$userIdToDelete]);
        
        // Delete the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userIdToDelete]);
        
        $pdo->commit();
        
        echo json_encode([
            "success" => true,
            "message" => "User deleted successfully",
            "deleted_user" => [
                "id" => $userToDelete['id'],
                "name" => $userToDelete['name'],
                "role" => $userToDelete['role']
            ]
        ]);
        
    } catch (Exception $e) {
        $pdo->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Failed to delete user: " . $e->getMessage()
    ]);
}
?>