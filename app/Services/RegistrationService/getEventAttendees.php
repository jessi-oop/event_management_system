<?php

require_once __DIR__ . '/../../Repositories/RegistrationRepository/getEventAttendees.php';
require_once __DIR__ . '/../EventService/getEventById.php';

class GetEventAttendeesService
{
    private $get_event_attendees;
    private $get_event_by_id;

    public function __construct()
    {
        $this->get_event_attendees = new GetEventAttendeesRepo();
        $this->get_event_by_id = new GetEventByIdService();
    }

    public function getEventAttendees($event_id)
    {
        if (empty($event_id)) {
            return ['success' => false, 'message' => 'Error. Event ID is empty.'];
        }

        $event = $this->get_event_by_id->getEventById($event_id);

        if (!$event) {
            return ['success' => false, 'message' => 'Error retrieving attendees. Event does not exist.'];
        }

        $event_attendees = $this->get_event_attendees->getEventAttendees($event_id);

        if ($event_attendees) {
            return ['success' => true, 'data' => $event_attendees];
        }

        return ['success' => false, 'message' => 'Failed to retrieve attendees.'];
    }
}
