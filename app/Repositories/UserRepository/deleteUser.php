<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/User.php';

class DeleteUserRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }


    // Delete user
    public function deleteUser($user_id)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE user_id = ?');
            return $stmt->execute([$user_id]);
        } catch (PDOException $e) {
            error_log('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }
}
