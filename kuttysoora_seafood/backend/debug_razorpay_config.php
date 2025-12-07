<?php
require_once 'cors_headers.php';

header('Content-Type: application/json');

// Load environment variables
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

loadEnv(__DIR__ . '/.env');

$razorpay_key_id = $_ENV['RAZORPAY_KEY_ID'] ?? '';
$razorpay_key_secret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';

$config_status = [
    'env_file_exists' => file_exists(__DIR__ . '/.env'),
    'razorpay_key_id_set' => !empty($razorpay_key_id),
    'razorpay_key_secret_set' => !empty($razorpay_key_secret),
    'razorpay_key_id_preview' => substr($razorpay_key_id, 0, 10) . '...',
    'vendor_autoload_exists' => file_exists(__DIR__ . '/vendor/autoload.php'),
    'razorpay_sdk_installed' => class_exists('Razorpay\Api\Api'),
];

echo json_encode($config_status, JSON_PRETTY_PRINT);
