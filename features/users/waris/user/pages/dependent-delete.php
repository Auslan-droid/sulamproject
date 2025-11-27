<?php
// Dependent Delete Action
$ROOT = dirname(__DIR__, 5);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/DependentController.php';

initSecureSession();
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new DependentController($mysqli);
    $controller->delete();
}

header('Location: ' . url('features/users/user/pages/profile.php'));
exit;
