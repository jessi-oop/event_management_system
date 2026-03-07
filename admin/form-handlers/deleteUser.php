<?php

require_once __DIR__ . '/../../app/Services/UserService/deleteUser.php';
require_once __DIR__ . '/../../app/Services/AuthService/requireRole.php';

$delete_user = new DeleteUserService();
$require_role = new RequireRole();

$require_role->requireRole(['admin']);

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Access Denied',
        'message' => 'You must be an admin to perform this action.'
    ];
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    if (!$user_id) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Invalid Request',
            'message' => 'Missing user ID.'
        ];
        header('Location: /Event-Management-System/admin/approvals.php');
        exit;
    }

    $result = $delete_user->deleteUser($user_id);

    if ($result['success']) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'User Deleted',
            'message' => 'The user has been deleted.'
        ];
        header('Location: /Event-Management-System/admin/approvals.php');
        exit;
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Deletion Failed',
            'message' => $result['message'] ?? 'Failed to delete user. Please try again.'
        ];
        header('Location: /Event-Management-System/admin/approvals.php');
        exit;
    }
}
