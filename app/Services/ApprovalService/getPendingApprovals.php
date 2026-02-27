<?php


require_once __DIR__ . '/../../Repositories/ApprovalRepository/getPendingApprovals.php';

class GetPendingApprovalsService
{
    private $get_pending_approvals;

    public function __construct()
    {
        $this->get_pending_approvals = new GetPendingApprovalsRepo();
    }

    public function getPendingApprovals()
    {
        return $this->get_pending_approvals->getPendingApprovals();
    }
}
