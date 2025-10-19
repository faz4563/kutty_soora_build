<?php
// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db_config.php';
require_once 'jwt_auth.php';

// Validate JWT token and get user info
$tokenPayload = JWTAuth::requireAuth();
$authenticated_user_id = $tokenPayload['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : 'list';

// Use authenticated user ID instead of user_id from request
$user_id = $authenticated_user_id;

if ($action === 'place') {
	// Get additional order data
	$delivery_address = isset($data['delivery_address']) ? $data['delivery_address'] : '';
	$phone_number = isset($data['phone_number']) ? $data['phone_number'] : '';
	$notes = isset($data['notes']) ? $data['notes'] : '';
	
	// Get user's name for the order
	$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
	$stmt->execute([$user_id]);
	$user = $stmt->fetch();
	$user_name = $user ? $user['name'] : 'Unknown';
	
	// Get cart items
	$stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
	$stmt->execute([$user_id]);
	$cart_items = $stmt->fetchAll();
	if (!$cart_items) {
		http_response_code(400);
		echo json_encode(["error" => "Cart is empty"]);
		exit;
	}
	$total = 0;
	foreach ($cart_items as $item) {
		$total += $item['price'] * $item['quantity'];
	}
	// Create order with correct column names
	$stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, name, address, phone) VALUES (?, ?, ?, ?, ?)");
	$stmt->execute([$user_id, $total, $user_name, $delivery_address, $phone_number]);
	$order_id = $pdo->lastInsertId();
	// Insert order items
	$stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
	foreach ($cart_items as $item) {
		$stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
	}
	// Clear cart
	$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
	$stmt->execute([$user_id]);
	
	// Return order details
	$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
	$stmt->execute([$order_id]);
	$order = $stmt->fetch();
	
	echo json_encode(["success" => true, "order_id" => $order_id, "order" => $order]);
	exit;
} elseif ($action === 'cancel') {
	$order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;
	if (!$order_id) {
		http_response_code(400);
		echo json_encode(["error" => "order_id required"]);
		exit;
	}
	
	// Check if order belongs to user and can be cancelled
	$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? AND status != 'cancelled'");
	$stmt->execute([$order_id, $user_id]);
	$order = $stmt->fetch();
	
	if (!$order) {
		http_response_code(404);
		echo json_encode(["error" => "Order not found or cannot be cancelled"]);
		exit;
	}
	
	// Update order status to cancelled
	$stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
	$stmt->execute([$order_id]);
	
	echo json_encode(["success" => true]);
	exit;
}

// List orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
foreach ($orders as &$order) {
	$stmt2 = $pdo->prepare("SELECT oi.*, p.name, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
	$stmt2->execute([$order['id']]);
	$order['items'] = $stmt2->fetchAll();
}
echo json_encode(["orders" => $orders]);
?>
