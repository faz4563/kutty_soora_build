<?php
require_once 'db_config.php';

header('Content-Type: application/json');

try {
    // Delete invalid order with id=0
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = 0 OR user_id = 0");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    
    // Fix AUTO_INCREMENT if needed
    $pdo->exec("ALTER TABLE orders MODIFY id INT(11) NOT NULL AUTO_INCREMENT");
    
    // Reset AUTO_INCREMENT to start from 1
    $pdo->exec("ALTER TABLE orders AUTO_INCREMENT = 1");
    
    echo json_encode([
        'success' => true,
        'deleted_invalid_orders' => $deleted,
        'message' => 'Orders table fixed successfully'
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
