-- Migration: Comprehensive Seed Data for Financial Accounts
-- Date: 2025-12-02
-- Description: Complete test data for financial_payment_accounts and financial_deposit_accounts
--              with all columns including receipt/voucher numbers and payment details
-- Note: Run this after clearing old data from tables (except financial_settings)

-- ============================================================================
-- SECTION 1: DEPOSIT ACCOUNTS (Akaun Terimaan / Official Receipts)
-- ============================================================================

-- Clear existing deposit account data (optional - comment out if not needed)
-- TRUNCATE TABLE `financial_deposit_accounts`;

INSERT INTO `financial_deposit_accounts` (
    `receipt_number`,
    `tx_date`,
    `description`,
    `received_from`,
    `payment_method`,
    `payment_reference`,
    `geran_kerajaan`,
    `sumbangan_derma`,
    `tabung_masjid`,
    `kutipan_jumaat_sadak`,
    `kutipan_aidilfitri_aidiladha`,
    `sewa_peralatan_masjid`,
    `hibah_faedah_bank`,
    `faedah_simpanan_tetap`,
    `sewa_rumah_kedai_tadika_menara`,
    `lain_lain_terimaan`
) VALUES
-- January 2025 - Opening Transactions
('RR/2025/0001', '2025-01-03', 'Kutipan Jumaat Minggu Pertama', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1250.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0002', '2025-01-05', 'Sumbangan Ikhlas Dermawan', 'Haji Ahmad bin Abdullah', 'bank', 'TRF20250105001', 0.00, 5000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0003', '2025-01-10', 'Kutipan Jumaat Minggu Kedua', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1420.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0004', '2025-01-12', 'Sewa Dewan Serbaguna - Majlis Kesyukuran', 'Encik Kamal bin Hassan', 'cheque', 'CHQ8023456', 0.00, 0.00, 0.00, 0.00, 0.00, 350.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0005', '2025-01-15', 'Geran JAIM Tahun 2025', 'Jabatan Agama Islam Melaka', 'bank', 'TRF20250115890', 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),

-- Mid-January Transactions
('RR/2025/0006', '2025-01-17', 'Kutipan Jumaat Minggu Ketiga', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1380.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0007', '2025-01-20', 'Derma Peralatan Masjid', 'Puan Siti Nurhaliza', 'bank', 'TRF20250120234', 0.00, 2500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0008', '2025-01-22', 'Tabung Pembinaan Masjid', 'Dermawan Anonymous', 'cash', NULL, 0.00, 0.00, 3000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0009', '2025-01-24', 'Kutipan Jumaat Minggu Keempat', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1510.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0010', '2025-01-27', 'Faedah Simpanan Tetap - Bank Islam', 'Bank Islam Malaysia Berhad', 'bank', 'INT20250127', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 458.50, 0.00, 0.00),

-- Late January & Early February
('RR/2025/0011', '2025-01-31', 'Kutipan Jumaat Minggu Kelima', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1290.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0012', '2025-02-01', 'Sewa Kedai Tingkat Bawah - Bulan Feb', 'Kedai Runcit Pak Ali', 'bank', 'TRF20250201567', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1200.00, 0.00),
('RR/2025/0013', '2025-02-03', 'Hibah Dari Bank Muamalat', 'Bank Muamalat Malaysia Berhad', 'bank', 'HIB20250203', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 125.00, 0.00, 0.00, 0.00),
('RR/2025/0014', '2025-02-07', 'Kutipan Jumaat Minggu Pertama Feb', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1445.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0015', '2025-02-10', 'Sewa Peralatan PA System - Majlis Perkahwinan', 'Encik Razak bin Osman', 'cash', NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 150.00, 0.00, 0.00, 0.00, 0.00),

-- Mid-February Transactions
('RR/2025/0016', '2025-02-14', 'Kutipan Jumaat Minggu Kedua Feb', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1520.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0017', '2025-02-15', 'Derma Dari Syarikat Perniagaan', 'Syarikat XYZ Sdn Bhd', 'cheque', 'CHQ9087654', 0.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0018', '2025-02-18', 'Sewa Dewan - Kelas Pendidikan Islam', 'Pusat Tahfiz An-Nur', 'bank', 'TRF20250218890', 0.00, 0.00, 0.00, 0.00, 0.00, 500.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0019', '2025-02-21', 'Kutipan Jumaat Minggu Ketiga Feb', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1365.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0020', '2025-02-25', 'Jualan Hasil Program Masjid', 'Program Majlis Tahunan', 'cash', NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 680.00),

-- Late February & March Start
('RR/2025/0021', '2025-02-28', 'Kutipan Jumaat Minggu Keempat Feb', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1480.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0022', '2025-03-01', 'Sewa Rumah Imam - Bulan Mac', 'Imam Masjid', 'bank', 'TRF20250301123', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 800.00, 0.00),
('RR/2025/0023', '2025-03-05', 'Geran Khas Pembinaan Surau', 'Kerajaan Negeri Melaka', 'bank', 'TRF20250305999', 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0024', '2025-03-07', 'Kutipan Jumaat Minggu Pertama Mac', 'Jemaah Masjid', 'cash', NULL, 0.00, 0.00, 0.00, 1555.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('RR/2025/0025', '2025-03-10', 'Derma Pembinaan Tadika', 'Datuk Seri Mahmud', 'cheque', 'CHQ1234567', 0.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);


-- ============================================================================
-- SECTION 2: PAYMENT ACCOUNTS (Akaun Bayaran / Payment Vouchers)
-- ============================================================================

-- Clear existing payment account data (optional - comment out if not needed)
-- TRUNCATE TABLE `financial_payment_accounts`;

INSERT INTO `financial_payment_accounts` (
    `voucher_number`,
    `tx_date`,
    `description`,
    `paid_to`,
    `payee_ic`,
    `payee_bank_name`,
    `payee_bank_account`,
    `payment_method`,
    `payment_reference`,
    `perayaan_islam`,
    `pengimarahan_aktiviti_masjid`,
    `penyelenggaraan_masjid`,
    `keperluan_kelengkapan_masjid`,
    `gaji_upah_saguhati_elaun`,
    `sumbangan_derma`,
    `mesyuarat_jamuan`,
    `utiliti`,
    `alat_tulis_percetakan`,
    `pengangkutan_perjalanan`,
    `caj_bank`,
    `lain_lain_perbelanjaan`
) VALUES
-- January 2025 - Opening Transactions
('PV/2025/0001', '2025-01-04', 'Bayaran Bil Elektrik - Bulan Disember 2024', 'TNB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250104001', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 385.50, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0002', '2025-01-05', 'Bayaran Bil Air - Bulan Disember 2024', 'SAMB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250105002', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 125.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0003', '2025-01-07', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Mohamad bin Ahmad', '750812-10-5432', 'Bank Islam', '1234567890123', 'bank', 'TRF20250107003', 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0004', '2025-01-08', 'Pembelian Alat Tulis Pejabat', 'Kedai Alat Tulis Mesra', NULL, NULL, NULL, 'cash', NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 125.80, 0.00, 0.00, 0.00),
('PV/2025/0005', '2025-01-10', 'Penyelenggaraan Kipas Siling Dewan', 'Syarikat Elektrik Jaya', NULL, NULL, NULL, 'cheque', 'CHQ7890123', 0.00, 0.00, 450.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),

-- Mid-January Transactions
('PV/2025/0006', '2025-01-12', 'Gaji Kakitangan Masjid - Bulan Januari', 'Encik Roslan bin Hassan (Imam)', '680523-10-1234', 'Maybank', '5678901234567', 'bank', 'TRF20250112006', 0.00, 0.00, 0.00, 0.00, 1500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0007', '2025-01-12', 'Gaji Kakitangan Masjid - Bulan Januari', 'Encik Ibrahim bin Yusof (Bilal)', '720815-10-5678', 'CIMB Bank', '8901234567890', 'bank', 'TRF20250112007', 0.00, 0.00, 0.00, 0.00, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0008', '2025-01-12', 'Gaji Kakitangan Masjid - Bulan Januari', 'Puan Fatimah binti Abdullah (Pembersih)', '850920-10-9012', 'RHB Bank', '2345678901234', 'bank', 'TRF20250112008', 0.00, 0.00, 0.00, 0.00, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0009', '2025-01-15', 'Pembelian Karpet Masjid Baru', 'Kedai Karpet Al-Hijrah', NULL, NULL, NULL, 'cheque', 'CHQ8901234', 0.00, 0.00, 0.00, 2800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0010', '2025-01-18', 'Bayaran Percetakan Banner Program Tahunan', 'Percetakan Mutiara', NULL, NULL, NULL, 'cash', NULL, 0.00, 350.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),

-- Late January Transactions
('PV/2025/0011', '2025-01-20', 'Saguhati Penceramah Kuliah Jumaat', 'Ustazah Aisyah binti Zainal', '821205-10-3456', 'Bank Muamalat', '4567890123456', 'bank', 'TRF20250120011', 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0012', '2025-01-22', 'Derma Bantuan Keluarga Asnaf', 'Keluarga Encik Ahmad bin Salleh', '650710-10-7890', NULL, NULL, 'cash', NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 300.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0013', '2025-01-25', 'Penyelenggaraan Aircond Dewan', 'Syarikat Penghawa Dingin Sejuk', NULL, NULL, NULL, 'cheque', 'CHQ9012345', 0.00, 0.00, 850.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0014', '2025-01-27', 'Belanja Jamuan Mesyuarat Jawatankuasa', 'Restoran Nasi Kandar Pelita', NULL, NULL, NULL, 'cash', NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 280.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0015', '2025-01-29', 'Caj Pengurusan Akaun Bank - Bulan Januari', 'Bank Islam Malaysia Berhad', NULL, NULL, NULL, 'bank', 'AUTO-DEBIT', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15.00, 0.00),

-- February Transactions
('PV/2025/0016', '2025-02-02', 'Bayaran Bil Elektrik - Bulan Januari 2025', 'TNB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250202016', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 412.30, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0017', '2025-02-03', 'Bayaran Bil Air - Bulan Januari 2025', 'SAMB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250203017', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 138.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0018', '2025-02-05', 'Pembelian Al-Quran dan Buku Terjemahan', 'Kedai Buku Pustaka Islamiah', NULL, NULL, NULL, 'cheque', 'CHQ0123456', 0.00, 0.00, 0.00, 1200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0019', '2025-02-08', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Abdullah bin Omar', '770315-10-2345', 'Bank Rakyat', '6789012345678', 'bank', 'TRF20250208019', 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0020', '2025-02-10', 'Belanja Pengangkutan Program Lawatan', 'Syarikat Bas Sinar Jaya', NULL, NULL, NULL, 'cash', NULL, 0.00, 550.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),

-- Mid-February Transactions
('PV/2025/0021', '2025-02-12', 'Gaji Kakitangan Masjid - Bulan Februari', 'Encik Roslan bin Hassan (Imam)', '680523-10-1234', 'Maybank', '5678901234567', 'bank', 'TRF20250212021', 0.00, 0.00, 0.00, 0.00, 1500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0022', '2025-02-12', 'Gaji Kakitangan Masjid - Bulan Februari', 'Encik Ibrahim bin Yusof (Bilal)', '720815-10-5678', 'CIMB Bank', '8901234567890', 'bank', 'TRF20250212022', 0.00, 0.00, 0.00, 0.00, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0023', '2025-02-12', 'Gaji Kakitangan Masjid - Bulan Februari', 'Puan Fatimah binti Abdullah (Pembersih)', '850920-10-9012', 'RHB Bank', '2345678901234', 'bank', 'TRF20250212023', 0.00, 0.00, 0.00, 0.00, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0024', '2025-02-14', 'Perbelanjaan Program Maulidur Rasul', 'Pelbagai Vendor', NULL, NULL, NULL, 'cash', NULL, 1500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0025', '2025-02-16', 'Bayaran Perkhidmatan Internet - Bulan Februari', 'TM Unifi', NULL, NULL, NULL, 'bank', 'TRF20250216025', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 159.00, 0.00, 0.00, 0.00, 0.00),

-- Late February & Early March
('PV/2025/0026', '2025-02-20', 'Penyelenggaraan Cat Dinding Luar Masjid', 'Syarikat Cat & Dekorasi', NULL, NULL, NULL, 'cheque', 'CHQ1234567', 0.00, 0.00, 2500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0027', '2025-02-22', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Zainuddin bin Ali', '791018-10-4567', 'Bank Islam', '7890123456789', 'bank', 'TRF20250222027', 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0028', '2025-02-25', 'Derma Bantuan Keluarga Asnaf', 'Keluarga Puan Maimunah binti Hassan', '721125-10-8901', NULL, NULL, 'cash', NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0029', '2025-02-27', 'Caj Pengurusan Akaun Bank - Bulan Februari', 'Bank Islam Malaysia Berhad', NULL, NULL, NULL, 'bank', 'AUTO-DEBIT', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15.00, 0.00),
('PV/2025/0030', '2025-03-01', 'Pembelian Peralatan Sound System Baru', 'Kedai Elektronik Harmoni', NULL, NULL, NULL, 'cheque', 'CHQ2345678', 0.00, 0.00, 0.00, 3500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),

-- Early March Transactions
('PV/2025/0031', '2025-03-04', 'Bayaran Bil Elektrik - Bulan Februari 2025', 'TNB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250304031', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 398.75, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0032', '2025-03-05', 'Bayaran Bil Air - Bulan Februari 2025', 'SAMB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250305032', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 142.50, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0033', '2025-03-08', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Hafiz bin Mahmud', '830722-10-5678', 'Bank Muamalat', '8901234567890', 'bank', 'TRF20250308033', 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00),
('PV/2025/0034', '2025-03-10', 'Belanja Perjalanan Mesyuarat Luar Negeri', 'Pengerusi Masjid - Encik Azman', '650210-10-1234', NULL, NULL, 'bank', 'TRF20250310034', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 850.00, 0.00, 0.00),
('PV/2025/0035', '2025-03-12', 'Pembelian Penyaman Udara (Aircond) Bilik Imam', 'Syarikat Elektrik Sejuk Beku', NULL, NULL, NULL, 'cheque', 'CHQ3456789', 0.00, 0.00, 0.00, 2200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);

-- ============================================================================
-- SUMMARY OF SEED DATA
-- ============================================================================
-- Deposit Accounts (financial_deposit_accounts): 25 records
--   - Coverage: January to March 2025
--   - Payment methods: Cash, Bank, Cheque
--   - All columns populated with diverse test data
--
-- Payment Accounts (financial_payment_accounts): 35 records
--   - Coverage: January to March 2025
--   - Payment methods: Cash, Bank, Cheque
--   - All columns populated including payee details
--   - Includes recurring expenses (salaries, utilities, saguhati)
--
-- Total Records: 60 comprehensive test transactions
-- ============================================================================
