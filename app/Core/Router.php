<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }
 
    public function get(string $path, $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch(string $uri, string $method)
{
    $path = parse_url($uri, PHP_URL_PATH);
    
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $basePath = dirname(dirname($scriptName));
    
    if ($basePath !== '/' && strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }
    
    $path = rtrim($path, '/') ?: '/';
    
    if ($path === '/index.php') {
        $path = '/';
    }

    $callback = $this->routes[$method][$path] ?? null;

    if (!$callback) {
        http_response_code(404);
        echo "404 - Page not found";
        return;
    }

    // Handle array format [Controller::class, 'method']
    if (is_array($callback)) {
        [$class, $methodName] = $callback; 
        $controller = $this->container->make($class);
        call_user_func([$controller, $methodName]);
        return;
    }

    // Handle closure
    if (is_callable($callback)) {
        call_user_func($callback);
        return;
    }

    throw new \Exception("Invalid route callback for $path");
}
}