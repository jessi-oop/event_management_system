<?php

require_once __DIR__ . '/../app/Services/AuthService/requireRole.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ .'/../app/Services/UserService/getUserById.php';

$require_role = new RequireRole();
$get_user_by_id = new GetUserByIdService();
$require_role->requireRole(['admin']);

// Get user_id from URL
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Invalid User',
        'message' => 'User ID is required.'
    ];
    header('Location: /Event-Management-System/admin/users.php');
    exit;
}


try {

    $user = $get_user_by_id->getUserById($user_id);

    if (!$user) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'title' => 'User Not Found',
            'message' => 'The requested user does not exist.'
        ];
        header('Location: /Event-Management-System/admin/users.php');
        exit;
    }
} catch (PDOException $e) {
    error_log('Error fetching user: ' . $e->getMessage());
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Database Error',
        'message' => 'Failed to retrieve user information.'
    ];
    header('Location: /Event-Management-System/admin/users.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit User - EMS</title>
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
    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="/Event-Management-System/assets/css/create.css" />

    <!-- Bootstrap JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'?>
    <?php include '../includes/modal.php'?>

    <!-- Main Content -->
    <div class="main-content">
      <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
          <h2 class="page-title">Edit User</h2>
          <p class="page-subtitle">Update user information</p>
        </div>

        <!-- User Edit Form -->
        <div class="form-container">
          <form action="/Event-Management-System/admin/form-handlers/editUserHandler.php" 
          method="POST" id="editUserForm">
            
            <!-- Hidden User ID -->
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user->user_id) ?>" />

            <!-- Full Name -->
            <div class="mb-4">
              <label for="full_name" class="form-label">Full Name</label>
              <input 
                type="text" 
                class="form-control" 
                id="full_name" 
                name="full_name" 
                value="<?= htmlspecialchars($user->full_name) ?>"
                placeholder="Enter full name"
                required
              />
            </div>

            <!-- Email -->
            <div class="mb-4">
              <label for="email" class="form-label">Email Address</label>
              <input 
                type="email" 
                class="form-control" 
                id="email" 
                name="email" 
                value="<?= htmlspecialchars($user->email) ?>"
                placeholder="Enter email address"
                required
              />
            </div>

            <!-- Role (Display Only) -->
            <div class="mb-4">
              <label for="role" class="form-label">Role</label>
              <input 
                type="text" 
                class="form-control" 
                id="role" 
                value="<?= htmlspecialchars(ucfirst($user->role)) ?>"
                disabled
                readonly
              />
              <small class="form-text text-muted mt-2">
                <i class="bi bi-info-circle"></i> User roles cannot be changed from this page.
              </small>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                Cancel
              </button>
              <button type="submit" class="btn btn-primary">
                Update User
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          showModal(
            '<?= $_SESSION['flash']['type'] ?>',
            '<?= $_SESSION['flash']['title'] ?>',
            '<?= $_SESSION['flash']['message'] ?>'
          );
        });
      </script>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
  </body>
</html>