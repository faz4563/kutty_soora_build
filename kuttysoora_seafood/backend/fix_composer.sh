#!/bin/bash
# Composer Dependencies Recovery Script
# This script will regenerate Composer dependencies if they're corrupted

echo "=== Composer Dependencies Recovery ==="
echo ""

# Check if we're in the backend directory
if [ ! -f "composer.json" ]; then
    echo "❌ Error: composer.json not found"
    echo "Please run this script from the backend directory"
    exit 1
fi

echo "📦 Step 1: Backing up vendor directory..."
if [ -d "vendor" ]; then
    timestamp=$(date +%Y%m%d_%H%M%S)
    mv vendor "vendor_backup_$timestamp"
    echo "✅ Vendor directory backed up to vendor_backup_$timestamp"
fi

echo ""
echo "📦 Step 2: Clearing Composer cache..."
composer clear-cache
echo "✅ Cache cleared"

echo ""
echo "📦 Step 3: Installing dependencies from composer.lock..."
if [ -f "composer.lock" ]; then
    composer install --no-interaction --prefer-dist --no-dev
    if [ $? -eq 0 ]; then
        echo "✅ Dependencies installed successfully"
    else
        echo "❌ Failed to install dependencies"
        exit 1
    fi
else
    echo "⚠️  composer.lock not found, running composer update instead..."
    composer update --no-interaction --prefer-dist --no-dev
    if [ $? -eq 0 ]; then
        echo "✅ Dependencies updated successfully"
    else
        echo "❌ Failed to update dependencies"
        exit 1
    fi
fi

echo ""
echo "📦 Step 4: Verifying autoload file..."
if [ -f "vendor/autoload.php" ]; then
    echo "✅ Autoload file verified"
else
    echo "❌ Autoload file not found after installation"
    exit 1
fi

echo ""
echo "✅ Composer recovery completed successfully!"
echo ""
echo "Your dependencies have been regenerated. The warning should now be resolved."
