<?php

require_once __DIR__ . '/../../Repositories/UserRepository/getUserById.php';

class GetUserByIdService
{
    private $get_user_by_id;

    public function __construct()
    {
        $this->get_user_by_id = new GetUserByIdRepo();
    }

    public function getUserById($user_id)
    {
        if (!$user_id) {
            return ['success' => false, 'message' => 'Error getting user. User ID is empty.'];
        }

        return $this->get_user_by_id->getUserById($user_id);

    }
}
