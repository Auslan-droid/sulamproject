<?php
// Payment Account Page
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();

// Instantiate Controller
$controller = new FinancialController($mysqli);
$data = $controller->paymentAccount();
extract($data);

// Define page header
$pageHeader = [
    'title' => 'Akaun Bayaran (Payment Account)',
    'subtitle' => 'Manage payment records.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Financial', 'url' => url('financial')],
        ['label' => 'Akaun Bayaran', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('financial'), 'class' => 'btn-secondary'],
        ['label' => 'Add Payment', 'icon' => 'fa-plus', 'url' => url('financial/payment-account/add'), 'class' => 'btn-primary'],
    ]
];

// 1. Capture the inner content
ob_start();
include __DIR__ . '/../views/payment-account.php';
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Akaun Bayaran';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
