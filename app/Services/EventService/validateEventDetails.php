<?php

class ValidateEventDetails {
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