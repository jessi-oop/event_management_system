<?php

require_once __DIR__ . '/../../app/Services/AuthService/requireRole.php';
require_once __DIR__ . '/../../app/Services/EventService/updateEvent.php';
require_once __DIR__ . '/../../app/Services/EventService/getEventById.php';
require_once __DIR__ . '/../../app/Service/ApprovalService/submitForApproval.php';

$require_role = new RequireRole();
$get_event_by_id = new GetEventByIdService();
$update_event = new UpdateEventService();
$submit_for_approval = new SubmitForApprovalService();

$require_role->requireRole(['organizer', 'admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id'] ?? 0);
} else {
    $event_id = intval($_GET['event_id'] ?? 0);
}

if (!$event_id) {
    header('Location: /Event-Management-System/organizer/manage.php');
}

$event = $get_event_by_id->getEventById($event_id);

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);

    $updated_event = $update_event->updateEvent(
        $event_id,
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity
    );

    if ($updated_event['success']) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'Event Updated',
            'message' => 'Event has been successfully been updated.'
        ];
        header('Location: /Event-Management-System/organizer/manage.php');
        exit;
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Update Error',
            'message' => 'Something went wrong in updating the event. Please try again.'
        ];
        header('Location: /Event-Management-System/organizer/manage.php');
        exit;
    }
}
