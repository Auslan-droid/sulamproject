<?php
/**
 * Financial Management Dashboard View
 * Variables expected: $balances, $settings, $fiscalYear
 */
?>

<div class="content-container">
    <!-- Bento Grid Dashboard -->
    <div class="bento-grid">
        
        <!-- Total Balance Hero Card (2×2) -->
        <div class="bento-card bento-2x2 card-balance">
            <div class="card-header">
                <div class="bento-icon">💰</div>
                <div>
                    <div class="bento-label">JUMLAH BAKI KESELURUHAN</div>
                    <p style="margin: 0; font-size: 0.85rem; font-weight: 500;">Tahun <?php echo $fiscalYear; ?></p>
                </div>
            </div>
            <div class="balance-value">RM <?php echo number_format($balances['total_balance'] ?? 0, 2); ?></div>
            <div class="balance-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-label">Tunai</div>
                    <div class="breakdown-value">RM <?php echo number_format($balances['closing_cash'] ?? 0, 2); ?></div>
                    <small style="color: var(--muted); font-size: 0.75rem;">Baki Awal: RM <?php echo number_format($balances['opening_cash'] ?? 0, 2); ?></small>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-label">Bank</div>
                    <div class="breakdown-value">RM <?php echo number_format($balances['closing_bank'] ?? 0, 2); ?></div>
                    <small style="color: var(--muted); font-size: 0.75rem;">Baki Awal: RM <?php echo number_format($balances['opening_bank'] ?? 0, 2); ?></small>
                </div>
            </div>
        </div>

        <!-- Terimaan Stat Card (1×1) -->
        <div class="bento-card bento-1x1 card-stat">
            <div class="bento-flex bento-gap-sm" style="margin-bottom: 0.75rem;">
                <div class="bento-icon bento-icon-sm" style="background: #d1fae5; color: #065f46;">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="bento-label">Terimaan</div>
            </div>
            <div class="bento-value-sm" style="color: #065f46; margin-bottom: 0.5rem;">
                RM <?php echo number_format(($balances['total_cash_in'] ?? 0) + ($balances['total_bank_in'] ?? 0), 2); ?>
            </div>
            <div style="color: var(--muted); font-size: 0.85rem;">
                Tunai: RM <?php echo number_format($balances['total_cash_in'] ?? 0, 2); ?><br>
                Bank: RM <?php echo number_format($balances['total_bank_in'] ?? 0, 2); ?>
            </div>
        </div>

        <!-- Bayaran Stat Card (1×1) -->
        <div class="bento-card bento-1x1 card-stat">
            <div class="bento-flex bento-gap-sm" style="margin-bottom: 0.75rem;">
                <div class="bento-icon bento-icon-sm" style="background: #fee2e2; color: #991b1b;">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="bento-label">Bayaran</div>
            </div>
            <div class="bento-value-sm" style="color: #991b1b; margin-bottom: 0.5rem;">
                RM <?php echo number_format(($balances['total_cash_out'] ?? 0) + ($balances['total_bank_out'] ?? 0), 2); ?>
            </div>
            <div style="color: var(--muted); font-size: 0.85rem;">
                Tunai: RM <?php echo number_format($balances['total_cash_out'] ?? 0, 2); ?><br>
                Bank: RM <?php echo number_format($balances['total_bank_out'] ?? 0, 2); ?>
            </div>
        </div>

        <!-- Quick Actions (2×2) -->
        <div class="bento-card bento-2x2">
            <h3 class="bento-title">⚡ Menu Pantas</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; flex: 1;">
                <a href="<?php echo url('financial/deposit-account'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-hand-holding-usd"></i></span>
                    <span style="font-size: 0.95rem;">Akaun Terimaan</span>
                </a>
                <a href="<?php echo url('financial/payment-account'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-file-invoice-dollar"></i></span>
                    <span style="font-size: 0.95rem;">Akaun Bayaran</span>
                </a>
                <a href="<?php echo url('financial/cash-book'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-book"></i></span>
                    <span style="font-size: 0.95rem;">Buku Tunai</span>
                </a>
                <a href="<?php echo url('financial/statement'); ?>" class="bento-btn" style="padding: 1.5rem;">
                    <span style="font-size: 2rem;"><i class="fas fa-chart-bar"></i></span>
                    <span style="font-size: 0.95rem;">Penyata Kewangan</span>
                </a>
            </div>
        </div>

        <!-- Settings Card (2×1) -->
        <div class="bento-card bento-2x1">
            <div class="bento-flex-between">
                <div>
                    <h3 class="bento-title" style="margin: 0 0 0.5rem 0;">⚙️ Tetapan Kewangan</h3>
                    <p style="margin: 0; color: var(--muted); font-size: 0.875rem;">Urus baki awal dan tahun kewangan</p>
                </div>
                <a href="<?php echo url('financial/settings'); ?>" class="bento-badge bento-badge-info" style="cursor: pointer; padding: 8px 16px; text-decoration: none;">
                    Tetapan <i class="fas fa-arrow-right" style="margin-left: 0.25rem;"></i>
                </a>
            </div>
        </div>

    </div>
</div>
