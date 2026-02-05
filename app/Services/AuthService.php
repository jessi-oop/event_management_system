<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthService
{
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository(); //creates a new and local instance of the UserRepo class
    }

    // Register user
    public function register($username, $email, $role, $password)
    {
        $validate = $this->validateRegistration($username, $email, $role, $password); //validate inputted credentials
        if (!$validate['valid']) {
            return ['success' => false, 'message' => $validate['message']];
        }

        if ($this->userRepo->emailExists($email)) { //check if email is already registered to avoid duplication
            return ['success', false, 'message' => 'Email already registered.' ];
        }

        $allowedRoles = ['organizer', 'attendee'];
        if (!in_array($role, $allowedRoles)) { //check if the selected role is in the valid roles
            return ['success' => false, 'message' => 'Invalid role selected.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT); //hasehes the password that was passed as an argument

        $createUser = $this->userRepo->createUser($username, $email, $role, $passwordHash); //calls the function in the UserRepo file to create a new user
        if ($createUser) {
            return ['success' => true, 'message' => 'Registration successfull.'];
        }

        return ['success' => false, 'message' => 'Registration failed.'];


    }

    public function login($email, $password)
    {
        $user = $this->repo->getUserByEmail($email); //find user by email, return their whole info

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }

        if (!password_verify($password, $user->password)) { //verify password
            return['success' => false, 'message' => 'Invalid email or password.'];
        }

        //start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

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

        return $this->userRepo->getUserById($_SESSION['user_id']);
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
    public function validateRegistration($username, $email, $role, $password)
    {
        if (empty($username) || empty($email) || empty($role) || empty($password)) {
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
