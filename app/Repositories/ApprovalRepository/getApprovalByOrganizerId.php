<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class GetApprovalByOrganizerIdRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getApprovalsByOrganizerId($organizer_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM event_approvals WHERE organizer_id = ? ORDER BY submitted_at DESC");
            $stmt->execute([$organizer_id]);

            $approvals = [];
            while ($data = $stmt->fetch()) {
                $approvals[] = new EventApproval($data);
            }

            return $approvals;
        } catch (PDOException $e) {
            error_log('Error getting approvals by organizer: ' . $e->getMessage());
            return [];
        }
    }
}