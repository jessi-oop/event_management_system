<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthService
{
    private $user_repo;

    public function __construct()
    {
        $this->user_repo = new UserRepository(); //creates a new and local instance of the UserRepo class
    }

}
