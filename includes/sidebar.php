<?php
// sidebar.php - Reusable Sidebar Component

// Get current page for active state highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Get user info from session (placeholder - replace with your actual session variables)
// session_start(); // Make sure session is started in your main files
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
        <div class="full-name"><?php echo htmlspecialchars($full_name); ?></div>
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
        
        <!-- Browse Events -->
        <li class="nav-item">
          <a href="/Event-Management-System/events/browse.php" class="nav-link <?php echo $current_page === 'browse' ? 'active' : ''; ?>">
            <i class="bi bi-calendar-event"></i>
            <span>Browse Events</span>
          </a>
        </li>

        <?php if ($user_role === 'organizer' || $user_role === 'admin'): ?>
        <!-- My Events (Organizer/Admin only) -->
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
        <?php endif; ?>

        <?php if ($user_role === 'admin'): ?>
        <!-- Admin Panel (Admin only) -->
        <li class="nav-item">
          <a href="/Event-Management-System/admin/index.php" class="nav-link <?php echo $current_page === 'admin' ? 'active' : ''; ?>">
            <i class="bi bi-gear"></i>
            <span>Admin Panel</span>
          </a>
        </li>
        <?php endif; ?>

        <!-- Divider -->
        <li class="nav-divider"></li>

        <!-- Profile -->
        <li class="nav-item">
          <a href="profile.php" class="nav-link <?php echo $current_page === 'profile' ? 'active' : ''; ?>">
            <i class="bi bi-person"></i>
            <span>Profile</span>
          </a>
        </li>

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