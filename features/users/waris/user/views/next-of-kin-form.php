<div class="card small-card" style="max-width:600px;margin:0 auto;">
    <h3><?php echo isset($nextOfKin['id']) ? 'Edit Next of Kin' : 'Add Next of Kin'; ?></h3>
    
    <?php if (isset($error)): ?>
        <div class="notice error" style="margin-bottom: 1rem;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <?php if (isset($nextOfKin['id'])): ?>
            <input type="hidden" name="id" value="<?php echo $nextOfKin['id']; ?>">
        <?php endif; ?>

        <div class="grid-2">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($nextOfKin['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="relationship">Relationship <span class="required">*</span></label>
                <select id="relationship" name="relationship" required>
                    <option value="">Select Relationship</option>
                    <?php 
                    $relationships = ['Spouse', 'Child', 'Parent', 'Sibling', 'Grandparent', 'Grandchild', 'Friend', 'Colleague', 'Other'];
                    foreach ($relationships as $rel): 
                        $selected = ($nextOfKin['relationship'] ?? '') === $rel ? 'selected' : '';
                    ?>
                        <option value="<?php echo $rel; ?>" <?php echo $selected; ?>>
                            <?php echo $rel; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($nextOfKin['email'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($nextOfKin['phone_number'] ?? ''); ?>">
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($nextOfKin['address'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 1.5rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Save Next of Kin</button>
            <a href="<?php echo url('features/users/user/pages/profile.php'); ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
    .required {
        color: red;
    }
</style>
