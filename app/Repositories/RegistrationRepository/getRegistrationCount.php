<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Registration.php';

class GetRegistrationCountRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getRegistrationCount($event_id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM registrations WHERE event_id = ?"
            );
            $stmt->execute([$event_id]);
            
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting registration count: " . $e->getMessage());
            return 0;
        }
    }
}