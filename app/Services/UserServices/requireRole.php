<?php

class RequireRole {
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
}