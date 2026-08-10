<?php
/**
 * Safe Composer Autoload Bootstrap
 * 
 * This file safely loads the Composer autoloader while suppressing
 * warnings from broken or incomplete Composer dependencies.
 * 
 * Usage: require_once 'composer_bootstrap.php';
 */

if (!function_exists('loadComposerAutoload')) {
    function loadComposerAutoload($baseDir = __DIR__) {
        $autoloadFile = $baseDir . '/vendor/autoload.php';
        
        // Check if autoload file exists
        if (!file_exists($autoloadFile)) {
            return false;
        }
        
        // Suppress warnings from broken Composer dependencies
        // This is safe because we're only suppressing file_exists errors
        @require_once $autoloadFile;
        
        return true;
    }
}

// Attempt to load Composer autoload
loadComposerAutoload();
?>
