<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

echo json_encode([
    "status" => "success",
    "message" => "Backend is accessible from Flutter web",
    "timestamp" => date('Y-m-d H:i:s'),
    "server_info" => [
        "REQUEST_URI" => $_SERVER['REQUEST_URI'] ?? 'not set',
        "HTTP_HOST" => $_SERVER['HTTP_HOST'] ?? 'not set',
        "REQUEST_METHOD" => $_SERVER['REQUEST_METHOD'] ?? 'not set'
    ]
]);
?>