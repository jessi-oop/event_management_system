<?php

require_once __DIR__ . '/../../Core/Database.php';

class ResetPasswordWithTokenRepo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function resetPasswordWithToken($token, $hashed_password)
    {
        try {
            $stmt = $this->db->prepare('CALL sp_reset_password(?, ?, @result, @message)');
            $stmt->execute([$token, $hashed_password]);
            $stmt->closeCursor();

            $result = $this->db->query('SELECT @result AS result, @message AS message');

            if ($result) {
                $output = $result->fetch();
                return [
                    'success' => $output['result'] === 'success', // ⭐ Fixed typo: 'sucsess' → 'success'
                    'message' => $output['message']
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to reset the password.'
            ];
        } catch (PDOException $e) {
            error_log('Error resetting password: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to reset the password.'
            ];
        }
    }
}
