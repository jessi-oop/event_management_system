<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /Event-Management-System/auth/login.php');
    exit;
}

// Load repositories
require_once __DIR__ . '/../app/Services/EventService/getAllEvents.php';

// Get all events
$get_all_events = new GetAllEventsService();
$events = $get_all_events->getAllEvents();

// Sample data structure (replace with your actual repo data)
// $events = [
//     ['event_id' => 1, 'event_title' => 'Tech Conference', 'organizer_name' => 'John Doe', 'event_date' => '2024-03-15', 'event_time' => '09:00', 'capacity' => 500, 'registered_count' => 342, 'location' => 'NYC'],
// ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Events - Admin</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../assets/css/admin-events.css" />
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
                            <h2 class="page-title">Event Management</h2>
                            <p class="page-subtitle">View and manage all events in the system</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-3 mb-4">
                    <?php
                    $total_events = count($events);
$upcoming_events = count(array_filter($events, fn ($e) => strtotime($e->event_date) >= strtotime('today')));
$past_events = $total_events - $upcoming_events;
$total_registrations = array_sum(array_column($events, 'registered_count'));
?>
                    
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon total-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Events</div>
                                <div class="stat-value"><?php echo $total_events; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon upcoming-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Upcoming</div>
                                <div class="stat-value"><?php echo $upcoming_events; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon past-icon">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Past Events</div>
                                <div class="stat-value"><?php echo $past_events; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon registrations-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Registrations</div>
                                <div class="stat-value"><?php echo $total_registrations; ?></div>
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
                                <input type="text" class="form-control search-input" placeholder="Search by title, organizer, or location..." id="searchInput" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Events</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="past">Past</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table events-table" id="eventsTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Organizer</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Capacity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($events as $event):
                                    $event_date = date('M d, Y', strtotime($event->event_date));
                                    $event_time = date('g:i A', strtotime($event->event_time));
                                    $is_past = strtotime($event->event_date) < strtotime('today');
                                    $fill_percentage = ($event->registered_count / $event->capacity) * 100;
                                    ?>
                                <tr data-status="<?php echo $is_past ? 'past' : 'upcoming'; ?>">
                                    <td class="title-cell">
                                        <a href="../events/details.php?event_id=<?php echo $event->event_id; ?>">
                                            <?php echo htmlspecialchars($event->title); ?>
                                        </a>
                                    </td>
                                    
                                    <td class="organizer-cell">
                                        <?php echo htmlspecialchars($event->organizer_name); ?>
                                    </td>
                                    
                                    <td class="date-cell"><?php echo $event_date; ?></td>
                                    
                                    <td class="time-cell"><?php echo $event_time; ?></td>
                                    
                                    <td class="location-cell">
                                        <i class="bi bi-geo-alt"></i>
                                        <?php echo htmlspecialchars($event->location); ?>
                                    </td>
                                    
                                    <td class="capacity-cell">
                                        <div class="capacity-bar-wrap">
                                            <div class="capacity-numbers">
                                                <span class="registered"><?php echo $event->registered_count; ?></span> / 
                                                <span class="total"><?php echo $event->capacity; ?></span>
                                            </div>
                                            <div class="capacity-bar">
                                                <div class="capacity-fill" style="width: <?php echo $fill_percentage; ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="action-btns">
                                            <button class="btn-action btn-view" title="View Details"
                                                onclick="window.location.href='../events/details.php?event_id=<?php echo $event->event_id; ?>'">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            <button class="btn-action btn-attendees" title="View Attendees"
                                                onclick="window.location.href='attendees.php?event_id=<?php echo $event->event_id; ?>'">
                                                <i class="bi bi-people"></i>
                                            </button>
                                            
                                            <button class="btn-action btn-edit" title="Edit Event"
                                                onclick="window.location.href='../events/edit.php?event_id=<?php echo $event->event_id; ?>'">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                            <button class="btn-action btn-delete" title="Delete Event"
                                                data-event-id="<?php echo $event->event_id; ?>"
                                                data-event-title="<?php echo htmlspecialchars($event->title); ?>"
                                                onclick="deleteEvent(this.dataset)">
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
                        <h4>No Events Found</h4>
                        <p>No events match your search criteria.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Delete Event
        function deleteEvent(data) {
            showModal('confirm', 'Delete Event', 
                `Are you sure you want to delete "${data.eventTitle}"? This action cannot be undone and will delete all registrations for this event.`, {
                confirmText: 'Yes, Delete',
                cancelText: 'Cancel',
                onConfirm: function() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/Event-Management-System/events/form-handlers/deleteEventHandler.php';
                    
                    const eventIdInput = document.createElement('input');
                    eventIdInput.type = 'hidden';
                    eventIdInput.name = 'event_id';
                    eventIdInput.value = data.eventId;
                    form.appendChild(eventIdInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Search and Filter
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const tableRows = document.querySelectorAll('#eventsTable tbody tr');
        const noResults = document.getElementById('noResults');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const statusValue = statusFilter.value;
            let visibleCount = 0;

            tableRows.forEach(row => {
                const title = row.querySelector('.title-cell').textContent.toLowerCase();
                const organizer = row.querySelector('.organizer-cell').textContent.toLowerCase();
                const location = row.querySelector('.location-cell').textContent.toLowerCase();
                const status = row.getAttribute('data-status');

                const matchesSearch = title.includes(searchTerm) || organizer.includes(searchTerm) || location.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;

                if (matchesSearch && matchesStatus) {
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
        statusFilter.addEventListener('change', filterTable);
    </script>

    <!-- Include Modal -->
    <?php include '../includes/modal.php'; ?>

</body>
</html>