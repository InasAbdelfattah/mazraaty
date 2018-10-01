-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2018 at 04:04 PM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.1.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mazraaty`
--

-- --------------------------------------------------------

--
-- Table structure for table `abilities`
--

CREATE TABLE `abilities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int(10) UNSIGNED DEFAULT NULL,
  `entity_type` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `only_owned` tinyint(1) NOT NULL DEFAULT '0',
  `scope` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abilities`
--

INSERT INTO `abilities` (`id`, `name`, `title`, `entity_id`, `entity_type`, `only_owned`, `scope`, `created_at`, `updated_at`) VALUES
(1, '*', 'All abilities', NULL, '*', 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(2, 'users_manage', 'ادارة المستخدمين', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(3, 'settings_manage', 'ادارة الاعدادات', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(4, 'orders_manage', 'ادارة الطلبات', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(5, 'reports_manage', 'ادارة التقارير', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(6, 'products_manage', 'ادارة المنتجات', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(7, 'ads_manage', 'ادارة الاعلانات', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(8, 'notifications_manage', 'ادارة الاشعارات', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(9, 'clients_manage', 'ادارة العملاء', NULL, NULL, 0, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `image`, `created_at`, `updated_at`) VALUES
(2, '1537099747.Ba2DBIIlLTRN0nVBvjIUb06b7885-2afa-446c-bb7e-f44f897b3b60.jpeg', '2018-09-16 13:08:48', '2018-09-16 13:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `assigned_roles`
--

CREATE TABLE `assigned_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL,
  `entity_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assigned_roles`
--

INSERT INTO `assigned_roles` (`role_id`, `entity_id`, `entity_type`, `scope`) VALUES
(1, 1, 'App\\User', NULL),
(2, 4, 'App\\User', NULL),
(2, 5, 'App\\User', NULL),
(2, 6, 'App\\User', NULL),
(2, 7, 'App\\User', NULL),
(2, 3, 'App\\User', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `baskets`
--

CREATE TABLE `baskets` (
  `id` int(10) UNSIGNED NOT NULL,
  `device` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_ordered` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `baskets`
--

INSERT INTO `baskets` (`id`, `device`, `user_id`, `created_at`, `updated_at`, `total_price`, `is_ordered`) VALUES
(1, 'ddddddddddd', NULL, '2018-09-10 07:46:13', '2018-09-10 16:02:56', NULL, 1),
(2, 'ddddddddddd', NULL, '2018-09-11 09:19:46', '2018-09-11 09:21:14', NULL, 1),
(3, 'ddddddddddd', NULL, '2018-09-11 15:07:32', '2018-09-11 15:07:38', NULL, 1),
(4, 'ddddddddddd', NULL, '2018-09-16 11:08:40', '2018-09-16 11:08:45', NULL, 1),
(5, 'ddddddddddd', NULL, '2018-09-17 12:54:25', '2018-09-17 12:57:57', NULL, 1),
(6, 'ddddddddddd', NULL, '2018-09-17 12:58:47', '2018-09-17 13:09:23', NULL, 1),
(7, 'ddddddddddd', 24, '2018-10-01 08:18:49', '2018-10-01 10:15:24', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `parent_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'قسم رئيسى1', '1535375696.K6B9LtMiqs800HwSucZR1514456436.jpg', 0, 0, '2018-08-27 11:10:56', '2018-09-25 10:09:29'),
(2, 'قسم فرعى 1', '1535455052.onoocuBpBjMiPN3jTqDWScreenshot (32).png', 0, 1, '2018-08-28 09:17:32', '2018-08-28 09:17:32'),
(3, 'aaaaa', '1535455943.K5ysWwUYb4DLYWN7GeYBScreenshot (3).png', 0, 1, '2018-08-28 09:20:42', '2018-08-28 09:32:23'),
(4, 'half', '1535456432.Tp3dXJPYTu7lXfTTb3vZScreenshot (4).png', 3, 1, '2018-08-28 09:40:32', '2018-08-28 09:42:38'),
(5, 'hff', '1535456492.KasqZ05s6UwLZ1Ksa8X0Screenshot (4).png', 3, 0, '2018-08-28 09:41:32', '2018-09-25 10:24:22'),
(6, 'قسم', '1537098469.AZPtch0RNGYQHhcV3evPb06b7885-2afa-446c-bb7e-f44f897b3b60.jpeg', 0, 1, '2018-09-16 12:47:50', '2018-09-16 12:48:25'),
(7, 'فرع', '1537098694.EhB9WPcSTJ3XFFjxIwi6One-Piece-PSVR-Ann-Init_07-12-17.jpg', 1, 0, '2018-09-16 12:50:57', '2018-09-16 12:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'مكة', 0, '2018-08-27 09:51:07', '2018-09-19 15:20:28'),
(2, 'الرياض', 1, '2018-08-29 07:07:47', '2018-09-16 12:09:37'),
(3, 'المدينة', 1, '2018-08-29 07:10:31', '2018-08-29 07:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `times` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `ratio` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `from`, `to`, `created_at`, `updated_at`, `times`, `ratio`) VALUES
(1, '5000', '2018-09-09', '2018-09-20', '2018-09-09 22:00:00', '2018-09-09 22:00:00', '2', '10'),
(2, '777', '2018-09-13', '2018-09-27', '2018-09-11 09:28:24', '2018-09-11 09:28:24', '7', '70'),
(3, '331', '2018-09-13', '2018-09-20', '2018-09-12 10:12:18', '2018-09-12 10:12:18', '3', '30'),
(4, '332', '2018-09-13', '2018-09-20', '2018-09-12 10:12:54', '2018-09-12 10:12:54', '3', '30'),
(5, '333', '2018-09-13', '2018-09-20', '2018-09-12 10:13:29', '2018-09-12 10:13:29', '3', '30'),
(6, '334', '2018-09-13', '2018-09-20', '2018-09-12 10:16:16', '2018-09-12 10:16:16', '3', '30'),
(7, '335', '2018-09-13', '2018-09-20', '2018-09-12 10:20:38', '2018-09-12 10:20:38', '3', '30'),
(8, '543', '2018-09-20', '2018-09-21', '2018-09-12 10:22:29', '2018-09-12 10:22:29', '9', '50'),
(9, '789', '2018-09-13', '2018-09-20', '2018-09-12 11:42:11', '2018-09-12 11:42:11', '9', '20'),
(10, '789', '2018-09-13', '2018-09-20', '2018-09-12 11:42:30', '2018-09-12 11:42:30', '9', '20');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `device` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `user_id`, `device`, `created_at`, `updated_at`) VALUES
(1, 1, '11111111111', '2018-09-10 08:06:50', '2018-09-10 08:06:50'),
(2, 1, '222222222222222', '2018-09-10 08:07:16', '2018-09-10 08:07:16'),
(3, 24, '222222222222222', '2018-09-30 16:52:55', '2018-09-30 16:52:55');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `times` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ratio` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_from` date NOT NULL,
  `end_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `times`, `code`, `ratio`, `start_from`, `end_at`, `created_at`, `updated_at`) VALUES
(1, '5', '1000', '5', '2018-09-04', '2018-09-06', '2018-09-03 12:10:18', '2018-09-03 12:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(10) UNSIGNED NOT NULL,
  `question` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(2, 'كيف يتم الدفع', '<p>نقدا عند الاستلام</p>', '2018-09-16 11:59:17', '2018-09-16 11:59:17');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) UNSIGNED NOT NULL,
  `itemable_id` int(10) UNSIGNED NOT NULL,
  `itemable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `basket_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `itemable_id`, `itemable_type`, `amount`, `type`, `basket_id`, `created_at`, `updated_at`) VALUES
(7, 1, 'App\\Product', '2', 'product', 1, '2018-09-10 16:02:28', '2018-09-10 16:02:28'),
(8, 2, 'App\\Offer', '5', 'offer', 1, '2018-09-10 16:02:28', '2018-09-10 16:02:28'),
(11, 1, 'App\\Product', '2', 'product', 2, '2018-09-11 09:21:03', '2018-09-11 09:21:03'),
(12, 2, 'App\\Offer', '5', 'offer', 2, '2018-09-11 09:21:03', '2018-09-11 09:21:03'),
(13, 1, 'App\\Product', '2', 'product', 3, '2018-09-11 15:07:32', '2018-09-11 15:07:32'),
(14, 2, 'App\\Offer', '5', 'offer', 3, '2018-09-11 15:07:32', '2018-09-11 15:07:32'),
(15, 1, 'App\\Product', '2', 'product', 4, '2018-09-16 11:08:40', '2018-09-16 11:08:40'),
(16, 2, 'App\\Offer', '5', 'offer', 4, '2018-09-16 11:08:40', '2018-09-16 11:08:40'),
(17, 1, 'App\\Product', '2', 'product', 5, '2018-09-17 12:57:25', '2018-09-17 12:57:25'),
(18, 2, 'App\\Offer', '5', 'offer', 5, '2018-09-17 12:57:25', '2018-09-17 12:57:25'),
(19, 1, 'App\\Product', '2', 'product', 6, '2018-09-17 12:58:47', '2018-09-17 12:58:47'),
(20, 2, 'App\\Offer', '4', 'offer', 6, '2018-09-17 12:58:47', '2018-09-17 12:58:47'),
(23, 1, 'App\\Product', '2', 'product', 7, '2018-10-01 09:47:39', '2018-10-01 09:47:39'),
(24, 2, 'App\\Offer', '5', 'offer', 7, '2018-10-01 09:47:39', '2018-10-01 09:47:39');

-- --------------------------------------------------------

--
-- Table structure for table `items_old`
--

CREATE TABLE `items_old` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `basket_id` int(10) UNSIGNED NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items_old`
--

INSERT INTO `items_old` (`id`, `product_id`, `basket_id`, `amount`, `created_at`, `updated_at`) VALUES
(3, 1, 2, '2', '2018-09-10 07:46:17', '2018-09-10 07:46:17'),
(4, 2, 2, '5', '2018-09-10 07:46:17', '2018-09-10 07:46:17'),
(5, 1, 3, '2', '2018-09-10 07:48:30', '2018-09-10 07:48:30'),
(6, 2, 3, '5', '2018-09-10 07:48:30', '2018-09-10 07:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `measurement_units`
--

CREATE TABLE `measurement_units` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `measurement_units`
--

INSERT INTO `measurement_units` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'لتر', 1, '2018-08-27 22:00:00', '2018-09-19 15:40:34'),
(2, 'كيلو', 1, '2018-08-27 22:00:00', '2018-08-27 22:00:00'),
(3, 'جراام', 1, '2018-08-29 07:13:45', '2018-09-16 12:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(22, '2014_10_12_000000_create_users_table', 1),
(23, '2014_10_12_100000_create_password_resets_table', 1),
(24, '2018_08_13_134259_create_cities_table', 1),
(25, '2018_08_13_134751_create_measurement_units_table', 1),
(26, '2018_08_13_134900_create_categories_table', 1),
(27, '2018_08_13_135229_create_products_table', 1),
(28, '2018_08_13_135645_create_offers_table', 1),
(29, '2018_08_13_140133_create_settings_table', 1),
(30, '2018_08_13_140452_create_supports_table', 1),
(31, '2018_08_13_141028_create_faqs_table', 1),
(32, '2018_08_13_141302_create_ads_table', 1),
(33, '2018_08_13_141438_create_coupons_table', 1),
(34, '2018_08_13_141957_create_items_table', 1),
(35, '2018_08_13_142453_create_baskets_table', 1),
(36, '2018_08_13_143756_create_orders_table', 1),
(37, '2018_08_13_144402_create_user_addresses_table', 1),
(38, '2018_08_13_145335_create_devices_table', 1),
(39, '2018_08_13_151856_create_bouncer_tables', 1),
(40, '2018_08_14_120318_add_image_to_users_table', 1),
(41, '2018_08_16_115309_create_user_logins_table', 1),
(42, '2018_08_26_095530_add_suspend_reason_to_users_table', 1),
(43, '2018_08_27_102827_add_api_token_to_users_table', 2),
(44, '2018_08_29_093953_add_category_id_to_offers_table', 3),
(45, '2018_08_29_094945_add_subcategory_id_to_offers_table', 3),
(46, '2018_09_02_131511_create_work_days_table', 4),
(47, '2018_09_03_131315_create_discounts_table', 5),
(48, '2018_09_10_102719_add_total_price_to_baskets_table', 6),
(50, '2018_09_10_111938_add_times_to_coupons_table', 7),
(51, '2018_09_10_112929_add_ratio_to_coupons_table', 7),
(52, '2018_09_10_164657_create_items_table', 8),
(53, '2018_09_10_174939_add_is_ordered_to_baskets_table', 9),
(54, '2018_09_12_115156_create_notifications_table', 10),
(55, '2018_09_12_131007_add_data_to_notifications_table', 11),
(56, '2018_09_30_120541_create_votes_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `user_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` int(10) UNSIGNED DEFAULT NULL,
  `target_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `user_ids`, `title`, `body`, `target_id`, `target_type`, `push_type`, `image`, `is_read`, `created_at`, `updated_at`, `data`) VALUES
(1, NULL, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 333 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, NULL, NULL, ''),
(2, NULL, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 333 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, NULL, NULL, ''),
(3, NULL, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 333 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, NULL, NULL, ''),
(4, 2, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 333 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, NULL, NULL, ''),
(5, 12, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 333 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, NULL, NULL, ''),
(6, 13, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 333 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, NULL, NULL, ''),
(7, 2, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 543 وجارى استخدامه فى الفترة من : 2018-09-20الى : 2018-09-21', NULL, 'coupon', 'global', NULL, 0, '2018-09-12 10:22:31', '2018-09-12 10:22:31', ''),
(8, 12, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 543 وجارى استخدامه فى الفترة من : 2018-09-20الى : 2018-09-21', NULL, 'coupon', 'global', NULL, 0, '2018-09-12 10:22:31', '2018-09-12 10:22:31', ''),
(9, 13, '[2,12,13]', 'كود خصم من مزرعتى', 'كود الخصم هو : 543 وجارى استخدامه فى الفترة من : 2018-09-20الى : 2018-09-21', NULL, 'coupon', 'global', NULL, 0, '2018-09-12 10:22:31', '2018-09-12 10:22:31', ''),
(10, 2, NULL, 'كود خصم من مزرعتى', 'كود الخصم هو : 789 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, '2018-09-12 11:42:31', '2018-09-12 11:42:31', ''),
(11, 12, NULL, 'كود خصم من مزرعتى', 'كود الخصم هو : 789 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, '2018-09-12 11:42:31', '2018-09-12 11:42:31', ''),
(12, 13, NULL, 'كود خصم من مزرعتى', 'كود الخصم هو : 789 وجارى استخدامه فى الفترة من : 2018-09-13الى : 2018-09-20', NULL, 'coupon', 'global', NULL, 0, '2018-09-12 11:42:31', '2018-09-12 11:42:31', ''),
(13, 2, NULL, 'كود خصم من مزرعتى', 'تم اضافة منتج جديد وهو : kkkk', NULL, 'product', 'global', NULL, 0, '2018-09-12 12:01:19', '2018-09-12 12:01:19', ''),
(14, 12, NULL, 'كود خصم من مزرعتى', 'تم اضافة منتج جديد وهو : kkkk', NULL, 'product', 'global', NULL, 0, '2018-09-12 12:01:19', '2018-09-12 12:01:19', ''),
(15, 13, NULL, 'كود خصم من مزرعتى', 'تم اضافة منتج جديد وهو : kkkk', NULL, 'product', 'global', NULL, 0, '2018-09-12 12:01:19', '2018-09-12 12:01:19', ''),
(16, 2, NULL, 'كود خصم من مزرعتى', 'تم اضافة منتج جديد وهو : منتججج', NULL, 'product', 'global', NULL, 0, '2018-09-16 12:55:15', '2018-09-16 12:55:15', ''),
(17, 12, NULL, 'كود خصم من مزرعتى', 'تم اضافة منتج جديد وهو : منتججج', NULL, 'product', 'global', NULL, 0, '2018-09-16 12:55:15', '2018-09-16 12:55:15', ''),
(18, 13, NULL, 'كود خصم من مزرعتى', 'تم اضافة منتج جديد وهو : منتججج', NULL, 'product', 'global', NULL, 0, '2018-09-16 12:55:15', '2018-09-16 12:55:15', ''),
(19, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 29, 'order', 'global', NULL, 0, '2018-09-17 13:40:57', '2018-09-17 13:40:57', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":29}'),
(20, 1, NULL, 'الرد على الطلب', 'تم رفض الطلب وسبب الرفض هو : aaaa', 28, 'order', 'global', NULL, 0, '2018-09-17 13:41:18', '2018-09-17 13:41:18', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0631\\u0641\\u0636 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u0628\\u0628 \\u0627\\u0644\\u0631\\u0641\\u0636 \\u0647\\u0648 : aaaa\",\"targetType\":\"order\",\"targetId\":28}'),
(21, 1, NULL, 'الرد على الطلب', 'تم رفض الطلب وسبب الرفض هو : aaaa', 28, 'order', 'global', NULL, 0, '2018-09-17 13:53:19', '2018-09-17 13:53:19', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0631\\u0641\\u0636 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u0628\\u0628 \\u0627\\u0644\\u0631\\u0641\\u0636 \\u0647\\u0648 : aaaa\",\"targetType\":\"order\",\"targetId\":28}'),
(22, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 27, 'order', 'global', NULL, 0, '2018-09-17 15:12:21', '2018-09-17 15:12:21', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":27}'),
(23, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 27, 'order', 'global', NULL, 0, '2018-09-17 15:21:53', '2018-09-17 15:21:53', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":27}'),
(24, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 26, 'order', 'global', NULL, 0, '2018-09-17 15:24:25', '2018-09-17 15:24:25', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":26}'),
(25, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 25, 'order', 'global', NULL, 0, '2018-09-17 15:25:06', '2018-09-17 15:25:06', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":25}'),
(26, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 23, 'order', 'global', NULL, 0, '2018-09-17 15:28:18', '2018-09-17 15:28:18', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":23}'),
(27, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 21, 'order', 'global', NULL, 0, '2018-09-17 15:29:34', '2018-09-17 15:29:34', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":21}'),
(28, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 18, 'order', 'global', NULL, 0, '2018-09-17 15:31:26', '2018-09-17 15:31:26', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":18}'),
(29, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 19, 'order', 'global', NULL, 0, '2018-09-17 15:32:39', '2018-09-17 15:32:39', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":19}'),
(30, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 24, 'order', 'global', NULL, 0, '2018-09-17 15:35:37', '2018-09-17 15:35:37', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":24}'),
(31, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 29, 'order', 'global', NULL, 0, '2018-09-17 15:39:35', '2018-09-17 15:39:35', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":29}'),
(33, 1, NULL, 'الرد على الطلب', 'تم قبول الطلب وسيتم تسلمها يوم ', 29, 'order', 'global', NULL, 0, '2018-09-17 15:51:32', '2018-09-17 15:51:32', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0642\\u0628\\u0648\\u0644 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u064a\\u062a\\u0645 \\u062a\\u0633\\u0644\\u0645\\u0647\\u0627 \\u064a\\u0648\\u0645 \",\"targetType\":\"order\",\"targetId\":29}'),
(34, 1, NULL, 'الرد على الطلب', 'تم رفض الطلب وسبب الرفض هو : شششششششششش', 24, 'order', 'global', NULL, 0, '2018-09-17 15:54:29', '2018-09-17 15:54:29', '{\"title\":\"\\u0627\\u0644\\u0631\\u062f \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0644\\u0628\",\"body\":\"\\u062a\\u0645 \\u0631\\u0641\\u0636 \\u0627\\u0644\\u0637\\u0644\\u0628 \\u0648\\u0633\\u0628\\u0628 \\u0627\\u0644\\u0631\\u0641\\u0636 \\u0647\\u0648 : \\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\",\"targetType\":\"order\",\"targetId\":24}'),
(36, 2, NULL, 'مزرعتى', 'شششششششششششش', NULL, 'notification', 'global', NULL, 0, '2018-09-24 12:40:00', '2018-09-24 12:40:00', '{\"title\":\"\\u0645\\u0632\\u0631\\u0639\\u062a\\u0649\",\"body\":\"\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\"}'),
(37, 13, NULL, 'مزرعتى', 'شششششششششششش', NULL, 'notification', 'global', NULL, 0, '2018-09-24 12:40:00', '2018-09-24 12:40:00', '{\"title\":\"\\u0645\\u0632\\u0631\\u0639\\u062a\\u0649\",\"body\":\"\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\\u0634\"}'),
(38, 2, NULL, 'مزرعتى', 'hhhhhhhhhhhhhhhhhhhhh', 2, 'notification', 'cities', NULL, 0, '2018-09-24 13:38:22', '2018-09-24 13:38:22', '{\"title\":\"\\u0645\\u0632\\u0631\\u0639\\u062a\\u0649\",\"body\":\"hhhhhhhhhhhhhhhhhhhhh\"}'),
(39, 13, NULL, 'مزرعتى', 'hhhhhhhhhhhhhhhhhhhhh', 2, 'notification', 'cities', NULL, 0, '2018-09-24 13:38:22', '2018-09-24 13:38:22', '{\"title\":\"\\u0645\\u0632\\u0631\\u0639\\u062a\\u0649\",\"body\":\"hhhhhhhhhhhhhhhhhhhhh\"}');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `measurement_id` int(10) UNSIGNED NOT NULL,
  `is_available` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `subcategory_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `name`, `image`, `description`, `price`, `amount`, `product_id`, `measurement_id`, `is_available`, `status`, `created_at`, `updated_at`, `category_id`, `subcategory_id`) VALUES
(1, 'big sale', '', 'offer description', '10', '5', 1, 1, 1, 0, '2018-09-04 05:53:18', '2018-09-26 09:40:35', 3, 5),
(2, 'qqqqq', '', 'wwwwwwwwwwwww', '12', '2', 1, 1, 0, 1, '2018-09-10 07:22:53', '2018-09-26 09:41:35', 3, 5),
(4, '', '', '', '10', '10', 7, 0, 1, 1, '2018-09-16 13:01:52', '2018-09-16 13:01:52', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `basket_id` int(10) UNSIGNED NOT NULL,
  `coupon_id` int(10) UNSIGNED DEFAULT NULL,
  `total_price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `address_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_deliverd_time` datetime DEFAULT NULL,
  `refuse_reason` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `basket_id`, `coupon_id`, `total_price`, `discount`, `order_date`, `order_time`, `address_id`, `status`, `user_deliverd_time`, `refuse_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '1000', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:01:02', '2018-09-10 10:01:02'),
(2, 1, 1, NULL, '1000', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:02:22', '2018-09-10 10:02:22'),
(3, 1, 1, NULL, '2000', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:04:57', '2018-09-10 10:04:57'),
(4, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:05:31', '2018-09-10 10:05:31'),
(5, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:07:00', '2018-09-10 10:07:00'),
(6, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:08:42', '2018-09-10 10:08:42'),
(7, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:10:06', '2018-09-10 10:10:06'),
(8, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:10:57', '2018-09-10 10:10:57'),
(9, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:15:38', '2018-09-10 10:15:38'),
(10, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:16:22', '2018-09-10 10:16:22'),
(11, 1, 1, 1, '4500', '450', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:16:44', '2018-09-10 10:16:44'),
(12, 1, 1, 1, '4500', '450', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:17:57', '2018-09-10 10:17:57'),
(13, 1, 1, 1, '4500', '450', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:25:12', '2018-09-10 10:25:12'),
(14, 1, 1, 1, '4500', '450', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:25:14', '2018-09-10 10:25:14'),
(15, 1, 1, 1, '4500', '450', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:25:17', '2018-09-10 10:25:17'),
(16, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 10:26:14', '2018-09-10 10:26:14'),
(17, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 1, 0, NULL, NULL, '2018-09-10 13:41:04', '2018-09-10 13:41:04'),
(18, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 7, 1, NULL, NULL, '2018-09-10 14:07:44', '2018-09-17 15:31:26'),
(19, 1, 1, NULL, '4500', '0', '2018-09-12', '11:00:00', 8, 1, NULL, NULL, '2018-09-10 14:14:21', '2018-09-17 15:32:39'),
(21, 1, 1, NULL, '0', '0', '2018-09-12', '11:00:00', 14, 2, NULL, 'ضضضضضضض', '2018-09-10 15:09:59', '2018-09-17 15:55:06'),
(23, 1, 1, NULL, '2060', '0', '2018-09-12', '11:00:00', 19, 1, NULL, NULL, '2018-09-10 15:17:32', '2018-09-17 15:28:18'),
(24, 1, 1, NULL, '2060', '0', '2018-09-12', '11:00:00', 20, 2, NULL, 'شششششششششش', '2018-09-10 16:02:56', '2018-09-17 15:54:29'),
(25, 1, 2, NULL, '2060', '0', '2018-09-12', '11:00:00', 21, 1, NULL, NULL, '2018-09-11 09:21:14', '2018-09-17 15:25:06'),
(26, 2, 3, NULL, '2110', '0', '2018-09-12', '11:00:00', 22, 1, NULL, NULL, '2018-09-11 15:07:38', '2018-09-17 15:24:25'),
(27, 2, 4, NULL, '2110', '0', '2018-09-17', '13:00:00', 24, 1, NULL, NULL, '2018-09-16 11:08:45', '2018-09-17 15:12:21'),
(28, 1, 5, NULL, '2110', '0', '2018-09-19', '13:00:00', 29, 1, NULL, 'aaaa', '2018-09-17 12:57:57', '2018-09-17 15:41:20'),
(29, 1, 6, 9, '2098', '409.6', '2018-09-19', '13:00:00', 30, 3, NULL, NULL, '2018-09-17 13:09:23', '2018-09-17 15:51:32'),
(30, 1, 7, NULL, '2110', '0', '2018-10-20', '13:00:00', 64, 0, NULL, NULL, '2018-10-01 10:15:24', '2018-10-01 10:15:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `ability_id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL,
  `entity_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `forbidden` tinyint(1) NOT NULL DEFAULT '0',
  `scope` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`ability_id`, `entity_id`, `entity_type`, `forbidden`, `scope`) VALUES
(1, 1, 'roles', 0, NULL),
(2, 1, 'App\\User', 0, NULL),
(2, 2, 'roles', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `subcategory_id` int(10) UNSIGNED NOT NULL,
  `measurement_id` int(10) UNSIGNED NOT NULL,
  `sales_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_bestseller` tinyint(1) NOT NULL,
  `is_available` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `category_id`, `subcategory_id`, `measurement_id`, `sales_no`, `is_bestseller`, `is_available`, `status`, `created_at`, `updated_at`) VALUES
(1, 'منتج1', 'وصف منتج 1', '1535464282.s3uDY9C8Iqh1UCLlb3kcScreenshot (3).png', '1000', 3, 5, 1, '0', 0, 1, 0, '2018-08-28 11:51:22', '2018-09-12 13:20:17'),
(2, 'منتج1', 'وصف منتج 1', '1535464282.s3uDY9C8Iqh1UCLlb3kcScreenshot (3).png', '500', 3, 5, 1, '0', 0, 1, 1, '2018-08-28 11:51:22', '2018-08-28 11:53:48'),
(3, 'kkkk', 'hhhhhhhhhh', '1536749942.mNRlDDFoJCFc6BIIK3Shb06b7885-2afa-446c-bb7e-f44f897b3b60.jpeg', '100', 3, 5, 2, '0', 0, 1, 1, '2018-09-12 11:59:02', '2018-09-12 11:59:02'),
(4, 'kkkk', 'hhhhhhhhhh', '1536749990.6IDyQDSTgRa7oZwCXYsCb06b7885-2afa-446c-bb7e-f44f897b3b60.jpeg', '100', 3, 5, 2, '0', 0, 1, 1, '2018-09-12 11:59:50', '2018-09-12 11:59:50'),
(5, 'kkkk', 'hhhhhhhhhh', '1536750028.XFZhDzNNN8DTani0yKhhb06b7885-2afa-446c-bb7e-f44f897b3b60.jpeg', '100', 3, 5, 2, '0', 0, 1, 1, '2018-09-12 12:00:28', '2018-09-12 12:00:28'),
(6, 'kkkk', 'hhhhhhhhhh', '1536750078.wiCwl383U5gg2tB1XAAlb06b7885-2afa-446c-bb7e-f44f897b3b60.jpeg', '100', 3, 5, 2, '0', 0, 1, 1, '2018-09-12 12:01:18', '2018-09-12 12:01:18'),
(7, 'منتججج', 'وصف منتججج', '1537098973.EiB7fqUJKootFAwGIXu7images (1).jpg', '50', 3, 4, 2, '0', 0, 0, 1, '2018-09-16 12:55:13', '2018-09-16 12:56:14');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(10) UNSIGNED DEFAULT NULL,
  `scope` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `title`, `level`, `scope`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'Owner', NULL, NULL, '2018-08-26 08:23:08', '2018-08-26 08:23:08'),
(2, 'ادارة المستخدمين', 'ادارة المستخدمين', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11'),
(3, 'ادارة العملاء', 'ادارة العملاء', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11'),
(4, 'ادارة المنتجات', 'ادارة المنتجات', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11'),
(5, 'ادارة الطلبات', 'ادارة الطلبات', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11'),
(6, 'ادارة الاشعارات', 'ادارة الاشعارات', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11'),
(7, 'ادارة الاعلانات', 'ادارة الاعلانات', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11'),
(8, 'ادارة التقارير', 'ادارة التقارير', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11'),
(9, 'ادارة الاعدادات', 'ادارة الاعدادات', NULL, NULL, '2018-08-27 08:22:11', '2018-08-27 08:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `body`, `created_at`, `updated_at`) VALUES
(1, 'email', 'farm@farm.com', '2018-09-02 06:51:56', '2018-09-02 06:51:56'),
(5, '3hot_no3', '051234563', '2018-09-02 06:51:56', '2018-09-02 06:51:56'),
(22, 'hot_no6', '123555605555', '2018-09-02 10:26:08', '2018-09-02 10:36:24'),
(27, 'hot_no2', '0111111111111', '2018-09-03 11:03:34', '2018-09-03 11:03:34'),
(28, 'hot_no4', '0333333333', '2018-09-03 11:03:34', '2018-09-03 11:03:34'),
(29, 'hot_no5', '0555555555', '2018-09-03 11:03:34', '2018-09-03 11:03:34'),
(30, 'terms', 'terms terms', '2018-09-04 05:56:36', '2018-09-04 05:56:36'),
(31, 'about_app_desc', 'about app', '2018-09-04 05:56:51', '2018-09-04 05:56:51'),
(32, 'support_phone', '0542136999', '2018-09-04 05:57:15', '2018-09-04 05:57:15'),
(33, 'delivery', '50', '2018-09-11 15:01:46', '2018-09-11 15:01:46'),
(34, 'fb', 'fb.com', '2018-09-11 16:12:09', '2018-09-11 16:12:09'),
(35, 'twitter', 'twitter.com', '2018-09-11 16:12:09', '2018-09-11 16:12:09'),
(36, 'google', 'googleplus.com', '2018-09-11 16:12:09', '2018-09-11 16:12:09'),
(37, 'instagram', 'instagram.com', '2018-09-11 16:12:09', '2018-09-11 16:12:09');

-- --------------------------------------------------------

--
-- Table structure for table `supports`
--

CREATE TABLE `supports` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` enum('complain','suggest','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `action_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_suspend` tinyint(1) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspend_reason` longtext COLLATE utf8mb4_unicode_ci,
  `api_token` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `city_id`, `action_code`, `is_active`, `is_suspend`, `is_admin`, `is_new`, `remember_token`, `created_at`, `updated_at`, `image`, `suspend_reason`, `api_token`) VALUES
(1, 'saned', 'saned@saned.sa', '0514796358', '$2y$10$o0gsHIi/NmllQjabz99VPuegeoe2woA46vZTRyeKL1mUCFccNeAfW', 1, '1234', 1, 0, 1, 0, 'EuOTahW3rFutXwjl6b4AloNXyu35bBE55PhuGfWXnVOuRj5uTb7aRwOrhBVc', '2018-08-25 22:00:00', '2018-09-16 13:12:29', '1537099948.vJIwvaC1DXt82abEeNx1img4.jpg', '', '4meXTP5jbm437xpjC3DcjDO71pV09Hs76Y19Wyf2Tu4DTPJsdvfdIfVmqW4p'),
(2, 'client2', 'client2@saned.sa', '0512365478', '$2y$10$NUV6RzjQE241JogTy1/BN.9.5.iUc8cebbeREprHD0xcyPqsKFct6', 2, '1069', 1, 0, 0, 0, NULL, '2018-08-25 22:00:00', '2018-09-30 16:29:31', NULL, 'm', 't9XHU5Bc3YqdpbUwR6jLmlhvSvOMezvOv6IkYWkxr39JaX90kwMQ5nn96Y49'),
(3, 'admin', 'admin@saned.sa', '0514896358', '$2y$10$o0gsHIi/NmllQjabz99VPuegeoe2woA46vZTRyeKL1mUCFccNeAfW', 1, '1234', 1, 1, 1, 0, 'mRWwBlb66120gUkEacqllbwhUbPJSuEse9hNyblCco11bs0SQWx0VZxaIQKs', '2018-08-25 22:00:00', '2018-09-19 13:41:50', '1535279087.iSYPOrNNRNoantyADCmC1514455646.jpg', 'xxxxxxxxx', NULL),
(12, 'samia', NULL, '0542319871', '$2y$10$c0aIYeK8HmOWeG9pCnFqGu0BcPLCuBleYl1mwm2ASc0UMdsP0ew/y', 1, '8885', 0, 1, 0, 0, NULL, '2018-09-04 09:50:04', '2018-09-20 09:22:44', NULL, 'ffffffff', '8PqqMTmdnk5uMK057htgWm6BG5czKo7jUBR0kWkk1IaFlUgYsnJpFBaoFaq7'),
(13, 'inas', NULL, '0542319888', '$2y$10$GiYXDvL5dsQv87xsvOSePui1P2M9pXwf8lNNzI3ZuBT8XCSXYfOFW', 2, '6108', 0, 1, 0, 0, NULL, '2018-09-05 08:04:50', '2018-09-20 11:03:38', NULL, 'aaaaaaaaaaaaabc', '3RBhB56yiYTXQuzdazbJVkJni75ljfHqIS6jCz8ErvOMTY6unDdWt7CRM78O'),
(14, 'admin222', 'admin22@saned.sa', '0514896858', '$2y$10$o0gsHIi/NmllQjabz99VPuegeoe2woA46vZTRyeKL1mUCFccNeAfW', 1, '1234', 1, 0, 1, 0, 'mRWwBlb66120gUkEacqllbwhUbPJSuEse9hNyblCco11bs0SQWx0VZxaIQKs', '2018-08-25 22:00:00', '2018-09-19 13:43:13', '1535279087.iSYPOrNNRNoantyADCmC1514455646.jpg', '', NULL),
(15, 'inas', NULL, '0542319881', '$2y$10$rRgjYvMd/kaosX2k6ag3e.sM7QctcIQ9CANP81u3yHWf/umHrV/wi', NULL, '7621', 0, 0, 0, 0, NULL, '2018-09-30 15:16:49', '2018-09-30 15:16:49', NULL, NULL, 'UwyQCr5LGQCCfRT0JmhOY5DO5xoGqHWE9pgaCiff6OBiRrspUFcnSlJzUp5A'),
(16, 'inas', NULL, '0542319887', '$2y$10$5iWnOuT0t.DKHaBrMsZRV.AgyF16AvSd6pW.VON0jqe1Br0jEFEpO', NULL, '8490', 0, 0, 0, 0, NULL, '2018-09-30 15:17:25', '2018-09-30 15:17:25', NULL, NULL, 'lQRonRwolFqnhbg23TnsWeiz2fcbKU46CtJlnxqHq7uI60G8D2i9NmGVWeps'),
(17, 'inas', NULL, '0542319889', '$2y$10$8vSBzEjO08c1NuvDHhtid.IQEDU606MQKGsPiyUMMpIUXt45QGtf2', NULL, '7035', 0, 0, 0, 0, NULL, '2018-09-30 15:18:15', '2018-09-30 15:18:15', NULL, NULL, '265QJyfNVxucXXSUxjxRtWGS4qQhJnrUBYW9Jkdv857Qhq6Ahcsps51B9kc5'),
(18, 'inas', '', '0542319880', '$2y$10$Kdn4N9HL/K2dTs5gZU0RTecqR/T97RyyViJ0h2OJvi4RcHzro7rFa', NULL, '5605', 0, 0, 0, 0, NULL, '2018-09-30 15:22:18', '2018-09-30 15:22:18', NULL, NULL, 'wNWt7aB8wut6uTnqBoZP2Ll61F06RjzKIQhLtpv2DbO2AhklMWSXIK34IfmJ'),
(20, 'inas', NULL, '0542319870', '$2y$10$EaxHtHTCQosYwQt1FE4DXOxwd/kqQfW/tFFexZJjnI9lcOCEztWnq', 1, '8570', 0, 0, 0, 0, NULL, '2018-09-30 15:28:50', '2018-09-30 15:28:50', NULL, NULL, 'bHhYJO3x9m1q71wna7rA96AqYJokiEuvuf5oDPGWaTDoH6PqPvG95E7QcgXK'),
(21, 'inas', NULL, '0542339870', '$2y$10$cQLjG/mOzITmMW1pA0NAou1QygJhaFVvyJIDgmqtuK/j4VZTOu5Me', 1, '3599', 0, 0, 0, 0, NULL, '2018-09-30 15:58:18', '2018-09-30 15:58:18', NULL, NULL, '9uUrcR00Y8xX7xrVLEPn8q833lghcTq2eJ4gETuJkPBjQNYEeby6ZXrpa6ji'),
(22, 'inas', NULL, '0572339870', '$2y$10$sAm7nD7XTO/JkhkDRCVER.5huWZ6QPuhSaS3Fm4UzelaDn.iKj3nO', 1, '3020', 0, 0, 0, 0, NULL, '2018-09-30 16:01:04', '2018-09-30 16:01:04', NULL, NULL, '6mOy9GXvSJ6ldTFHelH9vzUYo4RFzZxzEU6A8pmKhO98BhqyTIwk1UiadTV4'),
(23, 'inas', NULL, '0552339870', '$2y$10$L7/1PAtzHsyX8nZqSGfNzOwLJyu9N9he8kud0PQE24f6rNLeBOSBq', 1, '4064', 0, 0, 0, 0, NULL, '2018-09-30 16:01:50', '2018-09-30 16:01:50', NULL, NULL, '4pIOpyS2xdm6EzXbZRYr2XQ8pFkqoc26af9aWh3orkjJP1G2HqKrwqEsavxU'),
(24, 'samia', NULL, '0543020202', '$2y$10$NXPnvqWzO18pL1gssdPn3efZLfenV/Rx0bh06wTxa4/MKJW3QVFau', 1, '6168', 0, 0, 0, 0, NULL, '2018-09-30 16:40:39', '2018-10-01 07:30:57', NULL, NULL, 'V31W8pelCMRAr644bQnPC3FzGt1wop5jzJeEx2TqEtRpScIUE9X7BZIfu1cr');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `lat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lng` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `lat`, `lng`, `address`, `city`, `created_at`, `updated_at`) VALUES
(6, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:07:01', '2018-09-10 14:07:01'),
(7, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:07:44', '2018-09-10 14:07:44'),
(8, 2, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:14:20', '2018-09-10 14:14:20'),
(9, 2, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:14:30', '2018-09-10 14:14:30'),
(10, 2, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:14:32', '2018-09-10 14:14:32'),
(11, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:14:50', '2018-09-10 14:14:50'),
(12, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:15:03', '2018-09-10 14:15:03'),
(13, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 14:15:25', '2018-09-10 14:15:25'),
(14, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 15:09:58', '2018-09-10 15:09:58'),
(15, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 15:11:34', '2018-09-10 15:11:34'),
(16, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 15:11:58', '2018-09-10 15:11:58'),
(17, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 15:14:27', '2018-09-10 15:14:27'),
(18, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 15:14:35', '2018-09-10 15:14:35'),
(19, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 15:17:32', '2018-09-10 15:17:32'),
(20, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-10 16:02:56', '2018-09-10 16:02:56'),
(21, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-11 09:21:14', '2018-09-11 09:21:14'),
(22, 1, '10.33333333', '21.22222222', 'ryadh', NULL, '2018-09-11 15:07:38', '2018-09-11 15:07:38'),
(23, 1, '10.33333333', '21.22222222', 'ryadh-sa', 'الرياض', '2018-09-16 11:08:09', '2018-09-16 11:08:09'),
(24, 1, '10.33333333', '21.22222222', 'ryadh-sa', 'الرياض', '2018-09-16 11:08:45', '2018-09-16 11:08:45'),
(25, 12, '21.33333333333333', '12.3333333', 'ryad', 'الرياض', '2018-09-16 11:10:32', '2018-09-16 11:10:32'),
(26, 12, '21.33333333333333', '12.3333333', 'ryad', 'الرياض', '2018-09-16 11:10:32', '2018-09-16 11:10:32'),
(27, 12, '41.33333333333333', '16.3333333', 'dddd', 'الرياض', '2018-09-16 11:10:32', '2018-09-16 11:10:32'),
(28, 1, '10.33333333', '21.22222222', 'ryadh-sa', 'الرياض', '2018-09-17 12:57:40', '2018-09-17 12:57:40'),
(29, 1, '10.33333333', '21.22222222', 'ryadh-sa', 'الرياض', '2018-09-17 12:57:57', '2018-09-17 12:57:57'),
(30, 1, '10.33333333', '21.22222222', 'ryadh-sa', 'الرياض', '2018-09-17 13:09:23', '2018-09-17 13:09:23'),
(31, 17, '', '', 'data', '1', '2018-09-30 15:18:15', '2018-09-30 15:18:15'),
(32, 18, '', '', 'data', '1', '2018-09-30 15:22:18', '2018-09-30 15:22:18'),
(33, 20, '', '', 'data', '1', '2018-09-30 15:28:51', '2018-09-30 15:28:51'),
(34, 21, '', '', 'data', '1', '2018-09-30 15:58:18', '2018-09-30 15:58:18'),
(35, 22, '', '', 'data', '1', '2018-09-30 16:01:04', '2018-09-30 16:01:04'),
(36, 23, '', '', 'data', '1', '2018-09-30 16:01:50', '2018-09-30 16:01:50'),
(59, 24, '21.33333333333333', '12.3333333', 'ryad', '2', '2018-10-01 07:30:58', '2018-10-01 07:30:58'),
(60, 24, '21.33333333333333', '12.3333333', 'ryad', '2', '2018-10-01 07:30:58', '2018-10-01 07:30:58'),
(61, 24, '41.33333333333333', '16.3333333', 'dddd', '2', '2018-10-01 07:30:58', '2018-10-01 07:30:58'),
(62, 1, '10.33333333', '21.22222222', 'ryadh-sa', '1', '2018-10-01 10:14:41', '2018-10-01 10:14:41'),
(63, 1, '10.33333333', '21.22222222', 'ryadh-sa', '1', '2018-10-01 10:15:06', '2018-10-01 10:15:06'),
(64, 1, '10.33333333', '21.22222222', 'ryadh-sa', '2', '2018-10-01 10:15:24', '2018-10-01 10:15:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `logins_count` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_logins`
--

INSERT INTO `user_logins` (`id`, `user_id`, `logins_count`, `created_at`, `updated_at`) VALUES
(1, 1, '55', '2018-08-26 08:04:49', '2018-10-01 13:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(10) UNSIGNED NOT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `city_id`, `user_id`, `city`, `created_at`, `updated_at`) VALUES
(3, NULL, 1, 'مكه', '2018-09-30 11:39:54', '2018-09-30 11:39:54'),
(4, NULL, 1, 'مكة', '2018-09-30 11:40:48', '2018-09-30 11:40:48'),
(5, NULL, 2, 'مكة', '2018-09-30 11:40:48', '2018-09-30 11:40:48'),
(6, NULL, 3, 'مكة', '2018-09-30 11:40:48', '2018-09-30 11:40:48'),
(7, NULL, 2, 'مكه', '2018-09-30 11:39:54', '2018-09-30 11:39:54'),
(8, 1, 1, '', '2018-10-01 08:07:14', '2018-10-01 08:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `work_days`
--

CREATE TABLE `work_days` (
  `id` int(10) UNSIGNED NOT NULL,
  `day` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` time NOT NULL,
  `to` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_days`
--

INSERT INTO `work_days` (`id`, `day`, `from`, `to`, `created_at`, `updated_at`) VALUES
(41, 'Sat', '09:00:00', '17:00:00', '2018-09-10 09:56:17', '2018-09-10 09:56:17'),
(42, 'Sun', '09:00:00', '17:00:00', '2018-09-10 09:56:17', '2018-09-10 09:56:17'),
(43, 'Mon', '09:00:00', '19:00:00', '2018-09-10 09:56:18', '2018-09-10 09:56:18'),
(44, 'Tue', '09:59:00', '22:00:00', '2018-09-10 09:56:18', '2018-09-10 09:56:18'),
(45, 'Wed', '09:00:00', '17:59:00', '2018-09-10 09:56:18', '2018-09-10 09:56:18'),
(46, 'Thu', '09:00:00', '17:59:00', '2018-09-10 09:56:18', '2018-09-10 09:56:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abilities`
--
ALTER TABLE `abilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `abilities_scope_index` (`scope`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assigned_roles`
--
ALTER TABLE `assigned_roles`
  ADD KEY `assigned_roles_entity_index` (`entity_id`,`entity_type`,`scope`),
  ADD KEY `assigned_roles_role_id_index` (`role_id`),
  ADD KEY `assigned_roles_scope_index` (`scope`);

--
-- Indexes for table `baskets`
--
ALTER TABLE `baskets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_old`
--
ALTER TABLE `items_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measurement_units`
--
ALTER TABLE `measurement_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD KEY `permissions_entity_index` (`entity_id`,`entity_type`,`scope`),
  ADD KEY `permissions_ability_id_index` (`ability_id`),
  ADD KEY `permissions_scope_index` (`scope`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`,`scope`),
  ADD KEY `roles_scope_index` (`scope`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supports`
--
ALTER TABLE `supports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_days`
--
ALTER TABLE `work_days`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abilities`
--
ALTER TABLE `abilities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `baskets`
--
ALTER TABLE `baskets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `items_old`
--
ALTER TABLE `items_old`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `measurement_units`
--
ALTER TABLE `measurement_units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `supports`
--
ALTER TABLE `supports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `work_days`
--
ALTER TABLE `work_days`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_roles`
--
ALTER TABLE `assigned_roles`
  ADD CONSTRAINT `assigned_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_ability_id_foreign` FOREIGN KEY (`ability_id`) REFERENCES `abilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
