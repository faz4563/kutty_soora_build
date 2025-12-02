<?php
// Test script to verify JWT token generation and validation match

require_once 'jwt_auth.php';

echo "<h2>JWT Token Flow Test</h2>\n\n";

// Load .env
function loadEnv($file) {
    if (!file_exists($file)) return false;
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

// Test 1: Generate token using JWTAuth class
echo "<h3>Test 1: JWTAuth::generateToken()</h3>\n";
$token1 = JWTAuth::generateToken(1, '1234567890', 'user');
echo "Generated token: $token1\n\n";

// Test 2: Validate the token
echo "<h3>Test 2: JWTAuth::validateToken()</h3>\n";
$payload1 = JWTAuth::validateToken($token1);
echo "Validation result: " . ($payload1 ? "SUCCESS" : "FAILED") . "\n";
if ($payload1) {
    echo "Payload: " . json_encode($payload1, JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

// Test 3: Generate token using login.php method
echo "<h3>Test 3: Login.php-style token generation</h3>\n";
$jwtSecret = $_ENV['JWT_SECRET'] ?? 'fallback_secret_key_change_this';
$payload = [
    'user_id' => 1,
    'phone' => '1234567890',
    'role' => 'user',
    'iat' => time(),
    'exp' => time() + (24 * 60 * 60)
];

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$header_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$payload_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
$signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $jwtSecret, true);
$signature_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$token2 = "$header_encoded.$payload_encoded.$signature_encoded";

echo "Generated token: $token2\n\n";

// Test 4: Validate login.php-style token
echo "<h3>Test 4: Validate login.php-style token</h3>\n";
$payload2 = JWTAuth::validateToken($token2);
echo "Validation result: " . ($payload2 ? "SUCCESS" : "FAILED") . "\n";
if ($payload2) {
    echo "Payload: " . json_encode($payload2, JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

// Test 5: Compare tokens
echo "<h3>Test 5: Token Comparison</h3>\n";
echo "Tokens match: " . ($token1 === $token2 ? "YES" : "NO") . "\n";
echo "Both validate: " . ($payload1 && $payload2 ? "YES" : "NO") . "\n";

if ($payload1 && $payload2) {
    echo "\n<strong style='color: green;'>✓ ALL TESTS PASSED - Token generation and validation are consistent!</strong>\n";
} else {
    echo "\n<strong style='color: red;'>✗ TESTS FAILED - Token mismatch detected!</strong>\n";
}
?>
