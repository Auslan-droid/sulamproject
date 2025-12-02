<?php
// Moved from /admin.php
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
initSecureSession();
requireAdmin();

// Simple list of users with edit links
$users = [];
$res = $mysqli->query("SELECT users.id, users.name, users.username, users.email, users.roles, users.is_deceased, users.income, COUNT(dependent.id) as dependent_count FROM users LEFT JOIN dependent ON users.id = dependent.user_id GROUP BY users.id ORDER BY users.id DESC");
if ($res) { while ($row = $res->fetch_assoc()) { $users[] = $row; } $res->close(); }

// 1. Capture the inner content
ob_start();
?>
<div class="small-card" style="max-width:1100px;margin:0 auto;">
  <h2>Manage Users</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Income Class</th><th>Dependents</th><th>Deceased?</th><th>Action</th>
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
          <td>
            <?php
                $income = $u['income'];
                $incomeClass = '-';
                if ($income !== null && $income !== '') {
                    if ($income < 5250) {
                        $incomeClass = 'B40';
                    } elseif ($income < 11820) {
                        $incomeClass = 'M40';
                    } else {
                        $incomeClass = 'T20';
                    }
                }
                echo $incomeClass;
            ?>
          </td>
          <td><?php echo (int)$u['dependent_count']; ?></td>
          <td><?php echo $u['is_deceased'] ? 'Yes' : 'No'; ?></td>
          <td><a class="btn" href="<?php echo url('admin/user-edit?id=' . (int)$u['id']); ?>">Edit</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Admin - Users';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
