-- Seed data for Financial Module
-- Date: 2025-11-28

-- 1. Seed Payment Accounts (Akaun Bayaran)
INSERT INTO `financial_payment_accounts` 
(`tx_date`, `description`, `keperluan_kelengkapan_masjid`, `utiliti`, `gaji_upah_saguhati_elaun`) 
VALUES 
('2023-10-25', 'Pembelian Al-Quran Baru', 500.00, 0.00, 0.00),
('2023-10-26', 'Bayaran Bil Elektrik', 0.00, 250.00, 0.00),
('2023-10-27', 'Saguhati Penceramah Jemputan', 0.00, 0.00, 150.00);

-- 2. Seed Deposit Accounts (Akaun Terimaan)
INSERT INTO `financial_deposit_accounts` 
(`tx_date`, `description`, `kutipan_jumaat_sadak`, `sumbangan_derma`, `sewa_peralatan_masjid`) 
VALUES 
('2023-11-03', 'Kutipan Jumaat Minggu 1', 1200.00, 0.00, 0.00),
('2023-11-05', 'Sumbangan Ikhlas', 0.00, 500.00, 0.00),
('2023-11-10', 'Sewa Dewan Serbaguna', 0.00, 0.00, 300.00);
