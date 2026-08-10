<?php
/**
 * Payment Mode API
 *
 * Returns whether Razorpay is running in TEST mode or LIVE mode.
 * The app uses this to display a "TEST MODE" banner on the payment page
 * so users know no real money will be charged.
 *
 * Security:
 * - No sensitive data returned (only a boolean flag)
 * - Public endpoint (no auth required) - exposes no secrets
 */

require_once 'cors_headers.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables manually (mirrors other backend files)
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

// Load .env file for RAZORPAY_KEY_ID
loadEnv(__DIR__ . '/.env');

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$keyId = $_ENV['RAZORPAY_KEY_ID'] ?? '';
$isTestMode = strpos($keyId, 'rzp_test_') === 0;

echo json_encode([
    'success' => true,
    'is_test_mode' => $isTestMode,
    'key_prefix' => $isTestMode ? 'rzp_test' : 'rzp_live',
]);
