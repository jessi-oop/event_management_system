<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

// Load repositories
require_once __DIR__ . '/../app/Services/UserService/getAllUsers.php';

// Get all users
$get_all_users = new GetAllUsersService();
$users = $get_all_users->getAllUsers();

// Sample data structure (replace with your actual repo data)
// $users = [
//     ['user_id' => 1, 'full_name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'attendee', 'created_at' => '2024-01-15 10:30:00', 'is_banned' => 0],
// ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Users - Admin</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../assets/css/admin-users.css" />
</head>
<body>
    <div class="container-fluid">
        
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-wrapper w-100">
                
                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <div class="header-text">
                            <h2 class="page-title">User Management</h2>
                            <p class="page-subtitle">View and manage all registered users</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-3 mb-4">
                    <?php
                    $total_users = count($users);
$admins = count(array_filter($users, fn ($u) => $u->role === 'admin'));
$organizers = count(array_filter($users, fn ($u) => $u->role === 'organizer'));
$attendees = count(array_filter($users, fn ($u) => $u->role === 'attendee'));
?>
                    
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon total-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Users</div>
                                <div class="stat-value"><?php echo $total_users; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon admin-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Admins</div>
                                <div class="stat-value"><?php echo $admins; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon organizer-icon">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Organizers</div>
                                <div class="stat-value"><?php echo $organizers; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon attendee-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Attendees</div>
                                <div class="stat-value"><?php echo $attendees; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="filter-section mb-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" class="form-control search-input" placeholder="Search by name or email..." id="searchInput" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="roleFilter">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="organizer">Organizer</option>
                                <option value="attendee">Attendee</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table users-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user):
                                    $joined_date = date('M d, Y', strtotime($user->created_at));
                                    ?>
                                <tr data-role="<?php echo $user->role; ?>">
                                    <td class="id-cell"><?php echo $user->user_id; ?></td>
                                    
                                    <td class="name-cell">
                                        <div class="user-avatar">
                                            <?php echo strtoupper(substr($user->full_name, 0, 1)); ?>
                                        </div>
                                        <span><?php echo htmlspecialchars($user->full_name); ?></span>
                                    </td>
                                    
                                    <td class="email-cell">
                                        <a href="mailto:<?php echo htmlspecialchars($user->email); ?>">
                                            <?php echo htmlspecialchars($user->email); ?>
                                        </a>
                                    </td>
                                    
                                    <td>
                                        <span class="role-badge role-<?php echo $user->role; ?>">
                                            <i class="bi bi-<?php echo $user->role === 'admin' ? 'shield-check' : ($user->role === 'organizer' ? 'briefcase' : 'person'); ?>"></i>
                                            <?php echo ucfirst($user->role); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="date-cell"><?php echo $joined_date; ?></td>
                                    
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-action btn-edit" title="Edit User" 
                                                data-user-id="<?php echo $user->user_id; ?>"
                                                data-user-name="<?php echo htmlspecialchars($user->full_name); ?>"
                                                data-user-email="<?php echo htmlspecialchars($user->email); ?>"
                                                data-user-role="<?php echo $user->role; ?>"
                                                onclick="editUser(this.dataset)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                            <button class="btn-action btn-delete" title="Delete User"
                                                data-user-id="<?php echo $user->user_id; ?>"
                                                data-user-name="<?php echo htmlspecialchars($user->full_name); ?>"
                                                onclick="deleteUser(this.dataset)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- No Results -->
                    <div class="no-results" id="noResults" style="display: none;">
                        <i class="bi bi-search"></i>
                        <h4>No Users Found</h4>
                        <p>No users match your search criteria.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Edit User
        function editUser(data) {
            // You can create an edit modal or redirect to edit page
            window.location.href = 'editUser.php?user_id=' + data.userId;
        }

        // Delete User
        function deleteUser(data) {
            showModal('confirm', 'Delete User', 
                `Are you sure you want to delete "${data.userName}"? This action cannot be undone and will delete all their events and registrations.`, {
                confirmText: 'Yes, Delete',
                cancelText: 'Cancel',
                onConfirm: function() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/Event-Management-System/admin/form-handlers/deleteUser.php';
                    
                    const userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.name = 'user_id';
                    userIdInput.value = data.userId;
                    form.appendChild(userIdInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Search and Filter
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const tableRows = document.querySelectorAll('#usersTable tbody tr');
        const noResults = document.getElementById('noResults');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const roleValue = roleFilter.value;
            let visibleCount = 0;

            tableRows.forEach(row => {
                const name = row.querySelector('.name-cell').textContent.toLowerCase();
                const email = row.querySelector('.email-cell').textContent.toLowerCase();
                const role = row.getAttribute('data-role');

                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = !roleValue || role === roleValue;

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'flex' : 'none';
            document.querySelector('.table-responsive').style.display = visibleCount === 0 ? 'none' : 'block';
        }

        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
    </script>

    <!-- Include Modal -->
    <?php include '../includes/modal.php'; ?>

</body>
</html>