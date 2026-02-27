<?php

// Add error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../../app/Services/RegistrationService/cancelRegistration.php';
$cancel_registration = new CancelRegistrationService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

    $registration_cancelled = $cancel_registration->cancelRegistration($user_id, $event_id);

    if ($registration_cancelled['success']) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'Registration Cancelled',
            'message' => 'Registration to event has been cancelled.'
        ];
        header('Location: /Event-Management-System/attendee/myEventsAttendee.php');
        exit;
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Cancellation Error',
            'message' => 'Something went wrong in cancelling registration. Please try again.'
        ];
        header('Location: /Event-Management-System/attendee/myEventsAttendee.php');
        exit;
    }
}
