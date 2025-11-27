<?php
require_once 'db_config.php';

echo "Starting database migration...\n";

try {
    // Add new columns to products table using ALTER TABLE ADD IF NOT EXISTS (MySQL 5.7+)
    // For older MySQL, we'll use a different approach
    $alterations = [
        "brand" => "ALTER TABLE products ADD COLUMN brand VARCHAR(100) DEFAULT 'Kutty Soora'",
        "sku" => "ALTER TABLE products ADD COLUMN sku VARCHAR(50) UNIQUE",
        "availability" => "ALTER TABLE products ADD COLUMN availability VARCHAR(50) DEFAULT 'in_stock'",
        "weight" => "ALTER TABLE products ADD COLUMN weight VARCHAR(100)",
        "dimensions" => "ALTER TABLE products ADD COLUMN dimensions VARCHAR(100)",
        "material" => "ALTER TABLE products ADD COLUMN material VARCHAR(100)",
        "color" => "ALTER TABLE products ADD COLUMN color VARCHAR(50)",
        "images" => "ALTER TABLE products ADD COLUMN images TEXT",
        "tags" => "ALTER TABLE products ADD COLUMN tags TEXT",
        "created_date" => "ALTER TABLE products ADD COLUMN created_date DATE",
        "last_updated" => "ALTER TABLE products ADD COLUMN last_updated DATE"
    ];
    
    foreach ($alterations as $columnName => $sql) {
        try {
            // Check if column already exists
            $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE '$columnName'");
            if ($stmt->rowCount() == 0) {
                // Column doesn't exist, add it
                $pdo->exec($sql);
                echo "✓ Successfully added column: $columnName\n";
            } else {
                echo "! Column already exists: $columnName\n";
            }
        } catch (PDOException $e) {
            echo "! Error with column $columnName: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nMigration completed successfully!\n";
    echo "You can now run import_products.php to import the products from JSON.\n";
    
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
}
?>
