<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getApprovalById.php';

class GetApprovalByIdService
{
    private $get_approval_by_id;

    public function __construct()
    {
        $this->get_approval_by_id = new GetApprovalByIdRepo();
    }

    public function getApprovalById($approval_id)
    {
        return $this->get_approval_by_id->getApprovalById($approval_id);
    }
}
