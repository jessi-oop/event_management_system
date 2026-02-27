<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class GetApprovalByEventIdRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getApprovalByEventId($event_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM event_approvals WHERE event_id = ?");
            $stmt->execute([$event_id]);

            $data = $stmt->fetch();
            return $data ? new EventApproval($data) : null;
        } catch (PDOException $e) {
            error_log('Error getting approval by event ID: ' . $e->getMessage());
            return null;
        }
    }
}