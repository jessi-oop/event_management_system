<?php

require_once __DIR__ . '../UserRepository/getUserById.php';

class GetUserByIdService
{
    private $get_user_by_id;

    public function __construct()
    {
        $this->get_user_by_id = new GetUserByIdRepo();
    }

    public function getUserById($user_id)
    {
        if (empty($user_id)) {
            return ['success' => false, 'message' => 'Error getting user. User ID is empty.'];
        }

        $user = $this->get_user_by_id->getUserById($user_id);

        if ($user) {
            return $user;
        }

        return ['success' => false, 'message' => 'Error getting user.'];
    }
}
