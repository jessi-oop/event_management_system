<?php

require_once __DIR__ . '/isLoggedIn.php';

class RequireGuest
{
    private $is_logged_in;

    public function __construct()
    {
        $this->is_logged_in = new IsLoggedIn();
    }

    public function requireGuest()
    {
        if ($this->is_logged_in->isLoggedIn()) {
            $role = $_SESSION['role'];

            if ($role === 'admin') {
                header('Location: /Event-Management-System/admin/dashboard.php');
            } elseif ($role === 'organizer') {
                header('Location: /Event-Management-System/organizer/manage.php');
            } else {
                header('Location: /Event-Management-System/dashboard/index.php');
            }
            exit;
        }
    }
}
