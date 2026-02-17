<?php

class ValidateRegistration {
    // validate credentials
    public function validateRegistration($full_name, $email, $role, $password)
    {
        if (empty($full_name) || empty($email) || empty($role) || empty($password)) {
            return ['valid' => false, 'message' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Invalid email format.'];
        }

        if (strlen($password) < 8) {
            return ['valid' => false, 'message' => 'Password must be greater than 8 characters.'];
        }

        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{6,}$/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must have at least one uppercase letter, 
                lowercase letter, number, and special character'];
        }

        return ['valid' => true];
    }
}