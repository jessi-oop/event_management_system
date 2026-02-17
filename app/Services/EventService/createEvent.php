<?php

require_once __DIR__ . '/../../app/Repositories/EventRepository/createEvent.php';
require_once __DIR__ . '/../../app//Repositories/CategoryRepository/getAllCategoryIds.php';
require_once __DIR__ . '/../../Services/AuthService/getCurrentUser.php';
require_once __DIR__ / '/validateEventDetails.php';

class CreateEvent {
    private $create_event;
    private $get_category_ids;
    private $get_current_user;
    private $validate_event_details;

    public function __construct()
    {
        $this->create_event = new createEvent();
        $this->get_category_ids = new getAllCategoryIds();
        $this->get_current_user = new getCurrentUser();
        $this->validate_event_details = new validateEventDetails();
    }

    public function createEvent(
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
            return ['success' => false, 'message' => 'You must be logged in to create events.'];
        }


        if (!in_array($current_user->role, ['organizer', 'admin'])) {
            return ['success' => false, 'message' => 'You must be an organizer or admin to create events.'];
        }

        $organizer_id = $current_user->user_id;

        $validate = $this->validate_event_details->validateEventDetails(
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

        $allowed_category_ids = $this->get_category_ids->getAllCategoryIds();
        if (!in_array($category_id, $allowed_category_ids, true)) {
            return ['success' => false, 'message' => 'Category must be in the available categories.'];
        }

        $create_event = $this->create_event->createEvent(
            $organizer_id,
            $category_id,
            $title,
            $description,
            $event_date,
            $event_time,
            $location,
            $capacity
        );

        if ($create_event) {
            return ['success' => true, 'message' => 'Event created successfully.'];
        }

        return ['success' => false, 'message' => 'Error in creating the event.'];
    }
}