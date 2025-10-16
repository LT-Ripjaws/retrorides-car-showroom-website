<section class="register-card">
    <div class="headings">
        <h1>Create Account</h1>
        <p>Join the ride today</p>
    </div>

    <!-- Error message display -->
    <?php if (!empty($error)): ?>
        <div class="error-box">
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    <?php endif; ?>

    <!-- Success message display -->
    <?php if (!empty($success)): ?>
        <div class="success-box">
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>

    <!-- Registration form -->
    <form method="POST" action="<?php echo $baseUrl; ?>/register">
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

        <!-- Username -->
        <div class="input-field">
            <div class="input-wrapper">
                <input type="text" 
                        name="username" 
                        placeholder="Enter a username (min 3 characters)" 
                        minlength="3" 
                        required
                        value="<?php echo $previousName; ?>">
            </div>
        </div>

        <!-- Email -->
        <div class="input-field">
            <div class="input-wrapper">
                <input type="email" 
                        name="email" 
                        placeholder="Enter a valid email" 
                        required
                        value="<?php echo $previousEmail; ?>">
             </div>
        </div>

        <!-- Password -->
        <div class="input-field">
            <div class="input-wrapper">
                <input type="password" 
                        id="regPassword"
                        name="password" 
                        placeholder="Password (at least 8 characters)" 
                        minlength="8" 
                        required>
                <button type="button" class="toggle-password" data-target="regPassword">
                    <span class="material-symbols-rounded">visibility</span>
                </button>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="input-field">
            <div class="input-wrapper">
                <input type="password" 
                        id="regConfirmPassword"
                        name="confirm_password" 
                        placeholder="Re-enter your password" 
                        minlength="8" 
                        required>
                <button type="button" class="toggle-password" data-target="regConfirmPassword">
                    <span class="material-symbols-rounded">visibility</span>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-main" name="register">Register</button>
    </form>

    <!-- Link to login -->
    <p class="signup-link">Already have an account? <a href="<?php echo $baseUrl; ?>/login">Login</a></p>
</section>

