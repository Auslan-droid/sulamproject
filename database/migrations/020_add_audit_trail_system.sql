-- Migration: Add comprehensive audit trail system
-- Date: 2025-12-16
-- Purpose: Track who creates, updates, and deletes financial transactions

-- ============================================================================
-- STEP 1: Add audit columns to financial_payment_accounts
-- ============================================================================
ALTER TABLE `financial_payment_accounts`
    ADD COLUMN `created_by` INT UNSIGNED NULL AFTER `updated_at`,
    ADD COLUMN `updated_by` INT UNSIGNED NULL AFTER `created_by`,
    ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_by`,
    ADD COLUMN `deleted_by` INT UNSIGNED NULL AFTER `deleted_at`,
    ADD CONSTRAINT `fk_payment_created_by` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_payment_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_payment_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD INDEX `idx_payment_deleted_at` (`deleted_at`);

-- ============================================================================
-- STEP 2: Add audit columns to financial_deposit_accounts
-- ============================================================================
ALTER TABLE `financial_deposit_accounts`
    ADD COLUMN `created_by` INT UNSIGNED NULL AFTER `updated_at`,
    ADD COLUMN `updated_by` INT UNSIGNED NULL AFTER `created_by`,
    ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_by`,
    ADD COLUMN `deleted_by` INT UNSIGNED NULL AFTER `deleted_at`,
    ADD CONSTRAINT `fk_deposit_created_by` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_deposit_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_deposit_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    ADD INDEX `idx_deposit_deleted_at` (`deleted_at`);

-- ============================================================================
-- STEP 3: Create audit_logs table for full change history
-- ============================================================================
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `table_name` VARCHAR(100) NOT NULL COMMENT 'Table being audited (e.g., financial_payment_accounts)',
    `record_id` INT UNSIGNED NOT NULL COMMENT 'ID of the record being changed',
    `action` ENUM('create', 'update', 'delete', 'restore') NOT NULL COMMENT 'Type of action performed',
    `user_id` INT UNSIGNED NULL COMMENT 'User who performed the action',
    `username` VARCHAR(50) NULL COMMENT 'Username snapshot (in case user is deleted)',
    `user_fullname` VARCHAR(120) NULL COMMENT 'Full name snapshot (in case user is deleted)',
    `changed_fields` JSON NULL COMMENT 'Fields that were changed (for updates)',
    `old_values` JSON NULL COMMENT 'Previous values before change',
    `new_values` JSON NULL COMMENT 'New values after change',
    `ip_address` VARCHAR(45) NULL COMMENT 'IP address of user',
    `user_agent` VARCHAR(255) NULL COMMENT 'Browser/client info',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_table_record` (`table_name`, `record_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_action` (`action`),
    INDEX `idx_created_at` (`created_at`),
    CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Comprehensive audit trail for all financial transactions';

-- ============================================================================
-- STEP 4: Create view for easy audit trail queries
-- ============================================================================
CREATE OR REPLACE VIEW `v_audit_trail` AS
SELECT 
    al.id,
    al.table_name,
    al.record_id,
    al.action,
    al.user_id,
    COALESCE(u.username, al.username) as username,
    COALESCE(u.name, al.user_fullname) as user_fullname,
    al.changed_fields,
    al.old_values,
    al.new_values,
    al.ip_address,
    al.created_at,
    CASE 
        WHEN al.table_name = 'financial_payment_accounts' THEN 'Payment'
        WHEN al.table_name = 'financial_deposit_accounts' THEN 'Deposit'
        ELSE al.table_name
    END as transaction_type
FROM audit_logs al
LEFT JOIN users u ON al.user_id = u.id
ORDER BY al.created_at DESC;
