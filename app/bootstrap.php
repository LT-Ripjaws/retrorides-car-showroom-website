<?php
use App\Core\Container;

// Base project directory
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}

// Composer autoload
require_once BASE_PATH . '/vendor/autoload.php';

// secure session settings
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $secure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Start session if not already started (i didnt make a session manager core file cuz ehhh i'm just practicing, we can just use laravel for all the core boilerplates later.
//                                                                                                      for example req clas, response class, session_manager, etc.)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Get the base URL path for the application
 * This is useful for applications not hosted at the web server root.
 * 
 * @return string Base path (empty string for root installations)
 */
function getBasePath(): string 
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $basePath = dirname(dirname($scriptName));
    
    return ($basePath === '/' || $basePath === '.') ? '' : $basePath;
}


// Create container
$container = new Container();

// Load DB
$db = require BASE_PATH. '/config/database.php';

// Bind mysqli as singleton
$container->bind(mysqli::class, fn() => $db(), true);

// Authentication functions
require_once BASE_PATH . '/app/Core/auth.php';


return $container;

