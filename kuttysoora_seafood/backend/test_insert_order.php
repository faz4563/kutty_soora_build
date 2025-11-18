<?php
// Test order insertion - NO authentication required
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'db_config.php';

try {
    // Get first user
    $stmt = $pdo->query("SELECT id, name FROM users LIMIT 1");
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'No users in database']);
        exit;
    }
    
    // Insert test order with actual schema columns
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, subtotal, customer_name, customer_phone, delivery_address, payment_method, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $user['id'], 
        999.99,
        999.99,
        $user['name'], 
        '1234567890',
        'Test Address 123',
        'cash_on_delivery',
        'pending',
        'pending'
    ]);
    
    $orderId = $pdo->lastInsertId();
    
    // Verify it was inserted
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    // Get total orders now
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $totalOrders = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Test order inserted successfully',
        'order_id' => $orderId,
        'order' => $order,
        'total_orders_now' => $totalOrders
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
