<?php

require_once __DIR__ . '/../../Repositories/EventRepository/updateEvent.php';
require_once __DIR__ . '/../../Repositories/EventRepository/getEventById.php';
require_once __DIR__ . '/../../Repositories/CategoryRepository/getAllCategoryIds.php';
require_once __DIR__ . '/../AuthService/getCurrentUser.php';
require_once __DIR__ . '/validateEventDetails.php';

class UpdateEventService
{
    private $update_event;
    private $get_event_by_id;
    private $get_all_category_ids;
    private $get_current_user;
    private $validate_event;

    public function __construct()
    {
        $this->update_event = new UpdateEventRepo();
        $this->get_event_by_id = new GetEventByIdService();
        $this->get_all_category_ids = new GetAllCategoryIdsRepo();
        $this->get_current_user = new GetCurrentUser();
        $this->validate_event = new ValidateEventDetails();
    }

    public function updateEvent(
        $event_id,
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity
    ) {

        $current_user = $this->get_current_user->getCurrentUser();

        if (!$current_user) {
            return ['success' => false, 'message' => 'You must be logged in to update events.'];
        }

        if (!in_array($current_user->role, ['organizer', 'admin'])) {
            return ['success' => false, 'message' => 'You must be an organizer or admin to update events.'];
        }

        if (empty($event_id)) {
            return ['success' => false, 'message' => 'Event id is required to update events.'];
        }

        $validate = $this->validate_event->validateEventDetails(
            $event_id,
            $category_id,
            $title,
            $description,
            $event_date,
            $event_time,
            $location,
            $capacity
        );

        if (!$validate['valid']) {
            return ['success' => false, 'message' => $validate['message']];
        }

        $allowed_category_ids = $this->get_all_category_ids->getAllCategoryIds();
        if (!in_array($category_id, $allowed_category_ids, true)) {
            return ['success' => false, 'message' => 'Category must be in the available categories.'];
        }

        $event = $this->get_event_by_id->getEventById($event_id);
        if (!$event) {
            return ['success' => false, 'message' => 'Event not found.'];
        }
        if ($event->organizer_id !== $current_user->user_id && $current_user->role !== 'admin') {
            return ['success' => false, 'message' => 'You can only update events that you created.'];
        }

        $update_event = $this->update_event->updateEvent(
            $event_id,
            $category_id,
            $title,
            $description,
            $event_date,
            $event_time,
            $location,
            $capacity
        );

        if ($update_event) {
            return ['success' => true, 'message' => 'Event has been successfully updated.'];
        }

        return ['success' => false, 'message' => 'Error updating event.'];
    }
}
