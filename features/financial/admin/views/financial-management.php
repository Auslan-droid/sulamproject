<div class="card page-card">
    <div class="card-header">
        <h3>Financial Overview</h3>
    </div>
    <div class="card-body">
        <p>Welcome to the Financial Management module.</p>
        
        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <a href="<?php echo url('financial/payment-account'); ?>" class="btn btn-primary">
                <i class="fas fa-file-invoice-dollar"></i> Akaun Bayaran
            </a>
            <a href="<?php echo url('financial/deposit-account'); ?>" class="btn btn-success">
                <i class="fas fa-hand-holding-usd"></i> Akaun Terimaan
            </a>
            <a href="<?php echo url('financial/cash-book'); ?>" class="btn btn-info">
                <i class="fas fa-book"></i> Buku Tunai
            </a>
        </div>
    </div>
</div>
