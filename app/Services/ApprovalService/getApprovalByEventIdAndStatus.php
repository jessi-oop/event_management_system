<?php

require_once __DIR__ . '/../../Repositories/ApprovalRepository/getApprovalByEventIdAndStatus.php';

class GetApprovalByEventIdAndStatusService
{
    private $get_approval_by_event_id_and_status;

    public function __construct()
    {
        $this->get_approval_by_event_id_and_status = new GetApprovalByEventIdAndStatusRepo();
    }

    public function getApprovalByEventIdAndStatus($event_id)
    {
        if (!$event_id) {
            return ['success' => false, 'message' => 'Erro getting approval. Event ID is required.'];
        }

        return $this->get_approval_by_event_id_and_status->getApprovalByEventIdAndStatus($event_id);
    }
}
