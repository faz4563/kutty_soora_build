<?php
/**
 * Execute Payment Schema
 * Run this script once to create payment-related tables
 */

require_once 'db_config.php';

try {
    // Read the schema file
    $sql = file_get_contents(__DIR__ . '/payment_schema.sql');
    
    if ($sql === false) {
        throw new Exception("Could not read payment_schema.sql file");
    }
    
    // Split by semicolons to execute each statement separately
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && 
                   strpos($stmt, '--') !== 0 && 
                   strlen(trim($stmt)) > 0;
        }
    );
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        if (empty(trim($statement))) continue;
        
        try {
            $pdo->exec($statement);
            $successCount++;
            echo "✓ Executed statement successfully\n";
        } catch (PDOException $e) {
            $errorCount++;
            echo "✗ Error: " . $e->getMessage() . "\n";
            // Continue with other statements even if one fails
        }
    }
    
    echo "\n=== Summary ===\n";
    echo "Success: $successCount statements\n";
    echo "Errors: $errorCount statements\n";
    
    if ($errorCount === 0) {
        echo "\n✓ Payment schema setup completed successfully!\n";
    } else {
        echo "\n⚠ Some errors occurred. Please review the messages above.\n";
    }
    
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
