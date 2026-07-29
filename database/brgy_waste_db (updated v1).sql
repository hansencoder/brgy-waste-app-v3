-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2026 at 07:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brgy_waste_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_deactivations`
--

CREATE TABLE `account_deactivations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `deactivated_by` int(11) NOT NULL,
  `deactivated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `affected_record` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `result` varchar(50) NOT NULL DEFAULT 'success',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`) VALUES
(951, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 12:46:08'),
(952, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 12:46:25'),
(953, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-25 13:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs_backup`
--

CREATE TABLE `audit_logs_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `action_type` varchar(100) NOT NULL,
  `target_entity` varchar(100) DEFAULT NULL,
  `action_details` text NOT NULL,
  `result` enum('success','failed') NOT NULL DEFAULT 'success',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs_backup`
--

INSERT INTO `audit_logs_backup` (`id`, `user_id`, `action_type`, `target_entity`, `action_details`, `result`, `created_at`) VALUES
(1, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-03-31 16:45:17'),
(2, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-03-31 16:46:39'),
(3, NULL, 'Login failed', 'User', 'Invalid credentials for captain@dulongbayan.ph', 'failed', '2026-03-31 16:48:31'),
(4, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-03-31 16:51:18'),
(5, NULL, 'Account locked', 'User', 'Exceeded login attempts for secretary@dulongbayan.ph', 'failed', '2026-03-31 16:54:43'),
(6, 1, 'Login partial success', 'User', '2FA code sent', 'success', '2026-03-31 17:01:20'),
(7, 1, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-03-31 17:01:24'),
(8, 1, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-03-31 17:01:34'),
(9, 1, '2FA Resend', 'User', 'Code resent', 'success', '2026-03-31 17:01:45'),
(10, 1, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-03-31 17:01:53'),
(11, 1, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-03-31 17:02:15'),
(12, 1, '2FA Resend', 'User', 'Code resent', 'success', '2026-03-31 17:02:16'),
(13, 1, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-03-31 17:02:20'),
(14, 1, '2FA Resend', 'User', 'Code resent', 'success', '2026-03-31 17:02:22'),
(15, 1, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-03-31 17:03:16'),
(16, 1, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-03-31 17:03:59'),
(17, NULL, 'User Registration', 'User', 'Pending registration for floressktt11@gmail.com', 'success', '2026-03-31 17:05:23'),
(18, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-03-31 17:05:46'),
(19, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-03-31 17:05:49'),
(20, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:05:49'),
(21, 2, 'Account Approved', 'User ID 3', 'Approved account ID 3', 'success', '2026-03-31 17:06:02'),
(22, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:06:10'),
(23, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-03-31 17:06:16'),
(24, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-03-31 17:06:29'),
(25, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-03-31 17:06:33'),
(26, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-03-31 17:09:33'),
(27, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-03-31 17:09:51'),
(28, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-03-31 17:09:54'),
(29, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:09:54'),
(30, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:09:58'),
(31, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:10:04'),
(32, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:22:12'),
(33, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:22:14'),
(34, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:22:19'),
(35, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:22:20'),
(36, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:22:22'),
(37, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-03-31 17:25:39'),
(38, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-03-31 17:25:57'),
(39, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-03-31 17:26:00'),
(40, 3, 'Report Submitted', 'Waste Report', 'User submitted report', 'success', '2026-03-31 17:26:57'),
(41, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-03-31 17:27:18'),
(42, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-03-31 17:27:28'),
(43, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-03-31 17:27:37'),
(44, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:27:37'),
(45, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:27:43'),
(46, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-03-31 17:28:00'),
(47, 1, 'Login partial success', 'User', '2FA code sent', 'success', '2026-03-31 17:28:13'),
(48, 1, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-03-31 17:28:16'),
(49, 1, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-03-31 17:28:16'),
(50, 1, 'Logout', 'User', 'User logged out manually', 'success', '2026-03-31 17:28:21'),
(51, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-03 20:35:54'),
(52, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-03 20:35:58'),
(53, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-03 20:37:00'),
(54, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-03 20:37:55'),
(55, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-03 20:37:59'),
(56, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:37:59'),
(57, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:38:18'),
(58, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:38:51'),
(59, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:42:29'),
(60, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:42:33'),
(61, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:43:07'),
(62, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:43:21'),
(63, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 20:54:58'),
(64, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:02:46'),
(65, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:21'),
(66, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:23'),
(67, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:23'),
(68, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:23'),
(69, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:23'),
(70, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:23'),
(71, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:24'),
(72, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:24'),
(73, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:24'),
(74, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:18:39'),
(75, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:20:39'),
(76, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-03 21:20:58'),
(77, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@barangay.gov', 'failed', '2026-04-03 21:34:22'),
(78, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-03 21:35:07'),
(79, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-03 21:35:10'),
(80, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-03 21:35:10'),
(81, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-03 22:05:27'),
(82, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-03 22:06:35'),
(83, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-03 22:06:39'),
(84, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-03 22:24:45'),
(85, 1, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-04 01:46:00'),
(86, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-04 01:48:31'),
(87, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-04 01:48:34'),
(88, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-04 01:48:34'),
(89, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-04 01:49:56'),
(90, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-04 01:49:58'),
(91, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-04 01:50:01'),
(92, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-04 01:50:03'),
(93, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-04 01:50:32'),
(94, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-04 01:50:50'),
(95, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-04 01:50:52'),
(96, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-04 02:02:04'),
(97, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-04 02:02:24'),
(98, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-04 02:02:27'),
(99, 1, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-04 02:12:19'),
(100, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-04 17:24:38'),
(101, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-04 17:24:41'),
(102, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-05 02:50:31'),
(103, NULL, 'Login failed', 'User', 'Invalid credentials for floressktt11@gmail.com', 'failed', '2026-04-05 02:50:56'),
(104, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-05 02:51:07'),
(105, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-05 02:51:10'),
(106, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-05 03:02:00'),
(107, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-05 12:27:06'),
(108, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-05 12:27:12'),
(109, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-05 12:27:35'),
(110, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-05 12:27:44'),
(111, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-05 12:28:16'),
(112, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-05 12:28:19'),
(113, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-05 12:28:19'),
(114, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-05 12:51:40'),
(115, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-05 12:51:44'),
(116, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-05 12:51:47'),
(117, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-05 12:52:01'),
(118, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-05 12:52:03'),
(119, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-05 13:57:13'),
(120, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-05 14:09:52'),
(121, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-05 14:09:55'),
(122, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-05 14:21:40'),
(123, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-05 14:21:57'),
(124, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-05 14:22:02'),
(125, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-05 14:41:44'),
(126, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@barangay.gov', 'failed', '2026-04-05 14:41:51'),
(127, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-05 14:42:12'),
(128, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-05 14:42:14'),
(129, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-05 14:42:14'),
(130, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-05 17:07:58'),
(131, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 00:09:05'),
(132, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 00:09:09'),
(133, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 00:09:09'),
(134, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-06 03:36:50'),
(135, NULL, 'User Registration', 'User', 'Pending registration for floreshans.neust@gmail.com', 'success', '2026-04-06 06:16:56'),
(136, NULL, 'Login failed', 'User', 'Invalid credentials for eey@bngy.go', 'failed', '2026-04-06 06:17:30'),
(137, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 06:23:56'),
(138, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 06:24:01'),
(139, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 06:24:01'),
(140, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-06 10:06:51'),
(141, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@barangay.gov', 'failed', '2026-04-06 10:07:27'),
(142, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 10:08:38'),
(143, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 10:08:42'),
(144, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:08:42'),
(145, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:08:48'),
(146, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:08:51'),
(147, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:08:56'),
(148, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:09:00'),
(149, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:09:06'),
(150, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:09:10'),
(151, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 10:09:14'),
(152, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@barangay.gov', 'failed', '2026-04-06 13:53:46'),
(153, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 13:54:58'),
(154, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 13:55:01'),
(155, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 13:55:01'),
(156, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 13:56:31'),
(157, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 13:57:08'),
(158, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-06 14:34:42'),
(159, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 14:35:42'),
(160, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 14:35:46'),
(161, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 14:35:46'),
(162, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 14:39:16'),
(163, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 14:39:17'),
(164, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 14:39:40'),
(165, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-06 15:25:46'),
(166, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 15:26:01'),
(167, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 15:26:04'),
(168, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 15:26:04'),
(169, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-06 16:12:55'),
(170, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-06 16:13:11'),
(171, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@barangay.gov', 'failed', '2026-04-06 16:13:26'),
(172, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 16:13:40'),
(173, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 16:13:44'),
(174, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:13:44'),
(175, 2, 'Report Generated', 'Report Summary', 'Format: csv', 'success', '2026-04-06 16:13:58'),
(176, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-06 16:14:04'),
(177, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 16:14:18'),
(178, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 16:14:24'),
(179, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:14:24'),
(180, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:15:05'),
(181, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:16:22'),
(182, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:17:36'),
(183, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:18:22'),
(184, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:18:25'),
(185, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:18:28'),
(186, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:18:31'),
(187, 2, 'Account Approved', 'User ID 4', 'Approved account ID 4', 'success', '2026-04-06 16:18:42'),
(188, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:18:51'),
(189, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:19:29'),
(190, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:19:41'),
(191, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:20:07'),
(192, 2, 'Post Announcement', 'Announcements', 'Posted \'testing my post announce\'', 'success', '2026-04-06 16:20:48'),
(193, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:20:52'),
(194, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:21:30'),
(195, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:21'),
(196, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:23'),
(197, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:24'),
(198, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:25'),
(199, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:26'),
(200, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:28'),
(201, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:30'),
(202, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:31'),
(203, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:32'),
(204, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:36'),
(205, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:39'),
(206, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:22:52'),
(207, 2, 'Account Deactivated', 'User ID 4', 'Deactivated account ID 4. Reason: No reason provided', 'success', '2026-04-06 16:23:10'),
(208, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:26:43'),
(209, 2, 'Account Deleted', 'User ID 4', 'Deleted account ID 4', 'success', '2026-04-06 16:27:24'),
(210, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:27:54'),
(211, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-06 16:28:27'),
(212, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@barangay.gov', 'failed', '2026-04-06 16:43:19'),
(213, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-06 16:43:30'),
(214, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-06 16:43:33'),
(215, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:43:33'),
(216, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:45:24'),
(217, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:47:32'),
(218, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-06 16:47:39'),
(219, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-06 16:47:43'),
(220, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@barangay.gov', 'failed', '2026-04-07 14:25:16'),
(221, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-07 14:25:27'),
(222, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-07 14:25:38'),
(223, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-07 14:26:12'),
(224, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 14:26:12'),
(225, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-07 14:26:17'),
(226, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-07 14:26:31'),
(227, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-07 14:27:51'),
(228, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 14:27:51'),
(229, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-07 14:27:52'),
(230, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-07 14:34:15'),
(231, 2, '2FA failed', 'User', 'Invalid or expired OTP', 'failed', '2026-04-07 14:34:33'),
(232, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-07 14:34:38'),
(233, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 14:34:38'),
(234, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 14:34:49'),
(235, 2, 'Report Generated', 'Report Summary', 'Format: print', 'success', '2026-04-07 14:35:30'),
(236, 2, 'Report Generated', 'Report Summary', 'Format: csv', 'success', '2026-04-07 14:35:43'),
(237, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-07 14:38:05'),
(238, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-07 14:49:14'),
(239, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-07 14:49:18'),
(240, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 14:49:18'),
(241, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-07 14:59:14'),
(242, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-07 15:02:07'),
(243, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-07 15:05:50'),
(244, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:05:50'),
(245, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:05:56'),
(246, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:05:58'),
(247, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:38:42'),
(248, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:38:43'),
(249, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-07 15:38:45'),
(250, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-07 15:40:06'),
(251, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-07 15:44:37'),
(252, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-07 15:45:33'),
(253, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-07 15:46:35'),
(254, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-07 15:46:38'),
(255, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:46:38'),
(256, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:48:16'),
(257, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:48:56'),
(258, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:49:13'),
(259, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:49:17'),
(260, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:54:10'),
(261, 2, 'Report Generated', 'Report Summary', 'Format: print', 'success', '2026-04-07 15:54:29'),
(262, 2, 'Report Verified', 'Report ID 1', 'Verified report. Remark: ', 'success', '2026-04-07 15:55:23'),
(263, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-07 15:57:28'),
(264, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-07 19:46:31'),
(265, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-08 02:12:08'),
(266, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-08 02:12:53'),
(267, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-08 02:12:53'),
(268, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-08 02:14:06'),
(269, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-08 02:14:40'),
(270, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-08 02:14:43'),
(271, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-08 02:14:53'),
(272, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-08 02:14:58'),
(273, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-08 02:15:30'),
(274, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-08 05:35:41'),
(275, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-08 05:35:45'),
(276, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-08 05:35:45'),
(277, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-09 06:58:35'),
(278, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-09 06:58:38'),
(279, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 06:58:38'),
(280, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 06:59:13'),
(281, 2, 'Report Generated', 'Report Summary', 'Format: csv', 'success', '2026-04-09 06:59:15'),
(282, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 07:13:51'),
(283, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 07:13:54'),
(284, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 07:14:01'),
(285, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 07:14:28'),
(286, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 07:25:17'),
(287, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 07:30:18'),
(288, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-09 08:01:37'),
(289, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-09 08:01:52'),
(290, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-09 08:01:56'),
(291, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 08:01:56'),
(292, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 08:07:28'),
(293, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 08:08:48'),
(294, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 08:10:56'),
(295, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 08:14:21'),
(296, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-09 08:15:07'),
(297, NULL, 'Login failed', 'User', 'Invalid credentials for floressktt11@gmail.com', 'failed', '2026-04-09 08:15:53'),
(298, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-09 08:16:03'),
(299, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-09 08:16:06'),
(300, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-09 08:17:12'),
(301, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-09 08:17:20'),
(302, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-09 08:17:23'),
(303, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 08:17:23'),
(304, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-09 08:19:09'),
(305, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-09 08:19:25'),
(306, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-09 08:19:28'),
(307, 3, 'Report Submitted', 'Waste Report', 'User submitted report', 'success', '2026-04-09 08:20:16'),
(308, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-09 08:20:31'),
(309, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-09 08:20:40'),
(310, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-09 08:20:50'),
(311, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-09 08:20:53'),
(312, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-09 08:20:53'),
(313, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-09 15:33:11'),
(314, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-10 06:08:27'),
(315, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 06:08:35'),
(316, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 06:08:38'),
(317, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 06:08:38'),
(318, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-10 06:08:48'),
(319, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 06:09:00'),
(320, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 06:09:02'),
(321, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 06:09:02'),
(322, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-10 06:09:18'),
(323, NULL, 'User Registration', 'User', 'Pending registration for hanstwo@gmail.com', 'success', '2026-04-10 06:11:26'),
(324, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 06:13:42'),
(325, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 06:13:46'),
(326, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 06:13:46'),
(327, 2, 'Account Approved', 'User ID 5', 'Approved account ID 5', 'success', '2026-04-10 06:13:56'),
(328, 2, 'Account Deleted', 'User ID 5', 'Deleted account ID 5', 'success', '2026-04-10 06:14:04'),
(329, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 06:15:42'),
(330, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 06:15:44'),
(331, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 06:15:45'),
(332, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-10 06:15:48'),
(333, NULL, 'User Registration', 'User', 'Pending registration for hans@gmail.com', 'success', '2026-04-10 06:24:48'),
(334, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-10 06:25:26'),
(335, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 06:25:33'),
(336, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 06:25:36'),
(337, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 06:25:36'),
(338, 2, 'Account Approved', 'User ID 6', 'Approved account ID 6', 'success', '2026-04-10 06:25:48'),
(339, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-10 06:25:50'),
(340, 6, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 06:26:00'),
(341, 6, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 06:26:03'),
(342, 6, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-10 06:26:20'),
(343, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 07:46:42'),
(344, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 07:46:47'),
(345, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-10 13:09:45'),
(346, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 14:00:05'),
(347, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 14:00:09'),
(348, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-10 14:26:18'),
(349, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 14:26:30'),
(350, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 14:26:37'),
(351, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 14:26:37'),
(352, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-10 14:26:43'),
(353, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-10 15:12:50'),
(354, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 15:13:09'),
(355, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 15:13:11'),
(356, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-10 15:31:46'),
(357, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 15:31:55'),
(358, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 15:31:58'),
(359, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-10 16:46:04'),
(360, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 16:46:18'),
(361, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 16:46:21'),
(362, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-10 17:32:01'),
(363, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-10 17:32:08'),
(364, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-10 17:32:20'),
(365, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 03:18:41'),
(366, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 03:18:52'),
(367, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 03:18:55'),
(368, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 03:20:34'),
(369, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 03:20:53'),
(370, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 03:20:56'),
(371, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 04:02:10'),
(372, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 04:02:27'),
(373, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 04:02:31'),
(374, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 04:40:22'),
(375, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 04:40:34'),
(376, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 04:40:38'),
(377, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 09:52:38'),
(378, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 09:52:58'),
(379, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 09:52:59'),
(380, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 09:53:04'),
(381, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 12:09:48'),
(382, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 12:10:10'),
(383, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 12:10:14'),
(384, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 13:25:37'),
(385, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 13:25:53'),
(386, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 13:25:56'),
(387, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 14:36:26'),
(388, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 14:36:35'),
(389, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 14:36:38'),
(390, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 14:40:27'),
(391, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 14:40:36'),
(392, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 14:40:41'),
(393, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 14:56:49'),
(394, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 14:56:57'),
(395, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 14:57:00'),
(396, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 15:12:34'),
(397, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 15:12:45'),
(398, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 15:12:48'),
(399, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 17:11:15'),
(400, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 17:11:28'),
(401, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 17:11:31'),
(402, 3, 'Report Submitted', 'Waste Report', 'User submitted report', 'success', '2026-04-11 17:15:05'),
(403, 3, 'Report Submitted', 'Waste Report', 'User submitted report', 'success', '2026-04-11 17:26:03'),
(404, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success', '2026-04-11 18:45:53'),
(405, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 18:46:04'),
(406, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 18:46:06'),
(407, 3, 'Report Submitted', 'Waste Report', 'User submitted report', 'success', '2026-04-11 18:52:37'),
(408, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 18:54:42'),
(409, NULL, 'Login failed', 'User', 'Invalid credentials for floressktt11@gmail.com', 'failed', '2026-04-11 18:54:50'),
(410, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 18:54:58'),
(411, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 18:55:01'),
(412, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-11 18:55:01'),
(413, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-11 18:55:52'),
(414, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-11 18:56:10'),
(415, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-11 18:56:30'),
(416, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 18:56:40'),
(417, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 18:56:50'),
(418, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 18:56:52'),
(419, 3, 'Report Submitted', 'Waste Report', 'User submitted report', 'success', '2026-04-11 18:57:16'),
(420, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 18:57:49'),
(421, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 18:58:01'),
(422, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 18:58:04'),
(423, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-11 18:58:04'),
(424, 2, 'Report Verified', 'Report ID 6', 'Verified report. Remark: ', 'success', '2026-04-11 18:58:11'),
(425, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 18:58:17'),
(426, NULL, 'Login failed', 'User', 'Invalid credentials for floressktt11@gmail.com', 'failed', '2026-04-11 18:58:23'),
(427, 3, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 18:58:30'),
(428, 3, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 18:58:33'),
(429, 3, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 19:21:16'),
(430, 2, 'Login partial success', 'User', '2FA code sent', 'success', '2026-04-11 19:21:26'),
(431, 2, 'Login successful', 'User', 'Successfully completed 2FA', 'success', '2026-04-11 19:21:29'),
(432, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success', '2026-04-11 19:21:29'),
(433, 2, 'Logout', 'User', 'User logged out manually', 'success', '2026-04-11 19:39:29'),
(434, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-11 19:39:46'),
(435, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-11 19:40:02'),
(436, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-11 19:42:13'),
(437, NULL, 'Login failed', 'User', 'Invalid credentials for secretary@dulongbayan.ph', 'failed', '2026-04-11 19:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `email_otp_rate_limits`
--

CREATE TABLE `email_otp_rate_limits` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `window_start` datetime NOT NULL,
  `send_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_otp_rate_limits`
--

INSERT INTO `email_otp_rate_limits` (`id`, `email`, `ip`, `window_start`, `send_count`) VALUES
(12, 'floressktt11@gmail.com', '::1', '2026-07-25 06:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `report_id` int(11) DEFAULT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `send_to_all` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_backup`
--

CREATE TABLE `notifications_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('notification','announcement') NOT NULL DEFAULT 'notification',
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications_backup`
--

INSERT INTO `notifications_backup` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, NULL, 'announcement', 'testing my post announce', 'message content testing', 0, '2026-04-06 16:20:48');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `location_verified` tinyint(1) DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `submission_date` datetime DEFAULT current_timestamp(),
  `reviewed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_flags`
--

CREATE TABLE `report_flags` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `flag_reason` varchar(255) NOT NULL,
  `flagged_by` int(11) NOT NULL,
  `flagged_at` datetime DEFAULT current_timestamp(),
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_status_history`
--

CREATE TABLE `report_status_history` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `previous_status` varchar(50) NOT NULL,
  `new_status` varchar(50) NOT NULL,
  `remark` text DEFAULT NULL,
  `changed_by` int(11) NOT NULL,
  `changed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_summaries`
--

CREATE TABLE `report_summaries` (
  `id` int(11) NOT NULL,
  `generated_by` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `filters` text DEFAULT NULL,
  `total_reports` int(11) DEFAULT 0,
  `generated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_summaries_backup`
--

CREATE TABLE `report_summaries_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `generated_by` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `filter_criteria` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_otps`
--

CREATE TABLE `sms_otps` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `token` char(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `last_sent_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_rate_limits`
--

CREATE TABLE `sms_rate_limits` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `window_start` datetime NOT NULL,
  `sms_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `two_factor_tokens`
--

CREATE TABLE `two_factor_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `two_factor_tokens`
--

INSERT INTO `two_factor_tokens` (`id`, `user_id`, `email`, `token`, `expires_at`, `is_used`, `attempts`, `created_at`) VALUES
(253, 3, 'floressktt11@gmail.com', '587439', '2026-07-25 12:56:03', 1, 0, '2026-07-25 04:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `account_type` enum('resident','non-resident') DEFAULT 'resident',
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_front` varchar(255) DEFAULT NULL,
  `id_back` varchar(255) DEFAULT NULL,
  `role` enum('resident','secretary','captain') NOT NULL DEFAULT 'resident',
  `status` varchar(50) DEFAULT 'pending',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone_normalized` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `account_type`, `address`, `phone_number`, `email`, `password`, `id_front`, `id_back`, `role`, `status`, `last_login`, `created_at`, `updated_at`, `phone_normalized`) VALUES
(1, 'Barangay Captain', NULL, 'resident', 'Barangay Hall', '09123456789', 'captain@dulongbayan.ph', '$2y$10$Z/HeHO5k9Uu8kt3YsKCM9e0Q/5DnYYKhMTcDeQ3LDVX6KTj3iv2Gy', NULL, NULL, 'captain', 'active', NULL, '2026-04-01 00:28:49', '2026-04-19 10:55:34', NULL),
(2, 'Secretary Rose', NULL, 'resident', 'Barangay Hall', '09123456788', 'secretary@dulongbayan.ph', '$2y$10$Fc.nsw9nNPbluMgzYQjdcexP17LAZDc44VmKa1j0CoaXUV4dawUfm', NULL, NULL, 'secretary', 'active', NULL, '2026-04-01 00:28:49', '2026-04-19 17:59:16', NULL),
(3, 'Hans Flores', NULL, 'resident', 'brgy.testing.testing', '09951281511', 'floressktt11@gmail.com', '$2y$10$.jggu7XHDkz65Y2Q5L0mOOdnhB9MFl3TjKeSFVfSLrMERH.de9AAy', NULL, NULL, 'resident', 'active', NULL, '2026-04-01 01:05:23', '2026-04-19 04:48:09', NULL),
(15, 'asdasdadad', NULL, 'resident', '232323232323', '09951281511', 'floererererer@gmail.com', '$2y$10$fL/0SLQG2zLnUEniuGsTU.ulSh4yLbmpwPMkWFLIbqc2OU1xF1Niq', '/uploads/ids/front_6a6252ecf1d05.jpg', '/uploads/ids/back_6a6252ed00579.jpg', 'resident', 'pending', NULL, '2026-07-24 01:44:13', '2026-07-24 01:44:13', NULL),
(16, 'test email otp', NULL, 'resident', 'awwsdasdad', '09951281511', 'testingotp@gmail.com', '$2y$10$NlxWm4KHBTA2PPazo0MN7ehPrv3RmAtUukEAO8QavMGJAmG.0znou', '/uploads/ids/front_6a6258475b015.jpg', '/uploads/ids/back_6a6258475be3f.jpg', 'resident', 'pending', NULL, '2026-07-24 02:07:03', '2026-07-24 02:07:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_deactivations`
--
ALTER TABLE `account_deactivations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deactivated_by` (`deactivated_by`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `deactivated_at` (`deactivated_at`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `email_otp_rate_limits`
--
ALTER TABLE `email_otp_rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_ip_window` (`email`,`ip`,`window_start`),
  ADD KEY `email` (`email`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcement_id` (`announcement_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `report_flags`
--
ALTER TABLE `report_flags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flagged_by` (`flagged_by`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `flagged_at` (`flagged_at`);

--
-- Indexes for table `report_status_history`
--
ALTER TABLE `report_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `changed_at` (`changed_at`);

--
-- Indexes for table `report_summaries`
--
ALTER TABLE `report_summaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `generated_by` (`generated_by`),
  ADD KEY `generated_at` (`generated_at`);

--
-- Indexes for table `sms_otps`
--
ALTER TABLE `sms_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `token` (`token`),
  ADD KEY `expires_at` (`expires_at`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `sms_rate_limits`
--
ALTER TABLE `sms_rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_ip_window` (`phone`,`ip`,`window_start`),
  ADD KEY `phone` (`phone`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `two_factor_tokens`
--
ALTER TABLE `two_factor_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `email_2` (`email`),
  ADD KEY `role` (`role`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_deactivations`
--
ALTER TABLE `account_deactivations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=954;

--
-- AUTO_INCREMENT for table `email_otp_rate_limits`
--
ALTER TABLE `email_otp_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `report_flags`
--
ALTER TABLE `report_flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_status_history`
--
ALTER TABLE `report_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report_summaries`
--
ALTER TABLE `report_summaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_otps`
--
ALTER TABLE `sms_otps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sms_rate_limits`
--
ALTER TABLE `sms_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `two_factor_tokens`
--
ALTER TABLE `two_factor_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_deactivations`
--
ALTER TABLE `account_deactivations`
  ADD CONSTRAINT `account_deactivations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_deactivations_ibfk_2` FOREIGN KEY (`deactivated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_flags`
--
ALTER TABLE `report_flags`
  ADD CONSTRAINT `report_flags_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_flags_ibfk_2` FOREIGN KEY (`flagged_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_flags_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `report_status_history`
--
ALTER TABLE `report_status_history`
  ADD CONSTRAINT `report_status_history_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_summaries`
--
ALTER TABLE `report_summaries`
  ADD CONSTRAINT `report_summaries_ibfk_1` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sms_otps`
--
ALTER TABLE `sms_otps`
  ADD CONSTRAINT `sms_otps_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `two_factor_tokens`
--
ALTER TABLE `two_factor_tokens`
  ADD CONSTRAINT `two_factor_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
