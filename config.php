<?php
// Database connection settings.
// Fill these in with your actual MySQL host/database/username/password.
define('DB_HOST', 'localhost');
define('DB_NAME', 'chtm_admin');
define('DB_USER', 'root');
define('DB_PASS', '');

// Where uploaded images are stored on disk, and the URL path used in <img> tags.
define('UPLOAD_DIR_ANNOUNCEMENTS', __DIR__ . '/uploads/announcements/');
define('UPLOAD_URL_ANNOUNCEMENTS', 'uploads/announcements/');
define('UPLOAD_DIR_EVENTS', __DIR__ . '/uploads/events/');
define('UPLOAD_URL_EVENTS', 'uploads/events/');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Database connection failed. Please check config.php settings.');
}
