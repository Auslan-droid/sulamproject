<?php
/**
 * Payment Account Listing View
 * Variables expected: $payments, $categoryColumns, $categoryLabels
 */

// Format amount for display
function formatAmount($value) {
    if ($value > 0) {
        return 'RM ' . number_format($value, 2);
    }
    return '-';
}
?>

<div class="content-container">
    <!-- Payment Account Table -->
    
    <!-- Balance Summary Stat Cards -->
    <div class="stat-cards">
        <div class="stat-card stat-card--cash">
            <div class="stat-card__label">Jum. Bayaran Tunai (Total Cash)</div>
            <div class="stat-card__value">RM <?php echo number_format($totalCash, 2); ?></div>
        </div>
        <div class="stat-card stat-card--bank">
            <div class="stat-card__label">Jum. Bayaran Bank (Total Bank)</div>
            <div class="stat-card__value">RM <?php echo number_format($totalBank, 2); ?></div>
        </div>
        <div class="stat-card stat-card--total">
            <div class="stat-card__label">Jumlah Keseluruhan (Grand Total)</div>
            <div class="stat-card__value">RM <?php echo number_format($totalCash + $totalBank, 2); ?></div>
        </div>
    </div>

    <?php if (empty($payments)): ?>
        <div class="notice" style="text-align: center; padding: 3rem;">
            <i class="fas fa-coins" style="font-size: 3rem; color: var(--muted); margin-bottom: 1rem;"></i>
            <p style="font-size: 1.1rem; color: var(--muted);">No payment records found. <a href="<?php echo url('financial/payment-account/add'); ?>">Add a new record</a>.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive table-responsive--wide">
            <table class="table table-hover table--payment-account">
                <thead>
                    <tr>
                        <th>Tarikh</th>
                        <th>No. Baucar</th>
                        <th class="sticky-col-left">Butiran</th>
                        <th>Kaedah Pembayaran</th>
                        <?php foreach ($categoryLabels as $col => $label): ?>
                            <th><?php echo htmlspecialchars($label); ?></th>
                        <?php endforeach; ?>
                        <th>Jumlah</th>
                        <th class="table__cell--actions">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['tx_date']); ?></td>
                        <td>
                            <?php if (!empty($row['voucher_number'])): ?>
                                <span class="badge badge-light border"><?php echo htmlspecialchars($row['voucher_number']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="sticky-col-left">
                            <div style="font-weight: 600;"><?php echo htmlspecialchars($row['description']); ?></div>
                            <div style="color: #6b7280; font-size: 0.85em;"><?php echo htmlspecialchars($row['paid_to']); ?></div>
                        </td>
                        <td>
                            <span class="badge badge-light border">
                                <?php echo htmlspecialchars(ucfirst($row['payment_method'] ?? 'cash')); ?>
                            </span>
                            <?php if (!empty($row['payment_reference'])): ?>
                                <div class="badge-ref" onclick="copyRef('<?php echo htmlspecialchars($row['payment_reference'], ENT_QUOTES); ?>', this)" title="Click to copy">
                                    Ref: <?php echo htmlspecialchars($row['payment_reference']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <?php 
                        $rowTotal = 0;
                        foreach ($categoryColumns as $col): 
                            $val = (float)($row[$col] ?? 0);
                            $rowTotal += $val;
                        ?>
                            <td class="table__cell--numeric"><?php echo formatAmount($val); ?></td>
                        <?php endforeach; ?>
                        <td class="table__cell--numeric" style="font-weight: bold;"><?php echo formatAmount($rowTotal); ?></td>
                        <td class="table__cell--actions">
                            <a href="<?php echo url('financial/voucher-print?id=' . $row['id']); ?>" class="btn btn-sm btn-outline-primary" title="Print Voucher" target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="<?php echo url('financial/payment-account/edit?id=' . $row['id']); ?>" class="btn btn-sm btn-secondary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="<?php echo url('financial/payment-account/delete'); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?php echo url('features/financial/admin/assets/css/financial.css'); ?>">

<script>
function copyRef(text, el) {
    // Navigator clipboard API
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showCopyFeedback(el);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            fallbackCopy(text, el);
        });
    } else {
        fallbackCopy(text, el);
    }
}

function fallbackCopy(text, el) {
    // Fallback using temporary textarea
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-9999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showCopyFeedback(el);
    } catch (err) {
        console.error('Fallback copy failed', err);
    }
    
    document.body.removeChild(textArea);
}

function showCopyFeedback(el) {
    const originalContent = el.innerHTML;
    // Store original style if needed
    const originalStyle = el.getAttribute('style');
    
    // Feedback style (Green/Success)
    el.style.backgroundColor = '#d1fae5'; 
    el.style.borderColor = '#34d399';
    el.style.color = '#065f46';
    el.innerHTML = '<i class="fas fa-check"></i> Copied';
    
    setTimeout(() => {
        el.innerHTML = originalContent;
        if (originalStyle) {
            el.setAttribute('style', originalStyle);
        } else {
            el.removeAttribute('style');
        }
    }, 1500);
}
</script>
