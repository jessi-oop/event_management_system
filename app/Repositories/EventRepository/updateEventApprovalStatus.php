<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class UpdateEventStatusRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateEventStatus($event_id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE events SET approval_status = 'pending' WHERE event_id = ?");
            $data = $stmt->execute([$event_id]);

            return $data;
        } catch (PDOException $e) {
            error_log('Error in updating event status. ' . $e->getMessage());
            return false;
        }

    }
}
