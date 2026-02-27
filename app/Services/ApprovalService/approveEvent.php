<?php

require_once __DIR__ . '/getApprovalById.php';
require_once __DIR__ . '/../../Repositories/ApprovalRepository/approveEvent.php';
require_once __DIR__ . '/../ActivityLog/logActivity.php';

class ApproveEventService
{
    private $get_approval_by_id;
    private $approve_event;
    private $log_activity;

    public function __construct()
    {
        $this->get_approval_by_id = new GetApprovalByIdService();
        $this->approve_event = new ApproveEventRepo();
        $this->log_activity = new LogActivityService();
    }

    public function approveEvent($approval_id, $admin_id)
    {
        // Get approval first to get event_id for logging
        $approval = $this->get_approval_by_id->getApprovalById($approval_id);

        if (!$approval) {
            return ['success' => false, 'message' => 'Approval not found'];
        }

        if ($approval->status !== 'pending') {
            return ['success' => false, 'message' => 'Only pending approvals can be processed'];
        }

        $result = $this->approve_event->approveEvent($approval_id, $admin_id);

        if ($result['success']) {
            // Log activity
            $this->log_activity->logActivity(
                $admin_id,
                'EVENT_APPROVED',
                'Event approved by admin'
            );
        }

        return $result;
    }
}
