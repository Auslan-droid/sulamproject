<?php
/**
 * Financial Statement Page (Penyata Terimaan dan Bayaran)
 * 
 * Landing page to select date range and generate the financial statement.
 */
$ROOT = dirname(__DIR__, 4);
require_once $ROOT . '/features/shared/lib/auth/session.php';
require_once $ROOT . '/features/shared/lib/utilities/functions.php';

initSecureSession();
requireAuth();
requireAdmin();

// Default dates
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

// Define page header
$pageHeader = [
    'title' => 'Penyata Kewangan (Financial Statement)',
    'subtitle' => 'Generate income and expense summary reports for any period.',
    'breadcrumb' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Financial', 'url' => url('financial')],
        ['label' => 'Penyata Kewangan', 'url' => null],
    ],
    'actions' => [
        ['label' => 'Back', 'icon' => 'fa-arrow-left', 'url' => url('financial'), 'class' => 'btn-secondary'],
    ]
];

// 1. Capture the inner content
ob_start();
?>
<style>
    .financial-statement-title {
        background: linear-gradient(135deg, var(--text-primary), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .filter-body {
        padding: 2rem;
    }
</style>

<div class="card card--filter">
    <div class="filter-header">
        <div class="filter-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <h3 class="filter-title financial-statement-title">Generate Financial Statement</h3>
    </div>
    <div class="filter-body">
        <form action="<?php echo url('financial/statement-print'); ?>" method="GET" target="_blank" id="statementForm">
            <!-- Hidden inputs for actual submission -->
            <input type="hidden" name="start_date" id="real_start_date" value="<?php echo $startDate; ?>">
            <input type="hidden" name="end_date" id="real_end_date" value="<?php echo $endDate; ?>">

            <!-- Report Type Selection -->
            <div class="col-md-12 mb-3">
                <label for="report_type" class="form-label">Report Period</label>
                <select class="form-select form-control" id="report_type" name="report_type">
                    <option value="monthly" selected>Monthly</option>
                    <option value="annual">Annual (Whole Year)</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <div class="row g-4">
                <!-- Monthly/Annual Controls -->
                <div id="period_controls" class="col-12">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="select_year" class="form-label">Year</label>
                            <select class="form-select form-control" id="select_year">
                                <?php 
                                $currentYear = date('Y');
                                for ($y = $currentYear - 2; $y <= $currentYear + 1; $y++) {
                                    $selected = ($y == $currentYear) ? 'selected' : '';
                                    echo "<option value='$y' $selected>$y</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6" id="month_container">
                            <label for="select_month" class="form-label">Month</label>
                            <select class="form-select form-control" id="select_month">
                                <?php 
                                $months = [
                                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                ];
                                $currentMonth = (int)date('m');
                                foreach ($months as $num => $name) {
                                    $selected = ($num == $currentMonth) ? 'selected' : '';
                                    echo "<option value='$num' $selected>$num - $name</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Custom Range Controls -->
                <div id="custom_controls" class="col-12" style="display: none;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="input_start" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="input_start" value="<?php echo $startDate; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="input_end" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="input_end" value="<?php echo $endDate; ?>">
                        </div>
                    </div>
                </div>

                </div>

            <!-- Button Section - Centered relative to card with increased gap -->
            <div class="d-flex justify-content-center mt-5">
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fas fa-print me-2"></i> Generate Statement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('report_type');
    const periodControls = document.getElementById('period_controls');
    const customControls = document.getElementById('custom_controls');
    const monthContainer = document.getElementById('month_container');
    
    const selectYear = document.getElementById('select_year');
    const selectMonth = document.getElementById('select_month');
    const inputStart = document.getElementById('input_start');
    const inputEnd = document.getElementById('input_end');
    
    const realStart = document.getElementById('real_start_date');
    const realEnd = document.getElementById('real_end_date');

    function updateVisibility() {
        const type = reportTypeSelect.value;
        
        if (type === 'custom') {
            periodControls.style.display = 'none';
            customControls.style.display = 'block';
        } else {
            periodControls.style.display = 'block';
            customControls.style.display = 'none';
            
            if (type === 'annual') {
                monthContainer.style.visibility = 'hidden'; // Hide but keep layout
            } else {
                monthContainer.style.visibility = 'visible';
            }
        }
        updateDates();
    }

    function updateDates() {
        const type = reportTypeSelect.value;
        
        if (type === 'monthly') {
            const y = selectYear.value;
            const m = selectMonth.value.toString().padStart(2, '0');
            
            // First day of month
            realStart.value = `${y}-${m}-01`;
            
            // Last day of month
            const lastDay = new Date(y, m, 0).getDate();
            realEnd.value = `${y}-${m}-${lastDay}`;
            
        } else if (type === 'annual') {
            const y = selectYear.value;
            realStart.value = `${y}-01-01`;
            realEnd.value = `${y}-12-31`;
            
        } else if (type === 'custom') {
            realStart.value = inputStart.value;
            realEnd.value = inputEnd.value;
        }
    }

    // Attach listeners
    reportTypeSelect.addEventListener('change', updateVisibility);
    selectYear.addEventListener('change', updateDates);
    selectMonth.addEventListener('change', updateDates);
    inputStart.addEventListener('change', updateDates);
    inputEnd.addEventListener('change', updateDates);
    
    // Init
    updateVisibility();
});
</script>


<?php
$content = ob_get_clean();

// 2. Wrap with dashboard layout
ob_start();
include $ROOT . '/features/shared/components/layouts/app-layout.php';
$content = ob_get_clean();

// 3. Render with base layout
$pageTitle = 'Penyata Kewangan';
include $ROOT . '/features/shared/components/layouts/base.php';
?>
