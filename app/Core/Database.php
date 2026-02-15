<?php

class Database
{
    private static $instance = null;
    private $pdo;

    private $host;
    private $port;
    private $db_name;
    private $db_user;
    private $db_pass;

    private function __construct()
    {
        // Load .env
        $env = parse_ini_file(__DIR__ . '/../../.env');

        $this->host = $env['DB_HOST'] ?? '';
        $this->port = $env['DB_PORT'] ?? '';
        $this->db_name = $env['DB_NAME'] ?? '';
        $this->db_user = $env['DB_USER'] ?? '';
        $this->db_pass = $env['DB_PASS'] ?? '';

        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Singleton pattern
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    // Prevent cloning
    private function __clone()
    {
    }
}
