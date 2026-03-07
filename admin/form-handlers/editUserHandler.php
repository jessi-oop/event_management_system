<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

require_once __DIR__ . '/../../app/Services/AuthService/requireRole.php';
require_once __DIR__ . '/../../app/Services/UserService/updateUser.php';

$require_role = new RequireRole();
$require_role->requireRole(['admin']);

$update_user_service = new UpdateUserService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Basic validation
    if ($user_id <= 0) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Invalid Request',
            'message' => 'User ID is required.'
        ];
        header('Location: /Event-Management-System/admin/users.php');
        exit;
    }

    if (empty($full_name) || empty($email)) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Validation Error',
            'message' => 'All fields are required.'
        ];
        header('Location: /Event-Management-System/admin/editUser.php?user_id=' . $user_id);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Invalid Email',
            'message' => 'Please enter a valid email address.'
        ];
        header('Location: /Event-Management-System/admin/editUser.php?user_id=' . $user_id);
        exit;
    }

    // Update user
    $result = $update_user_service->updateUser($full_name, $email, $user_id);

    if ($result['success']) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'User Updated',
            'message' => 'User information has been updated successfully.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Update Failed',
            'message' => $result['message']
        ];
    }

    header('Location: /Event-Management-System/admin/users.php');
    exit;
} else {
    // If not POST request, redirect
    header('Location: /Event-Management-System/admin/users.php');
    exit;
}
