<?php

session_start();

require_once __DIR__ . '/../../app/Services/ApprovalService/approveEvent.php';

$approve_event = new ApproveEventService();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $approval_id = isset($_POST['approval_id']) ? intval($_POST['approval_id']) : 0;
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $admin_id = $_SESSION['user_id'];

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

    // Process approval
    $result = $approve_event->approveEvent($approval_id, $admin_id);

    if ($result['success']) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'title' => 'Event Approved',
            'message' => 'The event has been approved and is now visible to users.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'Approval Failed',
            'message' => $result['message'] ?? 'Failed to approve event. Please try again.'
        ];
    }
}

header('Location: /Event-Management-System/admin/approvals.php');
exit;
