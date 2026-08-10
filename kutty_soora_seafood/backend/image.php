<?php
// Enhanced CORS headers for image serving
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the image path from the URL parameter
$imagePath = isset($_GET['path']) ? $_GET['path'] : '';

// Validate the image path to prevent directory traversal
if (empty($imagePath)) {
    http_response_code(400);
    echo json_encode(["error" => "Image path is required"]);
    exit();
}

// SECURITY: Resolve the real path and verify it stays inside the images
// directory. Simple string replacement of "../" is bypassable (e.g. "....//"),
// so we use realpath() containment instead.
$imageName = basename($imagePath);
if ($imageName !== $imagePath && strpos($imagePath, 'images/') !== 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid image path"]);
    exit();
}

$imagesDir = realpath(__DIR__ . '/images');
$fullPath = realpath($imagesDir . '/' . $imageName);

// Reject if the file doesn't resolve or escapes the images directory
if ($fullPath === false || strpos($fullPath, $imagesDir . DIRECTORY_SEPARATOR) !== 0) {
    http_response_code(404);
    echo json_encode(["error" => "Image not found"]);
    exit();
}

// Check if the file exists and is actually an image
if (!file_exists($fullPath)) {
    http_response_code(404);
    echo json_encode(["error" => "Image not found: " . basename($imagePath)]);
    exit();
}

// Get file extension and set appropriate content type
$extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'svg' => 'image/svg+xml'
];

if (!isset($mimeTypes[$extension])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid image format"]);
    exit();
}

// Set content type and cache headers
header('Content-Type: ' . $mimeTypes[$extension]);
header('Cache-Control: public, max-age=2592000'); // 30 days cache
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT');

// Output the image
readfile($fullPath);
exit();
?>