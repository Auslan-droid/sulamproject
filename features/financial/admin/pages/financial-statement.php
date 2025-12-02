<?php
/**
 * Financial Statement Page (Penyata Terimaan dan Bayaran)
 * 
 * Landing page to select date range and generate the financial statement.
 */
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';

initSecureSession();
requireAuth();
requireAdmin();

// Default dates
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

// Define page header
$pageHeader = [
    'title' => 'Penyata Kewangan (Financial Statement)',
    'subtitle' => 'Generate income and expense summary reports for any period.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Financial', 'url' => url('financial')],
        ['label' => 'Penyata Kewangan', 'url' => null],
    ]
];

// 1. Capture the inner content
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h3>Generate Financial Statement</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo url('financial/statement-print'); ?>" method="GET" target="_blank" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate; ?>" required>
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate; ?>" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Generate Statement
                </button>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Note:</strong> The Financial Statement (Penyata Terimaan dan Bayaran) will be generated in a new tab formatted for printing (Lampiran 9).
</div>
<?php
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Penyata Kewangan';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
