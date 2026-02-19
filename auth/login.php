<?php

session_start();
require_once __DIR__ . '/../app/Services/AuthService/isLoggedIn.php';
require_once __DIR__ . '/../app/Services/AuthService/login.php';

$is_logged_in = new IsLoggedIn();
$login = new Login();

if ($is_logged_in->isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $message = 'Email and password is required.';
        return;
    } else {
        $result = $login->login($email, $password);
    }

    if ($result['success']) {
        $user = $result['user'];
        if ($user->isAdmin()) {

            header('Location: /Event-Management-System/admin/index.php');
        } else {
            header('Location: /Event-Management-System/organizer/manage.php');
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

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    />
    <link rel="stylesheet" href="../assets/css/login.css" />

    <script
      defer
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>
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
            value = "<?= htmlspecialchars($_POST['password'] ?? '') ?>"
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
