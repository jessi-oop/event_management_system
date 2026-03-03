<?php

session_start();
require_once __DIR__ . '/../app/Services/UserService/getUserById.php';
require_once __DIR__ . '/../app/Services/AuthService/requireLogin.php';

$require_login = new RequireLogin();
$require_login->requireLogin();

$get_user_by_id = new GetUserByIdService();
$user = $get_user_by_id->getUserById($_SESSION['user_id']);

if (!$user) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Error Password Update',
        'message' => 'Failed to update password. User does not exists.'
    ];
    header('Location: /Event-Management-System/dashboard.php');
    exit;
}

// Check for flash messages from session and store for modal
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile - EMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../assets/css/user-profile.css" />
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/modal.php'; ?>

    <div class="main-content">
        <div class="content-wrapper w-100">
            
            <!-- Page Header -->
            <div class="page-header">
                <h2 class="page-title">My Profile</h2>
                <p class="page-subtitle">View and manage your account information</p>
            </div>

            <!-- Removed inline alerts - now using modal system -->

            <div class="row g-4">
                <!-- Profile Info Card -->
                <div class="col-lg-8">
                    <div class="profile-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-person"></i> Personal Information
                            </h3>
                            <button type="button" class="btn-edit" onclick="toggleEditMode()">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        </div>

                        <div class="card-body">
                            <!-- View Mode -->
                            <div id="viewMode">
                                <div class="info-row">
                                    <span class="info-label">Full Name</span>
                                    <span class="info-value"><?php echo htmlspecialchars($user->full_name); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email</span>
                                    <span class="info-value"><?php echo htmlspecialchars($user->email); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Role</span>
                                    <span class="info-value">
                                        <span class="role-badge role-<?php echo strtolower($user->role); ?>">
                                            <?php echo ucfirst($user->role); ?>
                                        </span>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Member Since</span>
                                    <span class="info-value">
                                        <?php echo date('F d, Y', strtotime($user->created_at ?? 'now')); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Edit Mode -->
                            <form id="editMode" class="d-none" method="POST" action="/Event-Management-System/auth/form-handlers/updateUserProfileHandler.php">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" 
                                           value="<?php echo htmlspecialchars($user->full_name); ?>" required />
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($user->email); ?>" required />
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="col-lg-4">
                    <div class="profile-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-shield-lock"></i> Security
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Keep your account secure by updating your password regularly.</p>
                            <button type="button" class="btn btn-outline-primary w-100" onclick="showPasswordModal()">
                                <i class="bi bi-key"></i> Change Password
                            </button>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="profile-card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-info-circle"></i> Account Info
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="info-row small">
                                <span class="info-label">User ID</span>
                                <span class="info-value text-muted">#<?php echo $user->user_id; ?></span>
                            </div>
                            <div class="info-row small">
                                <span class="info-label">Status</span>
                                <span class="info-value">
                                    <span class="status-badge active">Active</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Password Change Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/Event-Management-System/auth/form-handlers/updatePasswordHandler.php" id="passwordForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required />
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                   minlength="8" required />
                            <small class="form-text text-muted">Minimum 8 characters</small>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEditMode() {
            document.getElementById('viewMode').classList.toggle('d-none');
            document.getElementById('editMode').classList.toggle('d-none');
        }

        function showPasswordModal() {
            new bootstrap.Modal(document.getElementById('passwordModal')).show();
        }

        // Password match validation
        document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('New passwords do not match!');
            }
        });

        // Auto-show flash modal if message exists
        <?php if ($flash): ?>
        document.addEventListener('DOMContentLoaded', function() {
            // Assuming your modal.php defines a function like showFlashModal(type, title, message)
            // or you can trigger it via Bootstrap modal
            <?php if (isset($flash['type']) && isset($flash['title']) && isset($flash['message'])): ?>
            if (typeof showFlashModal === 'function') {
                showModal(
                    '<?php echo htmlspecialchars($flash['type']); ?>',
                    '<?php echo htmlspecialchars($flash['title']); ?>',
                    '<?php echo htmlspecialchars($flash['message']); ?>'
                );
            } else {
                // Fallback: create and show modal dynamically
                const flashHtml = `
                    <div class="modal fade" id="flashModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'info'); ?> text-white">
                                    <h5 class="modal-title"><?php echo htmlspecialchars($flash['title']); ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <?php echo htmlspecialchars($flash['message']); ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', flashHtml);
                new bootstrap.Modal(document.getElementById('flashModal')).show();
            }
            <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>