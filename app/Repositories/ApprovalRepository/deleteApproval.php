<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class DeleteApprovalRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function deleteApproval($approval_id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM event_approvals WHERE approval_id = ?");
            $data = $stmt->execute();
            
            if ($data) {
                return ['success' => true, 'message' => 'Approval deleted successfully'];
            }
            
            return ['success' => false, 'message' => 'Failed to delete approval'];
        } catch (PDOException $e) {
            error_log('Error deleting approval: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}