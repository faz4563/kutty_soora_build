<?php
/**
 * Update cart and order_items quantity columns to support decimal values
 * This allows storing weights like 0.25kg (250g), 0.5kg (500g), etc.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Load environment variables manually
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

loadEnv(__DIR__ . '/../.env');

try {
    require_once __DIR__ . '/../db_config.php';
    require_once __DIR__ . '/../jwt_auth.php';

    // Require admin authentication
    $tokenPayload = JWTAuth::requireAuth();
    $user_role = $tokenPayload['role'] ?? null;

    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(["error" => "Admin access required"]);
        exit;
    }

    $updates = [];
    $errors = [];

    // Update cart table - change quantity from INT to DECIMAL(10,2)
    try {
        $stmt = $pdo->query("DESCRIBE cart quantity");
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($column) {
            // Check if it's already DECIMAL
            if (stripos($column['Type'], 'decimal') === false && stripos($column['Type'], 'float') === false) {
                $pdo->exec("ALTER TABLE cart MODIFY COLUMN quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00");
                $updates[] = "Updated cart.quantity to DECIMAL(10,2) to support weight-based quantities";
            } else {
                $updates[] = "cart.quantity already supports decimal values";
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error updating cart.quantity: " . $e->getMessage();
    }

    // Update order_items table - change quantity from INT to DECIMAL(10,2)
    try {
        $stmt = $pdo->query("DESCRIBE order_items quantity");
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($column) {
            // Check if it's already DECIMAL
            if (stripos($column['Type'], 'decimal') === false && stripos($column['Type'], 'float') === false) {
                $pdo->exec("ALTER TABLE order_items MODIFY COLUMN quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00");
                $updates[] = "Updated order_items.quantity to DECIMAL(10,2) to support weight-based quantities";
            } else {
                $updates[] = "order_items.quantity already supports decimal values";
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error updating order_items.quantity: " . $e->getMessage();
    }

    // Check current schema
    $cartSchema = [];
    $orderItemsSchema = [];

    try {
        $stmt = $pdo->query("DESCRIBE cart");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['Field'] === 'quantity') {
                $cartSchema = $row;
            }
        }

        $stmt = $pdo->query("DESCRIBE order_items");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['Field'] === 'quantity') {
                $orderItemsSchema = $row;
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error checking schema: " . $e->getMessage();
    }

    echo json_encode([
        "success" => count($errors) === 0,
        "updates" => $updates,
        "errors" => $errors,
        "current_schema" => [
            "cart_quantity" => $cartSchema,
            "order_items_quantity" => $orderItemsSchema
        ],
        "message" => count($errors) === 0 
            ? "Database schema updated successfully to support weight-based quantities" 
            : "Some errors occurred during schema update"
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Server error",
        "message" => $e->getMessage()
    ]);
}
?>
