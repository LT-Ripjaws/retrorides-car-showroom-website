 <header class="topbar">
    <h1>Car Management</h1>
    <!-- Opens modal for adding cars -->
    <button class="btn-main admin-add-btn">+ Add Car</button>
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

<!-- Search & filter controls for cars -->
<section class="search admin-filters">
    <input type="text" id="searchBar" placeholder="Search by Model, Brand...">
    
    <select id="brandFilter">
        <option value="">All Brands</option>
        <option value="mercedes-benz">Mercedes</option>
        <option value="ford">Ford</option>
        <option value="bmw">BMW</option>
    </select>

    <select id="statusFilter">
        <option value="">Status</option>
        <option value="available">Available</option>
        <option value="sold">Sold</option>
        <option value="maintenance">Maintenance</option>
    </select>

    <button class="btn-main" id="adminSearchBtn">Search</button>
</section>

<!-- Cars table -->
<section class="table admin-table">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>VIN</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cars)): ?>
                    <?php foreach ($cars as $car): ?>
                    <tr>
                        <!-- Car image preview -->
                        <td>
                            <?php if (!empty($car['image'])): ?>
                                <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($car['image']); ?>" width="80" height="60" style="object-fit:contain;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td class="vin"><?php echo htmlspecialchars($car['vin']); ?></td>
                        <td class="brand"><?php echo htmlspecialchars($car['brand']); ?></td>
                        <td class="model"><?php echo htmlspecialchars($car['model']); ?></td>
                        <td class="year"><?php echo (int) $car['year']; ?></td>
                        <td class="price"><?php echo '$' . number_format((float)$car['price'], 2); ?></td>
                        <td><span class="status <?php echo strtolower($car['status']); ?>"><?php echo htmlspecialchars($car['status']); ?></span></td>
                        <!-- Description shortened for table readability -->
                        <td class="description" title="<?php echo htmlspecialchars($car['description']); ?>">
                            <?php echo htmlspecialchars(mb_strimwidth($car['description'], 0, 50, '...')); ?>
                        </td>
                        <td>
                            <!-- Edit button passes car data via data-* attributes to JS script -->
                            <button 
                                class="btn-main small admin-edit-btn"
                                data-car-id="<?php echo (int)$car['car_id']; ?>"
                                data-vin="<?php echo htmlspecialchars($car['vin']); ?>"
                                data-brand="<?php echo htmlspecialchars($car['brand']); ?>"
                                data-model="<?php echo htmlspecialchars($car['model']); ?>"
                                data-year="<?php echo (int)$car['year']; ?>"
                                data-price="<?php echo (float)$car['price']; ?>"
                                data-status="<?php echo htmlspecialchars($car['status']); ?>"
                                data-desc="<?php echo htmlspecialchars($car['description']); ?>"
                                data-image="<?php echo htmlspecialchars($car['image']); ?>">
                                Edit
                            </button>
                            <!-- Delete action with CSRF protection + confirm -->
                            <form action="<?php echo $baseUrl; ?>/admin/cars/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="car_id" value="<?php echo (int)$car['car_id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <button type="submit" class="btn-main danger small" onclick="return confirm('Are you sure you want to delete this car?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- No data fallback -->
                    <tr><td colspan="9">No cars yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal (shared for Add & Edit car) -->
<div class="modal admin-modal">
    <div class="form-content">
        <h2 class="admin-modal-title" id="modalTitle">Add New Car</h2>
            <form class="admin-form" id="carForm" action="<?php echo $baseUrl; ?>/admin/cars/add" method="POST" enctype="multipart/form-data"
                data-add-action="<?= $baseUrl ?>/admin/cars/add"
                data-update-action="<?= $baseUrl ?>/admin/cars/update">
                
                <!-- CSRF protection -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <!-- Used only when editing -->
                <input type="hidden" class="admin-id-input" name="car_id" id="car_id">

                <label>Car Image</label>
                <!-- Current image preview (visible when editing) -->
                <div id="imagePreviewWrapper" style="margin-bottom:10px; display:none;">
                    <img id="currentImage" src="" alt="Current Car Image" width="120" style="object-fit:contain; border:1px solid #ccc; border-radius:6px;">
                </div>
                <input type="file" name="image" id="image" accept="image/*">

                <label>VIN</label>
                <input type="text" name="vin" id="vin" required>

                <label>Brand</label>
                <input type="text" name="brand" id="brand" required>

                <label>Model</label>
                <input type="text" name="model" id="model" required>

                <label>Year</label>
                <!-- Min year = first car invention, Max = next year -->
                <input type="number" name="year" id="year" min="1886" max="<?php echo date('Y') + 1; ?>">

                <label>Price</label>
                <input type="number" name="price" id="price" min="0" step="0.01">

                <label>Status</label>
                <select name="status" id="status">
                    <option value="available">Available</option>
                    <option value="sold">Sold</option>
                    <option value="maintenance">Maintenance</option>
                </select>

                <label>Description</label>
                <textarea name="desc" id="desc" rows="4" cols="50"></textarea>

                <div class="form-actions">
                    <button type="submit" class="btn-main">Save</button>
                    <button type="button" class="btn-main admin-cancel-btn" id="cancelBtn">Cancel</button>
                </div>
            </form>
    </div>
</div>

