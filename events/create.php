<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header: 'Location: /Event-Management-System/auth/login.php';
    exit;
}

require_once __DIR__ . '/../app/Services/EventService/validateEventDetails.php';
require_once __DIR__ . '/../app/Entities/User.php';
require_once __DIR__ . '/../app/Services/EventService/createEvent.php';
require_once __DIR__ . '/../app/Services/CategoryService/getAllCategories.php';


// if ()

$validate_event_details = new ValidateEventDetails();
$create_event = new CreateEventService();
$user = new User();
$category_service = new GetAllCategoriesService();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);


    $event_created = $create_event->createEvent(
        $category_id,
        $title,
        $description,
        $event_date,
        $event_time,
        $location,
        $capacity
    );

    echo "<pre>";
    echo "Event created result: ";
    var_dump($event_created);
    echo "</pre>";

    $message = $event_created['message'];
}

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
    <!-- External CSS -->
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
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-12 col-md-3 col-lg-2 sidebar">
          <h4 class="sidebar-title">EMS</h4>
          <!-- Add your navigation menu here -->
        </div>

        <!-- Main Content -->
        <div class="col-12 col-md-9 col-lg-10 main-content d-flex justify-content-center">
          <div class="content-wrapper">
            <!-- Page Header -->
            <div class="page-header">
              <h2 class="page-title">Create New Event</h2>
              <p class="page-subtitle">Fill in the details below to create a new event</p>
            </div>

            <!-- Event Creation Form -->
            <div class="form-container">
              <form action="create.php" method="POST" id="createEventForm">
                
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
      </div>
    </div>

    
  </body>
</html>