<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getApprovalsByStatus.php';

class GetApprovalsByStatusService
{
    private $get_apporvals_by_status;

    public function __construct()
    {
        $this->get_apporvals_by_status = new GetApprovalsByStatusRepo();
    }

    public function getApprovalsByStatus($status)
    {
        return $this->get_apporvals_by_status->getApprovalsByStatus($status);
    }
}
