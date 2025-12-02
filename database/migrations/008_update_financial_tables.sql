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
