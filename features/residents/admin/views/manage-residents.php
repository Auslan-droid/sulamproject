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
            <p>No residents found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Income Class</th>
                            <th class="text-center">Dependents</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo e($user['name']); ?></td>
                                <td><?php echo e($user['username']); ?></td>
                                <td>
                                    <span class="badge <?php echo $user['roles'] === 'admin' ? 'badge-primary' : 'badge-secondary'; ?>">
                                        <?php echo e($user['roles']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $income = $user['income'];
                                        $incomeClass = '-';
                                        if ($income !== null && $income !== '') {
                                            if ($income < 5250) {
                                                $incomeClass = 'B40';
                                            } elseif ($income < 11820) {
                                                $incomeClass = 'M40';
                                            } else {
                                                $incomeClass = 'T20';
                                            }
                                        }
                                        echo $incomeClass;
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php echo isset($user['dependent_count']) ? $user['dependent_count'] : 0; ?>
                                </td>
                                <td><?php echo e($user['email']); ?></td>
                                <td><?php echo e($user['phone_number'] ?? '-'); ?></td>
                                <td>
                                    <?php echo $user['is_deceased'] ? '<span style="color:red;">Deceased</span>' : 'Active'; ?>
                                </td>
                                <td>
                                    <?php if ($user['roles'] === 'resident'): ?>
                                        <a href="/admin/waris?user_id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View Waris</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    .table th, .table td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }
    .table th {
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 2px solid var(--border-color);
    }
    .text-center {
        text-align: center;
    }
    .badge {
        display: inline-block;
        padding: 0.25em 0.6em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }
    .badge-primary {
        color: #fff;
        background-color: var(--primary-color, #4f46e5);
    }
    .badge-secondary {
        color: #374151;
        background-color: #f3f4f6;
    }
</style>
