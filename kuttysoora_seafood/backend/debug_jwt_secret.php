<?php
// Debug script to check exact token generation
require_once 'jwt_auth.php';

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

header('Content-Type: text/plain');

echo "=== JWT SECRET CHECK ===\n\n";

// Check what JWTAuth uses
$reflector = new ReflectionClass('JWTAuth');
$method = $reflector->getMethod('getSecretKey');
$method->setAccessible(true);
$secret1 = $method->invoke(null);

echo "JWTAuth secret: " . $secret1 . "\n";
echo "JWTAuth secret length: " . strlen($secret1) . "\n\n";

// Check what login.php would use
$secret2 = $_ENV['JWT_SECRET'] ?? 'fallback_secret_key_change_this';
echo "Login.php secret: " . $secret2 . "\n";
echo "Login.php secret length: " . strlen($secret2) . "\n\n";

echo "Secrets match: " . ($secret1 === $secret2 ? "YES" : "NO") . "\n\n";

// Generate a test token with fixed timestamp
$testUserId = 1;
$testPhone = '1234567890';
$testRole = 'user';
$testIat = 1700000000;
$testExp = 1700086400;

echo "=== TEST TOKEN GENERATION ===\n\n";

// Method 1: JWTAuth class
$token1 = JWTAuth::generateToken($testUserId, $testPhone, $testRole);
echo "JWTAuth token (first 50): " . substr($token1, 0, 50) . "...\n";
$parts1 = explode('.', $token1);
echo "Signature (JWTAuth): " . $parts1[2] . "\n\n";

// Method 2: Login.php style with SAME timestamp
$payload = [
    'user_id' => $testUserId,
    'phone' => $testPhone,
    'role' => $testRole,
    'iat' => $testIat,
    'exp' => $testExp
];

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$header_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$payload_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
$signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret2, true);
$signature_encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$token2 = "$header_encoded.$payload_encoded.$signature_encoded";

echo "Login.php token (first 50): " . substr($token2, 0, 50) . "...\n";
$parts2 = explode('.', $token2);
echo "Signature (Login.php): " . $parts2[2] . "\n\n";

echo "Headers match: " . ($parts1[0] === $parts2[0] ? "YES" : "NO") . "\n";
echo "Payloads match: " . (strpos($parts1[1], $parts2[1]) !== false || strpos($parts2[1], $parts1[1]) !== false ? "SIMILAR" : "NO") . "\n";
echo "Signatures match: " . ($parts1[2] === $parts2[2] ? "YES" : "NO") . "\n\n";

// Validate both
$valid1 = JWTAuth::validateToken($token1);
$valid2 = JWTAuth::validateToken($token2);

echo "JWTAuth token valid: " . ($valid1 ? "YES" : "NO") . "\n";
echo "Login.php token valid: " . ($valid2 ? "YES" : "NO") . "\n";
?>
