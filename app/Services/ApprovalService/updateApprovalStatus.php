<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/updateApprovalStatus.php';

class UpdateApprovalStatusService
{
    private $update_approval;

    public function __construct()
    {
        $this->update_approval = new UpdateApprovalStatusRepo();
    }

    public function updateApprovalStatus($event_id)
    {
        if (!$event_id) {
            return ['success' => false, 'message' => 'Error upfating approval status. Event ID is required.'];
        }

        return $this->update_approval->updateApprovalStatus($event_id);
    }
}
