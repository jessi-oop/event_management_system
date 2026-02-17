<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Category.php';

class GetAllCategories {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllCategories()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM categories ORDER BY category_name ASC');

            $categories = [];
            while ($data = $stmt->fetch()) {
                $categories[] = new Category($data);
            }

            return $categories;

        } catch (PDOException $e) {
            error_log('Error getting categories: ' . $e->getMessage());
            return [];
        }
    }
}