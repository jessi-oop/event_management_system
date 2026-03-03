<?php

require_once __DIR__ . '/../../Repositories/UserRepository/updateUserPassword.php';

class UpdateUserPasswordService
{
    private $update_password;

    public function __construct()
    {
        $this->update_password = new UpdateUserPasswordRepo();
    }

    public function updateUserPassword($user_id, $new_password, $confirm_password)
    {
        if (!$user_id || !$new_password || !$confirm_password) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if ($new_password !== $confirm_password) {
            return ['success' => false, 'message' => 'New passwords do not match.'];
        }

        if (strlen($new_password) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters.'];
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/', $new_password)) {
            return [
                'success' => false,
                'message' => 'Password must contain uppercase, lowercase, number, and special character.'
            ];
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $result = $this->update_password->updateUserPassword($user_id, $hashed_password);

        return [
            'success' => $result,
            'message' => $result ? 'Your password has been changed successfully.' : 'Failed to update password. Please try again.'
        ];
    }
}
