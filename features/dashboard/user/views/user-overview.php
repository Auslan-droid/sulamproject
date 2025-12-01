<div class="card page-card">
    <section class="dashboard-cards">
        <a class="card dashboard-card card--elevated" href="<?php echo url('profile'); ?>">
            <i class="fa-solid fa-user-edit icon" aria-hidden="true"></i>
            <h3>Edit Profile</h3>
            <p>Update your personal info.</p>
        </a>
    </section>
</div>

<!-- Donations Preview -->
<div class="card" style="max-width: 980px; margin: 2rem auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="margin: 0;">Featured Donation</h3>
        <a href="<?php echo url('donations'); ?>" style="color: var(--accent); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Show more details →</a>
    </div>
    <div style="display: flex; gap: 1.5rem; align-items: center;">
        <div style="width: 120px; height: 120px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fa-solid fa-qrcode" style="font-size: 3rem; color: #d1d5db;"></i>
        </div>
        <div style="flex: 1;">
            <h4 style="margin: 0 0 0.5rem 0;">Mosque Building Fund</h4>
            <p style="margin: 0; color: var(--muted); line-height: 1.6;">Support our community mosque construction project. Scan the QR code to donate.</p>
        </div>
    </div>
</div>

<!-- Events Preview -->
<div class="card" style="max-width: 980px; margin: 2rem auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="margin: 0;">Upcoming Event</h3>
        <a href="<?php echo url('events'); ?>" style="color: var(--accent); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Show more details →</a>
    </div>
    <div style="display: flex; gap: 1.5rem; align-items: center;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #eef6ec 0%, #d1e7dd 100%); border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0;">
            <span style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); line-height: 1;">24</span>
            <span style="font-size: 0.65rem; font-weight: 700; color: var(--accent); text-transform: uppercase; margin-top: 0.2rem;">NOV</span>
        </div>
        <div style="flex: 1;">
            <h4 style="margin: 0 0 0.5rem 0;">Community Gathering</h4>
            <p style="margin: 0; color: var(--muted); line-height: 1.6;">Friday, 8:00 PM • Main Hall</p>
        </div>
    </div>
</div>
