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
            $this->migrateProgramsSchema();
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

    /**
     * Add program gallery columns and program_media table for existing SQLite DBs.
     */
    private function migrateProgramsSchema() {
        $cols = $this->connection->query('PRAGMA table_info(programs)')->fetchAll(PDO::FETCH_COLUMN, 1);
        if (!in_array('slug', $cols, true)) {
            $this->connection->exec('ALTER TABLE programs ADD COLUMN slug TEXT');
        }
        if (!in_array('faculty', $cols, true)) {
            $this->connection->exec('ALTER TABLE programs ADD COLUMN faculty TEXT');
        }
        if (!in_array('cover_image', $cols, true)) {
            $this->connection->exec('ALTER TABLE programs ADD COLUMN cover_image TEXT');
        }
        if (!in_array('detail_content', $cols, true)) {
            $this->connection->exec('ALTER TABLE programs ADD COLUMN detail_content TEXT');
        }
        if (!in_array('updated_at', $cols, true)) {
            $this->connection->exec('ALTER TABLE programs ADD COLUMN updated_at DATETIME');
        }

        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS program_media (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                program_id INTEGER NOT NULL,
                media_type TEXT NOT NULL DEFAULT \'image\',
                file_path TEXT,
                external_url TEXT,
                caption TEXT,
                sort_order INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
            )'
        );
        $this->connection->exec(
            'CREATE INDEX IF NOT EXISTS idx_program_media_program ON program_media(program_id)'
        );
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
