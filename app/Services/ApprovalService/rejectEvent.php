<?php

require_once __DIR__ . '/getApprovalById.php';
require_once __DIR__ . '/../../Repositories/ApprovalRepository/rejectEvent.php';
require_once __DIR__ . '/../ActivityLogService/logActivity.php';

class RejectEventService
{
    private $get_approval_by_id;
    private $reject_event;
    private $log_activity;

    public function __construct()
    {
        $this->get_approval_by_id = new GetApprovalByIdService();
        $this->reject_event = new RejectEventRepo();
        $this->log_activity = new LogActivityService();
    }

    // Reject event
    public function rejectEvent($approval_id, $admin_id, $reason)
    {
        // Get approval first to get event_id for logging
        $approval = $this->get_approval_by_id->getApprovalById($approval_id);

        if (!$approval) {
            return ['success' => false, 'message' => 'Approval not found'];
        }

        if ($approval->status !== 'pending') {
            return ['success' => false, 'message' => 'Only pending approvals can be processed'];
        }

        $result = $this->reject_event->rejectEvent($approval_id, $admin_id, $reason);

        if ($result['success']) {
            // Log activity
            $this->log_activity->logActivity(
                $admin_id,
                'EVENT_REJECTED',
                "Event rejected: {$reason}"
            );
        }

        return $result;
    }
}
