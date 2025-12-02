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
