<div class="card page-card">
    <h2>Verify Death</h2>
    <p class="lead" style="margin-bottom:0.75rem;">Verify reported deaths and review previously verified notifications.</p>

    <div class="alert alert-info" style="margin-bottom:1rem;">
        Pending notifications appear first. Use the Verified List filter to narrow verified results.
    </div>

    <!-- Pending (Unverified) -->
    <section class="pending-section" style="margin-top:1.5rem;">
        <h3>Pending Notifications</h3>

        <?php if (empty($pending)): ?>
            <div class="empty-state">No pending death notifications.</div>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item->deceased_name ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($item->ic_number ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($item->date_of_death ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($item->place_of_death ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($item->next_of_kin_name ?? '-'); ?></td>
                                <td class="table__cell--actions">
                                    <div class="actions">
                                        <button class="btn btn-primary btn-sm" title="Verify" aria-label="Verify" onclick="verifyNotification(<?php echo $item->id; ?>)">
                                            <i class="fas fa-check" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" title="Delete" aria-label="Delete" onclick="deleteNotification(<?php echo $item->id; ?>)">
                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <!-- Verified List filter -->
    <section class="verified-filter-section" style="margin-top:2rem;">
        <h3>Verified List</h3>

        <form method="get" class="form-inline" style="margin-bottom:1.25rem;">
            <label for="year" style="margin-right:.5rem;">Year:</label>
            <select id="year" name="year" class="form-control" style="margin-right:1rem;">
                <option value="">All</option>
                <?php foreach (($years ?? []) as $y): ?>
                    <option value="<?php echo htmlspecialchars($y); ?>" <?php echo (!empty($selectedYear) && (string)$selectedYear === (string)$y) ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="month" style="margin-right:.5rem;">Month:</label>
            <select id="month" name="month" class="form-control" style="margin-right:1rem; margin-bottom:.75rem;">
                <option value="">All</option>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <?php $label = date('F', mktime(0,0,0,$m,1)); ?>
                    <option value="<?php echo $m; ?>" <?php echo (!empty($selectedMonth) && (int)$selectedMonth === $m) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                <?php endfor; ?>
            </select>

            <span style="display:inline-flex;align-items:center;gap:.5rem;">
                <button class="btn btn-secondary btn-sm" type="submit" style="display:inline-flex;align-items:center;">Filter</button>
                <a class="btn btn-primary btn-sm" target="_blank" style="display:inline-flex;align-items:center;" href="<?php echo url('/death-funeral/verified-print') . '?year=' . urlencode($selectedYear ?? '') . '&month=' . urlencode($selectedMonth ?? ''); ?>">Print PDF</a>
            </span>
        </form>

        <?php if (empty($verified)): ?>
            <div class="empty-state">No verified notifications for the selected period.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Deceased Name</th>
                            <th>Date of Death</th>
                            <th>Verified At</th>
                            <th>Verified By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($verified as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item->deceased_name ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($item->date_of_death ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($item->verified_at ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($item->verified_by ?? '-'); ?></td>
                                <td class="table__cell--actions">
                                    <div class="actions">
                                        <button class="btn btn-danger btn-sm" title="Delete" aria-label="Delete" onclick="deleteNotification(<?php echo $item->id; ?>)">
                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

</div>
