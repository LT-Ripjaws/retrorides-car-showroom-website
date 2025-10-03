<?php 
// Get base URL for proper routing
$baseUrl = getBasePath(); 
?>
<aside class="sidebar">
    <header class="sidebar-header">
        <div class="sidebar-panel-name">
            <div class="sidebar-logo">
                <img src="<?php echo $baseUrl; ?>/assets/images/logo.png" alt="logo">
            </div>
            <h2>RetroRides</h2>
        </div>
        <button class="toggler sidebar-toggle">
            <span class="material-symbols-rounded">chevron_left</span>
        </button>
        <button class="toggler menu-toggle">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </header>
    <nav class="sidebar-nav">
        <!--primary top nav-->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/admin/dashboard" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <span class="nav-tooltip">Dashboard</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/admin/teams" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">group</span>
                    <span class="nav-label">Team</span>
                </a>
                <span class="nav-tooltip">Team</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/admin/cars" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">car_rental</span>
                    <span class="nav-label">Manage Cars</span>
                </a>
                <span class="nav-tooltip">Manage Cars</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/admin/bookings" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">event_upcoming</span>
                    <span class="nav-label">Bookings</span>
                </a>
                <span class="nav-tooltip">Bookings</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/admin/users" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">analytics</span>
                    <span class="nav-label">Users</span>
                </a>
                <span class="nav-tooltip">Users</span>
            </li>
        </ul>

        <!--Secondary bottom nav-->
        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/admin/profile" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">account_circle</span>
                    <span class="nav-label">Profile</span>
                </a>
                <span class="nav-tooltip">Profile</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/logout" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">logout</span>
                    <span class="nav-label">Logout</span>
                </a>
                <span class="nav-tooltip">Logout</span>
            </li>
        </ul>
    </nav>
</aside>