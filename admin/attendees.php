<?php
session_start();

// Load repositories
require_once __DIR__ . '/../app/Services/EventService/getEventById.php';
require_once __DIR__ . '/../app/Services/AuthService/requireRole.php';
require_once __DIR__ . '/../app/Services/RegistrationService/getEventAttendees.php';

$require_role = new RequireRole();
$get_event_by_id = new GetEventByIdRepo();
$get_event_attendees = new GetEventAttendeesRepo();

$require_role->requireRole(['admin']);

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

// Get event ID
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$event = $get_event_by_id->getEventById($event_id);
$attendees = $get_event_attendees->getEventAttendees($event_id);

if (!$event_id) {
    header('Location: /Event-Management-System/admin/events.php');
    exit;
}

if (!$event) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'title' => 'Event Not Found',
        'message' => 'The requested event does not exist.'
    ];
    header('Location: /Event-Management-System/admin/events.php');
    exit;
}

// Calculate stats
$total_attendees = count($attendees);
$available_slots = $event->capacity - $event->registered_count;
$fill_percentage = ($event->registered_count / $event->capacity) * 100;
$formatted_date = date('l, F d, Y', strtotime($event->event_date));
$formatted_time = date('g:i A', strtotime($event->event_time));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Attendees - Admin</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../assets/css/attendees.css" />
</head>
<body>
    <div class="container-fluid">
        
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-wrapper w-100">
                
                <!-- Back Button -->
                <div class="back-nav mb-3">
                    <a href="events.php" class="back-link">
                        <i class="bi bi-arrow-left"></i> Back to Events Management
                    </a>
                </div>

                <!-- Event Info Card -->
                <div class="event-info-card mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="event-header">
                                <span class="category-badge"><?php echo htmlspecialchars($event->category_name); ?></span>
                                <h2 class="event-title"><?php echo htmlspecialchars($event->title); ?></h2>
                            </div>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <i class="bi bi-calendar3"></i>
                                    <span><?php echo $formatted_date; ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <span><?php echo $formatted_time; ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span><?php echo htmlspecialchars($event->location); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-person"></i>
                                    <span>Organized by <?php echo htmlspecialchars($event->organizer_name); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="capacity-info">
                                <div class="capacity-header">
                                    <span class="capacity-label">Registration Status</span>
                                    <span class="capacity-percentage"><?php echo number_format($fill_percentage, 1); ?>%</span>
                                </div>
                                <div class="progress mb-2">
                                    <div class="progress-bar" style="width: <?php echo $fill_percentage; ?>%"></div>
                                </div>
                                <div class="capacity-text">
                                    <strong><?php echo $event->registered_count; ?></strong> / <?php echo $event->capacity; ?> registered
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon total-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Attendees</div>
                                <div class="stat-value"><?php echo $total_attendees; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon available-icon">
                                <i class="bi bi-ticket"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Available Slots</div>
                                <div class="stat-value"><?php echo $available_slots; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon capacity-icon">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Capacity</div>
                                <div class="stat-value"><?php echo $event->capacity; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Bar -->
                <div class="actions-bar mb-4">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control search-input" placeholder="Search by name or email..." id="searchInput" />
                    </div>
                </div>

                <!-- Attendees Table -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table attendees-table" id="attendeesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Registration Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
foreach ($attendees as $attendee):
    $reg_date = date('M d, Y', strtotime($attendee['registered_at']));
    $reg_time = date('g:i A', strtotime($attendee['registered_at']));
    ?>
                                <tr>
                                    <td class="counter-cell"><?php echo $counter++; ?></td>
                                    
                                    <td class="name-cell">
                                        <div class="attendee-avatar">
                                            <?php echo strtoupper(substr($attendee['full_name'], 0, 1)); ?>
                                        </div>
                                        <span><?php echo htmlspecialchars($attendee['full_name']); ?></span>
                                    </td>
                                    
                                    <td class="email-cell">
                                        <a href="mailto:<?php echo htmlspecialchars($attendee['email']); ?>">
                                            <?php echo htmlspecialchars($attendee['email']); ?>
                                        </a>
                                    </td>
                                    
                                    <td class="date-cell">
                                        <div class="date-info">
                                            <div class="date-text"><?php echo $reg_date; ?></div>
                                            <div class="time-text"><?php echo $reg_time; ?></div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="status-badge status-confirmed">
                                            <i class="bi bi-check-circle"></i> Confirmed
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-action btn-remove" title="Remove Attendee"
                                                data-event-id="<?php echo $event_id; ?>"
                                                data-user-id="<?php echo $attendee['user_id']; ?>"
                                                data-attendee-name="<?php echo htmlspecialchars($attendee['full_name']); ?>"
                                                onclick="confirmRemove(this.dataset.eventId, this.dataset.userId, this.dataset.attendeeName)">
                                                <i class="bi bi-x-circle"></i>
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
                        <h4>No Attendees Found</h4>
                        <p>No attendees match your search criteria.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Remove attendee confirmation
        function confirmRemove(eventId, userId, attendeeName) {
            showModal('confirm', 'Remove Attendee', 
                'Are you sure you want to remove "' + attendeeName + '" from this event? This will cancel their registration and they will need to register again.', {
                confirmText: 'Yes, Remove',
                cancelText: 'Cancel',
                onConfirm: function() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/Event-Management-System/events/form-handlers/removeAttendeeHandler.php';
                    
                    const eventInput = document.createElement('input');
                    eventInput.type = 'hidden';
                    eventInput.name = 'event_id';
                    eventInput.value = eventId;
                    form.appendChild(eventInput);
                    
                    const userInput = document.createElement('input');
                    userInput.type = 'hidden';
                    userInput.name = 'user_id';
                    userInput.value = userId;
                    form.appendChild(userInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Send email
        function sendEmail(email) {
            window.location.href = 'mailto:' + email;
        }

        // Export attendees
        function exportAttendees(format) {
            const eventId = <?php echo $event_id; ?>;
            window.location.href = 'export_attendees.php?event_id=' + eventId + '&format=' + format;
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#attendeesTable tbody tr');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const name = row.querySelector('.name-cell').textContent.toLowerCase();
                const email = row.querySelector('.email-cell').textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'flex' : 'none';
            document.querySelector('.table-responsive').style.display = visibleCount === 0 ? 'none' : 'block';
        });
    </script>

    <!-- Include Modal -->
    <?php include '../includes/modal.php'; ?>

</body>
</html>