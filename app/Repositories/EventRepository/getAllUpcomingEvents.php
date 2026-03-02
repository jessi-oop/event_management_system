<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class GetAllUpcomingEventsRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    //Used to get all the upcoming events that are approved
    public function getAllUpcomingEvents()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM event_summary 
                                      WHERE event_date >= CURDATE() 
                                      AND approval_status = "approved"
                                      ORDER BY event_date ASC, event_time ASC');

            $events = [];
            while ($data = $stmt->fetch()) {
                $events[] = new Event($data);
            }

            return $events;
        } catch (PDOException $e) {
            error_log('Error getting upcoming events: ' . $e->getMessage());
            return [];
        }
    }
}
