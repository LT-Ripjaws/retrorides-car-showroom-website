<!-- Dashboard Header -->
<header class="topbar">
    <div class="header-content">
        <div class="welcome-section">
            <h1>Welcome Back, <?php echo htmlspecialchars($customerName); ?>!</h1>
            <p class="header-subtitle">Here's what's happening with your vintage car journey</p>
        </div>
        <div class="header-actions">
            <button class="btn-main small" onclick="window.location.href='<?php echo getBasePath(); ?>/collection'">
                +New Booking
            </button>
        </div>
    </div>
</header>

<!-- Quick Stats Grid -->
<section class="summarisation-cards">
    <div class="stat-card primary">
        <div class="stat-icon-wrapper">
            <span class="material-symbols-rounded">event_available</span>
        </div>
        <div class="stat-content">
            <h3><?php echo $totalBookings; ?></h3>
            <p>Total Bookings</p>
            <span class="stat-trend positive">
                <span class="material-symbols-rounded">trending_up</span>
                All time
            </span>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-icon-wrapper">
            <span class="material-symbols-rounded">check_circle</span>
        </div>
        <div class="stat-content">
            <h3><?php echo $completedBookings; ?></h3>
            <p>Completed</p>
            <span class="stat-trend positive">
                <span class="material-symbols-rounded">done</span>
                Purchased
            </span>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-icon-wrapper">
            <span class="material-symbols-rounded">pending</span>
        </div>
        <div class="stat-content">
            <h3><?php echo $activeBookings; ?></h3>
            <p>Active Bookings</p>
            <span class="stat-trend">
                <span class="material-symbols-rounded">schedule</span>
                In progress
            </span>
        </div>
    </div>

    <div class="stat-card accent">
        <div class="stat-icon-wrapper">
            <span class="material-symbols-rounded">payments</span>
        </div>
        <div class="stat-content">
            <h3>$<?php echo number_format($totalSpent, 0); ?></h3>
            <p>Total Spent</p>
            <span class="stat-trend">
                <span class="material-symbols-rounded">account_balance_wallet</span>
                Invested
            </span>
        </div>
    </div>
</section>

<!-- Main Content Grid -->
<div class="dashboard-grid">
    <!-- Active Bookings Section -->
    <section class="dashboard-section active-bookings">
        <div class="section-header">
            <div>
                <h2>
                    <span class="material-symbols-rounded">bookmark</span>
                    Active Bookings
                </h2>
                <p class="section-subtitle">Your pending vehicle reservations</p>
            </div>
            <?php if (!empty($activeBookingsList)): ?>
                <a href="<?php echo getBasePath(); ?>/customer/bookings" class="view-all-link">
                    View All
                    <span class="material-symbols-rounded">arrow_forward</span>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($activeBookingsList)): ?>
            <div class="bookings-list">
                <?php foreach (array_slice($activeBookingsList, 0, 3) as $booking): ?>
                    <div class="booking-item">
                        <div class="booking-image-wrapper">
                            <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($booking['image'] ?? '/assets/images/default-car.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?>"
                                 style="object-fit:contain;">
                            <span class="booking-badge pending">Pending</span>
                        </div>
                        <div class="booking-info">
                            <h3><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></h3>
                            <p class="booking-meta">
                                <span class="meta-item">
                                    <span class="material-symbols-rounded">calendar_today</span>
                                    <?php echo htmlspecialchars($booking['year']); ?>
                                </span>
                                <span class="meta-item">
                                    <span class="material-symbols-rounded">schedule</span>
                                    <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?>
                                </span>
                            </p>
                        </div>
                        <div class="booking-price">
                            <span class="price-label">Price</span>
                            <span class="price-value">$<?php echo number_format($booking['price'], 2); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <span class="material-symbols-rounded">event_busy</span>
                <h3>No Active Bookings</h3>
                <p>You don't have any pending bookings at the moment</p>
                <a href="<?php echo getBasePath(); ?>/collection" class="btn-main">Browse Collection</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Recent Activity Section -->
    <section class="dashboard-section recent-activity">
        <div class="section-header">
            <div>
                <h2>
                    <span class="material-symbols-rounded">history</span>
                    Recent Activity
                </h2>
                <p class="section-subtitle">Your latest booking history</p>
            </div>
        </div>

        <?php if (!empty($bookingHistory)): ?>
            <div class="activity-timeline">
                <?php foreach (array_slice($bookingHistory, 0, 5) as $booking): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker status-<?php echo strtolower($booking['status']); ?>">
                            <span class="material-symbols-rounded">
                                <?php 
                                    echo match($booking['status']) {
                                        'sold' => 'check_circle',
                                        'cancelled' => 'cancel',
                                        default => 'pending'
                                    };
                                ?>
                            </span>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h4><?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?></h4>
                                <span class="timeline-status status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </div>
                            <p class="timeline-meta">
                                <?php echo date('F d, Y', strtotime($booking['booking_date'])); ?> • 
                                $<?php echo number_format($booking['price'], 2); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state-small">
                <span class="material-symbols-rounded">history</span>
                <p>No booking history yet</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<!-- Featured Collection -->
<?php if (!empty($featuredCars)): ?>
<section class="dashboard-section featured-collection">
    <div class="section-header">
        <div>
            <h2>
                <span class="material-symbols-rounded">star</span>
                Featured Collection
            </h2>
            <p class="section-subtitle">Handpicked vintage automobiles just for you</p>
        </div>
        <a href="<?php echo getBasePath(); ?>/collection" class="view-all-link">
            View All
            <span class="material-symbols-rounded">arrow_forward</span>
        </a>
    </div>

    <div class="featured-grid">
        <?php foreach (array_slice($featuredCars, 0, 6) as $car): ?>
            <div class="featured-card">
                <div class="featured-image">
                    <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($car['image'] ?? '/assets/images/default-car.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>"
                         style="object-fit:contain;">
                    <div class="featured-overlay">
                        <button class="icon-btn wishlist-btn" title="Add to Wishlist">
                            <span class="material-symbols-rounded">favorite_border</span>
                        </button>
                    </div>
                    <span class="featured-badge">Available</span>
                </div>
                <div class="featured-content">
                    <div class="featured-header">
                        <h3><?php echo htmlspecialchars($car['brand']); ?></h3>
                        <span class="featured-year"><?php echo htmlspecialchars($car['year']); ?></span>
                    </div>
                    <p class="featured-model"><?php echo htmlspecialchars($car['model']); ?></p>
                    <div class="featured-footer">
                        <span class="featured-price">$<?php echo number_format($car['price'], 2); ?></span>
                        <button class="btn-main small" onclick="window.location.href='<?php echo getBasePath(); ?>/collection'">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Account Overview & Quick Actions -->
<div class="dashboard-grid-2">
    <!-- Account Summary -->
    <section class="dashboard-section account-summary">
        <div class="section-header">
            <h2>
                <span class="material-symbols-rounded">account_circle</span>
                Account Overview
            </h2>
        </div>
        
        <div class="summary-content">
            <div class="summary-item">
                <div class="summary-icon">
                    <span class="material-symbols-rounded">badge</span>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Full Name</span>
                    <span class="summary-value"><?php echo htmlspecialchars($customerName); ?></span>
                </div>
            </div>
            
            <div class="summary-item">
                <div class="summary-icon">
                    <span class="material-symbols-rounded">mail</span>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Email Address</span>
                    <span class="summary-value"><?php echo htmlspecialchars($customerEmail); ?></span>
                </div>
            </div>
            
            <div class="summary-item">
                <div class="summary-icon">
                    <span class="material-symbols-rounded">calendar_month</span>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Member Since</span>
                    <span class="summary-value"><?php echo date('F Y', strtotime($memberSince)); ?></span>
                </div>
            </div>
            
            <div class="summary-item">
                <div class="summary-icon">
                    <span class="material-symbols-rounded">verified</span>
                </div>
                <div class="summary-details">
                    <span class="summary-label">Account Status</span>
                    <span class="summary-value status-active">Active</span>
                </div>
            </div>
        </div>
        
        <div class="summary-actions">
            <a href="<?php echo getBasePath(); ?>/customer/profile" class="btn-secondary">
                <span class="material-symbols-rounded">edit</span>
                Edit Profile
            </a>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="dashboard-section quick-actions">
        <div class="section-header">
            <h2>
                <span class="material-symbols-rounded">bolt</span>
                Quick Actions
            </h2>
        </div>
        
        <div class="actions-grid">
            <a href="<?php echo getBasePath(); ?>/collection" class="action-card">
                <div class="action-icon">
                    <span class="material-symbols-rounded">search</span>
                </div>
                <div class="action-content">
                    <h3>Browse Cars</h3>
                    <p>Explore our vintage collection</p>
                </div>
                <span class="material-symbols-rounded action-arrow">arrow_forward</span>
            </a>
            
            <a href="<?php echo getBasePath(); ?>/customer/bookings" class="action-card">
                <div class="action-icon">
                    <span class="material-symbols-rounded">receipt_long</span>
                </div>
                <div class="action-content">
                    <h3>View Bookings</h3>
                    <p>Manage your reservations</p>
                </div>
                <span class="material-symbols-rounded action-arrow">arrow_forward</span>
            </a>
            
            <a href="<?php echo getBasePath(); ?>/contact" class="action-card">
                <div class="action-icon">
                    <span class="material-symbols-rounded">support_agent</span>
                </div>
                <div class="action-content">
                    <h3>Get Support</h3>
                    <p>Contact our team</p>
                </div>
                <span class="material-symbols-rounded action-arrow">arrow_forward</span>
            </a>
        </div>
    </section>
</div>

<!-- Tips & Recommendations -->
<section class="dashboard-section tips-section">
    <div class="section-header">
        <h2>
            <span class="material-symbols-rounded">lightbulb</span>
            Tips & Recommendations
        </h2>
    </div>
    
    <div class="tips-grid">
        <div class="tip-card">
            <div class="tip-icon">
                <span class="material-symbols-rounded">verified_user</span>
            </div>
            <div class="tip-content">
                <h3>Secure Your Booking</h3>
                <p>Complete your booking early to secure your dream vintage car before it's gone!</p>
            </div>
        </div>
        
        <div class="tip-card">
            <div class="tip-icon">
                <span class="material-symbols-rounded">workspace_premium</span>
            </div>
            <div class="tip-content">
                <h3>Authenticity Guaranteed</h3>
                <p>All our vehicles come with complete documentation and certification of authenticity.</p>
            </div>
        </div>
        
        <div class="tip-card">
            <div class="tip-icon">
                <span class="material-symbols-rounded">handshake</span>
            </div>
            <div class="tip-content">
                <h3>Expert Consultation</h3>
                <p>Schedule a call with our vintage car experts for personalized recommendations.</p>
            </div>
        </div>
    </div>
</section>