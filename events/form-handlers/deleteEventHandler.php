<?php

require_once __DIR__ .  '/../../app/Services/EventService/deleteEvent.php';
require_once __DIR__ . '/../../app/Services/AuthService/requireRole.php';

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

    if ($delete_event) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'Event Deleted',
            'message' => 'Event has been successfully deleted.'
        ];
        header('Location: /Event-Management-System/organizer/manage.php');
        exit;
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Event Deletion Failed',
            'message' => 'Something went wrong in deleting event. Please try again.'
        ];
        header('Location: /Event-Management-System/organizer/manage.php');
        exit;
    }


}
