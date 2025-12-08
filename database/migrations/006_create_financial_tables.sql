-- Migration: Create financial tables for Akaun Bayaran and Akaun Terimaan
-- Date: 2025-11-28

-- Table for Payment Accounts (Akaun Bayaran)
CREATE TABLE IF NOT EXISTS `financial_payment_accounts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tx_date` DATE NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `perayaan_islam` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `pengimarahan_aktiviti_masjid` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `penyelenggaraan_masjid` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `keperluan_kelengkapan_masjid` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `gaji_upah_saguhati_elaun` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `sumbangan_derma` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `mesyuarat_jamuan` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `utiliti` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `alat_tulis_percetakan` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `pengangkutan_perjalanan` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `caj_bank` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `lain_lain_perbelanjaan` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_tx_date` (`tx_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for Deposit Accounts (Akaun Terimaan)
CREATE TABLE IF NOT EXISTS `financial_deposit_accounts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tx_date` DATE NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `geran_kerajaan` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `sumbangan_derma` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `tabung_masjid` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `kutipan_jumaat_sadak` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `kutipan_aidilfitri_aidiladha` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `sewa_peralatan_masjid` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `hibah_faedah_bank` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `faedah_simpanan_tetap` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `sewa_rumah_kedai_tadika_menara` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `lain_lain_terimaan` DECIMAL(12,2) UNSIGNED DEFAULT 0.00,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_tx_date` (`tx_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
