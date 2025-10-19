<?php
// JWT Authentication Helper Class
class JWTAuth {
    private static $secret_key = "kuttysoora_seafood_secret_2024_secure_key_here";
    private static $algorithm = "HS256";
    
    // Generate JWT token
    public static function generateToken($user_id, $phone) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        // Include role when available by passing as optional 3rd parameter
        $args = func_get_args();
        $role = isset($args[2]) ? $args[2] : null;

        $payloadArray = [
            'user_id' => $user_id,
            'phone' => $phone,
            'iat' => time(),
            'exp' => time() + (24 * 60 * 60) // 24 hours
        ];

        if ($role !== null) {
            $payloadArray['role'] = $role;
        }

        $payload = json_encode($payloadArray);
        
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, self::$secret_key, true);
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
        
        // Verify signature
        $validSignature = hash_hmac('sha256', $header . "." . $payload, self::$secret_key, true);
        $validSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($validSignature));
        
        if ($signature !== $validSignature) {
            return false;
        }
        
    // Decode payload
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $payload));
        $payloadData = json_decode($payload, true);
        
        // Check expiration
        if ($payloadData['exp'] < time()) {
            return false;
        }
        
        return $payloadData;
    }
    
    // Get token from headers
    public static function getTokenFromHeaders() {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (strpos($authHeader, 'Bearer ') === 0) {
                return substr($authHeader, 7);
            }
        }
        return null;
    }
    
    // Middleware to protect endpoints
    public static function requireAuth() {
        $token = self::getTokenFromHeaders();
        $payload = self::validateToken($token);
        
        if (!$payload) {
            http_response_code(401);
            echo json_encode([
                "error" => "Invalid or expired token",
                "code" => "UNAUTHORIZED"
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
            // Attempt to access PDO via global if set
            if (isset($GLOBALS['pdo'])) {
                try {
                    $stmt = $GLOBALS['pdo']->prepare("SELECT role FROM users WHERE id = ?");
                    $stmt->execute([$payload['user_id']]);
                    $row = $stmt->fetch();
                    if ($row && isset($row['role']) && strtolower($row['role']) === 'admin') {
                        return $payload;
                    }
                } catch (Exception $e) {
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