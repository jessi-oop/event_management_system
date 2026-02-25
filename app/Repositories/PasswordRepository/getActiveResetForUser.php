<?php

require_once __DIR__ . '/../../Core/Database.php';

class GetActiveResetForUser {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getActiveResetForUser($user_id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM password_resets 
                 WHERE user_id = ? AND used = 0 AND expires_at > NOW()
                 ORDER BY created_at DESC LIMIT 1"
            );
            $stmt->execute([$user_id]);
            
            // ⭐ PDO: Use fetch() directly
            $result = $stmt->fetch();
            
            if ($result) {
                return $result;
            }
            
            return null;
        } catch (PDOException $e) {
            error_log('Error getting active reset for user: ' . $e->getMessage());
            return null;
        }
    }
}