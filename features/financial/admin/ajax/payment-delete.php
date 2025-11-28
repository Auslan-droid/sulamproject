<?php
// Delete Payment Handler (AJAX/Form POST)
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();
requireAdmin();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/financial/payment-account');
    exit;
}

// Get ID from POST data
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    redirect('/financial/payment-account');
    exit;
}

// Instantiate Controller and delete
$controller = new FinancialController($mysqli);
$result = $controller->deletePayment($id);

// Redirect back to listing
redirect('/financial/payment-account');
exit;
