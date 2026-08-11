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
    // SECURITY: Require admin authentication. This validates the JWT and
    // verifies the admin role (from the token payload AND the database).
    // Note: previously this checked $decoded['is_admin'], but tokens carry
    // a 'role' claim — that check silently failed, locking admins out.
    JWTAuth::requireAdmin();

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS['pdo'] = $pdo;
    
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
    
    // Check and add price_unit field
    try {
        $stmt = $pdo->query("DESCRIBE products price_unit");
        $fieldExists = $stmt->fetch() !== false;
        
        if (!$fieldExists) {
            $pdo->exec("ALTER TABLE products ADD COLUMN price_unit VARCHAR(20) DEFAULT 'per_kg'");
            $updates[] = "Added price_unit column to products table";
        } else {
            $updates[] = "price_unit column already exists";
        }
    } catch (PDOException $e) {
        // Field doesn't exist, add it
        $pdo->exec("ALTER TABLE products ADD COLUMN price_unit VARCHAR(20) DEFAULT 'per_kg'");
        $updates[] = "Added price_unit column to products table";
    }
    
    // Create the banners table if it doesn't exist (home-page offer carousel)
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'banners'");
        $tableExists = $stmt->fetch() !== false;
        
        if (!$tableExists) {
            $pdo->exec("CREATE TABLE banners (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                subtitle VARCHAR(500) DEFAULT '',
                category VARCHAR(100) DEFAULT '',
                product_id INT UNSIGNED NULL,
                images TEXT NULL,
                old_price DECIMAL(10,2) NULL,
                offer_price DECIMAL(10,2) NULL,
                discount_label VARCHAR(50) DEFAULT '',
                is_active TINYINT(1) DEFAULT 1,
                sort_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_banners_active (is_active),
                KEY idx_banners_category (category)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $updates[] = "Created banners table (banner / offer carousel)";
        } else {
            $updates[] = "banners table already exists";
        }
    } catch (PDOException $e) {
        $updates[] = "banners table check skipped: " . $e->getMessage();
    }
    
    // Verify the update
    $stmt = $pdo->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasMinimumQuantity = false;
    $hasPriceUnit = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'minimum_quantity') {
            $hasMinimumQuantity = true;
        }
        if ($column['Field'] === 'price_unit') {
            $hasPriceUnit = true;
        }
    }
    
    echo json_encode([
        "success" => true,
        "updates_applied" => $updates,
        "minimum_quantity_field_exists" => $hasMinimumQuantity,
        "price_unit_field_exists" => $hasPriceUnit,
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