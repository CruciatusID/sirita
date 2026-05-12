-- SIRITA initial database import for phpMyAdmin.
-- Target database: kemenagt_berita
-- Import this file into the selected database in phpMyAdmin.
-- Default admin:
--   Username: admin
--   Password: password

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `activity_log`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `media`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `model_has_permissions`;
DROP TABLE IF EXISTS `model_has_roles`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `post_tag`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `role_has_permissions`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `units`;

CREATE TABLE `units` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `address` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_slug_unique` (`slug`),
  KEY `units_type_index` (`type`),
  KEY `units_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `unit_id` BIGINT UNSIGNED NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'active',
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_unit_id_index` (`unit_id`),
  KEY `users_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` BIGINT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` BIGINT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_roles` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  KEY `categories_is_active_index` (`is_active`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) NULL,
  `featured_image_caption` VARCHAR(255) NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `editor_user_id` BIGINT UNSIGNED NULL,
  `editor_name` VARCHAR(255) NULL,
  `unit_id` BIGINT UNSIGNED NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `published_at` TIMESTAMP NULL DEFAULT NULL,
  `seo_title` VARCHAR(255) NULL,
  `seo_description` TEXT NULL,
  `og_image` VARCHAR(255) NULL,
  `views` INT UNSIGNED NOT NULL DEFAULT 0,
  `likes_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `shares_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_category_id_foreign` (`category_id`),
  KEY `posts_user_id_foreign` (`user_id`),
  KEY `posts_editor_user_id_foreign` (`editor_user_id`),
  KEY `posts_unit_id_foreign` (`unit_id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_published_at_index` (`published_at`),
  CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `posts_editor_user_id_foreign` FOREIGN KEY (`editor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `posts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `post_tag` (
  `post_id` BIGINT UNSIGNED NOT NULL,
  `tag_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`, `tag_id`),
  KEY `post_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `seo_title` VARCHAR(255) NULL,
  `seo_description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `banners` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `link` VARCHAR(255) NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'active',
  `order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banners_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `media` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `caption` TEXT NULL,
  `path` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(255) NULL,
  `size` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `uploaded_by` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `media_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `activity_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` VARCHAR(255) NULL,
  `description` TEXT NOT NULL,
  `subject_type` VARCHAR(255) NULL,
  `subject_id` BIGINT UNSIGNED NULL,
  `event` VARCHAR(255) NULL,
  `causer_type` VARCHAR(255) NULL,
  `causer_id` BIGINT UNSIGNED NULL,
  `attribute_changes` JSON NULL,
  `properties` JSON NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_log_log_name_index` (`log_name`),
  KEY `subject` (`subject_type`, `subject_id`),
  KEY `causer` (`causer_type`, `causer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `units` (`id`, `name`, `slug`, `type`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kantor Kemenag Tana Toraja', 'kantor-kemenag-tana-toraja', 'Kantor', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(2, 'Seksi Bimbingan Masyarakat Kristen', 'seksi-bimbingan-masyarakat-kristen', 'Seksi', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(3, 'Seksi Bimbingan Masyarakat Islam', 'seksi-bimbingan-masyarakat-islam', 'Seksi', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(4, 'Seksi Pendidikan Islam', 'seksi-pendidikan-islam', 'Seksi', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(5, 'Penyelenggara Katolik', 'penyelenggara-katolik', 'Penyelenggara', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(6, 'Penyelenggara Zakat dan Wakaf', 'penyelenggara-zakat-dan-wakaf', 'Penyelenggara', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(7, 'KUA Bittuang', 'kua-bittuang', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(8, 'KUA Bonggakaradeng', 'kua-bonggakaradeng', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(9, 'KUA Gandangbatu Sillanan', 'kua-gandangbatu-sillanan', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(10, 'KUA Kurra', 'kua-kurra', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(11, 'KUA Makale', 'kua-makale', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(12, 'KUA Makale Selatan', 'kua-makale-selatan', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(13, 'KUA Makale Utara', 'kua-makale-utara', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(14, 'KUA Malimbong Balepe', 'kua-malimbong-balepe', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(15, 'KUA Mappak', 'kua-mappak', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(16, 'KUA Masanda', 'kua-masanda', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(17, 'KUA Mengkendek', 'kua-mengkendek', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(18, 'KUA Rano', 'kua-rano', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(19, 'KUA Rantetayo', 'kua-rantetayo', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(20, 'KUA Rembon', 'kua-rembon', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(21, 'KUA Saluputti', 'kua-saluputti', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(22, 'KUA Sangalla', 'kua-sangalla', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(23, 'KUA Sangalla Selatan', 'kua-sangalla-selatan', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(24, 'KUA Sangalla Utara', 'kua-sangalla-utara', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(25, 'KUA Simbuang', 'kua-simbuang', 'KUA', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(26, 'MIN 1 Tana Toraja', 'min-1-tana-toraja', 'Madrasah', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(27, 'MIN 2 Tana Toraja', 'min-2-tana-toraja', 'Madrasah', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(28, 'MIN 3 Tana Toraja', 'min-3-tana-toraja', 'Madrasah', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(29, 'MIN 4 Tana Toraja', 'min-4-tana-toraja', 'Madrasah', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(30, 'MTsN 1 Tana Toraja', 'mtsn-1-tana-toraja', 'Madrasah', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(31, 'MTsN 2 Tana Toraja', 'mtsn-2-tana-toraja', 'Madrasah', 'Kabupaten Tana Toraja', 1, NOW(), NOW()),
(32, 'MAN Tana Toraja', 'man-tana-toraja', 'Madrasah', 'Kabupaten Tana Toraja', 1, NOW(), NOW());

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', NOW(), NOW()),
(2, 'Admin Humas', 'web', NOW(), NOW()),
(3, 'Editor', 'web', NOW(), NOW()),
(4, 'Kontributor', 'web', NOW(), NOW());

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `unit_id`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin SIRITA', 'admin', NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi', 1, 'active', NULL, NOW(), NOW());

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Kemenag Tana Toraja', 'kemenag-tana-toraja', NULL, 1, NOW(), NOW()),
(2, NULL, 'Seksi Bimbingan Masyarakat Kristen', 'seksi-bimbingan-masyarakat-kristen', NULL, 1, NOW(), NOW()),
(3, NULL, 'Seksi Bimbingan Masyarakat Islam', 'seksi-bimbingan-masyarakat-islam', NULL, 1, NOW(), NOW()),
(4, NULL, 'Seksi Pendidikan Islam', 'seksi-pendidikan-islam', NULL, 1, NOW(), NOW()),
(5, NULL, 'Penyelenggara Katolik', 'penyelenggara-katolik', NULL, 1, NOW(), NOW()),
(6, NULL, 'Penyelenggara Zakat dan Wakaf', 'penyelenggara-zakat-dan-wakaf', NULL, 1, NOW(), NOW()),
(7, NULL, 'KUA', 'kua', NULL, 1, NOW(), NOW()),
(8, 7, 'Bittuang', 'bittuang', NULL, 1, NOW(), NOW()),
(9, 7, 'Bonggakaradeng', 'bonggakaradeng', NULL, 1, NOW(), NOW()),
(10, 7, 'Gandangbatu Sillanan', 'gandangbatu-sillanan', NULL, 1, NOW(), NOW()),
(11, 7, 'Kurra', 'kurra', NULL, 1, NOW(), NOW()),
(12, 7, 'Makale', 'makale', NULL, 1, NOW(), NOW()),
(13, 7, 'Makale Selatan', 'makale-selatan', NULL, 1, NOW(), NOW()),
(14, 7, 'Makale Utara', 'makale-utara', NULL, 1, NOW(), NOW()),
(15, 7, 'Malimbong Balepe', 'malimbong-balepe', NULL, 1, NOW(), NOW()),
(16, 7, 'Mappak', 'mappak', NULL, 1, NOW(), NOW()),
(17, 7, 'Masanda', 'masanda', NULL, 1, NOW(), NOW()),
(18, 7, 'Mengkendek', 'mengkendek', NULL, 1, NOW(), NOW()),
(19, 7, 'Rano', 'rano', NULL, 1, NOW(), NOW()),
(20, 7, 'Rantetayo', 'rantetayo', NULL, 1, NOW(), NOW()),
(21, 7, 'Rembon', 'rembon', NULL, 1, NOW(), NOW()),
(22, 7, 'Saluputti', 'saluputti', NULL, 1, NOW(), NOW()),
(23, 7, 'Sangalla', 'sangalla', NULL, 1, NOW(), NOW()),
(24, 7, 'Sangalla Selatan', 'sangalla-selatan', NULL, 1, NOW(), NOW()),
(25, 7, 'Sangalla Utara', 'sangalla-utara', NULL, 1, NOW(), NOW()),
(26, 7, 'Simbuang', 'simbuang', NULL, 1, NOW(), NOW()),
(27, NULL, 'Madrasah', 'madrasah', NULL, 1, NOW(), NOW()),
(28, 27, 'MIN 1 Tana Toraja', 'min-1-tana-toraja', NULL, 1, NOW(), NOW()),
(29, 27, 'MIN 2 Tana Toraja', 'min-2-tana-toraja', NULL, 1, NOW(), NOW()),
(30, 27, 'MIN 3 Tana Toraja', 'min-3-tana-toraja', NULL, 1, NOW(), NOW()),
(31, 27, 'MIN 4 Tana Toraja', 'min-4-tana-toraja', NULL, 1, NOW(), NOW()),
(32, 27, 'MTsN 1 Tana Toraja', 'mtsn-1-tana-toraja', NULL, 1, NOW(), NOW()),
(33, 27, 'MTsN 2 Tana Toraja', 'mtsn-2-tana-toraja', NULL, 1, NOW(), NOW()),
(34, 27, 'MAN Tana Toraja', 'man-tana-toraja', NULL, 1, NOW(), NOW());

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'ASN', 'asn', NOW(), NOW()),
(2, 'Layanan Publik', 'layanan-publik', NOW(), NOW()),
(3, 'Moderasi Beragama', 'moderasi-beragama', NOW(), NOW()),
(4, 'Kerukunan Umat', 'kerukunan-umat', NOW(), NOW()),
(5, 'Haji', 'haji', NOW(), NOW()),
(6, 'Umrah', 'umrah', NOW(), NOW()),
(7, 'Zakat', 'zakat', NOW(), NOW()),
(8, 'Wakaf', 'wakaf', NOW(), NOW()),
(9, 'Madrasah', 'madrasah', NOW(), NOW()),
(10, 'KUA', 'kua', NOW(), NOW()),
(11, 'Bimas Islam', 'bimas-islam', NOW(), NOW()),
(12, 'Bimas Kristen', 'bimas-kristen', NOW(), NOW()),
(13, 'Katolik', 'katolik', NOW(), NOW()),
(14, 'Pendidikan Islam', 'pendidikan-islam', NOW(), NOW()),
(15, 'PPID', 'ppid', NOW(), NOW()),
(16, 'Pengumuman', 'pengumuman', NOW(), NOW()),
(17, 'Kegiatan', 'kegiatan', NOW(), NOW()),
(18, 'Rapat Koordinasi', 'rapat-koordinasi', NOW(), NOW()),
(19, 'Pembinaan', 'pembinaan', NOW(), NOW()),
(20, 'Sosialisasi', 'sosialisasi', NOW(), NOW()),
(21, 'Digitalisasi', 'digitalisasi', NOW(), NOW()),
(22, 'Zona Integritas', 'zona-integritas', NOW(), NOW());

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `status`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 'Profil Kantor', 'profil-kantor', '<p>Konten halaman ini dapat diperbarui melalui panel admin.</p>', 'published', NULL, NULL, NOW(), NOW()),
(2, 'Visi Misi', 'visi-misi', '<p>Konten halaman ini dapat diperbarui melalui panel admin.</p>', 'published', NULL, NULL, NOW(), NOW()),
(3, 'Struktur Organisasi', 'struktur-organisasi', '<p>Konten halaman ini dapat diperbarui melalui panel admin.</p>', 'published', NULL, NULL, NOW(), NOW()),
(4, 'Kontak', 'kontak', '<p>Konten halaman ini dapat diperbarui melalui panel admin.</p>', 'published', NULL, NULL, NOW(), NOW()),
(5, 'PPID', 'ppid', '<p>Konten halaman ini dapat diperbarui melalui panel admin.</p>', 'published', NULL, NULL, NOW(), NOW());

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_05_04_122234_create_activity_log_table', 1),
('2026_05_04_122234_create_permission_tables', 1),
('2026_05_04_122240_create_units_table', 1),
('2026_05_04_122241_create_categories_table', 1),
('2026_05_04_122242_create_tags_table', 1),
('2026_05_04_122243_create_posts_table', 1),
('2026_05_04_122244_create_post_tag_table', 1),
('2026_05_04_122245_create_pages_table', 1),
('2026_05_04_122246_create_banners_table', 1),
('2026_05_04_122247_create_media_table', 1),
('2026_05_04_221556_add_parent_id_to_categories_table', 1),
('2026_05_05_093108_add_featured_image_caption_to_posts_table', 1),
('2026_05_05_093217_add_caption_to_media_table', 1),
('2026_05_05_120000_add_feedback_counts_to_posts_table', 1),
('2026_05_05_173000_add_editor_name_to_posts_table', 1),
('2026_05_05_181000_add_editor_user_id_to_posts_table', 1),
('2026_05_12_140000_add_username_and_nullable_email_to_users_table', 1);

SET FOREIGN_KEY_CHECKS = 1;
