<?php
// JWT Authentication Helper Class
class JWTAuth {
    private static function getSecretKey() {
        // Try to get from environment first
        $secret = $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET');
        if (empty($secret)) {
            error_log("JWT Auth: JWT_SECRET not found in environment, using default");
            $secret = "kuttysoora_seafood_secret_2024_secure_key_here";
        } else {
            error_log("JWT Auth: Using JWT_SECRET from environment");
        }
        error_log("JWT Auth: Secret key length: " . strlen(trim($secret)));
        error_log("JWT Auth: Loaded secret: [" . $secret . "]");
        return trim($secret);
    }
    
    private static $algorithm = "HS256";
    
    // Generate JWT token
    public static function generateToken($user_id, $phone, $role = 'user') {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        $payloadArray = [
            'user_id' => $user_id,
            'phone' => $phone,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + (24 * 60 * 60) // 24 hours
        ];

        $payload = json_encode($payloadArray);
        
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $secretKey = self::getSecretKey();
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $secretKey, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }
    
    // Validate JWT token
    public static function validateToken($token) {
        if (!$token) {
            return false;
        }
        
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            error_log("JWT Auth: Invalid token format. Parts count: " . count($parts));
            return false;
        }
        
        list($header, $payload, $signature) = $parts;
        
        // Verify signature
        $secretKey = self::getSecretKey();
        $validSignature = hash_hmac('sha256', $header . "." . $payload, $secretKey, true);
        $validSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($validSignature));
        
        if ($signature !== $validSignature) {
            error_log("JWT Auth: Signature mismatch");
            error_log("JWT Auth: Expected signature: " . substr($validSignature, 0, 20) . "...");
            error_log("JWT Auth: Received signature: " . substr($signature, 0, 20) . "...");
            return false;
        }
        
        // Decode payload
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $payload));
        $payloadData = json_decode($payload, true);
        
        if ($payloadData === null) {
            error_log("JWT Auth: Failed to decode payload");
            return false;
        }
        
        // Check expiration
        if ($payloadData['exp'] < time()) {
            error_log("JWT Auth: Token expired. Exp: " . $payloadData['exp'] . ", Now: " . time());
            return false;
        }
        
        return $payloadData;
    }
    
    // Alias for validateToken (for backward compatibility)
    public static function verifyToken($token) {
        return self::validateToken($token);
    }
    
    // Get token from headers
    public static function getTokenFromHeaders() {
        error_log("JWT Auth: Getting token from headers...");
        
        // Method 1: $_SERVER HTTP_AUTHORIZATION (MOST RELIABLE FOR FLUTTER)
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            error_log("JWT Auth: HTTP_AUTHORIZATION found: " . substr($_SERVER['HTTP_AUTHORIZATION'], 0, 30) . "...");
            if (strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') === 0) {
                error_log("JWT Auth: ✅ Token extracted from HTTP_AUTHORIZATION");
                return substr($_SERVER['HTTP_AUTHORIZATION'], 7);
            } else {
                // Sometimes it comes without "Bearer " prefix
                error_log("JWT Auth: ✅ Token extracted from HTTP_AUTHORIZATION (no Bearer prefix)");
                return $_SERVER['HTTP_AUTHORIZATION'];
            }
        }
        
        // Method 2: REDIRECT_HTTP_AUTHORIZATION
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            error_log("JWT Auth: REDIRECT_HTTP_AUTHORIZATION found");
            if (strpos($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'Bearer ') === 0) {
                error_log("JWT Auth: ✅ Token extracted from REDIRECT_HTTP_AUTHORIZATION");
                return substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 7);
            } else {
                error_log("JWT Auth: ✅ Token extracted from REDIRECT_HTTP_AUTHORIZATION (no Bearer prefix)");
                return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }
        }
        
        // Method 3: getallheaders()
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            error_log("JWT Auth: Available headers via getallheaders: " . json_encode(array_keys($headers)));
            foreach (['Authorization', 'authorization', 'AUTHORIZATION'] as $key) {
                if (isset($headers[$key]) && strpos($headers[$key], 'Bearer ') === 0) {
                    error_log("JWT Auth: ✅ Token found via getallheaders using key: $key");
                    return substr($headers[$key], 7);
                }
            }
        }
        
        // Method 4: apache_request_headers()
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            foreach (['Authorization', 'authorization', 'AUTHORIZATION'] as $key) {
                if (isset($headers[$key]) && strpos($headers[$key], 'Bearer ') === 0) {
                    error_log("JWT Auth: ✅ Token found via apache_request_headers using key: $key");
                    return substr($headers[$key], 7);
                }
            }
        }
        
        error_log("JWT Auth: ❌ Token not found in any header location");
        error_log("JWT Auth: HTTP_AUTHORIZATION in \$_SERVER: " . (isset($_SERVER['HTTP_AUTHORIZATION']) ? 'YES' : 'NO'));
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            error_log("JWT Auth: HTTP_AUTHORIZATION value: " . $_SERVER['HTTP_AUTHORIZATION']);
        }
        return null;
    }
    
    // Middleware to protect endpoints
    public static function requireAuth() {
        $token = self::getTokenFromHeaders();
        
        if (!$token) {
            error_log("JWT Auth: No token found in headers");
            http_response_code(401);
            echo json_encode([
                "error" => "Authentication required",
                "code" => "NO_TOKEN",
                "message" => "Please log in"
            ]);
            exit;
        }
        
        error_log("JWT Auth: Token found, validating... Token length: " . strlen($token));
        $payload = self::validateToken($token);
        
        if (!$payload) {
            error_log("JWT Auth: Token validation failed");
            error_log("JWT Auth: Token: " . substr($token, 0, 50) . "...");
            http_response_code(401);
            echo json_encode([
                "error" => "Invalid or expired token",
                "code" => "INVALID_TOKEN",
                "message" => "Please log in again"
            ]);
            exit;
        }
        
        // Extract user_id from the correct location in payload
        $user_id = null;
        if (isset($payload['user_id'])) {
            $user_id = $payload['user_id'];
        } elseif (isset($payload['data']['user_id'])) {
            $user_id = $payload['data']['user_id'];
        }
        
        error_log("JWT Auth: Authentication successful for user: " . $user_id);
        return $payload;
    }

    // Require the authenticated user to be an admin
    public static function requireAdmin() {
        $payload = self::requireAuth();

        // If payload contains role, check it; otherwise, fetch role from DB as a fallback
        if (isset($payload['role']) && strtolower($payload['role']) === 'admin') {
            return $payload;
        }

        // Fallback: try to load user's role from database if available
        if (isset($payload['user_id'])) {
            // Attempt to access PDO via global if set, or try to get it from the global scope
            $pdo = $GLOBALS['pdo'] ?? null;
            
            if ($pdo === null && defined('DB_CONNECTION')) {
                $pdo = constant('DB_CONNECTION');
            }
            
            if ($pdo !== null) {
                try {
                    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                    $stmt->execute([$payload['user_id']]);
                    $row = $stmt->fetch();
                    if ($row && isset($row['role']) && strtolower($row['role']) === 'admin') {
                        return $payload;
                    }
                } catch (Exception $e) {
                    error_log("JWT Auth - Error checking admin role: " . $e->getMessage());
                    // ignore and fall through to unauthorized
                }
            }
        }

        http_response_code(403);
        echo json_encode([
            "error" => "Forbidden: admin access required",
            "code" => "FORBIDDEN"
        ]);
        exit;
    }
}
?>