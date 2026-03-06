<?php
session_start();

$show_reset_link = isset($_SESSION['show_reset_link']) ? $_SESSION['show_reset_link'] : false;
$reset_token = isset($_SESSION['reset_token']) ? $_SESSION['reset_token'] : '';
$reset_email = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '';

// Only clear if user explicitly dismissed or came from the reset page
if (isset($_GET['dismiss']) || isset($_GET['from_reset'])) {
    unset($_SESSION['show_reset_link'], $_SESSION['reset_token'], $_SESSION['reset_email']);
    header('Location: /Event-Management-System/auth/forgotPassword.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    />
    <link rel="stylesheet" href="../assets/css/forgot-password.css" />
  </head>
  <body class="login-body">
    <div class="login-container">
      <h2>Forgot Password</h2>
      <p class="page-subtitle">
        Enter your email address and we'll generate a password reset link.
      </p>

      <?php if (!$show_reset_link): ?>
        <form method="POST" action="/Event-Management-System/auth/form-handlers/forgotPasswordHandler.php">
          <div class="login-form-group">
            <label for="email">Email:</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Enter your email"
              required
            />
          </div>

          <div class="login-form-group">
            <input type="submit" value="Send Reset Link" />
          </div>

          <div class="login-form-group">
            <p class="register-redirect">
              Remember your password?
              <a href="login.php">Back to Login</a>
            </p>
          </div>
        </form>
      <?php endif; ?>

      <?php if ($show_reset_link && $reset_token): ?>
        <div class="reset-link-panel">
          <h6>
            <i class="bi bi-key-fill"></i> Reset Link Generated Successfully
          </h6>
          
          <p class="mb-3">Click the button below to reset your password:</p>
          
          <a 
            href="/Event-Management-System/auth/resetPassword.php?token=<?php echo htmlspecialchars($reset_token); ?>"
            class="reset-btn"
          >
            <i class="bi bi-arrow-right-circle"></i> Reset My Password
          </a>
          
          <p class="expire-note">
            <i class="bi bi-clock"></i> This link expires in 1 hour
          </p>
          
          <div class="mt-3">
            <a href="?dismiss=1" class="text-muted" style="font-size: 0.875rem;">
              <i class="bi bi-x-circle"></i> Dismiss and request a new link
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <?php include '../includes/modal.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>