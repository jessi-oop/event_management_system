<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Category.php';

class GetAllCategoriesRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllCategories()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM categories');

            $categories = [];
            while ($data = $stmt->fetch()) {
                $categories[] = new Category($data);
            }

            if ($categories) {
                return $categories;
            }

            return ['success' => false, 'message' => 'Error retrieving categories.'];

        } catch (PDOException $e) {
            error_log('Error getting categories: ' . $e->getMessage());
            return [];
        }
    }
}
