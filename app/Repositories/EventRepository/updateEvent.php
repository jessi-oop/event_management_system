<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class UpdateEventRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Used to update event details
    public function updateEvent(
        $event_id,
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity
    ) {
        try {
            $stmt = $this->db->prepare('UPDATE events 
            SET category_id = ?, 
            title = ?, 
            description = ?, 
            event_date = ?,
            event_time = ?, 
            location = ?, 
            capacity = ? 
            WHERE event_id = ?');

            $data = $stmt->execute([$category_id, $title, $description, $event_date, $event_time, $location, $capacity, $event_id]);

            return $data;
        } catch (PDOException $e) {
            error_log('Error updating event": ' . $e->getMessage());
            return null;
        }
    }
}
