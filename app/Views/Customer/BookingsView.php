<!-- Page Header -->
<header class="topbar">
    <div class="header-content">
        <div class="header-title">
            <h1>
                <span class="material-symbols-rounded">event_note</span>
                My Bookings
            </h1>
            <p class="header-subtitle">View and manage your vintage car reservations</p>
        </div>
    </div>
</header>

<!-- Bookings Statistics -->
<section class="bookings-stats">
    <div class="stat-item">
        <div class="stat-icon all">
            <span class="material-symbols-rounded">folder_open</span>
        </div>
        <div class="stat-details">
            <h3><?php echo $totalCount; ?></h3>
            <p>Total Bookings</p>
        </div>
    </div>

    <div class="stat-item">
        <div class="stat-icon pending">
            <span class="material-symbols-rounded">schedule</span>
        </div>
        <div class="stat-details">
            <h3><?php echo $pendingCount; ?></h3>
            <p>Processing</p>
        </div>
    </div>

    <div class="stat-item">
        <div class="stat-icon completed">
            <span class="material-symbols-rounded">check_circle</span>
        </div>
        <div class="stat-details">
            <h3><?php echo $soldCount; ?></h3>
            <p>Completed</p>
        </div>
    </div>

    <div class="stat-item">
        <div class="stat-icon cancelled">
            <span class="material-symbols-rounded">cancel</span>
        </div>
        <div class="stat-details">
            <h3><?php echo $cancelledCount; ?></h3>
            <p>Cancelled</p>
        </div>
    </div>
</section>

<!-- Filter Tabs -->
<section class="filter-section">
    <div class="filter-tabs">
        <a href="?status=all" class="filter-tab <?php echo $currentFilter === 'all' ? 'active' : ''; ?>">
            <span class="material-symbols-rounded">grid_view</span>
            All Bookings
            <span class="tab-count"><?php echo $totalCount; ?></span>
        </a>
        <a href="?status=processing" class="filter-tab <?php echo $currentFilter === 'processing' ? 'active' : ''; ?>">
            <span class="material-symbols-rounded">sync</span>
            Processing
        </a>
        <a href="?status=sold" class="filter-tab <?php echo $currentFilter === 'sold' ? 'active' : ''; ?>">
            <span class="material-symbols-rounded">done_all</span>
            Completed
            <span class="tab-count"><?php echo $soldCount; ?></span>
        </a>
        <a href="?status=cancelled" class="filter-tab <?php echo $currentFilter === 'cancelled' ? 'active' : ''; ?>">
            <span class="material-symbols-rounded">block</span>
            Cancelled
            <span class="tab-count"><?php echo $cancelledCount; ?></span>
        </a>
    </div>
</section>

<!-- Alert Messages -->
<?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <span class="material-symbols-rounded">error</span>
        <span><?php echo htmlspecialchars($error); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <span class="material-symbols-rounded">check_circle</span>
        <span><?php echo htmlspecialchars($success); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
<?php endif; ?>

<!-- Bookings List -->
<section class="bookings-section">
    <?php if (!empty($bookings)): ?>
        <div class="bookings-grid">
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card" data-booking-id="<?php echo $booking['booking_id']; ?>">
                    <!-- Booking Status Badge -->
                    <div class="booking-status-badge status-<?php echo strtolower($booking['status']); ?>">
                        <span class="material-symbols-rounded">
                            <?php 
                                echo match(strtolower($booking['status'])) {
                                    'sold' => 'check_circle',
                                    'cancelled' => 'cancel',
                                    'processing' => 'sync',
                                    default => 'schedule'
                                };
                            ?>
                        </span>
                        <?php echo ucfirst($booking['status']); ?>
                    </div>

                    <!-- Car Image -->
                    <div class="booking-card-image">
                        <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($booking['image'] ?? '/assets/images/default-car.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?>"
                             style="object-fit: contain;">
                        <div class="image-overlay">
                            <button class="view-details-btn" onclick="toggleBookingDetails(<?php echo $booking['booking_id']; ?>)">
                                <span class="material-symbols-rounded">info</span>
                            </button>
                        </div>
                    </div>

                    <!-- Booking Info -->
                    <div class="booking-card-content">
                        <div class="booking-header">
                            <h3><?php echo htmlspecialchars($booking['brand']); ?></h3>
                            <span class="booking-year"><?php echo htmlspecialchars($booking['year']); ?></span>
                        </div>
                        <p class="booking-model"><?php echo htmlspecialchars($booking['model']); ?></p>

                        <div class="booking-meta">
                            <div class="meta-item">
                                <span class="material-symbols-rounded">calendar_today</span>
                                <span>Booked: <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="material-symbols-rounded">confirmation_number</span>
                                <span>ID: #<?php echo str_pad($booking['booking_id'], 6, '0', STR_PAD_LEFT); ?></span>
                            </div>
                        </div>

                        <div class="booking-price">
                            <span class="price-label">Price</span>
                            <span class="price-value">$<?php echo number_format($booking['price'], 2); ?></span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="booking-actions">
                            <?php if (in_array(strtolower($booking['status']), ['pending', 'processing'])): ?>
                                <button class="btn-danger small" onclick="confirmCancelBooking(<?php echo $booking['booking_id']; ?>)">
                                    <span class="material-symbols-rounded">close</span>
                                    Cancel Booking
                                </button>
                            <?php else: ?>
                                <button class="btn-secondary small" disabled>
                                    <span class="material-symbols-rounded">block</span>
                                    <?php echo ucfirst($booking['status']); ?>
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn-outline small" onclick="toggleBookingDetails(<?php echo $booking['booking_id']; ?>)">
                                <span class="material-symbols-rounded">visibility</span>
                                Details
                            </button>
                        </div>
                    </div>

                    <!-- Expandable Details Section -->
                    <div class="booking-details-panel" id="details-<?php echo $booking['booking_id']; ?>">
                        <div class="details-header">
                            <h4>
                                <span class="material-symbols-rounded">info</span>
                                Booking Details
                            </h4>
                            <button class="close-details" onclick="toggleBookingDetails(<?php echo $booking['booking_id']; ?>)">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        
                        <div class="details-content">
                            <div class="detail-row">
                                <span class="detail-label">
                                    <span class="material-symbols-rounded">badge</span>
                                    VIN Number
                                </span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['vin'] ?? 'N/A'); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">
                                    <span class="material-symbols-rounded">person</span>
                                    Customer Name
                                </span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['customer_name']); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">
                                    <span class="material-symbols-rounded">mail</span>
                                    Email
                                </span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['customer_email']); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">
                                    <span class="material-symbols-rounded">event</span>
                                    Booking Date
                                </span>
                                <span class="detail-value"><?php echo date('F d, Y \a\t g:i A', strtotime($booking['booking_date'])); ?></span>
                            </div>
                            
                            <?php if (!empty($booking['description'])): ?>
                                <div class="detail-row full">
                                    <span class="detail-label">
                                        <span class="material-symbols-rounded">description</span>
                                        Car Description
                                    </span>
                                    <span class="detail-value"><?php echo htmlspecialchars($booking['description']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state-large">
            <div class="empty-icon">
                <span class="material-symbols-rounded">event_busy</span>
            </div>
            <h2>No Bookings Found</h2>
            <p>
                <?php 
                    if ($currentFilter === 'all') {
                        echo "You haven't made any bookings yet. Explore our vintage car collection to get started!";
                    } else {
                        echo "No bookings with status: " . htmlspecialchars($currentFilter);
                    }
                ?>
            </p>
            <a href="<?php echo getBasePath(); ?>/collection" class="btn-main">
                <span class="material-symbols-rounded">directions_car</span>
                Browse Collection
            </a>
        </div>
    <?php endif; ?>
</section>

<!-- Cancel Booking Modal -->
<div class="modal" id="cancelModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <span class="material-symbols-rounded">warning</span>
                Cancel Booking
            </h3>
            <button class="modal-close" onclick="closeCancelModal()">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to cancel this booking? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeCancelModal()">Keep Booking</button>
            <form id="cancelForm" method="POST" action="<?php echo getBasePath(); ?>/customer/bookings/cancel">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="booking_id" id="cancelBookingId">
                <button type="submit" class="btn-danger">
                    <span class="material-symbols-rounded">delete</span>
                    Yes, Cancel Booking
                </button>
            </form>
        </div>
    </div>
</div>