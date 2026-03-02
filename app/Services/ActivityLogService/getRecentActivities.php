<?php

require_once __DIR__ . '/../../Repositories/ActivityLogRepository/getRecentActivities.php';

class GetRecentActivitiesService
{
    private $get_recent_activities;

    public function __construct()
    {
        $this->get_recent_activities = new GetRecentActivitiesRepo();
    }

    public function getRecentActivities($limit = 20)
    {
        return $this->get_recent_activities->getRecentActivities($limit);
    }
}
