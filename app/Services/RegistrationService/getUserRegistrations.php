<?php

require_once __DIR__ . '/../../Repositories/RegistrationRepository/getUserRegistrations.php';

class GetUserRegistrationsService
{
    private $get_user_registrations;

    public function __construct()
    {
        $this->get_user_registrations = new GetUserRegistrationsRepo();
    }

    public function getUserRegistrations($user_id)
    {
        if (empty($user_id)) {
            return ['success' => false, 'message' => 'Error. Failed to get user registrations, user id is empty.'];
        }

        $user_registrations = $this->get_user_registrations->getUserRegistrations($user_id);

        if ($user_registrations !== false) {
            return  $user_registrations;
        }

        return ['success' => false, 'message' => 'Failed to retrieve user registrations.'];
    }
}
