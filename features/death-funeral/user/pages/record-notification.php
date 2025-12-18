<?php
// Record Death Notification Page (User)
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/UserDeathsController.php';

initSecureSession();
requireAuth();

// Instantiate Controller
$controller = new UserDeathsController($mysqli, $ROOT, $_SESSION['user_id'] ?? null);

// Handle POST submission (if any)
$message = '';
$messageClass = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->handleCreate();
    $message = $result['message'] ?? '';
    $messageClass = $result['messageClass'] ?? '';
    
    // Redirect to prevent form resubmission
    if ($result['success']) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// Define page header
$pageHeader = [
    'title' => 'Record Death Notification',
    'subtitle' => 'Submit a death notification.',
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
<?php include __DIR__ . '/../views/record-notification.php'; ?>
<?php
$content = ob_get_clean();

// Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// Render with base layout
$pageTitle = 'Record Death Notification';
?>
<script src="<?php echo url('/features/death-funeral/user/assets/js/user-death-funeral.js'); ?>"></script>
<?php
include $ROOT . '/features/shared/components/layouts/base.php';
?>
