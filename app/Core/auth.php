<?php

/**
 * Authentication Helper Functions
 * Used throughout the application for user authentication and authorization
 * We could put it in a class itself but meh its fine
 */

/**
 * Sanitize user input
 */
function cleanInput(string $data): string 
{
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Set user session after successful login
 */
function setUserSession(int $id, string $name, string $role): void 
{
    session_regenerate_id(true);
    $_SESSION['user_id']   = $id;
    $_SESSION['user_name'] = $name;
    $_SESSION['role']      = $role;
}

/**
 * Redirect user based on role using new routing structure
 */
function roleRedirection(string $role): void 
{
    $basePath = getBasePath();
    
    $routes = [
        'admin'    => $basePath . '/admin/dashboard',
        'sales'    => $basePath . '/sales/dashboard', 
        'customer' => $basePath . '/',
    ];
    
    $target = $routes[$role] ?? $basePath . '/login?error=invalid_role';
    
    header("Location: $target");
    exit();
}

/**
 * This is used to generate CSRF token if not already set
 * and store it in session for form submissions
 */

function generateCSRFToken(): void 
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }


/**
 * Validate CSRF token to prevent cross-site request forgery
 */
    function validateCSRF(): bool 
{
    return isset($_POST['csrf_token']) && 
            hash_equals($_SESSION['csrf_token'], (string)$_POST['csrf_token']);
}


/**
 * Create and store remember-me token
 */
function setRememberMe(mysqli $conn, int $userId, string $role): void 
{
    try {
        $token     = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiry    = date("Y-m-d H:i:s", time() + (86400 * 30)); // 30 days

        $stmt = $conn->prepare("INSERT INTO remember_me (user_id, role, token, expires_at) 
                                 VALUES (?, ?, ?, ?)
                                 ON DUPLICATE KEY UPDATE 
                                 token = VALUES(token), 
                                 expires_at = VALUES(expires_at)");
        
        if (!$stmt) {
            error_log("Failed to prepare remember_me statement: " . $conn->error);
            return;
        }

        $stmt->bind_param("isss", $userId, $role, $tokenHash, $expiry);
        $stmt->execute();
        $stmt->close();

        // Set secure cookie
        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie(
            "remember_me", 
            $token, 
            [
                'expires'  => time() + (86400 * 30),
                'path'     => '/',
                'domain'   => '',
                'secure'   => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
        
    } catch (Exception $e) {
        error_log("Remember me token creation failed: " . $e->getMessage());
    }
}

/**
 * Auto login using remember-me token
 */
function autoLogin(mysqli $conn): void 
{
    // Skip if already logged in
    if (isset($_SESSION['user_id'])) {
        return; 
    }

    // Skip if no remember token
    if (!isset($_COOKIE['remember_me'])) {
        return;
    }

    try {
        $token     = $_COOKIE['remember_me'];
        $tokenHash = hash('sha256', $token);

        // Get remember token data
        $stmt = $conn->prepare("SELECT user_id, role, expires_at FROM remember_me WHERE token = ? LIMIT 1");
        if (!$stmt) {
            error_log("Failed to prepare auto-login statement: " . $conn->error);
            return;
        }

        $stmt->bind_param("s", $tokenHash);
        $stmt->execute();
        $tokenData = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Validate token
        if (!$tokenData || strtotime($tokenData['expires_at']) <= time()) {
            clearRememberToken($tokenHash, $conn);
            return;
        }

        // Get user data based on role
        $userData = getUserDataById((int)$tokenData['user_id'], $tokenData['role'], $conn);
        
        if ($userData) {
            setUserSession((int)$userData['id'], $userData['name'], $tokenData['role']);
            roleRedirection($tokenData['role']);
        } else {
            // User not found, clean up token
            clearRememberToken($tokenHash, $conn);
        }

    } catch (Exception $e) {
        error_log("Auto-login failed: " . $e->getMessage());
        // Clear potentially corrupted cookie
        setcookie("remember_me", "", time() - 3600, "/");
    }
}

/**
 * Get user data by ID and role
 */
function getUserDataById(int $userId, string $role, mysqli $conn): ?array 
{
    if ($role === "customer") {
        $stmt = $conn->prepare("SELECT id, username AS name, status FROM users WHERE id = ? LIMIT 1");
    } else {
        $stmt = $conn->prepare("SELECT employee_id AS id, name, status FROM employees WHERE employee_id = ? LIMIT 1");
    }
    
    if (!$stmt) {
        error_log("Failed to prepare user data statement: " . $conn->error);
        return null;
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Check if user is still active
    if ($result && $result['status'] !== 'active') {
        return null;
    }

    return $result;
}

/**
 * Clear remember token from database
 */
function clearRememberToken(string $tokenHash, mysqli $conn): void 
{
    $stmt = $conn->prepare("DELETE FROM remember_me WHERE token = ?");
    if ($stmt) {
        $stmt->bind_param("s", $tokenHash);
        $stmt->execute();
        $stmt->close();
    }
    
    // Clear cookie
    setcookie("remember_me", "", time() - 3600, "/");
}

/**
 * Require user to be logged in
 */
function requireLogin(): void 
{
    if (!isset($_SESSION['user_id'])) {
        $basePath = getBasePath();
        header("Location: {$basePath}/login?error=login_required");
        exit();
    }
}

/**
 * Require user to have specific role
 */
function requireRole(string $role): void 
{
    requireLogin();
    
    if ($_SESSION['role'] !== $role) {
        $basePath = getBasePath();
        header("Location: {$basePath}/login?error=unauthorized");
        exit();
    }
}

/**
 * Logout user and clear all session data
 */
function logout(mysqli $conn): void 
{
    // Clear remember token if exists
    if (isset($_COOKIE['remember_me'])) {
        $token     = $_COOKIE['remember_me'];
        $tokenHash = hash('sha256', $token);
        clearRememberToken($tokenHash, $conn);
    }

    // Clear all session data
    $_SESSION = [];
    
    // Destroy session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params["path"], 
            $params["domain"],
            $params["secure"], 
            $params["httponly"]
        );
    }

    session_destroy();
    
    $basePath = getBasePath();
    header("Location: {$basePath}/login?message=logged_out");
    exit();
}