<?php

class ValidateEventDetails
{
    public function validateEventDetails(
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity,
        $image_file = null
    ) {

        if (empty($category_id)) {
            return ['valid' => false, 'message' => 'Category id is required'];
        }

        if (empty($title)) {
            return ['valid' => false, 'message' => 'Title is required'];
        }

        if (empty($description)) {
            return ['valid' => false, 'message' => 'Description is required'];
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

        // Image validation
        if ($image_file !== null && isset($image_file['error'])) {
            // If file was uploaded (not just empty input)
            if ($image_file['error'] !== UPLOAD_ERR_NO_FILE) {
                // Check for upload errors
                if ($image_file['error'] !== UPLOAD_ERR_OK) {
                    return ['valid' => false, 'message' => 'Error uploading image. Please try again.'];
                }

                // Validate file type
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!in_array($image_file['type'], $allowed_types)) {
                    return ['valid' => false, 'message' => 'Invalid image format. Only JPG, PNG, and GIF are allowed.'];
                }

                // Validate file size (5MB max)
                $max_size = 5 * 1024 * 1024; // 5MB
                if ($image_file['size'] > $max_size) {
                    return ['valid' => false, 'message' => 'Image size must be less than 5MB.'];
                }

                // Validate actual image (prevents fake extensions)
                $image_info = getimagesize($image_file['tmp_name']);
                if ($image_info === false) {
                    return ['valid' => false, 'message' => 'Uploaded file is not a valid image.'];
                }
            }
        }

        return ['valid' => true];
    }
}
