<?php

class Registration
{
    public $registration_id;
    public $user_id;
    public $event_id;
    public $registered_at;

    // From views
    public $user_name;
    public $user_email;
    public $event_title;
    public $event_date;
    public $event_time;
    public $location;
    public $status;
    public $category_name;


    public function __construct($data = [])
    {
        $this->registration_id = $data['registration_id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->event_id = $data['event_id'] ?? null;
        $this->registered_at = $data['registered_at'] ?? null;

        $this->user_name = $data['user_name'] ?? null;
        $this->user_email = $data['user_email'] ?? null;
        $this->event_title = $data['event_title'] ?? null;
        $this->event_date = $data['event_date'] ?? null;
        $this->event_time = $data['event_time'] ?? null;
        $this->location = $data['location'] ?? null;
        $this->status = $data['status'] ?? 'upcoming';
        $this->category_name = $data['category_name'] ?? null;
    }

    public function getFormattedDate()
    {
        return date('F j, Y', strtotime($this->registered_at));
    }

    public function isPastEvent()
    {
        return strtotime($this->event_date) < strtotime('today');
    }
}
