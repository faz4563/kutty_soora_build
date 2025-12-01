<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    echo "Testing database connection...\n";
    
    require_once 'db_config.php';
    
    echo "Database config loaded\n";
    
    // Test basic query
    $stmt = $pdo->query("SELECT COUNT(*) as product_count FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => "success",
        "message" => "Database connection working",
        "product_count" => $result['product_count'],
        "timestamp" => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => $e->getMessage(),
        "timestamp" => date('Y-m-d H:i:s')
    ]);
}
?>