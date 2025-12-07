<?php
/**
 * Input Validator Class
 * 
 * Comprehensive input validation and sanitization
 * - SQL injection prevention
 * - XSS protection
 * - Type validation
 * - Format validation
 * - Business logic validation
 */

class InputValidator {
    
    /**
     * Validate and sanitize phone number
     */
    public static function validatePhone($phone) {
        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Indian phone: must be 10 digits starting with 6-9
        if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
            throw new Exception('Invalid phone number format. Must be 10 digits starting with 6-9.');
        }
        
        return $phone;
    }
    
    /**
     * Validate and sanitize email
     */
    public static function validateEmail($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address format.');
        }
        
        // Check for common disposable email domains (optional)
        $disposableDomains = ['tempmail.com', '10minutemail.com', 'guerrillamail.com'];
        $domain = substr(strrchr($email, "@"), 1);
        
        if (in_array($domain, $disposableDomains)) {
            throw new Exception('Disposable email addresses are not allowed.');
        }
        
        return $email;
    }
    
    /**
     * Validate and sanitize name
     */
    public static function validateName($name, $minLength = 2, $maxLength = 100) {
        $name = trim($name);
        
        // Remove HTML tags and special characters
        $name = strip_tags($name);
        $name = preg_replace('/[^a-zA-Z\s\'-]/', '', $name);
        
        $length = strlen($name);
        
        if ($length < $minLength) {
            throw new Exception("Name must be at least $minLength characters long.");
        }
        
        if ($length > $maxLength) {
            throw new Exception("Name must not exceed $maxLength characters.");
        }
        
        return $name;
    }
    
    /**
     * Validate and sanitize address
     */
    public static function validateAddress($address, $minLength = 10, $maxLength = 500) {
        $address = trim($address);
        
        // Remove HTML tags but keep common punctuation
        $address = strip_tags($address);
        
        $length = strlen($address);
        
        if ($length < $minLength) {
            throw new Exception("Address must be at least $minLength characters long.");
        }
        
        if ($length > $maxLength) {
            throw new Exception("Address must not exceed $maxLength characters.");
        }
        
        // Check for SQL injection patterns
        if (self::containsSQLInjection($address)) {
            throw new Exception('Invalid address format detected.');
        }
        
        return htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate integer ID
     */
    public static function validateId($id, $fieldName = 'ID') {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        
        if ($id === false || $id <= 0) {
            throw new Exception("Invalid $fieldName. Must be a positive integer.");
        }
        
        return $id;
    }
    
    /**
     * Validate positive number (for amounts, quantities, etc.)
     */
    public static function validatePositiveNumber($number, $fieldName = 'Number') {
        $number = filter_var($number, FILTER_VALIDATE_FLOAT);
        
        if ($number === false || $number < 0) {
            throw new Exception("Invalid $fieldName. Must be a positive number.");
        }
        
        return $number;
    }
    
    /**
     * Validate amount/price
     */
    public static function validateAmount($amount, $min = 0, $max = 999999.99) {
        $amount = filter_var($amount, FILTER_VALIDATE_FLOAT);
        
        if ($amount === false) {
            throw new Exception('Invalid amount format.');
        }
        
        if ($amount < $min) {
            throw new Exception("Amount must be at least ₹$min");
        }
        
        if ($amount > $max) {
            throw new Exception("Amount must not exceed ₹$max");
        }
        
        return round($amount, 2);
    }
    
    /**
     * Validate quantity
     */
    public static function validateQuantity($quantity, $min = 1, $max = 1000) {
        $quantity = filter_var($quantity, FILTER_VALIDATE_INT);
        
        if ($quantity === false || $quantity < $min) {
            throw new Exception("Quantity must be at least $min");
        }
        
        if ($quantity > $max) {
            throw new Exception("Quantity must not exceed $max");
        }
        
        return $quantity;
    }
    
    /**
     * Validate password strength
     */
    public static function validatePassword($password, $minLength = 8) {
        if (strlen($password) < $minLength) {
            throw new Exception("Password must be at least $minLength characters long.");
        }
        
        // Check for at least one letter and one number
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            throw new Exception('Password must contain at least one letter and one number.');
        }
        
        // Check for common weak passwords
        $weakPasswords = ['password', '12345678', 'qwerty', 'admin', 'letmein'];
        if (in_array(strtolower($password), $weakPasswords)) {
            throw new Exception('Password is too weak. Please choose a stronger password.');
        }
        
        return $password;
    }
    
    /**
     * Validate OTP
     */
    public static function validateOTP($otp) {
        // OTP must be 6 digits
        if (!preg_match('/^\d{6}$/', $otp)) {
            throw new Exception('Invalid OTP format. Must be 6 digits.');
        }
        
        return $otp;
    }
    
    /**
     * Validate order status
     */
    public static function validateOrderStatus($status) {
        $validStatuses = [
            'pending', 'confirmed', 'processing', 
            'shipped', 'delivered', 'cancelled'
        ];
        
        $status = strtolower(trim($status));
        
        if (!in_array($status, $validStatuses)) {
            throw new Exception('Invalid order status.');
        }
        
        return $status;
    }
    
    /**
     * Validate payment status
     */
    public static function validatePaymentStatus($status) {
        $validStatuses = ['pending', 'initiated', 'paid', 'failed', 'refunded'];
        
        $status = strtolower(trim($status));
        
        if (!in_array($status, $validStatuses)) {
            throw new Exception('Invalid payment status.');
        }
        
        return $status;
    }
    
    /**
     * Validate category
     */
    public static function validateCategory($category) {
        $validCategories = [
            'Fish', 'Prawns', 'Crabs', 
            'Squids and Lobsters', 'Special Seafoods', 'Dry Seafoods'
        ];
        
        if (!in_array($category, $validCategories)) {
            throw new Exception('Invalid category.');
        }
        
        return $category;
    }
    
    /**
     * Validate currency
     */
    public static function validateCurrency($currency) {
        $validCurrencies = ['INR', 'USD', 'EUR'];
        
        $currency = strtoupper(trim($currency));
        
        if (!in_array($currency, $validCurrencies)) {
            throw new Exception('Invalid currency. Supported: ' . implode(', ', $validCurrencies));
        }
        
        return $currency;
    }
    
    /**
     * Validate date format
     */
    public static function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        
        if (!$d || $d->format($format) !== $date) {
            throw new Exception("Invalid date format. Expected: $format");
        }
        
        return $date;
    }
    
    /**
     * Check for SQL injection patterns
     */
    private static function containsSQLInjection($input) {
        $sqlPatterns = [
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE)\b)/i',
            '/(UNION.*SELECT)/i',
            '/(\-\-|\/\*|\*\/|;)/i',
            '/(\bOR\b.*=.*)/i',
            '/(\bAND\b.*=.*)/i',
            '/(\'.*\bOR\b.*\'.*=.*\')/i'
        ];
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sanitize string for safe output (XSS prevention)
     */
    public static function sanitizeOutput($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate JSON input
     */
    public static function validateJSON($json) {
        if (empty($json)) {
            throw new Exception('Empty JSON input.');
        }
        
        $data = json_decode($json, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON format: ' . json_last_error_msg());
        }
        
        return $data;
    }
    
    /**
     * Validate required fields in array
     */
    public static function validateRequiredFields($data, $requiredFields) {
        $missing = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            throw new Exception('Missing required fields: ' . implode(', ', $missing));
        }
        
        return true;
    }
    
    /**
     * Sanitize filename for safe storage
     */
    public static function sanitizeFilename($filename) {
        // Remove path information and dots around the extension
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Prevent double extensions
        $filename = preg_replace('/\.{2,}/', '.', $filename);
        
        return $filename;
    }
    
    /**
     * Validate file upload
     */
    public static function validateFileUpload($file, $allowedTypes, $maxSize = 5242880) {
        // Check if file exists
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            throw new Exception('No file uploaded.');
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $file['error']);
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / 1048576;
            throw new Exception("File size must not exceed {$maxSizeMB}MB");
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid file type. Allowed: ' . implode(', ', $allowedTypes));
        }
        
        return true;
    }
    
    /**
     * Validate URL
     */
    public static function validateURL($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid URL format.');
        }
        
        // Only allow HTTP and HTTPS protocols
        $parsed = parse_url($url);
        if (!in_array($parsed['scheme'] ?? '', ['http', 'https'])) {
            throw new Exception('Only HTTP and HTTPS URLs are allowed.');
        }
        
        return $url;
    }
}
?>
