<?php
/**
 * Composer Dependencies Recovery Tool (PHP)
 * 
 * This tool can be run via web to fix broken Composer dependencies
 * Usage: Access this file in your browser from /backend/fix_composer.php
 * 
 * Security: Add authentication check before using in production
 */

// Security: Implement authentication in production
// if (!isset($_GET['token']) || $_GET['token'] !== 'YOUR_SECRET_TOKEN') {
//     http_response_code(403);
//     echo "Unauthorized";
//     exit;
// }

header('Content-Type: text/plain; charset=utf-8');

$backendDir = __DIR__;
$composerJson = $backendDir . '/composer.json';
$composerLock = $backendDir . '/composer.lock';
$vendorDir = $backendDir . '/vendor';

echo "=== Composer Dependencies Recovery Tool ===\n\n";

// Step 1: Check composer.json
echo "[1/4] Checking composer.json...\n";
if (!file_exists($composerJson)) {
    echo "❌ Error: composer.json not found\n";
    exit(1);
}
echo "✅ composer.json found\n\n";

// Step 2: Backup vendor directory
echo "[2/4] Backing up vendor directory...\n";
if (is_dir($vendorDir)) {
    $timestamp = date('YmdHis');
    $backupDir = $backendDir . '/vendor_backup_' . $timestamp;
    
    if (rename($vendorDir, $backupDir)) {
        echo "✅ Vendor directory backed up to vendor_backup_$timestamp\n\n";
    } else {
        echo "⚠️  Could not backup vendor directory (may be in use)\n\n";
    }
}

// Step 3: Run composer install
echo "[3/4] Installing dependencies...\n";
echo "Please wait, this may take a few minutes...\n\n";

// Use composer to reinstall
$output = [];
$return = 0;

// Try to run composer install
if (shell_exec('which composer') || file_exists('/usr/local/bin/composer')) {
    exec('cd ' . escapeshellarg($backendDir) . ' && composer install --no-interaction 2>&1', $output, $return);
} else {
    // Fallback: download composer.phar if not available
    echo "Composer not found in PATH, attempting to use composer.phar...\n";
    
    if (!file_exists($backendDir . '/composer.phar')) {
        echo "Downloading composer...\n";
        exec('cd ' . escapeshellarg($backendDir) . ' && curl -sS https://getcomposer.org/installer | php 2>&1', $output, $return);
    }
    
    if ($return === 0) {
        exec('cd ' . escapeshellarg($backendDir) . ' && php composer.phar install --no-interaction 2>&1', $output, $return);
    }
}

if ($return === 0) {
    echo "✅ Dependencies installed successfully\n";
    echo implode("\n", array_slice($output, -5)) . "\n\n";
} else {
    echo "⚠️  Composer returned status code: $return\n";
    echo "Last 10 lines of output:\n";
    echo implode("\n", array_slice($output, -10)) . "\n\n";
}

// Step 4: Verify autoload
echo "[4/4] Verifying autoload file...\n";
if (file_exists($backendDir . '/vendor/autoload.php')) {
    echo "✅ Autoload file verified\n\n";
} else {
    echo "❌ Autoload file not found\n\n";
}

// Summary
echo "=== Recovery Summary ===\n";
echo "Status: " . ($return === 0 ? "Success ✅" : "Partial ⚠️") . "\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "\n";

// Additional recommendations
echo "=== Recommendations ===\n";
echo "1. Test your API endpoints to ensure everything is working\n";
echo "2. If the warning persists, the rmccue/requests package may be corrupted\n";
echo "3. Try removing composer.lock and running: composer update\n";
echo "4. If problems continue, contact your hosting provider\n";
echo "\n";

// Alternative: Delete this file after use
echo "⚠️  Remember to delete this file (fix_composer.php) after use for security!\n";
?>
