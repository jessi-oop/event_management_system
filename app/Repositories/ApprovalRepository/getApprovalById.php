<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class GetApprovalByIdRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getApprovalById($approval_id){
        try {
            $stmt = $this->db->prepare( "SELECT * FROM event_approvals WHERE approval_id = ?");
            $stmt->execute([$approval_id]);
            $data = $stmt->fetch();

            return $data ? new EventApproval($data) : null;
        } catch (PDOException $e) {
            error_log('Error getting approval by ID: ' . $e->getMessage());
            return null;
        }
    }
}