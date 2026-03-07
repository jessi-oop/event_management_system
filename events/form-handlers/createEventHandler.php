<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

require_once __DIR__ . '/../../app/Services/EventService/validateEventDetails.php';
require_once __DIR__ . '/../../app/Services/RateLimitService/rateLimitService.php';
require_once __DIR__ . '/../../app/Entities/User.php';
require_once __DIR__ . '/../../app/Core/Database.php';
require_once __DIR__ . '/../../app/Services/EventService/createEvent.php';
require_once __DIR__ . '/../../app/Services/AuthService/getCurrentUser.php';

$validate_event_details = new ValidateEventDetails();
$rate_limiter = new RateLimitService();
$create_event = new CreateEventService();
$get_current_user = new GetCurrentUserService();
$user = new User();
$db = Database::getInstance()->getConnection();

$identifier = $rate_limiter->buildIdentifier('create_event', ['user_id' => $_SESSION['user_id'] ?? 0 ]);

if (!$rate_limiter->attempt('create_event', $identifier)) {
    http_response_code(429);
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Rate Limit Reached',
        'message' => 'Event submission has exceeded rate limit. Please try again later.'
    ];
    header('Location: /Event-Management-System/events/create.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);

    // Get image file if uploaded
    $image_file = $_FILES['event_image'] ?? null;

    // Validate event details including image
    $validation = $validate_event_details->validateEventDetails(
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity,
        $image_file
    );

    if (!$validation['valid']) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Validation Error',
            'message' => $validation['message']
        ];
        header('Location: /Event-Management-System/organizer/create.php');
        exit;
    }

    // Handle image upload if provided
    $image_path = null;
    if ($image_file && isset($image_file['error']) && $image_file['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../../public/uploads/event-images/';

        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($image_file['name'], PATHINFO_EXTENSION);
        $file_name = uniqid('event_') . '_' . time() . '.' . $file_extension;
        $target_path = $upload_dir . $file_name;

        // Move uploaded file
        if (move_uploaded_file($image_file['tmp_name'], $target_path)) {
            $image_path = 'public/uploads/event-images/' . $file_name;
        }
    }

    // Create event (service handles approval logic internally)
    $event_created = $create_event->createEvent(
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity,
        $image_path
    );

    if ($event_created['success']) {
        // Get current user to determine role
        $current_user = $get_current_user->getCurrentUser();

        // Set flash message and redirect based on role
        if ($current_user && $current_user->role === 'admin') {
            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => 'Event Created',
                'message' => 'Event has been created and automatically approved.'
            ];
            header('Location: /Event-Management-System/admin/index.php');
        } else {
            $_SESSION['flash'] = [
                'type' => 'success',
                'title' => 'Event Submitted',
                'message' => 'Event has been submitted to Admin for approval.'
            ];
            header('Location: /Event-Management-System/organizer/manage.php');
        }
        exit;
    } else {
        // Delete uploaded image if event creation failed
        if ($image_path && file_exists(__DIR__ . '/../../' . $image_path)) {
            unlink(__DIR__ . '/../../' . $image_path);
        }

        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Error Creating Event',
            'message' => 'Something went wrong in creating the event. Please try again.'
        ];
        header('Location: /Event-Management-System/organizer/manage.php');
        exit;
    }
}
