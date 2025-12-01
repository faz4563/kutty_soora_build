<?php
require_once 'db_config.php';

echo "=== FIXING PRODUCTS TABLE STRUCTURE ===\n\n";

try {
    // Step 1: Delete the problematic record with empty ID
    echo "Step 1: Deleting record with empty ID...\n";
    $pdo->exec("DELETE FROM products WHERE id = '' OR id IS NULL");
    echo "✓ Problematic records deleted\n\n";
    
    // Step 2: Change ID field from VARCHAR to INT AUTO_INCREMENT
    echo "Step 2: Converting ID field to INT AUTO_INCREMENT...\n";
    
    // First, drop primary key
    $pdo->exec("ALTER TABLE products DROP PRIMARY KEY");
    echo "✓ Dropped old PRIMARY KEY\n";
    
    // Modify ID column to INT AUTO_INCREMENT
    $pdo->exec("ALTER TABLE products MODIFY COLUMN id INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
    echo "✓ Changed ID to INT AUTO_INCREMENT PRIMARY KEY\n";
    
    // Step 3: Get max ID and set auto increment
    $stmt = $pdo->query("SELECT MAX(id) as max_id FROM products");
    $maxId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;
    $nextId = intval($maxId) + 1;
    $pdo->exec("ALTER TABLE products AUTO_INCREMENT = $nextId");
    echo "✓ Set AUTO_INCREMENT to $nextId\n\n";
    
    // Step 4: Verify the fix
    echo "Step 3: Verifying the fix...\n";
    $pdo->beginTransaction();
    $testStmt = $pdo->prepare("INSERT INTO products (name, description, price, category, stock, brand, sku, availability, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $testStmt->execute(['VERIFY_TEST', 'Test', 99.99, 'Test', 10, 'Test', 'VERIFY_999', 'in_stock', '']);
    $lastId = $pdo->lastInsertId();
    echo "✓ Test insert successful! New ID: $lastId\n";
    $pdo->rollBack();
    echo "✓ Test rolled back\n\n";
    
    echo "=== FIX COMPLETED SUCCESSFULLY ===\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
}
?>