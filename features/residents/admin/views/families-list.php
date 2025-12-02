<div class="card page-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Family Registry</h2>
        <div class="actions">
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Print List
            </button>
        </div>
    </div>

    <?php if (empty($families)): ?>
        <div class="notice">No families found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table--families">
                <thead>
                    <tr>
                        <th>Head of Family</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Dependents</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($families as $family): ?>
                        <tr>
                            <td>
                                <div style="font-weight: bold;"><?php echo htmlspecialchars($family['name']); ?></div>
                                <div class="text-muted text-sm"><i class="fas fa-id-card"></i> <?php echo htmlspecialchars($family['username']); ?></div>
                            </td>
                            <td>
                                <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($family['phone_number'] ?? '-'); ?></div>
                                <div class="text-muted text-sm"><?php echo htmlspecialchars($family['email'] ?? ''); ?></div>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($family['address'] ?? 'No address provided'); ?>
                            </td>
                            <td>
                                <?php if (empty($family['dependents'])): ?>
                                    <span class="text-muted">No dependents</span>
                                <?php else: ?>
                                    <ul style="padding-left: 1.2rem; margin: 0;">
                                        <?php foreach ($family['dependents'] as $dep): ?>
                                            <li>
                                                <?php echo htmlspecialchars($dep['name']); ?> 
                                                <span class="badge badge-secondary badge-sm"><?php echo htmlspecialchars($dep['relationship']); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="/features/residents/admin/assets/css/residents.css">
