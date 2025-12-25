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

// Load environment variables manually (no composer dependency)
function loadEnv($file) {
    if (!file_exists($file)) {
        return false;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
    return true;
}

// Load .env file for JWT_SECRET and other configs
loadEnv(__DIR__ . '/.env');

try {
    require_once 'db_config.php';
    require_once 'jwt_auth.php';

    // DEBUG: Log all cart requests
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'method' => $_SERVER['REQUEST_METHOD'],
        'headers' => getallheaders(),
        'body' => file_get_contents('php://input'),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    error_log('[CART_DEBUG] Request: ' . json_encode($logData));

    // Validate JWT token and get user info
    $tokenPayload = JWTAuth::requireAuth();
    
    // Extract user_id from the payload data structure
    $authenticated_user_id = null;
    if (isset($tokenPayload['user_id'])) {
        $authenticated_user_id = $tokenPayload['user_id'];
    } elseif (isset($tokenPayload['data']['user_id'])) {
        $authenticated_user_id = $tokenPayload['data']['user_id'];
    } else {
        error_log('[CART_DEBUG] No user_id found in token payload: ' . json_encode($tokenPayload));
        http_response_code(401);
        echo json_encode(["error" => "Invalid token structure", "message" => "Please log in again"]);
        exit;
    }

    error_log('[CART_DEBUG] Authenticated user ID: ' . $authenticated_user_id);

    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : 'list';

    error_log('[CART_DEBUG] Action: ' . $action);

    // Use authenticated user ID instead of user_id from request
    $user_id = $authenticated_user_id;

    if ($action === 'add') {
        $product_id = intval($data['product_id']);
        // Support decimal quantities for weight-based products (e.g., 0.25kg = 250g)
        $quantity = is_numeric($data['quantity']) ? floatval($data['quantity']) : 0;
        
        if (!$product_id || $quantity <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "product_id and valid quantity required"]);
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
        // Support decimal quantities for weight-based products
        $quantity = is_numeric($data['quantity']) ? floatval($data['quantity']) : 0;
        if (!$product_id || $quantity <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "product_id and valid quantity required"]);
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

    // List cart items with full product details (matching products API)
    $stmt = $pdo->prepare("SELECT 
        c.id as cart_id, 
        c.quantity, 
        p.id as id, 
        p.name, 
        p.category, 
        p.description, 
        p.price, 
        p.stock, 
        p.image_url, 
        p.brand, 
        p.sku, 
        p.availability, 
        p.minimum_quantity, 
        p.health_benefits, 
        p.nutritional_info, 
        p.product_uses, 
        p.is_special, 
        p.is_dry, 
        p.weight, 
        p.dimensions, 
        p.material, 
        p.color, 
        p.images, 
        p.tags, 
        p.created_date, 
        p.last_updated 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format image URLs for cart items
    foreach ($items as &$item) {
        // Ensure proper type casting
        $item['id'] = (int)$item['id'];
        $item['price'] = (float)$item['price'];
        $item['stock'] = (int)$item['stock'];
        // Keep quantity as float to support decimal weights (e.g., 0.25kg, 0.5kg)
        $item['quantity'] = (float)$item['quantity'];
        
        // Format image_url with full backend/images/ path if it's a relative path
        if (isset($item['image_url']) && !empty($item['image_url'])) {
            $imageUrl = trim($item['image_url']);
            if (!preg_match('/^https?:\/\//', $imageUrl)) {
                // Remove any leading slashes or 'images/' prefix
                $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
                $item['image_url'] = 'https://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
            }
        } else {
            $item['image_url'] = '';
        }
    }

    $response = ["cart" => $items];
    error_log('[CART_DEBUG] Sending response: ' . json_encode(['item_count' => count($items), 'user_id' => $user_id]));
    
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Server error",
        "message" => $e->getMessage()
    ]);
}
?>
