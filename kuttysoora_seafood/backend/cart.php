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

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1); // Enable display for web debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/cart_errors.log'); // Log to file

try {
    require_once 'db_config.php';
    require_once 'jwt_auth.php';

    error_log("Cart.php - Request received: " . $_SERVER['REQUEST_METHOD']);
    
    // Read input once and store it
    $inputData = file_get_contents('php://input');
    error_log("Cart.php - Request body: " . $inputData);

    // Validate JWT token and get user info
    $tokenPayload = JWTAuth::requireAuth();
    $authenticated_user_id = $tokenPayload['user_id'];
    
    error_log("Cart.php - Authenticated user_id: " . $authenticated_user_id);

    $data = json_decode($inputData, true);
    $action = isset($data['action']) ? $data['action'] : 'list';
    
    error_log("Cart.php - Action: " . $action);
    error_log("Cart.php - Data: " . json_encode($data));

    // Use authenticated user ID instead of user_id from request
    $user_id = $authenticated_user_id;

    if ($action === 'add') {
        $product_id = intval($data['product_id']);
        $quantity = intval($data['quantity']);
        
        error_log("Cart.php - Add action: product_id=$product_id, quantity=$quantity");
        
        if (!$product_id || !$quantity) {
            error_log("Cart.php - Add action failed: Missing product_id or quantity");
            http_response_code(400);
            echo json_encode(["error" => "product_id and quantity required"]);
            exit;
        }
        
        // Check if already in cart
        error_log("Cart.php - Checking if product already in cart for user $user_id");
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $item = $stmt->fetch();
        
        if ($item) {
            error_log("Cart.php - Product already in cart, updating quantity");
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
            $stmt->execute([$quantity, $item['id']]);
        } else {
            error_log("Cart.php - Adding new product to cart");
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        
        error_log("Cart.php - Add action successful");
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
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format image URLs for cart items
    foreach ($items as &$item) {
        // Ensure proper type casting
        $item['id'] = (int)$item['id'];
        $item['price'] = (float)$item['price'];
        $item['stock'] = (int)$item['stock'];
        $item['quantity'] = (int)$item['quantity'];
        
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

    echo json_encode(["cart" => $items]);

} catch (Exception $e) {
    error_log("Cart.php - Exception caught: " . $e->getMessage());
    error_log("Cart.php - Exception trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        "error" => "Server error",
        "message" => $e->getMessage(),
        "trace" => $e->getTraceAsString(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ]);
}
?>
