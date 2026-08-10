<?php
// Comprehensive CORS configuration for all backend endpoints

// Get the origin of the request
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// SECURITY: Strict origin allowlist. Unknown origins are NOT echoed back
// and receive no CORS headers, so browsers block them. Never fall back to
// "*" - a wildcard with credentials is unsafe.
//
// NOTE: Flutter web dev server uses a RANDOM port on every run, so any
// localhost / 127.0.0.1 origin with any port is allowed for development.
// Production origins are matched exactly.
$allowedOrigins = [
//    'https://t06z0dmj-5500.inc1.devtunnels.ms'
];

$isAllowed = empty($origin);

if (!$isAllowed) {
    $parsedOrigin = parse_url($origin);
    $host = isset($parsedOrigin['host']) ? strtolower($parsedOrigin['host']) : '';
    $port = isset($parsedOrigin['port']) ? $parsedOrigin['port'] : '';

    // Allow any port on localhost / 127.0.0.1 (Flutter web dev server)
    if (in_array($host, ['localhost', '127.0.0.1'], true)) {
        $isAllowed = true;
    }

    // Exact match for production / fixed local origins.
    // NOTE: browser Origin headers never include a trailing slash, so
    // compare with rtrim() to tolerate allowlist entries with one.
    if (!$isAllowed) {
        $normalizedOrigin = rtrim($origin, '/');
        foreach ($allowedOrigins as $allowed) {
            if ($normalizedOrigin === rtrim($allowed, '/')) {
                $isAllowed = true;
                break;
            }
        }
    }
}

if ($isAllowed) {
    // Only set CORS headers for allowed origins
    if (!empty($origin)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    }
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin, Cache-Control, Pragma');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
?>