<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/Event.php';

class EventRepository
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
        $capacity
    ) {
        try {
            $stmt = $this->db->prepare('INSERT INTO events 
            (organizer_id, category_id, title, description, event_date,
            event_time, location, capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)') ;

            $data = $stmt->execute([$organizer_id, $category_id, $title, $description, $event_date,
            $event_time, $location, $capacity]);

            if ($data) {
                return $this->db->lastInsertId(); //Returns the id of the last inserted row in the table
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error in creating the event: ' . $e->getMessage());
            return false;
        }
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

    //Used to get all the upcoming events
    public function getAllUpcomingEvents()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM event_summary WHERE event_date >= CURDATE() ORDER BY event_date ASC, event_time ASC ');

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

    // Used for single target search or retrieval of events
    public function getEventById($event_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM events WHERE event_id = ?');
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

    // Used to get events by organizer
    public function getEventByOrganizer($organizer_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM event_summary WHERE organizer_id = ?');
            $stmt->execute([$organizer_id]);
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
