
<!-- Page Header -->
<header class="topbar">
    <h1>Team Management</h1>
    <button class="btn-main admin-add-btn">+ Add Employee</button>
</header>

<!-- Success/Error Messages -->
<?php if (!empty($error)): ?>
    <div class="error-box">
        <p><?php echo htmlspecialchars($error); ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="success-box">
        <p><?php echo htmlspecialchars($success); ?></p>
    </div>
<?php endif; ?>

<!-- Search & Filter Section -->
<section class="search admin-filters">
    <input type="text" id="adminSearchBar" placeholder="Search employees by name, email or phone" class="search-bar">

    <!-- Filter by Department -->
    <select id="departmentFilter" class="search">
        <option value="">All Departments</option>
        <option value="sales">Sales</option>
        <option value="mechanic">Mechanic</option>
        <option value="finance">Finance</option>
        <option value="admin">Administration</option>
    </select>

    <!-- Filter by Status -->
    <select id="statusFilter" class="search">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="leave">On Leave</option>
    </select>

    <button class="btn-main" id="adminSearchBtn">Search</button>
</section>

<!-- Employee List Table -->
<section class="employee-table admin-table">
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Role</th>
                <th>Department</th>
                <th>Joined</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($employees)): ?>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <!-- Employee Basic Info -->
                        <td>
                            <div class="employee-info">
                                <div>
                                    <span class="name"><?php echo htmlspecialchars($employee['name']); ?></span>
                                    <span class="email"><?php echo htmlspecialchars($employee['email']); ?></span>
                                    <span class="phone"><?php echo htmlspecialchars($employee['phone']); ?></span>
                                </div>
                            </div>
                        </td>

                        <!-- Role, Department, Joined Date -->
                        <td><?php echo htmlspecialchars($employee['role']); ?></td>
                        <td><?php echo htmlspecialchars($employee['department']); ?></td>
                        <td><?php echo date("M d, Y", strtotime($employee['joined'])); ?></td>

                        <!-- Employee Status -->
                        <td>
                            <span class="status <?php echo strtolower(htmlspecialchars($employee['status'])); ?>">
                                <?php echo htmlspecialchars($employee['status']); ?>
                            </span>
                        </td>

                        <!-- Actions: Edit / Delete -->
                        <td class="actions">
                            <!-- Edit Button with preloaded data -->
                            <button 
                                class="btn-main small admin-edit-btn"
                                data-emp-id="<?php echo (int)$employee['employee_id']; ?>"
                                data-emp-name="<?php echo htmlspecialchars($employee['name']); ?>"
                                data-emp-email="<?php echo htmlspecialchars($employee['email']); ?>"
                                data-emp-phone="<?php echo htmlspecialchars($employee['phone']); ?>"
                                data-emp-role="<?php echo htmlspecialchars($employee['role']); ?>"
                                data-emp-department="<?php echo htmlspecialchars($employee['department']); ?>"
                                data-emp-status="<?php echo htmlspecialchars($employee['status']); ?>"
                            >Edit</button>

                            <!-- Delete Form -->
                            <form action="<?php echo $baseUrl; ?>/admin/teams/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="employee_id" value="<?php echo (int)$employee['employee_id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <button type="submit" class="btn-main small danger"
                                        onclick="return confirm('Are you sure you want to delete this employee?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- No employees fallback -->
                <tr><td colspan="6">No employees yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<!-- Add/Edit Employee Modal -->
<div class="modal admin-modal">
    <div class="form-content">
        <h2 class="admin-modal-title" id="modalTitle">Add New Employee</h2>
        <form class="admin-form" id="employeeForm" 
              action="<?php echo $baseUrl; ?>/admin/teams/add" 
              method="POST" 
              data-add-action="<?= $baseUrl ?>/admin/teams/add"
              data-update-action="<?= $baseUrl ?>/admin/teams/update">

            <!-- Hidden Fields -->
            <input type="hidden" class="admin-id-input" name="employee_id" id="employee_id">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <!-- Name -->
            <label>Name</label>
            <input type="text" name="name" id="name" required placeholder="e.g. Charles Mclair">

            <!-- Email -->
            <label>Email</label>
            <input type="email" name="email" id="email" required placeholder="e.g. charles.mclr@example.com">

            <!-- Phone -->
            <label>Phone</label>
            <input type="tel" name="phone" id="phone" required placeholder="e.g. +1 555-123-4567">

            <!-- Role -->
            <label>Role</label>
            <select name="role" id="role" required>
                <option value="">-- Select Role --</option>
                <option value="sales">Sales</option>
                <option value="mechanic">Mechanic</option>
                <option value="support">Support</option>
                <option value="admin">Administration</option>
            </select>

            <!-- Department -->
            <label>Department</label>
            <select name="department" id="department" required>
                <option value="">-- Select Department --</option>
                <option value="sales">Sales</option>
                <option value="mechanic">Mechanic</option>
                <option value="finance">Finance</option>
                <option value="admin">Administration</option>
            </select>

            <!-- Status -->
            <label>Status</label>
            <select name="status" id="status" required>
                <option value="">-- Select Status --</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="leave">On Leave</option>
            </select>

            <!-- Form Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn-main">Save</button>
                <button type="button" class="btn-main admin-cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>
