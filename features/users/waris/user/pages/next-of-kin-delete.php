<?php
// Delete Next of Kin
$ROOT = dirname(__DIR__, 5);

// Define APP_BASE_PATH for direct access
if (!defined('APP_BASE_PATH')) {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $featuresPos = strpos($scriptName, '/features/');
    if ($featuresPos !== false) {
        define('APP_BASE_PATH', substr($scriptName, 0, $featuresPos));
    } else {
        define('APP_BASE_PATH', '/sulamprojectex');
    }
}

require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/NextOfKinController.php';

initSecureSession();
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new NextOfKinController($mysqli);
    $controller->delete();
} else {
    header('Location: ' . url('features/users/user/pages/profile.php'));
    exit;
}
