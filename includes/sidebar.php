<?php
// sidebar.php - Reusable Sidebar Component

// Get current page for active state highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Get user info from session (replace with your actual session variables)
$full_name = $_SESSION['full_name'] ?? 'John Doe';
$user_role = $_SESSION['role'] ?? 'organizer'; // 'admin', 'organizer', or 'attendee'
$user_email = $_SESSION['email'] ?? 'john@example.com';

// Determine role display text
$role_display = ucfirst($user_role);
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  
  <!-- Mobile Toggle Button -->
  <button class="sidebar-toggle d-md-none" id="sidebarToggle">
    <i class="bi bi-list"></i>
  </button>

  <!-- Sidebar Content -->
  <div class="sidebar-content">
    
    <!-- Logo/Title -->
    <div class="sidebar-header">
      <h4 class="sidebar-title">EMS</h4>
      <button class="sidebar-close d-md-none" id="sidebarClose">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>

    <!-- User Info Section -->
    <div class="user-info">
      <div class="user-avatar">
        <i class="bi bi-person-circle"></i>
      </div>
      <div class="user-details">
        <div class="user-name"><?php echo htmlspecialchars($full_name); ?></div>
        <div class="user-role">
          <?php if ($user_role === 'admin'): ?>
            <i class="bi bi-shield-check"></i>
          <?php elseif ($user_role === 'organizer'): ?>
            <i class="bi bi-briefcase"></i>
          <?php else: ?>
            <i class="bi bi-person"></i>
          <?php endif; ?>
          <?php echo htmlspecialchars($role_display); ?>
        </div>
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
      <ul class="nav-list">
        
        <!-- ==========================================
             COMMON SECTION (ALL USERS)
             Add items here that all users should see
             ========================================== -->
        
        <!-- Browse Events - Available to all users -->
        <li class="nav-item">
          <a href="/Event-Management-System/events/browse.php" class="nav-link <?php echo $current_page === 'browse' ? 'active' : ''; ?>">
            <i class="bi bi-calendar-event"></i>
            <span>Browse Events</span>
          </a>
        </li>

        <!-- 
          HOW TO ADD MORE COMMON ITEMS:
          Copy the structure above and change:
          1. href = your page URL
          2. $current_page check = your page name (without .php)
          3. icon class = Bootstrap icon name (see list at bottom)
          4. span text = Display name
          
          Example:
          <li class="nav-item">
            <a href="/path/to/page.php" class="nav-link <?php echo $current_page === 'page' ? 'active' : ''; ?>">
              <i class="bi bi-ICON-NAME"></i>
              <span>Display Name</span>
            </a>
          </li>
        -->

        <!-- ==========================================
             ATTENDEE SECTION
             Items only visible to attendees
             ========================================== -->
        
        <?php if ($user_role === 'attendee'): ?>
        
        <!-- My Registered Events (Attendee only) -->
        <li class="nav-item">
          <a href="/Event-Management-System/attendee/myEventsAttendee.php" class="nav-link <?php echo $current_page === 'my-events' ? 'active' : ''; ?>">
            <i class="bi bi-bookmark-check"></i>
            <span>My Events</span>
          </a>
        </li>

        <!-- 
          ADD MORE ATTENDEE-SPECIFIC ITEMS HERE
          Examples of what you might add:
          
          <li class="nav-item">
            <a href="/Event-Management-System/attendee/certificates.php" class="nav-link <?php echo $current_page === 'certificates' ? 'active' : ''; ?>">
              <i class="bi bi-award"></i>
              <span>My Certificates</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/Event-Management-System/attendee/feedback.php" class="nav-link <?php echo $current_page === 'feedback' ? 'active' : ''; ?>">
              <i class="bi bi-chat-dots"></i>
              <span>Feedback</span>
            </a>
          </li>
        -->

        <?php endif; ?>

        <!-- ==========================================
             ORGANIZER / ADMIN SECTION
             Items visible to organizers and admins
             ========================================== -->
        
        <?php if ($user_role === 'organizer' || $user_role === 'admin'): ?>
        
        <!-- My Events (Organizer/Admin only) - Manage created events -->
        <li class="nav-item">
          <a href="/Event-Management-System/organizer/manage.php" class="nav-link <?php echo $current_page === 'manage' ? 'active' : ''; ?>">
            <i class="bi bi-grid"></i>
            <span>My Events</span>
          </a>
        </li>

        <!-- Create Event (Organizer/Admin only) -->
        <li class="nav-item">
          <a href="/Event-Management-System/events/create.php" class="nav-link <?php echo $current_page === 'create' ? 'active' : ''; ?>">
            <i class="bi bi-plus-circle"></i>
            <span>Create Event</span>
          </a>
        </li>

        <!-- 
          ADD MORE ORGANIZER/ADMIN ITEMS HERE
          Examples of what you might add:
          
          <li class="nav-item">
            <a href="/Event-Management-System/organizer/analytics.php" class="nav-link <?php echo $current_page === 'analytics' ? 'active' : ''; ?>">
              <i class="bi bi-graph-up"></i>
              <span>Analytics</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/Event-Management-System/organizer/reports.php" class="nav-link <?php echo $current_page === 'reports' ? 'active' : ''; ?>">
              <i class="bi bi-file-text"></i>
              <span>Reports</span>
            </a>
          </li>
        -->

        <?php endif; ?>

        <!-- ==========================================
             ADMIN-ONLY SECTION
             Items visible only to administrators
             ========================================== -->
        
        <?php if ($user_role === 'admin'): ?>
        
        <!-- Admin Panel (Admin only) -->
        <li class="nav-item">
          <a href="/Event-Management-System/admin/index.php" class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>">
            <i class="bi bi-gear"></i>
            <span>Admin Panel</span>
          </a>
        </li>

        <!-- 
          ADD MORE ADMIN-ONLY ITEMS HERE
          Examples of what you might add:
          
          <li class="nav-item">
            <a href="/Event-Management-System/admin/users.php" class="nav-link <?php echo $current_page === 'users' ? 'active' : ''; ?>">
              <i class="bi bi-people"></i>
              <span>Manage Users</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/Event-Management-System/admin/categories.php" class="nav-link <?php echo $current_page === 'categories' ? 'active' : ''; ?>">
              <i class="bi bi-tags"></i>
              <span>Manage Categories</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/Event-Management-System/admin/settings.php" class="nav-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
              <i class="bi bi-sliders"></i>
              <span>System Settings</span>
            </a>
          </li>
        -->

        <?php endif; ?>

        <!-- ==========================================
             DIVIDER
             Separates main content from user actions
             ========================================== -->
        
        <li class="nav-divider"></li>

        <!-- ==========================================
             USER ACTIONS SECTION
             Profile, Settings, Logout - for all users
             ========================================== -->

        <!-- Profile -->
        <li class="nav-item">
          <a href="/Event-Management-System/user/profile.php" class="nav-link <?php echo $current_page === 'profile' ? 'active' : ''; ?>">
            <i class="bi bi-person"></i>
            <span>Profile</span>
          </a>
        </li>

        <!-- 
          ADD MORE USER ACTION ITEMS HERE
          Examples of what you might add:
          
          <li class="nav-item">
            <a href="/Event-Management-System/user/settings.php" class="nav-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
              <i class="bi bi-sliders"></i>
              <span>Settings</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/Event-Management-System/help.php" class="nav-link <?php echo $current_page === 'help' ? 'active' : ''; ?>">
              <i class="bi bi-question-circle"></i>
              <span>Help & Support</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="/Event-Management-System/user/notifications.php" class="nav-link <?php echo $current_page === 'notifications' ? 'active' : ''; ?>">
              <i class="bi bi-bell"></i>
              <span>Notifications</span>
            </a>
          </li>
        -->

        <!-- Logout -->
        <li class="nav-item">
          <a href="/Event-Management-System/auth/logout.php" class="nav-link nav-link-logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
          </a>
        </li>

      </ul>
    </nav>

  </div>
  
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar Toggle Script -->
<script>
  // Mobile sidebar toggle
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarClose = document.getElementById('sidebarClose');
  const sidebarOverlay = document.getElementById('sidebarOverlay');

  function openSidebar() {
    sidebar.classList.add('sidebar-open');
    sidebarOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar.classList.remove('sidebar-open');
    sidebarOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', openSidebar);
  }

  if (sidebarClose) {
    sidebarClose.addEventListener('click', closeSidebar);
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
  }
</script>

<!-- 
  ================================================
  SIDEBAR STRUCTURE SUMMARY
  ================================================
  
  1. COMMON SECTION (All Users)
     └─ Browse Events
     └─ [Add more items all users need]
  
  2. ATTENDEE SECTION (Attendees Only)
     └─ My Events (registered events)
     └─ [Add certificates, feedback, history, etc.]
  
  3. ORGANIZER/ADMIN SECTION (Organizers & Admins)
     └─ My Events (created events)
     └─ Create Event
     └─ [Add analytics, reports, attendee lists, etc.]
  
  4. ADMIN-ONLY SECTION (Admins Only)
     └─ Admin Panel
     └─ [Add user management, categories, settings, etc.]
  
  5. DIVIDER
  
  6. USER ACTIONS (All Users)
     └─ Profile
     └─ [Add settings, help, notifications, etc.]
     └─ Logout
  
  ================================================
  BOOTSTRAP ICONS REFERENCE
  ================================================
  Find more icons at: https://icons.getbootstrap.com/
  
  Commonly Used Icons:
  
  EVENTS & CALENDAR:
  - bi-calendar-event (events)
  - bi-calendar-check (registered/confirmed)
  - bi-calendar-plus (add event)
  - bi-calendar-x (cancelled)
  - bi-bookmark-check (saved/bookmarked)
  
  NAVIGATION:
  - bi-grid (dashboard/manage)
  - bi-plus-circle (create/add)
  - bi-gear (settings/admin)
  - bi-house (home)
  
  USERS & PEOPLE:
  - bi-people (users/attendees)
  - bi-person (profile)
  - bi-person-circle (user avatar)
  - bi-shield-check (admin/verified)
  - bi-briefcase (organizer/business)
  
  DATA & ANALYTICS:
  - bi-graph-up (analytics/stats)
  - bi-bar-chart (reports)
  - bi-pie-chart (metrics)
  
  COMMUNICATION:
  - bi-chat-dots (messages/feedback)
  - bi-bell (notifications)
  - bi-envelope (email)
  
  DOCUMENTS:
  - bi-file-text (documents/reports)
  - bi-award (certificates/badges)
  - bi-tags (categories/labels)
  
  ACTIONS:
  - bi-box-arrow-right (logout)
  - bi-question-circle (help)
  - bi-sliders (settings/filters)
  - bi-search (search)
  
  ================================================
  ADDING NEW CONDITIONAL SECTIONS
  ================================================
  
  If you want to add sections based on OTHER conditions
  (not just role), use this pattern:
  
  <?php if ($some_condition): ?>
  <li class="nav-item">
    <a href="/path.php" class="nav-link">
      <i class="bi bi-icon"></i>
      <span>Item Name</span>
    </a>
  </li>
  <?php endif; ?>
  
  Examples:
  - Show "Premium Features" only if user has subscription
  - Show "Team Dashboard" only if user is part of a team
  - Show "Drafts" only if user has draft events
  
  ================================================
  TIPS FOR ORGANIZING LARGE MENUS
  ================================================
  
  If your sidebar gets too long, consider:
  
  1. Add another divider to separate sections:
     <li class="nav-divider"></li>
  
  2. Add section headers (not clickable):
     <li class="nav-section-header">Section Name</li>
     (You'll need to style this in CSS)
  
  3. Create collapsible submenus:
     Use Bootstrap collapse or accordion components
  
  4. Split into multiple pages:
     Create separate dashboards for different roles
-->