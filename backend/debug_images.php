<?php
// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// List all images in the images directory
$imagesDir = __DIR__ . '/images/';
$imageFiles = [];

if (is_dir($imagesDir)) {
    $files = scandir($imagesDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && !is_dir($imagesDir . $file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $imageFiles[] = [
                    'filename' => $file,
                    'direct_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/kuttysoora_seafood/backend/images/' . $file,
                    'script_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/kuttysoora_seafood/backend/image.php?path=' . $file,
                    'size' => filesize($imagesDir . $file),
                    'exists' => file_exists($imagesDir . $file)
                ];
            }
        }
    }
}

echo json_encode([
    'total_images' => count($imageFiles),
    'images' => array_slice($imageFiles, 0, 10), // Return first 10 for testing
    'image_base_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/kuttysoora_seafood/backend/image.php?path=',
    'direct_base_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/kuttysoora_seafood/backend/images/'
], JSON_PRETTY_PRINT);
?>