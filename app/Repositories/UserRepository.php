<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/User.php';

class UserRepository {
    private $db;

    public function __construct(){
        global $pdo;
        $this->db=$pdo;
    }

    // Creates new user
    public function createUser($username, $email, $password){
        // Prepares the query
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)"); 
        //Executes the query
        $data = $stmt->execute([$username, $email, $password]);
        return $data;
    }

    public function getUserByEmail($email){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt = execute([$email]);
        //Fetch the data that was returned by the query
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) { //if the $data variable has content then a new user object is created using the User class in the Entities
            return new User($data['id'], $data['username'], $data['email'], $data['password']);
        }
        
        return null;
    }
}