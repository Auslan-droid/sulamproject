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
