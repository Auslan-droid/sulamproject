<?php
/**
 * Financial Management Dashboard View
 * Variables expected: $balances, $settings, $fiscalYear
 */
?>

<div class="card page-card">
    <div class="card-header">
        <h3>Financial Overview - Tahun <?php echo $fiscalYear; ?></h3>
    </div>
    <div class="card-body">
        <!-- Balance Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Baki Tunai (Cash)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    RM <?php echo number_format($balances['closing_cash'] ?? 0, 2); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <hr class="my-2">
                        <small class="text-muted">
                            Baki Awal: RM <?php echo number_format($balances['opening_cash'] ?? 0, 2); ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Baki Bank
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    RM <?php echo number_format($balances['closing_bank'] ?? 0, 2); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-university fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <hr class="my-2">
                        <small class="text-muted">
                            Baki Awal: RM <?php echo number_format($balances['opening_bank'] ?? 0, 2); ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Jumlah Terimaan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    RM <?php echo number_format(($balances['total_cash_in'] ?? 0) + ($balances['total_bank_in'] ?? 0), 2); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <hr class="my-2">
                        <small class="text-muted">
                            Tunai: RM <?php echo number_format($balances['total_cash_in'] ?? 0, 2); ?> | 
                            Bank: RM <?php echo number_format($balances['total_bank_in'] ?? 0, 2); ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Jumlah Bayaran
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    RM <?php echo number_format(($balances['total_cash_out'] ?? 0) + ($balances['total_bank_out'] ?? 0), 2); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <hr class="my-2">
                        <small class="text-muted">
                            Tunai: RM <?php echo number_format($balances['total_cash_out'] ?? 0, 2); ?> | 
                            Bank: RM <?php echo number_format($balances['total_bank_out'] ?? 0, 2); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Balance Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="mb-0">Jumlah Baki Keseluruhan (Total Balance)</h4>
                            </div>
                            <div class="col-auto">
                                <h2 class="mb-0">RM <?php echo number_format($balances['total_balance'] ?? 0, 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <h5 class="mb-3">Menu Pantas</h5>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            <a href="<?php echo url('financial/deposit-account'); ?>" class="btn btn-success">
                <i class="fas fa-hand-holding-usd"></i> Akaun Terimaan
            </a>
            <a href="<?php echo url('financial/payment-account'); ?>" class="btn btn-danger">
                <i class="fas fa-file-invoice-dollar"></i> Akaun Bayaran
            </a>
            <a href="<?php echo url('financial/cash-book'); ?>" class="btn btn-info">
                <i class="fas fa-book"></i> Buku Tunai
            </a>
            <a href="<?php echo url('financial/statement'); ?>" class="btn btn-warning">
                <i class="fas fa-chart-bar"></i> Penyata Kewangan
            </a>
            <a href="<?php echo url('financial/settings'); ?>" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Tetapan Kewangan
            </a>
        </div>
    </div>
</div>
