<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class RejectEventRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function rejectEvent($approval_id, $admin_id, $reason)
    {
        try { 
            $stmt = $this->db->prepare("UPDATE event_approvals 
                    SET status = 'rejected', 
                        reviewed_at = NOW(), 
                        reviewed_by = ?,
                        rejection_reason = ?
                    WHERE approval_id = ? AND status = 'pending'");
            $data = $stmt->execute([$admin_id, $reason, $approval_id]);

            if ($data && $stmt->rowCount() > 0) {
                return ['success' => true, 'message' => 'Event rejected successfully'];
            }
            
            return ['success' => false, 'message' => 'Approval not found or already processed'];
        } catch (PDOException $e) {
            error_log('Error rejecting event: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}