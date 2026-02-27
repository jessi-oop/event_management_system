<?php

class EventApproval
{
    public $approval_id;
    public $event_id;
    public $organizer_id;
    public $status;
    public $submitted_at;
    public $reviewed_at;
    public $reviewed_by;
    public $rejection_reason;

    public function __construct($data)
    {
        $this->approval_id = $data['approval_id'] ?? null;
        $this->event_id = $data['event_id'] ?? null;
        $this->organizer_id = $data['organizer_id'] ?? null;
        $this->status = $data['status'] ?? 'pending';
        $this->submitted_at = $data['submitted_at'] ?? null;
        $this->reviewed_at = $data['reviewed_at'] ?? null;
        $this->reviewed_by = $data['reviewed_by'] ?? null;
        $this->rejection_reason = $data['rejection_reason'] ?? null;
    }

    public function toArray()
    {
        return [
            'approval_id' => $this->approval_id,
            'event_id' => $this->event_id,
            'organizer_id' => $this->organizer_id,
            'status' => $this->status,
            'submitted_at' => $this->submitted_at,
            'reviewed_at' => $this->reviewed_at,
            'reviewed_by' => $this->reviewed_by,
            'rejection_reason' => $this->rejection_reason
        ];
    }
}
