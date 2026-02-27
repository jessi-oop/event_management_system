<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/submitForApproval.php';
require_once __DIR__ . '/../../Repositories/ActivityLogRepository/logActivity.php';

class SubmitForApprovalService
{
    private $submit_for_approval;
    private $log_activity;

    public function __construct()
    {
        $this->submit_for_approval = new SubmitForApprovalRepo();
        $this->log_activity = new LogActivityRepo();
    }

    public function submitForApproval($event_id, $organizer_id)
    {
        $result = $this->submit_for_approval->submitForApproval($event_id, $organizer_id);

        if ($result['success']) {
            // Log activity
            $this->log_activity->logActivity(
                $organizer_id,
                'EVENT_SUBMISSION',
                'Event submitted for approval'
            );
        }

        return $result;
    }
}
