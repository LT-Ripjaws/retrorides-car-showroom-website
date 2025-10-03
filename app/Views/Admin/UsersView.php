 <header class="topbar">
    <h1>User Management</h1>
    <div style="color: #666; font-size: 0.9rem;">
        Monitor and manage user accounts
    </div>
</header>

<!-- Status Messages -->
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

<!-- Search & Filter -->
<section class="search admin-filters">
    <input type="text" id="adminSearchBar" placeholder="Search by username or email...">
    
    <select id="roleFilter">
        <option value="">All Roles</option>
        <option value="customer">Customer</option>
        <option value="premium">Premium</option>
        <option value="vip">VIP</option>
    </select>

    <select id="statusFilter">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>

    <button class="btn-main" id="adminSearchBtn">Search</button>
</section>

<!-- Users Table -->
<section class="employee-table admin-table">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User Info</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="employee-info">
                                <div>
                                    <div class="name"><?php echo htmlspecialchars($user['username']); ?></div>
                                    <span class="email"><?php echo htmlspecialchars($user['email']); ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status <?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span>
                        </td>
                        <td>
                            <span class="status <?php echo $user['status']; ?>"><?php echo ucfirst($user['status']); ?></span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                        <td><?php echo date('M j, Y', strtotime($user['updated_at'])); ?></td>
                        <td>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>

                                <!-- Role Update Form -->
                                    <section class="search">
                                    <form action="<?php echo $baseUrl; ?>/admin/users/update-role" method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <select name="role" onchange="this.form.submit()" style="padding: 0.3rem; font-size: 0.8rem; border-radius: 4px;">
                                            <option value="customer" <?php echo $user['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                            <option value="premium" <?php echo $user['role'] === 'premium' ? 'selected' : ''; ?>>Premium</option>
                                            <option value="vip" <?php echo $user['role'] === 'vip' ? 'selected' : ''; ?>>VIP</option>
                                        </select>
                                    </form>
                                    </section>

                                <!-- Status Toggle Button -->
                                <form action="<?php echo $baseUrl; ?>/admin/users/update-status" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <button 
                                        type="submit" 
                                        class="btn-main small <?php echo $user['status'] === 'active' ? 'warning' : ''; ?>"
                                        onclick="return confirm('Are you sure you want to <?php echo $user['status'] === 'active' ? 'deactivate' : 'activate'; ?> this user?')">
                                        <?php echo $user['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                    </button>
                                </form>

                                <!-- Delete Button -->
                                <form action="<?php echo $baseUrl; ?>/admin/users/delete" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <button 
                                        type="submit" 
                                        class="btn-main danger small" 
                                        onclick="return confirm('Are you sure you want to remove this user? This will deactivate their account and preserve their booking history.')">
                                        Remove
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color: #666; font-size: 0.85rem;">Your account</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align: center; padding: 2rem; color: #666;">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>