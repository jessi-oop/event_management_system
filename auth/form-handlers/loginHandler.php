<?php

session_start();
require_once __DIR__ . '/../../app/Services/AuthService/requireGuest.php';
require_once __DIR__ . '/../../app/Services/AuthService/login.php';

$require_guest = new RequireGuest();
$login = new Login();

$require_guest->requireGuest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Validation Error',
            'message' => 'Email and password are required.'
        ];
        header('Location: login.php');
        exit;
    } else {
        $result = $login->login($email, $password);

        if ($result['success']) {
            $user = $result['user'];
            if ($user->isAdmin()) {
                header('Location: /Event-Management-System/admin/index.php');
            } elseif ($user->isOrganizer()) {
                header('Location: /Event-Management-System/organizer/manage.php');
            } else {
                header('Location: /Event-Management-System/attendee/myEventsAttendee.php');
            }
            exit;
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'title' => 'Login Failed',
                'message' => $result['message']
            ];
            session_write_close();
            header('Location: /Event-Management-System/auth/login.php');
            exit;
        }
    }
}
?>