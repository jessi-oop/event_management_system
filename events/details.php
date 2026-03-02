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
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css "
      rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css "
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
        <div class="main-content">
          <div class="content-wrapper w-100">
            
            <?php
            // Calculate available slots
            $available_slots = $event->available_spots;
$fill_percentage = ($event->registered_count / $event->capacity) * 100;

// Format date and time
$formatted_date = date('l, F d, Y', strtotime($event->event_date));
$formatted_time = date('g:i A', strtotime($event->event_time));

// Check if event is full
$is_full = $available_slots <= 0;

// Check if image exists
$has_image = !empty($event->image_path) && file_exists(__DIR__ . '/../' . $event->image_path);
$image_url = $has_image ? '/Event-Management-System/' . htmlspecialchars($event->image_path) : '';
?>

            <!-- Back Button -->
            <div class="back-nav mb-3">
              <a href="browse.php" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Events
              </a>
            </div>

            <!-- Event Details Container -->
            <div class="details-container">
              
              <!-- Event Header -->
              <div class="event-header">
                <div class="header-top">
                  <span class="category-badge"><?php echo $event->category_name; ?></span>
                  <?php if ($is_full): ?>
                    <span class="status-badge full">Event Full</span>
                  <?php elseif ($available_slots <= 20): ?>
                    <span class="status-badge limited">Limited Slots</span>
                  <?php endif; ?>
                </div>
                <h1 class="event-title"><?php echo $event->title; ?></h1>
              </div>

              <!-- Event Info Grid -->
              <div class="row g-4 mb-4">
                
                <!-- Date Card -->
                <div class="col-md-6 col-lg-3">
                  <div class="info-card">
                    <div class="info-icon date-icon">
                      <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Date</div>
                      <div class="info-value"><?php echo $formatted_date; ?></div>
                    </div>
                  </div>
                </div>

                <!-- Time Card -->
                <div class="col-md-6 col-lg-3">
                  <div class="info-card">
                    <div class="info-icon time-icon">
                      <i class="bi bi-clock"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Time</div>
                      <div class="info-value"><?php echo $formatted_time; ?></div>
                    </div>
                  </div>
                </div>

                <!-- Location Card (Icon Only) -->
                <div class="col-md-6 col-lg-3">
                  <div class="info-card">
                    <div class="info-icon location-icon">
                      <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Location</div>
                      <div class="info-value"><?php echo $event->location; ?></div>
                    </div>
                  </div>
                </div>

                <!-- Capacity Card -->
                <div class="col-md-6 col-lg-3">
                  <div class="info-card">
                    <div class="info-icon capacity-icon">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="info-content">
                      <div class="info-label">Availability</div>
                      <div class="info-value"><?php echo $available_slots; ?> / <?php echo $event->capacity; ?></div>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Location Section with Image -->
              <div class="location-section mb-4">
                <h3 class="section-title">Venue Location</h3>
                <div class="location-card-large">
                  <?php if ($has_image): ?>
                    <div class="location-image-large">
                      <img 
                        src="<?php echo $image_url; ?>" 
                        alt="<?php echo htmlspecialchars($event->location); ?>"
                      />
                      <div class="location-overlay">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span><?php echo $event->location; ?></span>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="location-placeholder">
                      <i class="bi bi-geo-alt"></i>
                      <span><?php echo $event->location; ?></span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Registration Progress Bar -->
              <div class="registration-progress mb-4">
                <div class="progress-header">
                  <span class="progress-label">Registration Status</span>
                  <span class="progress-percentage"><?php echo number_format($fill_percentage, 1); ?>% Full</span>
                </div>
                <div class="progress">
                  <div 
                    class="progress-bar <?php echo $fill_percentage >= 90 ? 'bg-danger' : ($fill_percentage >= 70 ? 'bg-warning' : 'bg-primary'); ?>" 
                    role="progressbar" 
                    style="width: <?php echo $fill_percentage; ?>%"
                    aria-valuenow="<?php echo $fill_percentage; ?>" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
                  </div>
                </div>
              </div>

              <!-- Description Section -->
              <div class="description-section">
                <h3 class="section-title">About This Event</h3>
                <div class="description-content">
                  <?php echo nl2br($event->description); ?>
                </div>
              </div>

              <!-- Organizer Info -->
              <div class="organizer-section">
                <h3 class="section-title">Organizer Information</h3>
                <div class="organizer-content">
                  <div class="organizer-item">
                    <i class="bi bi-building"></i>
                    <span><?php echo $event->organizer_name; ?></span>
                  </div>
                  <div class="organizer-item">
                    <i class="bi bi-envelope"></i>
                    <a href="mailto:<?php echo $event->organizer_email; ?>"><?php echo $event->organizer_email; ?></a>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="action-section"> 
                <?php if ($can_edit): ?>
                  <a href="/Event-Management-System/events/edit.php?event_id=<?php echo $event->event_id; ?>" class="btn btn-warning">
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
              </div>

            </div>

          </div>
        </div>
        
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js "></script>

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
              form.action = '/Event-Management-System/events/form-handlers/registerToEventHandler.php';
              
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