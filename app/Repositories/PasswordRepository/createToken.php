<?php

require_once __DIR__ . '/../../Core/Database.php';

class CreateToken {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function createToken($user_id, $token, $expires_at){
        try {
            $stmt = $this->db->prepare('CALL sp_create_password_reset(?, ?, ?)');
            $stmt->execute([$user_id, $token, $expires_at]);
            $stmt->closeCursor(); // Good practice with stored procedures
            
            return true;
        } catch (PDOException $e) {
            error_log('Error creating token: ' . $e->getMessage());
            return false;
        }
    }
}