<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getPendingApprovalsDetails.php';

class GetPendingApprovalsDetailsService
{
    private $get_pending_approvals_details;

    public function __construct()
    {
        $this->get_pending_approvals_details = new GetPendingApprovalsDetailsRepo();
    }

    public function getPendingApprovalsDetails()
    {
        return $this->get_pending_approvals_details->getPendingApprovalsDetails();
    }
}
