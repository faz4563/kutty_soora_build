<?php
/**
 * Create Payment Tables and Add Columns
 */

require_once 'db_config.php';

try {
    echo "Setting up payment integration...\n\n";
    
    // 1. Add Razorpay columns to orders table
    echo "1. Adding Razorpay columns to orders table...\n";
    
    $columns = [
        "razorpay_order_id VARCHAR(100) DEFAULT NULL",
        "razorpay_payment_id VARCHAR(100) DEFAULT NULL",
        "razorpay_signature VARCHAR(255) DEFAULT NULL"
    ];
    
    foreach ($columns as $col) {
        $colName = explode(' ', $col)[0];
        try {
            $pdo->exec("ALTER TABLE orders ADD COLUMN $col");
            echo "  ✓ Added $colName\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "  - $colName already exists\n";
            } else {
                echo "  ✗ Error adding $colName: " . $e->getMessage() . "\n";
            }
        }
    }
    
    // 2. Add indexes
    echo "\n2. Adding indexes...\n";
    $indexes = [
        "idx_razorpay_order_id ON orders(razorpay_order_id)",
        "idx_razorpay_payment_id ON orders(razorpay_payment_id)"
    ];
    
    foreach ($indexes as $idx) {
        try {
            $pdo->exec("CREATE INDEX $idx");
            echo "  ✓ Created index\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate key') !== false) {
                echo "  - Index already exists\n";
            } else {
                echo "  ✗ Error: " . $e->getMessage() . "\n";
            }
        }
    }
    
    // 3. Create payment_logs table
    echo "\n3. Creating payment_logs table...\n";
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS payment_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                razorpay_order_id VARCHAR(100) DEFAULT NULL,
                razorpay_payment_id VARCHAR(100) DEFAULT NULL,
                amount DECIMAL(10, 2) DEFAULT NULL,
                currency VARCHAR(10) DEFAULT 'INR',
                status ENUM('initiated', 'success', 'failed', 'signature_failed', 'refunded') NOT NULL,
                payment_method VARCHAR(50) DEFAULT NULL,
                error_message TEXT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_order_id (order_id),
                INDEX idx_razorpay_order_id (razorpay_order_id),
                INDEX idx_razorpay_payment_id (razorpay_payment_id),
                INDEX idx_status (status),
                INDEX idx_created_at (created_at),
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "  ✓ payment_logs table created\n";
    } catch (PDOException $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
    
    // 4. Create payment_refunds table
    echo "\n4. Creating payment_refunds table...\n";
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS payment_refunds (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                razorpay_payment_id VARCHAR(100) NOT NULL,
                razorpay_refund_id VARCHAR(100) NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                reason VARCHAR(255) DEFAULT NULL,
                status ENUM('pending', 'processed', 'failed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_order_id (order_id),
                INDEX idx_razorpay_payment_id (razorpay_payment_id),
                INDEX idx_razorpay_refund_id (razorpay_refund_id),
                INDEX idx_status (status),
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "  ✓ payment_refunds table created\n";
    } catch (PDOException $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
    
    // 5. Create payment_webhooks table
    echo "\n5. Creating payment_webhooks table...\n";
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS payment_webhooks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_type VARCHAR(100) NOT NULL,
                razorpay_payment_id VARCHAR(100) DEFAULT NULL,
                razorpay_order_id VARCHAR(100) DEFAULT NULL,
                payload TEXT NOT NULL,
                processed BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                processed_at TIMESTAMP NULL DEFAULT NULL,
                INDEX idx_event_type (event_type),
                INDEX idx_processed (processed),
                INDEX idx_razorpay_payment_id (razorpay_payment_id),
                INDEX idx_razorpay_order_id (razorpay_order_id),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "  ✓ payment_webhooks table created\n";
    } catch (PDOException $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Payment Integration Setup Complete! ===\n";
    
} catch (Exception $e) {
    echo "\nFatal Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
