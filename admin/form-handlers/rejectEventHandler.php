<?php

session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Access Denied',
        'message' => 'You must be an admin to perform this action.'
    ];
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

require_once __DIR__ . '/../../app/Services/ApprovalService/rejectEvent.php';

$reject_event = new RejectEventService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $approval_id = isset($_POST['approval_id']) ? intval($_POST['approval_id']) : 0;
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $admin_id = $_SESSION['user_id'];
    $rejection_reason = isset($_POST['rejection_reason']) ? trim($_POST['rejection_reason']) : 'No reason provided';

    // Validate input
    if (!$approval_id || !$event_id) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Invalid Request',
            'message' => 'Missing approval or event information.'
        ];
        header('Location: /Event-Management-System/admin/approvals.php');
        exit;
    }

    $result = $reject_event->rejectEvent($approval_id, $admin_id, $rejection_reason);

    if ($result['success']) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'Event Rejected',
            'message' => 'The event has been rejected. The organizer can submit a new request.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Rejection Failed',
            'message' => $result['message'] ?? 'Failed to reject event. Please try again.'
        ];
    }
}

header('Location: /Event-Management-System/admin/approvals.php');
exit;
