<?php
require_once __DIR__ . '/../../lib/utilities/functions.php';
?>
<?php if (!isset($ROOT)) { $ROOT = dirname(__DIR__, 4); } ?>
<?php require_once $ROOT . '/features/shared/lib/auth/session.php'; initSecureSession(); ?>
<div class="dashboard">
    <aside class="sidebar">
        <div class="brand">OurMasjid</div>
        <nav class="nav">
            <?php $base = '/sulamproject'; $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: ''; ?>
            <a href="<?php echo $base; ?>/dashboard" class="<?php echo str_starts_with($path, "$base/dashboard") ? 'active' : ''; ?>">Dashboard</a>
            <a href="<?php echo $base; ?>/residents" class="<?php echo str_starts_with($path, "$base/residents") ? 'active' : ''; ?>">Residents</a>
            <a href="<?php echo $base; ?>/waris" class="<?php echo str_starts_with($path, "$base/waris") ? 'active' : ''; ?>">Waris</a>
            <a href="<?php echo $base; ?>/donations" class="<?php echo str_starts_with($path, "$base/donations") ? 'active' : ''; ?>">Donations</a>
            <a href="<?php echo $base; ?>/events" class="<?php echo str_starts_with($path, "$base/events") ? 'active' : ''; ?>">Events</a>
            <?php if (isAdmin()): ?>
                <a href="<?php echo $base; ?>/admin" class="<?php echo str_starts_with($path, "$base/admin") ? 'active' : ''; ?>">Admin</a>
            <?php endif; ?>
            <a href="/sulamproject/logout">Logout</a>
        </nav>
        <div class="sidebar-footer">
            <img src="/sulamproject/assets/uploads/masjid_logo.jpg" alt="Masjid Logo">
        </div>
    </aside>

    <main class="content">
        <?php echo $content ?? ''; ?>
    </main>
</div>
