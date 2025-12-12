<?php
/**
 * Cash Book View
 * Variables expected: $transactions, $tunaiBalance, $bankBalance, $openingCash, $openingBank, $fiscalYear, $hasSettings
 */
?>

<div class="content-container">
    <!-- Opening Balance Alert (if not configured) -->
    <?php if (!$hasSettings): ?>
    <div class="notice warning">
        <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
        <strong>Baki awal belum dikonfigurasi.</strong> 
        Sila tetapkan baki awal untuk tahun kewangan <?php echo $fiscalYear; ?>.
        <a href="<?php echo url('financial/settings'); ?>" style="margin-left: 0.5rem; color: inherit; text-decoration: underline;">Tetapkan Sekarang →</a>
    </div>
    <?php endif; ?>

    <?php
    // Define month names for display
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Mac', 4 => 'April',
        5 => 'Mei', 6 => 'Jun', 7 => 'Julai', 8 => 'Ogos',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Disember'
    ];
    
    // Calculate display balances based on filtered view
    $displayCash = $openingCash;
    $displayBank = $openingBank;
    
    if (!empty($transactions)) {
        //If transactions exist, the last row represents the closing balance for this view period
        $lastTx = end($transactions);
        $displayCash = $lastTx['tunai_balance'];
        $displayBank = $lastTx['bank_balance'];
    }
    ?>

    <!-- Balance Summary Stat Cards -->
    <div class="stat-cards">
        <div class="stat-card stat-card--cash">
            <div class="stat-card__label">Baki Tunai <?php echo $month ? "($months[$month])" : "(Tahun $fiscalYear)"; ?></div>
            <div class="stat-card__value">RM <?php echo number_format($displayCash, 2); ?></div>
            <div class="stat-card__meta">Baki Awal: RM <?php echo number_format($openingCash, 2); ?></div>
        </div>
        <div class="stat-card stat-card--bank">
            <div class="stat-card__label">Baki Bank <?php echo $month ? "($months[$month])" : "(Tahun $fiscalYear)"; ?></div>
            <div class="stat-card__value">RM <?php echo number_format($displayBank, 2); ?></div>
            <div class="stat-card__meta">Baki Awal: RM <?php echo number_format($openingBank, 2); ?></div>
        </div>
        <div class="stat-card stat-card--total">
            <div class="stat-card__label">Jumlah Baki (Total Balance)</div>
            <div class="stat-card__value">RM <?php echo number_format($displayCash + $displayBank, 2); ?></div>
            <div class="stat-card__meta">Tempoh: <?php echo $month ? "$months[$month] $fiscalYear" : $fiscalYear; ?></div>
        </div>
    </div>

    <!-- Filter Card (Styleguide Pattern) -->
    <div class="card card--filter mb-4" id="cashBookFilter">
        <!-- Filter Header -->
        <div class="filter-header">
            <div class="filter-icon">
                <i class="fas fa-sliders-h"></i>
            </div>
            <h4 class="filter-title">Cash Book Filters</h4>
            <button type="button" class="filter-collapse-toggle" aria-label="Toggle filters" onclick="toggleFilterCard()">
                <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
            </button>
        </div>

        <!-- Active Filters Display (Pills) -->
        <?php if ($month !== null || !empty($search)): ?>
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <div class="filter-pills">
                <?php if ($month !== null): ?>
                <span class="filter-pill filter-pill--selected">
                    <span><?php echo $months[$month]; ?> <?php echo $fiscalYear; ?></span>
                    <button type="button" class="filter-pill-remove" onclick="removeFilter('month')" aria-label="Remove month filter">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                <?php endif; ?>
                
                <?php if (!empty($search)): ?>
                <span class="filter-pill filter-pill--selected">
                    <span>Search: "<?php echo htmlspecialchars($search); ?>"</span>
                    <button type="button" class="filter-pill-remove" onclick="removeFilter('search')" aria-label="Remove search filter">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filter Content (Collapsible) -->
        <div id="filterContent" style="display: none;">
            <form method="GET" id="cashBookFilterForm">
                <!-- Period Filter Section -->
                <div class="filter-section">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span class="filter-section-title">Period</span>
                        <i class="fas fa-chevron-down filter-section-icon"></i>
                    </div>
                    <div class="filter-section-content">
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; align-items: end;">
                            <!-- Year Selection -->
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">Year</label>
                                <select name="year" class="form-control" style="width: 100%;">
                                    <?php 
                                    $currentYear = date('Y');
                                    for ($y = $currentYear - 2; $y <= $currentYear + 1; $y++) {
                                        $selected = ($y == $fiscalYear) ? 'selected' : '';
                                        echo "<option value='$y' $selected>$y</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Month Selection -->
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">Month</label>
                                <select name="month" class="form-control" style="width: 100%;">
                                    <option value="all" <?php echo ($month === null) ? 'selected' : ''; ?>>Entire Year</option>
                                    <?php
                                    foreach ($months as $num => $name) {
                                        $selected = ($month === $num) ? 'selected' : '';
                                        echo "<option value='$num' $selected>$num - $name</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Filter Section -->
                <div class="filter-section">
                    <div class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span class="filter-section-title">Search</span>
                        <i class="fas fa-chevron-down filter-section-icon"></i>
                    </div>
                    <div class="filter-section-content">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">Reference No. / Description</label>
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control" 
                                placeholder="Search by reference number or transaction description..." 
                                value="<?php echo htmlspecialchars($search ?? ''); ?>"
                                style="width: 100%;">
                        </div>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn" style="flex: 1;">
                        <i class="fas fa-filter" style="margin-right: 0.5rem;"></i>Apply Filters
                    </button>
                    <button type="button" class="btn outline" onclick="resetFilters()" style="flex: 1;">
                        <i class="fas fa-redo" style="margin-right: 0.5rem;"></i>Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Toggle filter card collapse
    function toggleFilterCard() {
        const content = document.getElementById('filterContent');
        const icon = document.getElementById('filterToggleIcon');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            content.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    // Toggle individual filter sections
    function toggleFilterSection(header) {
        const content = header.nextElementSibling;
        const icon = header.querySelector('.filter-section-icon');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
            header.classList.remove('collapsed');
        } else {
            content.style.display = 'none';
            icon.style.transform = 'rotate(-90deg)';
            header.classList.add('collapsed');
        }
    }

    // Remove individual filter
    function removeFilter(filterType) {
        const form = document.getElementById('cashBookFilterForm');
        const year = form.querySelector('select[name="year"]').value;
        
        if (filterType === 'month') {
            window.location.href = '<?php echo url('financial/cash-book'); ?>?year=' + year + '&month=all<?php echo !empty($search) ? "&search=" . urlencode($search) : ""; ?>';
        } else if (filterType === 'search') {
            window.location.href = '<?php echo url('financial/cash-book'); ?>?year=' + year + '&month=<?php echo $month ?? 'all'; ?>';
        }
    }

    // Reset all filters
    function resetFilters() {
        const currentYear = new Date().getFullYear();
        window.location.href = '<?php echo url('financial/cash-book'); ?>?year=' + currentYear + '&month=all';
    }
    </script>

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
                        <th rowspan="2" class="align-middle table__cell--actions" style="width: 50px;">Tindakan</th>
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
                        <td class="text-center">
                            <?php 
                            // Determine opening date
                            $openDay = '01';
                            $openMonth = $month ? str_pad($month, 2, '0', STR_PAD_LEFT) : '01';
                            echo "$openDay/$openMonth/$fiscalYear";
                            ?>
                        </td>
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
                            RM <?php echo number_format($displayCash, 2); ?>
                        </td>
                        <td colspan="2" class="text-center text-primary">
                            RM <?php echo number_format($displayBank, 2); ?>
                        </td>
                        <td colspan="3" class="text-center">
                            <strong>Jumlah: RM <?php echo number_format($displayCash + $displayBank, 2); ?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
</div>
