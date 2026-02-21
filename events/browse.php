<?php

require_once __DIR__ . '/../app/Services/AuthService/requireLogin.php';
require_once __DIR__ . '/../app/Services/EventService/getAllEvents.php';
require_once __DIR__ . '/../app/Services/CategoryService/getAllCategories.php';

$require_login = new RequireLogin();
$get_all_events = new GetAllEventsService();
$get_all_categories = new GetAllCategoriesService();

$require_login->requireLogin();

$events = $get_all_events->getAllEvents();
$categories = $get_all_categories->getAllCategories();


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
    <link rel="stylesheet" href="/../assets/css/browse.css" />
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
                    <option value="1">Conference</option>
                    <option value="2">Workshop</option>
                    <option value="3">Seminar</option>
                    <option value="4">Webinar</option>
                    <option value="5">Networking</option>
                    <option value="6">Training</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Events Grid -->
            <div class="row g-4" id="eventsGrid">
              
              <?php
              // Example events array - replace with database fetch
              // $events = fetch_events_from_db();

              // Sample data for demonstration
              $sample_events = [
                [
                  'id' => 1,
                  'title' => 'Annual Tech Conference 2024',
                  'description' => 'Join us for the biggest technology conference of the year. Featuring keynote speakers from leading tech companies, hands-on workshops, and networking opportunities with industry professionals.',
                  'event_date' => '2024-03-15',
                  'event_time' => '09:00',
                  'location' => 'Convention Center, New York',
                  'category' => 'Conference'
                ],
                [
                  'id' => 2,
                  'title' => 'Web Development Workshop',
                  'description' => 'Learn the latest web development techniques and frameworks in this intensive workshop. Perfect for beginners and intermediate developers looking to expand their skills.',
                  'event_date' => '2024-03-20',
                  'event_time' => '14:00',
                  'location' => 'Tech Hub, San Francisco',
                  'category' => 'Workshop'
                ],
                [
                  'id' => 3,
                  'title' => 'Digital Marketing Seminar',
                  'description' => 'Discover the secrets of successful digital marketing campaigns. Learn about SEO, social media marketing, content strategy, and analytics from industry experts.',
                  'event_date' => '2024-03-25',
                  'event_time' => '10:30',
                  'location' => 'Business Center, Chicago',
                  'category' => 'Seminar'
                ],
                [
                  'id' => 4,
                  'title' => 'AI and Machine Learning Webinar',
                  'description' => 'Explore the future of artificial intelligence and machine learning. This online session covers the latest trends, applications, and career opportunities in AI.',
                  'event_date' => '2024-04-05',
                  'event_time' => '16:00',
                  'location' => 'Online Event',
                  'category' => 'Webinar'
                ],
                [
                  'id' => 5,
                  'title' => 'Startup Networking Night',
                  'description' => 'Connect with fellow entrepreneurs, investors, and startup enthusiasts. Share ideas, find co-founders, and build valuable relationships in the startup ecosystem.',
                  'event_date' => '2024-04-10',
                  'event_time' => '18:00',
                  'location' => 'Innovation Hub, Austin',
                  'category' => 'Networking'
                ],
                [
                  'id' => 6,
                  'title' => 'Professional Leadership Training',
                  'description' => 'Develop essential leadership skills for the modern workplace. Topics include team management, communication strategies, conflict resolution, and strategic thinking.',
                  'event_date' => '2024-04-15',
                  'event_time' => '09:00',
                  'location' => 'Training Center, Boston',
                  'category' => 'Training'
                ],
              ];

// Loop through events and create cards
foreach ($sample_events as $event) {
    // Truncate description for teaser
    $teaser = strlen($event['description']) > 120
      ? substr($event['description'], 0, 120) . '...'
      : $event['description'];

    // Format date
    $formatted_date = date('M d, Y', strtotime($event['event_date']));

    // Format time
    $formatted_time = date('g:i A', strtotime($event['event_time']));
    ?>

              <!-- Event Card -->
              <div class="col-12 col-md-6 col-lg-4">
                <div class="event-card">
                  <!-- Category Badge -->
                  <div class="category-badge"><?php echo $event['category']; ?></div>
                  
                  <!-- Event Title -->
                  <h5 class="event-title"><?php echo $event['title']; ?></h5>
                  
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
                    <span><?php echo $event['location']; ?></span>
                  </div>
                  
                  <!-- Description Teaser -->
                  <p class="event-description"><?php echo $teaser; ?></p>

                  <!-- Redirect button to details.php -->
                  <div class="card-actions" style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <a href="details.php?event_id=<?php echo $event['id']; ?>" class="btn btn-outline" style="flex: 1; text-align: center;">
                      Read More
                    </a>
                  </div>
                  
                  <!-- Register Button -->
                  <a href="register.php?event_id=<?php echo $event['id']; ?>" class="btn btn-register">
                    Register Now
                  </a>
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
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Search functionality -->
    <script>
      // Simple search filter
      document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.event-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
          const title = card.querySelector('.event-title').textContent.toLowerCase();
          const description = card.querySelector('.event-description').textContent.toLowerCase();
          
          if (title.includes(searchTerm) || description.includes(searchTerm)) {
            card.closest('.col-12').style.display = '';
            visibleCount++;
          } else {
            card.closest('.col-12').style.display = 'none';
          }
        });
        
        // Show/hide no events message
        document.getElementById('noEventsMessage').style.display = 
          visibleCount === 0 ? 'block' : 'none';
      });
    </script>
  </body>
</html>