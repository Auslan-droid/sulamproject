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
