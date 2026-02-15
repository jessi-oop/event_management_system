<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthService
{
    private $user_repo;

    public function __construct()
    {
        $this->user_repo = new UserRepository(); //creates a new and local instance of the UserRepo class
    }

    // Register user
    public function register($full_name, $email, $role, $password)
    {
        $validate = $this->validateRegistration($full_name, $email, $role, $password); //validate inputted credentials
        if (!$validate['valid']) {
            return ['success' => false, 'message' => $validate['message']];
        }

        if ($this->user_repo->emailExists($email)) { //check if email is already registered to avoid duplication
            return ['success' => false, 'message' => 'Email already registered.' ];
        }

        $allowedRoles = ['organizer', 'attendee'];
        if (!in_array($role, $allowedRoles, true)) { //check if the selected role is in the valid roles
            return ['success' => false, 'message' => 'Invalid role selected.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT); //hasehes the password that was passed as an argument

        $createUser = $this->user_repo->createUser($full_name, $email, $role, $passwordHash); //calls the function in the UserRepo file to create a new user
        if ($createUser) {
            return ['success' => true, 'message' => 'Registration successfull.'];
        }

        return ['success' => false, 'message' => 'Registration failed.'];


    }

    public function login($email, $password)
    {
        if ($email === '' || $password === '') {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
        $user = $this->user_repo->getUserByEmail($email); //find user by email, return their whole info

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }

        if (!password_verify($password, $user->password_hash)) { //verify password
            return['success' => false, 'message' => 'Invalid password.'];
        }

        //start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true); //Generate a fresh session id after login

        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['full_name'] = $user->full_name;
        $_SESSION['email'] = $user->email;
        $_SESSION['role'] = $user->role;


        return ['success' => true, 'message' => 'Login successfull.', 'user' => $user];
    }

    // logout
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        session_unset();
    }

    // check if user is logged in
    public function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    // get the current user that is logged in
    public function getCurrentUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $this->user_repo->getUserById($_SESSION['user_id']);
    }

    //require login (redirect if not logged in)
    public function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /auth/login.php');
        }
    }

    // require specific role
    public function requireRole($role)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header('Location: /index.php');
            exit;
        }
    }

    // validate credentials
    public function validateRegistration($full_name, $email, $role, $password)
    {
        if (empty($full_name) || empty($email) || empty($role) || empty($password)) {
            return ['valid' => false, 'message' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Invalid email format.'];
        }

        if (strlen($password) < 8) {
            return ['valid' => false, 'message' => 'Password must be greater than 8 characters.'];
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{6,}$/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must have at least one uppercase letter, 
                lowercase letter, number, and special character'];
        }

        return ['valid' => true];
    }
}
