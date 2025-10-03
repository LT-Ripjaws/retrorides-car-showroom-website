<?php
require_once __DIR__ . '/../app/bootstrap.php';
use App\Core\Router;

// Router instance
$router = new Router($container);
// Load routes
require BASE_PATH . '/app/Core/Routes.php';

// ---------------- Dispatch ----------------
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
