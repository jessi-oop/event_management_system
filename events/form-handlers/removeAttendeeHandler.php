<?php

session_start();

require_once __DIR__ . '/../../app/Services/RegistrationService/cancelRegistration.php';
require_once __DIR__ . '/../../app/Services/EventService/getEventById.php';
require_once __DIR__ . '/../../app/Services/AuthService/requireLogin.php';

$require_login = new RequireLogin();
$get_event_by_id = new GetEventByIdService();
$cancel_registration = new CancelRegistrationService();

// Get user info from session
$current_user_id = $_SESSION['user_id'];
$current_user_role = $_SESSION['role'];

// Get POST data
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

$event = $get_event_by_id->getEventById($event_id);

// Validate input
if (!$event_id || !$user_id) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Invalid Request',
        'message' => 'Missing event or user information. Please try again.'
    ];
    redirectToAppropriateePage($current_user_role, $event_id);
}

try {
    // Check authorization
    if ($current_user_role === 'organizer') {
        // Organizers can only remove attendees from their own events
        if (!$event || $event->organizer_id != $current_user_id) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'title' => 'Access Denied',
                'message' => 'You are not authorized to manage attendees for this event.'
            ];
            redirectToAppropriateePage($current_user_role, $event_id);
        }
    } elseif ($current_user_role !== 'admin') {
        // Only organizers and admins can remove attendees
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Permission Denied',
            'message' => 'You do not have permission to remove attendees.'
        ];
        redirectToAppropriateePage($current_user_role, $event_id);
    }

    // Process the removal
    $result = $cancel_registration->cancelRegistration($user_id, $event_id);

    if ($result['success']) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'Attendee Removed',
            'message' => 'The attendee has been successfully removed from the event.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Removal Failed',
            'message' => $result['message'] ?? 'An error occurred while removing the attendee. Please try again.'
        ];
    }

} catch (Exception $e) {
    error_log("Error removing attendee: " . $e->getMessage());
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'System Error',
        'message' => 'An unexpected error occurred. Please try again later.'
    ];
}

redirectToAppropriateePage($current_user_role, $event_id);

// Redirect to correct page base on user role
function redirectToAppropriateePage($role, $event_id)
{
    $base_url = '/Event-Management-System';

    switch ($role) {
        case 'admin':
            // Redirect admin to admin attendees page
            header("Location: {$base_url}/admin/attendees.php?event_id={$event_id}");
            break;

        case 'organizer':
            // Redirect organizer to organizer attendees page
            header("Location: {$base_url}/organizer/attendees.php?event_id={$event_id}");
            break;

        default:
            // Fallback to browse page for other roles
            header("Location: {$base_url}/events/browse.php");
            break;
    }
    exit;
}
