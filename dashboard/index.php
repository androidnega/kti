<?php

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/helpers/Database.php';
require_once APP_PATH . '/helpers/Auth.php';
require_once APP_PATH . '/controllers/BaseController.php';
require_once APP_PATH . '/controllers/AdminController.php';

// Protect dashboard with authentication
Auth::requireAuth();

$controller = new AdminController();
$controller->dashboard();

