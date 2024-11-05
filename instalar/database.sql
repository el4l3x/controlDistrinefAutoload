-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla distrinef_dashboard.competitors
CREATE TABLE IF NOT EXISTS `competitors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filtro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.competitors: ~11 rows (aproximadamente)
INSERT INTO `competitors` (`id`, `nombre`, `filtro`, `created_at`, `updated_at`) VALUES
	(1, 'Gasfriocalor', '//span[@class=\'current-price\']//span[@class=\'product-price current-price-value\']', '2024-03-13 02:27:57', '2024-03-13 02:27:57'),
	(2, 'climahorro', '//*[@class=\'product-price current-price-value\']', '2024-03-13 02:28:26', '2024-03-13 02:28:26'),
	(3, 'ahorraclima', '//div[@class=\'current-price\']//span[@class=\'price\']', '2024-03-13 02:28:51', '2024-03-13 02:28:51'),
	(4, 'expertclima', '//div[@class=\'current-price\']//span[@class=\'current-price-value\']', '2024-03-13 02:29:27', '2024-03-13 02:29:27'),
	(5, 'tucalentadoreconomico', '//div[@class=\'current-price\']//span[@itemprop=\'price\']', '2024-03-13 02:29:53', '2024-03-13 02:29:53'),
	(6, 'Rehabilitaweb', '//span[@class=\'js-money font-weight-bold\']', '2024-03-26 19:07:48', '2024-03-26 19:07:48'),
	(7, 'Tuandco', '//span[@x-html=\'getFormattedFinalPrice()\']', '2024-03-26 19:15:04', '2024-03-26 19:15:04'),
	(8, 'Climamania', '//span[@class=\'current-price\']//span[@class=\'product-price current-price-value\']', '2024-03-26 19:39:01', '2024-03-26 19:39:01'),
	(9, 'Todoenclima', '//span[@itemprop=\'price\']', '2024-03-26 19:42:44', '2024-03-26 19:42:44'),
	(10, 'climaprecio', '//span[@itemprop=\'price\']', '2024-04-25 13:49:21', NULL),
	(11, 'habitium', '//span[@id=\'our_price_display_with_tax\']', '2024-04-25 13:50:57', NULL);

-- Volcando estructura para tabla distrinef_dashboard.competitors_divisonled
CREATE TABLE IF NOT EXISTS `competitors_divisonled` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filtro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.competitors_divisonled: ~3 rows (aproximadamente)
INSERT INTO `competitors_divisonled` (`id`, `nombre`, `filtro`, `created_at`, `updated_at`) VALUES
	(3, 'DivisionLed', '//div[@class=\'current-price\']//span[@itemprop=\'price\']', '2024-09-10 18:09:59', '2024-09-10 18:09:59'),
	(4, 'EfectoLED', '//div[@id=\'addToCart\']', '2024-09-10 18:20:32', '2024-09-10 18:20:32'),
	(5, 'Lamparas', '//div[@class=\'current-price\']//span[@itemprop=\'price\']', '2024-09-10 18:22:05', '2024-09-10 18:22:05');

-- Volcando estructura para tabla distrinef_dashboard.competitor_product
CREATE TABLE IF NOT EXISTS `competitor_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `competitor_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `competitor_product_competitor_id_foreign` (`competitor_id`),
  KEY `competitor_product_product_id_foreign` (`product_id`),
  CONSTRAINT `competitor_product_competitor_id_foreign` FOREIGN KEY (`competitor_id`) REFERENCES `competitors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `competitor_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26075 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando estructura para tabla distrinef_dashboard.competitor_product_divisonled
CREATE TABLE IF NOT EXISTS `competitor_product_divisonled` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `competitor_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando estructura para tabla distrinef_dashboard.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla distrinef_dashboard.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.migrations: ~18 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2014_10_12_100000_create_password_resets_table', 1),
	(4, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
	(5, '2019_08_19_000000_create_failed_jobs_table', 1),
	(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(7, '2024_03_01_220601_create_sessions_table', 1),
	(8, '2024_03_12_143043_create_competitors_table', 1),
	(9, '2024_03_12_144401_create_products_table', 1),
	(10, '2024_03_12_164153_create_competitor_product_table', 1),
	(11, '2024_03_26_131112_add_idgfc_products_table', 2),
	(14, '2024_04_04_132218_create_permission_tables', 3),
	(15, '2024_04_11_135142_add_status_columns_to_users_table', 3),
	(16, '2024_04_26_124958_add_reference_columns_to_products_table', 4),
	(17, '2024_05_02_152748_create_partners_table', 5),
	(18, '2024_05_02_152840_create_reports_table', 5),
	(19, '2024_05_02_152852_create_partner_report_table', 5),
	(20, '2024_05_17_122100_add_slug_columns_to_partners_table', 6),
	(21, '2024_09_09_171211_create_products_divisonled_table', 7),
	(22, '2024_09_09_171407_create_competitors_divisonled_table', 7),
	(23, '2024_09_09_171547_create_competitor_product_divisonled_table', 7);

-- Volcando estructura para tabla distrinef_dashboard.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.model_has_permissions: ~4 rows (aproximadamente)
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
	(2, 'App\\Models\\User', 5),
	(3, 'App\\Models\\User', 5),
	(4, 'App\\Models\\User', 5),
	(7, 'App\\Models\\User', 5);

-- Volcando estructura para tabla distrinef_dashboard.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.model_has_roles: ~5 rows (aproximadamente)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(1, 'App\\Models\\User', 3),
	(2, 'App\\Models\\User', 4),
	(3, 'App\\Models\\User', 5),
	(2, 'App\\Models\\User', 6);

-- Volcando estructura para tabla distrinef_dashboard.partners
CREATE TABLE IF NOT EXISTS `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.partners: ~6 rows (aproximadamente)
INSERT INTO `partners` (`id`, `name`, `created_at`, `updated_at`, `slug`) VALUES
	(1, 'Abad', '2024-05-02 21:58:37', '2024-05-02 21:58:37', 'abad'),
	(2, 'Ferreteria Ubetense', '2024-05-02 21:58:37', '2024-05-02 21:58:37', 'ferreteria-ubetense'),
	(3, 'Magserveis', '2024-05-02 21:58:37', '2024-05-02 21:58:37', 'magserveis'),
	(4, 'Calefon', '2024-05-02 21:58:37', '2024-05-02 21:58:37', 'calefon'),
	(9, 'ElectroMercantil', '2024-05-28 14:44:39', '2024-05-28 14:44:39', 'electromercantil'),
	(10, 'Calygas', '2024-05-28 14:47:08', '2024-05-28 14:47:08', 'calygas');

-- Volcando estructura para tabla distrinef_dashboard.partner_report
CREATE TABLE IF NOT EXISTS `partner_report` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `total` int NOT NULL,
  `revisados` int NOT NULL,
  `afectados` int NOT NULL,
  `errores` int NOT NULL DEFAULT '0',
  `tiempo` time NOT NULL,
  `partner_id` bigint unsigned NOT NULL,
  `report_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_report_partner_id_foreign` (`partner_id`),
  KEY `partner_report_report_id_foreign` (`report_id`),
  CONSTRAINT `partner_report_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `partner_report_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=909 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando estructura para tabla distrinef_dashboard.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.password_resets: ~0 rows (aproximadamente)

-- Volcando estructura para tabla distrinef_dashboard.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla distrinef_dashboard.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.permissions: ~10 rows (aproximadamente)
INSERT INTO `permissions` (`id`, `name`, `description`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'usuarios.index', 'Ver Usuarios', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(2, 'dashboard.index', 'Ver Dashboard', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(3, 'mejores.productos.index', 'Ver Mejores Productos', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(4, 'monitor.index', 'Ver Monitor de Precios', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(5, 'oportunidades.index', 'Ver Oportunidades de Ventas', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(6, 'informes.excel', 'Descarga de Informes Excel', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(7, 'consulta.stocks.netos', 'Consulta de Stocks y Netos', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(8, 'desbloquear.pedidos', 'Desbloquear Pedidos', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(9, 'subir.dtos.compra', 'Subir Dtos de Compra CSV', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(10, 'modificar.precios', 'Modificar Precios en Masa', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26');

-- Volcando estructura para tabla distrinef_dashboard.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla distrinef_dashboard.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idgfc` bigint unsigned NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3380 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando estructura para tabla distrinef_dashboard.products_divisonled
CREATE TABLE IF NOT EXISTS `products_divisonled` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando estructura para tabla distrinef_dashboard.reports
CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.reports: ~3 rows (aproximadamente)
INSERT INTO `reports` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Volcar CSV productos', '2024-05-02 21:59:02', '2024-05-02 21:59:02'),
	(2, 'Volcar CSV combinaciones', '2024-05-02 21:59:02', '2024-05-02 21:59:02'),
	(3, 'Volcar CSV Distribase', NULL, NULL);

-- Volcando estructura para tabla distrinef_dashboard.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'SuperAdmin', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(2, 'Normal', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26'),
	(3, 'Restringido', 'web', '2024-04-11 21:53:26', '2024-04-11 21:53:26');

-- Volcando estructura para tabla distrinef_dashboard.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.role_has_permissions: ~19 rows (aproximadamente)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(2, 2),
	(3, 2),
	(4, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 2),
	(10, 2);

-- Volcando estructura para tabla distrinef_dashboard.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando estructura para tabla distrinef_dashboard.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla distrinef_dashboard.users: ~1 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `username`, `email_verified_at`, `password`, `status`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador', 'distrinef', NULL, '$2y$12$a9YSnsfCJNisq9YTQW3BOe.2g1.QHoWr02t4NOi1JjlkwJtBwVbaa', 1, NULL, NULL, NULL, 'tvBMOWf7sf8MbkPvOgZX65mh0bZ9bgHL3mqpAj8DDITC4w6KEGSCnTSGPTfJ', NULL, 'user-stock.png', '2024-03-13 02:27:00', '2024-03-21 18:21:44');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
