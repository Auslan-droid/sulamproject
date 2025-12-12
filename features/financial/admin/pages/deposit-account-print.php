<?php
/**
 * Deposit Account Print View Entry
 */
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();
requireAdmin();

$controller = new FinancialController($mysqli);

// Get the same data as the main view (which already filters based on GET params)
$data = $controller->depositAccount();

// Extract variables for the view
$deposits = $data['deposits'];
$categoryColumns = $data['categoryColumns'];
$categoryLabels = $data['categoryLabels'];
$totalCash = $data['totalCash'];
$totalBank = $data['totalBank'];

include __DIR__ . '/../views/deposit-account-print.php';
