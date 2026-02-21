<?php

require_once __DIR__ . '/../../Repositories/EventRepository/getEventByOrganizer.php';

class GetEventByOrganizerService
{
    private $get_event;

    public function __construct()
    {
        $this->get_event = new GetEventByOrganizerRepo();
    }

    public function getEventByOrganizer($organizer_id)
    {
        if (empty($organizer_id)) {
            return [];
        }

        return $this->get_event->getEventByOrganizer($organizer_id);
    }
}
