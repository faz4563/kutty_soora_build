<?php
// Comprehensive CORS configuration for all backend endpoints

// Get the origin of the request
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Define allowed origins
$allowedOrigins = [
    'http://localhost:60179', // Flutter web dev server
    'http://localhost:3000',  // Common dev ports
    'http://localhost:8080',
    'http://localhost:8000',
    'http://127.0.0.1:60179',
    'http://127.0.0.1:3000',
    'https://kuttysoora.com',
    'http://kuttysoora.com'
];

// Check if origin is allowed or if it's a localhost variant
if (in_array($origin, $allowedOrigins) || 
    strpos($origin, 'localhost') !== false || 
    strpos($origin, '127.0.0.1') !== false || 
    empty($origin)) {
    header('Access-Control-Allow-Origin: ' . ($origin ?: '*'));
} else {
    header('Access-Control-Allow-Origin: *');
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin, Cache-Control, Pragma');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
?>