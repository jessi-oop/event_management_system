<?php

require_once __DIR__ . '/../../Repositories/EventRepository/getAllEvents.php';

class GetAllEventsService
{
    private $get_all_events;

    public function __construct()
    {
        $this->get_all_events = new GetAllEventsRepo();
    }

    public function getAllEvents()
    {
        return $this->get_all_events->getAllEvents();
    }
}
