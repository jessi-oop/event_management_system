<?php

require_once __DIR__ . '/../../Repositories/UserRepository/getUserById.php';
require_once __DIR__ . '/isLoggedIn.php';

class GetCurrentUserService
{
    private $get_user_by_id;
    private $is_logged_in;

    public function __construct()
    {
        $this->get_user_by_id = new GetUserByIdRepo();
        $this->is_logged_in = new IsLoggedIn();
    }

    // get the current user that is logged in
    public function getCurrentUser()
    {
        if (!$this->is_logged_in->isLoggedIn()) {
            return null;
        }

        return $this->get_user_by_id->getUserById($_SESSION['user_id']);
    }
}
