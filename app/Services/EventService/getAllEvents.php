<?php

require_once __DIR__ . '/../../app/Repositories/EventRepository/getAllEvents.php';

class GetAllEvents {
    private $get_all_events;

    public function __construct(){
        $this->get_all_events = new getAllEvents();
    }

    public function getAllEvents()
    {
        return $this->event_repo->getAllEvents();
    }
}