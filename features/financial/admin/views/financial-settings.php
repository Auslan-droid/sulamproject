<?php
/**
 * Financial Settings View
 * Variables expected: $settings, $allSettings, $availableYears, $currentYear, $errors, $success
 */
?>

<div class="content-container">
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Berjaya!</strong> Tetapan kewangan telah disimpan.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <strong>Ralat:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Settings Form -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog mr-2"></i>Tetapkan Baki Awal
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="fiscal_year">Tahun Kewangan <span class="text-danger">*</span></label>
                            <select name="fiscal_year" id="fiscal_year" class="form-control" required>
                                <?php foreach ($availableYears as $year): ?>
                                <option value="<?php echo $year; ?>" 
                                    <?php echo ($settings['fiscal_year'] == $year) ? 'selected' : ''; ?>>
                                    <?php echo $year; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Pilih tahun kewangan untuk tetapan baki awal.</small>
                        </div>

                        <div class="form-group">
                            <label for="opening_cash_balance">
                                Baki Awal di Tangan (Opening Cash Balance) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RM</span>
                                </div>
                                <input type="number" 
                                       name="opening_cash_balance" 
                                       id="opening_cash_balance" 
                                       class="form-control" 
                                       step="0.01" 
                                       min="0" 
                                       value="<?php echo number_format($settings['opening_cash_balance'] ?? 0, 2, '.', ''); ?>"
                                       required>
                            </div>
                            <small class="form-text text-muted">Jumlah tunai yang ada pada awal tahun kewangan.</small>
                        </div>

                        <div class="form-group">
                            <label for="opening_bank_balance">
                                Baki Awal di Bank (Opening Bank Balance) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RM</span>
                                </div>
                                <input type="number" 
                                       name="opening_bank_balance" 
                                       id="opening_bank_balance" 
                                       class="form-control" 
                                       step="0.01" 
                                       min="0" 
                                       value="<?php echo number_format($settings['opening_bank_balance'] ?? 0, 2, '.', ''); ?>"
                                       required>
                            </div>
                            <small class="form-text text-muted">Jumlah dalam akaun bank pada awal tahun kewangan.</small>
                        </div>

                        <div class="form-group">
                            <label for="effective_date">Tarikh Berkuatkuasa <span class="text-danger">*</span></label>
                            <input type="date" 
                                   name="effective_date" 
                                   id="effective_date" 
                                   class="form-control" 
                                   value="<?php echo $settings['effective_date'] ?? date('Y-01-01'); ?>"
                                   required>
                            <small class="form-text text-muted">Tarikh baki awal ini berkuatkuasa (biasanya 1 Januari).</small>
                        </div>

                        <div class="form-group">
                            <label for="notes">Nota (Pilihan)</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Sebarang nota atau catatan..."><?php echo htmlspecialchars($settings['notes'] ?? ''); ?></textarea>
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo url('financial'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Tetapan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Settings History -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-history mr-2"></i>Rekod Baki Awal Mengikut Tahun
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($allSettings)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Tiada rekod baki awal lagi.</p>
                        <p class="small">Sila tetapkan baki awal untuk tahun kewangan semasa.</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tahun</th>
                                    <th class="text-right">Baki Tunai</th>
                                    <th class="text-right">Baki Bank</th>
                                    <th class="text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allSettings as $record): ?>
                                <tr class="<?php echo ($record['fiscal_year'] == $currentYear) ? 'table-active' : ''; ?>">
                                    <td>
                                        <strong><?php echo $record['fiscal_year']; ?></strong>
                                        <?php if ($record['fiscal_year'] == $currentYear): ?>
                                        <span class="badge badge-primary ml-1">Semasa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">RM <?php echo number_format($record['opening_cash_balance'], 2); ?></td>
                                    <td class="text-right">RM <?php echo number_format($record['opening_bank_balance'], 2); ?></td>
                                    <td class="text-right font-weight-bold">
                                        RM <?php echo number_format($record['opening_cash_balance'] + $record['opening_bank_balance'], 2); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Panduan
                    </h6>
                    <ul class="mb-0 small">
                        <li class="mb-2">
                            <strong>Baki Awal di Tangan:</strong> Jumlah wang tunai fizikal yang ada pada permulaan tahun kewangan.
                        </li>
                        <li class="mb-2">
                            <strong>Baki Awal di Bank:</strong> Jumlah dalam akaun bank masjid pada permulaan tahun kewangan.
                        </li>
                        <li class="mb-2">
                            <strong>Proses Akhir Tahun:</strong> Pada akhir tahun, baki akhir akan menjadi baki awal untuk tahun berikutnya.
                        </li>
                        <li>
                            <strong>Buku Tunai:</strong> Baki awal akan dipaparkan sebagai baris pertama dalam Buku Tunai.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-set effective date when fiscal year changes
document.getElementById('fiscal_year').addEventListener('change', function() {
    const year = this.value;
    document.getElementById('effective_date').value = year + '-01-01';
});
</script>
