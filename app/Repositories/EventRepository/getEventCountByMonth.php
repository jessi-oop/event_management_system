<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Event.php';

class GetEventCountByMonthRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getEventCountByMonth($months = 6)
    {
        try {
            $stmt = $this->db->prepare("SELECT 
                    DATE_FORMAT(created_at, '%b') AS month,
                    COUNT(*) AS count
                FROM events
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b')
                ORDER BY DATE_FORMAT(created_at, '%Y-%m') ASC");
            $stmt->execute([$months]);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // If no events, return empty months with 0 counts
            if (empty($data)) {
                $months_array = [];
                for ($i = $months - 1; $i >= 0; $i--) {
                    $month_name = date('M', strtotime("-$i months"));
                    $months_array[] = ['month' => $month_name, 'count' => 0];
                }
                return $months_array;
            }

            return $data;

        } catch (PDOException $e) {
            error_log('Error getting event count by month: ' . $e->getMessage());
            return [];
        }
    }
}
