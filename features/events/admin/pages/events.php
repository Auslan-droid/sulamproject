<?php
// Moved from /events.php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
initSecureSession();
requireAuth();
$isAdmin = isAdmin();
$message = '';
$messageClass = 'notice';

// Handle create (admin only)
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $desc = trim($_POST['description'] ?? '');
  $gamba = null;
  // Handle file upload if provided
  if (!empty($_FILES['gamba']['name'])) {
    $uploadDir = $ROOT . '/assets/uploads';
    if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }
    $ext = pathinfo($_FILES['gamba']['name'], PATHINFO_EXTENSION);
    $basename = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . ($ext?'.'.preg_replace('/[^a-zA-Z0-9]+/','',$ext):'');
    $target = $uploadDir . '/' . $basename;
    if (move_uploaded_file($_FILES['gamba']['tmp_name'], $target)) {
      $gamba = 'assets/uploads/' . $basename;
    }
  } else if (isset($_POST['gamba_url']) && $_POST['gamba_url'] !== '') {
    $gamba = trim($_POST['gamba_url']);
  }
  if ($desc === '') { $message = 'Description is required.'; }
  else {
    $stmt = $mysqli->prepare('INSERT INTO events (description, image_path) VALUES (?, ?)');
    if ($stmt) { $stmt->bind_param('ss', $desc, $gamba); $stmt->execute(); $stmt->close(); $message='Event created'; $messageClass='notice success'; }
  }
}

// List events
$events = [];
$res = $mysqli->query('SELECT id, description, image_path, created_at FROM events ORDER BY id DESC');
if ($res) { while ($row = $res->fetch_assoc()) { $events[] = $row; } $res->close(); }

// 1. Capture the inner content
ob_start();
?>
<div class="page-card">
  <h2>Events</h2>
  <?php if ($message): ?><div class="<?php echo $messageClass; ?>"><?php echo $message; ?></div><?php endif; ?>

  <?php if ($isAdmin): ?>
  <h3>Create Event</h3>
  <form method="post" enctype="multipart/form-data">
    <label>Description
      <textarea name="description" rows="3" required></textarea>
    </label>
    <div class="grid-2">
      <label>Gamba (upload)
        <input type="file" name="gamba" accept="image/*">
      </label>
      <label>or Gamba URL
        <input type="url" name="gamba_url" placeholder="https://...">
      </label>
    </div>
    <div class="actions">
      <button class="btn" type="submit">Publish</button>
    </div>
  </form>
  <?php endif; ?>

  <h3 style="margin-top:1.5rem;">Latest</h3>
  <?php if (empty($events)): ?>
    <p>No events yet.</p>
  <?php else: ?>
    <div class="cards">
      <?php foreach ($events as $e): ?>
        <div class="card">
          <?php if (!empty($e['image_path'])): ?>
             <?php 
                // Handle both full URLs and relative paths
                $imgSrc = $e['image_path'];
                if (!str_starts_with($imgSrc, 'http')) {
                    $imgSrc = url($imgSrc);
                }
             ?>
             <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Event image" style="max-width:100%;height:auto;">
          <?php endif; ?>
          <p><?php echo nl2br(htmlspecialchars($e['description'])); ?></p>
          <small>Posted: <?php echo htmlspecialchars($e['created_at']); ?></small>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/dashboard-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Events';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
