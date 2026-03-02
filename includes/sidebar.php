<?php
// sidebar.php - Reusable Sidebar Component

// Get current page for active state highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Get user info from session
$full_name = $_SESSION['full_name'] ?? 'John Doe';
$user_role = $_SESSION['role'] ?? 'organizer';
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
             ========================================== -->
        
        <li class="nav-item">
          <a href="/Event-Management-System/events/browse.php" class="nav-link <?php echo $current_page === 'browse' ? 'active' : ''; ?>">
            <i class="bi bi-calendar-event"></i>
            <span>Browse Events</span>
          </a>
        </li>

        <!-- ==========================================
             ATTENDEE SECTION
             ========================================== -->
        
        <?php if ($user_role === 'attendee'): ?>
        
        <li class="nav-item">
          <a href="/Event-Management-System/attendee/myEventsAttendee.php" class="nav-link <?php echo $current_page === 'myEventsAttendee' ? 'active' : ''; ?>">
            <i class="bi bi-bookmark-check"></i>
            <span>My Events</span>
          </a>
        </li>

        <?php endif; ?>

        <!-- ==========================================
             ORGANIZER / ADMIN SECTION
             ========================================== -->
        
        <?php if ($user_role === 'organizer' || $user_role === 'admin'): ?>
        
        <li class="nav-item">
          <a href="/Event-Management-System/organizer/manage.php" class="nav-link <?php echo $current_page === 'manage' ? 'active' : ''; ?>">
            <i class="bi bi-grid"></i>
            <span>My Events</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="/Event-Management-System/events/create.php" class="nav-link <?php echo $current_page === 'create' ? 'active' : ''; ?>">
            <i class="bi bi-plus-circle"></i>
            <span>Create Event</span>
          </a>
        </li>

        <?php endif; ?>

        <!-- ==========================================
             ADMIN-ONLY SECTION
             ========================================== -->
        
        <?php if ($user_role === 'admin'): ?>
        
        <li class="nav-item">
          <a href="/Event-Management-System/admin/index.php" class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="/Event-Management-System/admin/users.php" class="nav-link <?php echo $current_page === 'users' ? 'active' : ''; ?>">
            <i class="bi bi-people"></i>
            <span>Manage Users</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="/Event-Management-System/admin/events.php" class="nav-link <?php echo $current_page === 'events' ? 'active' : ''; ?>">
            <i class="bi bi-calendar-event"></i>
            <span>Manage Events</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="/Event-Management-System/admin/approvals.php" class="nav-link <?php echo $current_page === 'approvals' ? 'active' : ''; ?>">
            <i class="bi bi-clipboard-check"></i>
            <span>Event Approvals</span>
          </a>
        </li>

        <?php endif; ?>

        <!-- ==========================================
             DIVIDER
             ========================================== -->
        
        <li class="nav-divider"></li>

        <!-- ==========================================
             USER ACTIONS SECTION
             ========================================== -->

        <li class="nav-item">
          <a href="/Event-Management-System/user/profile.php" class="nav-link <?php echo $current_page === 'profile' ? 'active' : ''; ?>">
            <i class="bi bi-person"></i>
            <span>Profile</span>
          </a>
        </li>

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