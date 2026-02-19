<?php
session_start();
require_once __DIR__ . '/../app/Services/AuthService/isLoggedIn.php';
require_once __DIR__ . '/../app/Services/AuthService/register.php';

$is_logged_in = new IsLoggedIn();
$register = new Register();
$message = '';
$message_type = '';

if ($is_logged_in->isLoggedIn()) {
    header('Location: dashboard/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $register->register($full_name, $email, $role, $password);

    $message = $result['message'];
    $message_type = $result['success'] ? 'sucess' : 'error';
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/register.css" />
  </head>
  <body class="register-body">
    <div class="register-container">
      <h2>Create Account</h2>

      <!-- Conditionaly displays a styled message box -->
      <?php if ($message): ?> 
        <div class="message <?= htmlspecialchars($message_type) ?>">
          <?= htmlspecialchars($message)?>
        </div>
      <?php endif; ?>  

      <?php if ($message_type === 'success'): ?>
        <p><a href="login.php" class="btn">Click here to login.</a></p>
      <?php else: ?>

      <form method="POST" action="register.php">
        <div class="register-form-group">
          <label class="labels" for="fullname">Fullname: </label>
          <input
            class="inputs"
            type="text"
            id="fullname"
            name="fullname"
            placeholder="Enter fullname"
            value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
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
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            required
          />
        </div>

        <div class="register-form-group">
          <label class="labels" for="role">Role:</label>
          <select class="role-select" name="role" id="role" required>
            <option value="organizer">Organizer</option>
            <option value="attendee">Attendee</option>
          </select>
        </div>

        <div class="register-form-group">
          <label class="labels" for="password">Password:</label>
          <input
            class="inputs"
            type="password"
            id="password"
            name="password"
            placeholder="Enter password"
            value = "<?= htmlspecialchars($_POST['password'] ?? '') ?>"
            required
          />
        </div>

        <div class="register-form-group">
          <input type="submit" value="Register" />
        </div>

        <div class="register-form-group">
          <p class="login-redirect">
            Already have an account?
            <a href="login.php">Login here</a>
          </p>
        </div>
      </form>
      <?php endif; ?>
      
    </div>
  </body>
</html>
