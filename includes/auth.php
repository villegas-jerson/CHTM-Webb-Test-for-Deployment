<?php
// Include this at the very top of any page that should require a logged-in admin.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_id'])) {
    header('Location: Admin.html');
    exit;
}
