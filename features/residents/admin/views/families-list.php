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
            <table class="table table-striped">
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

<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    /* Custom table styles for bigger/looser layout */
    .table {
        width: 100%;
        border-collapse: separate; /* Allows border-spacing if needed, or use collapse */
        border-spacing: 0;
        margin-bottom: 1rem;
    }
    
    .table th, .table td {
        padding: 1.25rem 1.5rem; /* Generous padding for "not compact" look */
        vertical-align: middle;
        border-top: 1px solid #eee;
        font-size: 1rem;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd;
        font-weight: 600;
        background-color: #f9fafb;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
    }

    .badge-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.6rem;
    }
    
    @media print {
        .actions, .sidebar, .page-header {
            display: none !important;
        }
        .page-wrapper {
            margin: 0;
            padding: 0;
        }
        .content {
            margin: 0;
            padding: 0;
        }
    }
</style>
