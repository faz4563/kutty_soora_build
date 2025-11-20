<?php
// Run database schema updates
require_once 'db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $pdo = getConnection();
    
    // Add new columns if they don't exist
    $pdo->exec("ALTER TABLE products 
                ADD COLUMN IF NOT EXISTS minimum_quantity VARCHAR(50) DEFAULT NULL AFTER availability");
    
    $pdo->exec("ALTER TABLE products 
                ADD COLUMN IF NOT EXISTS is_special BOOLEAN DEFAULT 0 AFTER minimum_quantity");
    
    $pdo->exec("ALTER TABLE products 
                ADD COLUMN IF NOT EXISTS is_dry BOOLEAN DEFAULT 0 AFTER is_special");
    
    // Update some products to have pre-order status
    $pdo->exec("UPDATE products 
                SET availability = 'pre_order' 
                WHERE id IN (1, 4, 8) 
                LIMIT 3");
    
    // Update some products to be out of stock
    $pdo->exec("UPDATE products 
                SET availability = 'out_of_stock' 
                WHERE id IN (2, 6) 
                LIMIT 2");
    
    // Add minimum quantities for shellfish
    $pdo->exec("UPDATE products 
                SET minimum_quantity = '500g' 
                WHERE category IN ('Crab', 'Prawns', 'Lobster')");
    
    // Mark some as special seafoods
    $pdo->exec("UPDATE products 
                SET is_special = 1 
                WHERE id IN (1, 8)");
    
    // Check if dry seafood exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE category = 'Dry Seafood'");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Add sample dry seafood products
        $pdo->exec("INSERT INTO products (name, category, description, price, stock, image_url, availability, is_dry, minimum_quantity) VALUES
                    ('Dried Prawns', 'Dry Seafood', 'Premium quality dried prawns', 350.00, 20, 'images/dried_prawns.jpg', 'in_stock', 1, '250g'),
                    ('Dried Fish (Karuvadu)', 'Dry Seafood', 'Traditional sun-dried fish', 280.00, 15, 'images/dried_fish.jpg', 'in_stock', 1, '250g'),
                    ('Anchovy Dry', 'Dry Seafood', 'Small dried anchovies', 220.00, 25, 'images/anchovy.jpg', 'in_stock', 1, '200g')");
    }
    
    // Ensure all products have proper stock status based on availability
    $pdo->exec("UPDATE products SET stock = 0 WHERE availability = 'out_of_stock'");
    $pdo->exec("UPDATE products SET stock = GREATEST(stock, 5) WHERE availability = 'in_stock'");
    $pdo->exec("UPDATE products SET stock = GREATEST(stock, 3) WHERE availability = 'pre_order'");
    
    echo json_encode([
        'success' => true,
        'message' => 'Database schema updated successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
