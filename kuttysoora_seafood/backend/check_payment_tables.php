<?php
/**
 * Check if payment tables exist
 */

require_once 'db_config.php';

try {
    echo "Checking payment-related database structure...\n\n";
    
    // Check if payment_logs table exists
    $result = $pdo->query("SHOW TABLES LIKE 'payment_logs'");
    if ($result->rowCount() > 0) {
        echo "✓ payment_logs table exists\n";
    } else {
        echo "✗ payment_logs table DOES NOT exist\n";
    }
    
    // Check if payment_refunds table exists
    $result = $pdo->query("SHOW TABLES LIKE 'payment_refunds'");
    if ($result->rowCount() > 0) {
        echo "✓ payment_refunds table exists\n";
    } else {
        echo "✗ payment_refunds table DOES NOT exist\n";
    }
    
    // Check if payment_webhooks table exists
    $result = $pdo->query("SHOW TABLES LIKE 'payment_webhooks'");
    if ($result->rowCount() > 0) {
        echo "✓ payment_webhooks table exists\n";
    } else {
        echo "✗ payment_webhooks table DOES NOT exist\n";
    }
    
    // Check orders table for payment columns
    echo "\nChecking orders table columns...\n";
    $result = $pdo->query("DESCRIBE orders");
    $columns = $result->fetchAll(PDO::FETCH_COLUMN);
    
    $paymentColumns = ['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'payment_status'];
    foreach ($paymentColumns as $col) {
        if (in_array($col, $columns)) {
            echo "✓ orders.$col exists\n";
        } else {
            echo "✗ orders.$col DOES NOT exist\n";
        }
    }
    
    // Show all orders columns
    echo "\nAll columns in orders table:\n";
    foreach ($columns as $col) {
        echo "  - $col\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
