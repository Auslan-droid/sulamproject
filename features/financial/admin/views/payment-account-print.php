<?php
/**
 * Payment Account Print Template
 */

// Build filter description
$filterParts = [];
if (!empty($_GET['date_from']) || !empty($_GET['date_to'])) {
    $dateFrom = $_GET['date_from'] ?? 'Start';
    $dateTo = $_GET['date_to'] ?? 'End';
    $filterParts[] = "Date: $dateFrom to $dateTo";
}
if (!empty($_GET['payment_method'])) {
    $filterParts[] = "Payment: " . ucfirst($_GET['payment_method']);
}
if (!empty($_GET['categories'])) {
    $catNames = array_map(function($cat) use ($categoryLabels) {
        return $categoryLabels[$cat] ?? $cat;
    }, $_GET['categories']);
    $filterParts[] = "Categories: " . implode(', ', $catNames);
}

$filterDescription = !empty($filterParts) ? implode(' | ', $filterParts) : 'All Records';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Akaun Bayaran - Print</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            margin: 15mm;
            padding: 0;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 10px 0;
        }
        .org-info {
            padding-bottom: 2px;
            margin-bottom: 5px;
            display: inline-block;
            min-width: 60%;
            text-align: center;
            font-style: italic;
        }
        .filter-info {
            text-align: center;
            font-size: 9pt;
            margin-bottom: 15px;
            font-style: italic;
        }
        .summary-box {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            gap: 10px;
        }
        .summary-item {
            border: 1px solid black;
            padding: 10px;
            flex: 1;
            text-align: center;
        }
        .summary-label {
            font-size: 9pt;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 12pt;
            font-weight: bold;
        }
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .print-table th, .print-table td {
            border: 1px solid black;
            padding: 4px;
            vertical-align: middle;
        }
        .print-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
        }
        .print-table td {
            font-size: 8pt;
        }
        .col-date { width: 70px; text-align: center; }
        .col-voucher { width: 80px; text-align: center; }
        .col-to { width: 100px; }
        .col-ic { width: 80px; text-align: center; }
        .col-bank { width: 90px; }
        .col-account { width: 80px; }
        .col-desc { }
        .col-method { width: 60px; text-align: center; }
        .col-ref { width: 80px; text-align: center; }
        .col-amount { width: 70px; text-align: right; }
        .col-total { width: 80px; text-align: right; font-weight: bold; }
        
        .footer-row {
            background-color: #ccc;
            font-weight: bold;
        }

        .footer-signatures {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-box {
            border: 1px solid black;
            width: 30%;
            padding: 10px;
            min-height: 100px;
        }

        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; padding: 10px; background: #eee; border-bottom: 1px solid #ddd;">
        <button onclick="window.print()" style="padding: 5px 15px; font-weight: bold;">Print / Save as PDF</button>
        <button onclick="closeWindow()" style="padding: 5px 15px;">Close</button>
    </div>

    <script>
        function closeWindow() {
            window.close();
            // If window.close() doesn't work (e.g., opened via link), go back
            setTimeout(function() {
                window.history.back();
            }, 100);
        }
    </script>

    <div class="header">
        <h1>AKAUN BAYARAN</h1>
        
        <div class="org-info">
            JAWATANKUASA PENGURUSAN MASJID DARUL ULUM
        </div>
        <br>
        <div class="org-info">
            Taman Desa Ilmu, 94300 Kota Samarahan, Sarawak
        </div>
    </div>

    <div class="filter-info">
        Filter: <?php echo htmlspecialchars($filterDescription); ?>
    </div>

    <!-- Summary boxes -->
    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Jumlah Tunai (Cash)</div>
            <div class="summary-value">RM <?php echo number_format($totalCash, 2); ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Jumlah Bank (Bank)</div>
            <div class="summary-value">RM <?php echo number_format($totalBank, 2); ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Jumlah Keseluruhan (Total)</div>
            <div class="summary-value">RM <?php echo number_format($totalCash + $totalBank, 2); ?></div>
        </div>
    </div>

    <table class="print-table">
        <thead>
            <tr>
                <th class="col-date">Tarikh</th>
                <th class="col-voucher">No. Baucar</th>
                <th class="col-to">Bayar Kepada</th>
                <th class="col-ic">No. K/P</th>
                <th class="col-bank">Bank</th>
                <th class="col-account">No. Akaun</th>
                <th class="col-desc">Perkara</th>
                <th class="col-method">Kaedah</th>
                <th class="col-ref">No. Rujukan</th>
                <?php foreach ($categoryColumns as $col): ?>
                    <th class="col-amount"><?php echo htmlspecialchars($categoryLabels[$col]); ?></th>
                <?php endforeach; ?>
                <th class="col-total">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($payments)): ?>
                <tr>
                    <td colspan="<?php echo 9 + count($categoryColumns) + 1; ?>" style="text-align: center; padding: 20px;">
                        Tiada rekod.
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                // Initialize totals for each category
                $categoryTotals = array_fill_keys($categoryColumns, 0);
                $grandTotal = 0;
                
                foreach ($payments as $payment): 
                    $rowTotal = 0;
                    foreach ($categoryColumns as $col) {
                        $amount = (float)($payment[$col] ?? 0);
                        $rowTotal += $amount;
                        $categoryTotals[$col] += $amount;
                    }
                    $grandTotal += $rowTotal;
                ?>
                    <tr>
                        <td class="col-date"><?php echo date('d/m/Y', strtotime($payment['tx_date'])); ?></td>
                        <td class="col-voucher"><?php echo htmlspecialchars($payment['voucher_number'] ?? '-'); ?></td>
                        <td class="col-to"><?php echo htmlspecialchars($payment['paid_to'] ?? ''); ?></td>
                        <td class="col-ic"><?php echo htmlspecialchars($payment['payee_ic'] ?? '-'); ?></td>
                        <td class="col-bank"><?php echo htmlspecialchars($payment['payee_bank_name'] ?? '-'); ?></td>
                        <td class="col-account"><?php echo htmlspecialchars($payment['payee_bank_account'] ?? '-'); ?></td>
                        <td class="col-desc"><?php echo htmlspecialchars($payment['description'] ?? ''); ?></td>
                        <td class="col-method"><?php echo strtoupper($payment['payment_method'] ?? 'cash'); ?></td>
                        <td class="col-ref"><?php echo htmlspecialchars($payment['payment_reference'] ?? '-'); ?></td>
                        <?php foreach ($categoryColumns as $col): ?>
                            <td class="col-amount">
                                <?php 
                                $amt = (float)($payment[$col] ?? 0);
                                echo $amt > 0 ? number_format($amt, 2) : '-';
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="col-total"><?php echo number_format($rowTotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <!-- Totals Row -->
                <tr class="footer-row">
                    <td colspan="9" style="text-align: right; padding-right: 10px;">JUMLAH:</td>
                    <?php foreach ($categoryColumns as $col): ?>
                        <td class="col-amount"><?php echo number_format($categoryTotals[$col], 2); ?></td>
                    <?php endforeach; ?>
                    <td class="col-total"><?php echo number_format($grandTotal, 2); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-signatures">
        <div class="signature-box">
            <p>Disediakan oleh :</p>
            <br><br>
            Bendahari<br>
            Nama :<br>
            Tarikh :
        </div>
        <div class="signature-box">
            <p>Disahkan oleh :</p>
            <br><br>
            Pengerusi<br>
            Nama :<br>
            Tarikh :
        </div>
        <div class="signature-box">
            <p>Disemak oleh :</p>
            <br><br>
            Juruaudit Dalam<br>
            Nama :<br>
            Tarikh :
        </div>
    </div>

</body>
</html>
