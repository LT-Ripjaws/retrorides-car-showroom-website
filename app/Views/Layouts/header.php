<?php
// we get the current route for active navigation highlighting
$current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$baseUrl = getBasePath();
$current_route = str_replace($baseUrl, '', $current_uri);
$current_route = trim($current_route, '/');

// Function to check if current route matches nav item
if (!function_exists('isActiveRoute')) {
    function isActiveRoute($route, $currentRoute) {
        if ($route === '' && ($currentRoute === '' || $currentRoute === '/')) {
            return true;
        }
        return $currentRoute === $route;
    }
}
?>

<!-- Navigation Header -->
<header>
    <nav class="navbar">
        <div class="logo-section">
            <a href="<?php echo $baseUrl; ?>/">
                <img src="<?php echo $baseUrl; ?>/assets/images/logo.png" class="logo" alt="RetroRides Logo">
                RetroRides
            </a>
        </div>

        <!-- Navigation Links -->
        <ul class="nav-links">
            <li>
                <a href="<?php echo $baseUrl; ?>/" 
                   class="<?php echo isActiveRoute('', $current_route) ? 'active' : ''; ?>">
                    Home
                </a>
            </li>
            <li>
                <a href="<?php echo $baseUrl; ?>/collection" 
                   class="<?php echo isActiveRoute('collection', $current_route) ? 'active' : ''; ?>">
                    Collection
                </a>
            </li>
            <li>
                <a href="<?php echo $baseUrl; ?>/about" 
                   class="<?php echo isActiveRoute('about', $current_route) ? 'active' : ''; ?>">
                    About
                </a>
            </li>
            <li>
                <a href="<?php echo $baseUrl; ?>/contact" 
                   class="<?php echo isActiveRoute('contact', $current_route) ? 'active' : ''; ?>">
                    Contact Us
                </a>
            </li>
            
            <!-- Mobile Login Button (inside nav-links for mobile) -->
            <li class="mobile-nav-btn">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User is logged in -->
                    <div class="user-menu">
                        <span class="user-greeting">Welcome</span>
                        
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="<?php echo $baseUrl; ?>/admin/dashboard">
                                <button class="btn-main small">
                                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                                </button>
                            </a>
                        <?php elseif ($_SESSION['role'] === 'customer'): ?>
                            <a href="<?php echo $baseUrl; ?>/customer/dashboard">
                                <button class="btn-main small">
                                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                                </button>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- User is not logged in -->
                    <a href="<?php echo $baseUrl; ?>/login">
                        <button class="btn-main">Login</button>
                    </a>
                <?php endif; ?>
            </li>
        </ul>

        <!-- Desktop User Account Section -->
        <div class="nav-btn desktop-nav-btn">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User is logged in -->
                <div class="user-menu">
                    <span class="user-greeting">Welcome</span>
                    
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo $baseUrl; ?>/admin/dashboard">
                            <button class="btn-main small">
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            </button>
                        </a>
                    <?php elseif ($_SESSION['role'] === 'customer'): ?>
                        <a href="<?php echo $baseUrl; ?>/customer/dashboard">
                            <button class="btn-main small">
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            </button>
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- User is not logged in -->
                <a href="<?php echo $baseUrl; ?>/login">
                    <button class="btn-main">Login</button>
                </a>
            <?php endif; ?>
        </div>

        <!-- Mobile Hamburger Menu -->
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>

<script>
    // Mobile menu toggle
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.querySelector('.nav-links');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navLinks.classList.toggle('open');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.navbar') && navLinks.classList.contains('open')) {
            hamburger.classList.remove('active');
            navLinks.classList.remove('open');
        }
    });

    // Close menu on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 991) {
            hamburger.classList.remove('active');
            navLinks.classList.remove('open');
        }
    });
</script>