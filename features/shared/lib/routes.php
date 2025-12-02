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

// User Profile
$router->get('/profile', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/users/user/pages/profile.php';
});

$router->post('/profile', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    require $ROOT . '/features/users/user/pages/profile.php';
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
    requireAdmin();
    require $ROOT . '/features/residents/admin/pages/user-management.php';
});

$router->get('/families', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/residents/admin/pages/families.php';
});

$router->get('/financial', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/financial-management.php';
});

$router->get('/financial/payment-account', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/payment-account.php';
});

$router->get('/financial/deposit-account', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/deposit-account.php';
});

$router->get('/financial/cash-book', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/cash-book.php';
});

$router->get('/financial/payment-account/add', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/payment-add.php';
});

$router->post('/financial/payment-account/add', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/payment-add.php';
});

$router->get('/financial/payment-account/edit', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/payment-edit.php';
});

$router->post('/financial/payment-account/edit', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/payment-edit.php';
});

$router->post('/financial/payment-account/delete', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/ajax/payment-delete.php';
});

$router->get('/financial/deposit-account/add', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/deposit-add.php';
});

$router->post('/financial/deposit-account/add', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/deposit-add.php';
});

$router->get('/financial/deposit-account/edit', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/deposit-edit.php';
});

$router->post('/financial/deposit-account/edit', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/deposit-edit.php';
});

$router->post('/financial/deposit-account/delete', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/ajax/deposit-delete.php';
});

// Financial print routes
$router->get('/financial/receipt-print', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/receipt-print.php';
});

$router->get('/financial/voucher-print', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
    require $ROOT . '/features/financial/admin/pages/voucher-print.php';
});

$router->get('/donations', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    if (isAdmin()) {
        require $ROOT . '/features/donations/admin/pages/donations.php';
    } else {
        require $ROOT . '/features/donations/user/pages/donations.php';
    }
});
$router->post('/donations', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    if (isAdmin()) {
        require $ROOT . '/features/donations/admin/pages/donations.php';
    } else {
        // Users don't post to donations yet
        http_response_code(403);
        die('Access denied.');
    }
});

$router->get('/events', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    if (isAdmin()) {
        require $ROOT . '/features/events/admin/pages/events.php';
    } else {
        require $ROOT . '/features/events/user/pages/events.php';
    }
});
$router->post('/events', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    if (isAdmin()) {
        require $ROOT . '/features/events/admin/pages/events.php';
    } else {
        // Users don't post to events yet
        http_response_code(403);
        die('Access denied.');
    }
});

$router->get('/waris', function() use ($ROOT) {
    initSecureSession();
    requireAuth();
    requireAdmin();
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
