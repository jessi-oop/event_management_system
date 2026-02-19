<?php

require_once __DIR__ . '/../Repositories/EventRepository.php';
require_once __DIR__ . '/../Repositories/CategoryRepository.php';
require_once __DIR__ . '/AuthService.php';

class EventService
{
    private $event_repo;
    private $category_repo;
    private $auth;

    public function __construct()
    {
        $this->event_repo = new EventRepository();
        $this->category_repo = new CategoryRepository();
        $this->auth = new AuthService();
    }

}
