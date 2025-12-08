<?php
// Financial Settings Page - Manage Opening Balances
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();
requireAdmin();

// Instantiate Controller
$controller = new FinancialController($mysqli);

// Handle form submission
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->saveFinancialSettings($_POST);
    $errors = $result['errors'] ?? [];
    $success = $result['success'] ?? false;
    
    if ($success) {
        // Redirect to prevent form resubmission
        header('Location: ' . url('financial/settings?saved=1'));
        exit;
    }
}

// Check for success message from redirect
if (isset($_GET['saved']) && $_GET['saved'] == '1') {
    $success = true;
}

$data = $controller->financialSettings();
extract($data);

// Define page header
$pageHeader = [
    'title' => 'Tetapan Kewangan (Financial Settings)',
    'subtitle' => 'Urus baki awal dan tetapan tahun kewangan.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Financial', 'url' => url('financial')],
        ['label' => 'Settings', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('financial'), 'class' => 'btn-secondary'],
    ]
];

// 1. Capture the inner content
ob_start();
include __DIR__ . '/../views/financial-settings.php';
$content = ob_get_clean();

// 2. Wrap with dashboard layout
$additionalStyles = [
    url('features/financial/admin/assets/css/financial-settings.css')
];

ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Financial Settings';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
