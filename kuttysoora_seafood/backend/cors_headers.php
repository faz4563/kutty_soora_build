<?php
/**
 * Enhanced CORS Configuration with Security
 * 
 * Features:
 * - Whitelist-based origin validation
 * - Environment-aware configuration
 * - Security headers
 * - Rate limiting headers
 */

// Load environment for production domain
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if ($key === 'PRODUCTION_DOMAIN') {
            $_ENV['PRODUCTION_DOMAIN'] = $value;
        }
        if ($key === 'APP_ENV') {
            $_ENV['APP_ENV'] = $value;
        }
    }
}

// Get the origin of the request
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';
$productionDomain = $_ENV['PRODUCTION_DOMAIN'] ?? 'https://kuttysoora.com';

// Define allowed origins based on environment
$allowedOrigins = [
    $productionDomain,
    str_replace('https://', 'http://', $productionDomain),
];

// Add development origins only in non-production
if (!$isProduction) {
    $devOrigins = [
        'http://localhost:60179', // Flutter web dev server
        'http://localhost:3000',  // Common dev ports
        'http://localhost:8080',
        'http://localhost:8000',
        'http://localhost:5000',
        'http://127.0.0.1:60179',
        'http://127.0.0.1:3000',
        'http://127.0.0.1:8080',
        'http://127.0.0.1:8000',
        'http://127.0.0.1:5000',
    ];
    $allowedOrigins = array_merge($allowedOrigins, $devOrigins);
}

// Validate origin
$originAllowed = false;
if (empty($origin)) {
    // No origin header (e.g., mobile app)
    $originAllowed = true;
    header('Access-Control-Allow-Origin: *');
} elseif (in_array($origin, $allowedOrigins)) {
    $originAllowed = true;
    header('Access-Control-Allow-Origin: ' . $origin);
} elseif (!$isProduction && (strpos($origin, 'localhost') !== false || strpos($origin, '127.0.0.1') !== false)) {
    // Allow any localhost in development
    $originAllowed = true;
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    // Origin not allowed - log security event
    error_log("CORS: Blocked origin: $origin");
    header('Access-Control-Allow-Origin: ' . $productionDomain);
}

// Standard CORS headers
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin, Cache-Control, Pragma, X-CSRF-Token');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json; charset=utf-8');

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

// Strict Transport Security (only in production with HTTPS)
if ($isProduction && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
?>