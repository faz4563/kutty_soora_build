<?php
/**
 * Request Refund API
 * Allows users to request refund for cancelled/delivered orders
 */

require_once 'cors_headers.php';

// Load environment variables
function loadEnv($file) {
    if (!file_exists($file)) {
        return false;
    }
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

require_once 'db_config.php';
require_once 'jwt_auth.php';

// Validate JWT token
try {
    $tokenPayload = JWTAuth::requireAuth();
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication failed']);
    exit();
}

$user_id = $tokenPayload['user_id'];

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;
$reason = isset($data['reason']) ? trim($data['reason']) : '';

if (!$order_id) {
    http_response_code(400);
    echo json_encode(['error' => 'order_id is required']);
    exit();
}

if (empty($reason)) {
    http_response_code(400);
    echo json_encode(['error' => 'Refund reason is required']);
    exit();
}

try {
    // Check if order exists and belongs to user
    $stmt = $pdo->prepare("
        SELECT id, status, payment_status, payment_method, total_amount, razorpay_payment_id 
        FROM orders 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();

    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit();
    }

    // Validate order is eligible for refund
    if (!in_array($order['status'], ['cancelled', 'delivered'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Only cancelled or delivered orders can request refund']);
        exit();
    }

    // Check if payment was made
    if ($order['payment_status'] !== 'paid') {
        http_response_code(400);
        echo json_encode(['error' => 'Only paid orders are eligible for refund']);
        exit();
    }

    // Check if refund already requested/processed
    if (in_array($order['payment_status'], ['refunded', 'partially_refunded'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Refund already processed for this order']);
        exit();
    }

    // Check if refund request already exists
    $checkStmt = $pdo->prepare("SELECT id FROM refund_requests WHERE order_id = ?");
    $checkStmt->execute([$order_id]);
    if ($checkStmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Refund already requested for this order']);
        exit();
    }

    // Create refund_requests table if not exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS refund_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            user_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            reason TEXT NOT NULL,
            status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
            admin_notes TEXT,
            razorpay_refund_id VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (user_id) REFERENCES users(id),
            INDEX idx_order_id (order_id),
            INDEX idx_user_id (user_id),
            INDEX idx_status (status)
        )
    ");

    // Insert refund request
    $insertStmt = $pdo->prepare("
        INSERT INTO refund_requests (order_id, user_id, amount, reason, status)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $insertStmt->execute([$order_id, $user_id, $order['total_amount'], $reason]);

    // Update order to indicate refund requested
    $updateStmt = $pdo->prepare("
        UPDATE orders 
        SET admin_notes = CONCAT(COALESCE(admin_notes, ''), '\n[', NOW(), '] Refund requested by customer: ', ?)
        WHERE id = ?
    ");
    $updateStmt->execute([$reason, $order_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Refund request submitted successfully',
        'refund_request_id' => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to submit refund request']);
}
?>
