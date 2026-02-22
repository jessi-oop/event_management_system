<?php

require_once __DIR__ . '/../app/Services/RegistrationService/registerToEvent.php';
require_once __DIR__ . '/../app/Services/AuthService/requireLogin.php';

$register_to_event = new RegisterToEventService();
$require_login = new RequireLogin();

$require_login->requireLogin();

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_SESSION['user_id']);
    $event_id = intval($_POST['event_id']);
} else {
    $user_id = intval($_SESSION['user_id']);
    $event_id = intval($_GET['event_id']);
}

if (!$event_id || !$user_id) {
    header('Location: /Event-Management-System/organizer/manage.php');
}

$register = $register_to_event->registerToEvent($user_id, $event_id);

if ($register) {
    $message = $register['message'];
    echo '<pre>';
    var_dump($message);
    echo '</pre>';
    header("Location: /Event-Management-System/events/browse.php");
    exit;
}
