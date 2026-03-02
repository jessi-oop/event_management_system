<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class GetPendingApprovalsDetailsRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPendingApprovalsDetails()
    {
        try {
            $stmt = $this->db->query("SELECT 
                    ea.approval_id,
                    ea.event_id,
                    ea.submitted_at,
                    ea.status,
                    ea.organizer_id,
                    e.title,
                    e.event_date,
                    e.event_time,
                    e.location,
                    e.capacity,
                    e.description,
                    c.category_name,
                    u.full_name AS organizer_name
                FROM event_approvals ea
                JOIN events e ON ea.event_id = e.event_id
                JOIN users u ON ea.organizer_id = u.user_id
                LEFT JOIN categories c ON e.category_id = c.category_id
                WHERE ea.status = 'pending'
                ORDER BY ea.submitted_at ASC");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting pending approvals with details: ' . $e->getMessage());
            return [];
        }
    }
}
