<?php
header('Content-Type: text/html; charset=utf-8');

// Quick JWT test - compares login.php and jwt_auth.php token generation
require_once 'jwt_auth.php';

// Load environment
function loadEnv($file) {
    if (!file_exists($file)) return false;
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
    }
    return true;
}

loadEnv(__DIR__ . '/.env');

echo "<h2>JWT Token Test</h2>";
echo "<pre>";

// Test data
$user_id = 123;
$phone = '9876543210';
$role = 'user';

echo "<h3>Method 1: JWTAuth::generateToken()</h3>";
$token1 = JWTAuth::generateToken($user_id, $phone, $role);
echo "Token: " . substr($token1, 0, 50) . "...\n";
echo "Length: " . strlen($token1) . "\n";
$valid1 = JWTAuth::validateToken($token1);
echo "Valid: " . ($valid1 ? "YES ✓" : "NO ✗") . "\n";
if ($valid1) {
    echo "Payload: " . json_encode($valid1) . "\n";
}

echo "\n<h3>Method 2: Login.php-style generation</h3>";
$jwtSecret = $_ENV['JWT_SECRET'] ?? 'fallback_secret_key_change_this';
$payload = [
    'user_id' => $user_id,
    'phone' => $phone,
    'role' => $role,
    'iat' => time(),
    'exp' => time() + (24 * 60 * 60)
];

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$header_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$payload_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
$signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $jwtSecret, true);
$signature_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$token2 = "$header_encoded.$payload_encoded.$signature_encoded";

echo "Token: " . substr($token2, 0, 50) . "...\n";
echo "Length: " . strlen($token2) . "\n";
$valid2 = JWTAuth::validateToken($token2);
echo "Valid: " . ($valid2 ? "YES ✓" : "NO ✗") . "\n";
if ($valid2) {
    echo "Payload: " . json_encode($valid2) . "\n";
}

echo "\n<h3>Comparison</h3>";
echo "Tokens identical: " . ($token1 === $token2 ? "NO (expected)" : "NO (expected - timestamps differ)") . "\n";
echo "Both tokens valid: " . ($valid1 && $valid2 ? "<strong style='color:green'>YES ✓ PASS</strong>" : "<strong style='color:red'>NO ✗ FAIL</strong>") . "\n";

if ($valid1 && $valid2) {
    echo "\n<strong style='color:green; font-size:18px'>✓ TOKEN GENERATION AND VALIDATION ARE CONSISTENT!</strong>\n";
    echo "Both methods generate valid tokens that pass validation.\n";
} else {
    echo "\n<strong style='color:red; font-size:18px'>✗ TOKEN MISMATCH!</strong>\n";
    echo "There is still an encoding or format issue.\n";
}

echo "</pre>";
?>
