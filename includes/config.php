<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'parking_admin');
define('DB_PASS', 'securepassword123');
define('DB_NAME', 'parking_db');

// Application Settings
define('APP_NAME', 'Parking Management System');
define('BASE_URL', 'http://localhost/parking-system');

// Timezone Configuration
date_default_timezone_set('America/New_York');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Session Configuration
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Create database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("System maintenance in progress. Please try again later.");
}

// Create required directories if they don't exist
$directories = [
    __DIR__ . '/../logs',
    __DIR__ . '/../uploads'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}
?>