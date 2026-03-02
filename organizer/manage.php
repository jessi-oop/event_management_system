<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../app/Services/AuthService/requireRole.php';
require_once __DIR__ . '/../app/Services/EventService/getEventByOrganizer.php';
require_once __DIR__ . '/../app/Services/CategoryService/getAllCategories.php';
require_once __DIR__ . '/../app/Services/ApprovalService/getRejectionReason.php';

$get_event_by_organizer = new GetEventByOrganizerService();
$get_all_categories = new GetAllCategoriesService();
$get_rejection_reason = new GetRejectionReasonService();
$require_role = new RequireRole();

$require_role->requireRole(['organizer', 'admin']);

$organizer_id = $_SESSION['user_id'];
$organized_events = $get_event_by_organizer->getEventByOrganizer($organizer_id);

$categories = $get_all_categories->getAllCategories();

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'all';
$event_id = $_GET['event_id'] ?? '';
$category_id = $_GET['category_id'] ?? '';

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Events - EMS</title>

   
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
    <link rel="stylesheet" href="../assets/css/manage.css" />
  </head>
  <body>    
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'?>
        <?php include '../includes/modal.php' ?>
        <!-- CLOSE: sidebar col -->

        <!-- Main Content -->
         <div class="main-content"> <!-- OPEN: main-content col -->
          <div class="content-wrapper w-100"> <!-- OPEN: content-wrapper -->
            
            <?php
            // Calculate statistics
            $total_events = count($organized_events);
$upcoming_events = count(array_filter($organized_events, fn ($e) => $e->status === 'upcoming'));
$total_registrations = array_sum(array_map(fn ($e) =>  $e->registered_count, $organized_events));
?>

            <!-- Page Header -->
            <div class="page-header"> <!-- OPEN: page-header -->
              <div class="header-content"> <!-- OPEN: header-content -->
                <div class="header-text"> <!-- OPEN: header-text -->
                  <h2 class="page-title">My Events</h2>
                  <p class="page-subtitle">Manage your created events</p>
                </div> <!-- CLOSE: header-text -->
                <div class="header-actions"> <!-- OPEN: header-actions -->
                  <a href="/Event-Management-System/events/create.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New Event
                  </a>
                </div> <!-- CLOSE: header-actions -->
              </div> <!-- CLOSE: header-content -->
            </div> <!-- CLOSE: page-header -->

            <!-- Statistics Cards -->
            <div class="row g-3 mb-4"> <!-- OPEN: stats row -->
              
              <div class="col-md-4"> <!-- OPEN: stat col 1 -->
                <div class="stat-card"> <!-- OPEN: stat-card -->
                  <div class="stat-icon total-icon"> <!-- OPEN+CLOSE: stat-icon -->
                    <i class="bi bi-calendar-event"></i>
                  </div>
                  <div class="stat-content"> <!-- OPEN: stat-content -->
                    <div class="stat-label">Total Events</div>
                    <div class="stat-value"><?php echo $total_events; ?></div>
                  </div> <!-- CLOSE: stat-content -->
                </div> <!-- CLOSE: stat-card -->
              </div> <!-- CLOSE: stat col 1 -->

              <div class="col-md-4"> <!-- OPEN: stat col 2 -->
                <div class="stat-card"> <!-- OPEN: stat-card -->
                  <div class="stat-icon upcoming-icon"> <!-- OPEN+CLOSE: stat-icon -->
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div class="stat-content"> <!-- OPEN: stat-content -->
                    <div class="stat-label">Upcoming Events</div>
                    <div class="stat-value"><?php echo $upcoming_events; ?></div>
                  </div> <!-- CLOSE: stat-content -->
                </div> <!-- CLOSE: stat-card -->
              </div> <!-- CLOSE: stat col 2 -->

              <div class="col-md-4"> <!-- OPEN: stat col 3 -->
                <div class="stat-card"> <!-- OPEN: stat-card -->
                  <div class="stat-icon registrations-icon"> <!-- OPEN+CLOSE: stat-icon -->
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="stat-content"> <!-- OPEN: stat-content -->
                    <div class="stat-label">Total Registrations</div>
                    <div class="stat-value"><?php echo $total_registrations; ?></div>
                  </div> <!-- CLOSE: stat-content -->
                </div> <!-- CLOSE: stat-card -->
              </div> <!-- CLOSE: stat col 3 -->

            </div> <!-- CLOSE: stats row -->

            <!-- Search and Filter -->
            <div class="filter-section mb-4"> <!-- OPEN: filter-section -->
              <div class="row g-3"> <!-- OPEN: filter row -->
                <div class="col-md-6"> <!-- OPEN: search col -->
                  <div class="search-box"> <!-- OPEN: search-box -->
                    <i class="bi bi-search"></i>
                    <input
                      type="text"
                      class="form-control search-input"
                      placeholder="Search events by title or location..."
                      id="searchInput"
                    />
                  </div> <!-- CLOSE: search-box -->
                </div> <!-- CLOSE: search col -->
                <div class="col-md-3"> <!-- OPEN: category col -->
                  <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <?php if (empty($categories)): ?>
                      <option value="">No categories available.</option>
                    <?php else: ?>
                      <?php foreach ($categories as $category): ?>
                      <option value="<?= htmlspecialchars($category->category_id)?>">
                        <?= htmlspecialchars($category->category_name)?>
                      </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div> <!-- CLOSE: category col -->
                <div class="col-md-3"> <!-- OPEN: status col -->
                  <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="full">Full</option>
                    <option value="past">Past</option>
                  </select>
                </div> <!-- CLOSE: status col -->
              </div> <!-- CLOSE: filter row -->
            </div> <!-- CLOSE: filter-section -->

            <!-- Events Table -->
            <div class="table-container"> <!-- OPEN: table-container -->
              <div class="table-responsive"> <!-- OPEN: table-responsive -->
                <table class="table events-table" id="eventsTable"> <!-- OPEN: table -->
                  <thead>
                    <tr>
                      <th>Event Title</th>
                      <th>Date & Time</th>
                      <th>Category</th>
                      <th>Location</th>
                      <th>Capacity</th>
                      <th>Approval Status</th>  <!-- NEW COLUMN -->
                      <th>Event Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody> <!-- OPEN: tbody -->
                    
                    <?php foreach ($organized_events as $event):
                        // Format date and time
                        $formatted_date = date('M d, Y', strtotime($event->event_date));
                        $formatted_time = date('g:i A', strtotime($event->event_time));

                        // Determine approval status badge
                        $approval_class = '';
                        $approval_text = '';
                        $approval_icon = '';
                        switch ($event->approval_status) {
                            case 'pending':
                                $approval_class = 'approval-pending';
                                $approval_text = 'Pending';
                                $approval_icon = 'bi-hourglass-split';
                                break;
                            case 'approved':
                                $approval_class = 'approval-approved';
                                $approval_text = 'Approved';
                                $approval_icon = 'bi-check-circle';
                                break;
                            case 'rejected':
                                $approval_class = 'approval-rejected';
                                $approval_text = 'Rejected';
                                $approval_icon = 'bi-x-circle';
                                break;
                        }

                        // Determine event status badge
                        $status_class = '';
                        $status_text = '';
                        switch ($event->status) {
                            case 'upcoming':
                                $status_class = 'status-upcoming';
                                $status_text = 'Upcoming';
                                break;
                            case 'full':
                                $status_class = 'status-full';
                                $status_text = 'Full';
                                break;
                            case 'past':
                                $status_class = 'status-past';
                                $status_text = 'Past';
                                break;
                        }

                        // Calculate fill percentage
                        $fill_percentage = ($event->registered_count / $event->capacity) * 100;
                        ?>
                    
                    <tr data-category="<?php echo $event->category_id; ?>" 
                        data-status="<?php echo $event->status; ?>" 
                        data-approval="<?php echo $event->approval_status; ?>"> <!-- OPEN: event row -->
                      
                      <!-- Event Title -->
                      <td class="event-title-cell"> <!-- OPEN+CLOSE: td -->
                        <a href="/Event-Management-System/events/details.php?event_id=<?php echo $event->event_id; ?>" class="event-link">
                          <?php echo $event->title; ?>
                        </a>
                      </td>
                      
                      <!-- Date & Time -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <div class="datetime-cell">
                          <div class="date-text">
                            <i class="bi bi-calendar3"></i>
                            <?php echo $formatted_date; ?>
                          </div>
                          <div class="time-text">
                            <i class="bi bi-clock"></i>
                            <?php echo $formatted_time; ?>
                          </div>
                        </div>
                      </td>
                      
                      <!-- Category -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <span class="category-badge"><?php echo $event->category_name; ?></span>
                      </td>
                      
                      <!-- Location -->
                      <td class="location-cell"> <!-- OPEN+CLOSE: td -->
                        <i class="bi bi-geo-alt"></i>
                        <?php echo $event->location; ?>
                      </td>
                      
                      <!-- Capacity -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <div class="capacity-cell">
                          <div class="capacity-text">
                            <strong><?php echo $event->registered_count; ?></strong> / <?php echo $event->capacity; ?>
                          </div>
                          <div class="capacity-bar">
                            <div class="capacity-fill" style="width: <?php echo $fill_percentage; ?>%"></div>
                          </div>
                        </div>
                      </td>
                      
                      <!-- Approval Status (NEW COLUMN) -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <span class="status-badge <?php echo $approval_class; ?>">
                          <i class="bi <?php echo $approval_icon; ?>"></i>
                          <?php echo $approval_text; ?>
                          
                          <?php if ($event->approval_status === 'rejected'):
                              // Get rejection reason
                              $rejection_reason = $get_rejection_reason->getRejectionReason($event->event_id);
                              // Truncate for tooltip preview
                              $truncated_reason = strlen($rejection_reason) > 100
                                  ? substr($rejection_reason, 0, 100) . '...'
                                  : $rejection_reason;
                              ?>
                              <!-- Info icon with truncated tooltip and click to show full modal -->
                              <i class="bi bi-info-circle-fill ms-1 rejection-info-icon" 
                                 data-bs-toggle="tooltip" 
                                 data-bs-html="true"
                                 data-bs-placement="right"
                                 title="<strong>Rejection Reason:</strong><br><?php echo htmlspecialchars($truncated_reason); ?><?php echo strlen($rejection_reason) > 100 ? '<br><small><em>Click icon for full reason</em></small>' : ''; ?>"
                                 onclick="showFullRejectionReason('<?php echo htmlspecialchars($event->title, ENT_QUOTES); ?>', '<?php echo htmlspecialchars($rejection_reason, ENT_QUOTES); ?>')"
                                 style="cursor: pointer; font-size: 0.85rem;"></i>
                          <?php endif; ?>
                        </span>
                      </td>
                      
                      <!-- Event Status -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <span class="status-badge <?php echo $status_class; ?>">
                          <?php echo $status_text; ?>
                        </span>
                      </td>
                      
                      <!-- Actions -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <div class="action-buttons">
                          <a href="/Event-Management-System/events/details.php?event_id=<?php echo $event->event_id; ?>" 
                             class="btn-action btn-view" 
                             title="View Details">
                            <i class="bi bi-eye"></i>
                          </a>
                          <button class="btn-action btn-attendees" title="View Attendees"
                                  onclick="window.location.href='attendees.php?event_id=<?php echo $event->event_id; ?>'">
                            <i class="bi bi-people"></i>
                          </button>
                          
                          <?php if ($event->approval_status === 'pending'): ?>
                            <!-- Disabled buttons for pending events -->
                            <button class="btn-action btn-edit" 
                                    title="Cannot edit while pending approval"
                                    disabled
                                    style="opacity: 0.5; cursor: not-allowed;">
                              <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-action btn-delete" 
                                    title="Cannot delete while pending approval"
                                    disabled
                                    style="opacity: 0.5; cursor: not-allowed;">
                              <i class="bi bi-trash"></i>
                            </button>
                          <?php else: ?>
                            <!-- Normal buttons for approved/rejected events -->
                            <a href="/Event-Management-System/events/edit.php?event_id=<?php echo $event->event_id; ?>" 
                               class="btn-action btn-edit" 
                               title="Edit Event">
                              <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn-action btn-delete" 
                                    title="Delete Event"
                                    onclick="confirmDelete(<?php echo $event->event_id; ?>, '<?php echo addslashes($event->title); ?>')">
                              <i class="bi bi-trash"></i>
                            </button>
                          <?php endif; ?>
                        </div>
                      </td>
                      
                    </tr> <!-- CLOSE: event row -->
                    
                    <?php endforeach; ?>

                  </tbody> <!-- CLOSE: tbody -->
                </table> <!-- CLOSE: table -->
              </div> <!-- CLOSE: table-responsive -->

              <!-- No Results Message -->
              <div class="no-results" id="noResults" style="display: none;"> <!-- OPEN+CLOSE: no-results -->
                <i class="bi bi-inbox"></i>
                <h4>No Events Found</h4>
                <p>No events match your search criteria.</p>
              </div>

            </div> <!-- CLOSE: table-container -->

          </div> <!-- CLOSE: content-wrapper -->
        </div> <!-- CLOSE: main-content col -->

        <!-- Rejection Reason Modal -->
        <div class="modal fade" id="rejectionReasonModal" tabindex="-1" aria-labelledby="rejectionReasonModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectionReasonModalLabel">
                            <i class="bi bi-x-circle me-2"></i>Rejection Reason
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3">
                            Event: <strong id="rejectedEventTitle"></strong>
                        </h6>
                        <div class="alert alert-danger mb-0">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4 flex-shrink-0"></i>
                                <div>
                                    <strong>Admin's Feedback:</strong>
                                    <p class="mb-0 mt-2" id="fullRejectionReason" style="white-space: pre-wrap; word-wrap: break-word; line-height: 1.6;"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Please address these concerns and resubmit your event.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
      function confirmDelete(eventId, eventTitle) {
        showModal('confirm', 'Delete Event', 'Are you sure you want to delete "' + eventTitle + '"? This cannot be undone. All registrations will also be deleted.', {
        confirmText: 'Yes, Delete',
        cancelText: 'Cancel',
        onConfirm: function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/Event-Management-System/events/form-handlers/deleteEventHandler.php';
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
      // Search functionality
      const searchInput = document.getElementById('searchInput');
      const categoryFilter = document.getElementById('categoryFilter');
      const statusFilter = document.getElementById('statusFilter');
      const tableRows = document.querySelectorAll('#eventsTable tbody tr');
      const noResults = document.getElementById('noResults');
      
      function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const categoryValue = categoryFilter.value;
        const statusValue = statusFilter.value;
        let visibleCount = 0;
        
        tableRows.forEach(row => {
          const title = row.querySelector('.event-title-cell').textContent.toLowerCase();
          const location = row.querySelector('.location-cell').textContent.toLowerCase();
          const category = row.getAttribute('data-category') || '';
          const status = row.getAttribute('data-status') || '';
          
          const matchesSearch = title.includes(searchTerm) || location.includes(searchTerm);
          const matchesCategory = !categoryValue || category === categoryValue;
          const matchesStatus = !statusValue || status === statusValue;
          
          if (matchesSearch && matchesCategory && matchesStatus) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });
        
        // Show/hide no results message
        noResults.style.display = visibleCount === 0 ? 'flex' : 'none';
      }

      // Initialize Bootstrap tooltips for rejection reasons
      document.addEventListener('DOMContentLoaded', function() {
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
          var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl, {
                  html: true,
                  boundary: 'window',
                  customClass: 'rejection-tooltip'
              });
          });
      });

      function showFullRejectionReason(eventTitle, fullReason) {
          // Set the event title
          document.getElementById('rejectedEventTitle').textContent = eventTitle;
          
          // Set the full rejection reason
          document.getElementById('fullRejectionReason').textContent = fullReason;
          
          // Hide any open tooltips first
          var tooltips = document.querySelectorAll('.tooltip');
          tooltips.forEach(function(tooltip) {
              tooltip.remove();
          });
          
          // Show the modal
          var modal = new bootstrap.Modal(document.getElementById('rejectionReasonModal'));
          modal.show();
      }

      // Initialize Bootstrap tooltips
      document.addEventListener('DOMContentLoaded', function() {
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
          var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl, {
                  html: true,
                  boundary: 'window',
                  customClass: 'rejection-tooltip'
              });
          });
      });
      
      searchInput.addEventListener('input', filterTable);
      categoryFilter.addEventListener('change', filterTable);
      statusFilter.addEventListener('change', filterTable);
    </script>
  </body>
</html>