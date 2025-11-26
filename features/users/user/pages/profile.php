<?php
// User Profile Page
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/ProfileController.php';

initSecureSession();
requireAuth();

$controller = new ProfileController($mysqli);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $controller->update();
} else {
    $data = $controller->edit();
}

extract($data); // Makes $user, $success, $error available to view

$pageHeader = [
    'title' => 'Edit Profile',
    'subtitle' => 'Update your personal information.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Dashboard', 'url' => url('dashboard')],
        ['label' => 'Profile', 'url' => null],
    ]
];

ob_start();
include __DIR__ . '/../views/edit-profile.php';
$content = ob_get_clean();

ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

$pageTitle = 'Edit Profile';
include $ROOT . '/features/shared/components/layouts/base.php';
