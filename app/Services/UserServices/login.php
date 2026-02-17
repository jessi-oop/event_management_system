<?php

require_once __DIR__ . '/../../Repositories/UserRepository/getUserByEmail.php';

class Login {
    private $get_user_by_email;

    public function __construct(){
        $this->get_user_by_email = new getUserByEmail();
    }

    public function login($email, $password)
    {
        if ($email === '' || $password === '') {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
        $user = $this->get_user_by_email->getUserByEmail($email); //find user by email, return their whole info

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

}