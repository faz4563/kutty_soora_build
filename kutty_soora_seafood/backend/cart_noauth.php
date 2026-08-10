<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Test database connection
    require_once 'db_config.php';
    require_once 'jwt_auth.php';

    // SECURITY: This is a leftover test file that previously used a hardcoded
    // user_id (1). Any visitor could read/modify another user's cart.
    // Locked down: only authenticated admins may use this testing endpoint.
    JWTAuth::requireAdmin();

    // Determine which user to operate on from the token
    $tokenPayload = JWTAuth::requireAuth();
    $user_id = $tokenPayload['user_id'] ?? $tokenPayload['id'] ?? null;
    if (!$user_id) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Get request data
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : 'list';
    
    if ($action === 'list') {
        // List cart items
        $stmt = $pdo->prepare("
            SELECT c.id as cart_id, c.quantity, 
                   p.id, p.name, p.category, p.description, p.price, p.stock, p.image_url 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format image URLs
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/backend')), '/');
        $baseImageUrl = $scheme . '://' . $host . $scriptDir . '/images/';

        foreach ($items as &$item) {
            $item['id'] = (int)$item['id'];
            $item['price'] = (float)$item['price'];
            $item['stock'] = (int)$item['stock'];
            $item['quantity'] = (int)$item['quantity'];
            
            if (isset($item['image_url']) && !empty($item['image_url'])) {
                $imageUrl = trim($item['image_url']);
                if (!preg_match('/^https?:\/\//', $imageUrl)) {
                    $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
                    $item['image_url'] = $baseImageUrl . $imageUrl;
                }
            } else {
                $item['image_url'] = '';
            }
        }
        
        echo json_encode(["cart" => $items]);
    } else {
        echo json_encode(["error" => "Only list action supported in test mode"]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine(),
        "trace" => $e->getTraceAsString()
    ]);
}
?>
