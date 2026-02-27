<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/deleteApproval.php';

class DeleteApprovalService
{
    private $delete_approval;

    public function __construct()
    {
        $this->delete_approval = new DeleteApprovalRepo();
    }

    // Delete approval
    public function deleteApproval($approval_id)
    {
        return $this->eventApprovalRepo->deleteApproval($approval_id);
    }
}
