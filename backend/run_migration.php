<?php
// Database Migration Script
require_once 'db_config.php';

echo "Running database migration for users table...\n";

try {
    // Add missing columns to users table if they don't exist
    $columns_to_add = [
        "address TEXT DEFAULT ''",
        "house VARCHAR(100) DEFAULT ''",
        "street VARCHAR(100) DEFAULT ''",
        "area VARCHAR(100) DEFAULT ''", 
        "city VARCHAR(100) DEFAULT ''",
        "pin_code VARCHAR(10) DEFAULT ''",
        "landmark VARCHAR(255) DEFAULT ''",
        "referral VARCHAR(255) DEFAULT ''"
    ];

    foreach ($columns_to_add as $column) {
        $column_name = explode(' ', $column)[0];
        
        // Check if column exists
        $stmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'kuttysoora_seafood' AND TABLE_NAME = 'users' AND COLUMN_NAME = ?");
        $stmt->execute([$column_name]);
        $exists = $stmt->fetch();
        
        if (!$exists) {
            $sql = "ALTER TABLE users ADD COLUMN $column";
            $pdo->exec($sql);
            echo "Added column: $column_name\n";
        } else {
            echo "Column already exists: $column_name\n";
        }
    }

    echo "Migration completed successfully!\n";

    // Show final table structure
    echo "\nCurrent users table structure:\n";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>