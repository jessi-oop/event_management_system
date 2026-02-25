<?php
require_once __DIR__ . '/../../Core/Database.php';

class DeleteExpiredToken {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function deleteExpiredToken(){
        try {
            $stmt = $this->db->prepare('CALL sp_cleanup_expired_tokens()');
            $stmt->execute();

            $result = $stmt->fetch();
            $stmt->closeCursor();
            
            if($result){
                return $result['deleted_count'];
            }

            return 0;
        } catch (PDOException $e) {
            error_log('Error deleting expired tokens: ' . $e->getMessage());
            return 0;
        }
    }
}