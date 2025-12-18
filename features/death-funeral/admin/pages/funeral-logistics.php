<?php
// Funeral Logistics Page
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

// Get funeral logistics
$funeralLogistics = $controller->getFuneralLogistics();

// Define page header
$pageHeader = [
    'title' => 'Funeral Logistics',
    'subtitle' => 'View and manage funeral logistics for verified notifications.',
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
include __DIR__ . '/../views/funeral-logistics.php';
$content = ob_get_clean();

// Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// Render with base layout
$pageTitle = 'Funeral Logistics';
?>
<?php include $ROOT . '/features/death-funeral/admin/views/edit-logistics-modal.php'; ?>
<script src="<?php echo url('/features/death-funeral/admin/assets/js/admin-death-funeral.js'); ?>"></script>
<?php
include $ROOT . '/features/shared/components/layouts/base.php';
?>
