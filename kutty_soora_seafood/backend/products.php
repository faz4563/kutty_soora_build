<?php
// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Performance optimizations
header('Cache-Control: max-age=180, must-revalidate'); // 3 min cache for products
ob_start('ob_gzhandler'); // Enable compression
ini_set('memory_limit', '256M');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    require_once 'db_config.php';
    require_once 'jwt_auth.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server configuration error', 'details' => $e->getMessage()]);
    exit();
}

// Optional authentication for products API (public browsing allowed)
$authenticated_user_id = null;
$token = JWTAuth::getTokenFromHeaders();
if ($token) {
    $tokenPayload = JWTAuth::validateToken($token);
    if ($tokenPayload) {
        $authenticated_user_id = $tokenPayload['user_id'];
    }
}

// SECURITY/CORRECTNESS: Build the image base URL from the actual request
// (scheme + host + script directory). Previously this was hardcoded to
// 'https://kuttysoora.com/...' (broke local dev) or
// 'http://localhost/...' (broke production). Deriving it works everywhere.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/backend')), '/');
$baseImageUrl = $scheme . '://' . $host . $scriptDir . '/images/';

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
			$product['image_url'] = $baseImageUrl . $imageUrl;
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
				$product['image_url'] = $baseImageUrl . $imageUrl;
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
	
	// Try full-text search first, fallback to LIKE search
	$stmt = $pdo->prepare("SELECT id, name, category, description, price, stock, image_url, availability, minimum_quantity, price_unit, MATCH(name, description, category) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance FROM products WHERE MATCH(name, description, category) AGAINST(? IN NATURAL LANGUAGE MODE) ORDER BY relevance DESC, id DESC LIMIT 50");
	$stmt->execute([$query, $query]);
	$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	// If no results with full-text, fallback to LIKE search
	if (empty($products)) {
		$searchTerm = "%$query%";
		$stmt = $pdo->prepare("SELECT id, name, category, description, price, stock, image_url, availability, minimum_quantity, price_unit FROM products WHERE name LIKE ? OR description LIKE ? OR category LIKE ? ORDER BY id DESC LIMIT 50");
		$stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
		$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
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
				$product['image_url'] = $baseImageUrl . $imageUrl;
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

// Default action: list products with all fields (matching get_by_category behavior)
$limit = isset($_GET['limit']) ? min(100, max(1, (int)$_GET['limit'])) : 50;
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?");
$stmt->execute([$limit]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Optimized product processing with batch operations

foreach ($products as &$product) {
    // Fast type casting
    $product['id'] = (int)$product['id'];
    $product['price'] = (float)$product['price'];
    $product['stock'] = (int)$product['stock'];
    
    // Optimized image URL processing
    $imageUrl = $product['image_url'] ?? '';
    if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
        // NOTE: use preg_replace, not ltrim — ltrim('/images/', ...) treats
        // the string as a character set and would strip letters from real
        // filenames (e.g. 'squid.jpg' -> 'quid.jpg').
        $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
        $product['image_url'] = $baseImageUrl . $imageUrl;
    } elseif (!$imageUrl) {
        $product['image_url'] = '';
    }
}

unset($product); // Clean up reference

echo json_encode([
    "products" => $products,
    "authenticated_user" => $authenticated_user_id
]);
?>
