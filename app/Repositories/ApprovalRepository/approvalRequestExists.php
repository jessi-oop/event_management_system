<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class ApprovalRequestExistsRepo {
     private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function approvalRequestExists($event_id){
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM event_approvals WHERE event_id = ?");
            $stmt->execute([$event_id]);

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Error checking if approval exists: ' . $e->getMessage());
            return false;
        }
    }
}