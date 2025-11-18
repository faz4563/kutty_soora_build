<?php
// Check users table schema
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'db_config.php';

try {
    // Get users table structure
    $stmt = $pdo->query("DESCRIBE users");
    $schema = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'schema' => $schema
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
