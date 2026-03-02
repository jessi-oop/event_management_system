<?php

require_once __DIR__ . '/../../Core/Database.php';

class UpdateEventImageRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateEventImage($event_id, $image_path)
    {
        try {
            $stmt = $this->db->prepare('UPDATE events SET image_path = ? WHERE event_id = ?');
            return $stmt->execute([$image_path, $event_id]);
        } catch (PDOException $e) {
            error_log('Error updating event image: ' . $e->getMessage());
            return false;
        }
    }
}
