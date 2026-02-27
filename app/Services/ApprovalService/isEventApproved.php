<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/isEventApproved.php';

class IsEventApprovedService
{
    private $is_event_approved;

    public function __construct()
    {
        $this->is_event_approved = new IsEventApprovedRepo();
    }

    // Check if event is approved
    public function isEventApproved($event_id)
    {
        return $this->is_event_approved->isEventApproved($event_id);
    }
}
