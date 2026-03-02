<?php

require_once __DIR__ . '/../../Repositories/EventRepository/getEventCountByMonth.php';

class GetEventCountByMonthService
{
    private $get_event_count_by_month;

    public function __construct()
    {
        $this->get_event_count_by_month = new GetEventCountByMonthRepo();
    }

    public function getEventCountByMonth()
    {
        return $this->get_event_count_by_month->getEventCountByMonth();
    }
}
