<?php

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/helpers/Database.php';
require_once APP_PATH . '/helpers/Auth.php';
require_once APP_PATH . '/controllers/BaseController.php';
require_once APP_PATH . '/controllers/HomeController.php';

$controller = new HomeController();

// Simple routing
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');  // Remove trailing slashes
$url = strtolower($url);  // Make case-insensitive

if (empty($url) || $url === 'home') {
    $controller->index();
} elseif ($url === 'programs' || $url === 'departments') {
    $controller->programs();
} elseif ($url === 'staff') {
    $controller->staff();
} elseif ($url === 'history') {
    $controller->history();
} elseif ($url === 'videos' || $url === 'youtube') {
    $controller->videos();
} elseif ($url === 'contact') {
    $controller->contact();
} else {
    $controller->page($url);
}
