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
