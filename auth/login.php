<?php

session_start();
require_once __DIR__ . '/../app/Services/AuthService.php';

$auth = new AuthService();

if ($auth->isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $result = $auth->login($email, $password);

    if ($result['success']) {
        $user = $result['user'];
        if ($user->isAdmin()) {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../dashboard/index.php');
        }
        exit;
    } else {
        $message = $result['message'];
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link rel="stylesheet" href="../public/css/style.css" />
  </head>
  <body class="login-body">
    <div class="login-container">
      <h2>Login</h2>

      <?php if ($message): ?>
        <div class="message error">
            <?= htmlspecialchars($message) ?>
        </div>

        <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="login-form-group">
          <label for="email">Email:</label>
          <input
            type="text"
            id="email"
            name="email"
            placeholder="Enter email"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            required
          />
        </div>

        <div class="login-form-group">
          <label for="password">Password:</label>
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter password"
            value="<?= htmlspecialchars($_POST['password'] ?? '') ?>"
            required
          />
        </div>

        <div class="login-form-group">
          <input type="submit" value="Login" />
        </div>

        <div class="login-form-group">
          <p class="register-redirect">
            Don't have an account?
            <a href="register.php">Register now!</a>
          </p>
        </div>
      </form>
    </div>
  </body>
</html>
