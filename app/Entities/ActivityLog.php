<?php

class ActivityLog
{
    public $activity_id;
    public $user_id;
    public $type;
    public $description;
    public $created_at;

    public function __construct($data)
    {
        $this->activity_id = $data['activity_id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }
}
