<?php

class Database
{
    private static $instance = null;
    private $pdo;

    public function __construct()
    {
        $env = parse_ini_file(__DIR__ . '/../../.env');

        try {
            $this->pdo = new PDO(
                "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4",
                $env['DB_USER'],
                $env['DB_PASS']
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    //Singleton pattern - only one db connection
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
