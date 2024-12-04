<?php
// Get the current page name dynamically
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav id="sidebarMenu" class="sidebar collapse d-lg-block bg-blue">
    <ul class="nav flex-column p-2">
        <li class="nav-item mb-1">
            <a class="nav-link py-2 px-3 <?= $current_page == 'dashboard.php' ? 'active' : '' ?>" href="../admin/dashboard.php">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link py-2 px-3 <?= $current_page == 'manage_users.php' ? 'active' : '' ?>" href="../admin/manage_users.php">
                <i class="fas fa-users me-2"></i> Users
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link py-2 px-3 <?= $current_page == 'manage_rooms.php' ? 'active' : '' ?>" href="../admin/manage_rooms.php">
                <i class="fas fa-door-open me-2"></i> Rooms
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link py-2 px-3 <?= $current_page == 'manage_bookings.php' ? 'active' : '' ?>" href="../admin/manage_bookings.php">
                <i class="fas fa-calendar-alt me-2"></i> Bookings
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link py-2 px-3 <?= $current_page == 'analytics.php' ? 'active' : '' ?>" href="../admin/analytics.php">
                <i class="fas fa-chart-bar me-2"></i> Analytics
            </a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link py-2 px-3 <?= $current_page == 'settings.php' ? 'active' : '' ?>" href="../admin/settings.php">
                <i class="fas fa-cogs me-2"></i> Settings
            </a>
        </li>
    </ul>
</nav>
