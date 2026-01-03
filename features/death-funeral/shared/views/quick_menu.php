<?php
// Shared Quick Menu for Death & Funeral feature
?>

<div class="bento-card bento-2x2">
    <h3 class="bento-title">⚡ Quick Menu</h3>
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; flex: 1;">
        <a href="<?php echo url('death-funeral/verify-death'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-user-check"></i></span>
            <span style="font-size: 0.95rem;">Verify Death<br><small>Pengesahan Kematian</small></span>
        </a>
        <a href="<?php echo url('death-funeral/manage-notifications'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-bell"></i></span>
            <span style="font-size: 0.95rem;">Manage Notifications<br><small>Kawalan Notifikasi</small></span>
        </a>
        <a href="<?php echo url('death-funeral/funeral-logistics'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-truck"></i></span>
            <span style="font-size: 0.95rem;">Funeral Logistics<br><small>Logistik Jenazah</small></span>
        </a>
        <a href="<?php echo url('death-funeral/record-logistics'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-clipboard-list"></i></span>
            <span style="font-size: 0.95rem;">Record Logistics<br><small>Rekod Logistik</small></span>
        </a>
        <!-- Approve Assistance removed per request -->
    </div>
</div>
