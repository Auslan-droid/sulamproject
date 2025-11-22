<?php
// Moved from /residents.php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
initSecureSession();
requireAuth();

// 1. Capture the inner content
ob_start();
?>
<div class="small-card" style="max-width:980px;margin:0 auto;">
  <h2>Residents</h2>
  <p>This section is coming soon.</p>
</div>
<?php
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/dashboard-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Residents';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
