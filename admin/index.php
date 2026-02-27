<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

// Load repositories
require_once __DIR__ . '/../app/Services/UserService/getAllUsers.php';
require_once __DIR__ . '/../app/Services/EventService/getAllEvents.php';
require_once __DIR__ . '/../app/Repositories/ApprovalRepository/getPendingApprovals.php';
require_once __DIR__ . '/../app/Repositories/ActivityRepository/getRecentActivity.php';
require_once __DIR__ . '/../app/Repositories/StatisticsRepository/getEventsByMonth.php';

// Get statistics
$users_repo = new GetAllUsersRepo();
$events_repo = new GetAllEventsRepo();
$approvals_repo = new GetPendingApprovalsRepo();
$activity_repo = new GetRecentActivityRepo();
$stats_repo = new GetEventsByMonthRepo();

$all_users = $users_repo->getAllUsers();
$all_events = $events_repo->getAllEvents();
$pending_approvals = $approvals_repo->getPendingApprovals();
$recent_activity = $activity_repo->getRecentActivity(10); // Last 10 activities
$events_by_month = $stats_repo->getEventsByMonth(6); // Last 6 months

// Calculate statistics
$total_users = count($all_users);
$total_events = count($all_events);
$total_registrations = array_sum(array_column($all_events, 'registered_count'));
$pending_count = count($pending_approvals);

// Sample data for chart (replace with actual data)
// Format: ['month' => 'Jan', 'count' => 5]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - EMS</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css" />
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
                            <h2 class="page-title">Admin Dashboard</h2>
                            <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! Here's what's happening today.</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="stat-card clickable" onclick="window.location.href='users.php'">
                            <div class="stat-icon users-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Users</div>
                                <div class="stat-value"><?php echo $total_users; ?></div>
                                <div class="stat-change">
                                    <i class="bi bi-arrow-up"></i> View All
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card clickable" onclick="window.location.href='events.php'">
                            <div class="stat-icon events-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Events</div>
                                <div class="stat-value"><?php echo $total_events; ?></div>
                                <div class="stat-change">
                                    <i class="bi bi-arrow-up"></i> Manage
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon registrations-icon">
                                <i class="bi bi-ticket"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Registrations</div>
                                <div class="stat-value"><?php echo $total_registrations; ?></div>
                                <div class="stat-change">
                                    <i class="bi bi-graph-up"></i> All Time
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card <?php echo $pending_count > 0 ? 'clickable pulse' : ''; ?>" 
                             onclick="<?php echo $pending_count > 0 ? "window.location.href='approvals.php'" : ''; ?>">
                            <div class="stat-icon pending-icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Pending Approvals</div>
                                <div class="stat-value"><?php echo $pending_count; ?></div>
                                <div class="stat-change">
                                    <?php if ($pending_count > 0): ?>
                                        <i class="bi bi-exclamation-circle"></i> Needs Review
                                    <?php else: ?>
                                        <i class="bi bi-check-circle"></i> All Clear
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals Alert (if any) -->
                <?php if ($pending_count > 0): ?>
                <div class="alert-banner mb-4">
                    <div class="alert-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <h5>Action Required</h5>
                        <p>You have <strong><?php echo $pending_count; ?></strong> event<?php echo $pending_count > 1 ? 's' : ''; ?> waiting for approval.</p>
                    </div>
                    <button class="btn btn-primary" onclick="window.location.href='approvals.php'">
                        Review Now <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Main Content Grid -->
                <div class="row g-4 mb-4">
                    
                    <!-- Events Chart -->
                    <div class="col-lg-8">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="bi bi-graph-up"></i> Events Created Per Month
                                </h5>
                                <span class="card-subtitle">Last 6 months</span>
                            </div>
                            <div class="card-body">
                                <canvas id="eventsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-4">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="bi bi-lightning"></i> Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="quick-links">
                                    <a href="users.php" class="quick-link">
                                        <div class="quick-link-icon" style="background: #EFF6FF; color: #2563EB;">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div class="quick-link-title">Manage Users</div>
                                            <div class="quick-link-desc">View and edit users</div>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>

                                    <a href="events.php" class="quick-link">
                                        <div class="quick-link-icon" style="background: #F0FDF4; color: #22C55E;">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div class="quick-link-title">Manage Events</div>
                                            <div class="quick-link-desc">View and delete events</div>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>

                                    <a href="approvals.php" class="quick-link">
                                        <div class="quick-link-icon" style="background: #FEF3C7; color: #F59E0B;">
                                            <i class="bi bi-clipboard-check"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div class="quick-link-title">Event Approvals</div>
                                            <div class="quick-link-desc"><?php echo $pending_count; ?> pending</div>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>

                                    <a href="../events/browse.php" class="quick-link">
                                        <div class="quick-link-icon" style="background: #E0E7FF; color: #4F46E5;">
                                            <i class="bi bi-eye"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div class="quick-link-title">View Public Site</div>
                                            <div class="quick-link-desc">See user experience</div>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Recent Activity -->
                <div class="row g-4">
                    <div class="col-12">
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="bi bi-activity"></i> Recent Activity
                                </h5>
                                <span class="card-subtitle">Latest system activities</span>
                            </div>
                            <div class="card-body">
                                <div class="activity-timeline">
                                    <?php if (empty($recent_activity)): ?>
                                        <div class="no-activity">
                                            <i class="bi bi-inbox"></i>
                                            <p>No recent activity</p>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($recent_activity as $activity):
                                            $time_ago = time_elapsed_string($activity['created_at']);
                                            ?>
                                        <div class="activity-item">
                                            <div class="activity-icon <?php echo $activity['type']; ?>">
                                                <i class="bi bi-<?php echo get_activity_icon($activity['type']); ?>"></i>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-text"><?php echo htmlspecialchars($activity['description']); ?></div>
                                                <div class="activity-meta">
                                                    <span class="activity-time"><?php echo $time_ago; ?></span>
                                                    <?php if (isset($activity['user_name'])): ?>
                                                        <span class="activity-separator">•</span>
                                                        <span class="activity-user"><?php echo htmlspecialchars($activity['user_name']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart Script -->
    <script>
        // Events per month chart
        const ctx = document.getElementById('eventsChart').getContext('2d');
        
        // Data from PHP
        const chartData = <?php echo json_encode($events_by_month); ?>;
        const labels = chartData.map(d => d.month);
        const data = chartData.map(d => d.count);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Events Created',
                    data: data,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2563EB',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#374151',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#E5E7EB'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

    <!-- Include Modal -->
    <?php include '../includes/modal.php'; ?>

</body>
</html>

<?php
// Helper functions
function time_elapsed_string($datetime)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d >= 1) {
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    }
    if ($diff->h >= 1) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    }
    if ($diff->i >= 1) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    }
    return 'Just now';
}

function get_activity_icon($type)
{
    $icons = [
        'user_registered' => 'person-plus',
        'event_created' => 'calendar-plus',
        'event_registered' => 'ticket',
        'event_cancelled' => 'x-circle',
        'event_approved' => 'check-circle',
        'event_rejected' => 'x-octagon'
    ];
    return $icons[$type] ?? 'circle';
}
?>