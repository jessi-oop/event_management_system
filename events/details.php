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
    <link rel="stylesheet" href="/../assets/css/dashboard.css" />
    <link rel="stylesheet" href="/../assets/css/details.css" />
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
            // Get event ID from URL
            $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

            // Fetch event from database
            // $event = fetch_event_by_id($event_id);

            // Sample event data - replace with database fetch
            $event = [
              'id' => 1,
              'title' => 'Annual Tech Conference 2024',
              'category' => 'Conference',
              'category_id' => 1,
              'description' => 'Join us for the biggest technology conference of the year. This premier event brings together industry leaders, innovators, and technology enthusiasts from around the world. 
              
              Our conference features keynote presentations from CEOs of leading tech companies, deep-dive technical workshops, panel discussions on emerging technologies, and unparalleled networking opportunities. 
              
              Topics covered include: Artificial Intelligence and Machine Learning, Cloud Computing and DevOps, Cybersecurity Best Practices, Web3 and Blockchain Technology, Mobile Development Trends, and much more.
              
              Whether you\'re a seasoned professional or just starting your tech journey, this conference offers valuable insights, practical knowledge, and the chance to connect with like-minded individuals who are shaping the future of technology.',
              'event_date' => '2024-03-15',
              'event_time' => '09:00',
              'location' => 'Convention Center, New York',
              'capacity' => 500,
              'registered' => 342, // Current registrations
              'organizer' => 'Tech Events Inc.',
              'contact_email' => 'info@techevents.com',
              'contact_phone' => '+1 (555) 123-4567'
            ];

            // Calculate available slots
            $available_slots = $event['capacity'] - $event['registered'];
            $fill_percentage = ($event['registered'] / $event['capacity']) * 100;

            // Format date and time
            $formatted_date = date('l, F d, Y', strtotime($event['event_date']));
            $formatted_time = date('g:i A', strtotime($event['event_time']));

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
                  <span class="category-badge"><?php echo $event['category']; ?></span>
                  <?php if ($is_full): ?>
                    <span class="status-badge full">Event Full</span>
                  <?php elseif ($available_slots <= 20): ?>
                    <span class="status-badge limited">Limited Slots</span>
                  <?php endif; ?>
                </div> <!-- CLOSE: header-top -->
                <h1 class="event-title"><?php echo $event['title']; ?></h1>
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
                      <div class="info-value"><?php echo $event['location']; ?></div>
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
                      <div class="info-value"><?php echo $available_slots; ?> / <?php echo $event['capacity']; ?></div>
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
                  <?php echo nl2br($event['description']); ?>
                </div> <!-- CLOSE: description-content -->
              </div> <!-- CLOSE: description-section -->

              <!-- Organizer Info -->
              <div class="organizer-section"> <!-- OPEN: organizer-section -->
                <h3 class="section-title">Organizer Information</h3>
                <div class="organizer-content"> <!-- OPEN: organizer-content -->
                  <div class="organizer-item"> <!-- OPEN+CLOSE: organizer-item -->
                    <i class="bi bi-building"></i>
                    <span><?php echo $event['organizer']; ?></span>
                  </div>
                  <div class="organizer-item"> <!-- OPEN+CLOSE: organizer-item -->
                    <i class="bi bi-envelope"></i>
                    <a href="mailto:<?php echo $event['contact_email']; ?>"><?php echo $event['contact_email']; ?></a>
                  </div>
                  <div class="organizer-item"> <!-- OPEN+CLOSE: organizer-item -->
                    <i class="bi bi-telephone"></i>
                    <a href="tel:<?php echo $event['contact_phone']; ?>"><?php echo $event['contact_phone']; ?></a>
                  </div>
                </div> <!-- CLOSE: organizer-content -->
              </div> <!-- CLOSE: organizer-section -->

             <!-- Action Buttons  -->
              <!-- Todo: Implement proper auth for authorization and role checking -->
                <div class="action-section"> 
                <!-- For organizers/admins only - Always show -->
                <a href="edit.php?event_id=<?php echo $event['id']; ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Event
                </a>

                <?php if ($is_full): ?>
                  <button class="btn btn-full" disabled>
                    <i class="bi bi-x-circle"></i> Event Full
                  </button>
                  <a href="browse.php" class="btn btn-secondary">
                    Browse Other Events
                  </a>
                <?php else: ?>
                  <a href="register.php?event_id=<?php echo $event['id']; ?>" class="btn btn-register">
                    <i class="bi bi-calendar-check"></i> Register Now
                  </a>
                  <button class="btn btn-secondary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Details
                  </button>
                <?php endif; ?>
              </div> <!-- CLOSE: action-section -->

            </div> <!-- CLOSE: details-container -->

          </div> <!-- CLOSE: content-wrapper -->
        </div> <!-- CLOSE: main-content col -->
        
      </div> <!-- CLOSE: row -->
    </div> <!-- CLOSE: container-fluid -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>