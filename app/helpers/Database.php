<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            // Create storage directory if it doesn't exist
            if (!file_exists(STORAGE_PATH)) {
                mkdir(STORAGE_PATH, 0755, true);
            }

            // Create database connection
            $this->connection = new PDO('sqlite:' . DB_PATH);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Enable foreign keys
            $this->connection->exec('PRAGMA foreign_keys = ON;');

            // Initialize database if it doesn't exist
            $this->initializeDatabase();
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function initializeDatabase() {
        // Check if tables exist
        $result = $this->connection->query(
            "SELECT name FROM sqlite_master WHERE type='table' AND name='users'"
        );
        
        if ($result->fetch() === false) {
            // Database is empty, initialize it
            $sql = file_get_contents(ROOT_PATH . '/config/database.sql');
            $this->connection->exec($sql);
        }
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Database query error: ' . $e->getMessage());
            return false;
        }
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch() : false;
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}
