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
    
    // For testing, use a hardcoded user_id (replace with actual user ID from your database)
    $user_id = 1;
    
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
        foreach ($items as &$item) {
            $item['id'] = (int)$item['id'];
            $item['price'] = (float)$item['price'];
            $item['stock'] = (int)$item['stock'];
            $item['quantity'] = (int)$item['quantity'];
            
            if (isset($item['image_url']) && !empty($item['image_url'])) {
                $imageUrl = trim($item['image_url']);
                if (!preg_match('/^https?:\/\//', $imageUrl)) {
                    $imageUrl = preg_replace('/^(images\/)?/', '', $imageUrl);
                    $item['image_url'] = 'http://kuttysoora.com/kuttysoora_seafood/backend/images/' . $imageUrl;
                }
            } else {
                $item['image_url'] = '';
            }
        }
        
        echo json_encode(["cart" => $items, "debug" => "No auth version works!"]);
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
