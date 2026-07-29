-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2026 at 08:36 AM
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
-- Table structure for table `announcement_visibilities`
--

CREATE TABLE `announcement_visibilities` (
  `visibility_id` int(11) NOT NULL,
  `visibility_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_visibilities`
--

INSERT INTO `announcement_visibilities` (`visibility_id`, `visibility_name`, `description`, `created_at`) VALUES
(1, 'Public', 'Visible to all visitors including non-registered users', '2026-07-25 13:56:31'),
(2, 'Registered', 'Visible only to registered users (Residents, Supervisors, Administrators)', '2026-07-25 13:56:31'),
(3, 'Internal', 'Visible only to Supervisors and Administrators', '2026-07-25 13:56:31');

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
  `created_at` datetime DEFAULT current_timestamp(),
  `module` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(951, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 12:46:08', NULL, NULL, NULL, NULL),
(952, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 12:46:25', NULL, NULL, NULL, NULL),
(953, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-25 13:35:08', NULL, NULL, NULL, NULL);

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
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `official_address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `official_email` varchar(100) DEFAULT NULL,
  `barangay_logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `barangay_name`, `municipality`, `province`, `region`, `official_address`, `contact_number`, `official_email`, `barangay_logo`, `created_at`, `updated_at`) VALUES
(1, 'Dulong Bayan', 'Talavera', 'Nueva Ecija', 'Central Luzon', NULL, NULL, NULL, NULL, '2026-07-25 14:27:25', '2026-07-25 14:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `barangay_boundaries`
--

CREATE TABLE `barangay_boundaries` (
  `boundary_id` int(11) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `polygon_geometry` geometry NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_schedules`
--

CREATE TABLE `collection_schedules` (
  `schedule_id` int(11) NOT NULL,
  `collection_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `waste_type` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','special') DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_schedule_puroks`
--

CREATE TABLE `collection_schedule_puroks` (
  `schedule_purok_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `purok_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `estimated_quantities`
--

CREATE TABLE `estimated_quantities` (
  `quantity_id` int(11) NOT NULL,
  `quantity_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estimated_quantities`
--

INSERT INTO `estimated_quantities` (`quantity_id`, `quantity_name`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Small', '1-2 garbage bags', 1, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(2, 'Medium', '3-5 garbage bags', 2, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(3, 'Large', '6-10 garbage bags', 3, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(4, 'Very Large', 'More than 10 garbage bags', 4, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `heatmap_settings`
--

CREATE TABLE `heatmap_settings` (
  `setting_id` int(11) NOT NULL,
  `radius_meters` int(11) DEFAULT 50,
  `minimum_reports` int(11) DEFAULT 3,
  `low_density_color` varchar(7) DEFAULT '#FDE68A',
  `medium_density_color` varchar(7) DEFAULT '#F97316',
  `high_density_color` varchar(7) DEFAULT '#EF4444',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `heatmap_settings`
--

INSERT INTO `heatmap_settings` (`setting_id`, `radius_meters`, `minimum_reports`, `low_density_color`, `medium_density_color`, `high_density_color`, `updated_by`, `updated_at`) VALUES
(1, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 13:56:31'),
(2, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:27:25'),
(3, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:30:39'),
(4, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:31:01'),
(5, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `map_landmarks`
--

CREATE TABLE `map_landmarks` (
  `landmark_id` int(11) NOT NULL,
  `landmark_name` varchar(100) NOT NULL,
  `landmark_type` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `notification_types`
--

CREATE TABLE `notification_types` (
  `notification_type_id` int(11) NOT NULL,
  `notification_type_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_types`
--

INSERT INTO `notification_types` (`notification_type_id`, `notification_type_name`, `description`, `created_at`) VALUES
(1, 'Report Status Update', 'When a report status changes', '2026-07-25 13:56:31'),
(2, 'New Announcement', 'When a new announcement is published', '2026-07-25 13:56:31'),
(3, 'Collection Schedule Update', 'When collection schedule changes', '2026-07-25 13:56:31'),
(4, 'Report Submitted', 'When a new report is submitted', '2026-07-25 13:56:31'),
(5, 'Report Verified', 'When a report is verified', '2026-07-25 13:56:31'),
(6, 'Report Resolved', 'When a report is resolved', '2026-07-25 13:56:31'),
(7, 'Account Approved', 'When a resident account is approved', '2026-07-25 13:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`position_id`, `position_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Barangay Captain', 'Chief executive of the barangay', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(2, 'Barangay Secretary', 'Administrative officer of the barangay', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(3, 'Barangay Kagawad', 'Elected barangay council member', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(4, 'Environmental Officer', 'Responsible for environmental programs', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(5, 'Waste Collection Coordinator', 'Manages waste collection operations', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(6, 'Resident', 'Registered barangay resident', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `puroks`
--

CREATE TABLE `puroks` (
  `purok_id` int(11) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `purok_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `puroks`
--

INSERT INTO `puroks` (`purok_id`, `barangay_id`, `purok_name`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Purok 1', NULL, 1, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(2, 1, 'Purok 2', NULL, 2, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(3, 1, 'Purok 3', NULL, 3, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(4, 1, 'Purok 4', NULL, 4, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(5, 1, 'Purok 5', NULL, 5, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `purok_boundaries`
--

CREATE TABLE `purok_boundaries` (
  `boundary_id` int(11) NOT NULL,
  `purok_id` int(11) NOT NULL,
  `polygon_geometry` geometry NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `location_verified` tinyint(1) DEFAULT 0,
  `submission_date` datetime DEFAULT current_timestamp(),
  `reviewed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `quantity_id` int(11) DEFAULT NULL,
  `condition_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `purok_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `rejected_reason` text DEFAULT NULL,
  `support_count` int(11) DEFAULT 0
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
-- Table structure for table `report_generation_settings`
--

CREATE TABLE `report_generation_settings` (
  `setting_id` int(11) NOT NULL,
  `report_header` text DEFAULT NULL,
  `report_footer` text DEFAULT NULL,
  `signatory_name` varchar(255) DEFAULT NULL,
  `signatory_position` varchar(255) DEFAULT NULL,
  `disclaimer` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_generation_settings`
--

INSERT INTO `report_generation_settings` (`setting_id`, `report_header`, `report_footer`, `signatory_name`, `signatory_position`, `disclaimer`, `updated_by`, `updated_at`) VALUES
(1, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 13:56:31'),
(2, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:27:25'),
(3, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:30:39'),
(4, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:31:01'),
(5, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `report_photos`
--

CREATE TABLE `report_photos` (
  `photo_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_settings`
--

CREATE TABLE `report_settings` (
  `setting_id` int(11) NOT NULL,
  `photo_required` tinyint(1) DEFAULT 1,
  `allowed_file_types` varchar(255) DEFAULT 'jpg,jpeg,png',
  `max_upload_size` int(11) DEFAULT 5242880,
  `duplicate_distance` int(11) DEFAULT 50,
  `duplicate_time_window` int(11) DEFAULT 7,
  `max_reports_per_day` int(11) DEFAULT 10,
  `enable_remarks` tinyint(1) DEFAULT 1,
  `remarks_character_limit` int(11) DEFAULT 500,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_settings`
--

INSERT INTO `report_settings` (`setting_id`, `photo_required`, `allowed_file_types`, `max_upload_size`, `duplicate_distance`, `duplicate_time_window`, `max_reports_per_day`, `enable_remarks`, `remarks_character_limit`, `updated_by`, `updated_at`) VALUES
(1, 1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500, NULL, '2026-07-25 13:56:31'),
(2, 1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500, NULL, '2026-07-25 14:27:25'),
(3, 1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500, NULL, '2026-07-25 14:30:39'),
(4, 1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500, NULL, '2026-07-25 14:31:01'),
(5, 1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500, NULL, '2026-07-25 14:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `report_statuses`
--

CREATE TABLE `report_statuses` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `color_code` varchar(7) DEFAULT '#F59E0B',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_statuses`
--

INSERT INTO `report_statuses` (`status_id`, `status_name`, `description`, `color_code`, `created_at`, `updated_at`) VALUES
(1, 'Pending', 'Report submitted and awaiting verification', '#F59E0B', '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(2, 'Verified', 'Report confirmed as valid waste concern', '#3B82F6', '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(3, 'In Progress', 'Report currently being addressed', '#8B5CF6', '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(4, 'Resolved', 'Waste concern has been addressed', '#10B981', '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(5, 'Rejected', 'Report identified as invalid', '#EF4444', '2026-07-25 13:56:31', '2026-07-25 13:56:31');

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
-- Table structure for table `report_supports`
--

CREATE TABLE `report_supports` (
  `support_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supported_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'Full system access and configuration', '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(2, 'Supervisor', 'Monitoring and analytics access', '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(3, 'Resident', 'Report submission and tracking access', '2026-07-25 13:56:31', '2026-07-25 13:56:31');

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
  `middle_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `account_type` enum('resident','non-resident') DEFAULT 'resident',
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_front` varchar(255) DEFAULT NULL,
  `id_back` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `purok_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone_normalized` varchar(20) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `otp_verified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `middle_name`, `suffix`, `username`, `account_type`, `address`, `phone_number`, `email`, `password`, `id_front`, `id_back`, `role_id`, `position_id`, `purok_id`, `status`, `last_login`, `created_at`, `updated_at`, `phone_normalized`, `email_verified`, `otp_verified_at`, `deleted_at`) VALUES
(1, 'Barangay Captain', NULL, NULL, NULL, 'resident', 'Barangay Hall', '09123456789', 'captain@dulongbayan.ph', '$2y$10$Z/HeHO5k9Uu8kt3YsKCM9e0Q/5DnYYKhMTcDeQ3LDVX6KTj3iv2Gy', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-04-01 00:28:49', '2026-07-25 14:27:25', NULL, 0, NULL, NULL),
(2, 'Secretary Rose', NULL, NULL, NULL, 'resident', 'Barangay Hall', '09123456788', 'secretary@dulongbayan.ph', '$2y$10$Fc.nsw9nNPbluMgzYQjdcexP17LAZDc44VmKa1j0CoaXUV4dawUfm', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-04-01 00:28:49', '2026-07-25 14:27:25', NULL, 0, NULL, NULL),
(3, 'Hans Flores', NULL, NULL, NULL, 'resident', 'brgy.testing.testing', '09951281511', 'floressktt11@gmail.com', '$2y$10$.jggu7XHDkz65Y2Q5L0mOOdnhB9MFl3TjKeSFVfSLrMERH.de9AAy', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-04-01 01:05:23', '2026-07-25 13:54:04', NULL, 0, NULL, NULL),
(15, 'asdasdadad', NULL, NULL, NULL, 'resident', '232323232323', '09951281511', 'floererererer@gmail.com', '$2y$10$fL/0SLQG2zLnUEniuGsTU.ulSh4yLbmpwPMkWFLIbqc2OU1xF1Niq', '/uploads/ids/front_6a6252ecf1d05.jpg', '/uploads/ids/back_6a6252ed00579.jpg', 3, 6, 1, 'pending', NULL, '2026-07-24 01:44:13', '2026-07-25 13:54:04', NULL, 0, NULL, NULL),
(16, 'test email otp', NULL, NULL, NULL, 'resident', 'awwsdasdad', '09951281511', 'testingotp@gmail.com', '$2y$10$NlxWm4KHBTA2PPazo0MN7ehPrv3RmAtUukEAO8QavMGJAmG.0znou', '/uploads/ids/front_6a6258475b015.jpg', '/uploads/ids/back_6a6258475be3f.jpg', 3, 6, 1, 'pending', NULL, '2026-07-24 02:07:03', '2026-07-25 13:54:04', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_reports_full`
-- (See below for the actual view)
--
CREATE TABLE `v_reports_full` (
`id` int(11)
,`resident_id` int(11)
,`reporter_name` varchar(100)
,`reporter_email` varchar(100)
,`reporter_phone` varchar(20)
,`description` text
,`latitude` decimal(10,8)
,`longitude` decimal(11,8)
,`location` varchar(255)
,`status` varchar(50)
,`status_color` varchar(7)
,`waste_category` varchar(100)
,`estimated_quantity` varchar(50)
,`waste_condition` varchar(50)
,`purok` varchar(50)
,`submission_date` datetime
,`updated_at` datetime
,`support_count` int(11)
,`photo_path` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `waste_categories`
--

CREATE TABLE `waste_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `recommended_action` text DEFAULT NULL,
  `severity_level` enum('low','medium','high','critical') DEFAULT 'medium',
  `icon` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_categories`
--

INSERT INTO `waste_categories` (`category_id`, `category_name`, `description`, `recommended_action`, `severity_level`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Illegal Dumping', 'Waste disposed in unauthorized areas', 'Conduct site inspection and investigate recurring dumping activities', 'high', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(2, 'Overflowing Garbage Bin', 'Garbage bins filled beyond capacity', 'Increase collection frequency and evaluate need for additional bins', 'medium', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(3, 'Uncollected Garbage', 'Waste not collected on scheduled day', 'Prioritize waste collection for the affected area', 'medium', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(4, 'Construction Waste', 'Waste from construction or demolition activities', 'Coordinate with construction site owners for proper disposal', 'low', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(5, 'Yard Waste', 'Leaves, branches, and garden waste', 'Schedule yard waste collection or composting', 'low', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(6, 'Hazardous Waste', 'Waste containing hazardous materials', 'Coordinate with environmental agency for proper handling', 'critical', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(7, 'Blocking Drainage', 'Waste blocking drainage or waterways', 'Coordinate immediate clearing to reduce flooding risks', 'high', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(8, 'Blocking Roadway', 'Waste blocking public roads', 'Immediate removal required for public safety', 'critical', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(9, 'Others', 'Waste that does not fit other categories', 'Review and assign appropriate category', 'medium', NULL, 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `waste_conditions`
--

CREATE TABLE `waste_conditions` (
  `condition_id` int(11) NOT NULL,
  `condition_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_conditions`
--

INSERT INTO `waste_conditions` (`condition_id`, `condition_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Newly Dumped', 'Recently disposed waste', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(2, 'Accumulating', 'Waste accumulating over time', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(3, 'Overflowing', 'Waste exceeding containment', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(4, 'Scattered', 'Waste spread over an area', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(5, 'Blocking Drainage', 'Waste obstructing water flow', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31'),
(6, 'Blocking Roadway', 'Waste obstructing public roads', 1, '2026-07-25 13:56:31', '2026-07-25 13:56:31');

-- --------------------------------------------------------

--
-- Structure for view `v_reports_full`
--
DROP TABLE IF EXISTS `v_reports_full`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_reports_full`  AS SELECT `r`.`id` AS `id`, `r`.`resident_id` AS `resident_id`, `u`.`name` AS `reporter_name`, `u`.`email` AS `reporter_email`, `u`.`phone_number` AS `reporter_phone`, `r`.`description` AS `description`, `r`.`latitude` AS `latitude`, `r`.`longitude` AS `longitude`, `r`.`location` AS `location`, `rs`.`status_name` AS `status`, `rs`.`color_code` AS `status_color`, `wc`.`category_name` AS `waste_category`, `eq`.`quantity_name` AS `estimated_quantity`, `wcnd`.`condition_name` AS `waste_condition`, `p`.`purok_name` AS `purok`, `r`.`submission_date` AS `submission_date`, `r`.`updated_at` AS `updated_at`, `r`.`support_count` AS `support_count`, coalesce((select `report_photos`.`photo_path` from `report_photos` where `report_photos`.`report_id` = `r`.`id` and `report_photos`.`is_primary` = 1 limit 1),(select `report_photos`.`photo_path` from `report_photos` where `report_photos`.`report_id` = `r`.`id` limit 1)) AS `photo_path` FROM ((((((`reports` `r` left join `users` `u` on(`r`.`resident_id` = `u`.`id`)) left join `report_statuses` `rs` on(`r`.`status_id` = `rs`.`status_id`)) left join `waste_categories` `wc` on(`r`.`category_id` = `wc`.`category_id`)) left join `estimated_quantities` `eq` on(`r`.`quantity_id` = `eq`.`quantity_id`)) left join `waste_conditions` `wcnd` on(`r`.`condition_id` = `wcnd`.`condition_id`)) left join `puroks` `p` on(`r`.`purok_id` = `p`.`purok_id`)) ;

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
-- Indexes for table `announcement_visibilities`
--
ALTER TABLE `announcement_visibilities`
  ADD PRIMARY KEY (`visibility_id`),
  ADD UNIQUE KEY `visibility_name` (`visibility_name`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action` (`action`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`);

--
-- Indexes for table `barangay_boundaries`
--
ALTER TABLE `barangay_boundaries`
  ADD PRIMARY KEY (`boundary_id`),
  ADD KEY `barangay_id` (`barangay_id`),
  ADD KEY `created_by` (`created_by`),
  ADD SPATIAL KEY `polygon_geometry` (`polygon_geometry`);

--
-- Indexes for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `collection_day` (`collection_day`);

--
-- Indexes for table `collection_schedule_puroks`
--
ALTER TABLE `collection_schedule_puroks`
  ADD PRIMARY KEY (`schedule_purok_id`),
  ADD UNIQUE KEY `schedule_id` (`schedule_id`,`purok_id`),
  ADD KEY `purok_id` (`purok_id`);

--
-- Indexes for table `email_otp_rate_limits`
--
ALTER TABLE `email_otp_rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_ip_window` (`email`,`ip`,`window_start`),
  ADD KEY `email` (`email`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `estimated_quantities`
--
ALTER TABLE `estimated_quantities`
  ADD PRIMARY KEY (`quantity_id`),
  ADD UNIQUE KEY `quantity_name` (`quantity_name`);

--
-- Indexes for table `heatmap_settings`
--
ALTER TABLE `heatmap_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `map_landmarks`
--
ALTER TABLE `map_landmarks`
  ADD PRIMARY KEY (`landmark_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `landmark_type` (`landmark_type`);

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
-- Indexes for table `notification_types`
--
ALTER TABLE `notification_types`
  ADD PRIMARY KEY (`notification_type_id`),
  ADD UNIQUE KEY `notification_type_name` (`notification_type_name`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`),
  ADD UNIQUE KEY `position_name` (`position_name`);

--
-- Indexes for table `puroks`
--
ALTER TABLE `puroks`
  ADD PRIMARY KEY (`purok_id`),
  ADD UNIQUE KEY `barangay_id` (`barangay_id`,`purok_name`);

--
-- Indexes for table `purok_boundaries`
--
ALTER TABLE `purok_boundaries`
  ADD PRIMARY KEY (`boundary_id`),
  ADD KEY `purok_id` (`purok_id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD SPATIAL KEY `polygon_geometry` (`polygon_geometry`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `fk_reports_category_id` (`category_id`),
  ADD KEY `fk_reports_quantity_id` (`quantity_id`),
  ADD KEY `fk_reports_condition_id` (`condition_id`),
  ADD KEY `fk_reports_status_id` (`status_id`),
  ADD KEY `fk_reports_purok_id` (`purok_id`);

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
-- Indexes for table `report_generation_settings`
--
ALTER TABLE `report_generation_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `report_photos`
--
ALTER TABLE `report_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `report_settings`
--
ALTER TABLE `report_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `report_statuses`
--
ALTER TABLE `report_statuses`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

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
-- Indexes for table `report_supports`
--
ALTER TABLE `report_supports`
  ADD PRIMARY KEY (`support_id`),
  ADD UNIQUE KEY `report_id` (`report_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `report_id_2` (`report_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

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
  ADD KEY `status` (`status`),
  ADD KEY `fk_users_role_id` (`role_id`),
  ADD KEY `fk_users_position_id` (`position_id`),
  ADD KEY `fk_users_purok_id` (`purok_id`);

--
-- Indexes for table `waste_categories`
--
ALTER TABLE `waste_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `waste_conditions`
--
ALTER TABLE `waste_conditions`
  ADD PRIMARY KEY (`condition_id`),
  ADD UNIQUE KEY `condition_name` (`condition_name`);

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
-- AUTO_INCREMENT for table `announcement_visibilities`
--
ALTER TABLE `announcement_visibilities`
  MODIFY `visibility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=954;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barangay_boundaries`
--
ALTER TABLE `barangay_boundaries`
  MODIFY `boundary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_schedule_puroks`
--
ALTER TABLE `collection_schedule_puroks`
  MODIFY `schedule_purok_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_otp_rate_limits`
--
ALTER TABLE `email_otp_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `estimated_quantities`
--
ALTER TABLE `estimated_quantities`
  MODIFY `quantity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `heatmap_settings`
--
ALTER TABLE `heatmap_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `map_landmarks`
--
ALTER TABLE `map_landmarks`
  MODIFY `landmark_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `notification_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `puroks`
--
ALTER TABLE `puroks`
  MODIFY `purok_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `purok_boundaries`
--
ALTER TABLE `purok_boundaries`
  MODIFY `boundary_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `report_generation_settings`
--
ALTER TABLE `report_generation_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_photos`
--
ALTER TABLE `report_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_settings`
--
ALTER TABLE `report_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_statuses`
--
ALTER TABLE `report_statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
-- AUTO_INCREMENT for table `report_supports`
--
ALTER TABLE `report_supports`
  MODIFY `support_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT for table `waste_categories`
--
ALTER TABLE `waste_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `waste_conditions`
--
ALTER TABLE `waste_conditions`
  MODIFY `condition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
-- Constraints for table `barangay_boundaries`
--
ALTER TABLE `barangay_boundaries`
  ADD CONSTRAINT `barangay_boundaries_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `barangay_boundaries_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  ADD CONSTRAINT `collection_schedules_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `collection_schedule_puroks`
--
ALTER TABLE `collection_schedule_puroks`
  ADD CONSTRAINT `collection_schedule_puroks_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `collection_schedules` (`schedule_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_schedule_puroks_ibfk_2` FOREIGN KEY (`purok_id`) REFERENCES `puroks` (`purok_id`) ON DELETE CASCADE;

--
-- Constraints for table `heatmap_settings`
--
ALTER TABLE `heatmap_settings`
  ADD CONSTRAINT `heatmap_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `map_landmarks`
--
ALTER TABLE `map_landmarks`
  ADD CONSTRAINT `map_landmarks_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `puroks`
--
ALTER TABLE `puroks`
  ADD CONSTRAINT `puroks_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON DELETE CASCADE;

--
-- Constraints for table `purok_boundaries`
--
ALTER TABLE `purok_boundaries`
  ADD CONSTRAINT `purok_boundaries_ibfk_1` FOREIGN KEY (`purok_id`) REFERENCES `puroks` (`purok_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purok_boundaries_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_category_id` FOREIGN KEY (`category_id`) REFERENCES `waste_categories` (`category_id`),
  ADD CONSTRAINT `fk_reports_condition_id` FOREIGN KEY (`condition_id`) REFERENCES `waste_conditions` (`condition_id`),
  ADD CONSTRAINT `fk_reports_purok_id` FOREIGN KEY (`purok_id`) REFERENCES `puroks` (`purok_id`),
  ADD CONSTRAINT `fk_reports_quantity_id` FOREIGN KEY (`quantity_id`) REFERENCES `estimated_quantities` (`quantity_id`),
  ADD CONSTRAINT `fk_reports_status_id` FOREIGN KEY (`status_id`) REFERENCES `report_statuses` (`status_id`),
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_flags`
--
ALTER TABLE `report_flags`
  ADD CONSTRAINT `report_flags_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_flags_ibfk_2` FOREIGN KEY (`flagged_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_flags_ibfk_3` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `report_generation_settings`
--
ALTER TABLE `report_generation_settings`
  ADD CONSTRAINT `report_generation_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `report_photos`
--
ALTER TABLE `report_photos`
  ADD CONSTRAINT `report_photos_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_settings`
--
ALTER TABLE `report_settings`
  ADD CONSTRAINT `report_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
-- Constraints for table `report_supports`
--
ALTER TABLE `report_supports`
  ADD CONSTRAINT `report_supports_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_supports_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_position_id` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`),
  ADD CONSTRAINT `fk_users_purok_id` FOREIGN KEY (`purok_id`) REFERENCES `puroks` (`purok_id`),
  ADD CONSTRAINT `fk_users_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
