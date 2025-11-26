<?php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/auth/session.php';
initSecureSession();
requireAuth();

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';

$pageHeader = [
    'title' => 'Donations',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Donations', 'url' => null],
    ],
];

// 1. Capture the inner content
ob_start();
require $ROOT . '/features/donations/user/views/donations.php';
$content = ob_get_clean();

// 2. Wrap into app-layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Set page title and include base layout
$pageTitle = "Donations";
include $ROOT . '/features/shared/components/layouts/base.php';
