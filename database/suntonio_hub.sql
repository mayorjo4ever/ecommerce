-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 10, 2026 at 03:42 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Nigeria',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `regno` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `regno`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Ojo Mayowa', 'mayorjo82@yahoo.com', '2026-02-07 16:27:32', '$2y$12$OJ.B/vVzwRrbtpWZ9ksvY.doj1BaHVgxsxRG.bR2pmZ.zn0I/dmeS', 'VqXQfHV78KSWcKTb0NQ6h63D4gNEY2Y2QHVgRogGK9O9MCPofy6z48WrvvlA', '2026-02-07 16:27:32', '2026-02-07 16:27:32'),
(2, NULL, 'Afolabi Sunday', 'suntonio@gmail.com', '2026-02-07 16:27:33', '$2y$12$vfC5tWuJeOGrQX2S3FlPyearH3eFQUqsSMRtOUXCoS9PvxENA2WX6', NULL, '2026-02-07 16:27:33', '2026-02-08 14:28:54'),
(3, NULL, 'Manager User', 'manager@admin.com', '2026-02-07 16:27:34', '$2y$12$1IhR4k/HjPiLAIXm2s6mQeyiYHZLyQbPJcp2QCGH7gG.vvj1d89yC', NULL, '2026-02-07 16:27:34', '2026-02-07 16:27:34'),
(4, NULL, 'Staff User', 'staff@admin.com', '2026-02-07 16:27:35', '$2y$12$OE.D/0o261TXoeQWusNPReqUmFnBpgqXZyGNVK8GbKRih4Gg2ADiG', NULL, '2026-02-07 16:27:35', '2026-02-07 16:27:35'),
(5, NULL, 'Support User', 'support@admin.com', '2026-02-07 16:27:36', '$2y$12$FXswmuEUcI3GWe.DWicmuu8vYKDHnV8XINOmMSBrTo4H9dARbFjhK', NULL, '2026-02-07 16:27:36', '2026-02-07 16:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', 'electronics', 'Description for Electronics', NULL, NULL, 1, 0, '2026-02-07 17:24:51', '2026-02-07 17:24:51'),
(2, 'Wears', 'wears', 'Description for Wears', NULL, NULL, 1, 0, '2026-02-07 17:24:51', '2026-02-08 07:44:09'),
(3, 'Tubers', 'tubers', 'Description for Tubers', NULL, 4, 1, 1, '2026-02-07 17:24:51', '2026-02-08 09:01:09'),
(4, 'Food and Beverages', 'food-and-beverages', 'Description for Home & Garden', NULL, NULL, 1, 0, '2026-02-07 17:24:51', '2026-02-08 07:33:10'),
(5, 'Grains', 'grains', 'Description for Grains', NULL, 4, 0, 2, '2026-02-07 17:24:51', '2026-02-08 09:30:48'),
(6, 'Shoes', 'shoes', NULL, NULL, 2, 1, 1, '2026-02-08 07:44:54', '2026-02-08 07:44:54'),
(7, 'Clothes', 'clothes', NULL, NULL, 2, 1, 1, '2026-02-08 07:45:50', '2026-02-08 07:45:50'),
(8, 'Televisions', 'televisions', 'Smart Electronics', NULL, 1, 1, 1, '2026-02-09 18:13:03', '2026-02-09 18:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` enum('fixed','percentage') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `minimum_purchase` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(9, '2026_02_07_153535_create_addresses_table', 2),
(10, '2026_02_07_153536_create_orders_table', 3),
(11, '2026_02_07_153537_create_order_items_table', 3),
(12, '2026_02_07_153538_create_carts_table', 3),
(13, '2026_02_07_153539_create_cart_items_table', 3),
(14, '2026_02_07_153541_create_payments_table', 3),
(15, '2026_02_07_153542_create_coupons_table', 3),
(16, '2026_02_07_153543_create_reviews_table', 3),
(17, '2026_02_08_104602_add_phone_to_users_table', 4),
(18, '2026_02_10_040604_add_payment_tracking_to_orders_table', 5),
(19, '2026_02_11_072845_add_description_to_coupons_table', 6),
(20, '2026_02_11_074214_create_stock_takes_table', 7),
(21, '2026_02_11_074217_create_stock_take_items_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1),
(1, 'App\\Models\\Admin', 2),
(3, 'App\\Models\\Admin', 3),
(4, 'App\\Models\\Admin', 4),
(5, 'App\\Models\\Admin', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','partial','paid','refunded') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `subtotal`, `tax`, `shipping`, `discount`, `total`, `amount_paid`, `balance`, `status`, `payment_status`, `notes`, `address_id`, `qr_code`, `created_at`, `updated_at`) VALUES
(1, 'POS-698A40B03A838', 8, '591000.00', '6000.00', '0.00', '15000.00', '582000.00', '0.00', '0.00', 'delivered', 'unpaid', 'Point of Sale Transaction', NULL, 'qrcodes/order_1_OHPpvSBx.svg', '2026-02-09 19:16:48', '2026-02-09 19:16:49'),
(2, 'POS-698A45A5789A2', 4, '198000.00', '0.00', '0.00', '0.00', '198000.00', '0.00', '0.00', 'delivered', 'unpaid', 'Point of Sale Transaction', NULL, 'qrcodes/order_2_TXaCkJnd.svg', '2026-02-09 19:37:57', '2026-02-09 19:37:57'),
(3, 'POS-698AB34F0063B', 1, '525000.00', '0.00', '0.00', '0.00', '525000.00', '200000.00', '325000.00', 'pending', 'partial', 'Point of Sale Transaction', NULL, 'qrcodes/order_3_20Md77wS.svg', '2026-02-10 03:25:51', '2026-02-10 03:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '66000.00', '66000.00', '2026-02-09 19:16:48', '2026-02-09 19:16:48'),
(2, 1, 2, 1, '525000.00', '525000.00', '2026-02-09 19:16:48', '2026-02-09 19:16:48'),
(3, 2, 1, 3, '66000.00', '198000.00', '2026-02-09 19:37:57', '2026-02-09 19:37:57'),
(4, 3, 2, 1, '525000.00', '525000.00', '2026-02-10 03:25:51', '2026-02-10 03:25:51');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`, `payment_details`, `created_at`, `updated_at`) VALUES
(1, 1, 'TXN-698A40B0A2608', 'cash', '582000.00', 'completed', '\"{\\\"amount_paid\\\":\\\"582000\\\",\\\"change\\\":0,\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-02-09 20:16:49.243969\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"}}\"', '2026-02-09 19:16:49', '2026-02-09 19:16:49'),
(2, 2, 'TXN-698A45A5ACD74', 'card', '198000.00', 'completed', '\"{\\\"amount_paid\\\":\\\"198000\\\",\\\"change\\\":0,\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-02-09 20:37:57.712518\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"}}\"', '2026-02-09 19:37:57', '2026-02-09 19:37:57'),
(3, 3, 'TXN-698AB34F2F85F', 'pos', '50000.00', 'completed', '\"{\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-02-10 04:25:51.235689\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"},\\\"payment_type\\\":\\\"pos\\\"}\"', '2026-02-10 03:25:51', '2026-02-10 03:25:51'),
(4, 3, 'TXN-698AB34F42D33', 'cash', '150000.00', 'completed', '\"{\\\"processed_by\\\":\\\"Ojo Mayowa\\\",\\\"processed_at\\\":{\\\"date\\\":\\\"2026-02-10 04:25:51.273857\\\",\\\"timezone_type\\\":3,\\\"timezone\\\":\\\"UTC\\\"},\\\"payment_type\\\":\\\"cash\\\"}\"', '2026-02-10 03:25:51', '2026-02-10 03:25:51');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view products', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(2, 'create products', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(3, 'edit products', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(4, 'delete products', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(5, 'view categories', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(6, 'create categories', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(7, 'edit categories', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(8, 'delete categories', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(9, 'view orders', 'admin', '2026-02-07 16:27:27', '2026-02-07 16:27:27'),
(10, 'create orders', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(11, 'edit orders', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(12, 'delete orders', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(13, 'process orders', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(14, 'view customers', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(15, 'create customers', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(16, 'edit customers', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(17, 'delete customers', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(18, 'view coupons', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(19, 'create coupons', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(20, 'edit coupons', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(21, 'delete coupons', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(22, 'view reviews', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(23, 'approve reviews', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(24, 'delete reviews', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(25, 'view admins', 'admin', '2026-02-07 16:27:28', '2026-02-07 16:27:28'),
(26, 'create admins', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(27, 'edit admins', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(28, 'delete admins', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(29, 'view roles', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(30, 'create roles', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(31, 'edit roles', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(32, 'delete roles', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(33, 'assign roles', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(34, 'view settings', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(35, 'edit settings', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(36, 'view reports', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(37, 'export reports', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(38, 'sell products', 'admin', '2026-02-08 19:51:27', '2026-02-08 19:51:27'),
(39, 'access pos', 'admin', '2026-02-08 20:16:02', '2026-02-08 20:16:02'),
(40, 'create pos sales', 'admin', '2026-02-08 20:16:02', '2026-02-08 20:16:02'),
(41, 'view pos history', 'admin', '2026-02-08 20:16:02', '2026-02-08 20:16:02'),
(42, 'print pos receipts', 'admin', '2026-02-08 20:16:02', '2026-02-08 20:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `sku`, `description`, `short_description`, `price`, `sale_price`, `quantity`, `category_id`, `featured_image`, `is_featured`, `is_active`, `qr_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Tomato Rice - 50KG', 'tomato-rice-50kg', 'tr-50kg', 'big food', 'no food', '67000.00', '66000.00', 20, 4, 'products/1770529120_TqjbxL9Jd5.jpg', 1, 1, 'qrcodes/product_1_maa0Ya2C.svg', '2026-02-08 04:38:52', '2026-02-12 04:29:26', NULL),
(2, 'Hisense 55\" Inches UHD 4K SMART TV', 'hisense-55-inches-uhd-4k-smart-tv', 'Hisense 55 Inches UHD 4K SMART TV', NULL, NULL, '533000.00', '525000.00', 30, 8, 'products/1770664553_wQtXegh9f0.jpg', 0, 1, 'qrcodes/product_2_HgqkDFmS.svg', '2026-02-09 18:16:07', '2026-03-07 18:04:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'products/1770529136_xMXntBYFEI.png', 1, '2026-02-08 04:38:57', '2026-02-08 04:38:57'),
(2, 2, 'products/1770664572_3rzENSGCUq.jpg', 1, '2026-02-09 18:16:12', '2026-02-09 18:16:12'),
(3, 2, 'products/1770664572_WN5d6u5yUv.jpg', 2, '2026-02-09 18:16:12', '2026-02-09 18:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', '2026-02-07 16:27:29', '2026-02-07 16:27:29'),
(2, 'Admin', 'admin', '2026-02-07 16:27:30', '2026-02-07 16:27:30'),
(3, 'Manager', 'admin', '2026-02-07 16:27:30', '2026-02-07 16:27:30'),
(4, 'Staff', 'admin', '2026-02-07 16:27:31', '2026-02-07 16:27:31'),
(5, 'Customer Support', 'admin', '2026-02-07 16:27:31', '2026-02-07 16:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(38, 1),
(39, 1),
(39, 2),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(42, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4bQwu1pPZHGGDSaVNct3qz524m2JjajkomHSS87b', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVjhzbDhodFhzdnBsdG8zYk1aWGFvbEhWVHdDYU82Y1MwVTZvQmt3OCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9hZG1pbi9yb2xlcyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ucm9sZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJwYWdlIjtzOjM6InBvcyI7fQ==', 1778377226),
('Ps53mT8Cakx0ouTKAolCdi2hwXLopEUEYLn1QXhf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 OPR/130.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVHk0S1FUbURHaVFBdUNYSFZHam82dVdaVzE2M09XYmxxdmtDVHZBWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9hZG1pbi9jdXN0b21lcnMiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLmN1c3RvbWVycy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6InBhZ2UiO3M6MzoicG9zIjt9', 1778377282);

-- --------------------------------------------------------

--
-- Table structure for table `stock_takes`
--

CREATE TABLE `stock_takes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `type` enum('weekly','monthly','yearly','custom') NOT NULL,
  `status` enum('draft','in_progress','completed') NOT NULL DEFAULT 'draft',
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `completed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_takes`
--

INSERT INTO `stock_takes` (`id`, `reference`, `type`, `status`, `period_start`, `period_end`, `notes`, `created_by`, `completed_by`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 'STK-698D64950869A', 'monthly', 'completed', '2026-02-01', '2026-02-28', NULL, 1, 1, '2026-02-12 04:29:26', '2026-02-12 04:26:45', '2026-02-12 04:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `stock_take_items`
--

CREATE TABLE `stock_take_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_take_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `system_quantity` int(11) NOT NULL,
  `physical_quantity` int(11) DEFAULT NULL,
  `variance` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_take_items`
--

INSERT INTO `stock_take_items` (`id`, `stock_take_id`, `product_id`, `system_quantity`, `physical_quantity`, `variance`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 16, 20, 4, NULL, '2026-02-12 04:26:45', '2026-02-12 04:28:06'),
(2, 1, 2, 28, 30, 2, NULL, '2026-02-12 04:26:45', '2026-02-12 04:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Customer 1', 'customer1@test.com', NULL, '2026-02-07 17:24:43', '$2y$12$2RVW4ml9iiIJLXJqU3/v5.3KOL8JiQMzoi0erbIaHH/pHUhDU6UDq', NULL, '2026-02-07 17:24:43', '2026-02-07 17:24:43'),
(2, 'Customer 2', 'customer2@test.com', NULL, '2026-02-07 17:24:44', '$2y$12$HwF4oMHCDhl1bBTYvcYb2.Ss0oGQ5Usl3I.1XSLDT8cwdVTwmCxB2', NULL, '2026-02-07 17:24:44', '2026-02-07 17:24:44'),
(3, 'Customer 3', 'customer3@test.com', NULL, '2026-02-07 17:24:45', '$2y$12$poH0CwQZFHjuEvqu4xDxH.91gO9yDzT7stSS1DY12aacgBmDKP2qa', NULL, '2026-02-07 17:24:45', '2026-02-07 17:24:45'),
(4, 'Customer 4', 'customer4@test.com', NULL, '2026-02-07 17:24:45', '$2y$12$OLTVfmkTOvrzZPSjT8utFe7SBsE15jLAAZxPbBPMxFsKQISXOTLHO', NULL, '2026-02-07 17:24:45', '2026-02-07 17:24:45'),
(5, 'Customer 5', 'customer5@test.com', NULL, '2026-02-07 17:24:46', '$2y$12$uV/5j3ICxmmqDkHq.i8Izu9Een9.lpsR.GhChhWmqSfkQacKQ23/2', NULL, '2026-02-07 17:24:46', '2026-02-07 17:24:46'),
(6, 'Customer 6', 'customer6@test.com', NULL, '2026-02-07 17:24:47', '$2y$12$6iFPvuQq/VIS0zMsLVgp1eqZEFBf3jtaHqICN5h35gtk8H2.VE3UW', NULL, '2026-02-07 17:24:47', '2026-02-07 17:24:47'),
(7, 'Customer 7', 'customer7@test.com', NULL, '2026-02-07 17:24:48', '$2y$12$LK7H2HkIjSjxWivRDEmny.Kg6NjD17OqqwPV7NyfPTeuLHLqRzx.K', NULL, '2026-02-07 17:24:48', '2026-02-07 17:24:48'),
(8, 'Customer 8', 'customer8@test.com', NULL, '2026-02-07 17:24:49', '$2y$12$TKUA6E4N8S/I0HOv.MCzrOABt3pfXgfck0pDSYES4J1ZOihrXkbAO', NULL, '2026-02-07 17:24:49', '2026-02-07 17:24:49'),
(9, 'Customer 9', 'customer9@test.com', NULL, '2026-02-07 17:24:50', '$2y$12$ZGytIbprIlyyZW.xahDH.eZh7hqisiUL/3e0Tuhf1JaizRYFimFiS', NULL, '2026-02-07 17:24:50', '2026-02-07 17:24:50'),
(10, 'Customer 10', 'customer10@test.com', NULL, '2026-02-07 17:24:51', '$2y$12$9B8CkdK6BlnmDzCL3lVEke8E/pIJW3WZKU1I1JLGl1SCDj.c2HbmK', NULL, '2026-02-07 17:24:51', '2026-02-07 17:24:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_address_id_foreign` (`address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_product_id_user_id_unique` (`product_id`,`user_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_takes`
--
ALTER TABLE `stock_takes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_takes_reference_unique` (`reference`),
  ADD KEY `stock_takes_created_by_foreign` (`created_by`),
  ADD KEY `stock_takes_completed_by_foreign` (`completed_by`);

--
-- Indexes for table `stock_take_items`
--
ALTER TABLE `stock_take_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_take_items_stock_take_id_foreign` (`stock_take_id`),
  ADD KEY `stock_take_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_takes`
--
ALTER TABLE `stock_takes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_take_items`
--
ALTER TABLE `stock_take_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_takes`
--
ALTER TABLE `stock_takes`
  ADD CONSTRAINT `stock_takes_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_takes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_take_items`
--
ALTER TABLE `stock_take_items`
  ADD CONSTRAINT `stock_take_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_take_items_stock_take_id_foreign` FOREIGN KEY (`stock_take_id`) REFERENCES `stock_takes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
