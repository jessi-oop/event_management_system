<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/ActivityLog.php';

class GetRecentActivity {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Get recent activities for dashboard (default: last 20)
    public function getRecentActivities($limit = 20)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT ? ");
            $stmt->execute([$limit]);

            $activities = [];
            while ($data = $stmt->fetch()) {
                $activities[] = new ActivityLog($data);
            }

            return $activities;
        } catch (PDOException $e) {
            error_log('Error getting recent activities: ' . $e->getMessage());
            return [];
        }
    }
}