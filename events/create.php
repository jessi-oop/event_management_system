<?php

require_once __DIR__ . '/../app/Services/CategoryService/getAllCategories.php';
require_once __DIR__ . '/../app/Services/AuthService/requireRole.php';

$category_service = new GetAllCategoriesService();
$require_role = new RequireRole();

$require_role->requireRole(['organizer', 'admin']);
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
    <link rel="stylesheet" href="/Event-Management-System/assets/css/create.css" />

    <!-- Bootstrap JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js "></script>
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
              <form action="/Event-Management-System/events/form-handlers/createEventHandler.php" 
              method="POST" id="createEventForm" enctype="multipart/form-data">
                
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

                <!-- Location Image Section -->
                <div class="mb-4">
                  <label class="form-label">Location Image</label>
                  
                  <div class="image-upload-container" id="imageUploadContainer">
                    <!-- Initial Upload State -->
                    <div class="upload-state-initial" id="uploadInitial">
                      <div class="upload-icon-wrapper">
                        <i class="bi bi-cloud-arrow-up"></i>
                      </div>
                      <div class="upload-text-main">Click to upload venue photo</div>
                      <div class="upload-text-sub">JPG, PNG, GIF up to 5MB</div>
                      <label for="event_image" class="upload-button">Select Image</label>
                      <input 
                        type="file" 
                        class="form-control d-none" 
                        id="event_image" 
                        name="event_image" 
                        accept="image/*"
                        onchange="previewSelectedImage(this)"
                      />
                    </div>

                    <!-- Image Preview State (hidden by default) -->
                    <div class="upload-state-preview d-none" id="uploadPreview">
                      <div class="preview-image-wrapper">
                        <img src="" alt="Venue preview" id="previewImage" />
                        <div class="image-overlay">
                          <span class="image-badge">New Image</span>
                        </div>
                      </div>
                      <div class="upload-actions">
                        <label for="event_image_replace" class="btn-change-image">
                          <i class="bi bi-arrow-repeat"></i> Change Image
                        </label>
                        <input 
                          type="file" 
                          class="d-none" 
                          id="event_image_replace" 
                          accept="image/*"
                          onchange="previewSelectedImage(this)"
                        />
                      </div>
                    </div>
                  </div>
                  
                  <small class="form-text text-muted mt-2">
                    <i class="bi bi-info-circle"></i> Upload a clear photo of the venue to help attendees find the location.
                  </small>
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
      
      <script>
        // Set minimum date to today
        document.getElementById('event_date').min = new Date().toISOString().split('T')[0];
        
        // Preview selected image
        function previewSelectedImage(input) {
          if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
              const previewImg = document.getElementById('previewImage');
              const initialState = document.getElementById('uploadInitial');
              const previewState = document.getElementById('uploadPreview');
              
              previewImg.src = e.target.result;
              initialState.classList.add('d-none');
              previewState.classList.remove('d-none');
              
              // Update the main input with the selected file
              const mainInput = document.getElementById('event_image');
              const dt = new DataTransfer();
              dt.items.add(input.files[0]);
              mainInput.files = dt.files;
            };
            
            reader.readAsDataURL(input.files[0]);
          }
        }
      </script>
  </body>
</html>