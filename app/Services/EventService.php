<?php

require_once __DIR__ . '/../Repositories/EventRepository.php';
require_once __DIR__ . '/../Repositories/CategoryRepository.php';
require_once __DIR__ . '/AuthService.php';

class EventService
{
    private $event_repo;
    private $category_repo;
    private $auth;

    public function __construct()
    {
        $this->event_repo = new EventRepository();
        $this->category_repo = new CategoryRepository();
        $this->auth = new AuthService();
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

        $current_user = $this->auth->getCurrentUser();
        if (!$current_user) {
            return ['success' => false, 'message' => 'You must be logged in to create events.'];
        }


        if (!in_array($current_user->role, ['organizer', 'admin'])) {
            return ['success' => false, 'message' => 'You must be an organizer or admin to create events.'];
        }

        $organizer_id = $current_user->user_id;

        $validate = $this->validateEventDetails(
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

        $allowed_category_ids = $this->category_repo->getAllCategoryIds();
        if (!in_array($category_id, $allowed_category_ids, true)) {
            return ['success' => false, 'message' => 'Category must be in the available categories.'];
        }

        $create_event = $this->event_repo->createEvent(
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

    public function getAllEvents()
    {
        return $this->event_repo->getAllEvents();
    }

    public function getUpcomingEvents()
    {
        return $this->event_repo->getAllUpcomingEvents();
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

        $current_user = $this->auth->getCurrentUser();

        if (!$current_user) {
            return ['success' => false, 'message' => 'You must be logged in to update events.'];
        }

        if (!in_array($current_user->role, ['organizer', 'admin'])) {
            return ['success' => false, 'message' => 'You must be an organizer or admin to update events.'];
        }

        if (empty($event_id)) {
            return ['success' => false, 'message' => 'Event id is required to update events.'];
        }

        $validate = $this->validateEventDetails(
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

        $allowed_category_ids = $this->category_repo->getAllCategoryIds();
        if (!in_array($category_id, $allowed_category_ids, true)) {
            return ['success' => false, 'message' => 'Category must be in the available categories.'];
        }

        $event = $this->event_repo->getEventById($event_id);
        if (!$event) {
            return ['success' => false, 'message' => 'Event not found.'];
        }
        if ($event->organizer_id !== $current_user->user_id && $current_user->role !== 'admin') {
            return ['success' => false, 'message' => 'You can only update events that you created.'];
        }

        $update_event = $this->event_repo->updateEvent(
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

    public function deleteEvent($event_id)
    {
        $current_user = $this->auth->getCurrentUser();
        if (!$current_user) {
            return ['success' => false, 'message' => 'You must be logged in to perform this action.'];
        }

        if (!in_array($current_user->role, ['organizer', 'admin'])) {
            return ['success' => false, 'message' => 'You must be an organizer or admin to delete events.'];
        }

        if (empty($event_id)) {
            return ['success' => false, 'message' => 'Event id is required to delete an event.'];
        }

        $event = $this->event_repo->getEventById($event_id);
        if (!$event) {
            return ['success' => false, 'message' => 'Event not found.'];
        }
        if ($event->organizer_id !== $current_user->user_id && $current_user->role !== 'admin') {
            return ['success' => false, 'message' => 'You can only delete events that you created.'];
        }

        $delete_event = $this->event_repo->deleteEvent($event_id);
        if ($delete_event) {
            return ['success' => true, 'message' => 'Event deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Error in deleting the event.'];
    }

    public function validateEventDetails(
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity
    ) {

        if (empty($category_id)) {
            return ['valid' => false, 'message' => 'Category id is required'];
        }

        if (empty($title)) {
            return ['valid' => false, 'message' => 'Title is required'];
        }

        if (empty($description)) {
            return ['valid' => false, 'message' => 'Description  is required'];
        }

        if (empty($event_date)) {
            return ['valid' => false, 'message' => 'Event date is required'];
        }

        if (empty($event_time)) {
            return ['valid' => false, 'message' => 'Event time is required'];
        }

        if (empty($location)) {
            return ['valid' => false, 'message' => 'Location is required'];
        }

        if (empty($capacity)) {
            return ['valid' => false, 'message' => 'Capacity is required'];
        }

        return ['valid' => true];
    }

}
