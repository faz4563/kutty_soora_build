<?php
// Admin API for home-page banner / offer management.
//
// Endpoints (all require admin JWT):
//   GET  banners.php?options=1     -> { categories: [...], products: [...] } for dropdowns
//   GET  banners.php               -> { banners: [...] } (all banners, newest first)
//   POST banners.php               -> create (no id) or update (id present) a banner
//   DELETE banners.php?id=N        -> delete a banner
//
// Image uploads are handled by upload_image.php (one file per request); the
// client stores the returned relative paths in the banner `images` JSON array.

// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Cache-Control, Pragma');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

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

loadEnv(__DIR__ . '/../.env');

try {
    require_once __DIR__ . '/../db_config.php';
    require_once __DIR__ . '/../jwt_auth.php';

    // SECURITY: Require admin privileges for ALL requests.
    try {
        $tokenPayload = JWTAuth::requireAuth();
        $user_role = $tokenPayload['role'] ?? null;
        if ($user_role !== 'admin') {
            http_response_code(403);
            echo json_encode(["success" => false, "error" => "Admin access required"]);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["success" => false, "error" => "Authentication failed"]);
        exit;
    }

    // db_config.php sets the global $pdo — use it directly (same as other admin files).
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Database connection unavailable"]);
        exit;
    }
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    // AGGRESSIVE FIX: If the banners table does not exist yet, treat the API
    // as empty instead of throwing a 500. Admin GET returns [], and the
    // options endpoint still returns categories/products for the dropdowns.
    $bannersTableExists = false;
    try {
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'banners'");
        $bannersTableExists = $tableCheck->fetch() !== false;
    } catch (Exception $e) {
        $bannersTableExists = false;
    }

    // ---- GET: options (categories + products for dropdowns) ----------------
    if ($method === 'GET' && isset($_GET['options']) && $_GET['options'] == '1') {
        $categoriesStmt = $pdo->query(
            "SELECT DISTINCT category FROM products
             WHERE category IS NOT NULL AND category != ''
             ORDER BY category ASC"
        );
        $categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);

        $productsStmt = $pdo->query(
            "SELECT id, name, category, price FROM products
             WHERE availability != 'out_of_stock'
             ORDER BY name ASC
             LIMIT 1000"
        );
        $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "categories" => $categories,
            "products" => $products,
        ]);
        exit;
    }

    // ---- GET: list all banners ---------------------------------------------
    if ($method === 'GET') {
        if (!$bannersTableExists) {
            echo json_encode(["success" => true, "banners" => []]);
            exit;
        }
        $stmt = $pdo->query(
            "SELECT b.*, p.name AS product_name
             FROM banners b
             LEFT JOIN products p ON p.id = b.product_id
             ORDER BY b.sort_order ASC, b.id DESC"
        );
        $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($banners as &$b) {
            $b['images'] = json_decode((string)($b['images'] ?? '[]'), true) ?: [];
        }
        unset($b);
        echo json_encode(["success" => true, "banners" => $banners]);
        exit;
    }

    // ---- DELETE: remove a banner -------------------------------------------
    if ($method === 'DELETE') {
        if (!$bannersTableExists) {
            echo json_encode(["success" => true, "message" => "Banner deleted"]);
            exit;
        }
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Banner id is required"]);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Banner deleted"]);
        exit;
    }

    // ---- POST: create / update ---------------------------------------------
    if ($method === 'POST' || $method === 'PUT') {
        if (!$bannersTableExists) {
            http_response_code(409);
            echo json_encode(["success" => false, "error" => "banners table missing — run the database schema updater"]);
            exit;
        }
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $data = $_POST;
        }

        $id = isset($data['id']) ? intval($data['id']) : 0;

        $title          = trim((string)($data['title'] ?? ''));
        $subtitle       = trim((string)($data['subtitle'] ?? ''));
        $category       = trim((string)($data['category'] ?? ''));
        $productId      = isset($data['product_id']) && $data['product_id'] !== '' && $data['product_id'] !== null
                            ? intval($data['product_id'])
                            : null;
        $oldPrice       = isset($data['old_price']) && $data['old_price'] !== '' && $data['old_price'] !== null
                            ? floatval($data['old_price'])
                            : null;
        $offerPrice     = isset($data['offer_price']) && $data['offer_price'] !== '' && $data['offer_price'] !== null
                            ? floatval($data['offer_price'])
                            : null;
        $discountLabel  = trim((string)($data['discount_label'] ?? ''));
        $isActive       = (isset($data['is_active']) && ($data['is_active'] === false || $data['is_active'] === '0' || $data['is_active'] === 0))
                            ? 0 : 1;
        $sortOrder      = isset($data['sort_order']) ? intval($data['sort_order']) : 0;

        // Build images JSON array from relative paths or full URLs.
        $images = [];
        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $img) {
                $img = trim((string)$img);
                if ($img !== '') {
                    // Normalise: strip leading slash so paths are like "images/foo.png"
                    $images[] = ltrim($img, '/');
                }
            }
        }
        $imagesJson = json_encode(array_values(array_unique($images)));

        if ($title === '') {
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Banner title is required"]);
            exit;
        }

        if ($id > 0) {
            // Update existing banner
            $stmt = $pdo->prepare(
                "UPDATE banners SET
                    title = ?, subtitle = ?, category = ?, product_id = ?,
                    images = ?, old_price = ?, offer_price = ?,
                    discount_label = ?, is_active = ?, sort_order = ?
                 WHERE id = ?"
            );
            $stmt->execute([
                $title, $subtitle, $category, $productId,
                $imagesJson, $oldPrice, $offerPrice,
                $discountLabel, $isActive, $sortOrder,
                $id,
            ]);
        } else {
            // Create new banner
            $stmt = $pdo->prepare(
                "INSERT INTO banners
                    (title, subtitle, category, product_id, images,
                     old_price, offer_price, discount_label, is_active, sort_order)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $title, $subtitle, $category, $productId,
                $imagesJson, $oldPrice, $offerPrice,
                $discountLabel, $isActive, $sortOrder,
            ]);
            $id = (int)$pdo->lastInsertId();
        }

        // Return the saved banner
        $stmt = $pdo->prepare(
            "SELECT b.*, p.name AS product_name
             FROM banners b
             LEFT JOIN products p ON p.id = b.product_id
             WHERE b.id = ?"
        );
        $stmt->execute([$id]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($banner) {
            $banner['images'] = json_decode((string)($banner['images'] ?? '[]'), true) ?: [];
        }

        echo json_encode([
            "success" => true,
            "message" => $id > 0 ? "Banner updated" : "Banner created",
            "banner" => $banner,
        ]);
        exit;
    }

    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Method not allowed"]);
} catch (Exception $e) {
    error_log("Banners API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Server error: " . $e->getMessage()]);
}
