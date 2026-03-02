<?php

require_once __DIR__ . '/../../Repositories/UserRepository/deleteUser.php';

class DeleteUserService
{
    private $delete_user;

    public function __construct()
    {
        $this->delete_user = new DeleteUserRepo();
    }

    public function deleteUser($user_id)
    {
        if (empty($user_id)) {
            return ['success' => false, 'message' => 'Error. User ID is missing.'];
        }

        $user_deleted = $this->delete_user->deleteUser($user_id);

        return ['success' => true, 'message' => 'User deleted.', 'data' => $user_deleted];
    }
}
