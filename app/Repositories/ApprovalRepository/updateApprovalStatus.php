<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class UpdateApprovalStatusRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateApprovalStatus($event_id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE event_approvals 
                SET status = 'pending', 
                    submitted_at = NOW(), 
                    reviewed_at = NULL, 
                    reviewed_by = NULL,
                    rejection_reason = NULL
                WHERE event_id = ? AND status = 'rejected'");
            $data = $stmt->execute([$event_id]);

            return $data;
        } catch (PDOException $e) {
            error_log('Error updating approval status: ' . $e->getMessage());
            return false;
        }

    }
}
