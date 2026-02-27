<?php

require_once __DIR__ . '/../../Repositories/ActivityLogRepository/logActivity.php';

class LogActivityService {
    private $log_activity;

    public function __construct(){
        $this->log_activity = new LogActivityRepo;
    }

     public function logActivity($user_id, $type, $description)
    {
        $this->log_activity->logActivity($user_id, $type, $description);
    }
}