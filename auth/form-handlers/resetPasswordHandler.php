<?php

session_start();

require_once __DIR__ . '/../../app/Services/PasswordService/resetPassword.php';

$reset_password = new ResetPasswordService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($password) || empty($confirm_password)) {
        $_SESSION['modal_message'] = "All fields are required.";
        $_SESSION['modal_type'] = "error";
        header('Location: /Event-Management-System/auth/resetPassword.php?token=' . urlencode($token));
        exit;
    }

    $result = $reset_password->resetPassword($token, $password, $confirm_password);

    if ($result['success']) {
        $_SESSION['flash'] = [
        'type' => 'success',
        'title' => 'Password Reset Successful',
        'message' => 'Password changed successfully.'
    ];
        header('Location: /Event-Management-System/auth/login.php');

    } else {
        $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'Error in changing password. Please try again later.'
    ];
        header('Location: /Event-Management-System/auth/resetPassword.php?token=' . urlencode($token));
    }

    exit();
}

// If not POST request, redirect to login page
header("Location: /Event-Management-System/auth/login.php");
exit;
