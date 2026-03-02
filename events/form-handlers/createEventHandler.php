<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header: 'Location: /Event-Management-System/auth/login.php';
    exit;
}

require_once __DIR__ . '/../../app/Services/EventService/validateEventDetails.php';
require_once __DIR__ . '/../../app/Entities/User.php';
require_once __DIR__ . '/../../app/Core/Database.php';
require_once __DIR__ . '/../../app/Services/EventService/createEvent.php';
require_once __DIR__ . '/../../app/Services/ApprovalService/submitForApproval.php';

$validate_event_details = new ValidateEventDetails();
$create_event = new CreateEventService();
$submit_for_approval = new SubmitForApprovalService();
$user = new User();
$db = Database::getInstance()->getConnection();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);

    $event_created = $create_event->createEvent(
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity
    );

    if ($event_created['success']) {
        $event_id = intval($db->lastInsertId());
        $organizer_id = $_SESSION['user_id'];

        $submit_for_approval->submitForApproval($event_id, $organizer_id);

        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'Event submitted',
            'message' => 'Event has been submitted to Admin for approval. '
        ];
        header('Location: /Event-Management-System/organizer/manage.php');
        exit;
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Error Creating Event',
            'message' => 'Something went wrong in creating the event. Please try again.'
        ];
        header('Location: /Event-Management-System/organizer/manage.php');
        exit;
    }
}
