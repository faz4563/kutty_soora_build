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

if ($action === 'add') {
	$product_id = intval($data['product_id']);
	$quantity = intval($data['quantity']);
	if (!$product_id || !$quantity) {
		http_response_code(400);
		echo json_encode(["error" => "product_id and quantity required"]);
		exit;
	}
	// Check if already in cart
	$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
	$stmt->execute([$user_id, $product_id]);
	$item = $stmt->fetch();
	if ($item) {
		$stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
		$stmt->execute([$quantity, $item['id']]);
	} else {
		$stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
		$stmt->execute([$user_id, $product_id, $quantity]);
	}
	echo json_encode(["success" => true]);
	exit;
} elseif ($action === 'update') {
	$product_id = intval($data['product_id']);
	$quantity = intval($data['quantity']);
	if (!$product_id || !$quantity) {
		http_response_code(400);
		echo json_encode(["error" => "product_id and quantity required"]);
		exit;
	}
	$stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
	$stmt->execute([$quantity, $user_id, $product_id]);
	echo json_encode(["success" => true]);
	exit;
} elseif ($action === 'remove') {
	$product_id = intval($data['product_id']);
	if (!$product_id) {
		http_response_code(400);
		echo json_encode(["error" => "product_id required"]);
		exit;
	}
	$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
	$stmt->execute([$user_id, $product_id]);
	echo json_encode(["success" => true]);
	exit;
} elseif ($action === 'clear') {
	$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
	$stmt->execute([$user_id]);
	echo json_encode(["success" => true]);
	exit;
}

// List cart items
$stmt = $pdo->prepare("SELECT c.id as cart_id, c.quantity, p.id, p.name, p.category, p.description, p.price, p.stock, p.image_url FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();
echo json_encode(["cart" => $items]);
?>
