<?php
// Printable Verified Deaths Report (admin)
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once __DIR__ . '/../controllers/AdminDeathsController.php';

initSecureSession();
requireAuth();
requireAdmin();

$selectedYear = isset($_GET['year']) && $_GET['year'] !== '' ? (int) $_GET['year'] : null;
$selectedMonth = isset($_GET['month']) && $_GET['month'] !== '' ? (int) $_GET['month'] : null;

$controller = new AdminDeathsController($mysqli, $ROOT, $_SESSION['user_id'] ?? null);
$verified = $controller->getVerifiedByDate($selectedYear, $selectedMonth);
$total = count($verified);

$yearLabel = $selectedYear ? (string)$selectedYear : 'All Years';
$monthLabel = $selectedMonth ? date('F', mktime(0,0,0,$selectedMonth,1)) : 'All Months';

// Output a minimal print-friendly HTML page. Users can Save as PDF from browser print dialog.
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Verified Deaths Report</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 0; }
        .meta { margin-bottom: 12px; color: #333; }
        .summary { margin: 16px 0; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 13px; }
        th { background: #f5f5f5; }
        .center { text-align: center; }
        @media print {
            a { display: none; }
        }
    </style>
</head>
<body>
    <div style="text-align:center;margin-bottom:6px;">
        <div style="font-size:20px;font-weight:700;letter-spacing:1px;">MASJID DARUL ULUM</div>
    </div>
    <h1>Verified Deaths Report</h1>
    <div class="meta">Period: <?php echo htmlspecialchars($monthLabel . ' / ' . $yearLabel); ?></div>
    <div class="summary">Total verified deaths: <?php echo $total; ?></div>

    <?php if ($total === 0): ?>
        <div>No records found for the selected period.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Deceased Name</th>
                    <th>Date of Death</th>
                    <th>Verified At</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach ($verified as $row): ?>
                    <tr>
                        <td class="center"><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row->deceased_name ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row->date_of_death ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row->verified_at ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="margin-top:18px;">
        <a href="#" onclick="window.print();return false;" style="display:inline-block;padding:8px 12px;background:#007bff;color:#fff;text-decoration:none;border-radius:4px;">Print / Save as PDF</a>
    </div>

    <script>
        // Auto-open print dialog briefly after load
        window.addEventListener('load', function(){
            setTimeout(function(){ window.print(); }, 300);
        });
    </script>
</body>
</html>
