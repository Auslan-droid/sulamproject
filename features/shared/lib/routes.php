<?php
/**
 * Routes Configuration (moved under features)
 */

$ROOT = dirname(__DIR__, 3);
require_once $ROOT . '/features/shared/lib/utilities/Router.php';
require_once $ROOT . '/features/users/shared/controllers/AuthController.php';
require_once $ROOT . '/features/dashboard/admin/controllers/DashboardController.php';
require_once $ROOT . '/features/shared/lib/auth/session.php';

$router = new Router();

// Authentication routes (controller-based)
$router->get('/login', function() {
    $controller = new AuthController();
    $controller->showLogin();
});

$router->post('/login', function() {
    $controller = new AuthController();
    $controller->handleLogin();
});

$router->get('/register', function() {
    $controller = new AuthController();
    $controller->showRegister();
});

$router->post('/register', function() {
    $controller = new AuthController();
    $controller->handleRegister();
});

$router->get('/logout', function() use ($ROOT) {
    require_once $ROOT . '/features/shared/lib/auth/session.php';
    initSecureSession();
    destroySession();
    redirect('/login');
});

// Dashboard route
$router->get('/dashboard', function() use ($ROOT) {
    initSecureSession();
    requireAuth();

    // Use the existing controller-backed overview pages
    $controller = new DashboardController();
    // Preview override: allow forcing a specific view via query param for verification
    $as = $_GET['as'] ?? null;
    if ($as === 'admin') {
        $controller->showAdminDashboard();
        return;
    }
    if ($as === 'user') {
        $controller->showUserDashboard();
        return;
    }
    if (isAdmin()) {
        $controller->showAdminDashboard();
    } else {
        $controller->showUserDashboard();
    }
});

$router->get('/', function() {
    initSecureSession();
    if (isAuthenticated()) {
        redirect('/dashboard');
    } else {
        redirect('/login');
    }
});

// Feature pages (use new feature pages with full HTML + POST handling)
$router->get('/users', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/residents/admin/pages/user-management.php';
});

$router->get('/donations', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/donations/admin/pages/donations.php';
});
$router->post('/donations', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/donations/admin/pages/donations.php';
});

$router->get('/events', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/events/admin/pages/events.php';
});
$router->post('/events', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/events/admin/pages/events.php';
});

$router->get('/waris', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/users/waris/pages/waris.php';
});

$router->get('/admin', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/users/admin/pages/admin.php';
});

$router->get('/admin/user-edit', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/users/admin/pages/user_edit.php';
});

$router->get('/admin/waris', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/users/waris/admin/pages/waris-management.php';
});

return $router;
