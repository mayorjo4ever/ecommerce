-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 06, 2026 at 07:17 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suntonio_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_1` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Nigeria',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `regno` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `regno`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Ojo Mayowa', 'mayorjo82@yahoo.com', '2026-04-05 16:38:18', '$2y$12$H9HPJcbIWUYnI.JA6UxzrOTYySHigAr0AIkvKmIP8KfF3zMRq20rm', NULL, '2026-04-05 16:38:18', '2026-04-05 16:38:18'),
(2, NULL, 'Admin User', 'admin@admin.com', '2026-04-05 16:38:18', '$2y$12$yTosI90M40UASA.2CN6n7eERzGCQf0lZ8jiZbZJ6AtRqyYb/Tf2t6', NULL, '2026-04-05 16:38:18', '2026-04-05 18:58:46'),
(3, NULL, 'Manager User', 'manager@admin.com', '2026-04-05 16:38:19', '$2y$12$MDWtj5zX6gje6lHt78ryueNvC9hcR5rCoqYZzbUGcgSiqmDcHL/e2', NULL, '2026-04-05 16:38:19', '2026-04-05 16:38:19'),
(4, NULL, 'Staff User', 'staff@admin.com', '2026-04-05 16:38:19', '$2y$12$kvzEowaQdM/chWkC1O13MuWE8YLP1bTppnQpk601x7R83Qd3C40mu', NULL, '2026-04-05 16:38:19', '2026-04-05 16:38:19'),
(5, NULL, 'Support User', 'support@admin.com', '2026-04-05 16:38:20', '$2y$12$gvmaEWdnEs1V8jwa7X06D.BxnK3aiddz8iW4GYI6UxLSd0o4/cGLK', NULL, '2026-04-05 16:38:20', '2026-04-05 16:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('suntonio-hub-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:38:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:11:\"view orders\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:13:\"create orders\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:11:\"edit orders\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"delete orders\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:14:\"process orders\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"view coupons\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:14:\"create coupons\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:12:\"edit coupons\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:14:\"delete coupons\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"view reviews\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:15:\"approve reviews\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:14:\"delete reviews\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:11:\"view admins\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:13:\"create admins\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:11:\"edit admins\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:13:\"delete admins\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:12:\"assign roles\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:13:\"view settings\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"edit settings\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:14:\"export reports\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:23:\"collect partial payment\";s:1:\"c\";s:5:\"admin\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:5:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"Super Admin\";s:1:\"c\";s:5:\"admin\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:5:\"admin\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:7:\"Manager\";s:1:\"c\";s:5:\"admin\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:5:\"Staff\";s:1:\"c\";s:5:\"admin\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:16:\"Customer Support\";s:1:\"c\";s:5:\"admin\";}}}', 1775504984);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `session_id` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_foreign` (`cart_id`),
  KEY `cart_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 'electronics', NULL, NULL, NULL, 1, 0, '2026-04-05 16:55:34', '2026-04-05 16:55:34'),
(2, 'Television', 'television', NULL, NULL, 1, 1, 1, '2026-04-05 16:55:59', '2026-04-05 16:55:59');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `minimum_purchase` decimal(10,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_07_151232_create_admins_table', 1),
(5, '2026_02_07_152850_create_permission_tables', 1),
(6, '2026_02_07_153529_create_categories_table', 1),
(7, '2026_02_07_153531_create_products_table', 1),
(8, '2026_02_07_153534_create_product_images_table', 1),
(9, '2026_02_07_153535_create_addresses_table', 1),
(10, '2026_02_07_153536_create_orders_table', 1),
(11, '2026_02_07_153537_create_order_items_table', 1),
(12, '2026_02_07_153538_create_carts_table', 1),
(13, '2026_02_07_153539_create_cart_items_table', 1),
(14, '2026_02_07_153541_create_payments_table', 1),
(15, '2026_02_07_153542_create_coupons_table', 1),
(16, '2026_02_07_153543_create_reviews_table', 1),
(17, '2026_02_08_104602_add_phone_to_users_table', 1),
(18, '2026_02_10_040604_add_payment_tracking_to_orders_table', 1),
(19, '2026_02_11_072845_add_description_to_coupons_table', 1),
(20, '2026_02_11_074214_create_stock_takes_table', 1),
(21, '2026_02_11_074217_create_stock_take_items_table', 1),
(23, '2026_04_06_053921_create_stock_movements_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1),
(2, 'App\\Models\\Admin', 2),
(3, 'App\\Models\\Admin', 3),
(4, 'App\\Models\\Admin', 4),
(5, 'App\\Models\\Admin', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','partial','paid','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `address_id` bigint UNSIGNED DEFAULT NULL,
  `qr_code` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_address_id_foreign` (`address_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `subtotal`, `tax`, `shipping`, `discount`, `total`, `amount_paid`, `balance`, `status`, `payment_status`, `notes`, `address_id`, `qr_code`, `created_at`, `updated_at`) VALUES
(1, 'POS-69D2BDEC80B25', 1, 156000.00, 5000.00, 0.00, 2500.00, 158500.00, 158500.00, 0.00, 'delivered', 'paid', 'Point of Sale Transaction', NULL, 'qrcodes/order_1_WhB2n1PL.svg', '2026-04-05 18:54:20', '2026-04-05 20:24:27'),
(2, 'POS-69D2D1F006BBD', 1, 776000.00, 0.00, 0.00, 0.00, 776000.00, 776000.00, 0.00, 'shipped', 'paid', 'Point of Sale Transaction', NULL, 'qrcodes/order_2_2ogmmpaZ.svg', '2026-04-05 20:19:44', '2026-04-05 20:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 156000.00, 156000.00, '2026-04-05 18:54:20', '2026-04-05 18:54:20'),
(2, 2, 1, 1, 156000.00, 156000.00, '2026-04-05 20:19:44', '2026-04-05 20:19:44'),
(3, 2, 2, 1, 620000.00, 620000.00, '2026-04-05 20:19:44', '2026-04-05 20:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_order_id_foreign` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`, `payment_details`, `created_at`, `updated_at`) VALUES
(1, 1, 'TXN-69D2BDEC9ADA9', 'cash', 78000.00, 'completed', '\"{\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-04-05 19:54:20.635502\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"},\\\"payment_type\\\":\\\"cash\\\"}\"', '2026-04-05 18:54:20', '2026-04-05 18:54:20'),
(2, 1, 'TXN-69D2BE85046B7', 'cash', 20000.00, 'completed', '\"{\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-04-05 19:56:53.018846\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"},\\\"payment_type\\\":\\\"cash\\\",\\\"note\\\":\\\"Additional payment for partial payment order\\\"}\"', '2026-04-05 18:56:53', '2026-04-05 18:56:53'),
(3, 2, 'TXN-69D2D1F023E79', 'pos', 600000.00, 'completed', '\"{\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-04-05 21:19:44.148211\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"},\\\"payment_type\\\":\\\"pos\\\"}\"', '2026-04-05 20:19:44', '2026-04-05 20:19:44'),
(4, 2, 'TXN-69D2D2A352B46', 'pos', 176000.00, 'completed', '\"{\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-04-05 21:22:43.339690\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"},\\\"payment_type\\\":\\\"pos\\\",\\\"note\\\":\\\"Additional payment for partial payment order\\\"}\"', '2026-04-05 20:22:43', '2026-04-05 20:22:43'),
(5, 1, 'TXN-69D2D30B7ADA4', 'cash', 60500.00, 'completed', '\"{\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-04-05 21:24:27.503700\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"},\\\"payment_type\\\":\\\"cash\\\",\\\"note\\\":\\\"Additional payment for partial payment order\\\"}\"', '2026-04-05 20:24:27', '2026-04-05 20:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view products', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(2, 'create products', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(3, 'edit products', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(4, 'delete products', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(5, 'view categories', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(6, 'create categories', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(7, 'edit categories', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(8, 'delete categories', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(9, 'view orders', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(10, 'create orders', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(11, 'edit orders', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(12, 'delete orders', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(13, 'process orders', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(14, 'view customers', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(15, 'create customers', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(16, 'edit customers', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(17, 'delete customers', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(18, 'view coupons', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(19, 'create coupons', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(20, 'edit coupons', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(21, 'delete coupons', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(22, 'view reviews', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(23, 'approve reviews', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(24, 'delete reviews', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(25, 'view admins', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(26, 'create admins', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(27, 'edit admins', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(28, 'delete admins', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(29, 'view roles', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(30, 'create roles', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(31, 'edit roles', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(32, 'delete roles', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(33, 'assign roles', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(34, 'view settings', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(35, 'edit settings', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(36, 'view reports', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(37, 'export reports', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(38, 'collect partial payment', 'admin', '2026-04-05 16:42:12', '2026-04-05 18:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `category_id` bigint UNSIGNED NOT NULL,
  `featured_image` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `qr_code` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `description`, `short_description`, `price`, `sale_price`, `quantity`, `category_id`, `featured_image`, `is_featured`, `is_active`, `qr_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'LG TV  32 Inches', 'lg-tv-32-inches', 'SKU-LG -775502', NULL, NULL, 170000.00, 156000.00, 13, 2, 'products/1775411807_WVa1SioxWM.jpg', 0, 1, 'qrcodes/product_1_OJHiSAna.svg', '2026-04-05 16:56:48', '2026-04-06 05:44:08', NULL),
(2, 'HP LAPTOP', 'hp-laptop', 'SKU-HP -489737', NULL, 'Laptop 13.3 inch 8GB RAM 128GB ROM Quad Core Celeron, Windows 10 Thin and Gray', 650000.00, 620000.00, 15, 1, 'products/1775421110_fyeHxfagml.jpg', 0, 1, 'qrcodes/product_2_g0f0gPU4.svg', '2026-04-05 19:31:50', '2026-04-06 05:41:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `order`, `created_at`, `updated_at`) VALUES
(1, 2, 'products/1775421111_XsEhi4YMeo.jpg', 1, '2026-04-05 19:31:52', '2026-04-05 19:31:52'),
(2, 2, 'products/1775421112_aLUCdqWZ9w.jpg', 2, '2026-04-05 19:31:53', '2026-04-05 19:31:53');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_product_id_user_id_unique` (`product_id`,`user_id`),
  KEY `reviews_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(2, 'Admin', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(3, 'Manager', 'admin', '2026-04-05 16:38:16', '2026-04-05 16:38:16'),
(4, 'Staff', 'admin', '2026-04-05 16:38:17', '2026-04-05 16:38:17'),
(5, 'Customer Support', 'admin', '2026-04-05 16:38:17', '2026-04-05 16:38:17');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(11, 3),
(11, 5),
(12, 1),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(14, 5),
(15, 1),
(16, 1),
(16, 2),
(16, 5),
(17, 1),
(18, 1),
(18, 2),
(18, 3),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(22, 3),
(22, 4),
(22, 5),
(23, 1),
(23, 2),
(23, 3),
(23, 5),
(24, 1),
(24, 2),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(36, 2),
(36, 3),
(37, 1),
(38, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('uxR9zJ7NqKtGQtxhd5a7hOfe8ThJTx806vizzWdK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 OPR/129.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic0RzMXpQZDJKY0tDRlZlQVZ3MU1HWHZla3FhV2pTa2RJSXZibVpGWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO319', 1775459756);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `supplier_name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment','return','damaged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in',
  `quantity` int NOT NULL,
  `quantity_before` int NOT NULL,
  `quantity_after` int NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `reference_no` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_created_by_foreign` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `supplier_name`, `created_by`, `type`, `quantity`, `quantity_before`, `quantity_after`, `cost_price`, `reference_no`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 1, 'damaged', 2, 17, 15, NULL, NULL, 'The Screen was damaged', '2026-04-06 05:41:18', '2026-04-06 05:41:18'),
(2, 1, 'LG Vendor', 1, 'in', 5, 8, 13, NULL, NULL, NULL, '2026-04-06 05:44:08', '2026-04-06 05:44:08');

-- --------------------------------------------------------

--
-- Table structure for table `stock_takes`
--

DROP TABLE IF EXISTS `stock_takes`;
CREATE TABLE IF NOT EXISTS `stock_takes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('weekly','monthly','yearly','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `completed_by` bigint UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_takes_reference_unique` (`reference`),
  KEY `stock_takes_created_by_foreign` (`created_by`),
  KEY `stock_takes_completed_by_foreign` (`completed_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_take_items`
--

DROP TABLE IF EXISTS `stock_take_items`;
CREATE TABLE IF NOT EXISTS `stock_take_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stock_take_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `system_quantity` int NOT NULL,
  `physical_quantity` int DEFAULT NULL,
  `variance` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_take_items_stock_take_id_foreign` (`stock_take_id`),
  KEY `stock_take_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mr. Chika Nmnazor', 'customer_1775414214@walkin.local', '08033225598', NULL, '$2y$12$bW5hcNIeaEMZRJkfSJ99tuXwDWvfqI6Gb6AxWcePw1Lpjqmdf2oOq', NULL, '2026-04-05 17:36:54', '2026-04-05 17:36:54');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
