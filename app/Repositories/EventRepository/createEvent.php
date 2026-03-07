<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class CreateEventRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Used for creating events
    public function createEvent(
        $organizer_id,
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity,
        $image_path,
        $approval_status = 'pending'
    ) {
        try {
            $stmt = $this->db->prepare('INSERT INTO events 
            (organizer_id, category_id, title, description, event_date,
            event_time, location, capacity, image_path, approval_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)') ;

            $data = $stmt->execute([$organizer_id, $category_id, $title, $description, $event_date,
            $event_time, $location, $capacity, $image_path, $approval_status]);

            if ($data) {
                return $this->db->lastInsertId(); //Returns the id of the last inserted row in the table
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error in creating the event: ' . $e->getMessage());
            return false;
        }
    }
}
