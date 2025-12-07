<?php
require_once '../cors_headers.php';
require_once '../db_config.php';
require_once '../jwt_auth.php';

header('Content-Type: application/json');

// Verify JWT token
$user = verifyJWT();
if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Admin access required']);
    exit;
}

try {
    // Get all refund requests with order details
    $stmt = $pdo->prepare("
        SELECT 
            rr.*,
            o.order_number,
            o.total_amount as order_amount,
            o.payment_method,
            o.payment_status,
            o.razorpay_payment_id,
            o.razorpay_order_id,
            u.phone as customer_phone,
            u.name as customer_name
        FROM refund_requests rr
        JOIN orders o ON rr.order_id = o.id
        JOIN users u ON rr.user_id = u.id
        ORDER BY rr.created_at DESC
    ");
    $stmt->execute();
    $refund_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'refund_requests' => $refund_requests
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
