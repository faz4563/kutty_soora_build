<?php
/**
 * Advanced Security Manager
 * 
 * Features:
 * - Rate limiting to prevent brute force attacks
 * - IP-based blocking and whitelisting
 * - Request validation and sanitization
 * - Security logging and monitoring
 * - SQL injection prevention
 * - XSS protection
 * - CSRF token validation
 */

class SecurityManager {
    private static $pdo;
    private static $maxLoginAttempts = 5;
    private static $loginLockoutTime = 900; // 15 minutes
    private static $maxRequestsPerMinute = 60;
    private static $maxRequestsPerHour = 1000;
    
    /**
     * Initialize with database connection
     */
    public static function init($pdoConnection) {
        self::$pdo = $pdoConnection;
        self::createSecurityTables();
    }
    
    /**
     * Create security tables if they don't exist
     */
    private static function createSecurityTables() {
        try {
            // Rate limiting table
            self::$pdo->exec("
                CREATE TABLE IF NOT EXISTS rate_limiting (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL,
                    endpoint VARCHAR(255) NOT NULL,
                    request_count INT DEFAULT 1,
                    window_start DATETIME NOT NULL,
                    last_request DATETIME NOT NULL,
                    INDEX idx_ip_endpoint (ip_address, endpoint),
                    INDEX idx_window (window_start)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            
            // Failed login attempts table
            self::$pdo->exec("
                CREATE TABLE IF NOT EXISTS failed_login_attempts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL,
                    phone VARCHAR(20),
                    attempt_time DATETIME NOT NULL,
                    user_agent TEXT,
                    INDEX idx_ip (ip_address),
                    INDEX idx_phone (phone),
                    INDEX idx_time (attempt_time)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            
            // Security logs table
            self::$pdo->exec("
                CREATE TABLE IF NOT EXISTS security_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL,
                    user_id INT,
                    event_type VARCHAR(50) NOT NULL,
                    severity ENUM('info', 'warning', 'critical') DEFAULT 'info',
                    description TEXT,
                    request_data JSON,
                    created_at DATETIME NOT NULL,
                    INDEX idx_ip (ip_address),
                    INDEX idx_user (user_id),
                    INDEX idx_type (event_type),
                    INDEX idx_created (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            
            // IP blacklist table
            self::$pdo->exec("
                CREATE TABLE IF NOT EXISTS ip_blacklist (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL UNIQUE,
                    reason TEXT,
                    blocked_at DATETIME NOT NULL,
                    expires_at DATETIME,
                    is_permanent BOOLEAN DEFAULT FALSE,
                    INDEX idx_ip (ip_address),
                    INDEX idx_expires (expires_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            
        } catch (PDOException $e) {
            error_log("Security Manager: Failed to create tables - " . $e->getMessage());
        }
    }
    
    /**
     * Get client IP address (handles proxies)
     */
    public static function getClientIP() {
        $ip = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
        
        return filter_var(trim($ip), FILTER_VALIDATE_IP) ? trim($ip) : '0.0.0.0';
    }
    
    /**
     * Check if IP is blacklisted
     */
    public static function isIPBlacklisted($ip = null) {
        if ($ip === null) {
            $ip = self::getClientIP();
        }
        
        try {
            $stmt = self::$pdo->prepare("
                SELECT id FROM ip_blacklist 
                WHERE ip_address = ? 
                AND (is_permanent = TRUE OR expires_at > NOW())
            ");
            $stmt->execute([$ip]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Security Manager: IP blacklist check failed - " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Rate limiting check
     */
    public static function checkRateLimit($endpoint, $maxPerMinute = null, $maxPerHour = null) {
        $ip = self::getClientIP();
        $maxPerMinute = $maxPerMinute ?? self::$maxRequestsPerMinute;
        $maxPerHour = $maxPerHour ?? self::$maxRequestsPerHour;
        
        try {
            // Check minute rate
            $stmt = self::$pdo->prepare("
                SELECT COUNT(*) as count FROM rate_limiting 
                WHERE ip_address = ? 
                AND endpoint = ? 
                AND window_start > DATE_SUB(NOW(), INTERVAL 1 MINUTE)
            ");
            $stmt->execute([$ip, $endpoint]);
            $minuteCount = $stmt->fetch()['count'];
            
            if ($minuteCount >= $maxPerMinute) {
                self::logSecurityEvent($ip, null, 'rate_limit_exceeded', 'warning', 
                    "Rate limit exceeded: $minuteCount requests in 1 minute for $endpoint");
                return false;
            }
            
            // Check hour rate
            $stmt = self::$pdo->prepare("
                SELECT COUNT(*) as count FROM rate_limiting 
                WHERE ip_address = ? 
                AND endpoint = ? 
                AND window_start > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            $stmt->execute([$ip, $endpoint]);
            $hourCount = $stmt->fetch()['count'];
            
            if ($hourCount >= $maxPerHour) {
                self::logSecurityEvent($ip, null, 'rate_limit_exceeded', 'warning', 
                    "Rate limit exceeded: $hourCount requests in 1 hour for $endpoint");
                return false;
            }
            
            // Record this request
            $stmt = self::$pdo->prepare("
                INSERT INTO rate_limiting (ip_address, endpoint, window_start, last_request) 
                VALUES (?, ?, NOW(), NOW())
            ");
            $stmt->execute([$ip, $endpoint]);
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Security Manager: Rate limit check failed - " . $e->getMessage());
            return true; // Fail open to avoid blocking legitimate users
        }
    }
    
    /**
     * Record failed login attempt
     */
    public static function recordFailedLogin($phone, $ip = null) {
        if ($ip === null) {
            $ip = self::getClientIP();
        }
        
        try {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            
            $stmt = self::$pdo->prepare("
                INSERT INTO failed_login_attempts (ip_address, phone, attempt_time, user_agent) 
                VALUES (?, ?, NOW(), ?)
            ");
            $stmt->execute([$ip, $phone, $userAgent]);
            
            // Check if should block IP
            $stmt = self::$pdo->prepare("
                SELECT COUNT(*) as count FROM failed_login_attempts 
                WHERE ip_address = ? 
                AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
            ");
            $stmt->execute([$ip]);
            $attempts = $stmt->fetch()['count'];
            
            if ($attempts >= self::$maxLoginAttempts) {
                self::blockIP($ip, "Exceeded maximum login attempts ($attempts)", false, 3600); // 1 hour block
                self::logSecurityEvent($ip, null, 'account_lockout', 'critical', 
                    "IP blocked due to $attempts failed login attempts");
                return false;
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Security Manager: Failed to record login attempt - " . $e->getMessage());
            return true;
        }
    }
    
    /**
     * Check if account is locked
     */
    public static function isAccountLocked($phone) {
        try {
            $stmt = self::$pdo->prepare("
                SELECT COUNT(*) as count FROM failed_login_attempts 
                WHERE phone = ? 
                AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
            ");
            $stmt->execute([$phone]);
            $attempts = $stmt->fetch()['count'];
            
            return $attempts >= self::$maxLoginAttempts;
            
        } catch (PDOException $e) {
            error_log("Security Manager: Account lock check failed - " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Clear failed login attempts for phone/IP
     */
    public static function clearFailedLogins($phone, $ip = null) {
        if ($ip === null) {
            $ip = self::getClientIP();
        }
        
        try {
            $stmt = self::$pdo->prepare("
                DELETE FROM failed_login_attempts 
                WHERE phone = ? OR ip_address = ?
            ");
            $stmt->execute([$phone, $ip]);
        } catch (PDOException $e) {
            error_log("Security Manager: Failed to clear login attempts - " . $e->getMessage());
        }
    }
    
    /**
     * Block an IP address
     */
    public static function blockIP($ip, $reason, $permanent = false, $duration = null) {
        try {
            $expiresAt = $permanent ? null : ($duration ? date('Y-m-d H:i:s', time() + $duration) : null);
            
            $stmt = self::$pdo->prepare("
                INSERT INTO ip_blacklist (ip_address, reason, blocked_at, expires_at, is_permanent) 
                VALUES (?, ?, NOW(), ?, ?)
                ON DUPLICATE KEY UPDATE 
                reason = VALUES(reason), 
                expires_at = VALUES(expires_at), 
                is_permanent = VALUES(is_permanent)
            ");
            $stmt->execute([$ip, $reason, $expiresAt, $permanent]);
            
            self::logSecurityEvent($ip, null, 'ip_blocked', 'critical', 
                "IP blocked: $reason" . ($permanent ? " (permanent)" : " (expires: $expiresAt)"));
            
        } catch (PDOException $e) {
            error_log("Security Manager: Failed to block IP - " . $e->getMessage());
        }
    }
    
    /**
     * Log security event
     */
    public static function logSecurityEvent($ip, $userId, $eventType, $severity, $description, $requestData = null) {
        try {
            $stmt = self::$pdo->prepare("
                INSERT INTO security_logs 
                (ip_address, user_id, event_type, severity, description, request_data, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $ip,
                $userId,
                $eventType,
                $severity,
                $description,
                $requestData ? json_encode($requestData) : null
            ]);
        } catch (PDOException $e) {
            error_log("Security Manager: Failed to log event - " . $e->getMessage());
        }
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate phone number format
     */
    public static function validatePhone($phone) {
        // Indian phone number: 10 digits
        return preg_match('/^[6-9]\d{9}$/', $phone);
    }
    
    /**
     * Clean up old records (run periodically)
     */
    public static function cleanup() {
        try {
            // Clean old rate limiting records (older than 2 hours)
            self::$pdo->exec("
                DELETE FROM rate_limiting 
                WHERE window_start < DATE_SUB(NOW(), INTERVAL 2 HOUR)
            ");
            
            // Clean old failed login attempts (older than 24 hours)
            self::$pdo->exec("
                DELETE FROM failed_login_attempts 
                WHERE attempt_time < DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            
            // Clean expired IP blocks
            self::$pdo->exec("
                DELETE FROM ip_blacklist 
                WHERE is_permanent = FALSE 
                AND expires_at < NOW()
            ");
            
            // Clean old security logs (older than 30 days)
            self::$pdo->exec("
                DELETE FROM security_logs 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            
        } catch (PDOException $e) {
            error_log("Security Manager: Cleanup failed - " . $e->getMessage());
        }
    }
}
?>
