<?php
session_start();
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
  </head>
  <body class="login-body">
    <div class="login-container">
      <h2>Login</h2>

      <form method="POST" action="/Event-Management-System/auth/form-handlers/loginHandler.php">
        <div class="login-form-group">
          <label for="email">Email:</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter email"
            required
          />
        </div>

        <div class="login-form-group">
          <label for="password">Password:</label>
          <div class="password-input-wrapper">
            <input
              type="password"
              name="password"
              id="password"
              placeholder="Enter password"
              required
            />
            <button type="button" class="password-toggle-btn" id="togglePassword">
              <i class="bi bi-eye" id="toggleIcon"></i>
            </button>
          </div>
          <div class="forgot-password-link">
            <a href="forgot-password.php">Forgot password?</a>
          </div>
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

    <!-- Include reusable modal -->
    <?php include '../includes/modal.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Password Toggle Script -->
    <script>
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');

      togglePassword.addEventListener('click', function() {
        // Toggle password visibility
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        if (type === 'password') {
          toggleIcon.classList.remove('bi-eye-slash');
          toggleIcon.classList.add('bi-eye');
        } else {
          toggleIcon.classList.remove('bi-eye');
          toggleIcon.classList.add('bi-eye-slash');
        }
      });
    </script>
  </body>
</html>