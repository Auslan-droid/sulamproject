<div class="card page-card">
    <div class="card-header">
        <h3>Add New Payment Record</h3>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <!-- Date -->
            <div class="form-group">
                <label for="date">Tarikh (Date)</label>
                <input type="date" id="date" name="date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Butiran (Description)</label>
                <input type="text" id="description" name="description" class="form-control" required placeholder="e.g. Bayaran Bil Elektrik">
            </div>

            <!-- Amount -->
            <div class="form-group">
                <label for="amount">Jumlah (Amount) RM</label>
                <input type="number" id="amount" name="amount" class="form-control" step="0.01" min="0" required placeholder="0.00">
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category">Kategori (Category)</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Perayaan Islam">Perayaan Islam</option>
                    <option value="Pengimarahan dan aktiviti masjid">Pengimarahan dan aktiviti masjid</option>
                    <option value="Penyelenggaraan Masjid">Penyelenggaraan Masjid</option>
                    <option value="Keperluan dan Kelengkapan Masjid">Keperluan dan Kelengkapan Masjid</option>
                    <option value="Gaji/Upah/Saguhati/Elaun">Gaji/Upah/Saguhati/Elaun</option>
                    <option value="Sumbangan/Derma">Sumbangan/Derma</option>
                    <option value="Mesyuarat dan Jamuan">Mesyuarat dan Jamuan</option>
                    <option value="Utiliti">Utiliti</option>
                    <option value="Alat tulis dan percetakan">Alat tulis dan percetakan</option>
                    <option value="Pengangkutan Dan Perjalanan">Pengangkutan Dan Perjalanan</option>
                    <option value="Caj Bank">Caj Bank</option>
                    <option value="Lain-lain perbelanjaan">Lain-lain perbelanjaan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="form-actions" style="margin-top: 1.5rem;">
                <button type="button" class="btn btn-primary" onclick="alert('This feature is not connected to the database yet.')">Save Record</button>
                <a href="<?php echo url('financial/payment-account'); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
