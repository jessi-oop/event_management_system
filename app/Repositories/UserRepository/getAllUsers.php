<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/User.php';

class getAllUsers {
     private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Get all users (for admin)
    public function getAllUsers()
    {
        try {
            $stmt = $this->db->query('SELECT user_id, full_name, email, role, created_at FROM users ORDER BY created_at DESC');
            $users = []; //an array of associative arrays that will hold user data/user objet

            while ($data = $stmt->fetch()) { //fetch the returned value and store it into the users array
                $users[] = new User($data);
            }

            return $users;
        } catch (PDOException $e) {
            error_log('Error getting all users: ' . $e->getMessage());
            return [];
        }
    }
}