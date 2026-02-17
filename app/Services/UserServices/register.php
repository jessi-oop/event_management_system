<?php

require_once __DIR__ . '/../../Repositories/UserRepository/createUser.php';
require_once __DIR__ . '/../../Repositories/UserRepository/emailExists.php';
require_once __DIR__ . '/validateRegistration.php';

class Register {
     private $creater_user;
     private $email_exists;
     private $validate_registration;

    public function __construct()
    {
        $this->creater_user = new createUser(); //creates a new and local instance of the UserRepo class
        $this->email_exists = new emailExists();
        $this->validate_registration = new validateRegistration();
    }

    // Register user
    public function register($full_name, $email, $role, $password)
    {
        $validate = $this->validate_registration->validateRegistration($full_name, $email, $role, $password); //validate inputted credentials
        if (!$validate['valid']) {
            return ['success' => false, 'message' => $validate['message']];
        }

        if ($this->email_exists->emailExists($email)) { //check if email is already registered to avoid duplication
            return ['success' => false, 'message' => 'Email already registered.' ];
        }

        $allowedRoles = ['organizer', 'attendee'];
        if (!in_array($role, $allowedRoles, true)) { //check if the selected role is in the valid roles
            return ['success' => false, 'message' => 'Invalid role selected.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT); //hasehes the password that was passed as an argument

        $createUser = $this->creater_user->createUser($full_name, $email, $role, $passwordHash); //calls the function in the UserRepo file to create a new user
        if ($createUser) {
            return ['success' => true, 'message' => 'Registration successfull.'];
        }

        return ['success' => false, 'message' => 'Registration failed.'];
    }
}