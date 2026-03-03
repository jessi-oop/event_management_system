<?php
session_start();

require_once __DIR__ . '/../app/Services/PasswordService/validateToken.php';

$validate_token = new ValidateTokenService();

$token = isset($_GET['token']) ? $_GET['token'] : '';
$valid_token = false;
$email = '';

if ($token) {
    $validate = $validate_token->validateToken($token);

    if ($validate['valid']) {
        $valid_token = true;
        $email = $validate['data']['email'];
    } else {
        $_SESSION['modal_message'] = $validate['message'];
        $_SESSION['modal_type'] = "error";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    />
    <!-- ↓ Dedicated stylesheet, no longer sharing login.css -->
    <link rel="stylesheet" href="../assets/css/reset-password.css" />
  </head>
  <body class="login-body">
    <div class="login-container">
      <h2>Reset Password</h2>

      <?php if ($valid_token): ?>

        <p class="page-subtitle">
          Enter your new password for
          <strong><?php echo htmlspecialchars($email); ?></strong>
        </p>

        <form
          method="POST"
          action="/Event-Management-System/auth/form-handlers/resetPasswordHandler.php"
          id="resetForm"
        >
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

          <div class="login-form-group">
            <label for="password">New Password:</label>
            <div class="password-input-wrapper">
              <input
                type="password"
                name="password"
                id="password"
                placeholder="Enter new password"
                required
                minlength="8"
              />
              <button type="button" class="password-toggle-btn" id="togglePassword">
                <i class="bi bi-eye" id="toggleIcon"></i>
              </button>
            </div>
          </div>

          <div class="login-form-group">
            <label for="confirm_password">Confirm Password:</label>
            <div class="password-input-wrapper">
              <input
                type="password"
                name="confirm_password"
                id="confirm_password"
                placeholder="Confirm new password"
                required
                minlength="8"
              />
              <button type="button" class="password-toggle-btn" id="toggleConfirmPassword">
                <i class="bi bi-eye" id="toggleConfirmIcon"></i>
              </button>
            </div>
            <span class="password-hint">Password must be at least 8 characters</span>
          </div>

          <div class="login-form-group">
            <input type="submit" value="Reset Password" />
          </div>
        </form>

      <?php else: ?>

        <div class="invalid-token-panel">
          <i class="bi bi-exclamation-triangle-fill"></i>
          Invalid or expired reset link. Please request a new one.
        </div>
        <a href="login.php" class="back-btn">Back to Login</a>

      <?php endif; ?>
    </div>

    <?php include '../includes/modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      // Toggle new password visibility
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput  = document.getElementById('password');
      const toggleIcon     = document.getElementById('toggleIcon');

      if (togglePassword) {
        togglePassword.addEventListener('click', function () {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          toggleIcon.classList.toggle('bi-eye', type === 'password');
          toggleIcon.classList.toggle('bi-eye-slash', type === 'text');
        });
      }

      // Toggle confirm password visibility
      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      const confirmPasswordInput  = document.getElementById('confirm_password');
      const toggleConfirmIcon     = document.getElementById('toggleConfirmIcon');

      if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function () {
          const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          confirmPasswordInput.setAttribute('type', type);
          toggleConfirmIcon.classList.toggle('bi-eye', type === 'password');
          toggleConfirmIcon.classList.toggle('bi-eye-slash', type === 'text');
        });
      }

      // Confirm passwords match before submit
      const resetForm = document.getElementById('resetForm');
      if (resetForm) {
        resetForm.addEventListener('submit', function (e) {
          if (passwordInput.value !== confirmPasswordInput.value) {
            e.preventDefault();
            alert('Passwords do not match!');
          }
        });
      }
    </script>
  </body>
</html>