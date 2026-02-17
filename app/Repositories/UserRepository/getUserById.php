<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/User.php';

class getUserById {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Find user by ID
    public function getUserById($user_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE user_id = ?');
            $stmt -> execute([$user_id]);
            $data = $stmt->fetch();

            if ($data) {
                return new User($data);
            }
        } catch (PDOException $e) {
            error_log('Error finding user: ' . $e->getMessage());
            return null;
        }
    }
}