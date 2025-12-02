<?php
/**
 * Payment Voucher Print Page (Baucar Bayaran - Lampiran 1)
 * 
 * Generates a printable voucher document for payment transactions.
 * Auto-prints when loaded.
 */

$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once $ROOT . '/features/financial/shared/lib/PaymentAccountRepository.php';

initSecureSession();
requireAuth();

// Get payment ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Invalid voucher ID.');
}

// Fetch payment record
$repository = new PaymentAccountRepository($mysqli);
$payment = $repository->findById($id);

if (!$payment) {
    die('Voucher not found.');
}

// Calculate total amount from category columns
$totalAmount = 0;
$categories = [];
foreach (PaymentAccountRepository::CATEGORY_COLUMNS as $col) {
    $val = (float)($payment[$col] ?? 0);
    if ($val > 0) {
        $totalAmount += $val;
        $categories[] = [
            'label' => PaymentAccountRepository::CATEGORY_LABELS[$col] ?? $col,
            'amount' => $val
        ];
    }
}

// Payment method display
$isCash = $payment['payment_method'] === 'cash';
$isBank = $payment['payment_method'] !== 'cash';

// Format date
$formattedDate = date('d/m/Y', strtotime($payment['tx_date']));

// Convert amount to words
$amountInWords = numberToWords($totalAmount);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baucar Bayaran - <?php echo e($payment['voucher_number'] ?? 'N/A'); ?></title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }

        /* Print-specific styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .voucher-container {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                page-break-after: always;
            }
        }

        /* Screen preview styles */
        @media screen {
            body {
                background: #f0f0f0;
                padding: 20px;
            }
            
            .no-print {
                text-align: center;
                margin-bottom: 20px;
            }
            
            .no-print button {
                padding: 10px 30px;
                font-size: 14pt;
                cursor: pointer;
                background: #4a90d9;
                color: #fff;
                border: none;
                border-radius: 4px;
                margin: 0 10px;
            }
            
            .no-print button:hover {
                background: #357abd;
            }
        }

        /* Voucher container */
        .voucher-container {
            width: 210mm;
            min-height: 297mm; /* A4 height */
            margin: 0 auto;
            padding: 12mm;
            background: #fff;
            border: 2px solid #000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Header section */
        .voucher-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .voucher-header h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .voucher-header .address {
            font-size: 9pt;
        }

        /* Title */
        .voucher-title {
            text-align: center;
            margin: 12px 0;
            padding: 6px;
            background: #f5f5f5;
            border: 1px solid #ccc;
        }

        .voucher-title h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Info sections grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-section {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .info-section h3 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            margin-bottom: 6px;
            font-size: 10pt;
        }

        .info-label {
            width: 110px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            border-bottom: 1px dotted #999;
            padding-left: 5px;
            min-height: 18px;
        }

        /* Checkbox styles */
        .checkbox-row {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10pt;
        }

        .checkbox {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .checkbox.checked::after {
            content: '✓';
        }

        /* Transaction table */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .transaction-table th,
        .transaction-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .transaction-table th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 10pt;
            text-align: center;
        }

        .transaction-table td.number {
            text-align: center;
            width: 50px;
        }

        .transaction-table td.amount {
            text-align: right;
            width: 120px;
        }

        .transaction-table tfoot td {
            font-weight: bold;
            background: #fafafa;
        }

        /* Amount in words section */
        .amount-words-section {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ccc;
            background: #fafafa;
        }

        .amount-words-section .label {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 5px;
        }

        .amount-words-section .value {
            font-style: italic;
            text-transform: uppercase;
            font-size: 11pt;
        }

        /* Signature section */
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 25px;
        }

        .signature-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
        }

        .signature-box h4 {
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .signature-box .sig-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .signature-box .sig-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 3px;
            font-size: 8pt;
        }

        .signature-box .sig-field {
            display: flex;
            margin-bottom: 3px;
            font-size: 8pt;
        }

        .signature-box .sig-field-label {
            width: 50px;
        }

        .signature-box .sig-field-value {
            flex: 1;
            border-bottom: 1px dotted #999;
        }

        /* Recipient section */
        .recipient-section {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 10px;
        }

        .recipient-section h4 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .recipient-declaration {
            font-size: 9pt;
            font-style: italic;
            margin-bottom: 10px;
            padding: 5px;
            background: #fafafa;
        }

        .recipient-fields {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .recipient-field {
            text-align: center;
        }

        .recipient-field .line {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 3px;
            font-size: 9pt;
        }

        /* Footer */
        .voucher-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            text-align: center;
            color: #666;
        }

        /* Lampiran label */
        .lampiran-label {
            position: absolute;
            top: 8px;
            right: 15px;
            font-size: 8pt;
            color: #666;
        }

        .voucher-wrapper {
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Print buttons (hidden when printing) -->
    <div class="no-print">
        <button onclick="window.print()"><i class="fas fa-print"></i> Cetak Baucar</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="voucher-wrapper">
        <div class="voucher-container">
            <span class="lampiran-label">Lampiran 1</span>
            
            <!-- Header -->
            <div class="voucher-header">
                <h1>Jawatankuasa Pengurusan Masjid Kamek</h1>
                <p class="address">
                    Jalan Masjid, Kampung Kamek, 12345 Bandar, Negeri, Malaysia | Tel: 012-345 6789
                </p>
            </div>

            <!-- Title -->
            <div class="voucher-title">
                <h2>Baucar Bayaran (Payment Voucher)</h2>
            </div>

            <!-- Payee and Voucher Details -->
            <div class="info-grid">
                <!-- Left: Payee Details -->
                <div class="info-section">
                    <h3>Butiran Penerima</h3>
                    <div class="info-row">
                        <span class="info-label">Bayar Kepada:</span>
                        <span class="info-value"><?php echo e($payment['paid_to'] ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. K/P:</span>
                        <span class="info-value"><?php echo e($payment['payee_ic'] ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama Bank:</span>
                        <span class="info-value"><?php echo e($payment['payee_bank_name'] ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. Akaun:</span>
                        <span class="info-value"><?php echo e($payment['payee_bank_account'] ?? '-'); ?></span>
                    </div>
                </div>

                <!-- Right: Voucher Details -->
                <div class="info-section">
                    <h3>Butiran Baucar</h3>
                    <div class="info-row">
                        <span class="info-label">No. Baucar:</span>
                        <span class="info-value"><?php echo e($payment['voucher_number'] ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tarikh:</span>
                        <span class="info-value"><?php echo e($formattedDate); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kaedah:</span>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-item">
                            <span class="checkbox <?php echo $isCash ? 'checked' : ''; ?>"></span>
                            <span>Tunai</span>
                        </div>
                        <div class="checkbox-item">
                            <span class="checkbox <?php echo $isBank ? 'checked' : ''; ?>"></span>
                            <span>Bank/E-Banking</span>
                        </div>
                    </div>
                    <?php if ($isBank && !empty($payment['payment_reference'])): ?>
                    <div class="info-row" style="margin-top: 8px;">
                        <span class="info-label">No. Rujukan:</span>
                        <span class="info-value"><?php echo e($payment['payment_reference']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Transaction Table -->
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Butiran Bayaran (Payment Details)</th>
                        <th style="width: 120px;">Amaun (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php $itemNo = 1; ?>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="number"><?php echo $itemNo++; ?></td>
                            <td><?php echo e($payment['description'] ?? $cat['label']); ?></td>
                            <td class="amount"><?php echo number_format($cat['amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="number">1</td>
                            <td><?php echo e($payment['description'] ?? '-'); ?></td>
                            <td class="amount"><?php echo number_format($totalAmount, 2); ?></td>
                        </tr>
                    <?php endif; ?>
                    <!-- Empty rows for writing additional items -->
                    <tr>
                        <td class="number">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="amount">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="number">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="amount">&nbsp;</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right;">JUMLAH (Total):</td>
                        <td class="amount">RM <?php echo number_format($totalAmount, 2); ?></td>
                    </tr>
                </tfoot>
            </table>

            <!-- Amount in Words -->
            <div class="amount-words-section">
                <div class="label">Amaun Dalam Perkataan (Amount in Words):</div>
                <div class="value"><?php echo e($amountInWords); ?></div>
            </div>

            <!-- Signatures Grid -->
            <div class="signature-grid">
                <!-- Prepared By -->
                <div class="signature-box">
                    <h4>Disediakan Oleh<br>(Prepared By)</h4>
                    <div class="sig-content">
                        <div class="sig-field">
                            <span class="sig-field-label">Nama:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Jawatan:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Tarikh:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-line">Tandatangan</div>
                    </div>
                </div>

                <!-- Approver 1 -->
                <div class="signature-box">
                    <h4>Disemak & Diluluskan<br>(Checked & Approved)</h4>
                    <div class="sig-content">
                        <div class="sig-field">
                            <span class="sig-field-label">Nama:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Jawatan:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Tarikh:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-line">Tandatangan</div>
                    </div>
                </div>

                <!-- Approver 2 -->
                <div class="signature-box">
                    <h4>Disemak & Diluluskan<br>(Checked & Approved)</h4>
                    <div class="sig-content">
                        <div class="sig-field">
                            <span class="sig-field-label">Nama:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Jawatan:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Tarikh:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-line">Tandatangan</div>
                    </div>
                </div>

                <!-- Approver 3 -->
                <div class="signature-box">
                    <h4>Disemak & Diluluskan<br>(Checked & Approved)</h4>
                    <div class="sig-content">
                        <div class="sig-field">
                            <span class="sig-field-label">Nama:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Jawatan:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-field">
                            <span class="sig-field-label">Tarikh:</span>
                            <span class="sig-field-value"></span>
                        </div>
                        <div class="sig-line">Tandatangan</div>
                    </div>
                </div>
            </div>

            <!-- Recipient Confirmation -->
            <div class="recipient-section">
                <h4>Penerima (Recipient)</h4>
                <div class="recipient-declaration">
                    "Saya mengesahkan bahawa pembayaran seperti di atas telah diterima."<br>
                    <em>("I confirm that the above payment has been received.")</em>
                </div>
                <div class="recipient-fields">
                    <div class="recipient-field">
                        <div class="line">Nama / Name</div>
                    </div>
                    <div class="recipient-field">
                        <div class="line">No. K/P / IC No.</div>
                    </div>
                    <div class="recipient-field">
                        <div class="line">Tarikh / Date</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="voucher-footer">
                <p>Dokumen ini adalah baucar bayaran rasmi. Sila simpan untuk rujukan.</p>
                <p>This document is an official payment voucher. Please keep for your records.</p>
            </div>
        </div>
    </div>

    <!-- Auto-print script -->
    <script>
        // Auto-print when page loads (with slight delay for rendering)
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
