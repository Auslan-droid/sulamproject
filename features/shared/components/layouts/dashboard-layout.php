<?php
require_once __DIR__ . '/../../lib/utilities/functions.php';
?>
<?php if (!isset($ROOT)) { $ROOT = dirname(__DIR__, 4); } ?>
<?php require_once $ROOT . '/features/shared/lib/auth/session.php'; initSecureSession(); ?>
<div class="dashboard">
    <aside class="sidebar">
        <div class="brand">OurMasjid</div>
        <nav class="nav">
            <?php $base = url(''); $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: ''; ?>
            <a href="<?php echo url('dashboard'); ?>" class="<?php echo str_starts_with($path, "$base/dashboard") ? 'active' : ''; ?>">Dashboard</a>
            <a href="<?php echo url('users'); ?>" class="<?php echo str_starts_with($path, "$base/users") ? 'active' : ''; ?>">User Management</a>
            <a href="<?php echo url('waris'); ?>" class="<?php echo str_starts_with($path, "$base/waris") ? 'active' : ''; ?>">Waris</a>
            <a href="<?php echo url('donations'); ?>" class="<?php echo str_starts_with($path, "$base/donations") ? 'active' : ''; ?>">Donations</a>
            <a href="<?php echo url('events'); ?>" class="<?php echo str_starts_with($path, "$base/events") ? 'active' : ''; ?>">Events</a>
            <?php if (isAdmin()): ?>
                <a href="<?php echo url('admin'); ?>" class="<?php echo str_starts_with($path, "$base/admin") ? 'active' : ''; ?>">Admin</a>
            <?php endif; ?>
            <a href="<?php echo url('logout'); ?>">Logout</a>
        </nav>
        <div class="sidebar-footer">
            <img src="<?php echo url('assets/uploads/masjid_logo.jpg'); ?>" alt="Masjid Logo">
        </div>
    </aside>

    <main class="content">
        <?php echo $content ?? ''; ?>
    </main>
</div>
