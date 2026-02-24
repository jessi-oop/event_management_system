<?php session_start();?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <!-- Bootstrap CDN -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/css/register.css" />
  </head>
  <body class="register-body">
    <div class="register-container">
      <h2>Create Account</h2>

      <form method="POST" action="/Event-Management-System/auth/form-handlers/registrationHandler.php">
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
            type="email"
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
      
    </div>

    <!-- Include reusable modal -->
    <?php include '../includes/modal.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>