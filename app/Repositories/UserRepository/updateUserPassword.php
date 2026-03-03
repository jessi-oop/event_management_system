<?php

require_once __DIR__ . '/../../Core/Database.php';

class UpdateUserPasswordRepo
{
    private $db;

    public function __construct()
    {
        $this->db  = Database::getInstance()->getConnection();
    }

    public function updateUserPassword($user_id, $hashed_password)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password_hash = ?
                WHERE user_id = ?
            ");

            return $stmt->execute([$hashed_password, $user_id]);
        } catch (PDOException $e) {
            error_log('Error updating password: ' . $e->getMessage());
            return false;
        }
    }
}
