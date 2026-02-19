<?php

require_once __DIR__ . '/../../Repositories/EventRepository/getAllUpcomingEvents.php';

class GetUpcomingEventsService
{
    private $get_upcoming_events;

    public function __construct()
    {
        $this->get_upcoming_events = new GetAllUpcomingEventsRepo();
    }

    public function getUpcomingEvents()
    {
        return $this->get_upcoming_events->getAllUpcomingEvents();
    }
}
