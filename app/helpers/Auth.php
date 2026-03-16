<?php

class Auth {
    public static function login($username, $password) {
        $db = Database::getInstance();
        
        // Look up user by username (stored in the name column)
        $user = $db->fetchOne(
            "SELECT * FROM users WHERE name = ?",
            [$username]
        );

        // If no user exists yet and default admin credentials are used,
        // create the seeded admin account on the fly.
        if (!$user && $username === 'admin' && $password === 'admin123') {
            $hash = self::hashPassword($password);
            $db->query(
                "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)",
                ['admin', 'admin', $hash, 'admin']
            );
            $user = $db->fetchOne(
                "SELECT * FROM users WHERE name = ?",
                ['admin']
            );
        }

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['login_time'] = time();
            return true;
        }

        return false;
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }

    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        // Check session timeout
        if (isset($_SESSION['login_time']) && 
            (time() - $_SESSION['login_time'] > SESSION_LIFETIME)) {
            self::logout();
            return false;
        }

        // Update last activity time
        $_SESSION['login_time'] = time();
        return true;
    }

    public static function user() {
        if (self::check()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role']
            ];
        }
        return null;
    }

    public static function requireAuth() {
        if (!self::check()) {
            header('Location: ' . ADMIN_URL . '/login.php');
            exit;
        }
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
