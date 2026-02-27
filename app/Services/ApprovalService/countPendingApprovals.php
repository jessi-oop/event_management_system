<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/countPendingApprovals.php';

class CountPendingApprovalsService
{
    private $count_pending_approvals;

    public function __construct()
    {
        $this->count_pending_approvals = new CountPendingApprovalsRepo();
    }

    // Count pending approvals
    public function countPendingApprovals()
    {
        return $this->eventApprovalRepo->countPendingApprovals();
    }

}
