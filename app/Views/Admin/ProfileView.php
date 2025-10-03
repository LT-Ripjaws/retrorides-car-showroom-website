
<header class="topbar">
    <h1>Profile</h1>
    <div style="color: #666; font-size: 0.9rem;">
            Manage your profile
    </div>
</header>

<!-- toggles messages for errors/success -->
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

<!-- Profile Overview (read-only details) -->
<section class="profile-grid">
    <div class="profile-overview">
        <img src="<?php echo $baseUrl; ?>/assets/images/Admin/profile/default-avatar.png" 
                alt="Profile Picture" class="profile-pic">
        <h2><?php echo htmlspecialchars($admin['name']); ?></h2>
        <p><?php echo htmlspecialchars($admin['role']); ?> - <?php echo htmlspecialchars($admin['department']); ?></p>
        <p>Joined: <?php echo date("F j, Y", strtotime($admin['joined'])); ?></p>
        <p>Status: <strong><?php echo htmlspecialchars($admin['status']); ?></strong></p>
    </div>

    <!-- Editable personal info -->
    <div class="panel">
        <h3>Edit Personal Info</h3>
        <form action="<?php echo $baseUrl; ?>/admin/profile/update" method="POST">
            
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="employee_id" value="<?php echo (int)$admin['employee_id']; ?>">

            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($admin['phone']); ?>" placeholder="Optional">

            <button class="btn-main" type="submit">Save Changes</button>
        </form>
    </div>
</section>

<!-- Password change + static account info -->
<section class="profile-grid">
    <!-- Change password form -->
    <div class="panel">
        <h3>Change Password</h3>
        <form action="<?php echo $baseUrl; ?>/admin/profile/password" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label>Current Password</label>
            <input type="password" name="old_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" required minlength="8" 
                    placeholder="At least 8 characters">

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required minlength="8">

            <button class="btn-main" type="submit">Update Password</button>
        </form>
    </div>

    <!-- Read-only account metadata -->
    <div class="panel">
        <h3>Account Info</h3>
        <p><strong>Employee ID:</strong> <?php echo (int)$admin['employee_id']; ?></p>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($admin['department']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($admin['role']); ?></p>
        <p><strong>Account Created:</strong> <?php echo date('F j, Y g:i A', strtotime($admin['created_at'])); ?></p>
        <p><strong>Last Updated:</strong> <?php echo date('F j, Y g:i A', strtotime($admin['updated_at'])); ?></p>
    </div>
</section>