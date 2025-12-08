<?php
/**
 * Financial Statement View (Penyata Terimaan dan Bayaran)
 * 
 * Variables expected:
 * - $startDate, $endDate, $startDateFormatted, $endDateFormatted
 * - $openingBalance (array: cash, bank, total)
 * - $deposits (array: by_category, by_method, total)
 * - $payments (array: by_category, by_method, total)
 * - $totalTerimaan, $totalBayaran, $surplusDeficit
 * - $closingBalance (array: cash, bank, total)
 * - $depositCategoryLabels, $paymentCategoryLabels
 */
?>

<div class="content-container">
    <!-- Date Range Filter -->
    <div class="card mb-4" style="margin-bottom: 1.5rem;">
        <div class="card-body" style="padding: 1rem;">
            <form method="GET" action="" class="date-range-form" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="start_date" style="display: block; margin-bottom: 0.25rem; font-weight: 500;">Tarikh Mula (Start Date)</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" 
                           value="<?php echo htmlspecialchars($startDate); ?>" 
                           style="padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="end_date" style="display: block; margin-bottom: 0.25rem; font-weight: 500;">Tarikh Akhir (End Date)</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" 
                           value="<?php echo htmlspecialchars($endDate); ?>"
                           style="padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Generate Report
                </button>
            </form>
        </div>
    </div>

    <!-- Report Area -->
    <div class="card financial-statement-report" id="printArea">
        <div class="card-body" style="padding: 2rem;">
            <!-- Report Header -->
            <div class="report-header text-center" style="margin-bottom: 2rem;">
                <h2 style="margin-bottom: 0.5rem; font-size: 1.5rem; font-weight: bold;">PENYATA TERIMAAN DAN BAYARAN</h2>
                <h3 style="margin-bottom: 0.25rem; font-size: 1.1rem; color: #666;">
                    BAGI TEMPOH <?php echo strtoupper(date('d F Y', strtotime($startDate))); ?> 
                    HINGGA <?php echo strtoupper(date('d F Y', strtotime($endDate))); ?>
                </h3>
                <p style="color: #888; font-size: 0.9rem;">(Lampiran 54)</p>
            </div>

            <!-- Opening Balance Section -->
            <div class="opening-balance-section" style="margin-bottom: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                <h4 style="margin-bottom: 1rem; font-weight: 600; border-bottom: 2px solid #dee2e6; padding-bottom: 0.5rem;">
                    <i class="fas fa-wallet text-primary"></i> BAKI AWAL (Opening Balance)
                </h4>
                <table class="table" style="margin-bottom: 0;">
                    <tbody>
                        <tr>
                            <td style="padding: 0.5rem 1rem;">Tunai di tangan (Cash in Hand)</td>
                            <td class="text-right" style="width: 150px; text-align: right; font-weight: 500;">
                                RM <?php echo number_format($openingBalance['cash'], 2); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0.5rem 1rem;">Tunai di bank (Cash at Bank)</td>
                            <td class="text-right" style="text-align: right; font-weight: 500;">
                                RM <?php echo number_format($openingBalance['bank'], 2); ?>
                            </td>
                        </tr>
                        <tr style="background: #e9ecef; font-weight: bold;">
                            <td style="padding: 0.75rem 1rem;">Jumlah Baki Awal</td>
                            <td class="text-right" style="text-align: right;">
                                RM <?php echo number_format($openingBalance['total'], 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Section A: Terimaan (Income) -->
            <div class="income-section" style="margin-bottom: 2rem;">
                <h4 style="margin-bottom: 1rem; font-weight: 600; border-bottom: 2px solid #28a745; padding-bottom: 0.5rem; color: #28a745;">
                    <i class="fas fa-arrow-down"></i> A. TERIMAAN (Income)
                </h4>
                <table class="table table-striped" style="margin-bottom: 0;">
                    <thead>
                        <tr style="background: #d4edda;">
                            <th style="padding: 0.75rem 1rem;">Kategori</th>
                            <th class="text-right" style="width: 150px; text-align: right;">Jumlah (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($depositCategoryLabels as $col => $label): 
                            $amount = (float)($deposits['by_category'][$col] ?? 0);
                            if ($amount > 0): ?>
                        <tr>
                            <td style="padding: 0.5rem 1rem;"><?php echo htmlspecialchars($label); ?></td>
                            <td class="text-right" style="text-align: right;">
                                <?php echo number_format($amount, 2); ?>
                            </td>
                        </tr>
                        <?php endif; endforeach; ?>
                        
                        <?php if ($totalTerimaan == 0): ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted" style="padding: 1rem;">
                                Tiada terimaan dalam tempoh ini.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: #28a745; color: white; font-weight: bold;">
                            <td style="padding: 0.75rem 1rem;">JUMLAH TERIMAAN (A)</td>
                            <td class="text-right" style="text-align: right;">
                                RM <?php echo number_format($totalTerimaan, 2); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Section B: Bayaran (Expenses) -->
            <div class="expense-section" style="margin-bottom: 2rem;">
                <h4 style="margin-bottom: 1rem; font-weight: 600; border-bottom: 2px solid #dc3545; padding-bottom: 0.5rem; color: #dc3545;">
                    <i class="fas fa-arrow-up"></i> B. BAYARAN (Expenses)
                </h4>
                <table class="table table-striped" style="margin-bottom: 0;">
                    <thead>
                        <tr style="background: #f8d7da;">
                            <th style="padding: 0.75rem 1rem;">Kategori</th>
                            <th class="text-right" style="width: 150px; text-align: right;">Jumlah (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paymentCategoryLabels as $col => $label): 
                            $amount = (float)($payments['by_category'][$col] ?? 0);
                            if ($amount > 0): ?>
                        <tr>
                            <td style="padding: 0.5rem 1rem;"><?php echo htmlspecialchars($label); ?></td>
                            <td class="text-right" style="text-align: right;">
                                <?php echo number_format($amount, 2); ?>
                            </td>
                        </tr>
                        <?php endif; endforeach; ?>
                        
                        <?php if ($totalBayaran == 0): ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted" style="padding: 1rem;">
                                Tiada bayaran dalam tempoh ini.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: #dc3545; color: white; font-weight: bold;">
                            <td style="padding: 0.75rem 1rem;">JUMLAH BAYARAN (B)</td>
                            <td class="text-right" style="text-align: right;">
                                RM <?php echo number_format($totalBayaran, 2); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Surplus/Deficit Section -->
            <div class="surplus-section" style="margin-bottom: 2rem; padding: 1rem; background: <?php echo $surplusDeficit >= 0 ? '#d4edda' : '#f8d7da'; ?>; border-radius: 8px;">
                <table class="table" style="margin-bottom: 0;">
                    <tbody>
                        <tr style="font-weight: bold; font-size: 1.1rem;">
                            <td style="padding: 0.75rem 1rem;">
                                <?php echo $surplusDeficit >= 0 ? 'LEBIHAN (Surplus)' : 'KURANGAN (Deficit)'; ?> (A - B)
                            </td>
                            <td class="text-right" style="width: 150px; text-align: right; color: <?php echo $surplusDeficit >= 0 ? '#28a745' : '#dc3545'; ?>;">
                                RM <?php echo number_format($surplusDeficit, 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Closing Balance Section -->
            <div class="closing-balance-section" style="margin-bottom: 2rem; padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                <h4 style="margin-bottom: 1rem; font-weight: 600; border-bottom: 2px solid #2196f3; padding-bottom: 0.5rem; color: #1976d2;">
                    <i class="fas fa-wallet"></i> BAKI AKHIR (Closing Balance)
                </h4>
                <table class="table" style="margin-bottom: 0;">
                    <tbody>
                        <tr>
                            <td style="padding: 0.5rem 1rem;">Tunai di tangan (Cash in Hand)</td>
                            <td class="text-right" style="width: 150px; text-align: right; font-weight: 500;">
                                RM <?php echo number_format($closingBalance['cash'], 2); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0.5rem 1rem;">Tunai di bank (Cash at Bank)</td>
                            <td class="text-right" style="text-align: right; font-weight: 500;">
                                RM <?php echo number_format($closingBalance['bank'], 2); ?>
                            </td>
                        </tr>
                        <tr style="background: #1976d2; color: white; font-weight: bold;">
                            <td style="padding: 0.75rem 1rem;">Jumlah Baki Akhir</td>
                            <td class="text-right" style="text-align: right;">
                                RM <?php echo number_format($closingBalance['total'], 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Signature Section -->
            <div class="signature-section" style="margin-top: 3rem; padding-top: 2rem; border-top: 2px dashed #dee2e6;">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; text-align: center;">
                    <!-- Prepared By -->
                    <div class="signature-block">
                        <div style="border-bottom: 1px solid #333; margin-bottom: 0.5rem; height: 60px;"></div>
                        <p style="margin-bottom: 0.25rem; font-weight: 600;">Disediakan oleh:</p>
                        <p style="margin-bottom: 0; font-size: 0.85rem; color: #666;">(Prepared By)</p>
                        <p style="margin-top: 1rem; font-size: 0.9rem;">Tarikh: _______________</p>
                    </div>
                    
                    <!-- Certified By -->
                    <div class="signature-block">
                        <div style="border-bottom: 1px solid #333; margin-bottom: 0.5rem; height: 60px;"></div>
                        <p style="margin-bottom: 0.25rem; font-weight: 600;">Disahkan oleh:</p>
                        <p style="margin-bottom: 0; font-size: 0.85rem; color: #666;">(Certified By)</p>
                        <p style="margin-top: 1rem; font-size: 0.9rem;">Tarikh: _______________</p>
                    </div>
                    
                    <!-- Checked By -->
                    <div class="signature-block">
                        <div style="border-bottom: 1px solid #333; margin-bottom: 0.5rem; height: 60px;"></div>
                        <p style="margin-bottom: 0.25rem; font-weight: 600;">Disemak oleh:</p>
                        <p style="margin-bottom: 0; font-size: 0.85rem; color: #666;">(Checked By)</p>
                        <p style="margin-top: 1rem; font-size: 0.9rem;">Tarikh: _______________</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .dashboard, .sidebar, .dashboard-header, .date-range-form, .header-actions {
        display: none !important;
    }
    
    .financial-statement-report {
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    body {
        background: white !important;
    }
    
    .content-container {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .page-wrapper {
        padding: 0 !important;
    }
    
    table {
        font-size: 0.85rem !important;
    }
    
    .signature-section {
        page-break-inside: avoid;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle print button
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
});
</script>
