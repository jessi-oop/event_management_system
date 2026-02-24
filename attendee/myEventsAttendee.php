<?php
require_once __DIR__ . '/../app/Services/AuthService/requireLogin.php';
require_once __DIR__ . '/../app/Services/RegistrationService/getUserRegistrations.php';

$require_login = new RequireLogin();
$get_user_registrations = new GetUserRegistrationsService();


$require_login->requireLogin();

$attendee_id = $_SESSION['user_id'];

$user_registrations = $get_user_registrations->getUserRegistrations($attendee_id);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Registered Events - EMS</title>
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
    <link rel="stylesheet" href="../assets/css/events-attendee.css" />
  </head>
  <body>
    <div class="container-fluid">
      
      <!-- Sidebar -->
      <?php include '../includes/sidebar.php'; ?>
      <?php include '../includes/modal.php' ?>

      <!-- Main Content -->
      <div class="main-content">
        <div class="content-wrapper w-100">
          
          <?php
          // Calculate statistics
          $today = date('Y-m-d');
$total_registered = count($user_registrations);
$upcoming_events = count(array_filter($user_registrations, fn ($e) => $e->event_date >= $today));
$past_events = count(array_filter($user_registrations, fn ($e) => $e->event_date <= $today));
?>

          <!-- Page Header -->
          <div class="page-header">
            <div class="header-content">
              <div class="header-text">
                <h2 class="page-title">My Registered Events</h2>
                <p class="page-subtitle">View and manage your event registrations</p>
              </div>
              <div class="header-actions">
                <a href="/Event-Management-System/events/browse.php" class="btn btn-primary">
                  <i class="bi bi-search"></i> Browse More Events
                </a>
              </div>
            </div>
          </div>

          <!-- Statistics Cards -->
          <div class="row g-3 mb-4">
            
            <div class="col-md-4">
              <div class="stat-card">
                <div class="stat-icon total-icon">
                  <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-content">
                  <div class="stat-label">Total Registered</div>
                  <div class="stat-value"><?php echo $total_registered; ?></div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="stat-card">
                <div class="stat-icon upcoming-icon">
                  <i class="bi bi-calendar-event"></i>
                </div>
                <div class="stat-content">
                  <div class="stat-label">Upcoming Events</div>
                  <div class="stat-value"><?php echo $upcoming_events; ?></div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
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

          </div>

          <!-- Search and Filter -->
          <div class="filter-section mb-4">
            <div class="row g-3">
              <div class="col-md-8">
                <div class="search-box">
                  <i class="bi bi-search"></i>
                  <input
                    type="text"
                    class="form-control search-input"
                    placeholder="Search events by title or location..."
                    id="searchInput"
                  />
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

          <!-- Events Grid -->
          <div class="row g-4" id="eventsGrid">
            
            <?php foreach ($user_registrations as $event):
                // Format date and time
                $formatted_date = date('M d, Y', strtotime($event->event_date));
                $formatted_time = date('g:i A', strtotime($event->event_time));
                $registration_date = date('M d, Y', strtotime($event->registered_at));

                // Determine status
                $status_class = '';
                $status_text = '';
                $status_icon = '';

                if ($event->status === 'upcoming') {
                    $status_class = 'status-upcoming';
                    $status_text = 'Upcoming';
                    $status_icon = 'bi-calendar-check';
                } else {
                    $status_class = 'status-past';
                    $status_text = 'Completed';
                    $status_icon = 'bi-check-circle';
                }
                ?>

            <!-- Event Card -->
            <div class="col-12 col-md-6 col-xl-4" data-status="<?php echo $event->status; ?>">
              <div class="event-card">
                
                <!-- Card Header -->
                <div class="card-header-section">
                  <span class="category-badge"><?php echo $event->category_name; ?></span>
                  <span class="status-badge <?php echo $status_class; ?>">
                    <i class="bi <?php echo $status_icon; ?>"></i>
                    <?php echo $status_text; ?>
                  </span>
                </div>

                <!-- Event Title -->
                <h5 class="event-title">
                  <a href="/Event-Management-System/events/details.php?event_id=<?php echo $event->event_id; ?>">
                    <?php echo $event->event_title; ?>
                  </a>
                </h5>

                <!-- Event Details -->
                <div class="event-details">
                  
                  <div class="detail-item">
                    <i class="bi bi-calendar3"></i>
                    <span><?php echo $formatted_date; ?></span>
                  </div>

                  <div class="detail-item">
                    <i class="bi bi-clock"></i>
                    <span><?php echo $formatted_time; ?></span>
                  </div>

                  <div class="detail-item">
                    <i class="bi bi-geo-alt"></i>
                    <span><?php echo $event->location; ?></span>
                  </div>

                </div>

                <!-- Registration Info -->
                <div class="registration-info">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Registered on <?php echo $registration_date; ?></span>
                </div>

                <!-- Card Actions -->
                <div class="card-actions">
                  <a href="/Event-Management-System/events/details.php?event_id=<?php echo $event->event_id; ?>" class="btn btn-view">
                    <i class="bi bi-eye"></i> View Details
                  </a>
                  
                  <?php if ($event->status === 'upcoming'): ?>
                  <button 
                      class="btn btn-cancel" 
                      data-event-id="<?php echo $event->event_id; ?>"
                      data-event-title="<?php echo htmlspecialchars($event->event_title); ?>"
                      onclick="confirmCancel(this.dataset.eventId, this.dataset.eventTitle)">
                      <i class="bi bi-x-circle"></i> Cancel Registration
                  </button>
                  <?php endif; ?>
                </div>

              </div>
            </div>

            <?php endforeach; ?>

          </div>

          <!-- No Events Message -->
          <div class="no-events" id="noEvents" style="display: none;">
            <i class="bi bi-calendar-x"></i>
            <h4>No Events Found</h4>
            <p>You haven't registered for any events yet.</p>
            <a href="/Event-Management-System/events/browse.php" class="btn btn-primary mt-3">
              <i class="bi bi-search"></i> Browse Events
            </a>
          </div>

        </div>
      </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
      // Cancel registration confirmation
      function confirmCancel(eventId, eventTitle) {
        showModal('confirm', 'Cancel Registration', 'Are you sure you want to cancel registration to "' + eventTitle, {
        confirmText: 'Yes, Cancel',
        cancelText: 'Cancel',
        onConfirm: function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/Event-Management-System/events/form-handlers/cancelRegistrationHandler.php';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'event_id';
            input.value = eventId;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
  }      
      
      // Search and filter functionality
      const searchInput = document.getElementById('searchInput');
      const statusFilter = document.getElementById('statusFilter');
      const eventCards = document.querySelectorAll('#eventsGrid > div[data-status]');
      const noEvents = document.getElementById('noEvents');
      
      function filterEvents() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        let visibleCount = 0;
        
        eventCards.forEach(card => {
          const title = card.querySelector('.event-title').textContent.toLowerCase();
          const location = card.querySelector('.event-details .detail-item:nth-child(3)').textContent.toLowerCase();
          const status = card.getAttribute('data-status');
          
          const matchesSearch = title.includes(searchTerm) || location.includes(searchTerm);
          const matchesStatus = !statusValue || status === statusValue;
          
          if (matchesSearch && matchesStatus) {
            card.style.display = '';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });
        
        // Show/hide no events message
        noEvents.style.display = visibleCount === 0 ? 'flex' : 'none';
        document.getElementById('eventsGrid').style.display = visibleCount === 0 ? 'none' : 'flex';
      }
      
      searchInput.addEventListener('input', filterEvents);
      statusFilter.addEventListener('change', filterEvents);
    </script>
  </body>
</html>