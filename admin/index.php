<?php

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/helpers/Database.php';
require_once APP_PATH . '/helpers/Auth.php';
require_once APP_PATH . '/controllers/BaseController.php';
require_once APP_PATH . '/controllers/AdminController.php';

// Require authentication
Auth::requireAuth();

$controller = new AdminController();

// Simple routing
$action = $_GET['action'] ?? 'dashboard';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'dashboard':
        $controller->dashboard();
        break;
    
    // Pages
    case 'pages':
        $controller->pages();
        break;
    case 'page_create':
        $controller->pageForm();
        break;
    case 'page_edit':
        $controller->pageForm($id);
        break;
    case 'page_save':
        $controller->pageSave();
        break;
    case 'page_delete':
        $controller->pageDelete($id);
        break;
    
    // Staff
    case 'staff':
        $controller->staff();
        break;
    case 'staff_create':
        $controller->staffForm();
        break;
    case 'staff_edit':
        $controller->staffForm($id);
        break;
    case 'staff_save':
        $controller->staffSave();
        break;
    case 'staff_delete':
        $controller->staffDelete($id);
        break;
    
    // Programs
    case 'programs':
        $controller->programs();
        break;
    case 'program_create':
        $controller->programForm();
        break;
    case 'program_edit':
        $controller->programForm($id);
        break;
    case 'program_save':
        $controller->programSave();
        break;
    case 'program_delete':
        $controller->programDelete($id);
        break;
    
    // Logout
    case 'logout':
        Auth::logout();
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    
    default:
        $controller->dashboard();
}
