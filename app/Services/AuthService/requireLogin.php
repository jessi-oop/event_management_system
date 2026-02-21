<?php

require_once __DIR__ . '/isLoggedIn.php';

class RequireLogin
{
    private $is_logged_in;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->is_logged_in = new isLoggedIn();
    }
    //require login (redirect if not logged in)
    public function requireLogin()
    {
        if (!$this->is_logged_in->isLoggedIn()) {
            header('Location: /Event-Management-System/auth/login.php');
            exit;
        }
    }
}
