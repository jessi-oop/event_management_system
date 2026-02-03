<?php
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Services/AuthService.php';

$auth = new AuthService();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->regsiter($username, $email, $password)) {
        $message = "Registration successful.";
    } else {
        $message = "Registration failed. Email might already exist.";
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="../CSS/style.css" />
  </head>
  <body class="register-body">
    <div class="register-container">
      <h2>Register Form</h2>
      <form method="POST" action="register.php">
        <div class="register-form-group">
          <label class="labels" for="username">Username: </label>
          <input
            class="inputs"
            type="text"
            id="username"
            name="username"
            placeholder="Enter username"
            required
          />
        </div>

        <div class="register-form-group">
          <label class="labels" for="email">Email:</label>
          <input
            class="inputs"
            type="text"
            id="email"
            name="email"
            placeholder="Enter email"
            required
          />
        </div>

        <div class="register-form-group">
          <label class="labels" for="password">Password:</label>
          <input
            class="inputs"
            type="password"
            id="password"
            name="password"
            placeholder="Enter password"
            required
          />
        </div>

        <div class="register-form-group">
          <input type="submit" value="Register" />
        </div>

        <div class="register-form-group">
          <p class="login-redirect">
            Already have an account?
            <a href="login.html" target="_blank">Login</a>
          </p>
        </div>
      </form>
    </div>
  </body>
</html>
