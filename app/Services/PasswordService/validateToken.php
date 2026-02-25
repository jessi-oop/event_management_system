<?php

require_once __DIR__ . '/../../Repositories/PasswordRepository/findByToken.php';

class ValidateTokenService {
    private $find_by_token;

    public function __construct(){
        $this->find_by_token = new FindByToken();
    }

    public function validateToken($token){
        if(empty($token)){
            return ['success'=> false, 'message'=> 'Error validting token. Token does not exist.'];
        }

        $reset_data = $this->find_by_token->findByToken($token);

        if(!$reset_data){
            return [
                'valid'=> false,
                'message'=> 'Invalid reset token',
                'data' => null
            ];
        }

        $status = $reset_data['token_status'];

        if($status === 'expired'){
            return [
                'valid'=> false,
                'message'=> 'Token has already expired.',
                'data'=> null

            ];
        }

        if($status === 'used'){
            return [
                'valid'=> false,
                'message'=> 'Token has already been used.',
                'data'=> null
            ];
        }

        return [
            'valid'=> true,
            'message'=> 'Token is valid',
            'data'=> $reset_data
        ];
    }
}