<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/countByStatus.php';

class CountByStatusService
{
    private $count_by_status;

    public function __construct()
    {
        $this->count_by_status = new CountByStatusRepo();
    }

    // Count approvals by status
    public function countByStatus($status)
    {
        return $this->eventApprovalRepo->countByStatus($status);
    }
}
