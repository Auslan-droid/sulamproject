<?php
/**
 * Cash Book Print View Entry
 */

require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/FinancialController.php';

initSecureSession();
requireAuth();
requireAdmin();

$controller = new FinancialController($mysqli);

// Get filters from request
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$month = isset($_GET['month']) && $_GET['month'] !== 'all' ? (int)$_GET['month'] : null;

// Use the same data method as the main view
$data = $controller->cashBook($year, $month);

// Extract variables for the view
$transactions = $data['transactions'];
$tunaiBalance = $data['currentCashBalance']; // Note: matching the return keys from controller
$bankBalance = $data['currentBankBalance'];
$openingCash = $data['openingCash'];
$openingBank = $data['openingBank'];
$fiscalYear = $data['fiscalYear'];
$month = $data['month'];

include __DIR__ . '/../views/cash-book-print.php';
