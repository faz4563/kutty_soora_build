-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 05, 2026 at 09:00 PM
-- Server version: 11.4.12-MariaDB-cll-lve-log
-- PHP Version: 8.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kuttizgf_kuttysoora_seafood`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(581, 28, 71, 500.00, '2026-05-07 10:36:58', '2026-05-07 10:36:58'),
(582, 30, 39, 500.00, '2026-05-07 10:37:29', '2026-05-07 10:37:29'),
(583, 30, 42, 500.00, '2026-05-07 10:38:44', '2026-05-07 10:38:44'),
(584, 35, 65, 500.00, '2026-05-07 10:47:04', '2026-05-07 10:47:04'),
(585, 37, 21, 500.00, '2026-05-07 10:48:38', '2026-05-07 10:48:38'),
(586, 44, 45, 1000.00, '2026-05-07 11:05:46', '2026-05-07 11:05:46'),
(587, 54, 24, 1000.00, '2026-05-07 11:55:33', '2026-05-07 11:55:42'),
(588, 63, 41, 500.00, '2026-05-07 14:37:41', '2026-05-07 14:37:41'),
(589, 64, 41, 500.00, '2026-05-07 15:04:10', '2026-05-07 15:04:10'),
(595, 69, 42, 500.00, '2026-05-07 17:31:11', '2026-05-07 17:31:11'),
(596, 73, 41, 500.00, '2026-05-07 22:18:16', '2026-05-07 22:22:12'),
(597, 73, 21, 500.00, '2026-05-07 22:22:03', '2026-05-07 22:22:03'),
(598, 75, 35, 500.00, '2026-05-08 02:45:54', '2026-05-08 02:45:54'),
(599, 80, 36, 1000.00, '2026-05-08 07:44:00', '2026-05-08 07:47:51'),
(600, 81, 20, 1000.00, '2026-05-08 11:04:20', '2026-05-08 11:04:20'),
(601, 83, 43, 1000.00, '2026-05-08 13:52:12', '2026-05-08 13:52:12'),
(602, 85, 34, 1000.00, '2026-05-08 15:37:22', '2026-05-08 15:37:31'),
(603, 89, 3, 2000.00, '2026-05-08 19:58:30', '2026-05-08 19:58:38'),
(604, 91, 73, 250.00, '2026-05-09 06:39:19', '2026-05-09 06:39:19'),
(621, 102, 44, 1000.00, '2026-05-16 16:29:11', '2026-05-16 16:31:46'),
(622, 102, 41, 1000.00, '2026-05-16 16:29:25', '2026-05-16 16:31:32'),
(623, 62, 41, 500.00, '2026-05-17 05:06:11', '2026-05-17 05:06:11'),
(624, 62, 65, 500.00, '2026-05-17 05:08:13', '2026-05-17 05:08:13'),
(627, 103, 62, 2000.00, '2026-05-19 13:47:33', '2026-05-19 13:47:33'),
(629, 105, 35, 1000.00, '2026-05-21 15:36:43', '2026-05-21 15:44:29'),
(631, 94, 21, 500.00, '2026-05-23 05:56:41', '2026-05-23 05:56:41'),
(632, 94, 62, 1000.00, '2026-05-23 05:56:58', '2026-05-23 05:56:58'),
(633, 107, 29, 1000.00, '2026-05-23 12:51:11', '2026-05-23 12:51:11'),
(644, 21, 32, 1000.00, '2026-05-31 18:09:52', '2026-05-31 18:09:52'),
(645, 21, 28, 500.00, '2026-05-31 18:10:15', '2026-05-31 18:10:15'),
(658, 118, 41, 500.00, '2026-06-21 02:18:55', '2026-06-21 02:18:55'),
(659, 36, 62, 1000.00, '2026-06-30 14:33:55', '2026-06-30 14:33:55'),
(674, 133, 42, 500.00, '2026-07-18 02:26:46', '2026-07-18 02:26:46'),
(675, 133, 39, 500.00, '2026-07-18 02:30:51', '2026-07-18 02:30:51'),
(676, 139, 36, 1000.00, '2026-07-25 12:54:03', '2026-07-25 12:54:03'),
(677, 142, 39, 1000.00, '2026-07-25 14:13:13', '2026-07-25 14:13:13'),
(678, 143, 42, 1000.00, '2026-07-25 14:16:12', '2026-07-25 14:16:12'),
(679, 143, 62, 1000.00, '2026-07-25 14:17:54', '2026-07-25 14:17:54'),
(680, 149, 23, 500.00, '2026-07-26 03:36:58', '2026-07-26 03:36:58'),
(683, 152, 39, 500.00, '2026-07-26 06:39:03', '2026-07-26 06:39:03'),
(684, 152, 28, 500.00, '2026-07-26 06:40:07', '2026-07-26 06:40:07'),
(685, 152, 37, 1000.00, '2026-07-26 06:41:27', '2026-07-26 06:41:27'),
(686, 154, 65, 500.00, '2026-07-26 09:12:04', '2026-07-26 09:12:04'),
(687, 154, 62, 1000.00, '2026-07-26 09:12:48', '2026-07-26 09:12:48'),
(692, 163, 37, 1000.00, '2026-07-29 06:09:13', '2026-07-29 06:09:13'),
(693, 163, 44, 1000.00, '2026-07-29 06:09:53', '2026-07-29 06:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `product_count` int(11) DEFAULT 0,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_url`, `parent_id`, `sort_order`, `is_active`, `product_count`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(1, 'Fresh Fish', 'fresh-fish', 'Premium fresh fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(2, 'Seafood', 'seafood', 'Fresh seafood including prawns, crabs, and shellfish', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(3, 'Dry Fish', 'dry-fish', 'Traditional dry fish varieties', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(4, 'Marinated', 'marinated', 'Ready-to-cook marinated seafood', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22'),
(5, 'Frozen', 'frozen', 'Frozen seafood products', NULL, NULL, 0, 1, 0, NULL, NULL, '2025-10-24 02:52:22', '2025-10-24 02:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `failed_login_attempts`
--

CREATE TABLE `failed_login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `attempt_time` datetime NOT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image_uploads`
--

CREATE TABLE `image_uploads` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_url` varchar(500) NOT NULL,
  `file_size` int(11) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `mime_type` varchar(100) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_blacklist`
--

CREATE TABLE `ip_blacklist` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `reason` text DEFAULT NULL,
  `blocked_at` datetime NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_permanent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_amount` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `customer_email` varchar(150) DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `delivery_city` varchar(100) DEFAULT NULL,
  `delivery_state` varchar(100) DEFAULT NULL,
  `delivery_pincode` varchar(10) DEFAULT NULL,
  `delivery_landmark` varchar(200) DEFAULT NULL,
  `payment_method` enum('cash_on_delivery','online','card','upi','wallet') DEFAULT 'cash_on_delivery',
  `payment_status` enum('pending','paid','failed','refunded','partially_refunded') DEFAULT 'pending',
  `payment_reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `actual_delivery` timestamp NULL DEFAULT NULL,
  `cancelled_reason` varchar(300) DEFAULT NULL,
  `cancelled_by` enum('customer','admin','system') DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `subtotal`, `tax_amount`, `shipping_amount`, `discount_amount`, `status`, `customer_name`, `customer_phone`, `customer_email`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_landmark`, `payment_method`, `payment_status`, `payment_reference`, `notes`, `admin_notes`, `estimated_delivery`, `actual_delivery`, `cancelled_reason`, `cancelled_by`, `cancelled_at`, `created_at`, `updated_at`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`) VALUES
(134, 2, 'ORD-20260414-81FC21', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:26:48', '2026-04-14 04:26:48', NULL, NULL, NULL),
(135, 2, 'ORD-20260414-3C4ECC', 3400.00, 3400.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:34:43', '2026-04-14 04:34:43', NULL, NULL, NULL),
(136, 2, 'ORD-20260414-C1EE09', 2550.00, 2550.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:39:08', '2026-04-14 04:39:08', NULL, NULL, NULL),
(137, 2, 'ORD-20260414-A4AA91', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:40:58', '2026-04-14 04:40:58', NULL, NULL, NULL),
(138, 2, 'ORD-20260414-7BEE25', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:48:07', '2026-04-14 04:48:07', NULL, NULL, NULL),
(139, 2, 'ORD-20260414-6691F0', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:48:54', '2026-04-14 04:48:54', NULL, NULL, NULL),
(140, 2, 'ORD-20260414-515301', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:55:49', '2026-04-14 04:55:49', NULL, NULL, NULL),
(141, 2, 'ORD-20260414-4AB294', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 04:56:52', '2026-04-14 04:56:52', NULL, NULL, NULL),
(142, 2, 'ORD-20260414-6F41AF', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:18:31', '2026-04-14 05:18:31', NULL, NULL, NULL),
(143, 2, 'ORD-20260414-F2AF25', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:18:55', '2026-04-14 05:18:55', NULL, NULL, NULL),
(144, 2, 'ORD-20260414-A958F8', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:21:46', '2026-04-14 05:21:46', NULL, NULL, NULL),
(145, 2, 'ORD-20260414-A75173', 3350.00, 3350.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:24:26', '2026-04-14 05:24:26', NULL, NULL, NULL),
(146, 2, 'ORD-20260414-ECA735', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:34:06', '2026-04-14 05:34:06', NULL, NULL, NULL),
(147, 2, 'ORD-20260414-4C7A05', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:36:20', '2026-04-14 05:36:20', NULL, NULL, NULL),
(148, 2, 'ORD-20260414-ABE52A', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:37:30', '2026-04-14 05:37:30', NULL, NULL, NULL),
(149, 2, 'ORD-20260414-0603BA', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:40:16', '2026-04-14 05:40:16', NULL, NULL, NULL),
(150, 2, 'ORD-20260414-A51E68', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:42:34', '2026-04-14 05:42:34', NULL, NULL, NULL),
(151, 2, 'ORD-20260414-8CF200', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:48:08', '2026-04-14 05:48:08', NULL, NULL, NULL),
(152, 2, 'ORD-20260414-2BAEEA', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:50:10', '2026-04-14 05:50:10', NULL, NULL, NULL),
(153, 2, 'ORD-20260414-80ADBE', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'FAZIL', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:53:28', '2026-04-14 05:53:28', NULL, NULL, NULL),
(154, 2, 'ORD-20260414-804DDE', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'FAZIL', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:56:08', '2026-04-14 05:56:27', 'order_SdGUt17iCIhvfg', NULL, NULL),
(155, 2, 'ORD-20260414-871400', 2550.00, 2550.00, 0.00, 0.00, 0.00, 'pending', 'FAZIL', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 05:59:36', '2026-04-14 05:59:55', 'order_SdGYXwfWOjREOh', NULL, NULL),
(156, 2, 'ORD-20260414-032123', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:06:56', '2026-04-14 06:06:56', NULL, NULL, NULL),
(157, 2, 'ORD-20260414-AC7B42', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:07:38', '2026-04-14 06:07:38', NULL, NULL, NULL),
(158, 15, 'ORD-20260414-45D394', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'kuttysoora', '9962463925', NULL, '43963, jredfd, rofff, Chennai , 60001', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:08:52', '2026-04-14 06:08:52', NULL, NULL, NULL),
(159, 2, 'ORD-20260414-7B1DDB', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:09:59', '2026-04-14 06:09:59', NULL, NULL, NULL),
(160, 2, 'ORD-20260414-8D99E7', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:10:48', '2026-04-14 06:10:48', NULL, NULL, NULL),
(161, 2, 'ORD-20260414-6A8637', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:12:38', '2026-04-14 06:12:38', NULL, NULL, NULL),
(162, 2, 'ORD-20260414-4032B3', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:17:56', '2026-04-14 06:17:56', NULL, NULL, NULL),
(163, 2, 'ORD-20260414-D9B5AF', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:19:41', '2026-04-14 06:19:41', NULL, NULL, NULL),
(164, 2, 'ORD-20260414-F04B7B', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:20:31', '2026-04-14 06:20:31', NULL, NULL, NULL),
(165, 2, 'ORD-20260414-15CA7A', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:36:17', '2026-04-14 06:36:17', NULL, NULL, NULL),
(166, 2, 'ORD-20260414-5E2ADB', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:38:13', '2026-04-14 06:38:13', NULL, NULL, NULL),
(167, 2, 'ORD-20260414-5745F8', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:43:33', '2026-04-14 06:43:33', NULL, NULL, NULL),
(168, 2, 'ORD-20260414-7A0EEF', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 06:48:23', '2026-04-14 06:48:23', NULL, NULL, NULL),
(169, 2, 'ORD-20260414-ECFA1E', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:04:46', '2026-04-14 07:04:46', NULL, NULL, NULL),
(170, 2, 'ORD-20260414-AC2997', 5100.00, 5100.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:05:14', '2026-04-14 07:05:14', NULL, NULL, NULL),
(171, 2, 'ORD-20260414-F09C22', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:05:51', '2026-04-14 07:05:51', NULL, NULL, NULL),
(172, 2, 'ORD-20260414-622578', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:09:26', '2026-04-14 07:09:26', NULL, NULL, NULL),
(173, 2, 'ORD-20260414-B87684', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:16:59', '2026-04-14 07:16:59', NULL, NULL, NULL),
(174, 2, 'ORD-20260414-34CBA6', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:17:55', '2026-04-14 07:17:55', NULL, NULL, NULL),
(175, 2, 'ORD-20260414-2D6148', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:18:58', '2026-04-14 07:18:58', NULL, NULL, NULL),
(176, 2, 'ORD-20260414-6C74E9', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:20:06', '2026-04-14 07:20:06', NULL, NULL, NULL),
(177, 2, 'ORD-20260414-A8327E', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:26:34', '2026-04-14 07:26:34', NULL, NULL, NULL),
(178, 2, 'ORD-20260414-47D92C', 860.00, 860.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:28:04', '2026-04-14 07:28:04', NULL, NULL, NULL),
(179, 2, 'ORD-20260414-9C4800', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:33:13', '2026-04-14 07:33:13', NULL, NULL, NULL),
(180, 2, 'ORD-20260414-EB8CB1', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:37:34', '2026-04-14 07:37:34', NULL, NULL, NULL),
(181, 2, 'ORD-20260414-7BE4BE', 755.00, 755.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:39:03', '2026-04-14 07:39:03', NULL, NULL, NULL),
(182, 2, 'ORD-20260414-9E427A', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'FAZIL', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:42:49', '2026-04-14 07:42:49', NULL, NULL, NULL),
(183, 2, 'ORD-20260414-B42615', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'FAZIL', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:44:43', '2026-04-14 07:44:43', NULL, NULL, NULL),
(184, 2, 'ORD-20260414-87A215', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'FAZIL', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:50:16', '2026-04-14 07:50:16', NULL, NULL, NULL),
(185, 2, 'ORD-20260414-24998D', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'cancelled', 'FAZIL', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:50:42', '2026-04-14 07:51:35', 'order_SdIRiOkeA543oX', NULL, NULL),
(186, 17, 'ORD-20260414-68AB3C', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'Akthar', '9087471613', NULL, 'Asfjkl, Tamil Nadu , dfhjk, chennai , 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:56:38', '2026-04-14 07:56:45', 'order_SdIXxoBo1caBFm', NULL, NULL),
(187, 16, 'ORD-20260414-6E7E43', 13280.00, 13280.00, 0.00, 0.00, 0.00, 'pending', 'Asif', '9176782584', NULL, '30, dbk street, Washermenpet , chennai , 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:56:54', '2026-04-14 07:56:54', NULL, NULL, NULL),
(188, 15, 'ORD-20260414-090B66', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'kuttysoora', '9962463925', NULL, '2467, jehxjn, ksuhdiknn, Chennai , 600002', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 08:33:52', '2026-04-14 08:33:52', NULL, NULL, NULL),
(189, 15, 'ORD-20260414-848A5D', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'kuttysoora', '9962463925', NULL, 'ortish, wyivh, ir7bdjkk, Chennai , 600002', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 08:34:48', '2026-04-14 08:34:48', NULL, NULL, NULL),
(190, 8, 'ORD-20260414-872249', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'Lakshmi', '8939497811', NULL, '463, jeevarathinam nagar, royapuram, chennai , 600013', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 15:32:56', '2026-04-14 15:33:58', 'order_SdQKw0wWdD7xLc', NULL, NULL),
(191, 2, 'ORD-20260414-E12C8E', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:39:58', '2026-04-14 16:39:58', NULL, NULL, NULL),
(192, 2, 'ORD-20260414-20372E', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:40:02', '2026-04-14 16:40:02', NULL, NULL, NULL),
(193, 2, 'ORD-20260414-4E9212', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:41:56', '2026-04-14 16:41:56', NULL, NULL, NULL),
(194, 2, 'ORD-20260414-04644C', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:42:56', '2026-04-14 16:42:56', NULL, NULL, NULL),
(195, 2, 'ORD-20260414-6EC176', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:44:38', '2026-04-14 16:45:38', 'order_SdRYdfK2kT3c0b', NULL, NULL),
(196, 2, 'ORD-20260414-FC0A26', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:46:07', '2026-04-14 16:46:07', NULL, NULL, NULL),
(197, 2, 'ORD-20260414-5DC801', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:49:09', '2026-04-14 16:49:09', NULL, NULL, NULL),
(198, 2, 'ORD-20260414-079E4D', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:50:24', '2026-04-14 16:50:24', NULL, NULL, NULL),
(199, 2, 'ORD-20260414-FBD191', 1700.00, 1700.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:51:43', '2026-04-14 16:51:43', NULL, NULL, NULL),
(200, 2, 'ORD-20260414-286E5F', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:54:26', '2026-04-14 16:54:26', NULL, NULL, NULL),
(201, 2, 'ORD-20260414-3322D1', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:56:35', '2026-04-14 16:56:51', 'order_SdRkTal3gqS7tt', NULL, NULL),
(202, 2, 'ORD-20260414-032371', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 16:59:12', '2026-04-14 16:59:12', NULL, NULL, NULL),
(203, 2, 'ORD-20260414-901497', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 17:01:45', '2026-04-14 17:01:45', NULL, NULL, NULL),
(204, 1, 'ORD-20260414-23AEDC', 1.30, 1.30, 0.00, 0.00, 0.00, 'pending', 'fazil', '9876543210', NULL, '123 Main St, City, State', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 17:05:38', '2026-04-14 17:05:38', NULL, NULL, NULL),
(205, 2, 'ORD-20260414-E4DAD5', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 17:09:02', '2026-04-14 17:09:02', NULL, NULL, NULL),
(206, 2, 'ORD-20260414-2570FB', 850.00, 850.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 17:41:22', '2026-04-14 17:41:37', 'order_SdSVlemnemiOSo', NULL, NULL),
(207, 2, 'ORD-20260414-20300D', 960.00, 960.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 17:42:42', '2026-04-14 17:42:49', 'order_SdSX2wD5RznaKm', NULL, NULL),
(208, 2, 'ORD-20260414-50F8B0', 755.00, 755.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 17:52:05', '2026-04-14 17:52:15', 'order_SdSh0SCU0tVvj8', NULL, NULL),
(209, 2, 'ORD-20260414-299F01', 1298.00, 1298.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 18:02:42', '2026-04-14 18:02:52', 'order_SdSsDmGJExHUSN', NULL, NULL),
(210, 2, 'ORD-20260414-32230D', 12870.00, 12870.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 18:09:39', '2026-04-14 18:09:54', 'order_SdSzeR7Ps5gGEn', NULL, NULL),
(211, 2, 'ORD-20260414-E9CCDA', 6800.00, 6800.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-14 18:15:42', '2026-04-14 18:15:54', 'order_SdT5z6VRLxKXT2', NULL, NULL),
(212, 18, 'ORD-20260415-A26B16', 14025.00, 14025.00, 0.00, 0.00, 0.00, 'cancelled', 'kumar', '9176246393', NULL, '462, jeefjak, kasimedu , Chennai , 600001', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-15 03:23:54', '2026-04-15 03:24:57', NULL, NULL, NULL),
(213, 1, 'ORD-20260418-534F42', 1720.00, 1720.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '9876543210', NULL, '121, abc street, old washermenpet, chennai, 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-18 03:37:25', '2026-04-18 03:37:25', NULL, NULL, NULL),
(214, 2, 'ORD-20260421-E0B192', 750.00, 750.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 10:36:46', '2026-04-21 10:36:50', 'order_Sg70trEJw9bqnI', NULL, NULL),
(215, 23, 'ORD-20260421-D500DA', 1099.00, 1099.00, 0.00, 0.00, 0.00, 'pending', 'geetha', '9841329565', NULL, '17, Sudalai Muthu street , newwashermenpet , chennai , chennai , 600081', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 19:24:13', '2026-04-21 19:24:13', NULL, NULL, NULL),
(216, 23, 'ORD-20260421-E674F3', 1099.00, 1099.00, 0.00, 0.00, 0.00, 'pending', 'geetha', '9841329565', NULL, '17, Sudalai Muthu street , newwashermenpet , chennai , chennai , 600081', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 19:24:30', '2026-04-21 19:24:30', NULL, NULL, NULL),
(217, 23, 'ORD-20260421-653C6B', 1200.00, 1200.00, 0.00, 0.00, 0.00, 'cancelled', 'geetha', '9841329565', NULL, '17, Sudalai Muthu street , newwashermenpet , chennai , 600081', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 19:27:34', '2026-04-22 11:24:36', NULL, NULL, NULL),
(218, 1, 'ORD-20260422-838752', 750.00, 750.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '9876543210', NULL, '121, abc street, old washermenpet, chennai, 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-22 03:59:04', '2026-04-22 03:59:08', 'order_SgOlvViz8DR1Ov', NULL, NULL),
(219, 1, 'ORD-20260505-C32379', 1149.00, 1149.00, 0.00, 0.00, 0.00, 'cancelled', 'fazil', '9876543210', NULL, '121, abc street, old washermenpet, chennai, 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-05 09:40:28', '2026-05-05 09:41:48', 'order_SldXfSl6nn6kLr', NULL, NULL),
(220, 14, 'ORD-20260507-C46750', 1100.00, 1100.00, 0.00, 0.00, 0.00, 'cancelled', 'jeevaroshini', '8637671775', NULL, 'no 41 jayalakshmi nagar nehruji street, nerkundram, Nerkundram , chennai, 600107', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-07 07:17:16', '2026-05-07 07:25:59', NULL, NULL, NULL),
(221, 14, 'ORD-20260507-345915', 1100.00, 1100.00, 0.00, 0.00, 0.00, 'delivered', 'jeevaroshini', '8637671775', NULL, 'no 41, jayalakshmi nagar , neruji street Nerkundram , chennai, 600107', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-07 07:18:43', '2026-05-07 13:17:54', 'order_SmOBgqrYhxqpg3', 'pay_SmOCskTu8rW8sO', '75f851485586c398b9f3482735ec7358dcc2e9cbfd07c09a358319910520f9ac'),
(222, 65, 'ORD-20260507-501CB0', 1499.00, 1499.00, 0.00, 0.00, 0.00, 'cancelled', 'padmavathy', '9597335615', NULL, 'no 151, no 151,pillaiyar kovil street, melathangal,chetpet,tiruvannamalai, tiruvannamalai, 606807', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-07 15:21:57', '2026-05-08 10:25:53', NULL, NULL, NULL),
(223, 93, 'ORD-20260509-E2F65F', 739.00, 739.00, 0.00, 0.00, 0.00, 'cancelled', 'vimala', '9787022858', NULL, '26/89 ,Homes India flat - A, , chithirai street, , chinmaya Nagar stage -2, virugambakkam, Chennai. , 600092', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-09 14:17:02', '2026-05-10 13:07:14', NULL, NULL, NULL),
(224, 93, 'ORD-20260509-DEB157', 739.00, 739.00, 0.00, 0.00, 0.00, 'delivered', 'vimala', '9787022858', NULL, '26/89, Homes India flat, Chithirai street, Chinmaya Nagar stage -2, virugambakkam , Chennai, 600092', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-09 14:22:05', '2026-05-10 06:55:51', 'order_SnITOUlF1xicsg', 'pay_SnIUBOetDNkV2z', '6992321c5edb2d08402ac954028794ae1d194ba0d40644b7fe1fc7ff7792f880'),
(225, 94, 'ORD-20260510-BBDE60', 2509.00, 2509.00, 0.00, 0.00, 0.00, 'delivered', 'Thiripuprashanth', '9789032695', NULL, '40 , Venkatesan Street, royapuram , chennai , 600013', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-10 04:50:35', '2026-05-10 06:55:47', 'order_SnXGkyezleK0Qj', 'pay_SnXGrf7itqh5cW', '6719cdadfdf982c54f96ec2a8deda273e856e4e8cb2c98e60b452796a6cc46cf'),
(226, 84, 'ORD-20260511-BE967D', 850.00, 850.00, 0.00, 0.00, 0.00, 'cancelled', 'Hannah Nikita', '9566137557', NULL, 'no 142 Nms villa Ambattur , groove street ambattur, ambattur, chennai, 600053', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-11 18:43:07', '2026-05-13 18:44:49', NULL, NULL, NULL),
(227, 96, 'ORD-20260512-C4CA9C', 850.00, 850.00, 0.00, 0.00, 0.00, 'delivered', 'abdul basith', '7871012399', NULL, 'no.11/60,jayaram street, jayaram street, old washermanpet, Chennai, 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-12 06:02:04', '2026-05-12 08:41:57', 'order_SoLYI7K73Nz7iV', 'pay_SoLYhbcyKKrYDb', '8a2919f8958dbe9f403447ea9013e14c5bfdd4b8465d0814c364be49ef8f3693'),
(228, 94, 'ORD-20260518-23C5A5', 1925.00, 1925.00, 0.00, 0.00, 0.00, 'delivered', 'Thiripu Prashanth', '9789032695', NULL, '40, Venkatesan street, Royapuram, Chennai, 600013', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-18 03:58:10', '2026-05-18 08:49:21', 'order_Sqge8Qk7fr22Yi', 'pay_SqgeGYVAmTUCj6', '1d42fbea5eee901979bbfb2f2d5a20cf9b11c5ea61a04f44a2e073cdca90fc7f'),
(229, 106, 'ORD-20260523-12E29E', 600.00, 600.00, 0.00, 0.00, 0.00, 'cancelled', 'Shyla', '7845169450', NULL, 'No. 142, VGN Brent park , Groove Street , Ambattur , Chennai , 600058', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-23 00:14:41', '2026-05-23 15:07:09', NULL, NULL, NULL),
(230, 109, 'ORD-20260529-808FD5', 1675.00, 1675.00, 0.00, 0.00, 0.00, 'cancelled', 'srilakshmi', '9444175278', NULL, 'plot no 3 Mullai street sivaprakasam nagar , mullai street sivaprakasam.nagar, Puzhuthivakka., chennai, 600091', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-29 14:14:00', '2026-05-30 12:12:18', NULL, NULL, NULL),
(231, 109, 'ORD-20260529-02E9C4', 1675.00, 1675.00, 0.00, 0.00, 0.00, 'delivered', 'srilakshmi', '9444175278', NULL, 'plot no , Mullai street Sivaprakasam nagar, Puzhuthivakkam, Chennai, 600091', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-29 14:19:12', '2026-05-30 09:54:54', 'order_SvD6YnF5mjtyA4', 'pay_SvD6hDywmKWord', '643860676e2cabdd8d2a7307975a1784d4cd6a306e603c853a57818247405f01'),
(232, 109, 'ORD-20260612-2CBBED', 980.00, 980.00, 0.00, 0.00, 0.00, 'delivered', 'srilakshmi', '9444175278', NULL, 'plot no 3, mullai street sivaprakasam nagar, puzhuthivakkam , chennai, 600091', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-12 13:49:06', '2026-06-13 13:07:23', 'order_T0k4NRxmIJwMPv', 'pay_T0k4c2j73Bmt4r', '89f164a8174a5f3f7a7530886a185d610d666a585463af690472e133cf3b66d3'),
(233, 57, 'ORD-20260620-D0C290', 2200.00, 2200.00, 0.00, 0.00, 0.00, 'cancelled', 'Princy', '9003201695', NULL, 'Plot 2, Srinivasan 2nd cross street , Nanmangalam, Kovilambakkam, 6001', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-20 05:37:33', '2026-06-20 11:40:05', NULL, NULL, NULL),
(234, 127, 'ORD-20260705-146BA7', 1100.00, 1100.00, 0.00, 0.00, 0.00, 'pending', 'Sharanya', '9790718424', NULL, 'flat no.2,  swati sumukhi, nageswara road, Nungambakkam , Chennai , 600034', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-05 01:41:53', '2026-07-05 01:41:53', NULL, NULL, NULL),
(235, 127, 'ORD-20260705-AB9945', 1100.00, 1100.00, 0.00, 0.00, 0.00, 'delivered', 'Sharanya', '9790718424', NULL, 'flat no.2, swati sumukhi apartments , 15, Nageswara road, Nungambakkam , Chennai , 600034', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-05 01:44:58', '2026-07-06 01:39:23', 'order_T9e1I7tRovL0Rm', 'pay_T9e1hyiFObpLTM', '7e494f481d5aaa8bd7788358892f05cbc9f2f7d23b9a305b809e6cd5cae8d182'),
(236, 119, 'ORD-20260714-BEA5B7', 1200.00, 1200.00, 0.00, 0.00, 0.00, 'pending', 'Santhosh M', '9566155289', NULL, '67/16, Gandhi Avanue 2, Village Street, Tiruvottiyur, Chennai, 600019', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-14 10:07:07', '2026-07-14 10:07:07', NULL, NULL, NULL),
(237, 119, 'ORD-20260714-91B894', 650.00, 650.00, 0.00, 0.00, 0.00, 'pending', 'Santhosh M', '9566155289', NULL, '67/16, Gandhi Avenue 2, Village Street, Tiruvottiyur, Chennai, 600019', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-14 10:08:25', '2026-07-14 10:08:31', 'order_TDLP6afCex2A48', NULL, NULL),
(238, 109, 'ORD-20260716-20601E', 2500.00, 2500.00, 0.00, 0.00, 0.00, 'pending', 'srilakshmi', '9444175278', NULL, 'plot no 3 mullai street sivaprakasam nagar , mullai street , Sivaprakasam nagar puzhuthivakkam , Chennai , 600091', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-16 14:26:42', '2026-07-16 14:26:42', NULL, NULL, NULL),
(239, 109, 'ORD-20260716-4C7BD0', 1950.00, 1950.00, 0.00, 0.00, 0.00, 'delivered', 'srilakshmi', '9444175278', NULL, 'plot no 3, mullai street , sivaprakasam nagar puzhuthivakkam , chennai, 600091', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'paid', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-16 14:36:52', '2026-07-17 12:16:53', 'order_TED2w9dWOeGhQs', 'pay_TED349qkqGodOs', '62a0a7eff41f108e73addc8e3d7655c2671922c831938eb29f03896c291f6506'),
(240, 150, 'ORD-20260726-7ED2EB', 820.00, 820.00, 0.00, 0.00, 0.00, 'pending', 'raushni mohan Kumar', '9360231410', NULL, '53, chinna street , Kundrathur , chennai , 69', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-26 04:36:39', '2026-07-26 04:36:39', NULL, NULL, NULL),
(241, 156, 'ORD-20260727-539BA9', 1600.00, 1600.00, 0.00, 0.00, 0.00, 'pending', 'sugantha', '9043914268', NULL, 'no 2a, mahalakshmi street gurusamy nagar extn, mugalivakkam , chennai, 600125', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-27 13:12:37', '2026-07-27 13:12:37', NULL, NULL, NULL),
(242, 164, 'ORD-20260729-EE7F12', 649.00, 649.00, 0.00, 0.00, 0.00, 'pending', 'revathi', '7305535620', NULL, '51/21, mian street, vinayagapuram , chennai, 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-29 09:10:22', '2026-07-29 09:10:22', NULL, NULL, NULL),
(243, 167, 'ORD-20260801-38996A', 600.00, 600.00, 0.00, 0.00, 0.00, 'pending', 'keerthi', '6382107795', NULL, '543, S A COLONY 7TH STREET 3RD CROSS , sharmanagar , chennai , 600039', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-01 17:56:51', '2026-08-01 17:56:51', NULL, NULL, NULL),
(244, 169, 'ORD-20260804-5097AD', 1200.00, 1200.00, 0.00, 0.00, 0.00, 'pending', 'premalatha', '8667351231', NULL, '86 , thandavaraya mudali st, oldwashermenpet, chennai, 600021', NULL, NULL, NULL, NULL, 'cash_on_delivery', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-04 13:18:29', '2026-08-04 13:18:29', NULL, NULL, NULL),
(245, 2, 'ORD-20260805-95939C', 820.00, 820.00, 0.00, 0.00, 0.00, 'pending', 'fazil', '8428617202', NULL, '121, test, test, chen, 123456', NULL, NULL, NULL, NULL, 'cash_on_delivery', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-05 18:59:05', '2026-08-05 18:59:13', 'order_TMCCMMxpgEX0lC', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_image_url` varchar(500) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `product_sku` varchar(50) DEFAULT NULL,
  `product_category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image_url`, `quantity`, `unit_price`, `total_price`, `discount_amount`, `tax_amount`, `product_sku`, `product_category`, `created_at`, `updated_at`) VALUES
(1, 134, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:26:48', '2026-04-14 04:26:48'),
(2, 135, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 2000.00, 850.00, 3400.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:34:43', '2026-04-14 04:34:43'),
(3, 136, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 1500.00, 850.00, 2550.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:39:08', '2026-04-14 04:39:08'),
(4, 137, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:40:58', '2026-04-14 04:40:58'),
(5, 138, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:48:07', '2026-04-14 04:48:07'),
(6, 139, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:48:54', '2026-04-14 04:48:54'),
(7, 140, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:55:49', '2026-04-14 04:55:49'),
(8, 141, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 04:56:52', '2026-04-14 04:56:52'),
(9, 142, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:18:31', '2026-04-14 05:18:31'),
(10, 143, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:18:55', '2026-04-14 05:18:55'),
(11, 144, 45, 'Kadalveral fish finger', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763427_694eaba31483c.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:21:46', '2026-04-14 05:21:46'),
(12, 144, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:21:46', '2026-04-14 05:21:46'),
(13, 145, 45, 'Kadalveral fish finger', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763427_694eaba31483c.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:24:26', '2026-04-14 05:24:26'),
(14, 145, 61, 'test product', '', 250.00, 2500.00, 2500.00, 0.00, 0.00, 'per_250g', 'Special Seafoods', '2026-04-14 05:24:26', '2026-04-14 05:24:26'),
(15, 146, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:34:06', '2026-04-14 05:34:06'),
(16, 147, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:36:20', '2026-04-14 05:36:20'),
(17, 148, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:37:30', '2026-04-14 05:37:30'),
(18, 149, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:40:16', '2026-04-14 05:40:16'),
(19, 150, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:42:34', '2026-04-14 05:42:34'),
(20, 151, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:48:08', '2026-04-14 05:48:08'),
(21, 152, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:50:10', '2026-04-14 05:50:10'),
(22, 153, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:53:28', '2026-04-14 05:53:28'),
(23, 154, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:56:08', '2026-04-14 05:56:08'),
(24, 155, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 1500.00, 850.00, 2550.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 05:59:36', '2026-04-14 05:59:36'),
(25, 156, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:06:56', '2026-04-14 06:06:56'),
(26, 157, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:07:38', '2026-04-14 06:07:38'),
(27, 158, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 1000.00, 850.00, 1700.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:08:52', '2026-04-14 06:08:52'),
(28, 159, 45, 'Kadalveral fish finger', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763427_694eaba31483c.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:09:59', '2026-04-14 06:09:59'),
(29, 159, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:09:59', '2026-04-14 06:09:59'),
(30, 160, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:10:48', '2026-04-14 06:10:48'),
(31, 161, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:12:38', '2026-04-14 06:12:38'),
(32, 162, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:17:56', '2026-04-14 06:17:56'),
(33, 163, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:19:41', '2026-04-14 06:19:41'),
(34, 164, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:20:31', '2026-04-14 06:20:31'),
(35, 165, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:36:17', '2026-04-14 06:36:17'),
(36, 166, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:38:13', '2026-04-14 06:38:13'),
(37, 167, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:43:33', '2026-04-14 06:43:33'),
(38, 168, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 06:48:23', '2026-04-14 06:48:23'),
(39, 169, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:04:46', '2026-04-14 07:04:46'),
(40, 170, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 3000.00, 850.00, 5100.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:05:14', '2026-04-14 07:05:14'),
(41, 171, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:05:51', '2026-04-14 07:05:51'),
(42, 172, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:09:26', '2026-04-14 07:09:26'),
(43, 173, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:16:59', '2026-04-14 07:16:59'),
(44, 174, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:17:55', '2026-04-14 07:17:55'),
(45, 175, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:18:58', '2026-04-14 07:18:58'),
(46, 176, 45, 'Kadalveral fish finger', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763427_694eaba31483c.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:20:06', '2026-04-14 07:20:06'),
(47, 176, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:20:06', '2026-04-14 07:20:06'),
(48, 177, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:26:34', '2026-04-14 07:26:34'),
(49, 178, 38, 'Sea blue crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_038.png', 1000.00, 430.00, 860.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-04-14 07:28:04', '2026-04-14 07:28:04'),
(50, 179, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:33:13', '2026-04-14 07:33:13'),
(51, 180, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:37:34', '2026-04-14 07:37:34'),
(52, 181, 31, 'Sea Crabs / 3dot crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_031.png', 500.00, 325.00, 325.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-04-14 07:39:03', '2026-04-14 07:39:03'),
(53, 181, 38, 'Sea blue crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_038.png', 500.00, 430.00, 430.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-04-14 07:39:03', '2026-04-14 07:39:03'),
(54, 182, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:42:49', '2026-04-14 07:42:49'),
(55, 183, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:44:43', '2026-04-14 07:44:43'),
(56, 184, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:50:16', '2026-04-14 07:50:16'),
(57, 185, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 1000.00, 850.00, 1700.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:50:42', '2026-04-14 07:50:42'),
(58, 186, 45, 'Kadalveral fish finger', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763427_694eaba31483c.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:56:38', '2026-04-14 07:56:38'),
(59, 186, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:56:38', '2026-04-14 07:56:38'),
(60, 187, 38, 'Sea blue crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_038.png', 500.00, 430.00, 430.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-04-14 07:56:54', '2026-04-14 07:56:54'),
(61, 187, 43, 'Vanjaram meen Full Fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_043.png', 10000.00, 6000.00, 12000.00, 0.00, 0.00, 'per_5kg', 'Fish', '2026-04-14 07:56:54', '2026-04-14 07:56:54'),
(62, 187, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 07:56:54', '2026-04-14 07:56:54'),
(63, 188, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 1000.00, 850.00, 1700.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 08:33:52', '2026-04-14 08:33:52'),
(64, 189, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 08:34:48', '2026-04-14 08:34:48'),
(65, 190, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 1000.00, 850.00, 1700.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 15:32:56', '2026-04-14 15:32:56'),
(66, 191, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:39:58', '2026-04-14 16:39:58'),
(67, 192, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:40:02', '2026-04-14 16:40:02'),
(68, 193, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:41:56', '2026-04-14 16:41:56'),
(69, 194, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:42:56', '2026-04-14 16:42:56'),
(70, 195, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:44:38', '2026-04-14 16:44:38'),
(71, 196, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:46:07', '2026-04-14 16:46:07'),
(72, 197, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:49:09', '2026-04-14 16:49:09'),
(73, 198, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:50:24', '2026-04-14 16:50:24'),
(74, 199, 45, 'Kadalveral fish finger', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763427_694eaba31483c.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:51:43', '2026-04-14 16:51:43'),
(75, 199, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:51:43', '2026-04-14 16:51:43'),
(76, 200, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:54:26', '2026-04-14 16:54:26'),
(77, 201, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:56:35', '2026-04-14 16:56:35'),
(78, 202, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 16:59:12', '2026-04-14 16:59:12'),
(79, 203, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 17:01:45', '2026-04-14 17:01:45'),
(80, 204, 1, 'live lobsters', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_001.png', 0.50, 1299.00, 1.30, 0.00, 0.00, 'per_500g', 'Squids and Lobsters', '2026-04-14 17:05:38', '2026-04-14 17:05:38'),
(81, 205, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 17:09:02', '2026-04-14 17:09:02'),
(82, 206, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 17:41:22', '2026-04-14 17:41:22'),
(83, 207, 32, 'Lady fish / keelanga meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766762808_694ea938a0215.jpg', 1000.00, 480.00, 960.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 17:42:42', '2026-04-14 17:42:42'),
(84, 208, 31, 'Sea Crabs / 3dot crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_031.png', 500.00, 325.00, 325.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-04-14 17:52:05', '2026-04-14 17:52:05'),
(85, 208, 38, 'Sea blue crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_038.png', 500.00, 430.00, 430.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-04-14 17:52:05', '2026-04-14 17:52:05'),
(86, 209, 13, 'Cuttlefish ( Squid Family Seafoods)', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_013.png', 1000.00, 599.00, 599.00, 0.00, 0.00, 'per_kg', 'Squids and Lobsters', '2026-04-14 18:02:42', '2026-04-14 18:02:42'),
(87, 209, 36, 'Sand Lobster', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_036.png', 1000.00, 699.00, 699.00, 0.00, 0.00, 'per_kg', 'Squids and Lobsters', '2026-04-14 18:02:42', '2026-04-14 18:02:42'),
(88, 210, 41, 'Sheela meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_041.png', 1000.00, 870.00, 870.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-04-14 18:09:39', '2026-04-14 18:09:39'),
(89, 210, 42, 'Kadalveral meen / cobia full Fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763193_694eaab9e41a8.jpg', 5000.00, 4250.00, 4250.00, 0.00, 0.00, 'per_5kg', 'Fish', '2026-04-14 18:09:39', '2026-04-14 18:09:39'),
(90, 210, 43, 'Vanjaram meen Full Fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_043.png', 5000.00, 6000.00, 6000.00, 0.00, 0.00, 'per_5kg', 'Fish', '2026-04-14 18:09:39', '2026-04-14 18:09:39'),
(91, 210, 44, 'White pomfret fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763382_694eab76117e8.jpg', 500.00, 900.00, 900.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 18:09:39', '2026-04-14 18:09:39'),
(92, 210, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 18:09:39', '2026-04-14 18:09:39'),
(93, 211, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 4000.00, 850.00, 6800.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-14 18:15:42', '2026-04-14 18:15:42'),
(94, 212, 31, 'Sea Crabs / 3dot crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_031.png', 500.00, 325.00, 325.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-04-15 03:23:54', '2026-04-15 03:23:54'),
(95, 212, 43, 'Vanjaram meen Full Fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_043.png', 10000.00, 6000.00, 12000.00, 0.00, 0.00, 'per_5kg', 'Fish', '2026-04-15 03:23:54', '2026-04-15 03:23:54'),
(96, 212, 46, 'Vanjaram Meen Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766761420_694ea3cc839cc.jpg', 1000.00, 850.00, 1700.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-15 03:23:54', '2026-04-15 03:23:54'),
(97, 213, 41, 'Sheela meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_image_041.png', 1000.00, 870.00, 870.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-04-18 03:37:25', '2026-04-18 03:37:25'),
(98, 213, 45, 'Kadalveral fish finger', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1766763427_694eaba31483c.jpg', 500.00, 850.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-18 03:37:25', '2026-04-18 03:37:25'),
(99, 214, 46, 'Romeo Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776485945_69e3063942da1.jpeg', 1000.00, 750.00, 750.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-04-21 10:36:46', '2026-04-21 10:36:46'),
(100, 215, 35, 'Lady fish / keelanga meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631973_69e540a5419dd.jpeg', 1000.00, 400.00, 800.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-21 19:24:13', '2026-04-21 19:24:13'),
(101, 215, 45, 'Big Nethili meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776509575_69e3628761eef.jpeg', 500.00, 299.00, 299.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-21 19:24:13', '2026-04-21 19:24:13'),
(102, 216, 35, 'Lady fish / keelanga meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631973_69e540a5419dd.jpeg', 1000.00, 400.00, 800.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-21 19:24:30', '2026-04-21 19:24:30'),
(103, 216, 45, 'Big Nethili meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776509575_69e3628761eef.jpeg', 500.00, 299.00, 299.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-21 19:24:30', '2026-04-21 19:24:30'),
(104, 217, 35, 'Lady fish / keelanga meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631973_69e540a5419dd.jpeg', 1500.00, 400.00, 1200.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-04-21 19:27:34', '2026-04-21 19:27:34'),
(105, 218, 46, 'Romeo Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776485945_69e3063942da1.jpeg', 1000.00, 750.00, 750.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-04-22 03:59:04', '2026-04-22 03:59:04'),
(106, 219, 29, 'Vanjaram / king Fish Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625249_69e5266114dde.jpeg', 500.00, 950.00, 950.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-05 09:40:28', '2026-05-05 09:40:28'),
(107, 219, 72, 'Thelalparai meen / Karuvadu', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777973776_69f9ba1054b5e.jpg', 250.00, 199.00, 199.00, 0.00, 0.00, 'per_250g', 'Dry Seafoods', '2026-05-05 09:40:28', '2026-05-05 09:40:28'),
(108, 220, 36, 'squid', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776708373_69e66b15e95cd.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Squids and Lobsters', '2026-05-07 07:17:16', '2026-05-07 07:17:16'),
(109, 220, 62, 'Fresh White prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-05-07 07:17:16', '2026-05-07 07:17:16'),
(110, 221, 36, 'squid', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776708373_69e66b15e95cd.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Squids and Lobsters', '2026-05-07 07:18:43', '2026-05-07 07:18:43'),
(111, 221, 62, 'Fresh White prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-05-07 07:18:43', '2026-05-07 07:18:43'),
(112, 222, 18, 'Red Koduva meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776628620_69e5338c758e4.jpeg', 1000.00, 720.00, 720.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-05-07 15:21:57', '2026-05-07 15:21:57'),
(113, 222, 64, 'Sea blue crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776706744_69e664b89c073.jpeg', 500.00, 430.00, 430.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-05-07 15:21:57', '2026-05-07 15:21:57'),
(114, 222, 71, 'Dry Karapodi meen / Karuvadu', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777973244_69f9b7fc16fe4.jpg', 500.00, 349.00, 349.00, 0.00, 0.00, 'per_500g', 'Dry Seafoods', '2026-05-07 15:21:57', '2026-05-07 15:21:57'),
(115, 223, 44, 'Ayala / kanakeluthi meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776506824_69e357c89fa4e.jpeg', 1000.00, 440.00, 440.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-05-09 14:17:02', '2026-05-09 14:17:02'),
(116, 223, 45, 'Big Nethili meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777972728_69f9b5f824f44.jpeg', 500.00, 299.00, 299.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-09 14:17:02', '2026-05-09 14:17:02'),
(117, 224, 44, 'Ayala / kanakeluthi meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776506824_69e357c89fa4e.jpeg', 1000.00, 440.00, 440.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-05-09 14:22:05', '2026-05-09 14:22:05'),
(118, 224, 45, 'Big Nethili meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777972728_69f9b5f824f44.jpeg', 500.00, 299.00, 299.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-09 14:22:05', '2026-05-09 14:22:05'),
(119, 225, 6, 'Udan meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631392_69e53e6048f2f.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-05-10 04:50:35', '2026-05-10 04:50:35'),
(120, 225, 34, 'Indian salmon / kala meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510539_69e3664b124b1.jpeg', 1000.00, 1099.00, 1099.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-05-10 04:50:35', '2026-05-10 04:50:35'),
(121, 225, 64, 'Sea blue crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776706744_69e664b89c073.jpeg', 1000.00, 430.00, 860.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-05-10 04:50:35', '2026-05-10 04:50:35'),
(122, 226, 41, 'Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624038_69e521a6e1dca.jpeg', 500.00, 300.00, 300.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-11 18:43:07', '2026-05-11 18:43:07'),
(123, 226, 62, 'Fresh White prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-05-11 18:43:07', '2026-05-11 18:43:07'),
(124, 227, 39, 'Thenga parai meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510432_69e365e00a89f.jpeg', 1000.00, 425.00, 850.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-12 06:02:04', '2026-05-12 06:02:04'),
(125, 228, 29, 'Vanjaram / king Fish Slice', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625249_69e5266114dde.jpeg', 500.00, 950.00, 950.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-18 03:58:10', '2026-05-18 03:58:10'),
(126, 228, 39, 'Thenga parai meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510432_69e365e00a89f.jpeg', 500.00, 425.00, 425.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-18 03:58:10', '2026-05-18 03:58:10'),
(127, 228, 62, 'Fresh White prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-05-18 03:58:10', '2026-05-18 03:58:10'),
(128, 229, 41, 'Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624038_69e521a6e1dca.jpeg', 1000.00, 300.00, 600.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-23 00:14:41', '2026-05-23 00:14:41'),
(129, 230, 39, 'Thenga parai meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510432_69e365e00a89f.jpeg', 500.00, 425.00, 425.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:14:00', '2026-05-29 14:14:00'),
(130, 230, 41, 'Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624038_69e521a6e1dca.jpeg', 500.00, 300.00, 300.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:14:00', '2026-05-29 14:14:00'),
(131, 230, 42, 'Mathi meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776508890_69e35fda3a06a.jpeg', 500.00, 250.00, 250.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:14:00', '2026-05-29 14:14:00'),
(132, 230, 43, 'kavalai meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776507214_69e3594e4ccf9.jpeg', 1000.00, 350.00, 350.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-05-29 14:14:00', '2026-05-29 14:14:00'),
(133, 230, 46, 'Romeo Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776485945_69e3063942da1.jpeg', 500.00, 350.00, 350.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:14:00', '2026-05-29 14:14:00'),
(134, 231, 39, 'Thenga parai meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510432_69e365e00a89f.jpeg', 500.00, 425.00, 425.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:19:12', '2026-05-29 14:19:12'),
(135, 231, 41, 'Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624038_69e521a6e1dca.jpeg', 500.00, 300.00, 300.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:19:12', '2026-05-29 14:19:12'),
(136, 231, 42, 'Mathi meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776508890_69e35fda3a06a.jpeg', 500.00, 250.00, 250.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:19:12', '2026-05-29 14:19:12'),
(137, 231, 43, 'kavalai meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776507214_69e3594e4ccf9.jpeg', 1000.00, 350.00, 350.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-05-29 14:19:12', '2026-05-29 14:19:12'),
(138, 231, 46, 'Romeo Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776485945_69e3063942da1.jpeg', 500.00, 350.00, 350.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-05-29 14:19:12', '2026-05-29 14:19:12'),
(139, 232, 62, 'Fresh White prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-06-12 13:49:06', '2026-06-12 13:49:06'),
(140, 232, 64, 'Sea blue crabs', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776706744_69e664b89c073.jpeg', 500.00, 430.00, 430.00, 0.00, 0.00, 'per_500g', 'Crabs', '2026-06-12 13:49:06', '2026-06-12 13:49:06'),
(141, 233, 23, 'Black pomfret / vaval meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776627190_69e52df657213.jpeg', 1000.00, 550.00, 1100.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-06-20 05:37:33', '2026-06-20 05:37:33'),
(142, 233, 62, 'Fresh White prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', 2000.00, 550.00, 1100.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-06-20 05:37:33', '2026-06-20 05:37:33'),
(143, 234, 37, 'Sea Shrimps prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689997_69e6234d569be.jpeg', 2000.00, 550.00, 1100.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-07-05 01:41:53', '2026-07-05 01:41:53'),
(144, 235, 37, 'Sea Shrimps prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689997_69e6234d569be.jpeg', 2000.00, 550.00, 1100.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-07-05 01:44:58', '2026-07-05 01:44:58'),
(145, 236, 11, 'Leather Jacket fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776630565_69e53b25600f0.jpeg', 1000.00, 650.00, 650.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-07-14 10:07:07', '2026-07-14 10:07:07'),
(146, 236, 37, 'Sea Shrimps prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689997_69e6234d569be.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-07-14 10:07:07', '2026-07-14 10:07:07'),
(147, 237, 11, 'Leather Jacket fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776630565_69e53b25600f0.jpeg', 1000.00, 650.00, 650.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-07-14 10:08:25', '2026-07-14 10:08:25'),
(148, 238, 23, 'Black pomfret / vaval meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776627190_69e52df657213.jpeg', 500.00, 550.00, 550.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-07-16 14:26:42', '2026-07-16 14:26:42'),
(149, 238, 32, 'Sheela meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625028_69e52584812b4.jpeg', 1000.00, 850.00, 850.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-07-16 14:26:42', '2026-07-16 14:26:42'),
(150, 238, 37, 'Sea Shrimps prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689997_69e6234d569be.jpeg', 2000.00, 550.00, 1100.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-07-16 14:26:42', '2026-07-16 14:26:42'),
(151, 239, 23, 'Black pomfret / vaval meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776627190_69e52df657213.jpeg', 500.00, 550.00, 550.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-07-16 14:36:52', '2026-07-16 14:36:52'),
(152, 239, 32, 'Sheela meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625028_69e52584812b4.jpeg', 1000.00, 850.00, 850.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-07-16 14:36:52', '2026-07-16 14:36:52'),
(153, 239, 37, 'Sea Shrimps prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689997_69e6234d569be.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-07-16 14:36:52', '2026-07-16 14:36:52'),
(154, 240, 21, 'Tiger Prawn\'s 30 count', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776690294_69e6247645202.jpeg', 1000.00, 410.00, 820.00, 0.00, 0.00, 'per_500g', 'Prawns', '2026-07-26 04:36:39', '2026-07-26 04:36:39'),
(155, 241, 26, 'Kadalveral meen / cobia ( slice )', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776626079_69e5299f129df.jpeg', 500.00, 750.00, 750.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-07-27 13:12:37', '2026-07-27 13:12:37'),
(156, 241, 41, 'Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624038_69e521a6e1dca.jpeg', 500.00, 300.00, 300.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-07-27 13:12:37', '2026-07-27 13:12:37'),
(157, 241, 62, 'Fresh White prawns', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', 1000.00, 550.00, 550.00, 0.00, 0.00, 'per_kg', 'Prawns', '2026-07-27 13:12:37', '2026-07-27 13:12:37'),
(158, 242, 45, 'Big Nethili meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777972728_69f9b5f824f44.jpeg', 500.00, 299.00, 299.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-07-29 09:10:22', '2026-07-29 09:10:22'),
(159, 242, 46, 'Romeo Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776485945_69e3063942da1.jpeg', 500.00, 350.00, 350.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-07-29 09:10:22', '2026-07-29 09:10:22'),
(160, 243, 41, 'Sankara meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624038_69e521a6e1dca.jpeg', 1000.00, 300.00, 600.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-08-01 17:56:51', '2026-08-01 17:56:51'),
(161, 244, 11, 'Leather Jacket fish', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776630565_69e53b25600f0.jpeg', 1000.00, 650.00, 650.00, 0.00, 0.00, 'per_kg', 'Fish', '2026-08-04 13:18:29', '2026-08-04 13:18:29'),
(162, 244, 23, 'Black pomfret / vaval meen', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776627190_69e52df657213.jpeg', 500.00, 550.00, 550.00, 0.00, 0.00, 'per_500g', 'Fish', '2026-08-04 13:18:29', '2026-08-04 13:18:29'),
(163, 245, 21, 'Tiger Prawn\'s 30 count', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776690294_69e6247645202.jpeg', 1000.00, 410.00, 820.00, 0.00, 0.00, 'per_500g', 'Prawns', '2026-08-05 18:59:05', '2026-08-05 18:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'INR',
  `status` enum('initiated','success','failed','signature_failed','refunded') NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_logs`
--

INSERT INTO `payment_logs` (`id`, `order_id`, `razorpay_order_id`, `razorpay_payment_id`, `amount`, `currency`, `status`, `payment_method`, `error_message`, `created_at`) VALUES
(1, 154, 'order_SdGUt17iCIhvfg', NULL, 850.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 05:56:27'),
(2, 155, 'order_SdGYXwfWOjREOh', NULL, 2550.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 05:59:55'),
(3, 185, 'order_SdIRiOkeA543oX', NULL, 1700.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 07:50:50'),
(4, 186, 'order_SdIXxoBo1caBFm', NULL, 1700.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 07:56:45'),
(5, 190, 'order_SdQKw0wWdD7xLc', NULL, 1700.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 15:33:58'),
(6, 195, 'order_SdRYdfK2kT3c0b', NULL, 850.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 16:45:38'),
(7, 201, 'order_SdRkTal3gqS7tt', NULL, 850.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 16:56:51'),
(8, 206, 'order_SdSVlemnemiOSo', NULL, 850.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 17:41:37'),
(9, 207, 'order_SdSX2wD5RznaKm', NULL, 960.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 17:42:49'),
(10, 208, 'order_SdSh0SCU0tVvj8', NULL, 755.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 17:52:15'),
(11, 209, 'order_SdSsDmGJExHUSN', NULL, 1298.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 18:02:52'),
(12, 210, 'order_SdSzeR7Ps5gGEn', NULL, 12870.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 18:09:54'),
(13, 211, 'order_SdT5z6VRLxKXT2', NULL, 6800.00, 'INR', 'initiated', NULL, NULL, '2026-04-14 18:15:54'),
(14, 214, 'order_Sg70trEJw9bqnI', NULL, 750.00, 'INR', 'initiated', NULL, NULL, '2026-04-21 10:36:50'),
(15, 218, 'order_SgOlvViz8DR1Ov', NULL, 750.00, 'INR', 'initiated', NULL, NULL, '2026-04-22 03:59:08'),
(16, 219, 'order_SldXfSl6nn6kLr', NULL, 1149.00, 'INR', 'initiated', NULL, NULL, '2026-05-05 09:41:03'),
(17, 221, 'order_SmOBgqrYhxqpg3', NULL, 1100.00, 'INR', 'initiated', NULL, NULL, '2026-05-07 07:18:51'),
(18, 221, 'order_SmOBgqrYhxqpg3', 'pay_SmOCskTu8rW8sO', 1100.00, 'INR', 'success', 'upi', NULL, '2026-05-07 07:21:22'),
(19, 224, 'order_SnITOUlF1xicsg', NULL, 739.00, 'INR', 'initiated', NULL, NULL, '2026-05-09 14:22:27'),
(20, 224, 'order_SnITOUlF1xicsg', 'pay_SnIUBOetDNkV2z', 739.00, 'INR', 'success', 'upi', NULL, '2026-05-09 14:23:53'),
(21, 225, 'order_SnXGkyezleK0Qj', NULL, 2509.00, 'INR', 'initiated', NULL, NULL, '2026-05-10 04:50:53'),
(22, 225, 'order_SnXGkyezleK0Qj', 'pay_SnXGrf7itqh5cW', 2509.00, 'INR', 'success', 'upi', NULL, '2026-05-10 04:51:26'),
(23, 227, 'order_SoLYI7K73Nz7iV', NULL, 850.00, 'INR', 'initiated', NULL, NULL, '2026-05-12 06:02:10'),
(24, 227, 'order_SoLYI7K73Nz7iV', 'pay_SoLYhbcyKKrYDb', 850.00, 'INR', 'success', 'wallet', NULL, '2026-05-12 06:04:44'),
(25, 228, 'order_Sqge8Qk7fr22Yi', NULL, 1925.00, 'INR', 'initiated', NULL, NULL, '2026-05-18 03:58:16'),
(26, 228, 'order_Sqge8Qk7fr22Yi', 'pay_SqgeGYVAmTUCj6', 1925.00, 'INR', 'success', 'upi', NULL, '2026-05-18 03:58:46'),
(27, 231, 'order_SvD6YnF5mjtyA4', NULL, 1675.00, 'INR', 'initiated', NULL, NULL, '2026-05-29 14:19:23'),
(28, 231, 'order_SvD6YnF5mjtyA4', 'pay_SvD6hDywmKWord', 1675.00, 'INR', 'success', 'upi', NULL, '2026-05-29 14:20:27'),
(29, 232, 'order_T0k4NRxmIJwMPv', NULL, 980.00, 'INR', 'initiated', NULL, NULL, '2026-06-12 13:49:12'),
(30, 232, 'order_T0k4NRxmIJwMPv', 'pay_T0k4c2j73Bmt4r', 980.00, 'INR', 'success', 'upi', NULL, '2026-06-12 13:50:02'),
(31, 235, 'order_T9e1I7tRovL0Rm', NULL, 1100.00, 'INR', 'initiated', NULL, NULL, '2026-07-05 01:45:08'),
(32, 235, 'order_T9e1I7tRovL0Rm', 'pay_T9e1hyiFObpLTM', 1100.00, 'INR', 'success', 'upi', NULL, '2026-07-05 01:46:11'),
(33, 237, 'order_TDLP6afCex2A48', NULL, 650.00, 'INR', 'initiated', NULL, NULL, '2026-07-14 10:08:31'),
(34, 239, 'order_TED2w9dWOeGhQs', NULL, 1950.00, 'INR', 'initiated', NULL, NULL, '2026-07-16 14:36:59'),
(35, 239, 'order_TED2w9dWOeGhQs', 'pay_TED349qkqGodOs', 1950.00, 'INR', 'success', 'upi', NULL, '2026-07-16 14:37:40'),
(36, 245, 'order_TMCCMMxpgEX0lC', NULL, 820.00, 'INR', 'initiated', NULL, NULL, '2026-08-05 18:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `payment_refunds`
--

CREATE TABLE `payment_refunds` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `razorpay_payment_id` varchar(100) NOT NULL,
  `razorpay_refund_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('pending','processed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_webhooks`
--

CREATE TABLE `payment_webhooks` (
  `id` int(11) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `payload` text NOT NULL,
  `processed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `availability` varchar(50) DEFAULT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `image_url` text DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `created_date` date DEFAULT NULL,
  `last_updated` date DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `offer_price` decimal(10,2) DEFAULT NULL,
  `minimum_quantity` varchar(50) DEFAULT 'per_kg',
  `health_benefits` text DEFAULT NULL,
  `nutritional_info` text DEFAULT NULL,
  `product_uses` text DEFAULT NULL,
  `unit` varchar(20) DEFAULT 'kg',
  `price_unit` varchar(20) DEFAULT 'per_kg',
  `is_special` tinyint(1) DEFAULT 0 COMMENT 'Indicates if product is special/featured',
  `is_dry` tinyint(1) DEFAULT 0 COMMENT 'Indicates if product is dry seafood',
  `material` varchar(100) DEFAULT NULL COMMENT 'Product material information',
  `color` varchar(50) DEFAULT NULL COMMENT 'Product color information',
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Additional product images (JSON array)' CHECK (json_valid(`images`))
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category`, `brand`, `sku`, `availability`, `specifications`, `image_url`, `tags`, `created_date`, `last_updated`, `weight`, `dimensions`, `offer_price`, `minimum_quantity`, `health_benefits`, `nutritional_info`, `product_uses`, `unit`, `price_unit`, `is_special`, `is_dry`, `material`, `color`, `images`) VALUES
(1, 'live lobsters', 'Lobster 4 to 5 pieces for per kg. each pie 200g to 250g size. lobster will deliver lively.', 1299.00, 10, 'Squids and Lobsters', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"half kg / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776709553_69e66fb1982f0.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', 1.50, '10x5x3', 99.99, '1 Kg', '[\"Rich in protein\",\"Low calorie seafood\",\"Good source of minerals\",\"Supports brain health\"]', '[\"High protein content\",\"Low fat and calories\",\"Vitamin B12 and selenium\",\"Phosphorus and copper\"]', '[\"Grilled preparations\",\"Curry and gravy dishes\",\"Fried and roasted\",\"Premium seafood recipes\"]', 'piece', 'per_kg', 0, 0, NULL, NULL, NULL),
(2, 'Big Tiger prawns 10 count', 'Fresh Big Tiger  prawn 10count per kg ,1kg of prawns after cleaning will get 450g-500g', 1600.00, 15, 'Prawns', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776691288_69e62858095a6.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Low in calories\",\"High protein content\",\"Good source of selenium\",\"Supports muscle growth\"]', '[\"Lean protein source\",\"Low fat content\",\"Vitamin B12 and niacin\",\"Phosphorus and selenium\"]', '[\"Prawn curry dishes\",\"Stir-fry preparations\",\"Appetizers and snacks\",\"Biryani and rice dishes\"]', 'gram', 'per_500g', 0, 0, NULL, NULL, NULL),
(3, 'Thirukai meen / Ray Fish', 'Fresh thirukai meen steaks , steaks without head', 650.00, 15, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631807_69e53fff1d085.jpg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '2 Kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_2kg', 0, 0, NULL, NULL, NULL),
(62, 'Fresh White prawns', 'Fresh White prawns mid-size 60c to 70c per kg. 1kg of Prawn After cleaning will get 500g', 550.00, 50, 'Prawns', 'Kutty Soora', 'per_kg', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689893_69e622e593dde.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(4, 'Tiger prawns 15count', 'Fresh tiger prawns 15count per kg , after cleaning 1kg will get 450g-550g', 1300.00, 15, 'Prawns', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776691079_69e6278752e25.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Low in calories\",\"High protein content\",\"Good source of selenium\",\"Supports muscle growth\"]', '[\"Lean protein source\",\"Low fat content\",\"Vitamin B12 and niacin\",\"Phosphorus and selenium\"]', '[\"Prawn curry dishes\",\"Stir-fry preparations\",\"Appetizers and snacks\",\"Biryani and rice dishes\"]', 'gram', 'per_500g', 0, 0, NULL, NULL, NULL),
(5, 'Komara parai meen', 'Fresh Komara parai meen ( 1 -2 ) count per kg , After cleaning will get 650 - 800g with Head', 500.00, 13, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631628_69e53f4cbb757.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(6, 'Udan meen', 'Fresh Udan meen 6c-8c per kg , after cleaning 1kg will get 750g-850g with head', 550.00, 15, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631392_69e53e6048f2f.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'gram', 'per_500g', 0, 0, NULL, NULL, NULL),
(7, 'Tuna / Keerai meen cube\'s', 'Keerai meen boneless , 1kg of fresh keerai meen boneless , cube\'s ( without -head , bone, skin )', 680.00, 75, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631074_69e53d22646bc.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(8, 'Kalavan / Grouper fish', 'Fresh kalavan meen 1-2c per kg , after cleaning 700g-800g with head', 750.00, 55, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776630737_69e53bd1b9735.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(9, 'Tiger Prawn 20 count', 'Fresh Tiger prawns 20 count per kg , after cleaning will get 450g-550g', 480.00, 20, 'Prawns', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776690693_69e62605e1f75.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Low in calories\",\"High protein content\",\"Good source of selenium\",\"Supports muscle growth\"]', '[\"Lean protein source\",\"Low fat content\",\"Vitamin B12 and niacin\",\"Phosphorus and selenium\"]', '[\"Prawn curry dishes\",\"Stir-fry preparations\",\"Appetizers and snacks\",\"Biryani and rice dishes\"]', 'gram', 'per_500g', 0, 0, NULL, NULL, NULL),
(10, 'Big live mud crab 1 count', 'Fresh Live Mud crab 1kg size ( per kg 1 pies ) Order will deliver Lively', 1799.00, 30, 'Crabs', 'Kutty Soora', 'per_kg', 'pre_order', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776707299_69e666e3f0508.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Rich in protein\",\"Low in saturated fat\",\"Good source of zinc\",\"Supports immune system\"]', '[\"High protein\",\"low calorie\",\"Omega-3 fatty acids\",\"Vitamin B12 and folate\",\"Copper and zinc minerals\"]', '[\"Crab curry preparations\",\"Steam cooking\",\"Crab soup making\",\"Traditional coastal dishes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(11, 'Leather Jacket fish', 'Fresh Leather Jacket fish After Cleaning will ( 700g - 750g ) with Head', 650.00, 23, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776630565_69e53b25600f0.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(12, 'Sand Lobster', 'Fresh sand Lobster , 5-7 count of lobster per kg', 700.00, 35, 'Squids and Lobsters', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776709095_69e66de766ed1.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Rich in protein\",\"Low calorie seafood\",\"Good source of minerals\",\"Supports brain health\"]', '[\"High protein content\",\"Low fat and calories\",\"Vitamin B12 and selenium\",\"Phosphorus and copper\"]', '[\"Grilled preparations\",\"Curry and gravy dishes\",\"Fried and roasted\",\"Premium seafood recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(13, 'Cuttlefish ( Squid Family Seafoods)', 'Fresh cuttlefish 1pie 1kg size , 1kg after cleaning will get 600g-700g( flesh with cuttlefish leg', 650.00, 75, 'Squids and Lobsters', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776708594_69e66bf2e21c1.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(14, 'Mahi fish Fillet ( boneless )', 'Fresh Mahi fish fillet , 1kg of without head , bone and skin ( Fish Finger, Fish Tikka Cubes )', 550.00, 45, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776630341_69e53a451f74e.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(15, 'Mahi fish  / Parala meen', 'Fresh Mahi fish , after cleaning will get 700g-800g with head', 550.00, 20, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776630110_69e5395ebfda7.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(16, 'White Shark / Paul Sura Cubes', 'Fresh white milk shark will get 1kg of curry cut cube each cube 100g-70g size( 10c -15c of cubes per kg', 1100.00, 38, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776629710_69e537ce4dc00.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '1 Kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(17, 'Black koduva meen', 'Fresh black koduva fish 1kg fish after clean 650g-800g with head', 650.00, 30, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776629528_69e537186b98a.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '1 Kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(18, 'Red Koduva meen', 'Fresh black koduva fish 1kg fish after clean 650g-800g with head', 750.00, 17, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776628620_69e5338c758e4.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(19, 'Asal Koduva meen Steaks', 'Fresh sea bass / asal koduva meen \n steaks without head only fish ( cubes ) 15 - 20 pie of steaks for per kg', 800.00, 25, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776628111_69e5318f5d9fa.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(20, 'Chinese pomfert fish', 'Fresh chinese fish , 500g-1kg&up size, 1kg after cleaning will get 800g-900gwith head', 900.00, 18, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776627713_69e53001726a7.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(21, 'Tiger Prawn\'s 30 count', 'Fresh Sea Tiger Prawns (30 count of prawns per kg) After cleaning 1kg ,will get 500g of prawns', 410.00, 25, 'Prawns', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776690294_69e6247645202.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Low in calories\",\"High protein content\",\"Good source of selenium\",\"Supports muscle growth\"]', '[\"Lean protein source\",\"Low fat content\",\"Vitamin B12 and niacin\",\"Phosphorus and selenium\"]', '[\"Prawn curry dishes\",\"Stir-fry preparations\",\"Appetizers and snacks\",\"Biryani and rice dishes\"]', 'gram', 'per_500g', 0, 0, NULL, NULL, NULL),
(22, 'White pomfret fish', 'Fresh white vaval meen , 3-5 count of fish per kg , after cleaning kg will get 800g-900g with head', 900.00, 10, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776627430_69e52ee64d839.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(23, 'Black pomfret / vaval meen', 'Fresh black pomfert fish , after cleaning 1kg will get 800g with head', 550.00, 40, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776627190_69e52df657213.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(24, 'Large Sea white prawns', 'Fresh  Big sea white prawns 20c to 30 count per kg , after cleaning 1kg will get 450g-500g', 850.00, 30, 'Prawns', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776690188_69e6240cf27da.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Low in calories\",\"High protein content\",\"Good source of selenium\",\"Supports muscle growth\"]', '[\"Lean protein source\",\"Low fat content\",\"Vitamin B12 and niacin\",\"Phosphorus and selenium\"]', '[\"Prawn curry dishes\",\"Stir-fry preparations\",\"Appetizers and snacks\",\"Biryani and rice dishes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(25, 'Kadalveral / cobia fish ( fish finger )', 'Fresh kadalveral fish finger , boneless without ( bone , skin, ) only fish fillet', 900.00, 20, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776626289_69e52a716be01.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(26, 'Kadalveral meen / cobia ( slice )', 'Fresh kadalveral hook fish slice without head, 10-15 count of per kg', 750.00, 50, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776626079_69e5299f129df.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(27, 'Kadalveral meen / cobia full Fish', 'Fresh kadalveral meen , Full fish 5kg size fish , after cleaning for 1kg ,150g - 250g will less ( wastage)', 4250.00, 80, 'Fish', 'Kutty Soora', 'per_5kg', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625861_69e528c513a70.jpg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(28, 'Vanjaram / king Fish curry cut(cubes)', '- Fresh hooked Vanjaram fish curry cut , each cubes 30g -40g size, for 1kg 20 - 25 count of cubes / for half kg 10 -15c of cubes', 950.00, 16, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625613_69e527cdb72fd.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(29, 'Vanjaram / king Fish Slice', 'Fresh Hook fish Big  slice ( without head ) , 10-12count of slice per kg', 1050.00, 50, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625249_69e5266114dde.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(30, 'Vanjaram meen Full Fish', 'Fresh Hook Vanjaram fish , Full fish 5kg Size ( mavalasi vanjaram meen ), For per kg 150g-200g wastage will less', 6999.00, 50, 'Fish', 'Kutty Soora', 'per_5kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631936_69e540800e841.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(31, 'live Mud Crabs ( 2 count per kg )', 'Live green mud crab , counts per kgs (2c per kg size) Crabs will deliver lively', 1499.00, 20, 'Crabs', 'Kutty Soora', 'per_kg', 'pre_order', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776707169_69e66661aab2d.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Rich in protein\",\"Low in saturated fat\",\"Good source of zinc\",\"Supports immune system\"]', '[\"High protein\",\"low calorie\",\"Omega-3 fatty acids\",\"Vitamin B12 and folate\",\"Copper and zinc minerals\"]', '[\"Crab curry preparations\",\"Steam cooking\",\"Crab soup making\",\"Traditional coastal dishes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(32, 'Sheela meen', 'Fresh Sheela meen , 1&up size fish , after cleaning 1kg will get 800g-900g with head', 850.00, 25, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776625028_69e52584812b4.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(33, 'Nakku meen / Nair meen', 'Fresh nakku meen / nair meen 1&up size fish , after cleaning 1kg will get 700g-800g without head', 820.00, 50, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624713_69e5244977d8a.jpg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(34, 'Indian salmon / kala meen', 'Fresh kala meen , 500g size fish , after cleaning 1kg will get 700g-800g with head', 1099.00, 40, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510539_69e3664b124b1.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '1 Kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(35, 'Lady fish / keelanga meen', 'Fresh keelanga meen 15-20count per kg , after cleaning 1kg will get 600g-700g without head', 425.00, 40, 'Fish', 'Kutty Soora', 'per_500g', 'out_of_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776631973_69e540a5419dd.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(36, 'squid', 'Fresh Squid Mid size of squid 22-30c ,1kg after cleaning will get 700g-800g with squid leg', 550.00, 40, 'Squids and Lobsters', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776708373_69e66b15e95cd.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '1 Kg', '[\"Rich in protein\",\"Low calorie seafood\",\"Good source of minerals\",\"Supports brain health\"]', '[\"High protein content\",\"Low fat and calories\",\"Vitamin B12 and selenium\",\"Phosphorus and copper\"]', '[\"Grilled preparations\",\"Curry and gravy dishes\",\"Fried and roasted\",\"Premium seafood recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(37, 'Sea Shrimps prawns', 'Fresh kadal eral mid-size 40c to 60c per kg. 1kg of Prawn After cleaning will get 450g-550g', 550.00, 45, 'Prawns', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776689997_69e6234d569be.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Low in calories\",\"High protein content\",\"Good source of selenium\",\"Supports muscle growth\"]', '[\"Lean protein source\",\"Low fat content\",\"Vitamin B12 and niacin\",\"Phosphorus and selenium\"]', '[\"Prawn curry dishes\",\"Stir-fry preparations\",\"Appetizers and snacks\",\"Biryani and rice dishes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(38, 'Live Crabs 4count', 'Fresh Live crabs ( 4 crab\'s for per kg ) crabs will deliver lively', 1099.00, 30, 'Crabs', 'Kutty Soora', 'per_kg', 'pre_order', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776707008_69e665c0ebcb4.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Rich in protein\",\"Low in saturated fat\",\"Good source of zinc\",\"Supports immune system\"]', '[\"High protein\",\"low calorie\",\"Omega-3 fatty acids\",\"Vitamin B12 and folate\",\"Copper and zinc minerals\"]', '[\"Crab curry preparations\",\"Steam cooking\",\"Crab soup making\",\"Traditional coastal dishes\"]', 'piece', 'per_500g', 0, 0, NULL, NULL, NULL),
(39, 'Thenga parai meen', 'Fresh Thenga parai meen 500g-1&up size fish, after cleaning will 700g-800g with head', 425.00, 110, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510432_69e365e00a89f.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(40, 'Korkai meen', 'Fresh korkai meen 500g-1&up size fish , after cleaning 1kg will get 700g-800g with head', 430.00, 25, 'Fish', 'Kutty Soora', 'per_500g', 'out_of_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776510391_69e365b780718.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(41, 'Sankara meen', 'Fresh sankara meen 8-10count of fish per kg,after cleaning 1kg will get 700g-800g with head', 300.00, 35, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776624038_69e521a6e1dca.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(42, 'Mathi meen', 'Fresh Mathi meen 1kg after cleaning will get 500g-650g without head', 250.00, 30, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776508890_69e35fda3a06a.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(43, 'kavalai meen', 'Fresh kavalai meen , 1kg after cleaning will get 600g-700g without head', 350.00, 40, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"3kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776507214_69e3594e4ccf9.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '1 Kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'gram', 'per_kg', 0, 0, NULL, NULL, NULL),
(44, 'Ayala / kanakeluthi meen', 'Fresh ayala 5-7count of fish per kg ,after cleaning 1kg will get 700g-800g', 440.00, 30, 'Fish', 'Kutty Soora', 'per_kg', 'in_stock', '{\"weight\":\"half / 1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776506824_69e357c89fa4e.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '1 Kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Traditional recipes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(45, 'Big Nethili meen', 'Fresh Nethili meen 1kg of fish after cleaning will get 600g-700g without head', 299.00, 30, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"half /1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777972728_69f9b5f824f44.jpeg', '[\"seafood\",\"fresh\"]', '2025-10-18', '2025-10-18', NULL, NULL, NULL, '500g (Half Kg)', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipes\"]', 'kg', 'per_500g', 0, 0, NULL, NULL, NULL),
(46, 'Romeo Sankara meen', 'Fresh  Big romeo sankara meen 5-7count of fish in per kg , after cleaning will get 700g-800g with Head', 350.00, 20, 'Fish', 'Kutty Soora', 'per_500g', 'in_stock', '{\"weight\":\"half /1kg\",\"dimensions\":\"\",\"material\":\"Fresh seafood\",\"color\":\"\"}', 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776485945_69e3063942da1.jpeg', '[\"seafood\",                                \"fresh\"]', '2025-10-18', '2025-10-18', 0.00, '', NULL, 'per_500g', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipe\"]', 'gram', 'per_kg', 0, 0, NULL, NULL, NULL),
(63, 'Sea Red Sweet Crabs', 'Fresh Red Sweet Crab\'s ( 15 to 20 count of crabs per kg )', 450.00, 35, 'Crabs', 'Kutty Soora', 'per_kg', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776706848_69e665204d715.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', '[\"Rich in protein\",\"Low in saturated fat\",\"Good source of zinc\",\"Supports immune system\"]', '[\"High protein\",\"low calorie\",\"Omega-3 fatty acids\",\"Vitamin B12 and folate\",\"Copper and zinc minerals\"]', '[\"Crab curry preparations\",\"Steam cooking\",\"Crab soup making\",\"Traditional coastal dishes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(64, 'Sea blue crabs', 'Fresh blue crabs 4 to 6count crabs per kg ,', 430.00, 20, 'Crabs', 'Kutty Soora', 'per_500g', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776706744_69e664b89c073.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', '[\"Rich in protein\",\"Low in saturated fat\",\"Good source of zinc\",\"Supports immune system\"]', '[\"High protein\",\"low calorie\",\"Omega-3 fatty acids\",\"Vitamin B12 and folate\",\"Copper and zinc minerals\"]', '[\"Crab curry preparations\",\"Steam cooking\",\"Crab soup making\",\"Traditional coastal dishes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(65, 'Sea Crabs / 3dot crabs', 'Fresh sea Crabs 6-10 count of crabs per kg ,', 325.00, 20, 'Crabs', 'Kutty Soora', 'per_500g', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776706517_69e663d529e9c.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', '[\"Rich in protein\",\"Low in saturated fat\",\"Good source of zinc\",\"Supports immune system\"]', '[\"High protein\",\"low calorie\",\"Omega-3 fatty acids\",\"Vitamin B12 and folate\",\"Copper and zinc minerals\"]', '[\"Crab curry preparations\",\"Steam cooking\",\"Crab soup making\",\"Traditional coastal dishes\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(66, 'Green Mussels', 'Fresh Green Mussels ( mid size mussels 20 to 30 and above count for per kg )\nif Customer will Buy without Shell easy to cook \nif customer will Buy With Shell - you have boiled in water then Take the Meat and cook with own recipes', 650.00, 20, 'Special Seafoods', 'Kutty Soora', 'per_kg', 'pre_order', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776960481_69ea43e11419b.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', '[\"Improved heart health\",\"Better brain function\",\"Strong bones\",\"Reduced inflammation\"]', '[\"High quality protein\",\"Rich in omega-3 fatty acids\",\"Vitamins D and B12\",\"Minerals: Iodine\",\"zinc\",\"selenium\"]', '[\"Fish curry preparation\",\"Grilling and frying\",\"Soup and stew making\",\"Traditional recipe\"]', 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(67, 'Octopus', 'Fresh octopus fish ( 5 to 7 count for per kg )', 699.00, 30, 'Special Seafoods', 'Kutty Soora', 'per_kg', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776710876_69e674dc0ae77.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(68, 'Fish Egg / Meen Senai', 'Fresh vanjaram , Paarai , kadalveral , Mahi and More big Fish Egg', 399.00, 2, 'Special Seafoods', 'Kutty Soora', 'per_250g', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776711813_69e67885dcaa8.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(69, 'Big Sea Snail', 'Fresh Big Sea Snail ( only snail will deliver because shell is very hard to break and more complicated', 899.00, 5, 'Special Seafoods', 'Kutty Soora', 'per_kg', 'pre_order', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776959410_69ea3fb224a21.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(70, 'Vanjaram Fish Head', 'Fresh Big Hook fish Head ( 5 to 8 kg size fish Head )', 799.00, 5, 'Special Seafoods', 'Kutty Soora', 'per_kg', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1776960387_69ea4383cce4d.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(71, 'Dry Karapodi meen / Karuvadu', 'Fresh Dry Seafoods ( Fresh fish to dry fish , and it will takes 2 to 3 days to become Fresh Dry fish/seafoods )', 349.00, 20, 'Dry Seafoods', 'Kutty Soora', 'per_500g', 'out_of_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777973244_69f9b7fc16fe4.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(72, 'Thelalparai meen / Karuvadu', 'Fresh Dry Seafoods ( Fresh fish to dry fish , and it will takes 2 to 3 days to become Fresh Dry fish/seafoods )', 199.00, 10, 'Dry Seafoods', 'Kutty Soora', 'per_250g', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777973776_69f9ba1054b5e.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL),
(73, 'Dry vanjaram meen / karuvadu', 'Fresh Dry Seafoods ( Fresh fish to dry fish , and it will takes 2 to 3 days to become Fresh Dry fish/seafoods )', 399.00, 12, 'Dry Seafoods', 'Kutty Soora', 'per_250g', 'in_stock', NULL, 'https://kuttysoora.com/kuttysoora_seafood/backend/images/product_1777974153_69f9bb8969314.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'per_kg', NULL, NULL, NULL, 'kg', 'per_kg', 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_title` varchar(200) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `is_verified_purchase` tinyint(1) DEFAULT 0,
  `is_approved` tinyint(1) DEFAULT 0,
  `helpful_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate_limiting`
--

CREATE TABLE `rate_limiting` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `request_count` int(11) DEFAULT 1,
  `window_start` datetime NOT NULL,
  `last_request` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rate_limiting`
--

INSERT INTO `rate_limiting` (`id`, `ip_address`, `endpoint`, `request_count`, `window_start`, `last_request`) VALUES
(1, '::1', '/login.php', 1, '2025-12-07 02:21:16', '2025-12-07 02:21:16'),
(2, '::1', '/login.php', 1, '2025-12-07 02:48:58', '2025-12-07 02:48:58'),
(3, '::1', '/login.php', 1, '2025-12-07 02:50:04', '2025-12-07 02:50:04'),
(4, '::1', '/login.php', 1, '2025-12-07 02:56:33', '2025-12-07 02:56:33'),
(5, '::1', '/login.php', 1, '2025-12-07 02:57:17', '2025-12-07 02:57:17'),
(6, '::1', '/login.php', 1, '2025-12-07 03:04:10', '2025-12-07 03:04:10'),
(7, '::1', '/login.php', 1, '2025-12-07 04:04:15', '2025-12-07 04:04:15'),
(8, '::1', '/login.php', 1, '2025-12-07 04:42:11', '2025-12-07 04:42:11'),
(9, '::1', '/login.php', 1, '2025-12-07 04:42:48', '2025-12-07 04:42:48'),
(10, '::1', '/login.php', 1, '2025-12-07 04:58:56', '2025-12-07 04:58:56'),
(11, '::1', '/login.php', 1, '2025-12-10 10:19:33', '2025-12-10 10:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `refund_requests`
--

CREATE TABLE `refund_requests` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','completed','rejected') DEFAULT 'pending',
  `razorpay_refund_id` varchar(255) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`event_data`)),
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `request_method` varchar(10) DEFAULT NULL,
  `request_uri` varchar(500) DEFAULT NULL,
  `response_code` int(11) DEFAULT NULL,
  `execution_time` decimal(8,4) DEFAULT NULL,
  `memory_usage` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `security_logs`
--

INSERT INTO `security_logs` (`id`, `user_id`, `event_type`, `event_data`, `ip_address`, `user_agent`, `request_method`, `request_uri`, `response_code`, `execution_time`, `memory_usage`, `created_at`) VALUES
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`event_data`)),
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `request_method` varchar(10) DEFAULT NULL,
  `request_uri` varchar(500) DEFAULT NULL,
  `response_code` int(11) DEFAULT NULL,
  `execution_time` decimal(8,4) DEFAULT NULL,
  `memory_usage` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `event_type`, `event_data`, `ip_address`, `user_agent`, `request_method`, `request_uri`, `response_code`, `execution_time`, `memory_usage`, `created_at`) VALUES
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23'),
(1, NULL, 'database_migration_completed', '{\"version\": \"2.0-safe\", \"timestamp\": \"2025-10-24 02:52:23\", \"tables_created\": 10, \"foreign_keys\": \"optional\"}', 'localhost', 'Database Migration Script v2.0-safe', NULL, NULL, NULL, NULL, NULL, '2025-10-24 02:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `email_verified` tinyint(1) DEFAULT 0,
  `mobile_verified` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user',
  `password_hash` varchar(255) DEFAULT NULL,
  `address` text DEFAULT '',
  `house` varchar(100) DEFAULT '',
  `street` varchar(100) DEFAULT '',
  `area` varchar(100) DEFAULT '',
  `city` varchar(100) DEFAULT '',
  `pin_code` varchar(10) DEFAULT '',
  `landmark` varchar(255) DEFAULT '',
  `referral` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `is_admin`, `email_verified`, `mobile_verified`, `status`, `last_login`, `login_attempts`, `locked_until`, `created_at`, `updated_at`, `role`, `password_hash`, `address`, `house`, `street`, `area`, `city`, `pin_code`, `landmark`, `referral`) VALUES
(1, 'fazil', 'fazil@gmail.com', '9876543210', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-11-15 05:18:36', '2026-08-05 04:26:46', 'admin', '$argon2id$v=19$m=65536,t=4,p=1$V3VrSFZmSTNxb28xT3JTWA$d5FxIMT0wayCE7gHkP5rsBX17wObohevNris0m/HEkk', '121, abc street, old washermenpet, chennai - 600021', '121', 'abc street', 'old washermenpet', 'chennai', '600021', '', ''),
(2, 'fazil', '', '8428617202', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-11-27 03:42:31', '2026-04-21 10:36:16', 'user', NULL, '121, test, test, chen - 123456 (Near cca2312431)', '121', 'test', 'test', 'chen', '123456', 'cca2312431', 'ascdafdefeqwfe'),
(3, 'FAZIL', '98/76543210@placeholder.local', '98/76543210', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-11-28 01:51:16', '2025-11-28 01:51:16', 'user', NULL, '', '', '', '', '', '', '', ''),
(4, 'ameen', '84286917202@placeholder.local', '84286917202', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-11-28 02:52:52', '2025-11-28 02:52:52', 'user', NULL, '', '', '', '', '', '', '', ''),
(5, 'fazil', '84286217202@placeholder.local', '84286217202', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-02 06:59:28', '2025-12-02 06:59:28', 'user', NULL, '', '', '', '', '', '', '', ''),
(6, 'Naresh', '9176246392@placeholder.local', '9176246392', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-25 09:05:36', '2025-12-25 09:05:36', 'user', NULL, '', '', '', '', '', '', '', ''),
(7, 'Siva siva', '6381363925@placeholder.local', '6381363925', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-25 09:15:45', '2025-12-30 15:41:10', 'user', NULL, '', '', '', '', '', '', '', ''),
(8, 'Lakshmi', '8939497811@placeholder.local', '8939497811', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-25 16:32:29', '2026-04-14 15:31:04', 'user', NULL, '', '', '', '', '', '', '', ''),
(9, 'Naresh Ragavendra', '6318363928@placeholder.local', '6318363928', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-26 15:54:25', '2025-12-26 15:54:25', 'user', NULL, '', '', '', '', '', '', '', ''),
(10, 'harish', '9361859654@placeholder.local', '9361859654', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-26 16:00:15', '2025-12-26 16:00:15', 'user', NULL, '', '', '', '', '', '', '', ''),
(11, 'karan', '9176351314@placeholder.local', '9176351314', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-27 07:40:12', '2025-12-27 07:40:12', 'user', NULL, '', '', '', '', '', '', '', ''),
(12, 'ash', '9360341528@placeholder.local', '9360341528', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-29 06:43:10', '2025-12-29 06:43:10', 'user', NULL, '', '', '', '', '', '', '', ''),
(13, 'RKD', '8778695766@placeholder.local', '8778695766', '', 0, 0, 0, 'active', NULL, 0, NULL, '2025-12-29 08:40:58', '2025-12-29 08:40:58', 'user', NULL, '', '', '', '', '', '', '', ''),
(14, 'jeevaroshini', 'JeevaRoshini4@gmal.com', '8637671775', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-01-01 20:41:18', '2026-05-07 09:26:06', 'user', NULL, 'no 41, jayalakshmi nagar, nehruji street, nerkundram - 600107 (Near vinayagar kovil)', 'no 41', 'jayalakshmi nagar', 'nehruji street', 'nerkundram', '600107', 'vinayagar kovil', ''),
(15, 'kuttysoora', '9962463925@placeholder.local', '9962463925', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-14 06:07:50', '2026-04-14 06:07:50', 'user', NULL, '', '', '', '', '', '', '', ''),
(16, 'Asif', '9176782584@placeholder.local', '9176782584', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-14 07:54:52', '2026-04-14 07:54:52', 'user', NULL, '', '', '', '', '', '', '', ''),
(17, 'Akthar', '9087471613@placeholder.local', '9087471613', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-14 07:55:08', '2026-04-14 07:55:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(18, 'kumar', '9176246393@placeholder.local', '9176246393', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-15 03:22:39', '2026-04-15 03:22:39', 'user', NULL, '', '', '', '', '', '', '', ''),
(19, 'va naiddey', '7550152660@placeholder.local', '7550152660', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-16 18:35:26', '2026-04-16 18:35:26', 'user', NULL, '', '', '', '', '', '', '', ''),
(20, 'Jaideep Nischinth', '7397242777@placeholder.local', '7397242777', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-18 07:07:45', '2026-04-18 07:07:45', 'user', NULL, '', '', '', '', '', '', '', ''),
(21, 'Kamal Haran', '7708459895@placeholder.local', '7708459895', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-19 17:47:11', '2026-07-26 15:36:34', 'user', NULL, '', '', '', '', '', '', '', ''),
(22, 'karthik', '9080863446@placeholder.local', '9080863446', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-20 01:51:55', '2026-04-20 01:51:55', 'user', NULL, '', '', '', '', '', '', '', ''),
(23, 'geetha', '9841329565@placeholder.local', '9841329565', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-21 19:17:45', '2026-04-21 19:17:45', 'user', NULL, '', '', '', '', '', '', '', ''),
(24, 'DHILIP KUMAR', '9600156026@placeholder.local', '9600156026', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-04-22 10:47:04', '2026-04-22 10:47:04', 'user', NULL, '', '', '', '', '', '', '', ''),
(25, 'fazil', '9846543210@placeholder.local', '9846543210', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-03 11:30:17', '2026-05-03 11:30:17', 'user', NULL, '', '', '', '', '', '', '', ''),
(26, 'balaji', '8940988940@placeholder.local', '8940988940', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 09:57:09', '2026-05-07 09:57:09', 'user', NULL, '', '', '', '', '', '', '', ''),
(27, 'duvya', '9080836437@placeholder.local', '9080836437', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:31:29', '2026-05-17 06:56:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(28, 'Ashok', '9840572660@placeholder.local', '9840572660', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:34:58', '2026-05-07 10:34:58', 'user', NULL, '', '', '', '', '', '', '', ''),
(29, 'Saranya', '7200364637@placeholder.local', '7200364637', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:36:46', '2026-05-07 10:36:46', 'user', NULL, '', '', '', '', '', '', '', ''),
(30, 'Saranya', '7200364637@placeholder.local', '7200364637', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:36:46', '2026-05-07 10:36:46', 'user', NULL, '', '', '', '', '', '', '', ''),
(31, 'k.supraja', '8668193399@placeholder.local', '8668193399', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:36:58', '2026-05-07 10:36:58', 'user', NULL, '', '', '', '', '', '', '', ''),
(32, 'Ajay kumar', '8939739702@placeholder.local', '8939739702', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:42:36', '2026-05-07 10:42:36', 'user', NULL, '', '', '', '', '', '', '', ''),
(33, 'parkavi', '9600926614@placeholder.local', '9600926614', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:44:53', '2026-05-07 10:44:53', 'user', NULL, '', '', '', '', '', '', '', ''),
(34, 'varsha', '9943925401@placeholder.local', '9943925401', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:45:58', '2026-05-07 10:45:58', 'user', NULL, '', '', '', '', '', '', '', ''),
(35, 'pavithra', '8778584867@placeholder.local', '8778584867', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:46:24', '2026-05-07 10:46:24', 'user', NULL, '', '', '', '', '', '', '', ''),
(36, 'induja ram', '8610732927@placeholder.local', '8610732927', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:48:14', '2026-06-30 14:33:04', 'user', NULL, '', '', '', '', '', '', '', ''),
(37, 'Indu Ram', '8610732927@placeholder.local', '8610732927', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:48:14', '2026-05-07 10:48:14', 'user', NULL, '', '', '', '', '', '', '', ''),
(38, 'Raji', '9962771111@placeholder.local', '9962771111', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:49:15', '2026-05-07 10:49:15', 'user', NULL, '', '', '', '', '', '', '', ''),
(39, 'Arthi', '8778093988@placeholder.local', '8778093988', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:51:02', '2026-05-07 10:51:02', 'user', NULL, '', '', '', '', '', '', '', ''),
(40, 'sowmya', '9710502617@placeholder.local', '9710502617', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 10:54:14', '2026-05-07 10:54:14', 'user', NULL, '', '', '', '', '', '', '', ''),
(41, 'meena', '7305604333@placeholder.local', '7305604333', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:00:23', '2026-05-07 11:00:23', 'user', NULL, '', '', '', '', '', '', '', ''),
(42, 'Lilly rose', '7358693200@placeholder.local', '7358693200', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:01:00', '2026-05-07 11:01:00', 'user', NULL, '', '', '', '', '', '', '', ''),
(43, 'sneha', '8056772839@placeholder.local', '8056772839', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:01:53', '2026-05-07 11:01:53', 'user', NULL, '', '', '', '', '', '', '', ''),
(44, 'Aravinth', '9751377411@placeholder.local', '9751377411', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:02:10', '2026-05-07 11:02:10', 'user', NULL, '', '', '', '', '', '', '', ''),
(45, 'Priyanka', '9363382851@placeholder.local', '9363382851', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:02:25', '2026-05-07 11:02:25', 'user', NULL, '', '', '', '', '', '', '', ''),
(46, 'tharika', '9600121947@placeholder.local', '9600121947', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:02:45', '2026-05-07 11:02:45', 'user', NULL, '', '', '', '', '', '', '', ''),
(47, 'rohini', '9962734989@placeholder.local', '9962734989', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:06:42', '2026-05-07 11:06:42', 'user', NULL, '', '', '', '', '', '', '', ''),
(48, 'vishal', '8637455587@placeholder.local', '8637455587', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:15:50', '2026-05-07 11:15:50', 'user', NULL, '', '', '', '', '', '', '', ''),
(49, 'karthiga A', '9384416811@placeholder.local', '9384416811', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:18:25', '2026-05-09 23:55:16', 'user', NULL, '', '', '', '', '', '', '', ''),
(50, 'Sangeetha Rajkumar', '9342857149@placeholder.local', '9342857149', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:19:01', '2026-05-07 11:19:01', 'user', NULL, '', '', '', '', '', '', '', ''),
(51, 'Ruby', '7904712515@placeholder.local', '7904712515', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:19:31', '2026-05-07 11:19:31', 'user', NULL, '', '', '', '', '', '', '', ''),
(52, 'sumathi', '8098245339@placeholder.local', '8098245339', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:37:31', '2026-05-07 11:37:31', 'user', NULL, '', '', '', '', '', '', '', ''),
(53, 'hari', '9710223959@placeholder.local', '9710223959', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:42:26', '2026-05-07 11:42:26', 'user', NULL, '', '', '', '', '', '', '', ''),
(54, 'Siva', '8838946602@placeholder.local', '8838946602', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:54:57', '2026-05-07 11:54:57', 'user', NULL, '', '', '', '', '', '', '', ''),
(55, 'joshna', '9042873622@placeholder.local', '9042873622', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 11:59:07', '2026-05-07 11:59:07', 'user', NULL, '', '', '', '', '', '', '', ''),
(56, 'Office', '9994630843@placeholder.local', '9994630843', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 12:30:11', '2026-05-07 12:30:11', 'user', NULL, '', '', '', '', '', '', '', ''),
(57, 'Princy', '9003201695@placeholder.local', '9003201695', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 12:40:34', '2026-05-07 12:40:34', 'user', NULL, '', '', '', '', '', '', '', ''),
(58, 'poojashanth', '9042409636@placeholder.local', '9042409636', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 12:46:23', '2026-05-20 05:20:17', 'user', NULL, '', '', '', '', '', '', '', ''),
(59, 'Gokul', '8883838512@placeholder.local', '8883838512', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 12:52:38', '2026-05-07 12:52:38', 'user', NULL, '', '', '', '', '', '', '', ''),
(60, 'bavanthika', '7305549951@placeholder.local', '7305549951', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 12:59:00', '2026-05-07 12:59:00', 'user', NULL, '', '', '', '', '', '', '', ''),
(61, 'Chitra Haribabu', '9962168286@placeholder.local', '9962168286', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 13:22:55', '2026-05-07 13:22:55', 'user', NULL, '', '', '', '', '', '', '', ''),
(62, 'visha', '8637490065@placeholder.local', '8637490065', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 13:30:58', '2026-05-07 13:30:58', 'user', NULL, '', '', '', '', '', '', '', ''),
(63, 'Keerthi', '9940061377@placeholder.local', '9940061377', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 14:36:00', '2026-05-07 14:36:00', 'user', NULL, '', '', '', '', '', '', '', ''),
(64, 'kokila vijay', '7550241028@placeholder.local', '7550241028', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 15:03:24', '2026-05-07 15:03:24', 'user', NULL, '', '', '', '', '', '', '', ''),
(65, 'padmavathy', '9597335615@placeholder.local', '9597335615', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 15:16:08', '2026-05-07 15:16:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(66, 'thanigachalam', '8925622074@placeholder.local', '8925622074', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 16:19:47', '2026-05-07 16:19:47', 'user', NULL, '', '', '', '', '', '', '', ''),
(67, 'priya', '6385469724@placeholder.local', '6385469724', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 17:22:18', '2026-05-07 17:22:18', 'user', NULL, '', '', '', '', '', '', '', ''),
(68, 'Priyanka Gopal', '9962289142@placeholder.local', '9962289142', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 17:27:03', '2026-05-07 17:27:03', 'user', NULL, '', '', '', '', '', '', '', ''),
(69, 'malar', '9940451269@placeholder.local', '9940451269', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 17:29:42', '2026-05-07 17:29:42', 'user', NULL, '', '', '', '', '', '', '', ''),
(70, 'Nithu', '9092010487@placeholder.local', '9092010487', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 18:34:08', '2026-05-07 18:34:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(71, 'caroline', '9962437318@placeholder.local', '9962437318', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 18:58:57', '2026-05-07 18:58:57', 'user', NULL, '', '', '', '', '', '', '', ''),
(72, 'santhoshini', '8072815250@placeholder.local', '8072815250', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 19:21:13', '2026-05-07 19:21:13', 'user', NULL, '', '', '', '', '', '', '', ''),
(73, 'Suresh r', 'sureshr1896@gmail.com', '8098986394', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-07 22:17:01', '2026-05-07 22:26:38', 'user', NULL, '13/6-66 road number 02, road number 02, Badangpet, Hyderabad - 500058', '13/6-66 road number 02', 'road number 02', 'Badangpet', 'Hyderabad', '500058', '', ''),
(74, 'Narayana moorthy', '7305141405@placeholder.local', '7305141405', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 01:10:59', '2026-05-08 01:10:59', 'user', NULL, '', '', '', '', '', '', '', ''),
(75, 'Parthiba Raja', '9551055577@placeholder.local', '9551055577', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 02:44:47', '2026-05-08 02:44:47', 'user', NULL, '', '', '', '', '', '', '', ''),
(76, 'Kumar', '6381363924@placeholder.local', '6381363924', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 06:59:53', '2026-05-08 06:59:53', 'user', NULL, '', '', '', '', '', '', '', ''),
(77, 'lavanya', '8056342055@placeholder.local', '8056342055', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 07:15:31', '2026-05-08 07:15:31', 'user', NULL, '', '', '', '', '', '', '', ''),
(78, 'Rajkumar', '7299661652@placeholder.local', '7299661652', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 07:26:37', '2026-05-08 07:26:37', 'user', NULL, '', '', '', '', '', '', '', ''),
(79, 'archana sekar', '9962413605@placeholder.local', '9962413605', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 07:28:24', '2026-05-08 07:28:24', 'user', NULL, '', '', '', '', '', '', '', ''),
(80, 'stella', '9790726741@placeholder.local', '9790726741', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 07:43:14', '2026-05-08 07:43:14', 'user', NULL, '', '', '', '', '', '', '', ''),
(81, 'lakshmanan', '9952763334@placeholder.local', '9952763334', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 11:03:07', '2026-05-08 11:03:07', 'user', NULL, '', '', '', '', '', '', '', ''),
(82, 'mohaan SD', '9003221615@placeholder.local', '9003221615', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 12:04:42', '2026-05-08 12:04:42', 'user', NULL, '', '', '', '', '', '', '', ''),
(83, 'nivetha', '9043645416@placeholder.local', '9043645416', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 13:51:02', '2026-05-08 13:51:02', 'user', NULL, '', '', '', '', '', '', '', ''),
(84, 'Hannah Nikita', '9566137557@placeholder.local', '9566137557', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 15:21:26', '2026-05-11 18:37:49', 'user', NULL, '', '', '', '', '', '', '', ''),
(85, 'Jagan', '9597102596@placeholder.local', '9597102596', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 15:36:40', '2026-05-08 15:36:40', 'user', NULL, '', '', '', '', '', '', '', ''),
(86, 'yamini', '9791061686@placeholder.local', '9791061686', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 16:04:44', '2026-05-08 16:04:44', 'user', NULL, '', '', '', '', '', '', '', ''),
(87, 'akila', '8667270277@placeholder.local', '8667270277', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 16:22:23', '2026-05-08 16:22:23', 'user', NULL, '', '', '', '', '', '', '', ''),
(88, 'kaar', '9087994202@placeholder.local', '9087994202', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 19:37:20', '2026-05-08 19:37:20', 'user', NULL, '', '', '', '', '', '', '', ''),
(89, 'sathta', '9345334464@placeholder.local', '9345334464', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-08 19:56:48', '2026-05-08 19:56:48', 'user', NULL, '', '', '', '', '', '', '', ''),
(90, 'Aravinth', '9754377411@placeholder.local', '9754377411', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-09 02:07:38', '2026-05-09 02:07:38', 'user', NULL, '', '', '', '', '', '', '', ''),
(91, 'sharon', '8547870888@placeholder.local', '8547870888', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-09 06:34:44', '2026-05-09 06:34:44', 'user', NULL, '', '', '', '', '', '', '', ''),
(92, 'Bhuvana', '8124206029@placeholder.local', '8124206029', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-09 11:35:51', '2026-05-09 11:35:51', 'user', NULL, '', '', '', '', '', '', '', ''),
(93, 'vimala', '9787022858@placeholder.local', '9787022858', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-09 13:41:40', '2026-05-09 13:41:40', 'user', NULL, '', '', '', '', '', '', '', ''),
(94, 'thiripu Prashanth', '', '9789032695', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-10 04:39:33', '2026-05-23 05:56:28', 'user', NULL, '40, Venkatesan street, Royapuram, Chennai - 600013 (Near next to mohan medical)', '40', 'Venkatesan street', 'Royapuram', 'Chennai', '600013', 'next to mohan medical', ''),
(95, 'deepika', '8072187966@placeholder.local', '8072187966', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-11 06:29:59', '2026-05-11 06:29:59', 'user', NULL, '', '', '', '', '', '', '', ''),
(96, 'amal', '7871012399@placeholder.local', '7871012399', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-12 06:00:50', '2026-06-16 06:25:32', 'user', NULL, '', '', '', '', '', '', '', ''),
(97, 'santhosh', '7418479708@placeholder.local', '7418479708', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-12 06:02:46', '2026-05-12 06:02:46', 'user', NULL, '', '', '', '', '', '', '', ''),
(98, 'ranjith', '9080420683@placeholder.local', '9080420683', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-12 07:55:07', '2026-05-12 07:55:07', 'user', NULL, '', '', '', '', '', '', '', ''),
(99, 'Ganeshprasad', '9952589030@placeholder.local', '9952589030', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-12 16:48:41', '2026-05-12 16:48:41', 'user', NULL, '', '', '', '', '', '', '', ''),
(100, 'ramakrishna', '9840214687@placeholder.local', '9840214687', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-15 09:08:37', '2026-05-28 08:56:00', 'user', NULL, '', '', '', '', '', '', '', ''),
(101, 'S.Aruna', '8124673822@placeholder.local', '8124673822', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-15 16:52:46', '2026-05-15 16:52:46', 'user', NULL, '', '', '', '', '', '', '', ''),
(102, 'Vignesh N', '7708665658@placeholder.local', '7708665658', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-16 16:28:04', '2026-05-16 16:28:04', 'user', NULL, '', '', '', '', '', '', '', ''),
(103, 'Aiswariyadevi', '9952448864@placeholder.local', '9952448864', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-19 13:45:41', '2026-05-19 13:45:41', 'user', NULL, '', '', '', '', '', '', '', ''),
(104, 'Aiswariyadevi', '9952448864@placeholder.local', '9952448864', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-19 13:45:41', '2026-05-19 13:45:41', 'user', NULL, '', '', '', '', '', '', '', ''),
(105, 'karthiga', '9384419811@placeholder.local', '9384419811', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-21 15:36:19', '2026-05-21 15:36:19', 'user', NULL, '', '', '', '', '', '', '', ''),
(106, 'Shyla', '7845169450@placeholder.local', '7845169450', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-23 00:09:23', '2026-05-23 00:09:23', 'user', NULL, '', '', '', '', '', '', '', ''),
(107, 'Maran', '9841106666@placeholder.local', '9841106666', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-23 12:50:30', '2026-05-23 12:50:30', 'user', NULL, '', '', '', '', '', '', '', ''),
(108, 'nandhu', '9791735968@placeholder.local', '9791735968', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-26 16:20:29', '2026-05-26 16:20:29', 'user', NULL, '', '', '', '', '', '', '', ''),
(109, 'srilakshmi', '9444175278@placeholder.local', '9444175278', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-05-29 14:09:24', '2026-05-29 14:09:24', 'user', NULL, '', '', '', '', '', '', '', ''),
(110, 'JAI', '7902060218@placeholder.local', '7902060218', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-01 12:55:48', '2026-06-01 12:55:48', 'user', NULL, '', '', '', '', '', '', '', ''),
(111, 'ushamaa home foods', '8925691056@placeholder.local', '8925691056', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-05 10:10:16', '2026-06-05 10:10:16', 'user', NULL, '', '', '', '', '', '', '', ''),
(112, 'Aafrin Fathima', '7397252628@placeholder.local', '7397252628', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-08 19:23:03', '2026-06-24 23:42:52', 'user', NULL, '', '', '', '', '', '', '', ''),
(113, 'amitha', '9841483786@placeholder.local', '9841483786', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-10 06:58:12', '2026-06-10 06:58:12', 'user', NULL, '', '', '', '', '', '', '', ''),
(114, 'Arul', '9095528559@placeholder.local', '9095528559', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-15 07:05:09', '2026-06-15 07:05:09', 'user', NULL, '', '', '', '', '', '', '', ''),
(115, 'pavithra', '9884049518@placeholder.local', '9884049518', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-16 07:56:56', '2026-06-16 07:56:56', 'user', NULL, '', '', '', '', '', '', '', ''),
(116, 'kokila', '9962240513@placeholder.local', '9962240513', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-17 14:06:42', '2026-06-17 14:06:42', 'user', NULL, '', '', '', '', '', '', '', ''),
(117, 'Monisha', '6380546472@placeholder.local', '6380546472', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-19 05:36:08', '2026-06-19 05:36:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(118, 'Lakshmi Jagan', '9840193427@placeholder.local', '9840193427', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-20 13:56:43', '2026-06-20 13:56:43', 'user', NULL, '', '', '', '', '', '', '', ''),
(119, 'Santhosh M', '9566155289@placeholder.local', '9566155289', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-20 15:58:10', '2026-06-20 15:58:10', 'user', NULL, '', '', '', '', '', '', '', ''),
(120, 'Divahar S', '9344938865@placeholder.local', '9344938865', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-22 17:19:05', '2026-06-22 17:19:05', 'user', NULL, '', '', '', '', '', '', '', ''),
(121, 'sanjay kumar', '8980880114@placeholder.local', '8980880114', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-23 07:20:15', '2026-06-23 07:20:15', 'user', NULL, '', '', '', '', '', '', '', ''),
(122, 'Faisal', '8825723470@placeholder.local', '8825723470', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-27 15:04:50', '2026-06-27 15:04:50', 'user', NULL, '', '', '', '', '', '', '', ''),
(123, 'Jaganathan GP', '9176144346@placeholder.local', '9176144346', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-27 17:02:28', '2026-06-27 17:02:28', 'user', NULL, '', '', '', '', '', '', '', ''),
(124, 'Mk', '9003678425@placeholder.local', '9003678425', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-06-30 01:54:16', '2026-06-30 01:54:16', 'user', NULL, '', '', '', '', '', '', '', ''),
(125, 'kumaraguru', '8940105155@placeholder.local', '8940105155', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-03 09:06:15', '2026-07-03 09:06:15', 'user', NULL, '', '', '', '', '', '', '', ''),
(126, 'Stenyfer', '9150314457@placeholder.local', '9150314457', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-04 06:16:41', '2026-07-04 06:16:41', 'user', NULL, '', '', '', '', '', '', '', ''),
(127, 'Sharanya', '9790718424@placeholder.local', '9790718424', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-04 11:27:52', '2026-07-04 11:27:52', 'user', NULL, '', '', '', '', '', '', '', ''),
(128, 'dhanalakshmi', '9080012196@placeholder.local', '9080012196', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-06 03:56:57', '2026-07-06 03:56:57', 'user', NULL, '', '', '', '', '', '', '', ''),
(129, 'Murugan', '9003265333@placeholder.local', '9003265333', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-07 12:55:37', '2026-07-07 12:55:37', 'user', NULL, '', '', '', '', '', '', '', ''),
(130, 'ayesha', '9606516639@placeholder.local', '9606516639', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-08 09:18:22', '2026-07-08 09:18:22', 'user', NULL, '', '', '', '', '', '', '', ''),
(131, 'Ramalingam', '8667303378@placeholder.local', '8667303378', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-09 06:46:16', '2026-07-09 06:46:16', 'user', NULL, '', '', '', '', '', '', '', ''),
(132, 'Murugan', '90032653333@placeholder.local', '90032653333', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-10 07:18:53', '2026-07-10 07:18:53', 'user', NULL, '', '', '', '', '', '', '', ''),
(133, 'Thalapathy Elango', '9710024209@placeholder.local', '9710024209', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-18 02:25:02', '2026-07-18 02:25:02', 'user', NULL, '', '', '', '', '', '', '', ''),
(134, 'rajkumar', '9003550438@placeholder.local', '9003550438', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-18 02:58:43', '2026-07-18 02:58:43', 'user', NULL, '', '', '', '', '', '', '', ''),
(135, 'S.Brisca', '9894636702@placeholder.local', '9894636702', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-23 16:42:18', '2026-07-23 16:42:18', 'user', NULL, '', '', '', '', '', '', '', ''),
(136, 'Niveditha', '7358445212@placeholder.local', '7358445212', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 10:46:51', '2026-07-25 10:46:51', 'user', NULL, '', '', '', '', '', '', '', ''),
(137, 'manoj kumar', '9952076476@placeholder.local', '9952076476', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 11:11:11', '2026-07-25 11:11:11', 'user', NULL, '', '', '', '', '', '', '', ''),
(138, 'kamal', '7904615272@placeholder.local', '7904615272', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 12:49:07', '2026-07-25 12:49:07', 'user', NULL, '', '', '', '', '', '', '', ''),
(139, 'sakthi', '8220364047@placeholder.local', '8220364047', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 12:52:04', '2026-07-25 12:52:04', 'user', NULL, '', '', '', '', '', '', '', ''),
(140, 'blessena charles', '8056882941@placeholder.local', '8056882941', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 13:09:03', '2026-07-25 13:09:03', 'user', NULL, '', '', '', '', '', '', '', ''),
(141, 'Keerthi', '7904880838@placeholder.local', '7904880838', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 13:44:40', '2026-07-25 13:44:40', 'user', NULL, '', '', '', '', '', '', '', ''),
(142, 'priya', '9940527480@placeholder.local', '9940527480', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 14:12:16', '2026-07-25 14:12:16', 'user', NULL, '', '', '', '', '', '', '', ''),
(143, 'priya', '9080230289@placeholder.local', '9080230289', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 14:15:36', '2026-07-25 14:15:36', 'user', NULL, '', '', '', '', '', '', '', ''),
(144, 'ahmed', '9894813033@placeholder.local', '9894813033', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 14:38:09', '2026-07-25 14:38:09', 'user', NULL, '', '', '', '', '', '', '', ''),
(145, 'kathak', '9876451320@placeholder.local', '9876451320', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 17:32:05', '2026-07-25 17:32:05', 'user', NULL, '', '', '', '', '', '', '', ''),
(146, 'senthil', '7305481731@placeholder.local', '7305481731', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-25 17:34:19', '2026-07-25 17:34:19', 'user', NULL, '', '', '', '', '', '', '', ''),
(147, 'Loga Ganesh', '9487124497@placeholder.local', '9487124497', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 01:12:46', '2026-07-26 01:12:46', 'user', NULL, '', '', '', '', '', '', '', ''),
(148, 'Jayashree', '8754558327@placeholder.local', '8754558327', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 02:06:16', '2026-07-26 02:06:16', 'user', NULL, '', '', '', '', '', '', '', ''),
(149, 'Saranya', '7904592280@placeholder.local', '7904592280', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 03:30:32', '2026-07-26 03:30:32', 'user', NULL, '', '', '', '', '', '', '', ''),
(150, 'raushni mohan Kumar', '9360231410@placeholder.local', '9360231410', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 04:09:28', '2026-07-26 04:34:30', 'user', NULL, '', '', '', '', '', '', '', ''),
(151, 'Nandhini', '9150224236@placeholder.local', '9150224236', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 04:29:39', '2026-07-26 04:29:39', 'user', NULL, '', '', '', '', '', '', '', ''),
(152, 'Pavithra', '9600027026@placeholder.local', '9600027026', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 06:38:08', '2026-07-26 06:38:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(153, 'bhuvana', '7094340482@placeholder.local', '7094340482', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 07:40:07', '2026-07-26 07:40:07', 'user', NULL, '', '', '', '', '', '', '', ''),
(154, 'raaji', '8838799033@placeholder.local', '8838799033', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 09:10:20', '2026-07-26 09:10:20', 'user', NULL, '', '', '', '', '', '', '', ''),
(155, 'Vidhubala', '8870718411@placeholder.local', '8870718411', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-26 10:30:27', '2026-07-26 10:30:27', 'user', NULL, '', '', '', '', '', '', '', ''),
(156, 'sugantha', '9043914268@placeholder.local', '9043914268', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-27 13:04:13', '2026-07-27 13:04:13', 'user', NULL, '', '', '', '', '', '', '', ''),
(157, 'Yokesh', '8012251412@placeholder.local', '8012251412', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-27 16:12:51', '2026-07-27 16:12:51', 'user', NULL, '', '', '', '', '', '', '', ''),
(158, 'Sridhar', '9003166614@placeholder.local', '9003166614', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-28 01:03:56', '2026-07-28 01:03:56', 'user', NULL, '', '', '', '', '', '', '', ''),
(159, 'Rithish', '9444756670@placeholder.local', '9444756670', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-28 08:41:53', '2026-07-28 08:41:53', 'user', NULL, '', '', '', '', '', '', '', ''),
(160, 'Lydia', '8879503981@placeholder.local', '8879503981', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-28 09:53:32', '2026-07-28 09:53:32', 'user', NULL, '', '', '', '', '', '', '', ''),
(161, 'Raushni mohan kumar', '9789044462@placeholder.local', '9789044462', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-28 16:19:19', '2026-07-28 16:19:19', 'user', NULL, '', '', '', '', '', '', '', ''),
(162, 'vidhya', '9952057912@placeholder.local', '9952057912', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-29 04:48:46', '2026-07-29 04:48:46', 'user', NULL, '', '', '', '', '', '', '', ''),
(163, 'Nisha', '9500134502@placeholder.local', '9500134502', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-29 06:04:06', '2026-07-29 06:04:06', 'user', NULL, '', '', '', '', '', '', '', ''),
(164, 'revathi', '7305535620@placeholder.local', '7305535620', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-29 09:08:08', '2026-07-29 09:08:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(165, 'Monika dharani', '7305205808@placeholder.local', '7305205808', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-29 11:26:31', '2026-07-29 11:26:31', 'user', NULL, '', '', '', '', '', '', '', ''),
(166, 'shruthe', '7358618246@placeholder.local', '7358618246', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-07-29 11:49:55', '2026-07-29 11:49:55', 'user', NULL, '', '', '', '', '', '', '', ''),
(167, 'keerthi', '6382107795@placeholder.local', '6382107795', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-08-01 17:51:23', '2026-08-01 17:51:23', 'user', NULL, '', '', '', '', '', '', '', ''),
(168, 'akila', '9790780089@placeholder.local', '9790780089', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-08-02 08:24:08', '2026-08-02 08:24:08', 'user', NULL, '', '', '', '', '', '', '', ''),
(169, 'premalatha', '8667351231@placeholder.local', '8667351231', '', 0, 0, 0, 'active', NULL, 0, NULL, '2026-08-04 13:15:07', '2026-08-04 13:15:07', 'user', NULL, '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `idx_cart_user_id` (`user_id`),
  ADD KEY `idx_cart_product_id` (`product_id`),
  ADD KEY `idx_cart_added_at` (`added_at`),
  ADD KEY `idx_cart_updated_at` (`updated_at`);

--
-- Indexes for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip` (`ip_address`),
  ADD KEY `idx_phone` (`phone`),
  ADD KEY `idx_time` (`attempt_time`);

--
-- Indexes for table `image_uploads`
--
ALTER TABLE `image_uploads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `filename` (`filename`),
  ADD KEY `idx_filename` (`filename`),
  ADD KEY `idx_uploaded_at` (`uploaded_at`),
  ADD KEY `idx_mime_type` (`mime_type`);

--
-- Indexes for table `ip_blacklist`
--
ALTER TABLE `ip_blacklist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`),
  ADD KEY `idx_ip` (`ip_address`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_user_id` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_payment_status` (`payment_status`),
  ADD KEY `idx_orders_created_at` (`created_at`),
  ADD KEY `idx_orders_updated_at` (`updated_at`),
  ADD KEY `idx_orders_user_status` (`user_id`,`status`),
  ADD KEY `idx_orders_payment_method` (`payment_method`),
  ADD KEY `idx_orders_delivery_city` (`delivery_city`),
  ADD KEY `idx_orders_total_amount` (`total_amount`),
  ADD KEY `idx_orders_estimated_delivery` (`estimated_delivery`),
  ADD KEY `idx_razorpay_order_id` (`razorpay_order_id`),
  ADD KEY `idx_razorpay_payment_id` (`razorpay_payment_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order_id` (`order_id`),
  ADD KEY `idx_order_items_product_id` (`product_id`),
  ADD KEY `idx_order_items_created_at` (`created_at`),
  ADD KEY `idx_order_items_product_category` (`product_category`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_razorpay_order_id` (`razorpay_order_id`),
  ADD KEY `idx_razorpay_payment_id` (`razorpay_payment_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `payment_refunds`
--
ALTER TABLE `payment_refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_razorpay_payment_id` (`razorpay_payment_id`),
  ADD KEY `idx_razorpay_refund_id` (`razorpay_refund_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `payment_webhooks`
--
ALTER TABLE `payment_webhooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_processed` (`processed`),
  ADD KEY `idx_razorpay_payment_id` (`razorpay_payment_id`),
  ADD KEY `idx_razorpay_order_id` (`razorpay_order_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rate_limiting`
--
ALTER TABLE `rate_limiting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip_endpoint` (`ip_address`,`endpoint`),
  ADD KEY `idx_window` (`window_start`);

--
-- Indexes for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=701;

--
-- AUTO_INCREMENT for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_uploads`
--
ALTER TABLE `image_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_blacklist`
--
ALTER TABLE `ip_blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `payment_refunds`
--
ALTER TABLE `payment_refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_webhooks`
--
ALTER TABLE `payment_webhooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `rate_limiting`
--
ALTER TABLE `rate_limiting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD CONSTRAINT `payment_logs_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_refunds`
--
ALTER TABLE `payment_refunds`
  ADD CONSTRAINT `payment_refunds_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD CONSTRAINT `refund_requests_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refund_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
