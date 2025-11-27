<?php
require_once 'db_config.php';

header('Content-Type: application/json');

try {
    // Get table schema
    $stmt = $pdo->query("DESCRIBE orders");
    $schema = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
