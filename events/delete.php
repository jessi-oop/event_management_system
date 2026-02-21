<?php

require_once __DIR__ .  '/../app/Services/EventService/deleteEvent.php';
require_once __DIR__ . '/../app/Services/AuthService/requireRole.php';

$delete_event = new DeleteEventService();
$require_role = new RequireRole();

$require_role->requireRole(['organizer', 'admin']);

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);

    if (!$event_id) {
        header('Location: /Event-Management-System/orgaznier/manage.php');
        exit;
    }

    $deleted_event = $delete_event->deleteEvent($event_id);

    if ($deleted_event['success']) {
        $message = $deleted_event['message'];
    }

    echo '<pre>';
    var_dump($message);
    echo '</pre>';
}
