<?php

require_once __DIR__ . '/../../app/Repositories/EventRepository/getAllUpcomingEvents.php';

class GetUpcomingEvents {
    private $get_upcoming_events;

    public function __construct(){
        $this->get_upcoming_events = new getAllUpcomingEvents();
    }

    public function getUpcomingEvents()
    {
        return $this->get_upcoming_events->getAllUpcomingEvents();
    }
}