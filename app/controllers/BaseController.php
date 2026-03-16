<?php

class BaseController {
    protected function view($viewPath, $data = []) {
        extract($data);
        
        $viewFile = APP_PATH . '/views/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("View not found: $viewPath");
        }
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}
