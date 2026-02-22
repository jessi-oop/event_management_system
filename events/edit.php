<?php
require_once __DIR__ . '/../app/Services/AuthService/requireRole.php';
require_once __DIR__ . '/../app/Services/EventService/updateEvent.php';
require_once __DIR__ . '/../app/Services/EventService/getEventById.php';
require_once __DIR__ . '/../app/Services/CategoryService/getAllCategories.php';

$require_role = new RequireRole();
$get_event_by_id = new GetEventByIdService();
$update_event = new UpdateEventService();
$get_ll_categories = new GetAllCategoriesService();

$require_role->requireRole(['organizer', 'admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id'] ?? 0);
} else {
    $event_id = intval($_GET['event_id'] ?? 0);
}

if (!$event_id) {
    header('Location: /Event-Management-System/organizer/manage.php');
}

$event = $get_event_by_id->getEventById($event_id);
$categories = $get_ll_categories->getAllCategories();

if (!$event) {
    header('Location: /browse.php');
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);

    $updated_event = $update_event->updateEvent(
        $event_id,
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity
    );


    $message = $updated_event['message'];

    if ($updated_event['success']) {
        $event = $get_event_by_id->getEventById($event_id);
    }
    echo '<pre>';
    var_dump($message);
    echo '</pre>';
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Event - EMS</title>
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
    <link rel="stylesheet" href="../assets/css/create.css" />
    <link rel="stylesheet" href="../assets/css/edit.css" />
  </head>
  <body>

        <!-- Sidebar -->
        <?php include '../includes/sidebar.php' ?>

        <!-- Main Content -->
        <div class="main-content">
          <div class="content-wrapper w-100">
            
            <!-- Back Button -->
            <div class="back-nav mb-3">
              <a href="details.php?event_id=<?php echo $event_id; ?>" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Event Details
              </a>
            </div>

            <!-- Page Header -->
            <div class="page-header">
              <h2 class="page-title">Edit Event</h2>
              <p class="page-subtitle">Update the event details below</p>
            </div>

            <!-- Event Edit Form -->
            <div class="form-container">
              <form action="edit.php" method="POST" id="editEventForm">
                
                <!-- Hidden field for event ID -->
                <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id)?>" />
                
                <!-- Event Title -->
                <div class="mb-4">
                  <label for="title" class="form-label">Event Title</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="title" 
                    name="title" 
                    placeholder="Enter event title"
                    value="<?php echo htmlspecialchars($event->title); ?>"
                    required
                  />
                </div>

                <!-- Category Dropdown -->
                <div class="mb-4">
                  <label for="category_id" class="form-label">Event Category</label>
                  <select class="form-select" id="category_id" name="category_id" required>
                    <option value="" disabled>Select a category</option>
                    <!-- PHP: Loop through categories from database -->
                    <?php

                    foreach ($categories as $category) {
                        $selected = ($category->category_id == $event->category_id) ? 'selected' : '';
                        echo "<option value='{$category->category_id}' {$selected}>{$category->category_name}</option>";
                    }
?>
                  </select>
                </div>

                <!-- Description -->
                <div class="mb-4">
                  <label for="description" class="form-label">Description</label>
                  <textarea 
                    class="form-control" 
                    id="description" 
                    name="description" 
                    rows="5"
                    placeholder="Enter event description"
                    required
                  ><?php echo htmlspecialchars($event->description); ?></textarea>
                </div>

                <!-- Date and Time Row -->
                <div class="row mb-4">
                  <!-- Event Date -->
                  <div class="col-md-6 mb-3 mb-md-0">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input 
                      type="date" 
                      class="form-control" 
                      id="event_date" 
                      name="event_date"
                      value="<?php echo $event->event_date; ?>"
                      required
                    />
                  </div>

                  <!-- Event Time -->
                  <div class="col-md-6">
                    <label for="event_time" class="form-label">Event Time</label>
                    <input 
                      type="time" 
                      class="form-control" 
                      id="event_time" 
                      name="event_time"
                      value="<?php echo $event->event_time; ?>"
                      required
                    />
                  </div>
                </div>

                <!-- Location -->
                <div class="mb-4">
                  <label for="location" class="form-label">Location</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="location" 
                    name="location" 
                    placeholder="Enter event location"
                    value="<?php echo htmlspecialchars($event->location); ?>"
                    required
                  />
                </div>

                <!-- Capacity -->
                <div class="mb-4">
                  <label for="capacity" class="form-label">Capacity</label>
                  <input 
                    type="number" 
                    class="form-control" 
                    id="capacity" 
                    name="capacity" 
                    placeholder="Enter maximum capacity"
                    value="<?php echo $event->capacity; ?>"
                    min="1"
                    required
                  />
                  <small class="form-text text-muted">
                    <i class="bi bi-info-circle"></i> Be careful when reducing capacity if attendees are already registered.
                  </small>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                  <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    Cancel
                  </button>
                  <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="bi bi-trash"></i> Delete Event
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Event
                  </button>
                </div>

              </form>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p>Are you sure you want to delete this event? This action cannot be undone.</p>
                    <p class="text-danger"><strong>Warning:</strong> All registrations associated with this event will also be deleted.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="/Event-Management-System/events/delete.php" method="POST" style="display: inline;">
                      <input type="hidden" name="event_id" value="<?php echo $event->event_id; ?>" />
                      <button type="submit" class="btn btn-danger">Delete Event</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form validation and delete confirmation script -->
    <script>
      // Set minimum date to today for new dates
      document.getElementById('event_date').min = new Date().toISOString().split('T')[0];
      
      // Delete confirmation
      function confirmDelete() {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
      }
      
      // Optional: Warn when reducing capacity
      const capacityInput = document.getElementById('capacity');
      const originalCapacity = <?php echo $event->capacity; ?>;
      
      capacityInput.addEventListener('change', function() {
        if (parseInt(this.value) < originalCapacity) {
          if (!confirm('Warning: You are reducing the event capacity. Make sure this doesn\'t exceed the current number of registrations.')) {
            this.value = originalCapacity;
          }
        }
      });
    </script>
  </body>
</html>