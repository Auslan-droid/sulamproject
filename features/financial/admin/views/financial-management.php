<?php
/**
 * Financial Management Dashboard View
 * Variables expected: $balances, $settings, $fiscalYear
 */
?>

<div class="content-container">
    <!-- Bento Grid Dashboard -->
    <div class="bento-grid">
        
        <!-- Total Balance Hero Card (2x1) -->
        <div class="bento-card bento-2x1 card-balance">
            <div class="card-header" style="margin-bottom: 1rem; padding-bottom: 0.75rem;">
                <div class="bento-icon bento-icon-sm">💰</div>
                <div>
                    <div class="bento-label">TOTAL BALANCE</div>
                    <p style="margin: 0; font-size: 0.85rem; font-weight: 500;">Year <?php echo $fiscalYear; ?></p>
                </div>
            </div>
            <div class="bento-flex-between" style="align-items: flex-end;">
                <div class="balance-value" style="font-size: 2rem; margin: 0;">RM <?php echo number_format($balances['total_balance'] ?? 0, 2); ?></div>
                <div style="text-align: right;">
                    <small style="display: block; color: var(--muted); font-size: 0.75rem;">Cash: RM <?php echo number_format($balances['closing_cash'] ?? 0, 2); ?></small>
                    <small style="display: block; color: var(--muted); font-size: 0.75rem;">Bank: RM <?php echo number_format($balances['closing_bank'] ?? 0, 2); ?></small>
                </div>
            </div>
        </div>

        <!-- Quick Actions (2x2) - Placed second to float right -->
        <div class="bento-card bento-2x2">
            <h3 class="bento-title">⚡ Quick Menu</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; flex: 1;">
                <a href="<?php echo url('financial/deposit-account'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-hand-holding-usd"></i></span>
                    <span style="font-size: 0.95rem;">Deposit Account <br><small>(Akaun Terimaan)</small></span>
                </a>
                <a href="<?php echo url('financial/payment-account'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-file-invoice-dollar"></i></span>
                    <span style="font-size: 0.95rem;">Payment Account <br><small>(Akaun Bayaran)</small></span>
                </a>
                <a href="<?php echo url('financial/cash-book'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-book"></i></span>
                    <span style="font-size: 0.95rem;">Cash Book <br><small>(Buku Tunai)</small></span>
                </a>
                <a href="<?php echo url('financial/statement'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-chart-bar"></i></span>
                    <span style="font-size: 0.95rem;">Financial Statement <br><small>(Penyata Kewangan)</small></span>
                </a>
                <!-- Settings Button -->
                <a href="<?php echo url('financial/settings'); ?>" class="bento-btn" style="padding: 1.5rem; grid-column: span 2;">
                    <span style="font-size: 2rem;"><i class="fas fa-cog"></i></span>
                    <span style="font-size: 0.95rem;">Financial Settings <br><small>(Tetapan Kewangan)</small></span>
                </a>
            </div>
        </div>

        <!-- Terimaan Stat Card (1x1) - Fills Row 2 Left -->
        <div class="bento-card bento-1x1 card-stat">
            <div class="bento-flex bento-gap-sm" style="margin-bottom: 0.75rem;">
                <div class="bento-icon bento-icon-sm" style="background: #d1fae5; color: #065f46;">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="bento-label">Receipts (Terimaan)</div>
            </div>
            <div class="bento-value-sm" style="color: #065f46; margin-bottom: 0.5rem;">
                RM <?php echo number_format(($balances['total_cash_in'] ?? 0) + ($balances['total_bank_in'] ?? 0), 2); ?>
            </div>
            <div style="color: var(--muted); font-size: 0.85rem;">
                Cash: RM <?php echo number_format($balances['total_cash_in'] ?? 0, 2); ?><br>
                Bank: RM <?php echo number_format($balances['total_bank_in'] ?? 0, 2); ?>
            </div>
        </div>

        <!-- Bayaran Stat Card (1x1) - Fills Row 2 Right -->
        <div class="bento-card bento-1x1 card-stat">
            <div class="bento-flex bento-gap-sm" style="margin-bottom: 0.75rem;">
                <div class="bento-icon bento-icon-sm" style="background: #fee2e2; color: #991b1b;">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="bento-label">Payments (Bayaran)</div>
            </div>
            <div class="bento-value-sm" style="color: #991b1b; margin-bottom: 0.5rem;">
                RM <?php echo number_format(($balances['total_cash_out'] ?? 0) + ($balances['total_bank_out'] ?? 0), 2); ?>
            </div>
            <div style="color: var(--muted); font-size: 0.85rem;">
                Cash: RM <?php echo number_format($balances['total_cash_out'] ?? 0, 2); ?><br>
                Bank: RM <?php echo number_format($balances['total_bank_out'] ?? 0, 2); ?>
            </div>
        </div>

    </div>
</div>
