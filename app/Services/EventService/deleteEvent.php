<?php

require_once __DIR__ . '/../../Repositories/EventRepository/deleteEvent.php';
require_once __DIR__ . '/../../Repositories/EventRepository/getEventById.php';
require_once __DIR__ . '/../AuthService/getCurrentUser.php';

class DeleteEventService
{
    private $delete_event;
    private $get_event_by_id;
    private $get_current_user;

    public function __construct()
    {
        $this->delete_event =  new DeleteEventRepo();
        $this->get_event_by_id = new GetEventByIdRepo();
        $this->get_current_user = new GetCurrentUser();
    }

    public function deleteEvent($event_id)
    {
        $current_user = $this->get_current_user->getCurrentUser();
        if (!$current_user) {
            return ['success' => false, 'message' => 'You must be logged in to perform this action.'];
        }

        if (!in_array($current_user->role, ['organizer', 'admin'])) {
            return ['success' => false, 'message' => 'You must be an organizer or admin to delete events.'];
        }

        if (empty($event_id)) {
            return ['success' => false, 'message' => 'Event id is required to delete an event.'];
        }

        $event = $this->get_event_by_id->getEventById($event_id);
        if (!$event) {
            return ['success' => false, 'message' => 'Event not found.'];
        }
        if ($event->organizer_id !== $current_user->user_id && $current_user->role !== 'admin') {
            return ['success' => false, 'message' => 'You can only delete events that you created.'];
        }

        $delete_event = $this->delete_event->deleteEvent($event_id);
        if ($delete_event) {
            return ['success' => true, 'message' => 'Event deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Error in deleting the event.'];
    }
}
