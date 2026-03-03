<?php

session_start();
require_once __DIR__ . '/../../app/Services/UserService/getUserById.php';
require_once __DIR__ . '/../../app/Services/UserService/updateUserPassword.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Event-Management-System/events/userProfile.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? 0;
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Verify current password
$user_service = new GetUserByIdService();
$user = $user_service->getUserById($user_id);

if (!$user || !password_verify($current_password, $user->password_hash)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Update Failed',
        'message' => 'Current password is incorrect.'
    ];
    header('Location: /Event-Management-System/events/userProfile.php');
    exit;
}

// Use service for new password validation & update
$update_password = new UpdateUserPasswordService();
$result = $update_password->updateUserPassword($user_id, $new_password, $confirm_password);

if ($result['success']) {
    $_SESSION['flash'] = [
        'type' => 'success',
        'title' => 'Password Updated',
        'message' => $result['message']
    ];
} else {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Update Failed',
        'message' => $result['message']
    ];
}

header('Location: /Event-Management-System/events/userProfile.php');
exit;
