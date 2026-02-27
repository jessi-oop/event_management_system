<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getOrganizerApprovalByStatus.php';

class GetOrganizerApprovalByStatusService
{
    private $get_organizer_apporval_by_status;

    public function __construct()
    {
        $this->get_organizer_apporval_by_status = new GetOrganizerApprovalByStatusRepo();
    }

    public function getOrganizerApprovals($organizer_id)
    {
        return $this->get_organizer_apporval_by_status->getApprovalsByOrganizerId($organizer_id);
    }
}
