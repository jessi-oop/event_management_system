<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class IsEventApprovedRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function isEventApproved($event_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT status FROM event_approvals WHERE event_id = ?");
            $stmt->execute([$event_id]);

            $data = $stmt->fetch();
            return $data && $data['status'] === 'approved';
        } catch (PDOException $e) {
            error_log('Error checking if event is approved: ' . $e->getMessage());
            return false;
        }
    }
}