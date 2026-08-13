<?php
// Public API for active home-page banner / offer carousel.
//
//   GET banners.php  -> { banners: [...] }  (only is_active = 1, by sort_order)
//
// Image paths are stored relative (e.g. "images/banner_x.png") and converted
// to absolute URLs derived from the actual request (works in local dev and
// production, same as products.php).

// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Cache-Control, Pragma');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Performance optimizations
// AGGRESSIVE FIX: NEVER cache the public banner list. The old 'max-age=120'
// header made browsers serve a stale list for 2 minutes, so a banner deleted
// in the admin panel kept showing on the home screen. The Flutter client also
// appends a cache-busting query param as a second line of defence.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ob_start('ob_gzhandler'); // Enable compression
ini_set('memory_limit', '128M');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    require_once 'db_config.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server configuration error', 'details' => $e->getMessage()]);
    exit();
}

// Build the image base URL from the actual request.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/backend')), '/');
$baseImageUrl = $scheme . '://' . $host . $scriptDir . '/';

// AGGRESSIVE FIX: If the banners table does not exist yet (fresh install /
// migration not run), return an empty list instead of a 500 error. The admin
// schema updater creates the table; until then the carousel simply hides.
try {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'banners'");
    if ($tableCheck->fetch() === false) {
        echo json_encode(['success' => true, 'banners' => []]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success' => true, 'banners' => []]);
    exit;
}

try {
    $stmt = $pdo->query(
        "SELECT id, title, subtitle, category, product_id, images,
                old_price, offer_price, discount_label
         FROM banners
         WHERE is_active = 1
         ORDER BY sort_order ASC, id DESC"
    );
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($banners as $b) {
        $images = json_decode((string)($b['images'] ?? '[]'), true);
        if (!is_array($images)) {
            $images = [];
        }
        // Absolute URLs + pick the first image as the cover.
        $imageUrls = [];
        foreach ($images as $img) {
            $img = ltrim((string)$img, '/');
            if ($img !== '') {
                $imageUrls[] = (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0)
                    ? $img
                    : $baseImageUrl . $img;
            }
        }

        $result[] = [
            'id' => (int)$b['id'],
            'title' => $b['title'],
            'subtitle' => $b['subtitle'],
            'category' => $b['category'],
            'product_id' => $b['product_id'] !== null ? (int)$b['product_id'] : null,
            'images' => $imageUrls,
            'image_url' => $imageUrls[0] ?? '',
            'old_price' => $b['old_price'] !== null ? (float)$b['old_price'] : null,
            'offer_price' => $b['offer_price'] !== null ? (float)$b['offer_price'] : null,
            'discount_label' => $b['discount_label'],
        ];
    }

    echo json_encode(['success' => true, 'banners' => $result]);
} catch (Exception $e) {
    error_log("Public banners API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
