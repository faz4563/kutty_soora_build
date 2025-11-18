<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    "status" => "ok",
    "message" => "Cart endpoint is reachable",
    "php_version" => PHP_VERSION,
    "extensions" => get_loaded_extensions()
]);
?>
