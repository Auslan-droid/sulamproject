<?php
// Verified Death Notifications Page (User)
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/UserDeathsController.php';

initSecureSession();
requireAuth();

// Instantiate Controller
$controller = new UserDeathsController($mysqli, $ROOT, $_SESSION['user_id'] ?? null);
// Parse filters (apply only to verified list)
$selectedYear = isset($_GET['year']) && $_GET['year'] !== '' ? (int) $_GET['year'] : null;
$selectedMonth = isset($_GET['month']) && $_GET['month'] !== '' ? (int) $_GET['month'] : null;

// Get verified notifications according to filters
$verifiedNotifications = $controller->getVerifiedByDate($selectedYear, $selectedMonth);

// Years for dropdown
$years = $controller->getVerifiedYears();

$pageData = [
    'verifiedNotifications' => $verifiedNotifications,
    'years' => $years,
    'selectedYear' => $selectedYear,
    'selectedMonth' => $selectedMonth,
];
extract($pageData);

// Define page header
$pageHeader = [
    'title' => 'Verified Death Notifications',
    'subtitle' => 'Community death notifications verified by administrators.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Death & Funeral', 'url' => url('death-funeral')],
        ['label' => 'Verified Death Notifications', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('death-funeral'), 'class' => 'btn-secondary'],
    ]
];

// Capture the inner content
ob_start();
include __DIR__ . '/../views/verified-notifications.php';
$content = ob_get_clean();

// Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// Render with base layout
$pageTitle = 'Verified Death Notifications';
?>
<script src="<?php echo url('/features/death-funeral/user/assets/js/user-death-funeral.js'); ?>"></script>
<?php
include $ROOT . '/features/shared/components/layouts/base.php';
?>
