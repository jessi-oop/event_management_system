<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Entities/User.php';
require_once __DIR__ . '/getUserByEmail.php';

class EmailExists
{
    private $db;
    private $get_user_by_email;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->get_user_by_email = new getUserByEmail();
    }

    // Check if email already exist
    public function emailExists($email)
    {
        return $this->get_user_by_email->getUserByEmail($email) !== null;
    }
}
