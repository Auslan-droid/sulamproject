<?php
// Funeral Logistics Page (User)
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/UserDeathsController.php';

initSecureSession();
requireAuth();

// Instantiate Controller
$controller = new UserDeathsController($mysqli, $ROOT, $_SESSION['user_id'] ?? null);

// Get funeral logistics for user
$pageData = [
    'funeralLogistics' => $controller->getFuneralLogistics(),
];
extract($pageData);

// Define page header
$pageHeader = [
    'title' => 'Funeral Logistics',
    'subtitle' => 'View funeral logistics for your submitted notifications.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Death & Funeral', 'url' => url('death-funeral')],
        ['label' => 'Funeral Logistics', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('death-funeral'), 'class' => 'btn-secondary'],
    ]
];

// Capture the inner content
ob_start();
include __DIR__ . '/../views/logistics-tracking.php';
$content = ob_get_clean();

// Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// Render with base layout
$pageTitle = 'Funeral Logistics';
?>
<script src="<?php echo url('/features/death-funeral/user/assets/js/user-death-funeral.js'); ?>"></script>
<?php
include $ROOT . '/features/shared/components/layouts/base.php';
?>
