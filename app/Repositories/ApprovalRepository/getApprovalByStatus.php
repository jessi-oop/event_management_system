<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class GetApprovalByStatusRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getApprovalsByStatus($status)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM event_approvals WHERE status = ? ORDER BY submitted_at DESC");
            $stmt->execute([$status]);

            $approvals = [];
            while ($data = $stmt->fetch()) {
                $approvals[] = new EventApproval($data);
            }

            return $approvals;
        } catch (PDOException $e) {
            error_log('Error getting approvals by status: ' . $e->getMessage());
            return [];
        }
    }
}
