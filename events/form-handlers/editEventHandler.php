<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../app/Services/AuthService/requireRole.php';
require_once __DIR__ . '/../../app/Services/RateLimitService/rateLimitService.php';
require_once __DIR__ . '/../../app/Services/EventService/updateEvent.php';
require_once __DIR__ . '/../../app/Services/EventService/updateEventImage.php';
require_once __DIR__ . '/../../app/Services/EventService/updateEventApprovalStatus.php';
require_once __DIR__ . '/../../app/Services/EventService/getEventById.php';
require_once __DIR__ . '/../../app/Services/ApprovalService/submitForApproval.php';
require_once __DIR__ . '/../../app/Services/ApprovalService/getApprovalByEventIdAndStatus.php';
require_once __DIR__ . '/../../app/Services/ApprovalService/updateApprovalStatus.php';

$require_role = new RequireRole();
$rate_limiter = new RateLimitService();
$get_event_by_id = new GetEventByIdService();
$update_event = new UpdateEventService();
$update_event_image = new UpdateEventImageService();
$update_event_status = new UpdateEventStatusService();
$submit_for_approval = new SubmitForApprovalService();
$get_approval = new GetApprovalByEventIdAndStatusService();
$update_approval_status = new UpdateApprovalStatusService();

$require_role->requireRole(['organizer', 'admin']);

$identifier = $rate_limiter->buildIdentifier('update_event', ['user_id' => $_SESSION['user_id'] ?? 0]);
if (!$rate_limiter->attempt('create_event', $identifier)) {
    http_response_code(429);
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Rate Limit Reached',
        'message' => 'Event submission has exceeded rate limit. Please try again later.'
    ];
    header('Location: /Event-Management-System/events/edit.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id'] ?? 0);
} else {
    $event_id = intval($_GET['event_id'] ?? 0);
}

if (!$event_id) {
    header('Location: /Event-Management-System/organizer/manage.php');
    exit;
}

$event = $get_event_by_id->getEventById($event_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category_id = intval($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);
    $image_file = $_FILES['event_image'] ?? null;

    // Update the event
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

        // Handle image update
        if ($image_file && isset($image_file['error']) && $image_file['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../public/uploads/event-images/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate unique filename
            $file_extension = pathinfo($image_file['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('event_') . '_' . time() . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;

            // Move uploaded file
            if (move_uploaded_file($image_file['tmp_name'], $target_path)) {
                $new_image_path = 'public/uploads/event-images/' . $file_name;

                // Update image in database (this will also delete old image)
                $image_update_result = $update_event_image->updateEventImage($event_id, $new_image_path);

                if (!$image_update_result['success']) {
                    // Clean up new image if database update failed
                    unlink($target_path);
                    error_log("Failed to update image for event $event_id");
                }
            }
        }

        // Handle approval status before redirecting
        $organizer_id = $_SESSION['user_id'];

        if (isset($event) && $event->approval_status === 'rejected') {
            // Event was rejected - resubmit for approval
            $update_event_status->updateEventStatus($event_id);

            $approval_exists = $get_approval->getApprovalByEventIdAndStatus($event_id);

            if ($approval_exists) {
                $update_approval_status->updateApprovalStatus($event_id);
            } else {
                $submit_for_approval->submitForApproval($event_id, $organizer_id);
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => 'Event Updated & Resubmitted',
                'message' => 'Event has been successfully updated and resubmitted for approval.'
            ];
        } else {
            // Regular update (approved/pending event)
            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => 'Event Updated',
                'message' => 'Event has been successfully updated.'
            ];
        }
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Update Error',
            'message' => 'Something went wrong in updating the event. Please try again.'
        ];
    }

    // Redirect after operations are complete
    header('Location: /Event-Management-System/organizer/manage.php');
    exit;
}
