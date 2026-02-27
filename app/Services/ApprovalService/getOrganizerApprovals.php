<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getApprovalByOrganizerId.php';

class GetApprovalByOrganizerIdService
{
    private $get_approval_by_orgaizer_id;

    public function __construct()
    {
        $this->get_approval_by_orgaizer_id = new GetApprovalByOrganizerIdRepo();
    }

    public function getOrganizerApprovals($organizer_id)
    {
        return $this->eventApprovalRepo->getApprovalsByOrganizerId($organizer_id);
    }
}
