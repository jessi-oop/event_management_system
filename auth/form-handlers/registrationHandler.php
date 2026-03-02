<?php

session_start();
require_once __DIR__ . '/../../app/Services/AuthService/isLoggedIn.php';
require_once __DIR__ . '/../../app/Services/AuthService/register.php';
require_once __DIR__ . '/../../app/Services/RateLimitService/rateLimitService.php';

$is_logged_in = new IsLoggedIn();
$rate_limiter = new RateLimitService();
$register = new Register();

if ($is_logged_in->isLoggedIn()) {
    header('Location: dashboard/index.php');
    exit;
}

$identifier = $rate_limiter->buildIdentifier('register', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
if (!$rate_limiter->attempt('register', $identifier)) {
    http_response_code(429);
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Rate Limit Reached',
        'message' => 'Too many registration attemps. Please wait 1 hour.'
    ];
    header('Location: /Event-Management-System/auth/register.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $register->register($full_name, $email, $role, $password);

    // Set flash message for modal
    $_SESSION['flash'] = [
        'type' => $result['success'] ? 'success' : 'error',
        'title' => $result['success'] ? 'Registration Successful' : 'Registration Failed',
        'message' => $result['message'],
        'options' => $result['success'] ? ['redirectUrl' => '/Event-Management-System/auth/login.php'] : []
    ];

    // Redirect to prevent form resubmission
    session_write_close();
    header('Location: /Event-Management-System/auth/register.php');
    exit;
}
