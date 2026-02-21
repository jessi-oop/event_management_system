<?php

require_once __DIR__ . '/../../Repositories/EventRepository/getEventById.php';

class GetEventByIdService
{
    private $get_event_by_id;

    public function __construct()
    {
        $this->get_event_by_id = new GetEventByIdRepo();
    }

    public function getEventById($event_id)
    {
        if (empty($event_id)) {
            return ['success' => false, 'message' => 'Error getting. Event ID is empty.'];
        }

        return $this->get_event_by_id->getEventById($event_id);
    }
}
