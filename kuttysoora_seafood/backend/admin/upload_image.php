<?php
// Enhanced CORS headers for better web compatibility
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Max-Age: 86400');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../db_config.php';
require_once '../jwt_auth.php';

// Require JWT authentication and admin privileges
$tokenPayload = JWTAuth::requireAuth();
$authenticated_user_id = $tokenPayload['user_id'];
$user_role = $tokenPayload['role'] ?? null;

// Check if user is admin
if ($user_role !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Admin access required"]);
    exit;
}

// Only allow POST for file upload
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed. Only POST is supported."]);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(["error" => "No image file provided"]);
    exit;
}

$uploadedFile = $_FILES['image'];

// Check for upload errors
if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'File size exceeds server limit',
        UPLOAD_ERR_FORM_SIZE => 'File size exceeds form limit',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
    ];
    
    $errorMessage = $errorMessages[$uploadedFile['error']] ?? 'Unknown upload error';
    http_response_code(400);
    echo json_encode(["error" => $errorMessage]);
    exit;
}

// Validate file type
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$fileType = $uploadedFile['type'];
$fileMimeType = mime_content_type($uploadedFile['tmp_name']);

if (!in_array($fileType, $allowedTypes) || !in_array($fileMimeType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed."]);
    exit;
}

// Validate file size (max 5MB)
$maxFileSize = 5 * 1024 * 1024; // 5MB
if ($uploadedFile['size'] > $maxFileSize) {
    http_response_code(400);
    echo json_encode(["error" => "File size too large. Maximum size is 5MB."]);
    exit;
}

// Create images directory if it doesn't exist
$uploadDir = '../images/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to create upload directory"]);
        exit;
    }
}

// Generate unique filename
$fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
$fileName = 'product_' . time() . '_' . uniqid() . '.' . strtolower($fileExtension);
$filePath = $uploadDir . $fileName;

// Move uploaded file
if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to save uploaded file"]);
    exit;
}

// Generate different image URLs based on domain setting
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname(dirname($_SERVER['REQUEST_URI']));

// Remove any trailing slashes and normalize path
$basePath = rtrim($basePath, '/');
$imageUrl = $protocol . '://' . $host . $basePath . '/images/' . $fileName;

// Also provide relative path for flexibility
$relativePath = 'images/' . $fileName;

// Get file info
$fileSize = filesize($filePath);
$imageInfo = getimagesize($filePath);
$width = $imageInfo ? $imageInfo[0] : null;
$height = $imageInfo ? $imageInfo[1] : null;

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    "message" => "Image uploaded successfully",
    "image" => [
        "filename" => $fileName,
        "relative_path" => $relativePath,
        "full_url" => $imageUrl,
        "size" => $fileSize,
        "width" => $width,
        "height" => $height,
        "type" => $fileMimeType
    ]
]);
?>