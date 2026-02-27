<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class GetPendingApprovalsRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPendingApprovals(){
        try {
            
            $stmt = $this->db->query("SELECT * FROM event_approvals WHERE status = 'pending' ORDER BY submitted_at DESC");
            
            $approvals = [];
            while ($data = $stmt->fetch()) {
                $approvals[] = new EventApproval($data);
            }

            return $approvals;
        } catch (PDOException $e) {
            error_log('Error getting pending approvals: ' . $e->getMessage());
            return [];
        }
    }
}