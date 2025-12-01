-- Payment Integration Database Schema
-- Run this script to add necessary tables and columns for Razorpay integration

-- Add payment-related columns to orders table
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS razorpay_order_id VARCHAR(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS razorpay_payment_id VARCHAR(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS razorpay_signature VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS payment_status ENUM('pending', 'initiated', 'paid', 'failed', 'refunded') DEFAULT 'pending',
ADD INDEX idx_razorpay_order_id (razorpay_order_id),
ADD INDEX idx_razorpay_payment_id (razorpay_payment_id),
ADD INDEX idx_payment_status (payment_status);

-- Create payment_logs table for tracking all payment transactions
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create payment_refunds table for tracking refunds
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create table for storing payment gateway webhooks
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
