<?php

require_once __DIR__ . '/../../Repositories/PasswordRepository/createToken.php';
require_once __DIR__ . '/../../Repositories/UserRepository/getUserbyEmail.php';

class GenerateResetTokenService
{
    private $create_token;
    private $get_user_by_email;

    public function __construct()
    {
        $this->create_token = new CreateToken();
        $this->get_user_by_email = new GetUserByEmail();
    }

    public function generateResetToken($email)
    {
        if (empty($email)) {
            return ['success' => false, 'message' => 'Email does not exist.'];
        }

        $user = $this->get_user_by_email->getUserByEmail($email);

        if (!$user) {
            return [
                'success' => true,
                'message' => 'Link has been generated.',
                'token' => null,
                'email' => null

            ];
        }

        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', time() + 3600);

        $create_token = $this->create_token->createToken($user->user_id, $token, $expires_at);

        if ($create_token) {
            return [
                'success' => true,
                'message' => 'Password reset token generated successfully.',
                'token' => $token,
                'email' => $email,
                'full_name' => $user->full_name
            ];
        }

        return [
            'success' => false,
            'message' => 'Error generating reset token.',
            'token' => null,
            'email' => null
        ];
    }
}
