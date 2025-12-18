<?php
// Verify Death Page
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/AdminDeathsController.php';

initSecureSession();
requireAuth();
requireAdmin();

// Instantiate Controller
$controller = new AdminDeathsController($mysqli, $ROOT, $_SESSION['user_id'] ?? null);

// Parse filters for verified list (apply only to verified rows)
$selectedYear = isset($_GET['year']) && $_GET['year'] !== '' ? (int) $_GET['year'] : null;
$selectedMonth = isset($_GET['month']) && $_GET['month'] !== '' ? (int) $_GET['month'] : null;

// Get pending (unverified) items
$pending = $controller->getUnverified();

// Get verified items according to filters
$verified = $controller->getVerifiedByDate($selectedYear, $selectedMonth);

// Years list for filter dropdown
$years = $controller->getVerifiedYears();

// Provide arrays to view
$pageData = [
    'pending' => $pending,
    'verified' => $verified,
    'years' => $years,
    'selectedYear' => $selectedYear,
    'selectedMonth' => $selectedMonth,
];
extract($pageData);

// Define page header
$pageHeader = [
    'title' => 'Verify Death',
    'subtitle' => 'Verify reported death notifications.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Death & Funeral', 'url' => url('death-funeral')],
        ['label' => 'Verify Death', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('death-funeral'), 'class' => 'btn-secondary'],
    ]
];

// Capture the inner content
ob_start();
include __DIR__ . '/../views/verify-death.php';
$content = ob_get_clean();

// Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// Render with base layout
$pageTitle = 'Verify Death';
?>
<script src="<?php echo url('/features/death-funeral/admin/assets/js/admin-death-funeral.js'); ?>"></script>
<?php
include $ROOT . '/features/shared/components/layouts/base.php';
?>
