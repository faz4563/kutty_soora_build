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

$rawInput = file_get_contents('php://input');
error_log("Orders.php - Raw input: " . $rawInput);

$data = json_decode($rawInput, true);
error_log("Orders.php - Decoded data: " . json_encode($data));

$action = isset($data['action']) ? $data['action'] : 'list';
error_log("Orders.php - Action: $action");

// Use authenticated user ID instead of user_id from request
$user_id = $authenticated_user_id;
error_log("Orders.php - Authenticated user ID: $user_id");

if ($action === 'place') {
	// Get additional order data
	$delivery_address = isset($data['delivery_address']) ? $data['delivery_address'] : '';
	$phone_number = isset($data['phone_number']) ? $data['phone_number'] : '';
	$notes = isset($data['notes']) ? $data['notes'] : '';
	
	error_log("Orders.php - Place order details:");
	error_log("  delivery_address: '$delivery_address'");
	error_log("  phone_number: '$phone_number'");
	error_log("  notes: '$notes'");
	
	// Get user's name for the order
	try {
		$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
		$stmt->execute([$user_id]);
		$user = $stmt->fetch();
		$user_name = $user ? $user['name'] : 'Unknown';
		error_log("Orders.php - User name: $user_name");
		
		// Get cart items
		$stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
		$stmt->execute([$user_id]);
		$cart_items = $stmt->fetchAll();
		error_log("Orders.php - Cart items count: " . count($cart_items));
		
		if (!$cart_items) {
			error_log("Orders.php - ERROR: Cart is empty");
			http_response_code(400);
			echo json_encode(["success" => false, "error" => "Cart is empty"]);
			exit;
		}
		
		$total = 0;
		foreach ($cart_items as $item) {
			$total += $item['price'] * $item['quantity'];
		}
		error_log("Orders.php - Total calculated: $total");
		
		// Generate order number
		$order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
		
		// Create order with actual database column names
		error_log("Orders.php - Attempting to insert order: user_id=$user_id, total=$total, customer_name=$user_name, delivery_address=$delivery_address, customer_phone=$phone_number, order_number=$order_number");
		$stmt = $pdo->prepare("INSERT INTO orders (user_id, order_number, total_amount, subtotal, customer_name, customer_phone, delivery_address, payment_method, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->execute([$user_id, $order_number, $total, $total, $user_name, $phone_number, $delivery_address, 'cash_on_delivery', 'pending', 'pending']);
		$order_id = $pdo->lastInsertId();
		error_log("Orders.php - Order inserted successfully - Order ID: $order_id, Order Number: $order_number");
	} catch (PDOException $e) {
		error_log("Orders.php - DATABASE ERROR: " . $e->getMessage());
		http_response_code(500);
		echo json_encode(["success" => false, "error" => "Database error: " . $e->getMessage()]);
		exit;
	}
	// Insert order items
	try {
		$stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
		foreach ($cart_items as $item) {
			$stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
			error_log("Orders.php - Inserted order item: product_id={$item['product_id']}, quantity={$item['quantity']}, price={$item['price']}");
		}
		
		// Clear cart
		$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
		$stmt->execute([$user_id]);
		error_log("Orders.php - Cart cleared for user_id=$user_id");
		
		// Return order details
		$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
		$stmt->execute([$order_id]);
		$order = $stmt->fetch();
		
		error_log("Orders.php - Order placed successfully, returning order: " . json_encode($order));
		echo json_encode(["success" => true, "order_id" => $order_id, "order" => $order]);
		exit;
	} catch (PDOException $e) {
		error_log("Orders.php - ERROR inserting order items: " . $e->getMessage());
		http_response_code(500);
		echo json_encode(["success" => false, "error" => "Failed to create order items: " . $e->getMessage()]);
		exit;
	}
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
