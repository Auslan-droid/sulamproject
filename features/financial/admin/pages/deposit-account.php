<?php
// Deposit Account Page
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();

// Instantiate Controller
$controller = new FinancialController($mysqli);
$data = $controller->depositAccount();
extract($data);

// Define page header
$pageHeader = [
    'title' => 'Akaun Terimaan',
    'subtitle' => 'Manage deposit records.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Financial', 'url' => url('financial')],
        ['label' => 'Akaun Terimaan', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('financial'), 'class' => 'btn-secondary'],
        ['label' => 'Add Deposit', 'icon' => 'fa-plus', 'url' => url('financial/deposit-account/add'), 'class' => 'btn-success'],
    ]
];

// 1. Capture the inner content
ob_start();
include __DIR__ . '/../views/deposit-account.php';
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Akaun Terimaan';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
