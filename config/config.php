<?php

// Application Configuration
define('APP_NAME', 'Kikam Technical Institute');
define('APP_URL', 'http://localhost/KTI/public');
define('ADMIN_URL', 'http://localhost/KTI/admin');

// Database Configuration
define('DB_PATH', __DIR__ . '/../storage/database.sqlite');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', STORAGE_PATH . '/uploads');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// Environment
define('ENVIRONMENT', 'development'); // development or production

// Error Reporting
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('UTC');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
