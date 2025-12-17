<?php
// Record Death Notification Page
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

// Get all notifications
$items = $controller->getAll();

// Handle POST submission (if any)
$message = '';
$messageClass = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleCreate();
    $message = $_SESSION['message'] ?? '';
    $messageClass = $_SESSION['messageClass'] ?? '';
    unset($_SESSION['message'], $_SESSION['messageClass']);
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

// Define page header
$pageHeader = [
    'title' => 'Record Death Notification',
    'subtitle' => 'Submit a new death notification.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Death & Funeral', 'url' => url('death-funeral')],
        ['label' => 'Record Death Notification', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('death-funeral'), 'class' => 'btn-secondary'],
    ]
];

// Capture the inner content
ob_start();
?>
<?php if ($message): ?>
    <div class="alert <?php echo htmlspecialchars($messageClass); ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/../views/manage-notifications.php'; ?>
<?php
$content = ob_get_clean();

// Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// Render with base layout
$pageTitle = 'Record Death Notification';
?>
<script src="<?php echo url('/features/death-funeral/admin/assets/js/admin-death-funeral.js'); ?>"></script>
<?php
include $ROOT . '/features/shared/components/layouts/base.php';
?>
