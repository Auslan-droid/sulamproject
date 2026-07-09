-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table masjidkamek.audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Table being audited (e.g., financial_payment_accounts)',
  `record_id` int unsigned NOT NULL COMMENT 'ID of the record being changed',
  `action` enum('create','update','delete','restore') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of action performed',
  `user_id` int unsigned DEFAULT NULL COMMENT 'User who performed the action',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Username snapshot (in case user is deleted)',
  `user_fullname` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Full name snapshot (in case user is deleted)',
  `changed_fields` json DEFAULT NULL COMMENT 'Fields that were changed (for updates)',
  `old_values` json DEFAULT NULL COMMENT 'Previous values before change',
  `new_values` json DEFAULT NULL COMMENT 'New values after change',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address of user',
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Browser/client info',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_table_record` (`table_name`,`record_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Comprehensive audit trail for all financial transactions';

-- Dumping data for table masjidkamek.audit_logs: ~17 rows (approximately)
INSERT INTO `audit_logs` (`id`, `table_name`, `record_id`, `action`, `user_id`, `username`, `user_fullname`, `changed_fields`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
	(1, 'financial_deposit_accounts', 54, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 54, "kontra": "0", "tx_date": "2025-12-16", "created_by": 2, "description": "dadadadad", "received_from": "ali", "tabung_masjid": "0", "geran_kerajaan": "1111", "payment_method": "cash", "receipt_number": "RR/2025/0026", "sumbangan_derma": "0", "hibah_faedah_bank": "0", "payment_reference": "", "lain_lain_terimaan": "0", "kutipan_jumaat_sadak": "0", "faedah_simpanan_tetap": "0", "sewa_peralatan_masjid": "0", "kutipan_aidilfitri_aidiladha": "0", "sewa_rumah_kedai_tadika_menara": "0"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:08:12'),
	(2, 'financial_payment_accounts', 39, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 39, "kontra": "0", "paid_to": "adas", "tx_date": "2025-12-16", "utiliti": "0", "caj_bank": "0", "payee_ic": "121212", "created_by": 2, "description": "adasdas", "payment_method": "cash", "perayaan_islam": "0", "voucher_number": "MADU/2025/0036", "payee_bank_name": "", "sumbangan_derma": "0", "mesyuarat_jamuan": "0", "payment_reference": "", "payee_bank_account": "", "alat_tulis_percetakan": "0", "lain_lain_perbelanjaan": "0", "penyelenggaraan_masjid": "0", "pengangkutan_perjalanan": "66", "gaji_upah_saguhati_elaun": "0", "keperluan_kelengkapan_masjid": "0", "pengimarahan_aktiviti_masjid": "0"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:42:06'),
	(3, 'financial_deposit_accounts', 55, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 55, "kontra": "444", "tx_date": "2025-12-16", "created_by": 2, "description": "Transfer from Bank to Cash", "received_from": "Internal Transfer", "tabung_masjid": "0", "geran_kerajaan": "0", "payment_method": "cash", "receipt_number": "RR/2025/0027", "sumbangan_derma": "0", "hibah_faedah_bank": "0", "payment_reference": "", "lain_lain_terimaan": "0", "kutipan_jumaat_sadak": "0", "faedah_simpanan_tetap": "0", "sewa_peralatan_masjid": "0", "kutipan_aidilfitri_aidiladha": "0", "sewa_rumah_kedai_tadika_menara": "0"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:43:16'),
	(4, 'financial_payment_accounts', 40, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 40, "kontra": "444", "paid_to": "Internal Transfer", "tx_date": "2025-12-16", "utiliti": 0, "caj_bank": 0, "created_by": 2, "description": "(Contra) Transfer from Bank to Cash - Ref: Deposit #55", "payment_method": "bank", "perayaan_islam": 0, "voucher_number": "MADU/2025/0037", "sumbangan_derma": 0, "mesyuarat_jamuan": 0, "payment_reference": "CONTRA-20251216034316", "alat_tulis_percetakan": 0, "lain_lain_perbelanjaan": 0, "penyelenggaraan_masjid": 0, "pengangkutan_perjalanan": 0, "gaji_upah_saguhati_elaun": 0, "keperluan_kelengkapan_masjid": 0, "pengimarahan_aktiviti_masjid": 0}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:43:16'),
	(5, 'financial_deposit_accounts', 56, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 56, "kontra": "1212", "tx_date": "2025-12-16", "created_by": 2, "description": "Transfer from Bank to Cash", "received_from": "Internal Transfer", "tabung_masjid": "0", "geran_kerajaan": "0", "payment_method": "cash", "receipt_number": "RR/2025/0028", "sumbangan_derma": "0", "hibah_faedah_bank": "0", "payment_reference": "", "lain_lain_terimaan": "0", "kutipan_jumaat_sadak": "0", "faedah_simpanan_tetap": "0", "sewa_peralatan_masjid": "0", "kutipan_aidilfitri_aidiladha": "0", "sewa_rumah_kedai_tadika_menara": "0"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:46:36'),
	(6, 'financial_payment_accounts', 41, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 41, "kontra": "1212", "paid_to": "Internal Transfer", "tx_date": "2025-12-16", "utiliti": 0, "caj_bank": 0, "created_by": 2, "description": "Kontra: Bank ke Tunai", "payment_method": "bank", "perayaan_islam": 0, "voucher_number": "MADU/2025/0038", "sumbangan_derma": 0, "mesyuarat_jamuan": 0, "payment_reference": "CONTRA-20251216034636", "alat_tulis_percetakan": 0, "lain_lain_perbelanjaan": 0, "penyelenggaraan_masjid": 0, "pengangkutan_perjalanan": 0, "gaji_upah_saguhati_elaun": 0, "keperluan_kelengkapan_masjid": 0, "pengimarahan_aktiviti_masjid": 0}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:46:36'),
	(7, 'financial_deposit_accounts', 57, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 57, "kontra": "3123123", "tx_date": "2025-12-16", "created_by": 2, "description": "Kontra: Bank ke Tunai", "received_from": "Internal Transfer", "tabung_masjid": "0", "geran_kerajaan": "0", "payment_method": "cash", "receipt_number": "RR/2025/0029", "sumbangan_derma": "0", "hibah_faedah_bank": "0", "payment_reference": "", "lain_lain_terimaan": "0", "kutipan_jumaat_sadak": "0", "faedah_simpanan_tetap": "0", "sewa_peralatan_masjid": "0", "kutipan_aidilfitri_aidiladha": "0", "sewa_rumah_kedai_tadika_menara": "0"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:50:18'),
	(8, 'financial_payment_accounts', 42, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 42, "kontra": "3123123", "paid_to": "Internal Transfer", "tx_date": "2025-12-16", "utiliti": 0, "caj_bank": 0, "created_by": 2, "description": "Kontra: Bank ke Tunai", "payment_method": "bank", "perayaan_islam": 0, "voucher_number": "MADU/2025/0039", "sumbangan_derma": 0, "mesyuarat_jamuan": 0, "payment_reference": "CONTRA-20251216035018", "alat_tulis_percetakan": 0, "lain_lain_perbelanjaan": 0, "penyelenggaraan_masjid": 0, "pengangkutan_perjalanan": 0, "gaji_upah_saguhati_elaun": 0, "keperluan_kelengkapan_masjid": 0, "pengimarahan_aktiviti_masjid": 0}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-16 02:50:18'),
	(9, 'financial_deposit_accounts', 58, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 58, "kontra": "0", "tx_date": "2026-01-22", "created_by": 2, "description": "qeqeqeqeq", "received_from": "arara", "tabung_masjid": "0", "geran_kerajaan": "0", "payment_method": "cash", "receipt_number": "RR/2026/0001", "sumbangan_derma": "500", "hibah_faedah_bank": "0", "payment_reference": "", "lain_lain_terimaan": "0", "kutipan_jumaat_sadak": "0", "faedah_simpanan_tetap": "0", "sewa_peralatan_masjid": "0", "kutipan_aidilfitri_aidiladha": "0", "sewa_rumah_kedai_tadika_menara": "0"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:04:05'),
	(10, 'financial_deposit_accounts', 57, 'delete', 2, 'admin', 'Admin User', NULL, '{"id": 57, "kontra": "3123123.00", "tx_date": "2025-12-16", "created_at": "2025-12-16 10:50:18", "created_by": 2, "deleted_at": null, "deleted_by": null, "updated_at": "2025-12-16 10:50:18", "updated_by": null, "description": "Kontra: Bank ke Tunai", "received_from": "Internal Transfer", "tabung_masjid": "0.00", "contra_pair_id": 42, "geran_kerajaan": "0.00", "payment_method": "cash", "receipt_number": "RR/2025/0029", "sumbangan_derma": "0.00", "hibah_faedah_bank": "0.00", "payment_reference": "", "lain_lain_terimaan": "0.00", "kutipan_jumaat_sadak": "0.00", "faedah_simpanan_tetap": "0.00", "is_contra_transaction": 1, "sewa_peralatan_masjid": "0.00", "kutipan_aidilfitri_aidiladha": "0.00", "sewa_rumah_kedai_tadika_menara": "0.00"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:10:06'),
	(11, 'financial_payment_accounts', 42, 'delete', 2, 'admin', 'Admin User', NULL, '{"id": 42, "kontra": "3123123.00", "paid_to": "Internal Transfer", "tx_date": "2025-12-16", "utiliti": "0.00", "caj_bank": "0.00", "payee_ic": null, "created_at": "2025-12-16 10:50:18", "created_by": 2, "deleted_at": null, "deleted_by": null, "updated_at": "2025-12-16 10:50:18", "updated_by": null, "description": "Kontra: Bank ke Tunai", "contra_pair_id": 57, "payment_method": "bank", "perayaan_islam": "0.00", "voucher_number": "MADU/2025/0039", "payee_bank_name": null, "sumbangan_derma": "0.00", "mesyuarat_jamuan": "0.00", "payment_reference": "CONTRA-20251216035018", "payee_bank_account": null, "alat_tulis_percetakan": "0.00", "is_contra_transaction": 1, "lain_lain_perbelanjaan": "0.00", "penyelenggaraan_masjid": "0.00", "pengangkutan_perjalanan": "0.00", "gaji_upah_saguhati_elaun": "0.00", "keperluan_kelengkapan_masjid": "0.00", "pengimarahan_aktiviti_masjid": "0.00"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:10:06'),
	(12, 'financial_deposit_accounts', 59, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 59, "kontra": "1111", "tx_date": "2026-01-22", "created_by": 2, "description": "Kontra: Bank ke Tunai", "received_from": "Internal Transfer", "tabung_masjid": "0", "geran_kerajaan": "0", "payment_method": "cash", "receipt_number": "RR/2026/0002", "sumbangan_derma": "0", "hibah_faedah_bank": "0", "payment_reference": "", "lain_lain_terimaan": "0", "kutipan_jumaat_sadak": "0", "faedah_simpanan_tetap": "0", "sewa_peralatan_masjid": "0", "kutipan_aidilfitri_aidiladha": "0", "sewa_rumah_kedai_tadika_menara": "0"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:10:51'),
	(13, 'financial_payment_accounts', 45, 'create', 2, 'admin', 'Admin User', NULL, NULL, '{"id": 45, "kontra": "1111", "paid_to": "Internal Transfer", "tx_date": "2026-01-22", "utiliti": 0, "caj_bank": 0, "created_by": 2, "description": "Kontra: Bank ke Tunai", "payment_method": "bank", "perayaan_islam": 0, "voucher_number": "MADU/2026/0001", "sumbangan_derma": 0, "mesyuarat_jamuan": 0, "payment_reference": "CONTRA-20260122071051", "alat_tulis_percetakan": 0, "lain_lain_perbelanjaan": 0, "penyelenggaraan_masjid": 0, "pengangkutan_perjalanan": 0, "gaji_upah_saguhati_elaun": 0, "keperluan_kelengkapan_masjid": 0, "pengimarahan_aktiviti_masjid": 0}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:10:51'),
	(14, 'financial_deposit_accounts', 59, 'delete', 2, 'admin', 'Admin User', NULL, '{"id": 59, "kontra": "1111.00", "tx_date": "2026-01-22", "created_at": "2026-01-22 14:10:51", "created_by": 2, "deleted_at": null, "deleted_by": null, "updated_at": "2026-01-22 14:10:51", "updated_by": null, "description": "Kontra: Bank ke Tunai", "received_from": "Internal Transfer", "tabung_masjid": "0.00", "contra_pair_id": 45, "geran_kerajaan": "0.00", "payment_method": "cash", "receipt_number": "RR/2026/0002", "sumbangan_derma": "0.00", "hibah_faedah_bank": "0.00", "payment_reference": "", "lain_lain_terimaan": "0.00", "kutipan_jumaat_sadak": "0.00", "faedah_simpanan_tetap": "0.00", "is_contra_transaction": 1, "sewa_peralatan_masjid": "0.00", "kutipan_aidilfitri_aidiladha": "0.00", "sewa_rumah_kedai_tadika_menara": "0.00"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:11:08'),
	(15, 'financial_payment_accounts', 45, 'delete', 2, 'admin', 'Admin User', NULL, '{"id": 45, "kontra": "1111.00", "paid_to": "Internal Transfer", "tx_date": "2026-01-22", "utiliti": "0.00", "caj_bank": "0.00", "payee_ic": null, "created_at": "2026-01-22 14:10:51", "created_by": 2, "deleted_at": null, "deleted_by": null, "updated_at": "2026-01-22 14:10:51", "updated_by": null, "description": "Kontra: Bank ke Tunai", "contra_pair_id": 59, "payment_method": "bank", "perayaan_islam": "0.00", "voucher_number": "MADU/2026/0001", "payee_bank_name": null, "sumbangan_derma": "0.00", "mesyuarat_jamuan": "0.00", "payment_reference": "CONTRA-20260122071051", "payee_bank_account": null, "alat_tulis_percetakan": "0.00", "is_contra_transaction": 1, "lain_lain_perbelanjaan": "0.00", "penyelenggaraan_masjid": "0.00", "pengangkutan_perjalanan": "0.00", "gaji_upah_saguhati_elaun": "0.00", "keperluan_kelengkapan_masjid": "0.00", "pengimarahan_aktiviti_masjid": "0.00"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:11:08'),
	(16, 'financial_payment_accounts', 41, 'delete', 2, 'admin', 'Admin User', NULL, '{"id": 41, "kontra": "1212.00", "paid_to": "Internal Transfer", "tx_date": "2025-12-16", "utiliti": "0.00", "caj_bank": "0.00", "payee_ic": null, "created_at": "2025-12-16 10:46:36", "created_by": 2, "deleted_at": null, "deleted_by": null, "updated_at": "2025-12-16 10:46:36", "updated_by": null, "description": "Kontra: Bank ke Tunai", "contra_pair_id": 56, "payment_method": "bank", "perayaan_islam": "0.00", "voucher_number": "MADU/2025/0038", "payee_bank_name": null, "sumbangan_derma": "0.00", "mesyuarat_jamuan": "0.00", "payment_reference": "CONTRA-20251216034636", "payee_bank_account": null, "alat_tulis_percetakan": "0.00", "is_contra_transaction": 1, "lain_lain_perbelanjaan": "0.00", "penyelenggaraan_masjid": "0.00", "pengangkutan_perjalanan": "0.00", "gaji_upah_saguhati_elaun": "0.00", "keperluan_kelengkapan_masjid": "0.00", "pengimarahan_aktiviti_masjid": "0.00"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:11:30'),
	(17, 'financial_deposit_accounts', 56, 'delete', 2, 'admin', 'Admin User', NULL, '{"id": 56, "kontra": "1212.00", "tx_date": "2025-12-16", "created_at": "2025-12-16 10:46:36", "created_by": 2, "deleted_at": null, "deleted_by": null, "updated_at": "2025-12-16 10:46:36", "updated_by": null, "description": "Transfer from Bank to Cash", "received_from": "Internal Transfer", "tabung_masjid": "0.00", "contra_pair_id": 41, "geran_kerajaan": "0.00", "payment_method": "cash", "receipt_number": "RR/2025/0028", "sumbangan_derma": "0.00", "hibah_faedah_bank": "0.00", "payment_reference": "", "lain_lain_terimaan": "0.00", "kutipan_jumaat_sadak": "0.00", "faedah_simpanan_tetap": "0.00", "is_contra_transaction": 1, "sewa_peralatan_masjid": "0.00", "kutipan_aidilfitri_aidiladha": "0.00", "sewa_rumah_kedai_tadika_menara": "0.00"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-22 06:11:30');

-- Dumping structure for table masjidkamek.deaths
CREATE TABLE IF NOT EXISTS `deaths` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `time` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `islamic_date` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_deaths_user_id` (`user_id`),
  CONSTRAINT `fk_deaths_user_boot` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.deaths: ~0 rows (approximately)

-- Dumping structure for table masjidkamek.death_notifications
CREATE TABLE IF NOT EXISTS `death_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `deceased_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ic_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_death` date NOT NULL,
  `place_of_death` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cause_of_death` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_of_kin_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_of_kin_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reported_by` int unsigned DEFAULT NULL,
  `verified` tinyint(1) DEFAULT '0',
  `verified_by` int unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reported_by` (`reported_by`),
  KEY `verified_by` (`verified_by`),
  KEY `idx_date_of_death` (`date_of_death`),
  CONSTRAINT `death_notifications_ibfk_1` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`),
  CONSTRAINT `death_notifications_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.death_notifications: ~0 rows (approximately)

-- Dumping structure for table masjidkamek.dependent
CREATE TABLE IF NOT EXISTS `dependent` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tanggungan_user_id` (`user_id`),
  CONSTRAINT `fk_tanggungan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.dependent: ~0 rows (approximately)

-- Dumping structure for table masjidkamek.donations
CREATE TABLE IF NOT EXISTS `donations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General Donation',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.donations: ~0 rows (approximately)
INSERT INTO `donations` (`id`, `title`, `description`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'asasasasa', 'dasdfasdasdas', 'assets/uploads/donation_1764217629_f1fa2088.png', 1, '2025-11-27 04:27:09', '2025-11-27 04:27:09');

-- Dumping structure for table masjidkamek.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New Event',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.events: ~0 rows (approximately)
INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `event_time`, `location`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'asdasdas', 'asdasdas', '2025-11-11', '14:29:00', 'asdsa', 'assets/uploads/event_1764217646_d31a808e.png', 1, '2025-11-27 04:27:26', '2025-11-27 04:27:26'),
	(2, 'abc', 'hanya lah event', '2025-12-23', '14:00:00', 'di sana sini', 'assets/uploads/event_1765682280_5aded46d.gif', 1, '2025-12-14 03:18:00', '2025-12-14 03:18:00');

-- Dumping structure for table masjidkamek.financial_deposit_accounts
CREATE TABLE IF NOT EXISTS `financial_deposit_accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `receipt_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tx_date` date NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `received_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` enum('cash','bank','cheque') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contra_pair_id` int unsigned DEFAULT NULL,
  `is_contra_transaction` tinyint(1) NOT NULL DEFAULT '0',
  `geran_kerajaan` decimal(12,2) unsigned DEFAULT '0.00',
  `sumbangan_derma` decimal(12,2) unsigned DEFAULT '0.00',
  `tabung_masjid` decimal(12,2) unsigned DEFAULT '0.00',
  `kutipan_jumaat_sadak` decimal(12,2) unsigned DEFAULT '0.00',
  `kutipan_aidilfitri_aidiladha` decimal(12,2) unsigned DEFAULT '0.00',
  `sewa_peralatan_masjid` decimal(12,2) unsigned DEFAULT '0.00',
  `hibah_faedah_bank` decimal(12,2) unsigned DEFAULT '0.00',
  `faedah_simpanan_tetap` decimal(12,2) unsigned DEFAULT '0.00',
  `sewa_rumah_kedai_tadika_menara` decimal(12,2) unsigned DEFAULT '0.00',
  `lain_lain_terimaan` decimal(12,2) unsigned DEFAULT '0.00',
  `kontra` decimal(12,2) unsigned DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int unsigned DEFAULT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tx_date` (`tx_date`),
  KEY `idx_receipt_number` (`receipt_number`),
  KEY `idx_contra_pair` (`contra_pair_id`,`is_contra_transaction`),
  KEY `fk_deposit_created_by` (`created_by`),
  KEY `fk_deposit_updated_by` (`updated_by`),
  KEY `fk_deposit_deleted_by` (`deleted_by`),
  KEY `idx_deposit_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_deposit_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_deposit_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_deposit_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.financial_deposit_accounts: ~34 rows (approximately)
INSERT INTO `financial_deposit_accounts` (`id`, `receipt_number`, `tx_date`, `description`, `received_from`, `payment_method`, `payment_reference`, `contra_pair_id`, `is_contra_transaction`, `geran_kerajaan`, `sumbangan_derma`, `tabung_masjid`, `kutipan_jumaat_sadak`, `kutipan_aidilfitri_aidiladha`, `sewa_peralatan_masjid`, `hibah_faedah_bank`, `faedah_simpanan_tetap`, `sewa_rumah_kedai_tadika_menara`, `lain_lain_terimaan`, `kontra`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
	(1, 'RR/2025/0001', '2025-01-03', 'Kutipan Jumaat Minggu Pertama', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1250.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(2, 'RR/2025/0002', '2025-01-05', 'Sumbangan Ikhlas Dermawan', 'Haji Ahmad bin Abdullah', 'bank', 'TRF20250105001', NULL, 0, 0.00, 5000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(3, 'RR/2025/0003', '2025-01-10', 'Kutipan Jumaat Minggu Kedua', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1420.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(4, 'RR/2025/0004', '2025-01-12', 'Sewa Dewan Serbaguna - Majlis Kesyukuran', 'Encik Kamal bin Hassan', 'bank', 'CHQ8023456', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 350.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-08 12:16:23', NULL, NULL, NULL, NULL),
	(5, 'RR/2025/0005', '2025-01-15', 'Geran JAIM Tahun 2025', 'Jabatan Agama Islam Melaka', 'bank', 'TRF20250115890', NULL, 0, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(6, 'RR/2025/0006', '2025-01-17', 'Kutipan Jumaat Minggu Ketiga', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1380.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(7, 'RR/2025/0007', '2025-01-20', 'Derma Peralatan Masjid', 'Puan Siti Nurhaliza', 'bank', 'TRF20250120234', NULL, 0, 0.00, 2500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(8, 'RR/2025/0008', '2025-01-22', 'Tabung Pembinaan Masjid', 'Dermawan Anonymous', 'cash', NULL, NULL, 0, 0.00, 0.00, 3000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(9, 'RR/2025/0009', '2025-01-24', 'Kutipan Jumaat Minggu Keempat', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1510.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(10, 'RR/2025/0010', '2025-01-27', 'Faedah Simpanan Tetap - Bank Islam', 'Bank Islam Malaysia Berhad', 'bank', 'INT20250127', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 458.50, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(11, 'RR/2025/0011', '2025-01-31', 'Kutipan Jumaat Minggu Kelima', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1290.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(12, 'RR/2025/0012', '2025-02-01', 'Sewa Kedai Tingkat Bawah - Bulan Feb', 'Kedai Runcit Pak Ali', 'bank', 'TRF20250201567', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1200.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(13, 'RR/2025/0013', '2025-02-03', 'Hibah Dari Bank Muamalat', 'Bank Muamalat Malaysia Berhad', 'bank', 'HIB20250203', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 125.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(14, 'RR/2025/0014', '2025-02-07', 'Kutipan Jumaat Minggu Pertama Feb', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1445.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(15, 'RR/2025/0015', '2025-02-10', 'Sewa Peralatan PA System - Majlis Perkahwinan', 'Encik Razak bin Osman', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 150.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(16, 'RR/2025/0016', '2025-02-14', 'Kutipan Jumaat Minggu Kedua Feb', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1520.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(17, 'RR/2025/0017', '2025-02-15', 'Derma Dari Syarikat Perniagaan', 'Syarikat XYZ Sdn Bhd', 'bank', 'CHQ9087654', NULL, 0, 0.00, 8000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-08 12:16:23', NULL, NULL, NULL, NULL),
	(18, 'RR/2025/0018', '2025-02-18', 'Sewa Dewan - Kelas Pendidikan Islam', 'Pusat Tahfiz An-Nur', 'bank', 'TRF20250218890', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 500.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(19, 'RR/2025/0019', '2025-02-21', 'Kutipan Jumaat Minggu Ketiga Feb', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1365.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(20, 'RR/2025/0020', '2025-02-25', 'Jualan Hasil Program Masjid', 'Program Majlis Tahunan', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 680.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(21, 'RR/2025/0021', '2025-02-28', 'Kutipan Jumaat Minggu Keempat Feb', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1480.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(22, 'RR/2025/0022', '2025-03-01', 'Sewa Rumah Imam - Bulan Mac', 'Imam Masjid', 'bank', 'TRF20250301123', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 800.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(23, 'RR/2025/0023', '2025-03-05', 'Geran Khas Pembinaan Surau', 'Kerajaan Negeri Melaka', 'bank', 'TRF20250305999', NULL, 0, 15000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(24, 'RR/2025/0024', '2025-03-07', 'Kutipan Jumaat Minggu Pertama Mac', 'Jemaah Masjid', 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1555.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', NULL, NULL, NULL, NULL, NULL),
	(25, 'RR/2025/0025', '2025-03-10', 'Derma Pembinaan Tadika', 'Datuk Seri Mahmud', 'bank', 'CHQ1234567', NULL, 0, 0.00, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-08 12:16:23', NULL, NULL, NULL, NULL),
	(26, NULL, '2023-11-03', 'Kutipan Jumaat Minggu 1', NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 1200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-11 03:42:31', NULL, NULL, NULL, NULL, NULL),
	(27, NULL, '2023-11-05', 'Sumbangan Ikhlas', NULL, 'cash', NULL, NULL, 0, 0.00, 500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-11 03:42:31', NULL, NULL, NULL, NULL, NULL),
	(28, NULL, '2023-11-10', 'Sewa Dewan Serbaguna', NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 300.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-11 03:42:31', NULL, NULL, NULL, NULL, NULL),
	(54, 'RR/2025/0026', '2025-12-16', 'dadadadad', 'ali', 'cash', '', NULL, 0, 1111.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-16 02:08:12', NULL, 2, NULL, NULL, NULL),
	(55, 'RR/2025/0027', '2025-12-16', 'Transfer from Bank to Cash', 'Internal Transfer', 'cash', '', 40, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 444.00, '2025-12-16 02:43:16', '2025-12-16 02:43:16', 2, NULL, NULL, NULL),
	(56, 'RR/2025/0028', '2025-12-16', 'Transfer from Bank to Cash', 'Internal Transfer', 'cash', '', 41, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1212.00, '2025-12-16 02:46:36', '2026-01-22 06:11:30', 2, NULL, '2026-01-22 06:11:30', 2),
	(57, 'RR/2025/0029', '2025-12-16', 'Kontra: Bank ke Tunai', 'Internal Transfer', 'cash', '', 42, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3123123.00, '2025-12-16 02:50:18', '2026-01-22 06:10:06', 2, NULL, '2026-01-22 06:10:06', 2),
	(58, 'RR/2026/0001', '2026-01-22', 'qeqeqeqeq', 'arara', 'cash', '', NULL, 0, 0.00, 500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-01-22 06:04:05', NULL, 2, NULL, NULL, NULL),
	(59, 'RR/2026/0002', '2026-01-22', 'Kontra: Bank ke Tunai', 'Internal Transfer', 'cash', '', 45, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1111.00, '2026-01-22 06:10:51', '2026-01-22 06:11:08', 2, NULL, '2026-01-22 06:11:08', 2);

-- Dumping structure for table masjidkamek.financial_payment_accounts
CREATE TABLE IF NOT EXISTS `financial_payment_accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `voucher_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tx_date` date NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payee_ic` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payee_bank_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payee_bank_account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` enum('cash','bank','cheque') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contra_pair_id` int unsigned DEFAULT NULL,
  `is_contra_transaction` tinyint(1) NOT NULL DEFAULT '0',
  `perayaan_islam` decimal(12,2) unsigned DEFAULT '0.00',
  `pengimarahan_aktiviti_masjid` decimal(12,2) unsigned DEFAULT '0.00',
  `penyelenggaraan_masjid` decimal(12,2) unsigned DEFAULT '0.00',
  `keperluan_kelengkapan_masjid` decimal(12,2) unsigned DEFAULT '0.00',
  `gaji_upah_saguhati_elaun` decimal(12,2) unsigned DEFAULT '0.00',
  `sumbangan_derma` decimal(12,2) unsigned DEFAULT '0.00',
  `mesyuarat_jamuan` decimal(12,2) unsigned DEFAULT '0.00',
  `utiliti` decimal(12,2) unsigned DEFAULT '0.00',
  `alat_tulis_percetakan` decimal(12,2) unsigned DEFAULT '0.00',
  `pengangkutan_perjalanan` decimal(12,2) unsigned DEFAULT '0.00',
  `caj_bank` decimal(12,2) unsigned DEFAULT '0.00',
  `lain_lain_perbelanjaan` decimal(12,2) unsigned DEFAULT '0.00',
  `kontra` decimal(12,2) unsigned DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int unsigned DEFAULT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tx_date` (`tx_date`),
  KEY `idx_voucher_number` (`voucher_number`),
  KEY `idx_contra_pair` (`contra_pair_id`,`is_contra_transaction`),
  KEY `fk_payment_created_by` (`created_by`),
  KEY `fk_payment_updated_by` (`updated_by`),
  KEY `fk_payment_deleted_by` (`deleted_by`),
  KEY `idx_payment_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_payment_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_payment_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_payment_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.financial_payment_accounts: ~43 rows (approximately)
INSERT INTO `financial_payment_accounts` (`id`, `voucher_number`, `tx_date`, `description`, `paid_to`, `payee_ic`, `payee_bank_name`, `payee_bank_account`, `payment_method`, `payment_reference`, `contra_pair_id`, `is_contra_transaction`, `perayaan_islam`, `pengimarahan_aktiviti_masjid`, `penyelenggaraan_masjid`, `keperluan_kelengkapan_masjid`, `gaji_upah_saguhati_elaun`, `sumbangan_derma`, `mesyuarat_jamuan`, `utiliti`, `alat_tulis_percetakan`, `pengangkutan_perjalanan`, `caj_bank`, `lain_lain_perbelanjaan`, `kontra`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
	(1, 'MADU/2025/0001', '2025-01-04', 'Bayaran Bil Elektrik - Bulan Disember 2024', 'TNB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250104001', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 385.50, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(2, 'MADU/2025/0002', '2025-01-05', 'Bayaran Bil Air - Bulan Disember 2024', 'SAMB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250105002', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 125.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(3, 'MADU/2025/0003', '2025-01-07', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Mohamad bin Ahmad', '750812-10-5432', 'Bank Islam', '1234567890123', 'bank', 'TRF20250107003', NULL, 0, 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(4, 'MADU/2025/0004', '2025-01-08', 'Pembelian Alat Tulis Pejabat', 'Kedai Alat Tulis Mesra', NULL, NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 125.80, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(5, 'MADU/2025/0005', '2025-01-10', 'Penyelenggaraan Kipas Siling Dewan', 'Syarikat Elektrik Jaya', NULL, NULL, NULL, 'bank', 'CHQ7890123', NULL, 0, 0.00, 0.00, 450.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(6, 'MADU/2025/0006', '2025-01-12', 'Gaji Kakitangan Masjid - Bulan Januari', 'Encik Roslan bin Hassan (Imam)', '680523-10-1234', 'Maybank', '5678901234567', 'bank', 'TRF20250112006', NULL, 0, 0.00, 0.00, 0.00, 0.00, 1500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(7, 'MADU/2025/0007', '2025-01-12', 'Gaji Kakitangan Masjid - Bulan Januari', 'Encik Ibrahim bin Yusof (Bilal)', '720815-10-5678', 'CIMB Bank', '8901234567890', 'bank', 'TRF20250112007', NULL, 0, 0.00, 0.00, 0.00, 0.00, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(8, 'MADU/2025/0008', '2025-01-12', 'Gaji Kakitangan Masjid - Bulan Januari', 'Puan Fatimah binti Abdullah (Pembersih)', '850920-10-9012', 'RHB Bank', '2345678901234', 'bank', 'TRF20250112008', NULL, 0, 0.00, 0.00, 0.00, 0.00, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(9, 'MADU/2025/0009', '2025-01-15', 'Pembelian Karpet Masjid Baru', 'Kedai Karpet Al-Hijrah', NULL, NULL, NULL, 'bank', 'CHQ8901234', NULL, 0, 0.00, 0.00, 0.00, 2800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(10, 'MADU/2025/0010', '2025-01-18', 'Bayaran Percetakan Banner Program Tahunan', 'Percetakan Mutiara', NULL, NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 350.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(11, 'MADU/2025/0011', '2025-01-20', 'Saguhati Penceramah Kuliah Jumaat', 'Ustazah Aisyah binti Zainal', '821205-10-3456', 'Bank Muamalat', '4567890123456', 'bank', 'TRF20250120011', NULL, 0, 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(12, 'MADU/2025/0012', '2025-01-22', 'Derma Bantuan Keluarga Asnaf', 'Keluarga Encik Ahmad bin Salleh', '650710-10-7890', NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 300.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(13, 'MADU/2025/0013', '2025-01-25', 'Penyelenggaraan Aircond Dewan', 'Syarikat Penghawa Dingin Sejuk', NULL, NULL, NULL, 'bank', 'CHQ9012345', NULL, 0, 0.00, 0.00, 850.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(14, 'MADU/2025/0014', '2025-01-27', 'Belanja Jamuan Mesyuarat Jawatankuasa', 'Restoran Nasi Kandar Pelita', NULL, NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 280.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(15, 'MADU/2025/0015', '2025-01-29', 'Caj Pengurusan Akaun Bank - Bulan Januari', 'Bank Islam Malaysia Berhad', NULL, NULL, NULL, 'bank', 'AUTO-DEBIT', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(16, 'MADU/2025/0016', '2025-02-02', 'Bayaran Bil Elektrik - Bulan Januari 2025', 'TNB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250202016', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 412.30, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(17, 'MADU/2025/0017', '2025-02-03', 'Bayaran Bil Air - Bulan Januari 2025', 'SAMB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250203017', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 138.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(18, 'MADU/2025/0018', '2025-02-05', 'Pembelian Al-Quran dan Buku Terjemahan', 'Kedai Buku Pustaka Islamiah', NULL, NULL, NULL, 'bank', 'CHQ0123456', NULL, 0, 0.00, 0.00, 0.00, 1200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(19, 'MADU/2025/0019', '2025-02-08', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Abdullah bin Omar', '770315-10-2345', 'Bank Rakyat', '6789012345678', 'bank', 'TRF20250208019', NULL, 0, 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(20, 'MADU/2025/0020', '2025-02-10', 'Belanja Pengangkutan Program Lawatan', 'Syarikat Bas Sinar Jaya', NULL, NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 550.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(21, 'MADU/2025/0021', '2025-02-12', 'Gaji Kakitangan Masjid - Bulan Februari', 'Encik Roslan bin Hassan (Imam)', '680523-10-1234', 'Maybank', '5678901234567', 'bank', 'TRF20250212021', NULL, 0, 0.00, 0.00, 0.00, 0.00, 1500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(22, 'MADU/2025/0022', '2025-02-12', 'Gaji Kakitangan Masjid - Bulan Februari', 'Encik Ibrahim bin Yusof (Bilal)', '720815-10-5678', 'CIMB Bank', '8901234567890', 'bank', 'TRF20250212022', NULL, 0, 0.00, 0.00, 0.00, 0.00, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(23, 'MADU/2025/0023', '2025-02-12', 'Gaji Kakitangan Masjid - Bulan Februari', 'Puan Fatimah binti Abdullah (Pembersih)', '850920-10-9012', 'RHB Bank', '2345678901234', 'bank', 'TRF20250212023', NULL, 0, 0.00, 0.00, 0.00, 0.00, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(24, 'MADU/2025/0024', '2025-02-14', 'Perbelanjaan Program Maulidur Rasul', 'Pelbagai Vendor', NULL, NULL, NULL, 'cash', NULL, NULL, 0, 1500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(25, 'MADU/2025/0025', '2025-02-16', 'Bayaran Perkhidmatan Internet - Bulan Februari', 'TM Unifi', NULL, NULL, NULL, 'bank', 'TRF20250216025', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 159.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(26, 'MADU/2025/0026', '2025-02-20', 'Penyelenggaraan Cat Dinding Luar Masjid', 'Syarikat Cat & Dekorasi', NULL, NULL, NULL, 'bank', 'CHQ1234567', NULL, 0, 0.00, 0.00, 2500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(27, 'MADU/2025/0027', '2025-02-22', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Zainuddin bin Ali', '791018-10-4567', 'Bank Islam', '7890123456789', 'bank', 'TRF20250222027', NULL, 0, 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(28, 'MADU/2025/0028', '2025-02-25', 'Derma Bantuan Keluarga Asnaf', 'Keluarga Puan Maimunah binti Hassan', '721125-10-8901', NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(29, 'MADU/2025/0029', '2025-02-27', 'Caj Pengurusan Akaun Bank - Bulan Februari', 'Bank Islam Malaysia Berhad', NULL, NULL, NULL, 'bank', 'AUTO-DEBIT', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(30, 'MADU/2025/0030', '2025-03-01', 'Pembelian Peralatan Sound System Baru', 'Kedai Elektronik Harmoni', NULL, NULL, NULL, 'bank', 'CHQ2345678', NULL, 0, 0.00, 0.00, 0.00, 3500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(31, 'MADU/2025/0031', '2025-03-04', 'Bayaran Bil Elektrik - Bulan Februari 2025', 'TNB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250304031', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 398.75, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(32, 'MADU/2025/0032', '2025-03-05', 'Bayaran Bil Air - Bulan Februari 2025', 'SAMB Melaka', NULL, NULL, NULL, 'bank', 'TRF20250305032', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 142.50, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(33, 'MADU/2025/0033', '2025-03-08', 'Saguhati Penceramah Kuliah Jumaat', 'Ustaz Hafiz bin Mahmud', '830722-10-5678', 'Bank Muamalat', '8901234567890', 'bank', 'TRF20250308033', NULL, 0, 0.00, 0.00, 0.00, 0.00, 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(34, 'MADU/2025/0034', '2025-03-10', 'Belanja Perjalanan Mesyuarat Luar Negeri', 'Pengerusi Masjid - Encik Azman', '650210-10-1234', NULL, NULL, 'bank', 'TRF20250310034', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 850.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(35, 'MADU/2025/0035', '2025-03-12', 'Pembelian Penyaman Udara (Aircond) Bilik Imam', 'Syarikat Elektrik Sejuk Beku', NULL, NULL, NULL, 'bank', 'CHQ3456789', NULL, 0, 0.00, 0.00, 0.00, 2200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-02 13:50:27', '2025-12-12 08:52:02', NULL, NULL, NULL, NULL),
	(36, NULL, '2023-10-25', 'Pembelian Al-Quran Baru', NULL, NULL, NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-11 03:42:31', NULL, NULL, NULL, NULL, NULL),
	(37, NULL, '2023-10-26', 'Bayaran Bil Elektrik', NULL, NULL, NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-11 03:42:31', NULL, NULL, NULL, NULL, NULL),
	(38, NULL, '2023-10-27', 'Saguhati Penceramah Jemputan', NULL, NULL, NULL, NULL, 'cash', NULL, NULL, 0, 0.00, 0.00, 0.00, 0.00, 150.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-12-11 03:42:31', NULL, NULL, NULL, NULL, NULL),
	(39, 'MADU/2025/0036', '2025-12-16', 'adasdas', 'adas', '121212', '', '', 'cash', '', NULL, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 66.00, 0.00, 0.00, 0.00, '2025-12-16 02:42:06', NULL, 2, NULL, NULL, NULL),
	(40, 'MADU/2025/0037', '2025-12-16', '(Contra) Transfer from Bank to Cash - Ref: Deposit #55', 'Internal Transfer', NULL, NULL, NULL, 'bank', 'CONTRA-20251216034316', 55, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 444.00, '2025-12-16 02:43:16', '2025-12-16 02:43:16', 2, NULL, NULL, NULL),
	(41, 'MADU/2025/0038', '2025-12-16', 'Kontra: Bank ke Tunai', 'Internal Transfer', NULL, NULL, NULL, 'bank', 'CONTRA-20251216034636', 56, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1212.00, '2025-12-16 02:46:36', '2026-01-22 06:11:30', 2, NULL, '2026-01-22 06:11:30', 2),
	(42, 'MADU/2025/0039', '2025-12-16', 'Kontra: Bank ke Tunai', 'Internal Transfer', NULL, NULL, NULL, 'bank', 'CONTRA-20251216035018', 57, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3123123.00, '2025-12-16 02:50:18', '2026-01-22 06:10:06', 2, NULL, '2026-01-22 06:10:06', 2),
	(45, 'MADU/2026/0001', '2026-01-22', 'Kontra: Bank ke Tunai', 'Internal Transfer', NULL, NULL, NULL, 'bank', 'CONTRA-20260122071051', 59, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1111.00, '2026-01-22 06:10:51', '2026-01-22 06:11:08', 2, NULL, '2026-01-22 06:11:08', 2);

-- Dumping structure for table masjidkamek.financial_settings
CREATE TABLE IF NOT EXISTS `financial_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fiscal_year` year NOT NULL,
  `opening_cash_balance` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Baki Awal di Tangan',
  `opening_bank_balance` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Baki Awal di Bank',
  `effective_date` date NOT NULL COMMENT 'Tarikh berkuatkuasa baki awal',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_fiscal_year` (`fiscal_year`),
  KEY `idx_effective_date` (`effective_date`),
  KEY `fk_financial_settings_user` (`created_by`),
  CONSTRAINT `fk_financial_settings_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.financial_settings: ~1 rows (approximately)
INSERT INTO `financial_settings` (`id`, `fiscal_year`, `opening_cash_balance`, `opening_bank_balance`, `effective_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
	(1, '2025', 1000.00, 2000.00, '2025-01-01', 'Baki awal permulaan sistem', NULL, '2025-12-02 11:16:54', '2025-12-11 03:42:31'),
	(6, '2026', 1000.00, 1000.00, '2026-01-01', '', 2, '2026-01-22 06:02:41', '2026-01-22 06:02:57');

-- Dumping structure for table masjidkamek.frontend_logs
CREATE TABLE IF NOT EXISTS `frontend_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('error','warning','info','debug') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'error',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'error, ajax_error, resource_error, performance, user_action',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stack_trace` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Page URL where error occurred',
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `screen_resolution` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_data` json DEFAULT NULL COMMENT 'Additional context data',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_level` (`level`),
  KEY `idx_type` (`type`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `frontend_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.frontend_logs: ~15 rows (approximately)
INSERT INTO `frontend_logs` (`id`, `user_id`, `session_id`, `level`, `type`, `message`, `stack_trace`, `url`, `user_agent`, `browser`, `os`, `screen_resolution`, `request_data`, `ip_address`, `created_at`) VALUES
	(1, NULL, '9anh4p2dtgl92qlqspsec4nbbl', 'info', 'test', 'Test log from PowerShell', NULL, NULL, 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-MY) WindowsPowerShell/5.1.26100.7019', NULL, NULL, NULL, '{"line": null, "extra": null, "column": null, "source": null}', '::1', '2025-12-06 12:52:49'),
	(2, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'javascript_error', 'Uncaught ReferenceError: nonExistentVariable is not defined', 'ReferenceError: nonExistentVariable is not defined\n    at triggerUndefinedError (http://localhost/sulamprojectex/test-frontend-logger.html:232:13)\n    at HTMLButtonElement.onclick (http://localhost/sulamprojectex/test-frontend-logger.html:133:55)', 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": 232, "extra": null, "column": 13, "source": "http://localhost/sulamprojectex/test-frontend-logger.html"}', '::1', '2025-12-06 12:57:12'),
	(3, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'javascript_error', 'Uncaught ReferenceError: nonExistentVariable is not defined', 'ReferenceError: nonExistentVariable is not defined\n    at triggerUndefinedError (http://localhost/sulamprojectex/test-frontend-logger.html:232:13)\n    at HTMLButtonElement.onclick (http://localhost/sulamprojectex/test-frontend-logger.html:133:55)', 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": 232, "extra": null, "column": 13, "source": "http://localhost/sulamprojectex/test-frontend-logger.html"}', '::1', '2025-12-06 12:59:51'),
	(4, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'javascript_error', 'Uncaught TypeError: Cannot read properties of null (reading \'toString\')', 'TypeError: Cannot read properties of null (reading \'toString\')\n    at triggerTypeError (http://localhost/sulamprojectex/test-frontend-logger.html:237:18)\n    at HTMLButtonElement.onclick (http://localhost/sulamprojectex/test-frontend-logger.html:134:50)', 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": 237, "extra": null, "column": 18, "source": "http://localhost/sulamprojectex/test-frontend-logger.html"}', '::1', '2025-12-06 12:59:58'),
	(5, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'javascript_error', 'Uncaught ReferenceError: nonExistentFunction is not defined', 'ReferenceError: nonExistentFunction is not defined\n    at eval (eval at triggerReferenceError (http://localhost/sulamprojectex/test-frontend-logger.html:30:13), <anonymous>:1:1)\n    at triggerReferenceError (http://localhost/sulamprojectex/test-frontend-logger.html:242:13)\n    at HTMLButtonElement.onclick (http://localhost/sulamprojectex/test-frontend-logger.html:135:55)', 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": 1, "extra": null, "column": 1, "source": ""}', '::1', '2025-12-06 12:59:59'),
	(6, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'promise_rejection', 'Unhandled Promise Rejection: Error: Test promise rejection', 'Error: Test promise rejection\n    at triggerPromiseRejection (http://localhost/sulamprojectex/test-frontend-logger.html:248:28)\n    at HTMLButtonElement.onclick (http://localhost/sulamprojectex/test-frontend-logger.html:142:57)', 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": null, "column": null, "source": null}', '::1', '2025-12-06 13:00:00'),
	(7, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'resource_error', 'Failed to load resource: http://localhost/nonexistent-image-1765026001524.jpg', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"type": "unknown", "tagName": "IMG"}, "column": null, "source": "http://localhost/nonexistent-image-1765026001524.jpg"}', '::1', '2025-12-06 13:00:01'),
	(8, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'resource_error', 'Failed to load resource: http://localhost/nonexistent-script-1765026001863.js', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"type": "unknown", "tagName": "SCRIPT"}, "column": null, "source": "http://localhost/nonexistent-script-1765026001863.js"}', '::1', '2025-12-06 13:00:01'),
	(9, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'error', 'custom', 'Manual error test', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"source": "test-page"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:02'),
	(10, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'warning', 'custom', 'Manual warning test', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"source": "test-page"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:03'),
	(11, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'info', 'custom', 'Manual info test', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"source": "test-page"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:03'),
	(12, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'debug', 'custom', 'Manual debug test', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"source": "test-page"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:03'),
	(13, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'info', 'custom', 'Button clicked: export', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"page": "/sulamprojectex/test-frontend-logger.html", "action": "export", "timestamp": "2025-12-06T13:00:08.010Z"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:08'),
	(14, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'info', 'custom', 'Button clicked: delete', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"page": "/sulamprojectex/test-frontend-logger.html", "action": "delete", "timestamp": "2025-12-06T13:00:08.321Z"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:08'),
	(15, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'info', 'custom', 'Button clicked: save', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"page": "/sulamprojectex/test-frontend-logger.html", "action": "save", "timestamp": "2025-12-06T13:00:08.571Z"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:08'),
	(16, 2, 'b58cdvbmvni3ut1lmlhqrv6c09', 'warning', 'custom', 'Slow operation detected', NULL, 'http://localhost/sulamprojectex/test-frontend-logger.html', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Chrome', 'Windows', '1536x865', '{"line": null, "extra": {"duration": 2004.10000000149, "operation": "simulateSlowOperation"}, "column": null, "source": null}', '::1', '2025-12-06 13:00:10');

-- Dumping structure for table masjidkamek.funeral_logistics
CREATE TABLE IF NOT EXISTS `funeral_logistics` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `death_notification_id` int NOT NULL,
  `burial_date` date DEFAULT NULL,
  `burial_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grave_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arranged_by` int unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `death_notification_id` (`death_notification_id`),
  KEY `arranged_by` (`arranged_by`),
  CONSTRAINT `funeral_logistics_ibfk_1` FOREIGN KEY (`death_notification_id`) REFERENCES `death_notifications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `funeral_logistics_ibfk_2` FOREIGN KEY (`arranged_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.funeral_logistics: ~0 rows (approximately)

-- Dumping structure for table masjidkamek.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table masjidkamek.migrations: ~7 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `executed_at`) VALUES
	(1, '004_add_relationship_to_next_of_kin.sql', '2025-12-11 03:42:31'),
	(2, '006_create_financial_tables.sql', '2025-12-11 03:42:31'),
	(3, '007_seed_financial_data.sql', '2025-12-11 03:42:31'),
	(4, '011_create_financial_settings_table.sql', '2025-12-11 03:42:31'),
	(5, '013_update_payment_method_enum.sql', '2025-12-11 03:42:32'),
	(6, '014_update_deathnotifications.sql', '2025-12-11 03:42:32'),
	(7, '015_update_funeral_logistics.sql', '2025-12-11 03:42:32');

-- Dumping structure for table masjidkamek.next_of_kin
CREATE TABLE IF NOT EXISTS `next_of_kin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_next_of_kin_user_id` (`user_id`),
  CONSTRAINT `fk_next_of_kin_user_boot` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.next_of_kin: ~0 rows (approximately)

-- Dumping structure for table masjidkamek.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` enum('resident','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'resident',
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `housing_status` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed','others') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deceased` tinyint(1) NOT NULL DEFAULT '0',
  `income` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table masjidkamek.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `roles`, `phone_number`, `address`, `housing_status`, `marital_status`, `is_deceased`, `income`, `created_at`, `updated_at`) VALUES
	(1, 'user', 'user1234', 'yyy@gg.com', '$2y$10$ueHhzpVf1OuCqQdwZbHyheYQ4G88Xyt.iiu4xSRuDxMXnylE4LtdC', 'resident', NULL, NULL, NULL, NULL, 0, NULL, '2025-11-22 11:11:16', '2025-11-23 10:50:04'),
	(2, 'Admin User', 'admin', 'admin@example.com', '$2y$10$TpCoz.GGNzxB1kWd4ZRhX.lsJCj4fmuAm4oo9DiZ2HsLCcsEE.5IW', 'admin', NULL, NULL, NULL, NULL, 0, NULL, '2025-11-22 19:40:25', '2025-12-06 11:16:32');

-- Dumping structure for view masjidkamek.v_audit_trail
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_audit_trail` (
	`id` INT(10) UNSIGNED NOT NULL,
	`table_name` VARCHAR(100) NOT NULL COMMENT 'Table being audited (e.g., financial_payment_accounts)' COLLATE 'utf8mb4_unicode_ci',
	`record_id` INT(10) UNSIGNED NOT NULL COMMENT 'ID of the record being changed',
	`action` ENUM('create','update','delete','restore') NOT NULL COMMENT 'Type of action performed' COLLATE 'utf8mb4_unicode_ci',
	`user_id` INT(10) UNSIGNED NULL COMMENT 'User who performed the action',
	`username` VARCHAR(50) NULL COLLATE 'utf8mb4_unicode_ci',
	`user_fullname` VARCHAR(120) NULL COLLATE 'utf8mb4_unicode_ci',
	`changed_fields` JSON NULL COMMENT 'Fields that were changed (for updates)',
	`old_values` JSON NULL COMMENT 'Previous values before change',
	`new_values` JSON NULL COMMENT 'New values after change',
	`ip_address` VARCHAR(45) NULL COMMENT 'IP address of user' COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NOT NULL,
	`transaction_type` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view masjidkamek.v_audit_trail
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_audit_trail`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_audit_trail` AS select `al`.`id` AS `id`,`al`.`table_name` AS `table_name`,`al`.`record_id` AS `record_id`,`al`.`action` AS `action`,`al`.`user_id` AS `user_id`,coalesce(`u`.`username`,`al`.`username`) AS `username`,coalesce(`u`.`name`,`al`.`user_fullname`) AS `user_fullname`,`al`.`changed_fields` AS `changed_fields`,`al`.`old_values` AS `old_values`,`al`.`new_values` AS `new_values`,`al`.`ip_address` AS `ip_address`,`al`.`created_at` AS `created_at`,(case when (`al`.`table_name` = 'financial_payment_accounts') then 'Payment' when (`al`.`table_name` = 'financial_deposit_accounts') then 'Deposit' else `al`.`table_name` end) AS `transaction_type` from (`audit_logs` `al` left join `users` `u` on((`al`.`user_id` = `u`.`id`))) order by `al`.`created_at` desc;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
