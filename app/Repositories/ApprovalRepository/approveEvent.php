<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class ApproveEventRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function approveEvent($approval_id, $admin_id)
    {
        try {
            
            $stmt = $this->db->prepare("UPDATE event_approvals 
                    SET status = 'approved', 
                        reviewed_at = NOW(), 
                        reviewed_by = ?,
                        rejection_reason = NULL
                    WHERE approval_id = ? AND status = 'pending'");
            $data = $stmt->execute([$admin_id, $approval_id]);
            
            if ($data && $stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Event approved successfully'];
            }
            
            return ['success' => false, 'message' => 'Approval not found or already processed'];
        } catch (PDOException $e) {
            error_log('Error approving event: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}