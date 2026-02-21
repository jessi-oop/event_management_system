<?php

require_once __DIR__ . '/requireLogin.php';

class RequireRole
{
    private $require_login;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->require_login = new RequireLogin();
    }

    // require specific role
    public function requireRole($allowed_roles)
    {

        if (!is_array($allowed_roles)) {
            $allowed_roles = [$allowed_roles];
        }

        $user_role = $_SESSION['role'] ?? null;

        if (!in_array($user_role, $allowed_roles)) {
            if ($user_role === 'admin') {
                header('Location: /Event-Management-System/admin/index.php');
            } elseif ($user_role === 'organizer') {
                header('Location: /Event-Management-System/organizer/manage.php');
            } else {
                header('Location: /Event-Management-System/dashboard/index.php');
            }
            exit;
        }
    }
}
