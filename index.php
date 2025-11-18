<?php
/**
 * Front Controller
 * Central entry point for all requests
 */

// Load router and routes (moved under features)
$router = require_once __DIR__ . '/features/shared/lib/routes.php';

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Remove base path if present (for subdirectory installations)
$basePath = '/sulamproject';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

// Remove script name from URI if present
if (strpos($uri, '/index.php') === 0) {
    $uri = substr($uri, 10) ?: '/';
}

// Dispatch request
$router->dispatch($method, $uri);
