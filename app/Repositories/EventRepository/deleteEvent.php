<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class DeleteEventRepo
{
    private $db;

    // Used for deleting events
    public function deleteEvent($event_id)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM events WHERE event_id = ?');
            $data = $stmt->execute([$event_id]);
            return $data;
        } catch (PDOException $e) {
            error_log('Error deleting event: ' . $e->getMessage());
            return false;
        }
    }
}
