<header class="topbar">
    <h1>Bookings Management</h1>
    <div style="color: #666; font-size: 0.9rem;">
            Monitor and manage bookings
    </div>
</header>

<!-- Error message (only shown if set) -->
<?php if (!empty($error)): ?>
    <div class="error-box">
        <p><?php echo htmlspecialchars($error); ?></p>
    </div>
<?php endif; ?>

<!-- Success message (only shown if set) -->
<?php if (!empty($success)): ?>
    <div class="success-box">
        <p><?php echo htmlspecialchars($success); ?></p>
    </div>
<?php endif; ?>

<!-- Main bookings table -->
<section class="table">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo (int)$booking['booking_id']; ?></td>
                            <td><?php echo htmlspecialchars($booking['brand'] . " " . $booking['model'] . " (" . $booking['year'] . ")"); ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars(date('M d, Y', strtotime($booking['booking_date']))); ?></td>
                            <td>
                                <!-- Status text  -->
                                <span class="status <?php echo strtolower(htmlspecialchars($booking['status'])); ?>">
                                    <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                </span>
                            </td>
                            <td class="actions">
                                    <!-- Cancel booking action -->
                                    <form action="<?php echo $baseUrl; ?>/admin/bookings/cancel" method="POST" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="booking_id" value="<?php echo (int)$booking['booking_id']; ?>">
                                        <button type="submit" class="btn-main danger small" 
                                                onclick="return confirm('Are you sure you want to cancel this booking?');">
                                            Cancel
                                        </button>
                                    </form> 
                                    
                                    <!-- Mark booking as sold -->
                                    <form action="<?php echo $baseUrl; ?>/admin/bookings/sold" method="POST" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="booking_id" value="<?php echo (int)$booking['booking_id']; ?>">
                                        <button type="submit" class="btn-main small"
                                                onclick="return confirm('Mark this booking as sold?');">
                                            Mark as Sold
                                        </button>
                                    </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- No data fallback -->
                    <tr><td colspan="7">No bookings yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>