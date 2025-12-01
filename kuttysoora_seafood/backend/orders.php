<?php
// Include comprehensive CORS configuration
require_once __DIR__ . '/cors_headers.php';

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
		
		// Get cart items (product price will be looked up per-item to avoid join issues)
		$stmt = $pdo->prepare("SELECT c.product_id, c.quantity FROM cart c WHERE c.user_id = ?");
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
		// Lookup product price per item and compute total
		$priceStmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
		foreach ($cart_items as $idx => &$item) {
			$priceStmt->execute([$item['product_id']]);
			$prod = $priceStmt->fetch();
			$item_price = $prod && isset($prod['price']) ? $prod['price'] : 0;
			$item['price'] = $item_price;
			$total += $item_price * $item['quantity'];
		}
		unset($item);
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
		// Insert into order_items using the actual schema (product_name, product_image_url, unit_price, total_price)
		$stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image_url, quantity, unit_price, total_price, discount_amount, tax_amount, product_sku, product_category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$prodStmt = $pdo->prepare("SELECT id, name, image_url, sku, category, price FROM products WHERE id = ?");
		foreach ($cart_items as $item) {
			try {
				// Ensure we have product info
				$prodStmt->execute([$item['product_id']]);
				$prod = $prodStmt->fetch();
				$pname = $prod && isset($prod['name']) ? $prod['name'] : '';
				$pimage = $prod && isset($prod['image_url']) ? $prod['image_url'] : '';
				$psku = $prod && isset($prod['sku']) ? $prod['sku'] : null;
				$pcat = $prod && isset($prod['category']) ? $prod['category'] : null;
				$unit_price = $item['price'];
				$total_price = $unit_price * $item['quantity'];
				$stmt->execute([
					$order_id,
					$item['product_id'],
					$pname,
					$pimage,
					$item['quantity'],
					$unit_price,
					$total_price,
					0.00,
					0.00,
					$psku,
					$pcat
				]);
				error_log("Orders.php - Inserted order item: product_id={$item['product_id']}, quantity={$item['quantity']}, unit_price={$unit_price}");
			} catch (PDOException $ie) {
				error_log("Orders.php - Ignored error inserting item for product_id={$item['product_id']}: " . $ie->getMessage());
			}
		}

		
		// Clear cart
		$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
		$stmt->execute([$user_id]);
		error_log("Orders.php - Cart cleared for user_id=$user_id");
		
		// Return order details with items
		$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
		$stmt->execute([$order_id]);
		$order = $stmt->fetch();
		
		// Fetch order items
		$stmt2 = $pdo->prepare("SELECT oi.*, COALESCE(NULLIF(oi.product_name, ''), p.name) AS name, COALESCE(NULLIF(oi.product_image_url, ''), p.image_url) AS image_url, CASE WHEN oi.unit_price > 0 THEN oi.unit_price ELSE p.price END AS price FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
		$stmt2->execute([$order_id]);
		$items = $stmt2->fetchAll();
		
		// Normalize items to always be an indexed array and cast types
		if (!is_array($items)) {
			$items = [];
		} else {
			foreach ($items as &$it) {
				if (isset($it['quantity'])) {
					$it['quantity'] = (int)$it['quantity'];
				}
				if (isset($it['price'])) {
					$it['price'] = (float)$it['price'];
				}
				$it['name'] = (isset($it['name']) && $it['name'] !== null && $it['name'] !== '') ? $it['name'] : ('Item #' . ($it['product_id'] ?? ''));
				$img = isset($it['image_url']) ? trim($it['image_url']) : '';
				if ($img === '') {
					$it['image_url'] = '';
				} else if (!preg_match('/^https?:\\/\\//', $img)) {
					$img = preg_replace('/^(images\\/)+/', '', $img);
					$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
					$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
					$it['image_url'] = $protocol . '://' . $host . '/kuttysoora_seafood/backend/images/' . $img;
				} else {
					if (strpos($img, 'http://kuttysoora.com') === 0) {
						$img = preg_replace('/^http:\\/\\//', 'https://', $img);
					}
					$it['image_url'] = $img;
				}
			}
			unset($it);
			$items = array_values($items);
		}
		
		$order['items'] = $items;
		
		error_log("Orders.php - Order placed successfully with " . count($items) . " items, returning order: " . json_encode($order));
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
		$stmt2 = $pdo->prepare("SELECT oi.*, COALESCE(NULLIF(oi.product_name, ''), p.name) AS name, COALESCE(NULLIF(oi.product_image_url, ''), p.image_url) AS image_url, CASE WHEN oi.unit_price > 0 THEN oi.unit_price ELSE p.price END AS price FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
			$stmt2->execute([$order['id']]);
			$items = $stmt2->fetchAll();
			// Debug: log raw fetched items for this order to server logs
			error_log("Orders.php - Raw fetched items for order " . $order['id'] . ": " . json_encode($items));

			// Normalize items to always be an indexed array and cast types
			if (!is_array($items)) {
				$items = [];
			} else {
				foreach ($items as &$it) {
					if (isset($it['quantity'])) {
						$it['quantity'] = (int)$it['quantity'];
					}
					if (isset($it['price'])) {
						$it['price'] = (float)$it['price'];
					}
					$it['name'] = (isset($it['name']) && $it['name'] !== null && $it['name'] !== '') ? $it['name'] : ('Item #' . ($it['product_id'] ?? ''));
					$img = isset($it['image_url']) ? trim($it['image_url']) : '';
					if ($img === '') {
						$it['image_url'] = '';
					} else if (!preg_match('/^https?:\/\//', $img)) {
						$img = preg_replace('/^(images\/)+/', '', $img);
						$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
						$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
						$it['image_url'] = $protocol . '://' . $host . '/kuttysoora_seafood/backend/images/' . $img;
					} else {
						if (strpos($img, 'http://kuttysoora.com') === 0) {
							$img = preg_replace('/^http:\/\//', 'https://', $img);
						}
						$it['image_url'] = $img;
					}
				}
				unset($it);
				$items = array_values($items);
			}

			$order['items'] = $items;

			// Fallback: if items empty try matching by order_number
			if (empty($order['items']) && !empty($order['order_number'])) {
				$stmt3 = $pdo->prepare("SELECT oi.*, p.name, p.image_url FROM order_items oi LEFT JOIN orders o ON oi.order_id = o.id LEFT JOIN products p ON oi.product_id = p.id WHERE o.order_number = ?");
				$stmt3->execute([$order['order_number']]);
				$fallbackItems = $stmt3->fetchAll();
				error_log("Orders.php - Fallback fetch by order_number '" . $order['order_number'] . "' returned " . count($fallbackItems) . " items");
				if (is_array($fallbackItems) && count($fallbackItems) > 0) {
					foreach ($fallbackItems as &$it) {
						if (isset($it['quantity'])) $it['quantity'] = (int)$it['quantity'];
						if (isset($it['price'])) $it['price'] = (float)$it['price'];
						$it['name'] = (isset($it['name']) && $it['name'] !== null && $it['name'] !== '') ? $it['name'] : ('Item #' . ($it['product_id'] ?? ''));
						$img = isset($it['image_url']) ? trim($it['image_url']) : '';
						if ($img === '') {
							$it['image_url'] = '';
						} else if (!preg_match('/^https?:\/\//', $img)) {
							$img = preg_replace('/^(images\/)*/', '', $img);
							$it['image_url'] = 'https://kuttysoora.com/kuttysoora_seafood/backend/images/' . $img;
						} else {
							if (strpos($img, 'http://kuttysoora.com') === 0) {
								$img = preg_replace('/^http:\/\//', 'https://', $img);
							}
							$it['image_url'] = $img;
						}
					}
					unset($it);
					$order['items'] = array_values($fallbackItems);
				}
			}
}
echo json_encode(["orders" => $orders]);
?>
