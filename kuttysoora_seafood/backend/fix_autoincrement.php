<?php
require_once 'db_config.php';

// Get the highest existing ID
$stmt = $pdo->query('SELECT MAX(id) as max_id FROM products');
$maxId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;
$nextId = $maxId + 1;

// Reset auto increment
$pdo->exec("ALTER TABLE products AUTO_INCREMENT = $nextId");
echo "Auto increment reset to: $nextId\n";

// Also check for any products with empty or NULL ids
$nullIds = $pdo->query('SELECT COUNT(*) as count FROM products WHERE id IS NULL OR id = ""')->fetch(PDO::FETCH_ASSOC);
echo "Products with NULL/empty IDs: " . $nullIds['count'] . "\n";
?>