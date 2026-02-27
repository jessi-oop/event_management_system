<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class CountPendingApprovalsRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function countPendingApprovals()
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM event_approvals WHERE status = 'pending'");
            $data =  $stmt->fetchColumn();
            return $data;
        } catch (PDOException $e) {
            error_log('Error counting pending approvals: ' . $e->getMessage());
            return 0;
        }
    }
}