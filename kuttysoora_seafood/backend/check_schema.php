<?php
// Check actual database schema
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'db_config.php';

try {
    // Get orders table structure
    $stmt = $pdo->query("DESCRIBE orders");
    $ordersSchema = $stmt->fetchAll();
    
    // Get order_items table structure
    $stmt = $pdo->query("DESCRIBE order_items");
    $orderItemsSchema = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'orders_table_schema' => $ordersSchema,
        'order_items_table_schema' => $orderItemsSchema
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
