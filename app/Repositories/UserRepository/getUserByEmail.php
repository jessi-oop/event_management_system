<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/User.php';

class getUserByEmail {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Find user by email
    public function getUserByEmail($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            //Fetch the data that was returned by the query
            $data = $stmt->fetch();

            if ($data) { //if the $data variable has content then a new user object is created using the User class in the Entities
                return new User($data);
            }

            return null;
        } catch (PDOException $e) {
            error_log('Error finding user: ' . $e->getMessage());
            return null;
        }

    }
}