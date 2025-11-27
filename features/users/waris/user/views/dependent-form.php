<div class="card small-card" style="max-width:600px;margin:0 auto;">
    <h3><?php echo isset($dependent['id']) ? 'Edit Dependent' : 'Add Dependent'; ?></h3>
    
    <?php if (isset($error)): ?>
        <div class="notice error" style="margin-bottom: 1rem;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <?php if (isset($dependent['id'])): ?>
            <input type="hidden" name="id" value="<?php echo $dependent['id']; ?>">
        <?php endif; ?>

        <div class="form-group full-width">
            <label for="name">Full Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($dependent['name'] ?? ''); ?>" required class="form-control">
        </div>

        <div class="form-group full-width">
            <label for="relationship">Relationship <span class="required">*</span></label>
            <select id="relationship" name="relationship" required class="form-control">
                <option value="">Select Relationship</option>
                <?php 
                $relationships = ['Spouse', 'Child', 'Parent', 'Sibling', 'Grandparent', 'Grandchild', 'Other'];
                foreach ($relationships as $rel): 
                    $selected = ($dependent['relationship'] ?? '') === $rel ? 'selected' : '';
                ?>
                    <option value="<?php echo $rel; ?>" <?php echo $selected; ?>>
                        <?php echo $rel; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($dependent['email'] ?? ''); ?>" class="form-control">
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($dependent['phone_number'] ?? ''); ?>" class="form-control">
        </div>

        <div class="form-group full-width">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" class="form-control"><?php echo htmlspecialchars($dependent['address'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions full-width" style="margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save Dependent</button>
            <a href="<?php echo url('features/users/user/pages/profile.php'); ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .full-width {
        grid-column: 1 / -1;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .form-control {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    .required {
        color: red;
    }
    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
