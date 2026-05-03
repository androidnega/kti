#!/usr/bin/env php
<?php
/**
 * Set the admin login (users.name = "admin") password to a known value.
 * Run once after deploy: php tools/reset_admin_password.php
 */
require_once dirname(__DIR__) . '/config/config.php';
require_once APP_PATH . '/helpers/Database.php';
require_once APP_PATH . '/helpers/Auth.php';

$pass = getenv('KTI_ADMIN_PASSWORD') ?: 'Admin@KTI2026';
$db = Database::getInstance();
$db->query(
    'UPDATE users SET password_hash = ? WHERE name = ? OR email = ?',
    [Auth::hashPassword($pass), 'admin', 'admin']
);
$n = (int) $db->getConnection()->query('SELECT changes()')->fetchColumn();
if ($n < 1) {
    fwrite(STDERR, "No user named \"admin\" found. Create one in admin or database.sql first.\n");
    exit(1);
}
fwrite(STDERR, "Admin password updated. Username: admin  Password: {$pass}\n");
