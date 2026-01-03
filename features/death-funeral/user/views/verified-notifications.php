<?php
echo '<div class="card page-card">';
?>
    <h2>Verified Death Notifications</h2>
    <p>Community death notifications that have been verified by administrators.</p>

    <form method="get" class="form-inline" style="margin: .75rem 0 1rem; display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
        <label for="year" style="margin-right:.5rem;">Year:</label>
        <select id="year" name="year" class="form-control" style="margin-right:1rem;">
            <option value="">All</option>
            <?php foreach (($years ?? []) as $y): ?>
                <option value="<?php echo htmlspecialchars($y); ?>" <?php echo (!empty($selectedYear) && (string)$selectedYear === (string)$y) ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="month" style="margin-right:.5rem;">Month:</label>
        <select id="month" name="month" class="form-control" style="margin-right:1rem;">
            <option value="">All</option>
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <?php $label = date('F', mktime(0,0,0,$m,1)); ?>
                <option value="<?php echo $m; ?>" <?php echo (!empty($selectedMonth) && (int)$selectedMonth === $m) ? 'selected' : ''; ?>><?php echo $label; ?></option>
            <?php endfor; ?>
        </select>

        <button class="btn btn-secondary btn-sm" type="submit" style="display:inline-flex;align-items:center;">Filter</button>
    </form>
<?php
    if (empty($verifiedNotifications)): ?>
        <div class="empty-state">
            <p>No verified death notifications at this time.</p>
        echo '</div>';
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Deceased Name</th>
                        <th>IC Number</th>
                        <th>Date of Death</th>
                        <th>Place of Death</th>
                        <th>Next of Kin</th>
                        <th>Verified Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifiedNotifications as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item->deceased_name ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($item->ic_number ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($item->date_of_death ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($item->place_of_death ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($item->next_of_kin_name ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($item->verified_at ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>