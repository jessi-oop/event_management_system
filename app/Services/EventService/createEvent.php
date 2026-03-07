<?php

require_once __DIR__ . '/../../Repositories/EventRepository/createEvent.php';
require_once __DIR__ . '/../../Repositories/CategoryRepository/getAllCategoryIds.php';
require_once __DIR__ . '/../AuthService/getCurrentUser.php';
require_once __DIR__ . '/validateEventDetails.php';
require_once __DIR__ . '/../ApprovalService/submitForApproval.php';
require_once __DIR__ . '/../../Core/Database.php';

class CreateEventService
{
    private $db;
    private $create_event;
    private $get_category_ids;
    private $get_current_user;
    private $validate_event_details;
    private $submit_for_approval;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->create_event = new CreateEventRepo();
        $this->get_category_ids = new GetAllCategoryIdsRepo();
        $this->get_current_user = new GetCurrentUserService();
        $this->validate_event_details = new ValidateEventDetails();
        $this->submit_for_approval = new SubmitForApprovalService();
    }

    public function createEvent(
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity,
        $image_path = null
    ) {
        $category_id = (int)$category_id;

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
            $capacity,
            $image_path
        );

        if (!$validate['valid']) {
            return ['success' => false, 'message' => $validate['message']];
        }

        $allowed_category_ids = $this->get_category_ids->getAllCategoryIds();
        if (!in_array($category_id, $allowed_category_ids, true)) {
            return ['success' => false, 'message' => 'Category must be in the available categories.'];
        }

        try {
            // Start transaction
            $this->db->beginTransaction();

            // Determine approval status based on role
            $approval_status = ($current_user->role === 'admin') ? 'approved' : 'pending';

            // Create event with appropriate approval status
            $event_id = $this->create_event->createEvent(
                $organizer_id,
                $category_id,
                $title,
                $description,
                $event_date,
                $event_time,
                $location,
                $capacity,
                $image_path,
                $approval_status
            );

            if (!$event_id) {
                throw new Exception('Failed to create event in database');
            }

            // Only submit for approval if user is an organizer (not admin)
            if ($current_user->role !== 'admin') {
                $approval_result = $this->submit_for_approval->submitForApproval($event_id, $organizer_id);

                if (!$approval_result['success']) {
                    throw new Exception('Failed to submit for approval');
                }

                $message = 'Event created and submitted for approval.';
            } else {
                $message = 'Event created and automatically approved.';
            }

            // Commit transaction
            $this->db->commit();

            return [
                'success' => true,
                'event_id' => $event_id,
                'message' => $message
            ];

        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            error_log('Event creation failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error in creating the event.'
            ];
        }
    }
}
