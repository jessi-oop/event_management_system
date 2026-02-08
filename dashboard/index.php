<?php

session_start();
// DEBUG: Show what's in session
// echo "<pre>";
// echo "Session Data:\n";
// print_r($_SESSION);
// echo "\n\nIs session started? " . (session_status() === PHP_SESSION_ACTIVE ? 'YES' : 'NO');
// echo "\n</pre>";
require_once __DIR__ . '/../app/Services/AuthService.php';

$authService = new AuthService();
$authService->requireLogin();

$user = $authService->getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Management System</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($user->username) ?>!</p>
        <p>Email: <?= htmlspecialchars($user->email) ?></p>
        <p>Role: <?= htmlspecialchars($user->role) ?></p>
        
        <nav>
            <a href="../events/browse.php">Browse Events</a>
            <?php
            var_dump($user);
die();
?>

            <?php if ($user->isOrganizer()): ?>
                <a href="../events/create.php">Create Event</a>
            <?php endif; ?>
            
            <?php if ($user->isAdmin()): ?>
                <a href="../admin/index.php">Admin Panel</a>
            <?php endif; ?>
            
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </div>
</body>
</html>