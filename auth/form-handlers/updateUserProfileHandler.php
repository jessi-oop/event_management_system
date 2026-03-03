<?php

session_start();
require_once __DIR__ . '/../../app/Services/UserService/updateUser.php';
require_once __DIR__ . '/../../app/Services/RateLimitService/rateLimitService.php';

// Rate limit updates
$rate_limiter = new RateLimitService();
$identifier = $rate_limiter->buildIdentifier('update_user', ['user_id' => $_SESSION['user_id'] ?? 0]);

if (!$rate_limiter->attempt('update_event', $identifier)) {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'title' => 'Slow Down',
        'message' => 'Too many updates. Please wait a moment before trying again.'
    ];
    header('Location: /Event-Management-System/events/userProfile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Event-Management-System/events/userProfile.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? 0;
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validation
if (empty($fullname) || empty($email)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Missing Information',
        'message' => 'Please fill in all required fields.'
    ];
    header('Location: /Event-Management-System/events/userProfile.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Invalid Email',
        'message' => 'Please enter a valid email address.'
    ];
    header('Location: /Event-Management-System/events/userProfile.php');
    exit;
}

$update_user = new UpdateUserService();
$result = $update_user->updateUser($fullname, $email, $user_id);

if ($result['success']) {
    $_SESSION['flash'] = [
        'type' => 'success',
        'title' => 'Profile Updated',
        'message' => 'Your profile has been updated successfully.'
    ];
} else {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Update Failed',
        'message' => 'Failed to update profile. Please try again later.'
    ];
}

header('Location: /Event-Management-System/events/userProfile.php');
exit;
