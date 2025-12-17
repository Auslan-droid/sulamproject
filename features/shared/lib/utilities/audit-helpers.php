<?php
/**
 * Audit Trail Helper Functions
 * 
 * Helper functions for displaying audit trail information in views
 */

/**
 * Generate audit info icon that triggers the shared modal
 * 
 * @param array|null $auditInfo Audit information from repository
 * @return string HTML for the audit icon with data attributes
 */
function renderAuditIcon($auditInfo) {
    // Return empty if no audit info
    if (!$auditInfo) {
        return '';
    }
    
    // Check if we have required fields
    if (empty($auditInfo['username']) && empty($auditInfo['user_fullname'])) {
        return '';
    }
    
    $username = htmlspecialchars($auditInfo['username'] ?? 'Unknown');
    $fullname = htmlspecialchars($auditInfo['user_fullname'] ?? 'Unknown User');
    $timestamp = $auditInfo['created_at'] ?? '';
    
    // Format timestamp
    $formattedTime = '';
    if ($timestamp) {
        try {
            $dt = new DateTime($timestamp);
            $formattedTime = $dt->format('d/m/Y h:i A');
        } catch (Exception $e) {
            $formattedTime = $timestamp;
        }
    }
    
    // Output icon with data attributes
    // We avoid inline onClick to keep it clean and use event delegation or direct binding
    $html = <<<HTML
<button type="button" class="audit-info-icon" 
        onclick="showAuditModal(this)"
        data-username="{$username}" 
        data-fullname="{$fullname}" 
        data-time="{$formattedTime}">
    <i class="fas fa-info"></i>
</button>
HTML;
    
    return $html;
}

/**
 * Render the shared audit modal HTML structure
 * Should be called once at the bottom of the page
 */
function renderSharedAuditModal() {
    return <<<HTML
<!-- Shared Audit Modal -->
<div class="audit-modal-overlay" id="sharedAuditModal" onclick="closeAuditModal(event)">
    <div class="audit-modal" onclick="event.stopPropagation()">
        <div class="audit-modal-header">
            <h3 class="audit-modal-title">Audit Information</h3>
            <button class="audit-modal-close" onclick="closeAuditModal()">&times;</button>
        </div>
        <div class="audit-modal-body">
            <div class="audit-info-row">
                <span class="audit-info-label">Created By</span>
                <span class="audit-info-value audit-info-username" id="auditModalUsername"></span>
            </div>
            <div class="audit-info-row">
                <span class="audit-info-label">Full Name</span>
                <span class="audit-info-value" id="auditModalFullname"></span>
            </div>
            <div class="audit-divider"></div>
            <div class="audit-info-row">
                <span class="audit-info-label">Created At</span>
                <span class="audit-info-value audit-info-time" id="auditModalTime"></span>
            </div>
        </div>
    </div>
</div>

<script>
function showAuditModal(btn) {
    // Get data from button
    const username = btn.getAttribute('data-username');
    const fullname = btn.getAttribute('data-fullname');
    const time = btn.getAttribute('data-time');
    
    // Populate modal
    document.getElementById('auditModalUsername').textContent = '@' + username;
    document.getElementById('auditModalFullname').textContent = fullname;
    document.getElementById('auditModalTime').textContent = time;
    
    // Show modal
    const modal = document.getElementById('sharedAuditModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
    
    // Move to body if not already (ensures z-index works)
    if (modal.parentNode !== document.body) {
        document.body.appendChild(modal);
    }
}

function closeAuditModal(event) {
    if (event) {
        // If clicked on overlay (event.target === event.currentTarget)
        if (event.target !== event.currentTarget) return;
    }
    
    const modal = document.getElementById('sharedAuditModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

// Close on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAuditModal();
    }
});
</script>
HTML;
}

/**
 * Format audit timestamp for display
 */
function formatAuditTimestamp($timestamp, $format = 'd/m/Y h:i A') {
    if (empty($timestamp)) {
        return '-';
    }
    try {
        $dt = new DateTime($timestamp);
        return $dt->format($format);
    } catch (Exception $e) {
        return '-';
    }
}
