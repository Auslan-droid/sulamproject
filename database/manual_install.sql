-- Manual Installation Script for SulamProject
-- Generated on 12/11/2025 11:54:26
-- this script is for easy manual installation of the whole database and all migrations up until 015, any migrations after 015 should be run manually

-- ==========================================
-- Source: database/schema.sql
-- ==========================================

CREATE DATABASE IF NOT EXISTS `masjidkamek` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `masjidkamek`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(120) NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(120) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `roles` ENUM('resident', 'admin') NOT NULL DEFAULT 'resident',
    `phone_number` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `marital_status` ENUM('single','married','divorced','widowed','others') NULL,
    `is_deceased` TINYINT(1) NOT NULL DEFAULT 0,
    `income` DECIMAL(10,2) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_username` (`username`),
    UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tanggungan` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(120) NOT NULL,
    `relationship` VARCHAR(100) DEFAULT NULL,
    `email` VARCHAR(120) NULL,
    `phone_number` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tanggungan_user_id` (`user_id`),
    CONSTRAINT `fk_tanggungan_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `events` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `event_date` DATE NULL,
    `event_time` TIME NULL,
    `location` VARCHAR(255) NULL,
    `image_path` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `donations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `image_path` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `deaths` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `time` TIME NULL,
    `date` DATE NULL,
    `islamic_date` VARCHAR(50) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_deaths_user_id` (`user_id`),
    CONSTRAINT `fk_deaths_user_boot` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Financial Settings for Opening Balances
CREATE TABLE IF NOT EXISTS `financial_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `fiscal_year` YEAR NOT NULL,
    `opening_cash_balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Baki Awal di Tangan',
    `opening_bank_balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Baki Awal di Bank',
    `effective_date` DATE NOT NULL COMMENT 'Tarikh berkuatkuasa baki awal',
    `notes` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_fiscal_year` (`fiscal_year`),
    INDEX `idx_effective_date` (`effective_date`),
    CONSTRAINT `fk_financial_settings_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- Source: database/migrations/001_add_role_column.sql
-- ==========================================

-- Migration: Add role column to users table
-- Date: 2025-11-14

-- Add role column to users table
ALTER TABLE `users` 
ADD COLUMN `role` varchar(20) NOT NULL DEFAULT 'user' AFTER `password_hash`;

-- Update existing users to have user role (if any exist)
UPDATE `users` SET `role` = 'user' WHERE `role` IS NULL OR `role` = '';

-- ==========================================
-- Source: database/migrations/002_update_donations_table.sql
-- ==========================================

ALTER TABLE `donations`
ADD COLUMN `title` VARCHAR(255) NOT NULL DEFAULT 'General Donation' AFTER `id`,
ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `image_path`;

-- ==========================================
-- Source: database/migrations/003_update_events_table.sql
-- ==========================================

ALTER TABLE `events`
ADD COLUMN `title` VARCHAR(255) NOT NULL DEFAULT 'New Event' AFTER `id`,
ADD COLUMN `event_date` DATE NULL AFTER `description`,
ADD COLUMN `event_time` TIME NULL AFTER `event_date`,
ADD COLUMN `location` VARCHAR(255) NULL AFTER `event_time`,
ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `image_path`;

-- ==========================================
-- Source: database/migrations/004_add_relationship_to_next_of_kin.sql
-- ==========================================

ALTER TABLE `next_of_kin` ADD COLUMN `relationship` VARCHAR(50) NULL AFTER `name`;

-- ==========================================
-- Source: database/migrations/004_align_events_schema.sql
-- ==========================================

ALTER TABLE `events`
ADD COLUMN `title` VARCHAR(255) NOT NULL DEFAULT 'New Event' AFTER `id`;

ALTER TABLE `events`
ADD COLUMN `event_date` DATE NULL AFTER `description`;

ALTER TABLE `events`
ADD COLUMN `event_time` TIME NULL AFTER `event_date`;

ALTER TABLE `events`
ADD COLUMN `location` VARCHAR(255) NULL AFTER `event_time`;

-- If legacy column `gamba` exists, rename to `image_path`
SET @col_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'gamba');
SET @q := IF(@col_exists > 0, 'ALTER TABLE `events` CHANGE `gamba` `image_path` VARCHAR(255) NULL;', 'SELECT 1;');
PREPARE stmt FROM @q; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Ensure `image_path` column exists (add if missing)
SET @img_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'image_path');
SET @q2 := IF(@img_exists = 0, 'ALTER TABLE `events` ADD COLUMN `image_path` VARCHAR(255) NULL AFTER `location`;', 'SELECT 1;');
PREPARE stmt2 FROM @q2; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;

-- Add is_active if missing
SET @act_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'events' AND COLUMN_NAME = 'is_active');
SET @q3 := IF(@act_exists = 0, 'ALTER TABLE `events` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `image_path`;', 'SELECT 1;');
PREPARE stmt3 FROM @q3; EXECUTE stmt3; DEALLOCATE PREPARE stmt3;

-- ==========================================
-- Source: database/migrations/005_align_donations_schema.sql
-- ==========================================

ALTER TABLE `donations`
ADD COLUMN `title` VARCHAR(255) NOT NULL DEFAULT 'General Donation' AFTER `id`;

-- Rename legacy gamba to image_path if exists
SET @col_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'donations' AND COLUMN_NAME = 'gamba');
SET @q := IF(@col_exists > 0, 'ALTER TABLE `donations` CHANGE `gamba` `image_path` VARCHAR(255) NULL;', 'SELECT 1;');
PREPARE stmt FROM @q; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Ensure image_path exists
SET @img_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'donations' AND COLUMN_NAME = 'image_path');
SET @q2 := IF(@img_exists = 0, 'ALTER TABLE `donations` ADD COLUMN `image_path` VARCHAR(255) NULL AFTER `description`;', 'SELECT 1;');
PREPARE stmt2 FROM @q2; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;

-- Add is_active if missing
SET @act_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'donations' AND COLUMN_NAME = 'is_active');
SET @q3 := IF(@act_exists = 0, 'ALTER TABLE `donations` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `image_path`;', 'SELECT 1;');
PREPARE stmt3 FROM @q3; EXECUTE stmt3; DEALLOCATE PREPARE stmt3;

-- ==========================================
-- Source: database/migrations/006_create_financial_tables.sql
-- ==========================================

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

-- ==========================================
-- Source: database/migrations/007_seed_financial_data.sql
-- ==========================================

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

-- ==========================================
-- Source: database/migrations/008_create_tanggungan_table.sql
-- ==========================================

CREATE TABLE IF NOT EXISTS `tanggungan` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(120) NOT NULL,
    `relationship` VARCHAR(100) DEFAULT NULL,
    `email` VARCHAR(120) NULL,
    `phone_number` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tanggungan_user_id` (`user_id`),
    CONSTRAINT `fk_tanggungan_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrate data from next_of_kin if it exists
INSERT INTO `tanggungan` (user_id, name, relationship, email, phone_number, address, created_at, updated_at)
SELECT user_id, name, relationship, email, phone_number, address, created_at, updated_at
FROM `next_of_kin`;

-- ==========================================
-- Source: database/migrations/009_rename_tanggungan_to_dependent.sql
-- ==========================================

RENAME TABLE `tanggungan` TO `dependent`;

-- ==========================================
-- Source: database/migrations/010_update_financial_tables.sql
-- ==========================================

-- Migration: Update financial tables for Receipts, Vouchers, and Cash Book
-- Date: 2025-12-02

-- Update financial_deposit_accounts (Akaun Terimaan)
-- Adding fields for Official Receipt (Resit Rasmi) and Cash Book tracking
ALTER TABLE `financial_deposit_accounts`
ADD COLUMN `receipt_number` VARCHAR(50) NULL AFTER `id`,
ADD COLUMN `received_from` VARCHAR(255) NULL AFTER `description`,
ADD COLUMN `payment_method` ENUM('cash', 'bank', 'cheque') NOT NULL DEFAULT 'cash' AFTER `received_from`,
ADD COLUMN `payment_reference` VARCHAR(100) NULL AFTER `payment_method`, -- For Bank Ref / Cheque No
ADD INDEX `idx_receipt_number` (`receipt_number`);

-- Update financial_payment_accounts (Akaun Bayaran)
-- Adding fields for Payment Voucher (Baucar Bayaran) and Cash Book tracking
ALTER TABLE `financial_payment_accounts`
ADD COLUMN `voucher_number` VARCHAR(50) NULL AFTER `id`,
ADD COLUMN `paid_to` VARCHAR(255) NULL AFTER `description`,
ADD COLUMN `payee_ic` VARCHAR(20) NULL AFTER `paid_to`,
ADD COLUMN `payee_bank_name` VARCHAR(100) NULL AFTER `payee_ic`,
ADD COLUMN `payee_bank_account` VARCHAR(50) NULL AFTER `payee_bank_name`,
ADD COLUMN `payment_method` ENUM('cash', 'bank', 'cheque') NOT NULL DEFAULT 'cash' AFTER `payee_bank_account`,
ADD COLUMN `payment_reference` VARCHAR(100) NULL AFTER `payment_method`, -- For Cheque No / Ref
ADD INDEX `idx_voucher_number` (`voucher_number`);

-- ==========================================
-- Source: database/migrations/011_create_financial_settings_table.sql
-- ==========================================

-- Migration: Create financial_settings table for opening balances
-- Date: 2025-12-02
-- Description: Stores opening balances (baki di tangan & baki di bank) per fiscal year

CREATE TABLE IF NOT EXISTS `financial_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `fiscal_year` YEAR NOT NULL,
    `opening_cash_balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Baki Awal di Tangan',
    `opening_bank_balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Baki Awal di Bank',
    `effective_date` DATE NOT NULL COMMENT 'Tarikh berkuatkuasa baki awal',
    `notes` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_fiscal_year` (`fiscal_year`),
    INDEX `idx_effective_date` (`effective_date`),
    CONSTRAINT `fk_financial_settings_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default opening balances for current fiscal year (2025)
-- Adjust these values based on actual opening balances
INSERT INTO `financial_settings` (`fiscal_year`, `opening_cash_balance`, `opening_bank_balance`, `effective_date`, `notes`)
VALUES (2025, 0.00, 0.00, '2025-01-01', 'Baki awal permulaan sistem')
ON DUPLICATE KEY UPDATE `updated_at` = CURRENT_TIMESTAMP;

-- ==========================================
-- Source: database/migrations/012_seed_financial_accounts_comprehensive.sql
-- ==========================================

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

-- ==========================================
-- Source: database/migrations/013_update_payment_method_enum.sql
-- ==========================================

USE masjidkamek;

-- Update existing 'cheque' values to 'bank'
UPDATE financial_deposit_accounts SET payment_method = 'bank' WHERE payment_method = 'cheque';
UPDATE financial_payment_accounts SET payment_method = 'bank' WHERE payment_method = 'cheque';

-- Modify the columns to be ENUM('cash', 'bank')
ALTER TABLE financial_deposit_accounts MODIFY COLUMN payment_method ENUM('cash', 'bank') NOT NULL DEFAULT 'cash';
ALTER TABLE financial_payment_accounts MODIFY COLUMN payment_method ENUM('cash', 'bank') NOT NULL DEFAULT 'cash';

-- ==========================================
-- Source: database/migrations/014_update_deathnotifications.sql
-- ==========================================

-- based on migration-plan.md

CREATE TABLE IF NOT EXISTS `death_notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `deceased_name` VARCHAR(255) NOT NULL,
    `ic_number` VARCHAR(20),
    `date_of_death` DATE NOT NULL,
    `place_of_death` VARCHAR(255),
    `cause_of_death` VARCHAR(255),
    `next_of_kin_name` VARCHAR(255),
    `next_of_kin_phone` VARCHAR(20),

    -- UPDATED LINES BELOW: Added UNSIGNED (and potentially BIGINT)
    `reported_by` INT UNSIGNED, 
    `verified` BOOLEAN DEFAULT FALSE,
    `verified_by` INT UNSIGNED, 
    
    `verified_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (`reported_by`) REFERENCES `users`(`id`),
    FOREIGN KEY (`verified_by`) REFERENCES `users`(`id`),
    INDEX `idx_date_of_death` (`date_of_death`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- Source: database/migrations/015_update_funeral_logistics.sql
-- ==========================================

-- based on migration-plan.md

CREATE TABLE IF NOT EXISTS `funeral_logistics` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `death_notification_id` INT NOT NULL, -- Ensure death_notifications.id is also INT (Signed or Unsigned matching this)
    `burial_date` DATE,
    `burial_location` VARCHAR(255),
    `grave_number` VARCHAR(50),
    `arranged_by` INT UNSIGNED NOT NULL,
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`death_notification_id`) REFERENCES `death_notifications`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`arranged_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
