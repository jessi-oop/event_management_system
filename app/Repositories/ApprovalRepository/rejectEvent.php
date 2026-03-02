<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class RejectEventRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Reject event
    public function rejectEvent($approval_id, $admin_id, $reason)
    {
        try {
            // Start transaction
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("SELECT event_id FROM event_approvals WHERE approval_id = ?");
            $stmt->execute([$approval_id]);
            $result = $stmt->fetch();

            if (!$result) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Approval not found'];
            }

            $event_id = $result['event_id'];

            // Update event_approvals table
            $stmt = $this->db->prepare(
                "UPDATE event_approvals 
                SET status = 'rejected', 
                    reviewed_at = NOW(), 
                    reviewed_by = ?,
                    rejection_reason = ?
                WHERE approval_id = ? AND status = 'pending'"
            );
            $stmt->execute([$admin_id, $reason, $approval_id]);

            if ($stmt->rowCount() === 0) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Approval not found or already processed'];
            }

            // Update events table
            $stmt = $this->db->prepare("UPDATE events SET approval_status = 'rejected' WHERE event_id = ?");
            $stmt->execute([$event_id]);

            // Commit transaction
            $this->db->commit();

            return ['success' => true, 'message' => 'Event rejected successfully'];

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Error rejecting event: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
