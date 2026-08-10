<?php
// JWT Authentication Helper Class
class JWTAuth {
    private static function getSecretKey() {
        // SECURITY: The secret MUST come from the environment (.env).
        // There is intentionally NO hardcoded fallback - a leaked or
        // guessed default secret would allow token forgery.
        $secret = $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET');
        $secret = trim((string)$secret);
        if ($secret === '' || strlen($secret) < 32) {
            error_log("JWT Auth: JWT_SECRET is missing or too short in environment");
            http_response_code(500);
            echo json_encode(["error" => "Server authentication is not configured"]);
            exit;
        }
        return $secret;
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
            return false;
        }
        
        list($header, $payload, $signature) = $parts;
        
        // Verify signature using constant-time comparison
        $secretKey = self::getSecretKey();
        $validSignature = hash_hmac('sha256', $header . "." . $payload, $secretKey, true);
        $validSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($validSignature));
        
        if (!hash_equals($validSignature, $signature)) {
            return false;
        }
        
        // Decode payload
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $payload));
        $payloadData = json_decode($payload, true);
        
        if ($payloadData === null) {
            return false;
        }
        
        // Check expiration
        if (!isset($payloadData['exp']) || $payloadData['exp'] < time()) {
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
        // Method 1: $_SERVER HTTP_AUTHORIZATION (MOST RELIABLE FOR FLUTTER)
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            if (strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') === 0) {
                return substr($_SERVER['HTTP_AUTHORIZATION'], 7);
            } else {
                // Sometimes it comes without "Bearer " prefix
                return $_SERVER['HTTP_AUTHORIZATION'];
            }
        }
        
        // Method 2: REDIRECT_HTTP_AUTHORIZATION
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            if (strpos($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 'Bearer ') === 0) {
                return substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 7);
            } else {
                return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }
        }
        
        // Method 3: getallheaders()
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach (['Authorization', 'authorization', 'AUTHORIZATION'] as $key) {
                if (isset($headers[$key]) && strpos($headers[$key], 'Bearer ') === 0) {
                    return substr($headers[$key], 7);
                }
            }
        }
        
        // Method 4: apache_request_headers()
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            foreach (['Authorization', 'authorization', 'AUTHORIZATION'] as $key) {
                if (isset($headers[$key]) && strpos($headers[$key], 'Bearer ') === 0) {
                    return substr($headers[$key], 7);
                }
            }
        }
        
        return null;
    }
    
    // Middleware to protect endpoints
    public static function requireAuth() {
        $token = self::getTokenFromHeaders();
        
        if (!$token) {
            http_response_code(401);
            echo json_encode([
                "error" => "Authentication required",
                "code" => "NO_TOKEN",
                "message" => "Please log in"
            ]);
            exit;
        }
        
        $payload = self::validateToken($token);
        
        if (!$payload) {
            http_response_code(401);
            echo json_encode([
                "error" => "Invalid or expired token",
                "code" => "INVALID_TOKEN",
                "message" => "Please log in again"
            ]);
            exit;
        }
        
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