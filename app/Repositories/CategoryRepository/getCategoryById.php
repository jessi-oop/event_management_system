<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/Category.php';

class GetCategoryById {
     private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
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
            return null;
        }
    }
}