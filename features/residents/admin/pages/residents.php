<?php
// Moved from /residents.php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
initSecureSession();
requireAuth();

$stylePath = $ROOT . '/assets/css/style.css';
$styleVersion = file_exists($stylePath) ? filemtime($stylePath) : time();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Residents — SulamProject</title>
  <link rel="stylesheet" href="/sulamproject/assets/css/style.css?v=<?php echo $styleVersion; ?>">
  </head>
  <body>
    <div class="dashboard">
  <?php $currentPage='residents.php'; include $ROOT . '/features/shared/components/sidebar.php'; ?>
      <main class="content">
        <div class="small-card" style="max-width:980px;margin:0 auto;">
          <h2>Residents</h2>
          <p>This section is coming soon.</p>
        </div>
      </main>
    </div>
  </body>
  <?php include $ROOT . '/features/shared/components/footer.php'; ?>
</html>
