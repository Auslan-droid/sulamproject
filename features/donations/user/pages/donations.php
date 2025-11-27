<?php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/auth/session.php';

initSecureSession();
requireAuth();

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';

$pageHeader = [
    'title' => 'Donations',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Donations', 'url' => null],
    ],
];

// Fetch active donations uploaded by admin
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
$donations = [];
try {
    $stmt = $mysqli->prepare('SELECT id, title, description, image_path FROM donations WHERE is_active = 1 ORDER BY id DESC');
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
        }
        $stmt->close();
    }
} catch (Throwable $e) {
    // Non-fatal: surface a simple message in the view via variable
    $donationsError = 'Failed to load donations.';
}

// 1. Capture the inner content
ob_start();
require $ROOT . '/features/donations/user/views/donations.php';
$content = ob_get_clean();

// 2. Wrap into app-layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Set page title and include base layout
$pageTitle = "Donations";
include $ROOT . '/features/shared/components/layouts/base.php';
