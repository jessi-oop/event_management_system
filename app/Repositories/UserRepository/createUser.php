<?php
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/User.php';

class createUser{
     private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

     // Creates new user
    public function createUser($full_name, $email, $role, $password_hashed)
    {
        try {
            // Prepares the query
            $stmt = $this->db->prepare("INSERT INTO users (full_name, email, role, password_hash) VALUES (?, ?, ?, ?)");
            //Executes the query
            $data = $stmt->execute([$full_name, $email, $role, $password_hashed]);
            return $data;
        } catch (PDOException $e) {
            error_log('Error creating the user: ' . $e->getMessage());
            return false;
        }

    }

}