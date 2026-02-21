<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class GetEventByOrganizerRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Used to get events by organizer
    public function getEventByOrganizer($organizer_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM event_summary WHERE organizer_id = ?');
            $stmt->execute([$organizer_id]);

            $events = [];
            while ($data = $stmt->fetch()) {
                $events[] = new Event($data);
            }

            return $events;
        } catch (PDOException $e) {
            error_log('Error getting event: ' . $e->getMessage());
            return [];
        }
    }
}
