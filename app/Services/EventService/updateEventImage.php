<?php

require_once __DIR__ . '/../../Repositories/EventRepository/updateEventImage.php';
require_once __DIR__ . '/../../Repositories/EventRepository/getEventImagePath.php';

class UpdateEventImageService
{
    private $update_event_image;
    private $get_event_image_path;

    public function __construct()
    {
        $this->update_event_image = new UpdateEventImageRepo();
        $this->get_event_image_path = new GetEventImagePathRepo();
    }

    // New method for updating event image
    public function updateEventImage($event_id, $new_image_path)
    {
        try {
            // Get old image path first
            $old_image_path = $this->get_event_image_path->getEventImagePath($event_id);

            // Update in database
            $updated = $this->update_event_image->updateEventImage($event_id, $new_image_path);

            if (!$updated) {
                return [
                    'success' => false,
                    'message' => 'Failed to update event image in database'
                ];
            }

            // Delete old image if it exists and is different
            if ($old_image_path && $old_image_path !== $new_image_path) {
                $old_file_path = __DIR__ . '/../../../' . $old_image_path;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }

            return [
                'success' => true,
                'message' => 'Event image updated successfully'
            ];

        } catch (Exception $e) {
            error_log('Image update failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to update event image'
            ];
        }
    }
}
