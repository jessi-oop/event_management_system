<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class GetEventByIdRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Used for single target search or retrieval of events
    public function getEventById($event_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM event_summary WHERE event_id = ?');
            $stmt->execute([$event_id]);
            $data = $stmt->fetch();

            if ($data) {
                return new Event($data);
            }

            return null;
        } catch (PDOException $e) {
            error_log('Error getting event: ' . $e->getMessage());
            return null;
        }
    }
}
