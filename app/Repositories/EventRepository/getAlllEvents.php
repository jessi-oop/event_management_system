<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class GetAllEvents {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

     // Get all events, past and future
    public function getAllEvents()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM event_summary ORDER BY event_date DESC, event_time DESC');

            $events = [];
            while ($data = $stmt->fetch()) {
                $events[] = new Event($data);
            }

            return $events;
        } catch (PDOException $e) {
            error_log('Error getting all events: ' . $e->getMessage());
            return [];
        }
    }
}