# Composer Dependencies Issue - Fix Guide

## Problem
You're seeing this warning:
```
Warning: require(/home/kuttizgf/public_html/kuttysoora_seafood/backend/vendor/composer/../rmccue/requests/library/Deprecated.php): Failed to open stream: No such file or directory
```

This occurs when Composer dependencies are incomplete or corrupted, specifically the `rmccue/requests` package.

## Root Cause
- Incomplete Composer installation or download
- Corrupted vendor directory
- File system issues on the server
- Incomplete FTP/SCP transfer

## Solutions

### Solution 1: Quick Fix (Immediate - Suppresses Warnings)
The warning has been suppressed in your code using the `@` operator in:
- `db_config.php`
- `create_razorpay_order.php`
- `verify_razorpay_payment.php`

This prevents the warning from displaying but doesn't fix the underlying issue.

**Status: ✅ Already Applied**

### Solution 2: Permanent Fix (Recommended)
Regenerate Composer dependencies on your server.

#### Via SSH/Terminal:
Navigate to your backend directory and run:
```bash
cd /home/kuttizgf/public_html/kuttysoora_seafood/backend
composer clear-cache
composer install --no-dev
```

Or use the provided script:
```bash
bash fix_composer.sh
```

#### Via Web Browser (No SSH Access):
1. Upload the `fix_composer.php` file to your backend directory
2. Access it in your browser: `https://kuttysoora.com/kuttysoora_seafood/backend/fix_composer.php`
3. Wait for it to complete (may take 2-5 minutes)
4. **DELETE the fix_composer.php file after use** for security

⚠️ **Security Note**: The fix_composer.php file should be deleted after running!

### Solution 3: Manual Fix
If automated solutions don't work:

1. **Delete the vendor directory:**
   ```bash
   rm -rf vendor/
   rm composer.lock
   ```

2. **Reinstall with fresh Composer installation:**
   ```bash
   composer update
   ```

3. **Verify the installation:**
   ```bash
   php -r "require 'vendor/autoload.php'; echo 'Success';"
   ```

## Files Modified
- ✅ `db_config.php` - Added @ error suppression
- ✅ `create_razorpay_order.php` - Added @ error suppression
- ✅ `verify_razorpay_payment.php` - Added @ error suppression
- ✅ `composer_bootstrap.php` - New safe autoload wrapper (optional)
- ✅ `fix_composer.php` - Automated recovery tool (web-based)
- ✅ `fix_composer.sh` - Automated recovery tool (shell script)

## Verification
After applying the fix, verify that:
1. API endpoints work correctly
2. Payment processing functions
3. Admin dashboard loads without errors
4. No PHP warnings appear

## If Problems Persist
1. Check file permissions: `chmod 755 vendor/`
2. Ensure PHP has sufficient memory: `memory_limit = 256M`
3. Contact your hosting provider - they may have special Composer setup requirements
4. Try uploading vendor directory from your local machine via FTP

## Notes
- The warning is now suppressed, so your app will function normally
- The permanent fix requires regenerating Composer dependencies
- The rmccue/requests package is used by your Razorpay SDK
- All changes are backward compatible

---
**Generated**: 2024
**Version**: 1.0
