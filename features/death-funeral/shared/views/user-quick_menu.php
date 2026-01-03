<?php
// User Quick Menu styled like admin bento layout
?>

<div class="bento-card bento-2x2">
    <h3 class="bento-title">⚡ Quick Menu</h3>
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; flex: 1;">
        <a href="<?php echo url('death-funeral/record-notification'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-pen"></i></span>
            <span style="font-size: 0.95rem;">Record Notification<br><small>Rekod Notifikasi</small></span>
        </a>

        <a href="<?php echo url('death-funeral/verified-notifications'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-check-circle"></i></span>
            <span style="font-size: 0.95rem;">Verified Notifications<br><small>Notifikasi Disahkan</small></span>
        </a>

        <a href="<?php echo url('death-funeral/my-notifications'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-list"></i></span>
            <span style="font-size: 0.95rem;">My Notifications<br><small>Notifikasi Saya</small></span>
        </a>

        <a href="<?php echo url('death-funeral/funeral-logistics'); ?>" class="bento-btn" style="padding: 1.25rem;">
            <span style="font-size: 1.75rem;"><i class="fas fa-truck"></i></span>
            <span style="font-size: 0.95rem;">Funeral Logistics<br><small>Logistik Jenazah</small></span>
        </a>
    </div>
</div>
