<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Registration.php';

class CancelRegistrationRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function cancelRegistration($user_id, $event_id){
        try {
            $stmt = $this->db->prepare('DELETE FROM registrations WHERE user_id = ? AND event_id = ?');
            $data = $stmt->execute([$user_id, $event_id]);
            
            return $data;
        } catch (PDOException $e) {
            error_log('Error canceling registration: ' . $e->getMessage());
            return false;
        }
    }
}