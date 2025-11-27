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
    
    // Insert test order with an order_number (unique) and actual schema columns
    $order_number = 'TEST-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_number, total_amount, subtotal, customer_name, customer_phone, delivery_address, payment_method, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $user['id'],
        $order_number,
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

    // Insert sample order_items for this test order using up to 2 products
    $stmt = $pdo->query("SELECT id, price FROM products ORDER BY id DESC LIMIT 2");
    $sampleProducts = $stmt->fetchAll();
    $insertedItems = [];
    if ($sampleProducts) {
        $stmtIns = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image_url, quantity, unit_price, total_price, discount_amount, tax_amount, product_sku, product_category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($sampleProducts as $p) {
            $qty = 1;
            $price = isset($p['price']) ? $p['price'] : 0;
            $pname = isset($p['name']) ? $p['name'] : '';
            $pimage = isset($p['image_url']) ? $p['image_url'] : '';
            $psku = isset($p['sku']) ? $p['sku'] : null;
            $pcat = isset($p['category']) ? $p['category'] : null;
            $total_price = $price * $qty;
            $stmtIns->execute([$orderId, $p['id'], $pname, $pimage, $qty, $price, $total_price, 0.00, 0.00, $psku, $pcat]);
            $insertedItems[] = ['product_id' => $p['id'], 'quantity' => $qty, 'unit_price' => $price, 'total_price' => $total_price];
        }
    }

    // Get total orders now
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $totalOrders = $stmt->fetch()['count'];

    echo json_encode([
        'success' => true,
        'message' => 'Test order inserted successfully (with sample items)',
        'order_id' => $orderId,
        'order' => $order,
        'inserted_items' => $insertedItems,
        'total_orders_now' => $totalOrders
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
