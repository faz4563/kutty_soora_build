<?php
// List all orders for admin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

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
loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../db_config.php';
require_once __DIR__ . '/../jwt_auth.php';

// Make $pdo available globally for JWT authentication
$GLOBALS['pdo'] = $pdo;

$payload = JWTAuth::requireAdmin();

try {
    // First, check if orders table has any data at all
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders");
    $countStmt->execute();
    $count = $countStmt->fetch();
    error_log("Admin Orders - Total orders in database: " . $count['total']);
    
    // Check if users table has data
    $userCountStmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
    $userCountStmt->execute();
    $userCount = $userCountStmt->fetch();
    error_log("Admin Orders - Total users in database: " . $userCount['total']);
    
    // Fetch all orders with user information - filter out canceled/cancelled orders
    // Exclude both payment_status='canceled' and status='cancelled' to ensure no canceled orders appear
    $stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.mobile as user_mobile FROM orders o JOIN users u ON o.user_id = u.id WHERE o.payment_status = 'paid' AND o.payment_status NOT IN ('canceled', 'failed', 'timeout') AND o.status NOT IN ('cancelled') ORDER BY o.id DESC");
            $stmt->execute();
    $orders = $stmt->fetchAll();
    
    error_log("Admin Orders - Total orders found after JOIN: " . count($orders));

    foreach ($orders as &$order) {
        $stmt2 = $pdo->prepare("SELECT oi.*, COALESCE(NULLIF(oi.product_name, ''), p.name) AS name, COALESCE(NULLIF(oi.product_image_url, ''), p.image_url) AS image_url, COALESCE(oi.unit_price, p.price) AS price FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt2->execute([$order['id']]);
        $items = $stmt2->fetchAll();
        // Debug: log raw fetched items to help diagnose empty items
        error_log("Admin Orders - Raw fetched items for order " . $order['id'] . ": " . json_encode($items));

        // Normalize items to always be an indexed array and cast types
        if (!is_array($items)) {
            $items = [];
        } else {
            foreach ($items as &$it) {
                // Ensure numeric types
                if (isset($it['quantity'])) {
                    $it['quantity'] = (int)$it['quantity'];
                }
                if (isset($it['price'])) {
                    $it['price'] = (float)$it['price'];
                }

                // Ensure name and image_url keys exist; fall back to product_id when name missing
                $it['name'] = (isset($it['name']) && $it['name'] !== null && $it['name'] !== '') ? $it['name'] : ('Item #' . ($it['product_id'] ?? '')); 
                $img = isset($it['image_url']) ? trim($it['image_url']) : '';
                if ($img === '') {
                    $it['image_url'] = '';
                } else if (!preg_match('/^https?:\/\//', $img)) {
                    // Remove any leading 'images/' and build URL using current host/protocol so local dev works
                    $img = preg_replace('/^(images\/)+/', '', $img);
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
                    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
                    $it['image_url'] = $protocol . '://' . $host . '/kuttysoora_seafood/backend/images/' . $img;
                } else {
                    // If full URL, keep it but prefer https for kuttysoora.com
                    if (strpos($img, 'http://kuttysoora.com') === 0) {
                        $img = preg_replace('/^http:\/\//', 'https://', $img);
                    }
                    $it['image_url'] = $img;
                }

                // Also provide legacy keys some clients may expect
                $it['product_name'] = $it['name'];
                $it['product_image_url'] = $it['image_url'];
            }
            unset($it);
            // Re-index to ensure JSON encodes as array
            $items = array_values($items);
        }

        $order['items'] = $items;
        // If no items found, attempt fallback by matching order_number (some orders may have inconsistent id values)
        if (empty($order['items']) && !empty($order['order_number'])) {
            $stmt3 = $pdo->prepare("SELECT oi.*, p.name, p.image_url FROM order_items oi LEFT JOIN orders o ON oi.order_id = o.id LEFT JOIN products p ON oi.product_id = p.id WHERE o.order_number = ?");
            $stmt3->execute([$order['order_number']]);
            $fallbackItems = $stmt3->fetchAll();
            error_log("Admin Orders - Fallback fetch by order_number '" . $order['order_number'] . "' returned " . count($fallbackItems) . " items");
            if (is_array($fallbackItems) && count($fallbackItems) > 0) {
                // Normalize and assign fallback items
                foreach ($fallbackItems as &$it) {
                    if (isset($it['quantity'])) $it['quantity'] = (int)$it['quantity'];
                    if (isset($it['price'])) $it['price'] = (float)$it['price'];
                    $it['name'] = (isset($it['name']) && $it['name'] !== null && $it['name'] !== '') ? $it['name'] : ('Item #' . ($it['product_id'] ?? ''));
                    $img = isset($it['image_url']) ? trim($it['image_url']) : '';
                    if ($img === '') {
                        $it['image_url'] = '';
                    } else if (!preg_match('/^https?:\/\//', $img)) {
                        // Remove any leading 'images/' and build URL using current host/protocol so local dev works
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

                    // Also provide legacy keys some clients may expect
                    $it['product_name'] = $it['name'];
                    $it['product_image_url'] = $it['image_url'];
                }
                unset($it);
                $order['items'] = array_values($fallbackItems);
            }
        }

        error_log("Admin Orders - Order #" . $order['id'] . " has " . count($order['items']) . " items");
    }

    // Debug log first order's items structure
    if (!empty($orders) && isset($orders[0]['items']) && !empty($orders[0]['items'])) {
        error_log("Admin Orders - Sample item structure: " . json_encode($orders[0]['items'][0]));
    }
    
    error_log("Admin Orders - Returning orders to frontend");
    echo json_encode(["success" => true, "orders" => $orders]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

?>
