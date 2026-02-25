<?php

require_once __DIR__ . '/../../Core/Database.php';

class FindByToken {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByToken($token){
        try {
            $stmt = $this->db->prepare('CALL sp_validate_token(?)');
            $stmt->execute([$token]);
            
            $result = $stmt->fetch();
            $stmt->closeCursor();
            
            if($result){
                return $result;
            }

            return null;
        } catch (PDOException $e) {
            error_log('Error finding token: ' . $e->getMessage());
            return null;
        }
    }
}