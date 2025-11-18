<?php
// Script to set admin password for existing admin users
require_once 'db_config.php';

$adminPassword = 'SecureAdminPass123!';

try {
    // Find all admin users
    $stmt = $pdo->query("SELECT id, mobile, name, role FROM users WHERE role = 'admin'");
    $adminUsers = $stmt->fetchAll();
    
    if (empty($adminUsers)) {
        echo "No admin users found in the database.\n";
        echo "Creating a default admin user...\n";
        
        // Create default admin user
        $defaultMobile = '9999999999'; // Default admin mobile
        $defaultName = 'Admin User';
        $hash = password_hash($adminPassword, PASSWORD_DEFAULT);
        
        $insertStmt = $pdo->prepare(
            "INSERT INTO users (mobile, name, role, password_hash) VALUES (?, ?, 'admin', ?)"
        );
        $insertStmt->execute([$defaultMobile, $defaultName, $hash]);
        
        echo "✓ Created default admin user:\n";
        echo "  Mobile: $defaultMobile\n";
        echo "  Name: $defaultName\n";
        echo "  Password: $adminPassword\n";
        echo "  Role: admin\n\n";
    } else {
        echo "Found " . count($adminUsers) . " admin user(s):\n\n";
        
        // Set password for all existing admin users
        $hash = password_hash($adminPassword, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        
        foreach ($adminUsers as $admin) {
            $updateStmt->execute([$hash, $admin['id']]);
            echo "✓ Updated password for admin:\n";
            echo "  ID: {$admin['id']}\n";
            echo "  Mobile: {$admin['mobile']}\n";
            echo "  Name: {$admin['name']}\n";
            echo "  Password: $adminPassword\n\n";
        }
    }
    
    echo "Admin password setup completed successfully!\n";
    echo "\nYou can now login as admin using:\n";
    echo "- Mobile number of any admin user\n";
    echo "- Password: $adminPassword\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>