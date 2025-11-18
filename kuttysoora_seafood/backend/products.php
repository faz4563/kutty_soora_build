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

// Require JWT authentication for products API
$tokenPayload = JWTAuth::requireAuth();
$authenticated_user_id = $tokenPayload['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : 'list';
$product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;

if ($action === 'get_by_id') {
	if (!$product_id) {
		http_response_code(400);
		echo json_encode(["error" => "product_id required"]);
		exit;
	}
	
	$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
	$stmt->execute([$product_id]);
	$product = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if (!$product) {
		http_response_code(404);
		echo json_encode(["error" => "Product not found"]);
		exit;
	}
	
	// Ensure proper type casting and format image_url with full path
	$product['id'] = (int)$product['id'];
	$product['price'] = (float)$product['price'];
	$product['stock'] = (int)$product['stock'];
	
	// Format image_url with full backend/images/ path if it's a relative path
	if (isset($product['image_url']) && !empty($product['image_url'])) {
		$imageUrl = trim($product['image_url']);
		if (!preg_match('/^https?:\/\//', $imageUrl)) {
			// Remove any leading slashes or 'images/' prefix
			$imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
			$product['image_url'] = 'http://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
		} else {
			$product['image_url'] = $imageUrl;
		}
	} else {
		$product['image_url'] = '';
	}
	
	echo json_encode(["product" => $product]);
	exit;
} elseif ($action === 'get_by_category') {
	$category = isset($data['category']) ? trim($data['category']) : '';
	if (!$category) {
		http_response_code(400);
		echo json_encode(["error" => "category required"]);
		exit;
	}
	
	$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
	$stmt->execute([$category]);
	$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	// Ensure proper type casting for numeric fields and format image_url
	foreach ($products as &$product) {
		$product['id'] = (int)$product['id'];
		$product['price'] = (float)$product['price'];
		$product['stock'] = (int)$product['stock'];
		
		// Format image_url with full backend/images/ path if it's a relative path
		if (isset($product['image_url']) && !empty($product['image_url'])) {
			$imageUrl = trim($product['image_url']);
			if (!preg_match('/^https?:\/\//', $imageUrl)) {
				// Remove any leading slashes or 'images/' prefix
				$imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
				$product['image_url'] = 'http://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
			} else {
				$product['image_url'] = $imageUrl;
			}
		} else {
			$product['image_url'] = '';
		}
	}
	
	echo json_encode(["products" => $products]);
	exit;
} elseif ($action === 'search') {
	$query = isset($data['query']) ? trim($data['query']) : '';
	if (!$query) {
		http_response_code(400);
		echo json_encode(["error" => "search query required"]);
		exit;
	}
	
	$searchTerm = "%$query%";
	$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? OR category LIKE ? ORDER BY id DESC");
	$stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
	$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	// Ensure proper type casting for numeric fields and format image_url
	foreach ($products as &$product) {
		$product['id'] = (int)$product['id'];
		$product['price'] = (float)$product['price'];
		$product['stock'] = (int)$product['stock'];
		
		// Format image_url with full backend/images/ path if it's a relative path
		if (isset($product['image_url']) && !empty($product['image_url'])) {
			$imageUrl = trim($product['image_url']);
			if (!preg_match('/^https?:\/\//', $imageUrl)) {
				// Remove any leading slashes or 'images/' prefix
				$imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
				$product['image_url'] = 'http://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
			} else {
				$product['image_url'] = $imageUrl;
			}
		} else {
			$product['image_url'] = '';
		}
	}
	
	echo json_encode(["products" => $products]);
	exit;
}

// Default action: list all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ensure proper type casting for numeric fields and format image_url
foreach ($products as &$product) {
    $product['id'] = (int)$product['id'];
    $product['price'] = (float)$product['price'];
    $product['stock'] = (int)$product['stock'];
    
    // Format image_url with full backend/images/ path if it's a relative path
    if (isset($product['image_url']) && !empty($product['image_url'])) {
        $imageUrl = trim($product['image_url']);
        if (!preg_match('/^https?:\/\//', $imageUrl)) {
            // Remove any leading slashes or 'images/' prefix
            $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
            $product['image_url'] = 'http://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
        } else {
            $product['image_url'] = $imageUrl;
        }
    } else {
        $product['image_url'] = '';
    }
}

echo json_encode([
    "products" => $products,
    "authenticated_user" => $authenticated_user_id
]);
?>
