<?php
require_once 'db_config.php';

echo "=== CHECKING PRODUCT DATA ===\n\n";

$stmt = $pdo->query("SELECT id, name, health_benefits, nutritional_info, product_uses FROM products WHERE id = 46 LIMIT 1");
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    echo "Product ID: " . $product['id'] . "\n";
    echo "Product Name: " . $product['name'] . "\n\n";
    
    echo "Health Benefits (raw):\n";
    var_dump($product['health_benefits']);
    echo "\n";
    
    echo "Nutritional Info (raw):\n";
    var_dump($product['nutritional_info']);
    echo "\n";
    
    echo "Product Uses (raw):\n";
    var_dump($product['product_uses']);
    echo "\n";
} else {
    echo "No product found\n";
}
?>