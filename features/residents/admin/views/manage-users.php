<div class="card page-card">
    <div class="card card--outline" style="margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Filter Users</h3>
            <div>
                <select onchange="window.location.href=this.value" style="padding: 0.5rem; border-radius: 4px; border: 1px solid var(--border-color); background-color: var(--card-bg); color: var(--text-color);">
                    <option value="?" <?php echo $currentRole === null ? 'selected' : ''; ?>>All Users</option>
                    <option value="?role=resident" <?php echo $currentRole === 'resident' ? 'selected' : ''; ?>>Residents</option>
                    <option value="?role=admin" <?php echo $currentRole === 'admin' ? 'selected' : ''; ?>>Admins</option>
                </select>
            </div>
        </div>
    </div>
    
    <div>
        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid var(--border-color);">
                        <th style="padding: 0.5rem;">Name</th>
                        <th style="padding: 0.5rem;">Username</th>
                        <th style="padding: 0.5rem;">Role</th>
                        <th style="padding: 0.5rem;">Email</th>
                        <th style="padding: 0.5rem;">Phone</th>
                        <th style="padding: 0.5rem;">Status</th>
                        <th style="padding: 0.5rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.5rem;"><?php echo e($user['name']); ?></td>
                            <td style="padding: 0.5rem;"><?php echo e($user['username']); ?></td>
                            <td style="padding: 0.5rem;">
                                <span style="
                                    background-color: <?php echo $user['roles'] === 'admin' ? '#eef2ff' : '#f3f4f6'; ?>;
                                    color: <?php echo $user['roles'] === 'admin' ? '#4f46e5' : '#374151'; ?>;
                                    padding: 0.2rem 0.5rem;
                                    border-radius: 4px;
                                    font-size: 0.85rem;
                                    text-transform: capitalize;
                                ">
                                    <?php echo e($user['roles']); ?>
                                </span>
                            </td>
                            <td style="padding: 0.5rem;"><?php echo e($user['email']); ?></td>
                            <td style="padding: 0.5rem;"><?php echo e($user['phone_number'] ?? '-'); ?></td>
                            <td style="padding: 0.5rem;">
                                <?php echo $user['is_deceased'] ? '<span style="color:red;">Deceased</span>' : 'Active'; ?>
                            </td>
                            <td style="padding: 0.5rem;">
                                <?php if ($user['roles'] === 'resident'): ?>
                                    <a href="/admin/waris?user_id=<?php echo $user['id']; ?>" style="
                                        display: inline-block;
                                        padding: 0.3rem 0.6rem;
                                        background-color: var(--primary-color, #4f46e5);
                                        color: white;
                                        text-decoration: none;
                                        border-radius: 4px;
                                        font-size: 0.85rem;
                                        margin-right: 0.5rem;
                                    ">View Waris</a>
                                <?php endif; ?>
                                <a href="<?php echo url('admin/user-edit?id=' . $user['id']); ?>" style="
                                    display: inline-block;
                                    padding: 0.3rem 0.6rem;
                                    background-color: var(--secondary-color, #6b7280);
                                    color: white;
                                    text-decoration: none;
                                    border-radius: 4px;
                                    font-size: 0.85rem;
                                ">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
