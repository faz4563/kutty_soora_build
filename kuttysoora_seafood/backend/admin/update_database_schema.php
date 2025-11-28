<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../db_config.php';
require_once '../jwt_auth.php';

try {
    // Verify admin token
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    $token = str_replace('Bearer ', '', $authHeader);
    
    if (!$token) {
        throw new Exception("No token provided");
    }
    
    $decoded = verifyToken($token);
    if (!$decoded || !$decoded['is_admin']) {
        throw new Exception("Admin access required");
    }
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $updates = [];
    
    // Check and add minimum_quantity field
    try {
        $stmt = $pdo->query("DESCRIBE products minimum_quantity");
        $fieldExists = $stmt->fetch() !== false;
        
        if (!$fieldExists) {
            $pdo->exec("ALTER TABLE products ADD COLUMN minimum_quantity VARCHAR(50) DEFAULT 'per_kg'");
            $updates[] = "Added minimum_quantity column to products table";
        } else {
            $updates[] = "minimum_quantity column already exists";
        }
    } catch (PDOException $e) {
        // Field doesn't exist, add it
        $pdo->exec("ALTER TABLE products ADD COLUMN minimum_quantity VARCHAR(50) DEFAULT 'per_kg'");
        $updates[] = "Added minimum_quantity column to products table";
    }
    
    // Verify the update
    $stmt = $pdo->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasMinimumQuantity = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'minimum_quantity') {
            $hasMinimumQuantity = true;
            break;
        }
    }
    
    echo json_encode([
        "success" => true,
        "updates_applied" => $updates,
        "minimum_quantity_field_exists" => $hasMinimumQuantity,
        "message" => "Database schema updated successfully"
    ]);
    
} catch (Exception $e) {
    error_log("Schema Update Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "error" => "Schema update failed: " . $e->getMessage()
    ]);
}
?>