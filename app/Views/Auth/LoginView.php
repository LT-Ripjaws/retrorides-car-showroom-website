<div class="login-block">
    <!-- Left side with rotating car images -->
    <div class="left-part">
        <div class="login-slides">
            <img src="<?php echo $baseUrl; ?>/assets/images/login/car1.jpg" alt="login image1">
            <img src="<?php echo $baseUrl; ?>/assets/images/login/car2.jpg" alt="login image2">
            <img src="<?php echo $baseUrl; ?>/assets/images/login/car3.jpg" alt="login image3">
            <img src="<?php echo $baseUrl; ?>/assets/images/login/car4.jpg" alt="login image4">
        </div>
    </div>

    <!-- Right side: Login form -->
    <div class="right-part">
        <h1>Welcome back Rider!</h1>
        <p>Sign in to your account</p>

        <!-- Error message display -->
        <?php if (!empty($error)): ?>
            <div class="error-box">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form action="login" method="post">
            <!-- CSRF protection -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <!-- Email input -->
                <div class="input-field">
                 <div class="input-wrapper">
                    <input type="email" 
                            name="username" 
                            placeholder="Enter your email" 
                            required 
                            value="<?php echo $previousEmail; ?>"
                            autocomplete="email">
                     <div style="color:red">*</div>
                    </div>  
                </div>

                <!-- Password input -->
                <div class="input-field">
                    <div class="input-wrapper">
                        <input type="password" 
                                id="loginPassword"
                                name="password" 
                                placeholder="Enter your password" 
                                required
                                autocomplete="current-password">
                        <button type="button" class="toggle-password">
                            <span class="material-symbols-rounded">visibility</span>
                        </button>
                        <div style="color:red">*</div>
                    </div>
                </div>

                <!-- Remember me checkbox -->
                <?php if ($showRememberMe): ?>
                    <div class="remember">
                        <input type="checkbox" 
                                class="remember-me" 
                                name="remember-me" 
                                id="remember-me"> 
                        <label for="remember-me">Remember Me?</label>
                    </div>
                    <br>
                <?php endif; ?>


            <!-- Submit button -->
            <button type="submit" class="btn-main" name="login">Login</button>
        </form>

        <!-- Link to registration -->
            <p class="signup-link">
                Don't have an account? 
                <a href="<?php echo $registerLink; ?>">Sign Up</a>
            </p>
    </div>
</div>