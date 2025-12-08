<?php
/**
 * Official Receipt Print Page (Resit Rasmi - Lampiran 6)
 * 
 * Generates a printable receipt document for deposit transactions.
 * Auto-prints when loaded.
 */

$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';
require_once $ROOT . '/features/shared/lib/database/mysqli-db.php';
require_once $ROOT . '/features/financial/shared/lib/DepositAccountRepository.php';

initSecureSession();
requireAuth();

// Get deposit ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Invalid receipt ID.');
}

// Fetch deposit record
$repository = new DepositAccountRepository($mysqli);
$deposit = $repository->findById($id);

if (!$deposit) {
    die('Receipt not found.');
}

// Calculate total amount from category columns
$totalAmount = 0;
foreach (DepositAccountRepository::CATEGORY_COLUMNS as $col) {
    $totalAmount += (float)($deposit[$col] ?? 0);
}

// Determine category for display
$categoryLabel = '';
foreach (DepositAccountRepository::CATEGORY_COLUMNS as $col) {
    $val = (float)($deposit[$col] ?? 0);
    if ($val > 0) {
        $categoryLabel = DepositAccountRepository::CATEGORY_LABELS[$col] ?? $col;
        break; // Use the first non-zero category
    }
}

// Payment method display
$paymentMethodDisplay = 'Tunai';
if ($deposit['payment_method'] !== 'cash') {
    $paymentMethodDisplay = 'Bank';
    if (!empty($deposit['payment_reference'])) {
        $paymentMethodDisplay .= ' - No. ' . htmlspecialchars($deposit['payment_reference']);
    }
}

// Format date
$formattedDate = date('d/m/Y', strtotime($deposit['tx_date']));

// Convert amount to words
$amountInWords = numberToWords($totalAmount);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resit Rasmi - <?php echo e($deposit['receipt_number'] ?? 'N/A'); ?></title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.4;
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
            
            .receipt-container {
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

        /* Receipt container */
        .receipt-container {
            width: 210mm;
            min-height: 148mm; /* A5 height */
            margin: 0 auto;
            padding: 15mm;
            background: #fff;
            border: 2px solid #000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Header section */
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .receipt-header h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .receipt-header .address {
            font-size: 10pt;
        }

        /* Title */
        .receipt-title {
            text-align: center;
            margin: 15px 0;
            padding: 8px;
            background: #f5f5f5;
            border: 1px solid #ccc;
        }

        .receipt-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Info grid */
        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
            border-bottom: 1px dotted #999;
            padding-left: 5px;
        }

        /* Full width info rows */
        .info-full {
            grid-column: 1 / -1;
        }

        /* Amount box */
        .amount-section {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: end;
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ccc;
            background: #fafafa;
        }

        .amount-words {
            font-size: 11pt;
        }

        .amount-words .label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .amount-words .value {
            font-style: italic;
            text-transform: uppercase;
        }

        .amount-box {
            border: 2px solid #000;
            padding: 10px 20px;
            text-align: center;
            background: #fff;
            min-width: 150px;
        }

        .amount-box .label {
            font-size: 10pt;
            font-weight: bold;
        }

        .amount-box .value {
            font-size: 16pt;
            font-weight: bold;
        }

        /* Payment method section */
        .payment-section {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .payment-section .label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Signature section */
        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
            padding-top: 20px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }

        .signature-label {
            font-size: 10pt;
        }

        /* Footer */
        .receipt-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }

        /* Lampiran label */
        .lampiran-label {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 9pt;
            color: #666;
        }

        .receipt-wrapper {
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Print buttons (hidden when printing) -->
    <div class="no-print">
        <button onclick="window.print()"><i class="fas fa-print"></i> Cetak Resit</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="receipt-wrapper">
        <div class="receipt-container">
            <span class="lampiran-label">Lampiran 6</span>
            
            <!-- Header -->
            <div class="receipt-header">
                <h1>Jawatankuasa Pengurusan Masjid Kamek</h1>
                <p class="address">
                    Jalan Masjid, Kampung Kamek,<br>
                    12345 Bandar, Negeri, Malaysia<br>
                    Tel: 012-345 6789
                </p>
            </div>

            <!-- Title -->
            <div class="receipt-title">
                <h2>Resit Rasmi (Official Receipt)</h2>
            </div>

            <!-- Receipt Info -->
            <div class="receipt-info">
                <div class="info-row">
                    <span class="info-label">No. Resit:</span>
                    <span class="info-value"><?php echo e($deposit['receipt_number'] ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tarikh:</span>
                    <span class="info-value"><?php echo e($formattedDate); ?></span>
                </div>
                <div class="info-row info-full">
                    <span class="info-label">Diterima Dari:</span>
                    <span class="info-value"><?php echo e($deposit['received_from'] ?? '-'); ?></span>
                </div>
                <div class="info-row info-full">
                    <span class="info-label">Perkara:</span>
                    <span class="info-value"><?php echo e($deposit['description'] ?? $categoryLabel); ?></span>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="amount-section">
                <div class="amount-words">
                    <div class="label">Jumlah (Dalam Perkataan):</div>
                    <div class="value"><?php echo e($amountInWords); ?></div>
                </div>
                <div class="amount-box">
                    <div class="label">RM</div>
                    <div class="value"><?php echo number_format($totalAmount, 2); ?></div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="payment-section">
                <div class="label">Kaedah Pembayaran:</div>
                <div class="value"><?php echo e($paymentMethodDisplay); ?></div>
            </div>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">
                        <span class="signature-label">Disediakan Oleh / Prepared By</span>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        <span class="signature-label">Tandatangan / Signature</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="receipt-footer">
                <p>Resit ini adalah bukti rasmi penerimaan wang. Sila simpan untuk rujukan.</p>
                <p>This receipt is an official proof of payment. Please keep for your records.</p>
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
