<?php
// List all orders for admin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../jwt_auth.php';

$payload = JWTAuth::requireAdmin();

try {
    $stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.mobile as user_mobile FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll();

    foreach ($orders as &$order) {
        $stmt2 = $pdo->prepare("SELECT oi.*, p.name, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt2->execute([$order['id']]);
        $order['items'] = $stmt2->fetchAll();
    }

    echo json_encode(["success" => true, "orders" => $orders]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

?>
