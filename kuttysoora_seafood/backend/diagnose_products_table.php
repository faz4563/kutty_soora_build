<?php
require_once 'db_config.php';

echo "=== PRODUCTS TABLE STRUCTURE ===\n";
$stmt = $pdo->query("DESCRIBE products");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo "{$col['Field']}: {$col['Type']} | Null: {$col['Null']} | Key: {$col['Key']} | Default: {$col['Default']} | Extra: {$col['Extra']}\n";
}

echo "\n=== CHECK FOR PROBLEMATIC RECORDS ===\n";
$check = $pdo->query("SELECT id, name FROM products WHERE id = '' OR id = 0 OR id IS NULL");
$bad = $check->fetchAll(PDO::FETCH_ASSOC);
if (count($bad) > 0) {
    echo "Found " . count($bad) . " problematic records:\n";
    print_r($bad);
} else {
    echo "No problematic records found.\n";
}

echo "\n=== AUTO_INCREMENT STATUS ===\n";
$status = $pdo->query("SHOW TABLE STATUS LIKE 'products'")->fetch(PDO::FETCH_ASSOC);
echo "Auto_increment value: " . $status['Auto_increment'] . "\n";
echo "Engine: " . $status['Engine'] . "\n";

echo "\n=== TEST INSERT (DRY RUN) ===\n";
try {
    $pdo->beginTransaction();
    $testStmt = $pdo->prepare("INSERT INTO products (name, description, price, category, stock, brand, sku, availability, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $testStmt->execute(['TEST_PRODUCT', 'Test Description', 99.99, 'Test', 10, 'Test Brand', 'TEST_SKU_999', 'in_stock', '']);
    $lastId = $pdo->lastInsertId();
    echo "Test insert successful! Last ID: $lastId\n";
    $pdo->rollBack();
    echo "Transaction rolled back (dry run).\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Test insert FAILED: " . $e->getMessage() . "\n";
}
?>