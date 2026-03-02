<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class GetApprovalByEventIdAndStatusRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getApprovalByEventIdAndStatus($event_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT approval_id FROM event_approvals WHERE event_id = ? AND status = 'rejected'");
            $stmt->execute([$event_id]);
            $data = $stmt->fetch();

            return $data;
        } catch (PDOException $e) {
            error_log('Error in getting approval. ' . $e->getMessage());
        }
    }
}
