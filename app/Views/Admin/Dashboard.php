 <header class="topbar">
    <div>
        <h1>Dashboard</h1>
        <!-- Displays logged-in user -->
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></p>
    </div>
</header>

<!-- Summary KPIs -->
<section class="summarisation-cards">
    <div class="card"><h3>TOTAL USERS</h3><p><?php echo $usersCount; ?></p></div>
    <div class="card"><h3>TOTAL EMPLOYEES</h3><p><?php echo $employees; ?></p></div>
    <div class="card"><h3>CARS ADDED</h3><p><?php echo $carsCount; ?></p></div>
    <div class="card"><h3>BOOKINGS</h3><p><?php echo $bookings; ?></p></div>
    <div class="card"><h3>TOTAL REVENUE</h3><p>$<?php echo number_format($revenue, 2); ?></p></div>
</section>

<!-- Data visualisations -->
<section class="chart-grid">
    <div class="panel">
        <h4>Sales (Last 30 days)</h4>
        <!-- Chart.js chart gets data via dataset -->
        <canvas id="salesChart"
            data-labels='<?php echo json_encode($dateLabels); ?>'
            data-values='<?php echo json_encode($salesData); ?>'></canvas>
    </div>
    <div class="panel">
        <h4>Sales by Car Brand</h4>
        <canvas id="BrandChart"
            data-labels='<?php echo json_encode($brandLabels); ?>'
            data-values='<?php echo json_encode($brandData); ?>'></canvas>
    </div>
</section>

<!-- Latest activity tables -->
<section class="table-grid">
    <div class="panel">
        <h4>Recent Users</h4>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr></thead>
            <tbody>
            <?php if ($recentUsers): foreach ($recentUsers as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['id']); ?></td>
                    <td><?php echo htmlspecialchars($u['username']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['role']); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <!-- Empty state -->
                <tr><td colspan="4">No recent users</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h4>Recent Cars Added</h4>
        <table>
            <thead><tr><th>ID</th><th>Brand</th><th>Model</th><th>Year</th></tr></thead>
            <tbody>
            <?php if ($recentCars): foreach ($recentCars as $c): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['car_id']); ?></td>
                    <td><?php echo htmlspecialchars($c['brand']); ?></td>
                    <td><?php echo htmlspecialchars($c['model']); ?></td>
                    <td><?php echo htmlspecialchars($c['year']); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <!-- Empty state -->
                <tr><td colspan="4">No recent cars</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
