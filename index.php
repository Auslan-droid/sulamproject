<?php
/**
 * Front Controller
 * Central entry point for all requests
 */

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Auto-detect base path with Windows support
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $dir = dirname($scriptName);
    $dir = str_replace('\\', '/', $dir);
    define('APP_BASE_PATH', $dir === '/' ? '' : $dir);

    // Load router and routes (moved under features)
    $router = require_once __DIR__ . '/features/shared/lib/routes.php';

    // Get request method and URI
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    // Remove query string
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }

    // Decode URI
    $uri = rawurldecode($uri);

    // Remove base path if present
    if (APP_BASE_PATH !== '' && strpos($uri, APP_BASE_PATH) === 0) {
        $uri = substr($uri, strlen(APP_BASE_PATH));
    }

    // Ensure URI starts with /
    if (empty($uri) || $uri[0] !== '/') {
        $uri = '/' . $uri;
    }

    // Dispatch request
    $router->dispatch($method, $uri);

} catch (Throwable $e) {
    http_response_code(500);
    echo "<h1>Internal Server Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

