<?php
// Test endpoint to check orders without authentication
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../db_config.php';

try {
    // Check if orders table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $result = $stmt->fetch();
    $orderCount = $result['count'];
    
    // Get sample orders
    $stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.mobile as user_mobile FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5");
    $stmt->execute();
    $orders = $stmt->fetchAll();
    
    echo json_encode([
        "success" => true,
        "total_orders" => $orderCount,
        "sample_orders" => $orders,
        "message" => "Found $orderCount orders in database"
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage(),
        "message" => "Database error occurred"
    ]);
}
?>
