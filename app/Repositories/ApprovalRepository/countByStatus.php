<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/EventApproval.php';

class CountByStatusRepo {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function countByStatus($status)
    {
        try {
            $stmt = $this->db->prepare( "SELECT COUNT(*) FROM event_approvals WHERE status = ?");
            $stmt->execute([$status]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error counting approvals by status: ' . $e->getMessage());
            return 0;
        }
    }
}