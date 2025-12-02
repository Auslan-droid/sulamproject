<?php
/**
 * Cash Book View
 * Variables expected: $transactions, $tunaiBalance, $bankBalance
 */
?>

<div class="content-container">
    <!-- Cash Book Table -->
    <div class="table-responsive">
        <table class="table table-hover table--cash-book" id="cashBookTable">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2" class="align-middle text-center" style="width: 100px;">Tarikh<br>(Date)</th>
                        <th rowspan="2" class="align-middle text-center" style="width: 120px;">No. Rujukan<br>(Ref No)</th>
                        <th rowspan="2" class="align-middle text-center">Butiran<br>(Description)</th>
                        <th colspan="2" class="text-center">Tunai (Cash)</th>
                        <th colspan="2" class="text-center">Bank</th>
                        <th colspan="2" class="text-center">Baki (Balance)</th>
                        <th rowspan="2" class="align-middle table__cell--actions" style="width: 50px;">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center text-success" style="width: 100px;">Masuk (In)</th>
                        <th class="text-center text-danger" style="width: 100px;">Keluar (Out)</th>
                        <th class="text-center text-success" style="width: 100px;">Masuk (In)</th>
                        <th class="text-center text-danger" style="width: 100px;">Keluar (Out)</th>
                        <th class="text-center" style="width: 100px;">Tunai</th>
                        <th class="text-center" style="width: 100px;">Bank</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle mr-1"></i> No transactions found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $tx): ?>
                            <?php 
                                $amount = (float)$tx['amount'];
                                $isCash = $tx['payment_method'] === 'cash';
                                $isIn = $tx['type'] === 'IN';
                                
                                // Determine where to place the amount
                                $cashIn = ($isIn && $isCash) ? $amount : 0;
                                $cashOut = (!$isIn && $isCash) ? $amount : 0;
                                $bankIn = ($isIn && !$isCash) ? $amount : 0;
                                $bankOut = (!$isIn && !$isCash) ? $amount : 0;
                            ?>
                            <tr>
                                <td class="text-center"><?php echo date('d/m/Y', strtotime($tx['tx_date'])); ?></td>
                                <td class="text-center">
                                    <?php if ($tx['ref_no']): ?>
                                        <span class="badge badge-light border"><?php echo htmlspecialchars($tx['ref_no']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($tx['description']); ?></td>
                                
                                <!-- Tunai Columns -->
                                <td class="table__cell--numeric text-success">
                                    <?php echo $cashIn > 0 ? number_format($cashIn, 2) : '-'; ?>
                                </td>
                                <td class="table__cell--numeric text-danger">
                                    <?php echo $cashOut > 0 ? number_format($cashOut, 2) : '-'; ?>
                                </td>
                                
                                <!-- Bank Columns -->
                                <td class="table__cell--numeric text-success">
                                    <?php echo $bankIn > 0 ? number_format($bankIn, 2) : '-'; ?>
                                </td>
                                <td class="table__cell--numeric text-danger">
                                    <?php echo $bankOut > 0 ? number_format($bankOut, 2) : '-'; ?>
                                </td>
                                
                                <!-- Balance Columns -->
                                <td class="table__cell--numeric font-weight-bold">
                                    <?php echo number_format($tx['tunai_balance'], 2); ?>
                                </td>
                                <td class="table__cell--numeric font-weight-bold">
                                    <?php echo number_format($tx['bank_balance'], 2); ?>
                                </td>

                                <!-- Actions -->
                                <td class="table__cell--actions">
                                    <?php if ($isIn): ?>
                                        <a href="<?php echo url("financial/receipt-print?id={$tx['id']}"); ?>" 
                                           target="_blank" class="btn btn-sm btn-outline-primary" title="Print Receipt">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo url("financial/voucher-print?id={$tx['id']}"); ?>" 
                                           target="_blank" class="btn btn-sm btn-outline-primary" title="Print Voucher">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-light font-weight-bold">
                    <tr>
                        <td colspan="3" class="text-right">Current Balance (Baki Semasa):</td>
                        <td colspan="2" class="text-center text-primary">
                            RM <?php echo number_format($tunaiBalance, 2); ?>
                        </td>
                        <td colspan="2" class="text-center text-primary">
                            RM <?php echo number_format($bankBalance, 2); ?>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
</div>

<link rel="stylesheet" href="/features/financial/admin/assets/css/financial.css">
