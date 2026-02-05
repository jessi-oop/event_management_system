<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Entities/User.php';

class UserRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Creates new user
    public function createUser($username, $email, $role, $password_hashed)
    {
        try {
            // Prepares the query
            $stmt = $this->db->prepare("INSERT INTO users (username, email, role, password_hash) VALUES (?, ?, ?, ?)");
            //Executes the query
            $data = $stmt->execute([$username, $email, $role, $password_hashed]);
            return $data;
        } catch (PDOException $e) {
            error_log('Error creating the user: ' . $e->getMessage());
            return false;
        }

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

    // Find user by ID
    public function getUserById($id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE user_id = ?');
            $stmt = execute([$id]);
            $data = $stmt->fetch();

            if ($data) {
                return new User($data);
            }
        } catch (PDOException $e) {
            error_log('Error finding user: ' . $e->getMessage());
            return null;
        }
    }

    // Get all users (for admin)
    public function getAllUsers()
    {
        try {
            $stmt = $this->db->query('SELECT user_id, username, email, role, created_at FROM users ORDER BY created_at DESC');
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

    // Update user info
    public function updateUser($id, $username, $email)
    {
        try {
            $stmt = $this->db->prepare('UPDATE users SET username = ?, email = ? WHERE user_id = ?');
            return $stmt->execute([$username, $email, $id]);
        } catch (PDOException $e) {
            error_log('Error updating user info: ' . $e->getMessage());
            return false;
        }
    }

    // Delete user
    public function deleteUser($id)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE user_id = ?');
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }

    // Check if email already exist
    public function emailExists($email)
    {
        return $this->getUserByEmail($email) !== null;
    }
}
