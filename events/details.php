<?php
session_start();
require_once __DIR__ . '/../app/Services/EventService/getEventById.php';
require_once __DIR__ . '/../app/Services/AuthService/requireLogin.php';

$get_event_by_id = new GetEventByIdService();
$require_login = new RequireLogin();

$require_login->requireLogin();

$user_role = $_SESSION['role'] ?? 'guest';
$user_id = $_SESSION['user_id'] ?? 0;

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$event = $get_event_by_id->getEventById($event_id);

$can_edit = false;

if ($user_role === 'admin') {
    $can_edit = true;
} elseif ($user_role === 'organizer') {
    $can_edit = ($event->organizer_id === $user_id);
}

$can_register = in_array($user_role, ['admin', 'organizer', 'attendee']);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Details - EMS</title>
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
    <link rel="stylesheet" href="../assets/css/details.css" />
  </head>
  <body>
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'?>
        <?php include '../includes/modal.php'?>

        <!-- Main Content -->
        <div class="main-content"> <!-- OPEN: main-content col -->
          <div class="content-wrapper w-100"> <!-- OPEN: content-wrapper -->
            
            <?php

            // Calculate available slots
            $available_slots = $event->available_spots;
$fill_percentage = ($event->registered_count / $event->capacity) * 100;

// Format date and time
$formatted_date = date('l, F d, Y', strtotime($event->event_date));
$formatted_time = date('g:i A', strtotime($event->event_time));

// Check if event is full
$is_full = $available_slots <= 0;
?>

            <!-- Back Button -->
            <div class="back-nav mb-3"> <!-- OPEN: back-nav -->
              <a href="browse.php" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Events
              </a>
            </div> <!-- CLOSE: back-nav -->

            <!-- Event Details Container -->
            <div class="details-container"> <!-- OPEN: details-container -->
              
              <!-- Event Header -->
              <div class="event-header"> <!-- OPEN: event-header -->
                <div class="header-top"> <!-- OPEN: header-top -->
                  <span class="category-badge"><?php echo $event->category_name; ?></span>
                  <?php if ($is_full): ?>
                    <span class="status-badge full">Event Full</span>
                  <?php elseif ($available_slots <= 20): ?>
                    <span class="status-badge limited">Limited Slots</span>
                  <?php endif; ?>
                </div> <!-- CLOSE: header-top -->
                <h1 class="event-title"><?php echo $event->title; ?></h1>
              </div> <!-- CLOSE: event-header -->

              <!-- Event Info Grid -->
              <div class="row g-4 mb-4"> <!-- OPEN: row g-4 mb-4 -->
                
                <!-- Date Card -->
                <div class="col-md-6 col-lg-3"> <!-- OPEN: date col -->
                  <div class="info-card"> <!-- OPEN: info-card -->
                    <div class="info-icon date-icon"> <!-- OPEN: info-icon -->
                      <i class="bi bi-calendar-event"></i>
                    </div> <!-- CLOSE: info-icon -->
                    <div class="info-content"> <!-- OPEN: info-content -->
                      <div class="info-label">Date</div>
                      <div class="info-value"><?php echo $formatted_date; ?></div>
                    </div> <!-- CLOSE: info-content -->
                  </div> <!-- CLOSE: info-card -->
                </div> <!-- CLOSE: date col -->

                <!-- Time Card -->
                <div class="col-md-6 col-lg-3"> <!-- OPEN: time col -->
                  <div class="info-card"> <!-- OPEN: info-card -->
                    <div class="info-icon time-icon"> <!-- OPEN: info-icon -->
                      <i class="bi bi-clock"></i>
                    </div> <!-- CLOSE: info-icon -->
                    <div class="info-content"> <!-- OPEN: info-content -->
                      <div class="info-label">Time</div>
                      <div class="info-value"><?php echo $formatted_time; ?></div>
                    </div> <!-- CLOSE: info-content -->
                  </div> <!-- CLOSE: info-card -->
                </div> <!-- CLOSE: time col -->

                <!-- Location Card -->
                <div class="col-md-6 col-lg-3"> <!-- OPEN: location col -->
                  <div class="info-card"> <!-- OPEN: info-card -->
                    <div class="info-icon location-icon"> <!-- OPEN: info-icon -->
                      <i class="bi bi-geo-alt"></i>
                    </div> <!-- CLOSE: info-icon -->
                    <div class="info-content"> <!-- OPEN: info-content -->
                      <div class="info-label">Location</div>
                      <div class="info-value"><?php echo $event->location; ?></div>
                    </div> <!-- CLOSE: info-content -->
                  </div> <!-- CLOSE: info-card -->
                </div> <!-- CLOSE: location col -->

                <!-- Capacity Card -->
                <div class="col-md-6 col-lg-3"> <!-- OPEN: capacity col -->
                  <div class="info-card"> <!-- OPEN: info-card -->
                    <div class="info-icon capacity-icon"> <!-- OPEN: info-icon -->
                      <i class="bi bi-people"></i>
                    </div> <!-- CLOSE: info-icon -->
                    <div class="info-content"> <!-- OPEN: info-content -->
                      <div class="info-label">Availability</div>
                      <div class="info-value"><?php echo $available_slots; ?> / <?php echo $event->capacity; ?></div>
                    </div> <!-- CLOSE: info-content -->
                  </div> <!-- CLOSE: info-card -->
                </div> <!-- CLOSE: capacity col -->

              </div> <!-- CLOSE: row g-4 mb-4 -->

              <!-- Registration Progress Bar -->
              <div class="registration-progress mb-4"> <!-- OPEN: registration-progress -->
                <div class="progress-header"> <!-- OPEN: progress-header -->
                  <span class="progress-label">Registration Status</span>
                  <span class="progress-percentage"><?php echo number_format($fill_percentage, 1); ?>% Full</span>
                </div> <!-- CLOSE: progress-header -->
                <div class="progress"> <!-- OPEN: progress -->
                  <div 
                    class="progress-bar <?php echo $fill_percentage >= 90 ? 'bg-danger' : ($fill_percentage >= 70 ? 'bg-warning' : 'bg-primary'); ?>" 
                    role="progressbar" 
                    style="width: <?php echo $fill_percentage; ?>%"
                    aria-valuenow="<?php echo $fill_percentage; ?>" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
                  </div>
                </div> <!-- CLOSE: progress -->
              </div> <!-- CLOSE: registration-progress -->

              <!-- Description Section -->
              <div class="description-section"> <!-- OPEN: description-section -->
                <h3 class="section-title">About This Event</h3>
                <div class="description-content"> <!-- OPEN: description-content -->
                  <?php echo nl2br($event->description); ?>
                </div> <!-- CLOSE: description-content -->
              </div> <!-- CLOSE: description-section -->

              <!-- Organizer Info -->
              <div class="organizer-section"> <!-- OPEN: organizer-section -->
                <h3 class="section-title">Organizer Information</h3>
                <div class="organizer-content"> <!-- OPEN: organizer-content -->
                  <div class="organizer-item"> <!-- OPEN+CLOSE: organizer-item -->
                    <i class="bi bi-building"></i>
                    <span><?php echo $event->organizer_name; ?></span>
                  </div>
                  <div class="organizer-item"> <!-- OPEN+CLOSE: organizer-item -->
                    <i class="bi bi-envelope"></i>
                    <a href="mailto:<?php echo $event->organizer_email; ?>"><?php echo $event->organizer_email; ?></a>
                  </div>
                </div> <!-- CLOSE: organizer-content -->
              </div> <!-- CLOSE: organizer-section -->

             <!-- Action Buttons  -->
              <!-- Todo: Implement proper auth for authorization and role checking -->
                <div class="action-section"> 
                <!-- For organizers/admins only - Always show -->
                 <?php if ($can_edit): ?>
                <a href="/Event-Management-System/events/form-handlers/editEventHandler.php?event_id=<?php echo $event->event_id; ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Event
                </a>
                <?php endif; ?>

                <?php if ($can_register): ?>
                  <?php if ($is_full): ?>
                    <button class="btn btn-full" disabled>
                      <i class="bi bi-x-circle"></i> Event Full
                    </button>
                    <a href="browse.php" class="btn btn-secondary">
                      Browse Other Events
                    </a>
                  <?php else: ?>
                    <button class="btn btn-success" onclick="confirmRegistration(<?php echo $event_id; ?>, '<?php echo addslashes($event->title); ?>')">
                        <i class="bi bi-calendar-check"></i> Register Now
                    </button>
                  <?php endif; ?>
                <?php endif; ?>
              </div> <!-- CLOSE: action-section -->

            </div> <!-- CLOSE: details-container -->

          </div> <!-- CLOSE: content-wrapper -->
        </div> <!-- CLOSE: main-content col -->
        
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
     
    function confirmRegistration(eventId, eventTitle) {
      showModal(
          'confirm',
          'Confirm Registration',
          'Do you want to register for "' + eventTitle + '"?',
          {
              confirmText: 'Yes, Register',
              cancelText: 'Cancel',
              onConfirm: function() {
                  const form = document.createElement('form');
                  form.method = 'POST';
                  form.action = '/Event-Management-System/events/form-handlers/registerEventHandler.php';
                  
                  const input = document.createElement('input');
                  input.type = 'hidden';
                  input.name = 'event_id';
                  input.value = eventId;
                  
                  form.appendChild(input);
                  document.body.appendChild(form);
                  form.submit();
              }
          }
      );
    }

    </script>
  </body>
</html>