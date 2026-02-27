<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class GetOrganizerApprovalByStatusRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getOrganizerApprovalsByStatus($organizer_id, $status)
    {
        try {
            $sql = 
            
            $stmt = $this->db->prepare("SELECT * FROM event_approvals 
                    WHERE organizer_id = ? AND status = ?
                    ORDER BY submitted_at DESC");
            $stmt->execute([$organizer_id, $status]);

            $approvals = [];
            while ($data = $stmt->fetch()) {
                $approvals[] = new EventApproval($data);
            }

            return $approvals;
        } catch (PDOException $e) {
            error_log('Error getting organizer approvals by status: ' . $e->getMessage());
            return [];
        }
    }
}