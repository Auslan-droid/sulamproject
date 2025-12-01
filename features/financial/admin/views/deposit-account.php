<?php
/**
 * Deposit Account Listing View
 * Variables expected: $deposits, $categoryColumns, $categoryLabels
 */

// Format amount for display
function formatDepositAmount($value) {
    if ($value > 0) {
        return 'RM ' . number_format($value, 2);
    }
    return '-';
}
?>
<div class="card page-card">
    <div class="card-header">
        <h3>Akaun Terimaan (Deposit Account)</h3>
    </div>
    <div class="card-body">
        <?php if (empty($deposits)): ?>
            <p class="text-muted">No deposit records found. <a href="<?php echo url('financial/deposit-account/add'); ?>">Add a new record</a>.</p>
        <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; min-width: 2000px;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--border-color);">
                        <th style="padding: 0.5rem;">Tarikh</th>
                        <th style="padding: 0.5rem;">Butiran</th>
                        <?php foreach ($categoryLabels as $col => $label): ?>
                            <th style="padding: 0.5rem;"><?php echo htmlspecialchars($label); ?></th>
                        <?php endforeach; ?>
                        <th style="padding: 0.5rem;">Jumlah</th>
                        <th style="padding: 0.5rem; text-align: center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deposits as $row): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 0.5rem;"><?php echo htmlspecialchars($row['tx_date']); ?></td>
                        <td style="padding: 0.5rem;"><?php echo htmlspecialchars($row['description']); ?></td>
                        <?php 
                        $rowTotal = 0;
                        foreach ($categoryColumns as $col): 
                            $val = (float)($row[$col] ?? 0);
                            $rowTotal += $val;
                        ?>
                            <td style="padding: 0.5rem;"><?php echo formatDepositAmount($val); ?></td>
                        <?php endforeach; ?>
                        <td style="padding: 0.5rem; font-weight: bold;"><?php echo formatDepositAmount($rowTotal); ?></td>
                        <td style="padding: 0.5rem; text-align: center; white-space: nowrap;">
                            <a href="<?php echo url('financial/deposit-account/edit?id=' . $row['id']); ?>" class="btn btn-sm btn-secondary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="<?php echo url('financial/deposit-account/delete'); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
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
</div>
