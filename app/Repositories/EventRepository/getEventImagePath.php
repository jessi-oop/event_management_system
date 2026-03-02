<?php

require_once __DIR__ . '/../../Core/Database.php';

class GetEventImagePathRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getEventImagePath($event_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT image_path FROM events WHERE event_id = ?');
            $stmt->execute([$event_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['image_path'] : null;
        } catch (PDOException $e) {
            error_log('Error getting event image: ' . $e->getMessage());
            return null;
        }
    }
}
