<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/User.php';

class updateUser {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

     // Update user info
    public function updateUser($user_id, $full_name, $email)
    {
        try {
            $stmt = $this->db->prepare('UPDATE users SET full_name = ?, email = ? WHERE user_id = ?');
            return $stmt->execute([$full_name, $email, $user_id]);
        } catch (PDOException $e) {
            error_log('Error updating user info: ' . $e->getMessage());
            return false;
        }
    }
}