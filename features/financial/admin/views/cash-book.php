<?php
/**
 * Cash Book View
 * Variables expected: $transactions, $tunaiBalance, $bankBalance, $openingCash, $openingBank, $fiscalYear, $hasSettings
 */
?>

<div class="content-container">
    <!-- Opening Balance Alert (if not configured) -->
    <?php if (!$hasSettings): ?>
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <div>
            <strong>Baki awal belum dikonfigurasi.</strong> 
            Sila tetapkan baki awal untuk tahun kewangan <?php echo $fiscalYear; ?>.
            <a href="<?php echo url('financial/settings'); ?>" class="alert-link ml-2">Tetapkan Sekarang →</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Balance Summary Stat Cards -->
    <div class="stat-cards">
        <div class="stat-card stat-card--cash">
            <div class="stat-card__label">Baki Tunai (Cash Balance)</div>
            <div class="stat-card__value">RM <?php echo number_format($tunaiBalance, 2); ?></div>
            <div class="stat-card__meta">Baki Awal: RM <?php echo number_format($openingCash, 2); ?></div>
        </div>
        <div class="stat-card stat-card--bank">
            <div class="stat-card__label">Baki Bank (Bank Balance)</div>
            <div class="stat-card__value">RM <?php echo number_format($bankBalance, 2); ?></div>
            <div class="stat-card__meta">Baki Awal: RM <?php echo number_format($openingBank, 2); ?></div>
        </div>
        <div class="stat-card stat-card--total">
            <div class="stat-card__label">Jumlah Baki (Total Balance)</div>
            <div class="stat-card__value">RM <?php echo number_format($tunaiBalance + $bankBalance, 2); ?></div>
            <div class="stat-card__meta">Tahun Kewangan: <?php echo $fiscalYear; ?></div>
        </div>
    </div>

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
                    <!-- Opening Balance Row -->
                    <tr class="table-secondary font-weight-bold">
                        <td class="text-center">01/01/<?php echo $fiscalYear; ?></td>
                        <td class="text-center"><span class="badge badge-secondary">BAKI AWAL</span></td>
                        <td>Baki Bawa Ke Hadapan (Opening Balance)</td>
                        <td class="table__cell--numeric">-</td>
                        <td class="table__cell--numeric">-</td>
                        <td class="table__cell--numeric">-</td>
                        <td class="table__cell--numeric">-</td>
                        <td class="table__cell--numeric text-primary"><?php echo number_format($openingCash, 2); ?></td>
                        <td class="table__cell--numeric text-primary"><?php echo number_format($openingBank, 2); ?></td>
                        <td class="table__cell--actions">
                            <a href="<?php echo url('financial/settings'); ?>" class="btn btn-sm btn-outline-secondary" title="Edit Opening Balance">
                                <i class="fas fa-cog"></i>
                            </a>
                        </td>
                    </tr>

                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle mr-1"></i> Tiada transaksi dijumpai untuk tahun <?php echo $fiscalYear; ?>.
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
                        <td colspan="3" class="text-right">Baki Semasa (Current Balance):</td>
                        <td colspan="2" class="text-center text-primary">
                            RM <?php echo number_format($tunaiBalance, 2); ?>
                        </td>
                        <td colspan="2" class="text-center text-primary">
                            RM <?php echo number_format($bankBalance, 2); ?>
                        </td>
                        <td colspan="3" class="text-center">
                            <strong>Jumlah: RM <?php echo number_format($tunaiBalance + $bankBalance, 2); ?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
</div>
