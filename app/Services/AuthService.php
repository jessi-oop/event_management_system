<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthService {
    private $repo;

    public function __construct() {
        $this->repo = new UserRepository(); //creates a new and local instance of the UserRepo class
    }

    public function register($username, $email, $password){
        if (empty($username) || empty($email) || empty($password)){
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return ['success' => false, 'message' => 'Invalid email format.'];
        }

        if (strlen($password) < 8){
            return ['success' => false, 'message' => 'Password must be greater than 8 characters.'];
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{6,}$/', $password)) {
            return ['success' => false, 'message' => 'Password must have at least one uppercase letter, 
            lowercase letter, number, and special character'];
        }
        $hashPassword = password_hash($password, PASSWORD_DEFAULT); //hasehes the password that was passed as an argument 

        return $this->repo->createUser($username, $email, $hashPassword); //calls the function in the UserRepo file to create a new user
    }

    public function login($email, $password){
        $user = $this->repo->getUserByEmail($email); //find user by email, return their whole info
        //verify if the user has content and if the password stored in the user matches the passed argument
        if ($user && password_verify($password, $user->pasword)){ 
            return $user; //login succesfull
        }
        return null; //login failed
    }
}