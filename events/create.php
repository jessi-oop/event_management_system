<?php

require_once __DIR__ . '/../app/Services/CategoryService/getAllCategories.php';

$category_service = new GetAllCategoriesService();
$categories = $category_service->getAllCategories();

if (!$categories) {
    $message = "Failed to retrieve categories.";
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Event - EMS</title>
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
    <link rel="stylesheet" href="/Event-Management-System/assets/css/create.css" />

    <!-- Bootstrap JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Form validation script -->
    <script defer>
      // Set minimum date to today
      document.getElementById('event_date').min = new Date().toISOString().split('T')[0];
    </script>
  </head>
  <body>
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'?>

        <!-- Main Content -->
        <div class="main-content">
          <div class="content-wrapper">
            <!-- Page Header -->
            <div class="page-header">
              <h2 class="page-title">Create New Event</h2>
              <p class="page-subtitle">Fill in the details below to create a new event</p>
            </div>

            <!-- Event Creation Form -->
            <div class="form-container">
              <form action="/Event-Management-System/events/form-handlers/createEventHandler.php" method="POST" id="createEventForm">
                
                <!-- Event Title -->
                <div class="mb-4">
                  <label for="title" class="form-label">Event Title</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="title" 
                    name="title" 
                    placeholder="Enter event title"
                    required
                  />
                </div>

                <!-- Category Dropdown -->
                <div class="mb-4">
                  <label for="category_id" class="form-label">Event Category</label>
                  <select class="form-select" id="category_id" name="category_id" required>
                    <option value="" selected disabled>Select a category</option>
                    <!-- PHP: Loop through categories from database -->
                    <?php if (empty($categories)): ?>
                      <option value="">No categories available</option>
                    <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?= htmlspecialchars($category->category_id)?>">
                        <?= htmlspecialchars($category->category_name)?>
                      </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
              
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
                  ></textarea>
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
                    min="1"
                    required
                  />
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                  <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    Cancel
                  </button>
                  <button type="submit" class="btn btn-primary">
                    Create Event
                  </button>
                </div>

              </form>
            </div>
          </div>
        </div>

      <?php if (!empty($modal)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
              showModal(
                '<?= $modal['type']?>'
                '<?= $modal['title']?>'
                '<?= $modal['message']?>'
              )
            })
        </script>
      <?php endif; ?>
      
  </body>
</html>