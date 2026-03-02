<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getRejectionReason.php';

class GetRejectionReasonService
{
    private $get_rejection_reason;

    public function __construct()
    {
        $this->get_rejection_reason = new GetRejectionReasonRepo();
    }

    public function getRejectionReason($event_id)
    {
        if (!$event_id) {
            return ['success' => false, 'message' => 'Error. Failed to retrieve reason, event id is empty.'];
        }

        return $this->get_rejection_reason->getRejectionReason($event_id);
    }
}
