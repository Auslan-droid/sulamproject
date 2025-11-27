<div class="card page-card">
    <?php if (empty($donations)): ?>
        <div class="card card--elevated" style="margin-top: 2rem; text-align: center; padding: 3rem;">
            <i class="fa-solid fa-box-open fa-3x" style="color: #d1d5db; margin-bottom: 1rem;"></i>
            <p>No active donation campaigns at the moment.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
            <?php foreach ($donations as $donation): ?>
                <div class="card card--elevated" style="display: flex; flex-direction: column; padding: 0; overflow: hidden;">
                    <?php if (!empty($donation['image_path'])): ?>
                        <img src="<?php echo url($donation['image_path']); ?>" alt="<?php echo e($donation['title']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-hand-holding-heart fa-3x" style="color: #d1d5db;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="margin-top: 0; margin-bottom: 0.5rem; font-size: 1.25rem;"><?php echo e($donation['title']); ?></h3>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.5rem; flex: 1; line-height: 1.5;"><?php echo nl2br(e($donation['description'])); ?></p>
                        
                        <div style="margin-top: auto;">
                            <button class="btn btn-primary" style="width: 100%;">Donate Now</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
