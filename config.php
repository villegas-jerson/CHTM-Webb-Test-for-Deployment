<?php
// TiDB Cloud database connection settings

define('DB_HOST', 'gateway01.us-west-2.prod.aws.tidbcloud.com');
define('DB_NAME', 'chtm_admin');
define('DB_PORT', 4000);
define('DB_USER', '23KRec1ZH9AB1Qp.root');
define('DB_PASS', 'sI9x6f5TFJoCPRqR'); // replace with your password


// Uploaded images paths

define('UPLOAD_DIR_ANNOUNCEMENTS', __DIR__ . '/uploads/announcements/');
define('UPLOAD_URL_ANNOUNCEMENTS', 'uploads/announcements/');

define('UPLOAD_DIR_EVENTS', __DIR__ . '/uploads/events/');
define('UPLOAD_URL_EVENTS', 'uploads/events/');


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = mysqli_init();

    // Enable SSL connection for TiDB Cloud
    mysqli_ssl_set(
        $conn,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL
    );

    mysqli_real_connect(
        $conn,
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT,
        NULL,
        MYSQLI_CLIENT_SSL
    );

    $conn->set_charset('utf8mb4');

} catch (mysqli_sql_exception $e) {

    die('Database connection failed: ' . $e->getMessage());

}