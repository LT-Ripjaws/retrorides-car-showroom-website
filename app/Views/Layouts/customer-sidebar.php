<?php 
$baseUrl = getBasePath(); 
$customerName = $_SESSION['user_name'] ?? 'Customer';
?>
<aside class="customer-sidebar">
    <header class="sidebar-header">
        <div class="sidebar-panel-name">
            <div class="sidebar-logo">
                <img src="<?php echo $baseUrl; ?>/assets/images/logo.png" alt="RetroRides Logo">
            </div>
            <div class="sidebar-brand">
                <h2>RetroRides</h2>
                <span class="sidebar-subtitle">Customer Portal</span>
            </div>
        </div>
        <button class="toggler sidebar-toggle">
            <span class="material-symbols-rounded">chevron_left</span>
        </button>
        <button class="toggler menu-toggle">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </header>

    <!-- User Profile Section -->
    <div class="sidebar-profile">
        <div class="profile-avatar">
            <span class="material-symbols-rounded">account_circle</span>
        </div>
        <div class="profile-info">
            <h3><?php echo htmlspecialchars($customerName); ?></h3>
        </div>
    </div>

    <nav class="sidebar-nav">
        <!-- Primary Navigation -->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/customer/dashboard" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <span class="nav-tooltip">Dashboard</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/customer/bookings" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">event_note</span>
                    <span class="nav-label">My Bookings</span>
                </a>
                <span class="nav-tooltip">My Bookings</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/collection" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">directions_car</span>
                    <span class="nav-label">Browse Cars</span>
                </a>
                <span class="nav-tooltip">Browse Cars</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/customer/profile" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">person</span>
                    <span class="nav-label">My Profile</span>
                </a>
                <span class="nav-tooltip">My Profile</span>
            </li>
        </ul>

        <!-- Secondary Navigation -->
        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">home</span>
                    <span class="nav-label">Home</span>
                </a>
                <span class="nav-tooltip">Home</span>
            </li>
            <li class="nav-item">
                <a href="<?php echo $baseUrl; ?>/contact" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">support_agent</span>
                    <span class="nav-label">Support</span>
                </a>
                <span class="nav-tooltip">Support</span>
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