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
    <link rel="stylesheet" href="/../assets/css/create.css" />
    <link rel="stylesheet" href="/../assets/css/edit.css" />
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-12 col-md-3 col-lg-2 sidebar">
          <h4 class="sidebar-title">EMS</h4>
          <!-- Add your navigation menu here -->
        </div>

        <!-- Main Content -->
        <div class="col-12 col-md-9 col-lg-10 main-content d-flex justify-content-center">
          <div class="content-wrapper w-100">
            
            <?php
            // Get event ID from URL
            $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

            // Check if user is authorized (organizer or admin)
            // if (!is_authorized_user()) {
            //   header('Location: browse.php');
            //   exit();
            // }

            // Fetch event from database
            // $event = fetch_event_by_id($event_id);

            // Sample event data - replace with database fetch
            $event = [
              'id' => 1,
              'category_id' => 1,
              'title' => 'Annual Tech Conference 2024',
              'description' => 'Join us for the biggest technology conference of the year. This premier event brings together industry leaders, innovators, and technology enthusiasts from around the world.',
              'event_date' => '2024-03-15',
              'event_time' => '09:00',
              'location' => 'Convention Center, New York',
              'capacity' => 500
            ];

            // If event not found, redirect
            // if (!$event) {
            //   header('Location: browse.php');
            //   exit();
            // }
            ?>

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
              <form action="process_edit.php" method="POST" id="editEventForm">
                
                <!-- Hidden field for event ID -->
                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>" />
                
                <!-- Event Title -->
                <div class="mb-4">
                  <label for="title" class="form-label">Event Title</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="title" 
                    name="title" 
                    placeholder="Enter event title"
                    value="<?php echo htmlspecialchars($event['title']); ?>"
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
                    // Example categories - replace with database fetch
                    $categories = [
                      ['id' => 1, 'name' => 'Conference'],
                      ['id' => 2, 'name' => 'Workshop'],
                      ['id' => 3, 'name' => 'Seminar'],
                      ['id' => 4, 'name' => 'Webinar'],
                      ['id' => 5, 'name' => 'Networking'],
                      ['id' => 6, 'name' => 'Training']
                    ];

            foreach ($categories as $category) {
                $selected = ($category['id'] == $event['category_id']) ? 'selected' : '';
                echo "<option value='{$category['id']}' {$selected}>{$category['name']}</option>";
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
                  ><?php echo htmlspecialchars($event['description']); ?></textarea>
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
                      value="<?php echo $event['event_date']; ?>"
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
                      value="<?php echo $event['event_time']; ?>"
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
                    value="<?php echo htmlspecialchars($event['location']); ?>"
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
                    value="<?php echo $event['capacity']; ?>"
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
                    <form action="process_delete.php" method="POST" style="display: inline;">
                      <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>" />
                      <button type="submit" class="btn btn-danger">Delete Event</button>
                    </form>
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
      const originalCapacity = <?php echo $event['capacity']; ?>;
      
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