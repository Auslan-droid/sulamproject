<div class="card page-card">
    <div class="card-header">
        <h3>Add New Deposit Record</h3>
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
                <input type="text" id="description" name="description" class="form-control" required placeholder="e.g. Kutipan Jumaat">
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
                    <option value="Geran Kerajaan">Geran Kerajaan</option>
                    <option value="Sumbangan/Derma">Sumbangan/Derma</option>
                    <option value="Tabung Masjid">Tabung Masjid</option>
                    <option value="Kutipan Jumaat (Sadak)">Kutipan Jumaat (Sadak)</option>
                    <option value="Kutipan Aidilfitri/Aidiladha">Kutipan Aidilfitri/Aidiladha</option>
                    <option value="Sewa Peralatan Masjid">Sewa Peralatan Masjid</option>
                    <option value="Hibah/Faedah bank">Hibah/Faedah bank</option>
                    <option value="Faedah Simpanan Tetap">Faedah Simpanan Tetap</option>
                    <option value="Sewa (Rumah/Kedai/Tadika/Menara)">Sewa (Rumah/Kedai/Tadika/Menara)</option>
                    <option value="Lain-lain terimaan">Lain-lain terimaan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="form-actions" style="margin-top: 1.5rem;">
                <button type="button" class="btn btn-success" onclick="alert('This feature is not connected to the database yet.')">Save Record</button>
                <a href="<?php echo url('financial/deposit-account'); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
