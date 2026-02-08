<?php

require_once __DIR__ . '../Core/Database.php';
require_once __DIR__ . '../Entities/Categories.php';


class CategoryRepository
{
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
                $categories = new Category($data);
            }

            return $categories;

        } catch (PDOException $e) {
            error_log('Error getting categories: ' . $e->getMessage());
            return [];
        }
    }

    public function getCategoryById($category_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM categories WHERE category_id = ?');
            $stmt->execute([$category_id]);
            $data = $stmt->fetch();

            if ($data) {
                return new Category($data);
            }

            // Todo: Get an error return message in case null
            return null;

        } catch (PDOException $e) {
            error_log('Error getting category: ' . $e->getMessage());
        }
    }
}
