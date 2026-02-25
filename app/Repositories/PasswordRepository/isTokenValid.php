<?php

require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/findByToken.php';

class IsTokenValid{
    private $db;
    private $find_by_token;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
        $this->find_by_token = new FindByToken();
    }

    public function isTokenValid($token) {
        $reset_data = $this->find_by_token->findByToken($token);

        if(!$reset_data){
            return false;
        }

        return $reset_data['token_status'] === 'valid';
    }
}