<div class="card small-card" style="max-width:800px;margin:0 auto;">
    
    <?php if (isset($success)): ?>
        <div class="notice success" style="margin-bottom: 1rem;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="notice error" style="margin-bottom: 1rem;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" disabled class="form-control" style="background-color: #f5f5f5; cursor: not-allowed;">
            <small class="text-muted">Username cannot be changed.</small>
        </div>

        <div class="form-group">
            <label for="name">Full Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required class="form-control">
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" class="form-control">
        </div>

        <div class="form-group full-width">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" class="form-control"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="marital_status">Marital Status</label>
            <select id="marital_status" name="marital_status" class="form-control">
                <option value="">Select Status</option>
                <?php 
                $statuses = ['single', 'married', 'divorced', 'widowed', 'others'];
                foreach ($statuses as $status): 
                    $selected = ($user['marital_status'] ?? '') === $status ? 'selected' : '';
                ?>
                    <option value="<?php echo $status; ?>" <?php echo $selected; ?>>
                        <?php echo ucfirst($status); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="income">Monthly Income (MYR)</label>
            <input type="number" id="income" name="income" step="0.01" value="<?php echo htmlspecialchars($user['income'] ?? ''); ?>" class="form-control">
        </div>

        <div class="form-actions full-width" style="margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?php echo url('dashboard'); ?>" class="btn btn-secondary">Cancel</a>
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
    .text-muted {
        color: #666;
        font-size: 0.85rem;
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
