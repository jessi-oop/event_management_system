<?php
require_once __DIR__ . '/../app/Services/CategoryService/getAllCategories.php';
require_once __DIR__ . '/../app/Services/EventService/getUpcomingEvents.php';
require_once __DIR__ . '/../app/Services/AuthService/requireLogin.php';

$get_all_categories = new GetAllCategoriesService();
$get_upcoming_events = new GetUpcomingEventsService();
$require_login = new RequireLogin();

$require_login->requireLogin();

$categories = $get_all_categories->getAllCategories();
$upcoming_events = $get_upcoming_events->getUpcomingEvents();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Browse Events - EMS</title>
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
    <link rel="stylesheet" href="../assets/css/browse.css" />
  </head>
  <body>
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'?>

        <!-- Main Content -->
        <div class="main-content">
          <div class="content-wrapper w-100">
            <!-- Page Header -->
            <div class="page-header">
              <h2 class="page-title">Browse Events</h2>
              <p class="page-subtitle">Discover and register for upcoming events</p>
            </div>

            <!-- Search and Filter Section (Optional) -->
            <div class="filter-section mb-4">
              <div class="row g-3">
                <div class="col-md-8">
                  <input
                    type="text"
                    class="form-control search-input"
                    placeholder="Search events..."
                    id="searchInput"
                  />
                </div>
                <div class="col-md-4">
                  <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <?php if (empty($categories)): ?>
                      <option value="">No available categories</option>
                    <?php else: ?>
                      <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category->category_id)?>">
                          <?= htmlspecialchars($category->category_name)?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>
            </div>

            <!-- Events Grid -->
            <div class="row g-4" id="eventsGrid">
              
              <?php
              // Loop through events and create cards
              foreach ($upcoming_events as $event) {
                  // Truncate description for teaser
                  $teaser = strlen($event->description) > 120
                    ? substr($event->description, 0, 120) . '...'
                    : $event->description;

                  // Format date
                  $formatted_date = date('M d, Y', strtotime($event->event_date));

                  // Format time
                  $formatted_time = date('g:i A', strtotime($event->event_time));
                  ?>

              <!-- Event Card -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="event-card" data-category="<?php echo htmlspecialchars($event->category_id)?>">
                  <!-- Category Badge -->
                  <div class="category-badge"><?php echo $event->category_name; ?></div>
                  
                  <!-- Event Title -->
                  <h5 class="event-title"><?php echo $event->title; ?></h5>
                  
                  <!-- Date and Time -->
                  <div class="event-meta">
                    <div class="meta-item">
                      <i class="bi bi-calendar-event"></i>
                      <span><?php echo $formatted_date; ?></span>
                    </div>
                    <div class="meta-item">
                      <i class="bi bi-clock"></i>
                      <span><?php echo $formatted_time; ?></span>
                    </div>
                  </div>

                  <!-- Location -->
                  <div class="event-location">
                    <i class="bi bi-geo-alt"></i>
                    <span><?php echo $event->location; ?></span>
                  </div>
                  
                  <!-- Description Teaser -->
                  <p class="event-description"><?php echo $teaser; ?></p>

                  <!-- Redirect button to details.php -->
                  <div class="card-actions" style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <a href="details.php?event_id=<?php echo $event->event_id; ?>" class="btn btn-outline" style="flex: 1; text-align: center;">
                      Read More
                    </a>
                  </div>

                  <!-- Register Form -->
                  <form action="register.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event->event_id); ?>">
                    <button type="submit" class="btn btn-register" style="width: 100%;">Register Now</button>
                  </form>
                </div>
              </div>

              <?php } ?>

            </div>

            <!-- No Events Message (hidden by default) -->
            <div class="no-events" id="noEventsMessage" style="display: none;">
              <i class="bi bi-calendar-x"></i>
              <h4>No Events Found</h4>
              <p>There are no events matching your search criteria.</p>
            </div>

          </div>
        </div>
     

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Search functionality -->
    <script>
      const searchInput = document.getElementById('searchInput');
      const categoryFilter = document.getElementById('categoryFilter');
      
      function filterEvents() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const selectedCategory = categoryFilter?.value || ''; // Keep as empty string or ID
        const cards = document.querySelectorAll('.event-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
          const title = card.querySelector('.event-title')?.textContent.toLowerCase() || '';
          const description = card.querySelector('.event-description')?.textContent.toLowerCase() || '';
          const category = card.dataset.category || ''; // Keep as is (category_id)
          
          // Check search match
          const matchesSearch = !searchTerm || 
            title.includes(searchTerm) || 
            description.includes(searchTerm);
          
          // Check category match (empty selectedCategory means "All")
          const matchesCategory = selectedCategory === '' || category === selectedCategory;
          
          // Show card only if both conditions are met
          if (matchesSearch && matchesCategory) {
            card.closest('.col-12').style.display = '';
            visibleCount++;
          } else {
            card.closest('.col-12').style.display = 'none';
          }
        });
        
        const noEventsMsg = document.getElementById('noEventsMessage');
        if (noEventsMsg) {
          noEventsMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        }
      }
      
      searchInput?.addEventListener('input', filterEvents);
      categoryFilter?.addEventListener('change', filterEvents);
    </script>
  </body>
</html>