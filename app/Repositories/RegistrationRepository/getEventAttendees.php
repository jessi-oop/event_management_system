<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Registration.php';

class GetEventAttendeesRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getEventAttendees($event_id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT u.user_id, u.full_name, u.email, r.registered_at 
                 FROM registrations r
                 JOIN users u ON r.user_id = u.user_id
                 WHERE r.event_id = ?
                 ORDER BY r.registered_at ASC"
            );
            $stmt->execute([$event_id]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting event attendees: " . $e->getMessage());
            return [];
        }
    }
}