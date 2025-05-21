-- Buat tabel settings jika belum ada
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tambahkan setting site_logo jika belum ada
INSERT IGNORE INTO `settings` (`key`, `value`, `group`, `is_public`, `created_at`, `updated_at`)
VALUES ('site_logo', 'images/logo.png', 'appearance', 1, NOW(), NOW());

-- Tambahkan setting site_favicon jika belum ada
INSERT IGNORE INTO `settings` (`key`, `value`, `group`, `is_public`, `created_at`, `updated_at`)
VALUES ('site_favicon', 'images/favicon.ico', 'appearance', 1, NOW(), NOW());

-- Tambahkan setting welcome_slider_enabled jika belum ada
INSERT IGNORE INTO `settings` (`key`, `value`, `group`, `is_public`, `created_at`, `updated_at`)
VALUES ('welcome_slider_enabled', 'true', 'home', 1, NOW(), NOW());

-- Tambahkan setting welcome_slider_speed jika belum ada
INSERT IGNORE INTO `settings` (`key`, `value`, `group`, `is_public`, `created_at`, `updated_at`)
VALUES ('welcome_slider_speed', '5000', 'home', 1, NOW(), NOW());

-- Tambahkan setting welcome_slider_images jika belum ada
INSERT IGNORE INTO `settings` (`key`, `value`, `group`, `is_public`, `created_at`, `updated_at`)
VALUES ('welcome_slider_images', '["images\\/vpn_network.png"]', 'home', 1, NOW(), NOW());

-- Buat tabel slides jika belum ada
CREATE TABLE IF NOT EXISTS `slides` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tambahkan slide default jika tabel kosong
INSERT INTO `slides` (`title`, `description`, `image_path`, `active`, `order`, `created_at`, `updated_at`)
SELECT 'Welcome to DRP Network Solutions', 'Your networking partner for success', 'images/vpn_network.png', 1, 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `slides` LIMIT 1); 