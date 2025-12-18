<?php
// Death & Funeral User Index Page
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/UserDeathsController.php';

initSecureSession();
requireAuth();

// Instantiate Controller
$controller = new UserDeathsController($mysqli, $ROOT, $_SESSION['user_id'] ?? null);

// Define page header
$pageHeader = [
    'title' => 'Death & Funeral',
    'subtitle' => 'Quick access to death notification and funeral management sections.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Death & Funeral', 'url' => null],
    ],
];

// Capture the inner content
ob_start();
include __DIR__ . '/../views/index.php';
$content = ob_get_clean();

// Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// Render with base layout
$pageTitle = 'Death & Funeral';
?>
<script src="<?php echo url('/features/death-funeral/user/assets/js/user-death-funeral.js'); ?>"></script>
<?php
include $ROOT . '/features/shared/components/layouts/base.php';
?>
