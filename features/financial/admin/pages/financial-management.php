<?php
// Financial Management Page
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();

// Instantiate Controller
$controller = new FinancialController($mysqli);
$data = $controller->index();
extract($data);

// Define page header
$pageHeader = [
    'title' => 'Financial Management',
    'subtitle' => 'Manage financial records and assistance.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Financial', 'url' => null],
    ],
    'actions' => [
        // Add actions here later
    ]
];

// 1. Capture the inner content
ob_start();
include __DIR__ . '/../views/financial-management.php';
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Financial Management';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
