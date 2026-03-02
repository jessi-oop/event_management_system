<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';
require_once __DIR__ . '/approvalRequestExists.php';

class SubmitForApprovalRepo
{
    private $db;
    private $request_exists;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->request_exists = new ApprovalRequestExistsRepo();
    }

    public function submitForApproval($event_id, $organizer_id)
    {
        try {
            // Check if already exists
            if ($this->request_exists->approvalRequestExists($event_id)) {
                return ['success' => false, 'message' => 'Event already submitted for approval'];
            }

            // If NOT exists, insert it
            $stmt = $this->db->prepare("INSERT INTO event_approvals (event_id, organizer_id, status, submitted_at) 
                VALUES (?, ?, 'pending', NOW())");

            $data = $stmt->execute([$event_id, $organizer_id]);

            if ($data) {
                return ['success' => true, 'approval_id' => $this->db->lastInsertId()];
            }

            return ['success' => false, 'message' => 'Failed to submit for approval'];

        } catch (PDOException $e) {
            error_log('Error submitting for approval: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
