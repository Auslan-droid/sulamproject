<?php
/**
 * Financial Statement Print Page (Penyata Terimaan dan Bayaran - Lampiran 9)
 */

$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once $ROOT . '/features/financial/shared/lib/FinancialStatementController.php';

initSecureSession();
requireAuth();

// Get date range from URL
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

// Fetch data
$controller = new FinancialStatementController($mysqli);
$data = $controller->getStatementData($startDate, $endDate);

// Format dates for display
$displayStartDate = date('d/m/Y', strtotime($startDate));
$displayEndDate = date('d/m/Y', strtotime($endDate));
$periodString = "{$displayStartDate} HINGGA {$displayEndDate}";

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyata Terimaan dan Bayaran</title>
    <style>
        /* Reset and base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 11pt; line-height: 1.3; color: #000; background: #fff; }

        /* Print styles */
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .page-container { border: none !important; box-shadow: none !important; margin: 0 !important; }
        }

        /* Screen styles */
        @media screen {
            body { background: #f0f0f0; padding: 20px; }
            .no-print { text-align: center; margin-bottom: 20px; }
            .no-print button { padding: 10px 20px; font-size: 12pt; cursor: pointer; background: #4a90d9; color: #fff; border: none; border-radius: 4px; margin: 0 5px; }
            .page-container { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 15mm; background: #fff; border: 1px solid #ccc; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        }

        /* Layout */
        .page-container { position: relative; }
        .lampiran-label { position: absolute; top: 0; right: 0; text-align: right; font-weight: bold; }
        
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
        .header-line { border-bottom: 1px solid #000; margin: 5px 0; padding-bottom: 2px; }
        .header-text { font-style: italic; font-size: 10pt; }

        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; }
        
        .statement-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .statement-table td { padding: 4px; vertical-align: top; }
        .col-label { width: 45%; }
        .col-box { width: 30%; }
        .col-nota { width: 5%; text-align: center; }
        .col-total { width: 20%; text-align: right; }

        .box-container { border: 1px solid #000; padding: 10px; min-height: 60px; }
        .box-item { display: flex; justify-content: space-between; margin-bottom: 2px; }
        
        .total-line { border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0; font-weight: bold; margin-top: 5px; }
        .double-line { border-bottom: 3px double #000; }

        .signatures { display: flex; justify-content: space-between; margin-top: 50px; }
        .sig-box { width: 30%; border: 1px solid #000; padding: 10px; height: 120px; font-size: 9pt; }
        .sig-title { margin-bottom: 40px; }

        .text-right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Cetak</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="page-container">
        <div class="lampiran-label">
            Lampiran 9<br>
            [Ruj. 54]
        </div>

        <div class="header">
            <h1>PENYATA TERIMAAN DAN BAYARAN</h1>
            
            <div class="header-line">
                (JAWATANKUASA PENGURUSAN MASJID KAMEK)
            </div>
            
            <div class="header-line">
                (JALAN MASJID, KAMPUNG KAMEK, 12345 BANDAR, NEGERI)
            </div>

            <div style="margin-top: 20px;">
                BAGI <span style="border-bottom: 1px solid #000; padding: 0 10px;"><?php echo $periodString; ?></span>
                <br>
                <span class="header-text">(tempoh / tahun berakhir)</span>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-bottom: 5px; font-weight: bold;">
            <div style="width: 5%; text-align: center;">Nota</div>
            <div style="width: 20%; text-align: right;">(tahun)<br>RM</div>
        </div>

        <!-- Opening Balance -->
        <table class="statement-table">
            <tr>
                <td class="col-label">
                    <div class="bold">BAKI PADA <?php echo $displayStartDate; ?></div>
                    <div style="margin-left: 10px;">Wang Tunai di tangan</div>
                    <div style="margin-left: 10px;">Wang Tunai di bank</div>
                    <div style="margin-left: 10px;">Pelaburan</div>
                </td>
                <td class="col-box">
                    <div class="box-container">
                        <div class="box-item">
                            <span>Tunai:</span>
                            <span><?php echo number_format($data['opening_balance']['cash'], 2); ?></span>
                        </div>
                        <div class="box-item">
                            <span>Bank:</span>
                            <span><?php echo number_format($data['opening_balance']['bank'], 2); ?></span>
                        </div>
                        <div class="box-item">
                            <span>Pelaburan:</span>
                            <span>0.00</span>
                        </div>
                    </div>
                </td>
                <td class="col-nota"></td>
                <td class="col-total">
                    <div style="margin-top: 25px; border-bottom: 1px solid #000;">
                        <?php echo number_format($data['opening_balance']['cash'] + $data['opening_balance']['bank'], 2); ?>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Receipts -->
        <table class="statement-table">
            <tr>
                <td class="col-label">
                    <div class="bold">A. TERIMAAN</div>
                </td>
                <td class="col-box">
                    <div class="box-container">
                        <?php foreach ($data['receipts'] as $item): ?>
                        <div class="box-item">
                            <span><?php echo $item['label']; ?></span>
                            <span><?php echo number_format($item['amount'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($data['receipts'])): ?>
                            <div style="text-align: center; color: #999;">- Tiada Terimaan -</div>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="col-nota"></td>
                <td class="col-total"></td>
            </tr>
            <tr>
                <td class="col-label bold">JUMLAH TERIMAAN</td>
                <td class="col-box"></td>
                <td class="col-nota"></td>
                <td class="col-total">
                    <div style="border-bottom: 1px solid #000;">
                        <?php echo number_format($data['total_receipts'], 2); ?>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Payments -->
        <table class="statement-table">
            <tr>
                <td class="col-label">
                    <div class="bold">B. BAYARAN</div>
                </td>
                <td class="col-box">
                    <div class="box-container">
                        <?php foreach ($data['payments'] as $item): ?>
                        <div class="box-item">
                            <span><?php echo $item['label']; ?></span>
                            <span><?php echo number_format($item['amount'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($data['payments'])): ?>
                            <div style="text-align: center; color: #999;">- Tiada Bayaran -</div>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="col-nota"></td>
                <td class="col-total"></td>
            </tr>
            <tr>
                <td class="col-label bold">JUMLAH BAYARAN</td>
                <td class="col-box"></td>
                <td class="col-nota"></td>
                <td class="col-total">
                    <div style="border-bottom: 1px solid #000;">
                        <?php echo number_format($data['total_payments'], 2); ?>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Surplus/Deficit -->
        <table class="statement-table">
            <tr>
                <td class="col-label">
                    Lebihan / (Kurangan) (A-B)
                </td>
                <td class="col-box"></td>
                <td class="col-nota"></td>
                <td class="col-total">
                    <div style="border-bottom: 1px solid #000;">
                        <?php echo number_format($data['surplus_deficit'], 2); ?>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Closing Balance -->
        <table class="statement-table">
            <tr>
                <td class="col-label">
                    <div class="bold">BAKI PADA <?php echo $displayEndDate; ?></div>
                    <div class="bold" style="text-decoration: underline; margin-top: 5px;">DIWAKILI OLEH</div>
                    <div style="margin-left: 10px;">Wang Tunai di tangan</div>
                    <div style="margin-left: 10px;">Wang Tunai di bank</div>
                    <div style="margin-left: 10px;">Pelaburan</div>
                </td>
                <td class="col-box"></td>
                <td class="col-nota"></td>
                <td class="col-total">
                    <div class="double-line" style="margin-top: 25px;">
                        <?php echo number_format($data['closing_balance']['cash'] + $data['closing_balance']['bank'], 2); ?>
                    </div>
                    
                    <!-- Breakdown for Closing Balance -->
                    <div style="margin-top: 20px; border-bottom: 1px solid #000;">
                        <?php echo number_format($data['closing_balance']['cash'], 2); ?>
                    </div>
                    <div style="border-bottom: 1px solid #000;">
                        <?php echo number_format($data['closing_balance']['bank'], 2); ?>
                    </div>
                    <div style="border-bottom: 1px solid #000;">
                        0.00
                    </div>
                    <div class="double-line">
                        -
                    </div>
                </td>
            </tr>
        </table>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sig-box">
                <div class="sig-title">Disediakan oleh :</div>
                <div>Nama:</div>
                <div>Jawatan:</div>
                <div>Tarikh :</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Disahkan oleh :</div>
                <div>Nama:</div>
                <div>Jawatan:</div>
                <div>Tarikh :</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Disemak oleh :</div>
                <div>Nama:</div>
                <div>Jawatan:</div>
                <div>Tarikh :</div>
            </div>
        </div>

        <div style="position: absolute; bottom: 15mm; left: 15mm;">
            56
        </div>
    </div>

    <script>
        // Auto-print
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
