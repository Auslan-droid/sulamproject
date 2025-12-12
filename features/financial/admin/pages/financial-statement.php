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

// Add page-specific CSS (including stat cards via financial.css)
$additionalStyles = [
    url('features/financial/admin/assets/css/financial.css'),
];

// 1. Capture the inner content
ob_start();
?>

<div class="content-container">
    <!-- Filter Card (Styleguide Pattern) -->
    <div class="card card--filter mb-4" id="statementFilter">
        <!-- Filter Header -->
        <div class="filter-header">
            <div class="filter-icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h4 class="filter-title">Financial Statement Generator</h4>
            <button type="button" class="filter-collapse-toggle" aria-label="Toggle filters" onclick="toggleStatementFilter()">
                <i class="fas fa-chevron-down" id="statementFilterIcon"></i>
            </button>
        </div>

        <!-- Filter Content (Collapsible) -->
        <div id="statementFilterContent" style="display: block;">
            <form action="<?php echo url('financial/statement-print'); ?>" method="GET" target="_blank" id="statementForm">
                <!-- Hidden inputs for actual submission -->
                <input type="hidden" name="start_date" id="real_start_date" value="<?php echo $startDate; ?>">
                <input type="hidden" name="end_date" id="real_end_date" value="<?php echo $endDate; ?>">

                <!-- Report Period Section -->
                <div class="filter-section">
                    <div class="filter-section-header" onclick="toggleStatementSection(this)">
                        <span class="filter-section-title">Report Period</span>
                        <i class="fas fa-chevron-down filter-section-icon"></i>
                    </div>
                    <div class="filter-section-content">
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">Period Type</label>
                            <select class="form-control" id="report_type" name="report_type" style="width: 100%;">
                                <option value="monthly" selected>Monthly</option>
                                <option value="annual">Annual (Whole Year)</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>

                        <!-- Monthly/Annual Controls -->
                        <div id="period_controls">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">Year</label>
                                    <select class="form-control" id="select_year" style="width: 100%;">
                                        <?php 
                                        $currentYear = date('Y');
                                        for ($y = $currentYear - 2; $y <= $currentYear + 1; $y++) {
                                            $selected = ($y == $currentYear) ? 'selected' : '';
                                            echo "<option value='$y' $selected>$y</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group" id="month_container" style="margin-bottom: 0;">
                                    <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">Month</label>
                                    <select class="form-control" id="select_month" style="width: 100%;">
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
                        <div id="custom_controls" style="display: none;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">Start Date</label>
                                    <input type="date" class="form-control" id="input_start" value="<?php echo $startDate; ?>" style="width: 100%;">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="display: block; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--text-primary);">End Date</label>
                                    <input type="date" class="form-control" id="input_end" value="<?php echo $endDate; ?>" style="width: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="padding: 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: center; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                        <i class="fas fa-print" style="margin-right: 0.5rem;"></i> Generate Statement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Filter toggle function
function toggleStatementFilter() {
    const content = document.getElementById('statementFilterContent');
    const icon = document.getElementById('statementFilterIcon');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.style.display = 'none';
        icon.style.transform = 'rotate(-90deg)';
    }
}

// Section toggle function
function toggleStatementSection(header) {
    const content = header.nextElementSibling;
    const icon = header.querySelector('.filter-section-icon');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.style.display = 'none';
        icon.style.transform = 'rotate(-90deg)';
    }
}

// Date calculation logic
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
