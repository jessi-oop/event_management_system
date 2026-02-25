<?php

require_once __DIR__ . '/../../Repositories/PasswordRepository/resetPasswordWithToken.php';

class ResetPasswordService
{
    private $reset_password;

    public function __construct()
    {
        $this->reset_password = new ResetPasswordWithTokenRepo();
    }

    public function resetPassword($token, $new_password, $confirm_password)
    {
        if ($new_password !== $confirm_password) {
            return [
                'success' => false,
                'message' => 'Passwords do not match.'
            ];
        }

        if (strlen($new_password) < 8) {
            return ['valid' => false, 'message' => 'Password must be greater than 8 characters.'];
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{6,}$/', $new_password)) {
            return [
                'valid' => false,
                'message' => 'Password must have at least one uppercase letter, 
                lowercase letter, number, and special character'];
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $result = $this->reset_password->resetPasswordWithToken($token, $hashed_password);

        if ($result) {
            return ['success' => true, 'message' => 'Password changed successfully.', 'result' => $result];
        }

        return ['success' => false, 'message' => 'Error in resetting password.'];
    }
}
