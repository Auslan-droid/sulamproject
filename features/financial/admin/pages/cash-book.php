<?php
// Cash Book Page
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();
requireAdmin(); // Assuming only admins/treasurers access this

// Instantiate Controller
$controller = new FinancialController($mysqli);
$data = $controller->cashBook();

extract($data);

// Define page header
$pageHeader = [
    'title' => 'Buku Tunai (Cash Book)',
    'subtitle' => 'View all financial transactions and running balances.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Financial', 'url' => url('financial')],
        ['label' => 'Buku Tunai', 'url' => null],
    ],
    'actions' => [
        // We can add "Print Statement" here later
    ]
];

// 1. Capture the inner content
ob_start();
include __DIR__ . '/../views/cash-book.php';
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Buku Tunai';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
