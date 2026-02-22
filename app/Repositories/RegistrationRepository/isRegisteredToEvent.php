<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Registration.php';

class IsRegisteredToEventRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function isRegisteredToEvent($user_id, $event_id) {
        try {
             $stmt = $this->db->prepare('SELECT COUNT(*) FROM registrations WHERE user_id = ? AND event_id = ?');
        $stmt->execute([$user_id, $event_id]);
        $data = $stmt->fetchColumn() > 0;

        if($data) {
            return $data;
        }

        return false;
        } catch (PDOException $e) {
            error_log('Error checking registrations:' . $e->getMessage());
            return false;
        }
    }
}