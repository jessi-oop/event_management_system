<?php

require_once __DIR__ . '/../../Repositories/PasswordRepository/deleteExpiredToken.php';

class CleanUpExpiredTokens {
    private $delete_token;

    public function __construct(){
        $this->delete_token = new DeleteExpiredToken();
    }

    public function cleanUpExpiredTokens(){
        return $this->delete_token->deleteExpiredTokens();
    }
}