<?php
// Direct database check - NO authentication required
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'db_config.php';

try {
    // Check orders count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $ordersCount = $stmt->fetch()['count'];
    
    // Check users count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $usersCount = $stmt->fetch()['count'];
    
    // Check cart items count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM cart");
    $cartCount = $stmt->fetch()['count'];
    
    // Check products count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $productsCount = $stmt->fetch()['count'];
    
    // Get last 5 orders if any
    $stmt = $pdo->query("SELECT o.*, u.name as user_name, u.mobile FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5");
    $recentOrders = $stmt->fetchAll();
    
    // Get all users
    $stmt = $pdo->query("SELECT id, name, mobile, role FROM users");
    $users = $stmt->fetchAll();
    
    // Check database name
    $stmt = $pdo->query("SELECT DATABASE() as db_name");
    $dbName = $stmt->fetch()['db_name'];
    
    echo json_encode([
        'success' => true,
        'database' => $dbName,
        'counts' => [
            'orders' => $ordersCount,
            'users' => $usersCount,
            'cart_items' => $cartCount,
            'products' => $productsCount
        ],
        'recent_orders' => $recentOrders,
        'users' => $users
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
