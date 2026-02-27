<?php

class Event
{
    public $event_id;
    public $organizer_id;
    public $category_id;
    public $title;
    public $description;
    public $event_date;
    public $event_time;
    public $location;
    public $capacity;
    public $status;
    public $approval_status;
    public $created_at;
    public $updated_at;

    // Additional fiedls from views (if loaded from event_summary view table)
    public $category_name;
    public $organizer_name;
    public $organizer_email;
    public $registered_count;
    public $available_spots;

    public function __construct($data = [])
    {
        $this->event_id = $data['event_id'] ?? null;
        $this->organizer_id = $data['organizer_id'] ?? null;
        $this->category_id = $data['category_id'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->event_date = $data['event_date'] ?? null;
        $this->event_time = $data['event_time'] ?? null;
        $this->location = $data['location'] ?? null;
        $this->capacity = $data['capacity'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->approval_status = $data['approval_status'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;

        // if from views
        $this->category_name = $data['category_name'] ?? null;
        $this->organizer_name = $data['organizer_name'] ?? null;
        $this->organizer_email = $data['organizer_email'] ?? null;
        $this->registered_count = $data['registered_count'] ?? null;
        $this->available_spots = $data['available_spots'] ?? null;
    }

    // Check if event is full
    public function isEventFull()
    {
        return $this->available_spots <= 0;
    }

    // Check if an event is in the past
    public function isPastEvent()
    {
        return strtotime($this->event_date) < strtotime('today');
    }

    // Check if user is the organizer of this event
    public function isOrganizedBy($user_id)
    {
        return $this->organizer_id == $user_id;
    }

    // Format date for display
    public function getFormattedDate()
    {
        return date('F j, Y', strtotime($this->event_date));
    }

    // Format time for display
    public function getFormattedTime()
    {
        return date('g:i A', strtotime($this->event_time));
    }

    // Get formatted date and time
    public function getFormattedDateAndTime()
    {
        return $this->getFormattedDate() . ' at ' . $this->getFormattedTime();
    }

    public function isUpcoming()
    {
        return $this->status === 'upcoming';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFull()
    {
        return $this->status === 'full' || $this->available_spots <= 0;
    }

}
