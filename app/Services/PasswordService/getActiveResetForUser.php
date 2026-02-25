<?php

require_once __DIR__ . '/../../Repositories/PasswordRepository/getActiveResetForUser.php';

class GetActiveResetForUser {
    private $get_active_reset;

    public function __construct(){
        $this->get_active_reset = new GetActiveResetForUser();
    }

     public function getActiveResetForUser($user_id) {
        return $this->get_active_reset->getActiveResetForUser($userId);
    }
}