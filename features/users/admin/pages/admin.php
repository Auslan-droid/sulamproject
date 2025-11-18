<?php
// Moved from /admin.php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
initSecureSession();
requireAdmin();

// Simple list of users with edit links
$users = [];
$res = $mysqli->query("SELECT id, name, username, email, roles, is_meninggal FROM users ORDER BY id DESC");
if ($res) { while ($row = $res->fetch_assoc()) { $users[] = $row; } $res->close(); }
$stylePath = $ROOT . '/assets/css/style.css';
$styleVersion = file_exists($stylePath) ? filemtime($stylePath) : time();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin — Users</title>
  <link rel="stylesheet" href="/sulamproject/assets/css/style.css?v=<?php echo $styleVersion; ?>">
</head>
<body>
  <div class="dashboard">
  <?php $currentPage='admin.php'; include $ROOT . '/features/shared/components/sidebar.php'; ?>
    <main class="content">
      <div class="small-card" style="max-width:1100px;margin:0 auto;">
        <h2>Manage Users</h2>
        <table class="table">
          <thead>
            <tr>
              <th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Deceased?</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><?php echo (int)$u['id']; ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['roles']); ?></td>
                <td><?php echo $u['is_meninggal'] ? 'Yes' : 'No'; ?></td>
                <td><a class="btn" href="/sulamproject/admin/user-edit?id=<?php echo (int)$u['id']; ?>">Edit</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
<?php include $ROOT . '/features/shared/components/footer.php'; ?>
</body>
</html>
