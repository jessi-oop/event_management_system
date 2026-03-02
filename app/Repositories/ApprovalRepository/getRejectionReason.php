<?php

require_once __DIR__ . '/../../Core/Database.php';

class GetRejectionReasonRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getRejectionReason($event_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT rejection_reason FROM event_approvals 
                WHERE event_id = ? AND status = 'rejected' 
                ORDER BY reviewed_at DESC LIMIT 1");
            $stmt->execute([$event_id]);

            $result = $stmt->fetch();
            return $result ? $result['rejection_reason'] : 'No reason provided';
        } catch (PDOException $e) {
            error_log('Error getting rejection reason: ' . $e->getMessage());
            return 'Unable to load rejection reason';
        }
    }
}
