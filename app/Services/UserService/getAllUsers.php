<?php

require_once __DIR__ . '/../../Repositories/UserRepository/getAllUsers.php';

class GetAllUsersService {
    private $get_all_users;

    public function __construct(){
        $this->get_all_users = new GetAllUsersRepo();
    }

    public function getAllUsers(){
        return $this->get_all_users->getAllUsers();
    }
}