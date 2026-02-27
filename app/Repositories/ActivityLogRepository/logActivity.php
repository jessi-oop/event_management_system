<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/ActivityLog.php';

class LogActivityRepo {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Log a new activity
    public function logActivity($user_id, $type, $description)
    {
        try {
            
            $stmt = $this->db->prepare("INSERT INTO activity_log (user_id, type, description, created_at) 
                    VALUES (?, ?, ?, NOW())");
            $stmt->execute([$user_id, $type, $description]);
        } catch (PDOException $e) {
            error_log('Error logging activity: ' . $e->getMessage());
        }
    }
}