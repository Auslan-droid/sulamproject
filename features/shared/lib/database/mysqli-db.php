<?php
// Legacy mysqli-based database bootstrap used by feature pages
// Exposes $mysqli (mysqli connection)

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_NAME = getenv('DB_NAME') ?: 'masjidkamek';
$DB_CHARSET = 'utf8mb4';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    // 1. Try connecting directly to the specific database (Production/Hostinger friendly)
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $mysqli->set_charset($DB_CHARSET);
} catch (mysqli_sql_exception $e) {
    // 2. If that fails, assume DB doesn't exist yet (Local Dev friendly)
    // Connect to server only, then create DB
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
    $mysqli->set_charset($DB_CHARSET);

    // Create database if it doesn't exist
    $mysqli->query("CREATE DATABASE IF NOT EXISTS `{$DB_NAME}` CHARACTER SET {$DB_CHARSET} COLLATE {$DB_CHARSET}_unicode_ci");
    $mysqli->select_db($DB_NAME);
}

try {
    // Ensure tables exist (bootstrap)
    $mysqli->query("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(120) NOT NULL,
        `username` VARCHAR(50) NOT NULL,
        `email` VARCHAR(120) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `roles` VARCHAR(50) NOT NULL DEFAULT 'user',
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
    ) ENGINE=InnoDB DEFAULT CHARSET={$DB_CHARSET} COLLATE {$DB_CHARSET}_unicode_ci;");

    $mysqli->query("CREATE TABLE IF NOT EXISTS `next_of_kin` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` INT UNSIGNED NOT NULL,
      `name` VARCHAR(120) NOT NULL,
      `email` VARCHAR(120) NULL,
      `phone_number` VARCHAR(20) NULL,
      `address` TEXT NULL,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_next_of_kin_user_id` (`user_id`),
      CONSTRAINT `fk_next_of_kin_user_boot` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$DB_CHARSET} COLLATE {$DB_CHARSET}_unicode_ci;");

    $mysqli->query("CREATE TABLE IF NOT EXISTS `events` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `description` TEXT NOT NULL,
      `image_path` VARCHAR(255) NULL,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$DB_CHARSET} COLLATE {$DB_CHARSET}_unicode_ci;");

    $mysqli->query("CREATE TABLE IF NOT EXISTS `donations` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `description` TEXT NOT NULL,
      `image_path` VARCHAR(255) NULL,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$DB_CHARSET} COLLATE {$DB_CHARSET}_unicode_ci;");

    $mysqli->query("CREATE TABLE IF NOT EXISTS `deaths` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` INT UNSIGNED NOT NULL,
      `time` TIME NULL,
      `date` DATE NULL,
      `islamic_date` VARCHAR(50) NULL,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_deaths_user_id` (`user_id`),
      CONSTRAINT `fk_deaths_user_boot` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$DB_CHARSET} COLLATE {$DB_CHARSET}_unicode_ci;");

  // Seed a default admin user for local development if none exists
  $defaultAdminUsername = getenv('DEFAULT_ADMIN_USERNAME') ?: 'admin';
  $defaultAdminEmail = getenv('DEFAULT_ADMIN_EMAIL') ?: 'admin@sulamproject.local';
  $defaultAdminPassword = getenv('DEFAULT_ADMIN_PASSWORD') ?: 'admin123';

  // Check for any existing admin
  $hasAdmin = false;
  if ($stmt = $mysqli->prepare('SELECT 1 FROM `users` WHERE `roles` = "admin" LIMIT 1')) {
    $stmt->execute();
    $stmt->store_result();
    $hasAdmin = $stmt->num_rows > 0;
    $stmt->close();
  }

  if (!$hasAdmin) {
    // If specific username already exists but not admin, promote it to admin; otherwise create new admin
    $userId = null;
    if ($stmt = $mysqli->prepare('SELECT `id`, `roles` FROM `users` WHERE `username` = ? LIMIT 1')) {
      $stmt->bind_param('s', $defaultAdminUsername);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result ? $result->fetch_assoc() : null;
      $stmt->close();
      if ($row) {
        $userId = (int)$row['id'];
        if ($row['roles'] !== 'admin') {
          // Promote existing user to admin
          if ($up = $mysqli->prepare('UPDATE `users` SET `roles` = "admin" WHERE `id` = ?')) {
            $up->bind_param('i', $userId);
            $up->execute();
            $up->close();
          }
        }
      }
    }

    if (!$userId) {
      // Create a new admin account
      $passwordHash = password_hash($defaultAdminPassword, PASSWORD_DEFAULT);
      if ($ins = $mysqli->prepare('INSERT INTO `users` (`name`, `username`, `email`, `password`, `roles`) VALUES (?,?,?,?,"admin")')) {
        $name = 'Admin';
        $ins->bind_param('ssss', $name, $defaultAdminUsername, $defaultAdminEmail, $passwordHash);
        try {
          $ins->execute();
        } catch (mysqli_sql_exception $e) {
          // Ignore duplicate errors in case of race condition
        }
        $ins->close();
      }
    }
  }
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo '<h2>Database connection error</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}
