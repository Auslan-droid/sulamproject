<?php if (!empty($donationsError)): ?>
    <div class="card page-card">
        <div class="notice error" style="margin-top: 1rem;">
            <?php echo htmlspecialchars($donationsError); ?>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($donations) && count($donations) > 0): ?>
    <div class="card page-card">
        <div class="card card--elevated" style="margin-top: 2rem;">
            <h3>Donations</h3>
            <div class="card-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1rem;">
                <?php foreach ($donations as $d): ?>
                    <div class="card card--elevated">
                        <?php if (!empty($d['image_path'])): ?>
                            <img src="<?php echo url('/' . htmlspecialchars($d['image_path'])); ?>" alt="<?php echo htmlspecialchars($d['title']); ?>" style="width:100%; height:auto; object-fit:contain; border-radius: 6px 6px 0 0;" />
                        <?php endif; ?>
                        <div class="card-body" style="padding: 1rem;">
                            <h4 style="margin: 0 0 .5rem;">
                                <?php echo htmlspecialchars($d['title']); ?>
                            </h4>
                            <p style="color:#555;">
                                <?php echo nl2br(htmlspecialchars($d['description'])); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- No placeholder shown when there are no donations -->
<?php endif; ?>
