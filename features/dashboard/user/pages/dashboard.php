<?php
// Moved from /dashboard.php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
initSecureSession();
requireAuth();
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
$stylePath = $ROOT . '/assets/css/style.css';
$styleVersion = file_exists($stylePath) ? filemtime($stylePath) : time();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — SulamProject</title>
    <?php 
    // Auto-detect base path for assets
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $webRoot = str_replace('/features/dashboard/user/pages/dashboard.php', '', $scriptName);
    if ($webRoot === $scriptName) {
        // Try to find 'sulamprojectex' or similar in path
        $parts = explode('/', trim($scriptName, '/'));
        if (count($parts) > 0) {
            $webRoot = '/' . $parts[0];
        } else {
            $webRoot = '';
        }
    }
    $v = time();
    ?>
  <link rel="stylesheet" href="<?php echo $webRoot; ?>/features/shared/assets/css/variables.css?v=<?php echo $v; ?>">
  <link rel="stylesheet" href="<?php echo $webRoot; ?>/features/shared/assets/css/base.css?v=<?php echo $v; ?>">
  <link rel="stylesheet" href="<?php echo $webRoot; ?>/features/shared/assets/css/layout.css?v=<?php echo $v; ?>">
  <link rel="stylesheet" href="<?php echo $webRoot; ?>/features/shared/assets/css/cards.css?v=<?php echo $v; ?>">
  <link rel="stylesheet" href="<?php echo $webRoot; ?>/features/shared/assets/css/footer.css?v=<?php echo $v; ?>">
  <link rel="stylesheet" href="<?php echo $webRoot; ?>/features/shared/assets/css/responsive.css?v=<?php echo $v; ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  </head>
  <body>
    <div class="dashboard">
  <?php $currentPage='dashboard.php'; include $ROOT . '/features/shared/components/sidebar.php'; ?>

      <main class="content">
        <div class="small-card" style="max-width:980px;margin:0 auto;padding:1.2rem 1.4rem;">
          <div class="dashboard-header">
            <h2 style="margin:0">Welcome</h2>
            <div>Hi, <strong><?php echo $username; ?></strong></div>
          </div>

          <section class="dashboard-cards">
            <a class="dashboard-card" href="<?php echo url('residents'); ?>">
              <i class="fa-solid fa-users icon" aria-hidden="true"></i>
              <h3>Residents</h3>
              <p>Manage residents and households.</p>
            </a>
            <a class="dashboard-card" href="<?php echo url('donations'); ?>">
              <i class="fa-solid fa-coins icon" aria-hidden="true"></i>
              <h3>Donations</h3>
              <p>Track donations and receipts.</p>
            </a>
            <a class="dashboard-card" href="<?php echo url('events'); ?>">
              <i class="fa-solid fa-calendar-days icon" aria-hidden="true"></i>
              <h3>Events</h3>
              <p>Plan and manage events.</p>
            </a>
          </section>
        </div>
      </main>
    </div>
      <?php include $ROOT . '/features/shared/components/footer.php'; ?>
      </body>
</html>
