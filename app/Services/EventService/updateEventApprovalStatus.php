<?php

require_once __DIR__ . '/../../Repositories/EventRepository/updateEventApprovalStatus.php';

class UpdateEventStatusService
{
    private $update_event_status;

    public function __construct()
    {
        $this->update_event_status = new UpdateEventStatusRepo();
    }

    public function updateEventStatus($event_id)
    {
        if (!$event_id) {
            return ['success' => false, 'message' => 'Error updating status. Event ID is required.'];
        }

        return $this->update_event_status->updateEventStatus($event_id);
    }
}
