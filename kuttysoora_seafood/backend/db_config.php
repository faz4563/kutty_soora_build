<?php
// AGGRESSIVE FIX: Handle broken Composer dependencies gracefully
// Try to load dotenv if available, but don't fail if Composer is broken
$composerLoaded = false;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        // Use include instead of require to avoid fatal errors
        // Wrap in try-catch to handle both old and new PHP error handling
        $composerLoaded = @include_once __DIR__ . '/vendor/autoload.php';
        
        // Verify the autoloader actually worked
        if (function_exists('spl_autoload_register') && $composerLoaded) {
            $composerLoaded = true;
        }
    } catch (Throwable $e) {
        // Composer is broken, continue without it
        error_log("Composer autoload failed: " . $e->getMessage());
        $composerLoaded = false;
    }
}

// Try to load dotenv if Composer worked
if ($composerLoaded && class_exists('Dotenv\Dotenv')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } catch (Throwable $e) {
        // Dotenv failed, will try direct .env parsing
        error_log("Dotenv loading failed: " . $e->getMessage());
    }
}

// If $_ENV is not set, try to parse .env file manually
if (!isset($_ENV['DB_HOST']) && file_exists(__DIR__ . '/.env')) {
    $envFile = file_get_contents(__DIR__ . '/.env');
    $lines = explode("\n", $envFile);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Check if required environment variables exist
$required = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
$missing = [];
foreach ($required as $var) {
    if (!isset($_ENV[$var]) || empty($_ENV[$var])) {
        $missing[] = $var;
    }
}

if (!empty($missing)) {
    http_response_code(500);
    echo json_encode([
        "error" => "Missing required environment variables",
        "missing" => $missing,
        "env_file_exists" => file_exists(__DIR__ . '/.env'),
        "vendor_exists" => file_exists(__DIR__ . '/vendor/autoload.php')
    ]);
    exit();
}

$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "error" => "Database connection failed",
        "details" => $e->getMessage(),
        "host" => $host,
        "database" => $db
    ]);
    exit();
}
?>
