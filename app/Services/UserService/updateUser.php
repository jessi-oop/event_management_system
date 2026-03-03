<?php

require_once __DIR__ . '/../../Repositories/UserRepository/updateUser.php';

class UpdateUserService
{
    private $update_user;

    public function __construct()
    {
        $this->update_user = new UpdateUserRepo();
    }

    public function updateUser($full_name, $email, $user_id)
    {
        if (!$full_name || !$email || !$user_id) {
            return [
                'success' => false,
                'message' => 'All fields are required.'
            ];
        }

        $updated = $this->update_user->updateUser($full_name, $email, $user_id);

        return [
            'success' => $updated,
            'message' => $updated
                ? 'Profile updated successfully.'
                : 'Failed to update profile.'
        ];
    }
}
