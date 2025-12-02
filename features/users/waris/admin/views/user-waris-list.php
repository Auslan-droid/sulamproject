<div class="card page-card" style="max-width:800px;">
    <div style="margin-bottom: 1.5rem;">
        <a href="/users" style="text-decoration: none; color: var(--muted); font-size: 0.9rem;">&larr; Back to Users</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="notice error"><?php echo e($error); ?></div>
    <?php else: ?>
        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 0.5rem;">Family & Next of Kin for <?php echo e($targetUser['name']); ?></h2>
            <div style="color: var(--muted); font-size: 0.95rem;">
                <span>@<?php echo e($targetUser['username']); ?></span> &bull; 
                <span><?php echo e($targetUser['email']); ?></span> &bull; 
                <span><?php echo e($targetUser['phone_number'] ?? 'No Phone'); ?></span>
            </div>
        </div>

        <!-- Dependents Section -->
        <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Dependents</h3>
        <?php if (empty($dependentsList)): ?>
            <div class="card card--muted" style="text-align: center; padding: 2rem; margin-bottom: 2rem;">
                <p>No dependents registered.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: 1rem; margin-bottom: 2rem;">
                <?php foreach ($dependentsList as $dep): ?>
                    <div class="card">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem;"><?php echo e($dep['name']); ?> <span class="badge badge-secondary" style="font-size: 0.8rem;"><?php echo e($dep['relationship']); ?></span></h4>
                                <div style="display: grid; gap: 0.3rem; color: var(--text-color);">
                                    <?php if (!empty($dep['phone_number'])): ?>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="opacity: 0.7;">📞</span> 
                                            <a href="tel:<?php echo e($dep['phone_number']); ?>" style="color: inherit; text-decoration: none; border-bottom: 1px dotted var(--muted);"><?php echo e($dep['phone_number']); ?></a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($dep['email'])): ?>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="opacity: 0.7;">✉️</span> 
                                            <span><?php echo e($dep['email']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Next of Kin Section -->
        <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Next of Kin (Emergency)</h3>
        <?php if (empty($nextOfKinList)): ?>
            <div class="card card--muted" style="text-align: center; padding: 2rem;">
                <p>No next of kin registered.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: 1rem;">
                <?php foreach ($nextOfKinList as $kin): ?>
                    <div class="card">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem;"><?php echo e($kin['name']); ?> <span class="badge badge-secondary" style="font-size: 0.8rem;"><?php echo e($kin['relationship']); ?></span></h4>
                                <div style="display: grid; gap: 0.3rem; color: var(--text-color);">
                                    <?php if (!empty($kin['phone_number'])): ?>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="opacity: 0.7;">📞</span> 
                                            <a href="tel:<?php echo e($kin['phone_number']); ?>" style="color: inherit; text-decoration: none; border-bottom: 1px dotted var(--muted);"><?php echo e($kin['phone_number']); ?></a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($kin['email'])): ?>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span style="opacity: 0.7;">✉️</span> 
                                            <span><?php echo e($kin['email']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($kin['address'])): ?>
                                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; margin-top: 0.3rem;">
                                            <span style="opacity: 0.7;">🏠</span> 
                                            <span style="font-size: 0.9rem; opacity: 0.9;"><?php echo e($kin['address']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
