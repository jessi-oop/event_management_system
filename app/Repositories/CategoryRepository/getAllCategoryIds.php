<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Category.php';

class GetAllCategoryIdsRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllCategoryIds()
    {
        try {
            $stmt = $this->db->query("SELECT category_id FROM categories ORDER BY category_name ASC");

            $category_ids = [];
            while ($data = $stmt->fetch()) {
                $category_ids[] = $data['category_id'];
            }

            return $category_ids;
        } catch (PDOException $e) {
            error_log('Error geting category ids: ' . $e->getMessage());
            return [];
        }

    }
}
