<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getApprovalByEventId.php';

class GetApprovalByEventIdService
{
    private $get_approval_by_event_id;

    public function __construct()
    {
        $this->get_approval_by_event_id = new GetApprovalByEventIdRepo();
    }

    public function getApprovalByEventId($event_id)
    {
        return $this->eventApprovalRepo->getApprovalByEventId($event_id);
    }
}
