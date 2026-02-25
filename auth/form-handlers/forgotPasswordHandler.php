<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Handler reached!<br>";
echo "POST data: ";
print_r($_POST);
echo "<br><br>";

require_once __DIR__ . '/../../app/Services/PasswordService/generateResetToken.php';

$generate_token = new GenerateResetTokenService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    echo "Email received: $email<br>";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['modal_message'] = "Invalid email address.";
        $_SESSION['modal_type'] = "error";
        header('Location: /Event-Management-System/auth/forgotPassword.php');
        exit;
    }

    $result = $generate_token->generateResetToken($email);

    if ($result['success']) {
        if ($result['token']) {
            $_SESSION['reset_token'] = $result['token'];
            $_SESSION['reset_email'] = $result['email'];
            $_SESSION['show_reset_link'] = true;
        }

        $_SESSION['modal_message'] = $result['message'];
        $_SESSION['modal_type'] = 'success';
    } else {
        $_SESSION['modal_message'] = $result['message'];
        $_SESSION['modal_type'] = 'error';
    }

    header('Location: /Event-Management-System/auth/forgotPassword.php');
    exit;
}

header('Location: /Event-Management-System/auth/forgotPassword.php');
exit;
