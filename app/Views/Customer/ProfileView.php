<header class="topbar">
    <div class="header-content">
        <div class="welcome-section">
            <h1>My Profile</h1>
            <p class="header-subtitle">Manage your account settings and preferences</p>
        </div>
    </div>
</header>

<section class="profile-content">
    
    <!-- Display Messages -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <span class="material-symbols-rounded">error</span>
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <span class="material-symbols-rounded">check_circle</span>
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>

    <div class="profile-grid">
        
        <!-- Profile Information Card-->
        <div class="profile-card">
            <div class="card-header">
                <span class="material-symbols-rounded">account_circle</span>
                <h2>Profile Information</h2>
            </div>
            
            <div class="profile-info">
                <div class="info-item">
                    <span class="info-label">Username</span>
                    <span class="info-value"><?php echo $user['username']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <span class="info-value"><?php echo $user['email']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Account Type</span>
                    <span class="info-value">
                        <span class="badge badge-<?php echo strtolower($user['role']); ?>">
                            <?php echo $user['role']; ?>
                        </span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Account Status</span>
                    <span class="info-value">
                        <span class="badge badge-<?php echo strtolower($user['status']); ?>">
                            <?php echo $user['status']; ?>
                        </span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value"><?php echo $user['member_since']; ?></span>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="profile-card">
            <div class="card-header">
                <span class="material-symbols-rounded">edit</span>
                <h2>Edit Profile</h2>
            </div>
            
            <form method="POST" 
                  action="<?php echo $baseUrl; ?>/customer/profile/update" 
                  class="profile-form"
                  id="profileForm">
                
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <p class="form-description">Update your username and email address</p>
                
                <!-- Username Field -->
                <div class="form-group">
                    <label for="username">
                        Username <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="material-symbols-rounded input-icon">person</span>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="<?php echo $this->old('username', $user['username']); ?>"
                               placeholder="Enter your username"
                               required
                               minlength="2">
                    </div>
                    <span class="field-hint">Minimum 2 characters</span>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">
                        Email Address <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="material-symbols-rounded input-icon">email</span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo $this->old('email', $user['email']); ?>"
                               placeholder="your@email.com"
                               required>
                    </div>
                    <span class="field-hint">Must be a valid email address</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    <span class="material-symbols-rounded">save</span>
                    <span>Update Profile</span>
                </button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="profile-card">
            <div class="card-header">
                <span class="material-symbols-rounded">lock</span>
                <h2>Change Password</h2>
            </div>
            
            <form method="POST" 
                  action="<?php echo $baseUrl; ?>/customer/profile/password" 
                  class="profile-form"
                  id="passwordForm">
                
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <p class="form-description">Ensure your account stays secure</p>
                
                <!-- Current Password -->
                <div class="form-group">
                    <label for="current_password">
                        Current Password <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="material-symbols-rounded input-icon">lock</span>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               placeholder="Enter current password"
                               required>
                        <button type="button" class="toggle-password" data-target="current_password">
                            <span class="material-symbols-rounded">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label for="new_password">
                        New Password <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="material-symbols-rounded input-icon">key</span>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               placeholder="Enter new password"
                               required
                               minlength="8">
                        <button type="button" class="toggle-password" data-target="new_password">
                            <span class="material-symbols-rounded">visibility</span>
                        </button>
                    </div>
                    <span class="field-hint">Minimum 8 characters</span>
                </div>

                <!-- Confirm New Password -->
                <div class="form-group">
                    <label for="confirm_password">
                        Confirm New Password <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="material-symbols-rounded input-icon">key</span>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               placeholder="Confirm new password"
                               required
                               minlength="8">
                        <button type="button" class="toggle-password" data-target="confirm_password">
                            <span class="material-symbols-rounded">visibility</span>
                        </button>
                    </div>
                    <span class="field-hint">Must match new password</span>
                </div>

                <div class="password-requirements">
                    <p><strong>Password Requirements:</strong></p>
                    <ul>
                        <li id="req-length">
                            <span class="material-symbols-rounded">circle</span>
                            At least 8 characters long
                        </li>
                        <li id="req-match">
                            <span class="material-symbols-rounded">circle</span>
                            Passwords must match
                        </li>
                    </ul>
                </div>

                <button type="submit" class="btn-submit">
                    <span class="material-symbols-rounded">security</span>
                    <span>Change Password</span>
                </button>
            </form>
        </div>

    </div>
</section>

<?php 
$this->clearOldInput(); 
?>