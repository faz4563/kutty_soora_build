<?php
require_once 'db_config.php';

header('Content-Type: application/json');

try {
    // Get all orders
    $stmt = $pdo->query('SELECT id, user_id, customer_name, total_amount, status, created_at FROM orders ORDER BY id DESC LIMIT 10');
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'count' => count($orders),
        'orders' => $orders
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
