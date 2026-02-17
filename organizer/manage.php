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
    <link rel="stylesheet" href="/../assets/css/dashboard.css" />
    <link rel="stylesheet" href="/../assets/css/manage.css" />
  </head>
  <body>
    <div class="container-fluid"> <!-- OPEN: container-fluid -->
      <div class="row"> <!-- OPEN: row -->
        
        <!-- Sidebar -->
        <div class="col-12 col-md-3 col-lg-2 sidebar"> <!-- OPEN: sidebar col -->
          <h4 class="sidebar-title">EMS</h4>
          <!-- Add your navigation menu here -->
        </div> <!-- CLOSE: sidebar col -->

        <!-- Main Content -->
        <div class="col-12 col-md-9 col-lg-10 main-content d-flex justify-content-center"> <!-- OPEN: main-content col -->
          <div class="content-wrapper w-100"> <!-- OPEN: content-wrapper -->
            
            <?php
            // Check if user is an organizer
            // session_start();
            // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organizer') {
            //   header('Location: browse.php');
            //   exit();
            // }
            
            // Get organizer ID from session
            // $organizer_id = $_SESSION['user_id'];
            
            // Fetch events created by this organizer
            // $events = fetch_events_by_organizer($organizer_id);
            
            // Sample events data - replace with database fetch
            $events = [
              [
                'id' => 1,
                'title' => 'Annual Tech Conference 2024',
                'category' => 'Conference',
                'event_date' => '2024-03-15',
                'event_time' => '09:00',
                'location' => 'Convention Center, New York',
                'capacity' => 500,
                'registered' => 342,
                'status' => 'upcoming'
              ],
              [
                'id' => 2,
                'title' => 'Web Development Workshop',
                'category' => 'Workshop',
                'event_date' => '2024-03-20',
                'event_time' => '14:00',
                'location' => 'Tech Hub, San Francisco',
                'capacity' => 50,
                'registered' => 50,
                'status' => 'full'
              ],
              [
                'id' => 3,
                'title' => 'Digital Marketing Seminar',
                'category' => 'Seminar',
                'event_date' => '2024-02-10',
                'event_time' => '10:30',
                'location' => 'Business Center, Chicago',
                'capacity' => 100,
                'registered' => 87,
                'status' => 'past'
              ],
              [
                'id' => 4,
                'title' => 'AI and Machine Learning Webinar',
                'category' => 'Webinar',
                'event_date' => '2024-04-05',
                'event_time' => '16:00',
                'location' => 'Online Event',
                'capacity' => 200,
                'registered' => 145,
                'status' => 'upcoming'
              ],
              [
                'id' => 5,
                'title' => 'Startup Networking Night',
                'category' => 'Networking',
                'event_date' => '2024-04-10',
                'event_time' => '18:00',
                'location' => 'Innovation Hub, Austin',
                'capacity' => 80,
                'registered' => 12,
                'status' => 'upcoming'
              ],
            ];
            
            // Calculate statistics
            $total_events = count($events);
            $upcoming_events = count(array_filter($events, fn($e) => $e['status'] === 'upcoming'));
            $total_registrations = array_sum(array_column($events, 'registered'));
            ?>

            <!-- Page Header -->
            <div class="page-header"> <!-- OPEN: page-header -->
              <div class="header-content"> <!-- OPEN: header-content -->
                <div class="header-text"> <!-- OPEN: header-text -->
                  <h2 class="page-title">My Events</h2>
                  <p class="page-subtitle">Manage your created events</p>
                </div> <!-- CLOSE: header-text -->
                <div class="header-actions"> <!-- OPEN: header-actions -->
                  <a href="create.php" class="btn btn-primary">
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
                    <option value="Conference">Conference</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Webinar">Webinar</option>
                    <option value="Networking">Networking</option>
                    <option value="Training">Training</option>
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
                  <thead> <!-- OPEN: thead -->
                    <tr> <!-- OPEN: tr -->
                      <th>Event Title</th>
                      <th>Date & Time</th>
                      <th>Category</th>
                      <th>Location</th>
                      <th>Capacity</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr> <!-- CLOSE: tr -->
                  </thead> <!-- CLOSE: thead -->
                  <tbody> <!-- OPEN: tbody -->
                    
                    <?php foreach ($events as $event): 
                      // Format date and time
                      $formatted_date = date('M d, Y', strtotime($event['event_date']));
                      $formatted_time = date('g:i A', strtotime($event['event_time']));
                      
                      // Determine status badge
                      $status_class = '';
                      $status_text = '';
                      switch($event['status']) {
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
                      $fill_percentage = ($event['registered'] / $event['capacity']) * 100;
                    ?>
                    
                    <tr data-category="<?php echo $event['category']; ?>" data-status="<?php echo $event['status']; ?>"> <!-- OPEN: event row -->
                      
                      <!-- Event Title -->
                      <td class="event-title-cell"> <!-- OPEN+CLOSE: td -->
                        <a href="details.php?event_id=<?php echo $event['id']; ?>" class="event-link">
                          <?php echo $event['title']; ?>
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
                        <span class="category-badge"><?php echo $event['category']; ?></span>
                      </td>
                      
                      <!-- Location -->
                      <td class="location-cell"> <!-- OPEN+CLOSE: td -->
                        <i class="bi bi-geo-alt"></i>
                        <?php echo $event['location']; ?>
                      </td>
                      
                      <!-- Capacity -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <div class="capacity-cell">
                          <div class="capacity-text">
                            <strong><?php echo $event['registered']; ?></strong> / <?php echo $event['capacity']; ?>
                          </div>
                          <div class="capacity-bar">
                            <div class="capacity-fill" style="width: <?php echo $fill_percentage; ?>%"></div>
                          </div>
                        </div>
                      </td>
                      
                      <!-- Status -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <span class="status-badge <?php echo $status_class; ?>">
                          <?php echo $status_text; ?>
                        </span>
                      </td>
                      
                      <!-- Actions -->
                      <td> <!-- OPEN+CLOSE: td -->
                        <div class="action-buttons">
                          <a href="details.php?event_id=<?php echo $event['id']; ?>" 
                             class="btn-action btn-view" 
                             title="View Details">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="edit.php?event_id=<?php echo $event['id']; ?>" 
                             class="btn-action btn-edit" 
                             title="Edit Event">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <button class="btn-action btn-delete" 
                                  title="Delete Event"
                                  onclick="confirmDelete(<?php echo $event['id']; ?>, '<?php echo addslashes($event['title']); ?>')">
                            <i class="bi bi-trash"></i>
                          </button>
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
        
      </div> <!-- CLOSE: row -->
    </div> <!-- CLOSE: container-fluid -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true"> <!-- OPEN: modal -->
      <div class="modal-dialog modal-dialog-centered"> <!-- OPEN: modal-dialog -->
        <div class="modal-content"> <!-- OPEN: modal-content -->
          <div class="modal-header"> <!-- OPEN: modal-header -->
            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div> <!-- CLOSE: modal-header -->
          <div class="modal-body"> <!-- OPEN: modal-body -->
            <p>Are you sure you want to delete the event:</p>
            <p class="event-name-delete" id="eventNameDelete"></p>
            <p class="text-danger"><strong>Warning:</strong> This action cannot be undone. All registrations for this event will also be deleted.</p>
          </div> <!-- CLOSE: modal-body -->
          <div class="modal-footer"> <!-- OPEN: modal-footer -->
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <form action="process_delete.php" method="POST" style="display: inline;"> <!-- OPEN: form -->
              <input type="hidden" name="event_id" id="deleteEventId" />
              <button type="submit" class="btn btn-danger">Delete Event</button>
            </form> <!-- CLOSE: form -->
          </div> <!-- CLOSE: modal-footer -->
        </div> <!-- CLOSE: modal-content -->
      </div> <!-- CLOSE: modal-dialog -->
    </div> <!-- CLOSE: modal -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
      // Delete confirmation
      function confirmDelete(eventId, eventTitle) {
        document.getElementById('deleteEventId').value = eventId;
        document.getElementById('eventNameDelete').textContent = eventTitle;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
      }
      
      // Search functionality
      const searchInput = document.getElementById('searchInput');
      const categoryFilter = document.getElementById('categoryFilter');
      const statusFilter = document.getElementById('statusFilter');
      const tableRows = document.querySelectorAll('#eventsTable tbody tr');
      const noResults = document.getElementById('noResults');
      
      function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const categoryValue = categoryFilter.value;
        const statusValue = statusFilter.value;
        let visibleCount = 0;
        
        tableRows.forEach(row => {
          const title = row.querySelector('.event-title-cell').textContent.toLowerCase();
          const location = row.querySelector('.location-cell').textContent.toLowerCase();
          const category = row.getAttribute('data-category');
          const status = row.getAttribute('data-status');
          
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
      
      searchInput.addEventListener('input', filterTable);
      categoryFilter.addEventListener('change', filterTable);
      statusFilter.addEventListener('change', filterTable);
    </script>
  </body>
</html>