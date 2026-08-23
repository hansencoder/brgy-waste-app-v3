-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2026 at 06:26 AM
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
  `cover_image` varchar(255) DEFAULT NULL,
  `publish_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `visibility_id` int(11) DEFAULT 1,
  `status` enum('draft','scheduled','published','expired') DEFAULT 'published',
  `expiration_date` datetime DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `cover_image`, `publish_date`, `created_by`, `created_at`, `visibility_id`, `status`, `expiration_date`, `is_published`) VALUES
(8, 'Special Collection Notice — July 26, 2026 cancelled', 'Due to the national holiday, waste collection in Zones A, B, and C is rescheduled to Saturday, July 26. Please place bins at the curb no later than 6:00 AM.', NULL, NULL, 1, '2026-07-26 06:58:41', 1, 'published', NULL, 1),
(9, 'Schedule Postponed: Monday', 'The collection schedule for Monday has been postponed. New date: July 23, 2026. Reason: holiday testing', NULL, NULL, 2, '2026-08-02 08:50:16', 1, 'published', NULL, 1);

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
(1, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 00:03:37', NULL, NULL, NULL, NULL),
(2, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 00:06:28', NULL, NULL, NULL, NULL),
(3, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-21 01:26:23', NULL, NULL, NULL, NULL),
(4, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 01:49:08', NULL, NULL, NULL, NULL),
(5, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 01:49:19', NULL, NULL, NULL, NULL),
(6, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 01:49:19', NULL, NULL, NULL, NULL),
(7, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 01:50:43', NULL, NULL, NULL, NULL),
(8, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 01:50:48', NULL, NULL, NULL, NULL),
(9, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-21 02:36:55', NULL, NULL, NULL, NULL),
(10, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:00:14', NULL, NULL, NULL, NULL),
(11, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:00:27', NULL, NULL, NULL, NULL),
(12, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:00:27', NULL, NULL, NULL, NULL),
(13, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:01:53', NULL, NULL, NULL, NULL),
(14, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:04:56', NULL, NULL, NULL, NULL),
(15, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-21 16:35:35', NULL, NULL, NULL, NULL),
(16, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:47:23', NULL, NULL, NULL, NULL),
(17, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:47:53', NULL, NULL, NULL, NULL),
(18, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 16:47:53', NULL, NULL, NULL, NULL),
(19, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-21 17:24:46', NULL, NULL, NULL, NULL),
(20, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 18:57:33', NULL, NULL, NULL, NULL),
(21, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 18:57:57', NULL, NULL, NULL, NULL),
(22, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 18:57:57', NULL, NULL, NULL, NULL),
(23, 2, 'Profile Updated', 'Profile', 'Admin updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 18:58:19', NULL, NULL, NULL, NULL),
(24, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 18:58:33', NULL, NULL, NULL, NULL),
(25, 2, 'View Report', 'Report ID 29', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 18:58:44', NULL, NULL, NULL, NULL),
(26, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 18:59:40', NULL, NULL, NULL, NULL),
(27, 2, 'Audit Archive Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 19:00:09', NULL, NULL, NULL, NULL),
(28, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 19:00:25', NULL, NULL, NULL, NULL),
(29, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 19:00:27', NULL, NULL, NULL, NULL),
(30, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-21 20:37:52', NULL, NULL, NULL, NULL),
(31, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 20:38:06', NULL, NULL, NULL, NULL),
(32, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 20:38:30', NULL, NULL, NULL, NULL),
(33, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 20:38:31', NULL, NULL, NULL, NULL),
(34, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-21 20:38:33', NULL, NULL, NULL, NULL),
(35, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-22 10:36:31', NULL, NULL, NULL, NULL),
(36, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 10:36:45', NULL, NULL, NULL, NULL),
(37, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 10:39:31', NULL, NULL, NULL, NULL),
(38, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 10:39:31', NULL, NULL, NULL, NULL),
(39, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 10:39:38', NULL, NULL, NULL, NULL),
(40, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 10:39:41', NULL, NULL, NULL, NULL),
(41, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 10:39:57', NULL, NULL, NULL, NULL),
(42, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 11:06:31', NULL, NULL, NULL, NULL),
(43, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 11:07:03', NULL, NULL, NULL, NULL),
(44, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 11:07:04', NULL, NULL, NULL, NULL),
(45, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 11:07:19', NULL, NULL, NULL, NULL),
(46, 2, 'View Report', 'Report ID 1', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 11:07:33', NULL, NULL, NULL, NULL),
(47, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-22 11:38:31', NULL, NULL, NULL, NULL),
(48, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 11:38:48', NULL, NULL, NULL, NULL),
(49, 2, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-22 12:11:25', NULL, NULL, NULL, NULL),
(50, 2, '2FA Resend', 'User', 'Code resent to email', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-22 12:12:25', NULL, NULL, NULL, NULL),
(51, 2, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'failed', '2026-08-22 12:12:44', NULL, NULL, NULL, NULL),
(52, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-22 12:12:59', NULL, NULL, NULL, NULL),
(53, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-22 12:12:59', NULL, NULL, NULL, NULL),
(54, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 12:13:25', NULL, NULL, NULL, NULL),
(55, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 12:13:41', NULL, NULL, NULL, NULL),
(56, 3, 'Report Edited', 'Waste Report', 'User updated details for pending report ID 29', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-22 12:16:23', NULL, NULL, NULL, NULL),
(57, 3, 'Report Submitted', 'Waste Report', 'User submitted report ID 31', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 12:21:24', NULL, NULL, NULL, NULL),
(58, 3, 'Report Edited', 'Waste Report', 'User updated details for pending report ID 31', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 12:22:46', NULL, NULL, NULL, NULL),
(59, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 12:25:18', NULL, NULL, NULL, NULL),
(60, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 12:25:31', NULL, NULL, NULL, NULL),
(61, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-22 14:03:27', NULL, NULL, NULL, NULL),
(62, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-22 14:03:38', NULL, NULL, NULL, NULL),
(63, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 14:03:38', NULL, NULL, NULL, NULL),
(64, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 14:03:51', NULL, NULL, NULL, NULL),
(65, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 14:04:40', NULL, NULL, NULL, NULL),
(66, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 14:04:40', NULL, NULL, NULL, NULL),
(67, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 14:06:24', NULL, NULL, NULL, NULL),
(68, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-22 14:53:21', NULL, NULL, NULL, NULL),
(69, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-22 14:57:54', NULL, NULL, NULL, NULL),
(70, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:16:41', NULL, NULL, NULL, NULL),
(71, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:16:57', NULL, NULL, NULL, NULL),
(72, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:16:57', NULL, NULL, NULL, NULL),
(73, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:19:12', NULL, NULL, NULL, NULL),
(74, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:19:24', NULL, NULL, NULL, NULL),
(75, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:19:50', NULL, NULL, NULL, NULL),
(76, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:20:11', NULL, NULL, NULL, NULL),
(77, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:20:13', NULL, NULL, NULL, NULL),
(78, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:20:14', NULL, NULL, NULL, NULL),
(79, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:20:51', NULL, NULL, NULL, NULL),
(80, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:20:53', NULL, NULL, NULL, NULL),
(81, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:21:03', NULL, NULL, NULL, NULL),
(82, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:21:14', NULL, NULL, NULL, NULL),
(83, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:26:01', NULL, NULL, NULL, NULL),
(84, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:29:10', NULL, NULL, NULL, NULL),
(85, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:29:16', NULL, NULL, NULL, NULL),
(86, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:35:06', NULL, NULL, NULL, NULL),
(87, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:59:45', NULL, NULL, NULL, NULL),
(88, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 16:59:56', NULL, NULL, NULL, NULL),
(89, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 17:00:15', NULL, NULL, NULL, NULL),
(90, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-22 17:35:52', NULL, NULL, NULL, NULL),
(91, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:04:56', NULL, NULL, NULL, NULL),
(92, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:05:12', NULL, NULL, NULL, NULL),
(93, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:05:12', NULL, NULL, NULL, NULL),
(94, 2, 'View Report', 'Report ID 33', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:05:22', NULL, NULL, NULL, NULL),
(95, 2, 'View Report', 'Report ID 33', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:05:31', NULL, NULL, NULL, NULL),
(96, 2, 'Report Verified', 'Report ID 33', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:09:46', NULL, NULL, NULL, NULL),
(97, 2, 'View Report', 'Report ID 33', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:09:53', NULL, NULL, NULL, NULL),
(98, 2, 'View Report', 'Report ID 33', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:09:57', NULL, NULL, NULL, NULL),
(99, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:10:13', NULL, NULL, NULL, NULL),
(100, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:10:16', NULL, NULL, NULL, NULL),
(101, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:10:20', NULL, NULL, NULL, NULL),
(102, 2, 'Report Verified', 'Report ID 31', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:10:21', NULL, NULL, NULL, NULL),
(103, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:10:29', NULL, NULL, NULL, NULL),
(104, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:11:38', NULL, NULL, NULL, NULL),
(105, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:11:47', NULL, NULL, NULL, NULL),
(106, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:17:48', NULL, NULL, NULL, NULL),
(107, 2, 'Report In Progress', 'Report ID 31', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:18:02', NULL, NULL, NULL, NULL),
(108, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:18:08', NULL, NULL, NULL, NULL),
(109, 2, 'Report Resolved', 'Report ID 31', 'Resolved report. Remark: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:18:46', NULL, NULL, NULL, NULL),
(110, 2, 'View Report', 'Report ID 31', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:18:53', NULL, NULL, NULL, NULL),
(111, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:19:13', NULL, NULL, NULL, NULL),
(112, 2, 'View Report', 'Report ID 33', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:19:20', NULL, NULL, NULL, NULL),
(113, 2, 'Report In Progress', 'Report ID 33', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:19:23', NULL, NULL, NULL, NULL),
(114, 2, 'View Report', 'Report ID 33', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:19:29', NULL, NULL, NULL, NULL),
(115, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:35:22', NULL, NULL, NULL, NULL),
(116, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:37:53', NULL, NULL, NULL, NULL),
(117, 2, 'Report Verified', 'Report ID 34', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:37:55', NULL, NULL, NULL, NULL),
(118, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:38:01', NULL, NULL, NULL, NULL),
(119, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:43:10', NULL, NULL, NULL, NULL),
(120, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-22 19:43:12', NULL, NULL, NULL, NULL),
(121, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-23 10:56:22', NULL, NULL, NULL, NULL),
(122, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 10:59:37', NULL, NULL, NULL, NULL),
(123, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 10:59:44', NULL, NULL, NULL, NULL),
(124, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 10:59:44', NULL, NULL, NULL, NULL),
(125, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:00:08', NULL, NULL, NULL, NULL),
(126, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:00:09', NULL, NULL, NULL, NULL),
(127, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:00:47', NULL, NULL, NULL, NULL),
(128, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:00:54', NULL, NULL, NULL, NULL),
(129, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:22:54', NULL, NULL, NULL, NULL),
(130, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:25:56', NULL, NULL, NULL, NULL),
(131, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:27:01', NULL, NULL, NULL, NULL),
(132, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:32:05', NULL, NULL, NULL, NULL),
(133, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:33:29', NULL, NULL, NULL, NULL),
(134, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:33:31', NULL, NULL, NULL, NULL),
(135, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:34:11', NULL, NULL, NULL, NULL),
(136, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:34:12', NULL, NULL, NULL, NULL),
(137, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:34:23', NULL, NULL, NULL, NULL),
(138, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:34:24', NULL, NULL, NULL, NULL),
(139, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:34:27', NULL, NULL, NULL, NULL),
(140, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:34:33', NULL, NULL, NULL, NULL),
(141, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:34:35', NULL, NULL, NULL, NULL),
(142, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:41:37', NULL, NULL, NULL, NULL),
(143, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:41:56', NULL, NULL, NULL, NULL),
(144, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:41:58', NULL, NULL, NULL, NULL),
(145, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:42:19', NULL, NULL, NULL, NULL),
(146, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:42:22', NULL, NULL, NULL, NULL),
(147, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:43:19', NULL, NULL, NULL, NULL),
(148, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:43:22', NULL, NULL, NULL, NULL),
(149, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:49:34', NULL, NULL, NULL, NULL),
(150, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:49:45', NULL, NULL, NULL, NULL),
(151, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:50:18', NULL, NULL, NULL, NULL),
(152, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:50:20', NULL, NULL, NULL, NULL),
(153, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:50:32', NULL, NULL, NULL, NULL),
(154, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:50:34', NULL, NULL, NULL, NULL),
(155, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:51:21', NULL, NULL, NULL, NULL),
(156, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:51:36', NULL, NULL, NULL, NULL),
(157, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:51:53', NULL, NULL, NULL, NULL),
(158, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:52:04', NULL, NULL, NULL, NULL),
(159, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:52:06', NULL, NULL, NULL, NULL),
(160, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:55:54', NULL, NULL, NULL, NULL),
(161, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:55:58', NULL, NULL, NULL, NULL),
(162, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:56:04', NULL, NULL, NULL, NULL),
(163, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:56:06', NULL, NULL, NULL, NULL),
(164, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:56:09', NULL, NULL, NULL, NULL),
(165, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 11:56:17', NULL, NULL, NULL, NULL),
(166, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:02:28', NULL, NULL, NULL, NULL),
(167, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:02:30', NULL, NULL, NULL, NULL),
(168, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:03:26', NULL, NULL, NULL, NULL),
(169, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:03:27', NULL, NULL, NULL, NULL),
(170, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:08:44', NULL, NULL, NULL, NULL),
(171, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:09:07', NULL, NULL, NULL, NULL),
(172, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:20:49', NULL, NULL, NULL, NULL),
(173, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings & intervals', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:20:54', NULL, NULL, NULL, NULL),
(174, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:21:37', NULL, NULL, NULL, NULL),
(175, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:21:46', NULL, NULL, NULL, NULL),
(176, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:22:07', NULL, NULL, NULL, NULL),
(177, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-23 12:23:03', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs_archive`
--

CREATE TABLE `audit_logs_archive` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `affected_record` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `result` varchar(50) DEFAULT 'success',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `system_name` varchar(255) DEFAULT 'Barangay Waste Management System',
  `system_short_name` varchar(100) DEFAULT 'WasteWatch',
  `system_motto` varchar(255) DEFAULT 'Efficient, Transparent & Eco-Friendly Community Waste Management',
  `system_logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `barangay_name`, `municipality`, `province`, `region`, `official_address`, `contact_number`, `official_email`, `barangay_logo`, `created_at`, `updated_at`, `system_name`, `system_short_name`, `system_motto`, `system_logo`) VALUES
(1, 'Dulong Bayan', 'Quezon', 'Nueva Ecija', 'Central Luzon', 'brgy dulong bayan, quezon, nueva ecija', '09951281511', 'floreshans.neust@gmail.com', '/uploads/logos/brgy_seal_1787217917.jpg', '2026-07-25 14:27:25', '2026-08-20 17:25:17', 'Barangay Waste Management System', 'LINARAYA', '', '/uploads/logos/sys_logo_1787217917.jpg');

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `center_latitude` decimal(10,8) DEFAULT 15.55800000,
  `center_longitude` decimal(11,8) DEFAULT 120.80300000,
  `default_zoom` int(11) DEFAULT 15,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_boundaries`
--

INSERT INTO `barangay_boundaries` (`boundary_id`, `barangay_id`, `polygon_geometry`, `created_by`, `created_at`, `updated_at`, `center_latitude`, `center_longitude`, `default_zoom`, `updated_by`) VALUES
(1, 1, 0x00000000010300000001000000500000000406a45849335e40ea27f796cd232f40ccac4ac741335e40446ff1f09e232f40fdee67c240335e400a3b7b1d27232f40fe70a13c3d335e40d965aec21c232f403aebae473c335e40ffbbf4e5bb222f403e31a13836335e406cf8cc4873222f40747b496334335e409e341eb63e222f40b5368ded35335e402157ea5910222f406c054d4b2c335e401af8510dfb212f407d7b325a22335e40827ae9dca3212f40af6d9bf31d335e4076fd82ddb0212f40b1378cdd19335e4036ba394b7f212f40374591ff13335e40fe65f7e461212f407ced3e6c0e335e40fd1d407562212f40952710760a335e409ec25b316d212f40698d412704335e404fb4064a65212f4092318859f9325e40689ce16135212f40b961ca76f4325e4046e5cbb0f6202f402ffce07cea325e4045c5eef1f8202f40a314be08e4325e4041cddab7a4202f40258b451de7325e408016010462202f406724e769e5325e40b3b3e89d0a202f4051d77f42e2325e40c7dd7b139e1f2f4069bd29f6e1325e40111c9771531f2f4078865fa0da325e40e61ce159271f2f407e9fbb6edb325e40d3f71a82e31e2f4021640fa3d6325e40efa023038a1e2f4097fe25a9cc325e408698011d9c1e2f40567ce827c1325e40757632384a1e2f40fec9294bbe325e405885178e311d2f40e006c60bb3325e40b48f15fc361c2f40592cea38a3325e40cb2c42b1151c2f4046a11b50a5325e40c5313784cf1b2f408c135feda8325e40c1351c3b4d1b2f4001ab7e00ad325e4074e904d9571b2f40c19f2b5b35335e40d3beb9bf7a182f40b3e8425145335e40dbbfb2d2a4182f407bf42b4256335e40eeaaac12d1182f400a174dc260335e400ff91d79d6182f40181f0b7668335e40dfd1b5e512192f400245d1a865335e4049d0042f9f192f409638978771335e40fcabc77dab192f40bbbe6ac07b335e4006234097ba192f409f5be84a84335e4082700514ea192f4050bba99292335e40ea5c514a081a2f409363fc459a335e4090f98040671a2f4045acb47ba8335e408a7a1c61ac1a2f4011ab3fc2b0335e40aee06d94ab1a2f4090b3fa34b8335e408d96033dd41a2f40cf3758eebb335e4055c444decd1a2f4050076811c0335e40f972558fea1a2f407e344179c4335e40de2dda2d131b2f40ad57ec8af4335e40e7902ffc3b1a2f403469ae2e02345e4047ca1649bb192f409bf6de292e345e402faea70cd21a2f40dd28b2d650345e4089ee59d768192f40f89e364f9a345e405616e016871a2f40290417d0b0345e404b4dccc4be1a2f40e152df43d3345e403fe834666d1a2f40a65714c0de345e4077e1bd59391a2f40d6c56d3400355e408e0d935ff51a2f40dadb77561c355e4040a77bf88d1a2f4094162eab30355e407cf6b75f991a2f409774948359355e40e91505b0b71b2f407bdd7de948355e40cc4642b6871c2f40e0e7099549355e40b509e69e641d2f40642e5f4d54355e40c183b064d81e2f40b081be3e04355e403041b22550212f401a5822abb6345e40279b17cc04242f4034ba83d899345e40075273034f232f4004bd81139b345e4038e4558ca9222f404750a15f80345e4011829f820d212f40aa65c63684345e40d89a5203721e2f40e0a8705f62345e40d377c8de9c1f2f4098c4b4ca27345e4048e757ce391f2f409fd44d1803345e407872970ee71f2f4045c1e7e2ca335e409d352ed27a1e2f404ea8966368335e403510262081222f40e514d33b4b335e40d1f35d00d0232f400406a45849335e40ea27f796cd232f40, 1, '2026-08-16 06:39:50', '2026-08-16 07:02:44', 15.55800000, 120.80300000, 15, 2);

-- --------------------------------------------------------

--
-- Table structure for table `collection_notes`
--

CREATE TABLE `collection_notes` (
  `note_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `special_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_schedules`
--

INSERT INTO `collection_schedules` (`schedule_id`, `collection_day`, `start_time`, `end_time`, `waste_type`, `status`, `created_by`, `created_at`, `updated_at`, `special_notes`) VALUES
(8, 'Saturday', '06:00:00', '08:00:00', 'General', 'active', 2, '2026-08-15 02:30:46', '2026-08-16 17:37:44', 'na eedit ako'),
(9, 'Sunday', '10:40:00', '19:43:00', 'Non-Biodegradable', 'active', 2, '2026-08-16 07:40:51', '2026-08-16 07:40:51', '');

-- --------------------------------------------------------

--
-- Table structure for table `collection_schedule_puroks`
--

CREATE TABLE `collection_schedule_puroks` (
  `schedule_purok_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `purok_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_schedule_puroks`
--

INSERT INTO `collection_schedule_puroks` (`schedule_purok_id`, `schedule_id`, `purok_id`) VALUES
(45, 8, 1),
(46, 8, 2),
(47, 8, 3),
(48, 8, 4),
(49, 8, 5),
(42, 9, 2),
(43, 9, 3),
(44, 9, 4);

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
(198, 'floreshans.neust@gmail.com', '::1', '2026-08-22 13:00:00', 1),
(199, 'floreshans.neust@gmail.com', '::1', '2026-08-23 04:00:00', 1);

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
-- Table structure for table `guest_otp_tokens`
--

CREATE TABLE `guest_otp_tokens` (
  `id` int(11) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `token` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_otp_tokens`
--

INSERT INTO `guest_otp_tokens` (`id`, `phone`, `token`, `expires_at`, `is_used`, `attempts`, `ip`, `created_at`) VALUES
(1, '09951281511', '735473', '2026-08-10 02:05:06', 1, 1, '::1', '2026-08-09 18:00:06'),
(2, '09951281511', '000450', '2026-08-10 08:00:13', 1, 0, '::1', '2026-08-09 23:55:13'),
(3, '09951281511', '387667', '2026-08-10 08:03:32', 1, 0, '::1', '2026-08-09 23:58:32'),
(4, '09951281511', '767685', '2026-08-10 08:12:11', 1, 0, '::1', '2026-08-10 00:07:11'),
(5, '09951281511', '863607', '2026-08-10 08:14:59', 1, 0, '::1', '2026-08-10 00:09:59'),
(6, '09951281511', '914604', '2026-08-10 08:15:50', 1, 0, '::1', '2026-08-10 00:10:50'),
(7, '09123123123', '190492', '2026-08-10 08:22:41', 1, 0, '::1', '2026-08-10 00:17:41'),
(8, '09123123123', '626620', '2026-08-10 08:25:18', 1, 0, '::1', '2026-08-10 00:20:18'),
(9, '09123123123', '177837', '2026-08-10 08:26:44', 1, 0, '::1', '2026-08-10 00:21:44'),
(10, '09123123123', '404176', '2026-08-10 08:29:05', 1, 0, '::1', '2026-08-10 00:24:05'),
(11, '09123123123', '406234', '2026-08-10 08:30:10', 1, 0, '::1', '2026-08-10 00:25:10'),
(12, '09123123123', '018857', '2026-08-10 08:31:10', 0, 0, '::1', '2026-08-10 00:26:10'),
(13, '09951281333', '313123', '2026-08-10 08:32:04', 1, 0, '::1', '2026-08-10 00:27:04'),
(14, '09951281333', '183502', '2026-08-10 08:33:36', 1, 0, '::1', '2026-08-10 00:28:36'),
(15, '09951281333', '713762', '2026-08-10 08:35:18', 1, 0, '::1', '2026-08-10 00:30:18'),
(16, '09951281333', '147605', '2026-08-10 08:37:10', 1, 0, '::1', '2026-08-10 00:32:10'),
(17, '09951281333', '842708', '2026-08-10 08:39:22', 0, 0, '::1', '2026-08-10 00:34:22'),
(18, '09121212121', '436756', '2026-08-10 12:43:47', 1, 0, '::1', '2026-08-10 04:38:47'),
(19, '09121212121', '611784', '2026-08-10 12:46:44', 1, 0, '::1', '2026-08-10 04:41:44'),
(20, '09121212121', '766824', '2026-08-10 12:56:46', 1, 0, '::1', '2026-08-10 04:51:46'),
(21, '09951281511', '261213', '2026-08-16 08:13:27', 1, 0, '::1', '2026-08-16 00:08:27'),
(22, '09951281511', '463877', '2026-08-16 08:16:15', 1, 0, '::1', '2026-08-16 00:11:15'),
(23, '09951281511', '825180', '2026-08-16 08:56:52', 1, 0, '::1', '2026-08-16 00:51:52'),
(24, '09951281511', '397382', '2026-08-16 09:14:11', 1, 0, '::1', '2026-08-16 01:09:11'),
(25, '09951281511', '756733', '2026-08-16 09:19:24', 1, 0, '::1', '2026-08-16 01:14:24'),
(26, '09951281511', '135119', '2026-08-16 09:23:13', 1, 0, '::1', '2026-08-16 01:18:13'),
(27, '09951281511', '966293', '2026-08-16 09:24:40', 1, 0, '::1', '2026-08-16 01:19:40'),
(28, '09951281511', '105568', '2026-08-16 09:32:45', 1, 0, '::1', '2026-08-16 01:27:45'),
(29, '09951281511', '336498', '2026-08-16 09:35:23', 1, 0, '::1', '2026-08-16 01:30:23'),
(30, '09951281511', '078924', '2026-08-16 09:53:56', 1, 0, '::1', '2026-08-16 01:48:56'),
(31, '09121212121', '479242', '2026-08-16 09:58:14', 1, 0, '::1', '2026-08-16 01:53:14'),
(32, '09121212121', '382043', '2026-08-16 11:17:28', 1, 0, '::1', '2026-08-16 03:12:28'),
(33, '09121212121', '240460', '2026-08-16 12:41:42', 1, 0, '::1', '2026-08-16 04:36:42'),
(34, '09121212121', '166230', '2026-08-16 12:56:11', 1, 0, '::1', '2026-08-16 04:51:11'),
(35, '09121212121', '204412', '2026-08-16 13:32:34', 1, 0, '::1', '2026-08-16 05:27:34'),
(36, '09121212121', '963061', '2026-08-16 16:16:58', 1, 0, '::1', '2026-08-16 08:11:58'),
(37, '09951281511', '802020', '2026-08-17 16:42:02', 1, 0, '::1', '2026-08-17 08:37:02'),
(38, '09951281511', '325038', '2026-08-17 16:43:49', 1, 0, '::1', '2026-08-17 08:38:49'),
(39, '09951281511', '624216', '2026-08-17 17:55:56', 1, 0, '::1', '2026-08-17 09:50:56'),
(40, '09951281511', '964366', '2026-08-17 18:18:39', 1, 0, '::1', '2026-08-17 10:13:39'),
(41, '09121212121', '162787', '2026-08-18 23:25:07', 1, 0, '::1', '2026-08-18 15:20:07'),
(42, '09121212121', '347633', '2026-08-18 23:27:18', 0, 0, '::1', '2026-08-18 15:22:18'),
(43, 'hanseduinfo@gmail.com', '342170', '2026-08-22 18:55:15', 1, 0, '::1', '2026-08-22 10:50:15'),
(44, 'hanseduinfo@gmail.com', '048771', '2026-08-22 19:36:18', 1, 0, '::1', '2026-08-22 11:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `guest_sms_rate_limits`
--

CREATE TABLE `guest_sms_rate_limits` (
  `id` int(11) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `window_start` datetime NOT NULL,
  `send_count` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest_sms_rate_limits`
--

INSERT INTO `guest_sms_rate_limits` (`id`, `phone`, `ip`, `window_start`, `send_count`) VALUES
(1, '09951281511', '::1', '2026-08-09 20:00:00', 1),
(2, '09951281511', '::1', '2026-08-10 01:00:00', 2),
(4, '09951281511', '::1', '2026-08-10 02:00:00', 3),
(7, '09123123123', '::1', '2026-08-10 02:00:00', 6),
(13, '09951281333', '::1', '2026-08-10 02:00:00', 5),
(18, '09121212121', '::1', '2026-08-10 06:00:00', 3),
(21, '09951281511', '::1', '2026-08-16 02:00:00', 3),
(24, '09951281511', '::1', '2026-08-16 03:00:00', 7),
(31, '09121212121', '::1', '2026-08-16 03:00:00', 1),
(32, '09121212121', '::1', '2026-08-16 05:00:00', 1),
(33, '09121212121', '::1', '2026-08-16 06:00:00', 2),
(35, '09121212121', '::1', '2026-08-16 07:00:00', 1),
(36, '09121212121', '::1', '2026-08-16 10:00:00', 1),
(37, '09951281511', '::1', '2026-08-17 10:00:00', 2),
(39, '09951281511', '::1', '2026-08-17 11:00:00', 1),
(40, '09951281511', '::1', '2026-08-17 12:00:00', 1),
(41, '09121212121', '::1', '2026-08-18 17:00:00', 1),
(42, 'hanseduinfo@gmail.com', '::1', '2026-08-22 12:00:00', 1),
(43, 'hanseduinfo@gmail.com', '::1', '2026-08-22 13:00:00', 1);

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `low_min` int(11) DEFAULT 3,
  `low_max` int(11) DEFAULT 5,
  `moderate_min` int(11) DEFAULT 6,
  `moderate_max` int(11) DEFAULT 10,
  `severe_min` int(11) DEFAULT 11
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `heatmap_settings`
--

INSERT INTO `heatmap_settings` (`setting_id`, `radius_meters`, `minimum_reports`, `low_density_color`, `medium_density_color`, `high_density_color`, `updated_by`, `updated_at`, `low_min`, `low_max`, `moderate_min`, `moderate_max`, `severe_min`) VALUES
(1, 50, 3, '#fde68a', '#f97316', '#ef4444', 2, '2026-08-23 12:20:54', 3, 5, 6, 10, 11);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_history`
--

CREATE TABLE `maintenance_history` (
  `id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `maintenance_type` varchar(50) DEFAULT NULL,
  `maintenance_message` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `previous_status` tinyint(1) DEFAULT NULL,
  `new_status` tinyint(1) DEFAULT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_history`
--

INSERT INTO `maintenance_history` (`id`, `action`, `maintenance_type`, `maintenance_message`, `reason`, `previous_status`, `new_status`, `start_at`, `end_at`, `performed_by`, `ip_address`, `created_at`) VALUES
(1, 'DISABLE_MAINTENANCE_MODE', 'scheduled', 'The system is currently undergoing scheduled maintenance. We apologize for any inconvenience and will be back shortly.', '', 0, 0, NULL, NULL, 2, '::1', '2026-08-16 16:15:57'),
(2, 'UPDATE_MAINTENANCE_SETTINGS', 'scheduled', 'The system is currently undergoing scheduled maintenance. We apologize for any inconvenience and will be back shortly.', '', 0, 0, NULL, NULL, 2, '::1', '2026-08-16 16:17:40'),
(3, 'DISABLE_MAINTENANCE_MODE', 'emergency', 'The system is currently undergoing scheduled maintenance. We apologize for any inconvenience and will be back shortly.', '', 0, 0, NULL, NULL, 2, '::1', '2026-08-16 16:18:07'),
(4, 'UPDATE_MAINTENANCE_SETTINGS', 'emergency', 'The system is currently undergoing scheduled maintenance. We apologize for any inconvenience and will be back shortly.', 'Database migration', 0, 0, '2026-08-17 10:19:00', '2026-08-17 10:22:00', 2, '::1', '2026-08-17 02:20:12'),
(5, 'ENABLE_EMERGENCY_LOCKDOWN', 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'Under Maintenance', 0, 1, NULL, NULL, 2, '::1', '2026-08-17 02:24:22'),
(6, 'DISABLE_EMERGENCY_LOCKDOWN', 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'Under Maintenance', 1, 0, NULL, NULL, 2, '::1', '2026-08-17 02:27:10'),
(7, 'ENABLE_EMERGENCY_LOCKDOWN', 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'maintenance', 0, 1, NULL, NULL, 2, '::1', '2026-08-20 09:39:22'),
(8, 'DISABLE_EMERGENCY_LOCKDOWN', 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'maintenance', 1, 0, NULL, NULL, 2, '::1', '2026-08-20 09:42:06'),
(9, 'DISABLE_MAINTENANCE_MODE', 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'maintenance', 0, 0, NULL, NULL, 2, '::1', '2026-08-20 09:42:31');

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

--
-- Dumping data for table `map_landmarks`
--

INSERT INTO `map_landmarks` (`landmark_id`, `landmark_name`, `landmark_type`, `latitude`, `longitude`, `description`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'brgy hall', 'Barangay Hall', 15.56286800, 120.80499500, 'brgy hall', 1, 2, '2026-08-15 01:52:38', '2026-08-15 01:52:38');

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

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `report_id`, `announcement_id`, `type`, `title`, `content`, `is_read`, `send_to_all`, `created_at`) VALUES
(43, 3, NULL, NULL, 'Collection Schedule Update', 'Collection rescheduled to Saturday', 'Your purok\'s waste collection has been moved to Saturday at 6:00 AM due to the holiday.', 1, 0, '2026-07-26 08:43:32'),
(44, 3, NULL, NULL, 'New Announcement', 'New hazardous waste drop-off point', 'A new drop-off site is now available near the barangay center for batteries and electronics.', 1, 0, '2026-07-26 07:53:32'),
(45, 3, NULL, NULL, 'New Announcement', 'Clean-up drive reminder', 'Please join the riverbank clean-up this Sunday at 7:00 AM and bring reusable gloves.', 1, 0, '2026-07-25 08:53:32'),
(46, 3, NULL, NULL, 'Report Status Update', 'Your report has been verified', 'Your waste report #WR-001 has been verified by the barangay team.', 1, 0, '2026-07-26 06:53:32'),
(47, 3, NULL, NULL, 'Report Resolved', 'Your report has been resolved', 'Your waste report #WR-002 has been resolved. Thank you for your contribution!', 1, 0, '2026-07-26 08:23:32'),
(48, NULL, NULL, NULL, 'New Announcement', 'Barangay Clean-Up Drive this Saturday', 'Join us for a community clean-up drive this Saturday at 7:00 AM. Meet at the barangay hall.', 1, 1, '2026-07-24 08:53:32'),
(49, NULL, NULL, NULL, 'Collection Schedule Update', 'Holiday schedule changes', 'Collection schedule will be adjusted for the upcoming holidays. Please check the collection schedule page for details.', 1, 1, '2026-07-23 08:53:32'),
(50, 1, NULL, NULL, 'New Announcement', 'Monthly meeting reminder', 'Monthly barangay meeting scheduled for August 1, 2026 at 2:00 PM.', 0, 0, '2026-07-26 04:53:32'),
(51, 2, NULL, NULL, 'Report Status Update', 'New reports pending review', 'There are 5 new waste reports pending your review.', 1, 0, '2026-07-26 03:53:32'),
(104, 1, 31, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: no description', 0, 0, '2026-08-22 12:21:24'),
(105, 2, 31, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: no description', 1, 0, '2026-08-22 12:21:24'),
(106, 18, 31, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: no description', 0, 0, '2026-08-22 12:21:24'),
(107, NULL, 33, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-22 19:09:46'),
(108, 3, 31, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-22 19:10:21'),
(109, 3, 31, NULL, 'report_update', 'Report In Progress', 'Your report is now in progress and being addressed.', 0, 0, '2026-08-22 19:18:02'),
(110, 3, 31, NULL, 'report_update', 'Report Resolved', 'Your report has been resolved. Thank you for reporting!', 0, 0, '2026-08-22 19:18:46'),
(111, NULL, 33, NULL, 'report_update', 'Report In Progress', 'Your report is now in progress and being addressed.', 0, 0, '2026-08-22 19:19:23'),
(112, NULL, 34, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-22 19:37:55');

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
-- Table structure for table `penalty_rules`
--

CREATE TABLE `penalty_rules` (
  `rule_id` int(11) NOT NULL,
  `offense_no` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `rule_type` enum('prohibited_action','penalty') DEFAULT 'penalty',
  `description` text DEFAULT NULL,
  `legal_ref` varchar(150) DEFAULT NULL,
  `fine_range` varchar(150) DEFAULT NULL,
  `alt_penalty` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `purok_boundaries`
--

INSERT INTO `purok_boundaries` (`boundary_id`, `purok_id`, `polygon_geometry`, `updated_by`, `updated_at`) VALUES
(1, 1, 0x0000000001030000000100000020000000319a95ed43335e407731cd74af232f4044a4a65d4c335e40a1664815c5232f406abddf68c7335e403048fab48a1e2f40594fadbeba335e401f9dbaf2591e2f40fa60191bba325e4040deab56261c2f40bd8c62b9a5325e40cf6bec12d51b2f4023ba675da3325e4012d90759161c2f4046459c4eb2325e4073a087da361c2f404f232d95b7325e4080ef366f9c1c2f405d8aabcabe325e40e0d8b3e7321d2f406ea7ad11c1325e4080f0a1444b1e2f40850662d9cc325e407711a628971e2f409eb30584d6325e40417fa1478c1e2f40a79196cadb325e40c075c58cf01e2f40a8ab3b16db325e40795a7ee02a1f2f40ba8102efe4325e40b2135e82531f2f40c5ad8218e8325e40522b4cdf6b202f402c0fd253e4325e40a99f3715a9202f403a90f5d4ea325e40ea5910cafb202f4058ace122f7325e403106d671fc202f4037a968acfd325e40257497c459212f4053910a630b335e405e2d776682212f406781768714335e4034f8fbc56c212f407aa52c431c335e406fd8b628b3212f408c135fed28335e40f486fbc8ad212f40950b957f2d335e409c8bbfed09222f40a9fb00a436335e40902e36ad14222f40aa6395d233335e403733fad170222f40c899266c3f335e40df37bef6cc222f40c899266c3f335e40d658c2da18232f40d0436d1b46335e40dba6785c54232f40319a95ed43335e407731cd74af232f40, 2, '2026-08-16 22:35:50'),
(2, 2, 0x00000000010300000001000000110000007ec7f0d8cf335e40d21bee23b71e2f40060e68e90a345e40d656ec2fbb1f2f406b44300e2e345e40a4552de9281f2f40e19a3bfa5f345e4065e42cec691f2f402cf3565d87345e407b336abe4a1e2f402ea9da6e82345e40edbab72231212f407d1f0e12a2345e40c22ff5f3a6222f4069ad68739c345e4096e7c1dd59232f40a27dace0b7345e409eb30584d6232f4065fcfb8c0b355e4070eb6e9eea202f402c465d6bef345e40361e6cb1db1f2f40de1d19abcd345e40c5abac6d8a1f2f40b709f7cabc345e40a7e67283a11e2f40429946938b345e4062bce6559d1d2f40cbdaa6785c345e40e97de36bcf1c2f4007280d350a345e409df2e846581c2f407ec7f0d8cf335e40d21bee23b71e2f40, 2, '2026-08-16 22:36:26'),
(3, 3, 0x000000000103000000010000001100000057cd7344be335e407ec9c6832d1e2f4081971936ca335e40de9046054e1e2f40cfd90242eb335e40d2890453cd1c2f4043e21e4b1f345e40cee33098bf1a2f40f437a11001345e40f4ddad2cd1192f40e02d90a0f8335e4082583673481a2f4081971936ca335e403f56f0db101b2f40567f8461c0335e40fb96395d161b2f4044c362d4b5335e40eaebf99ae51a2f401c615111a7335e407ec7f0d8cf1a2f400abfd4cf9b335e40378aac35941a2f40f6b4c35f93335e404cc631923d1a2f40e27668588c335e406d1cb1169f1a2f40e25cc30c8d335e401421753bfb1a2f40e25cc30c8d335e40462234828d1b2f40f54c2f3196335e406af6402b301c2f4057cd7344be335e407ec9c6832d1e2f40, 2, '2026-08-16 22:37:02'),
(4, 4, 0x00000000010300000001000000170000008ae942acfe335e40ce70033e3f1c2f409f5be84a04345e403cbce7c0721c2f40b39943520b345e40f67ea31d371c2f406cec12d55b345e4033ddeba4be1c2f4057cd7344be345e40b056ed9a901e2f4072193735d0345e40895c70067f1f2f40c075c58cf0345e40de1d19abcd1f2f40116e32aa0c355e40923cd7f7e1202f40ac5626fc52355e40cf85915ed41e2f40840eba8443355e40c59107228b1c2f40b682a62556355e40eeb25f77ba1b2f40541c075e2d355e403dbb7cebc31a2f401762f54718355e40ea77616bb61a2f40d47fd6fcf8345e40861f9c4f1d1b2f408509a359d9345e406da818e76f1a2f40268dd13aaa345e402331410ddf1a2f408044132862345e4071395e81e8192f405930f14751345e40b0aa5e7ea7192f40f4c5de8b2f345e4025581ccefc1a2f40eacda8f92a345e40b98ac56f0a1b2f40e109bdfe24345e40d0ed258dd11a2f40a6b73f170d345e4035f0a31af61b2f408ae942acfe335e40ce70033e3f1c2f40, 2, '2026-08-16 22:38:05'),
(5, 5, 0x000000000103000000010000000a000000d8d825aab7325e40a6d1e4620c1c2f40ec1681b1be325e4022365838491b2f408e3ba583f5325e409cdd5a26c3192f4059dc7f643a335e402c7e5358a9182f40fa980f0874335e408466d7bd15192f4038876bb587335e409a5fcd0182192f40361fd7868a335e40791edc9db51b2f40c4d155babb335e401a6cea3c2a1e2f401cee23b726335e404a4563edef1c2f40d8d825aab7325e40a6d1e4620c1c2f40, 2, '2026-08-08 17:16:04');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `reporter_type` enum('resident','guest') NOT NULL DEFAULT 'resident',
  `tracking_number` varchar(30) DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_phone` varchar(191) DEFAULT NULL,
  `guest_email` varchar(191) DEFAULT NULL,
  `reporter_latitude` decimal(10,8) DEFAULT NULL,
  `reporter_longitude` decimal(11,8) DEFAULT NULL,
  `location_plausibility` enum('plausible','requires_review','high_risk') NOT NULL DEFAULT 'plausible',
  `is_duplicate` tinyint(1) NOT NULL DEFAULT 0,
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

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `resident_id`, `reporter_type`, `tracking_number`, `guest_name`, `guest_phone`, `guest_email`, `reporter_latitude`, `reporter_longitude`, `location_plausibility`, `is_duplicate`, `description`, `latitude`, `longitude`, `location_verified`, `submission_date`, `reviewed_by`, `created_at`, `updated_at`, `category_id`, `quantity_id`, `condition_id`, `status_id`, `purok_id`, `location`, `rejected_reason`, `support_count`) VALUES
(1, 3, 'resident', 'WRS-2026-32459', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Overflowing public concrete waste bin near the chapel. Trash spilling onto the sidewalk and causing unpleasant odor in the morning.', 15.56045000, 120.80490000, 0, '2026-08-14 08:30:00', NULL, '2026-08-14 00:30:00', '2026-08-14 08:30:00', 2, 2, 3, 2, 1, 'Near Purok 1 Community Chapel, Corner Mabini St.', NULL, 12),
(2, 3, 'resident', 'WRS-2026-31419', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'May nagtapon ng mga sirang sako at halo-halong plastic waste sa tabi ng kanal. May mga lumulutang na plastic bottles.', 15.55980000, 120.80410000, 0, '2026-08-16 09:15:00', NULL, '2026-08-16 01:15:00', '2026-08-16 09:15:00', 1, 3, 1, 1, 1, 'Purok 1 Irrigation Canal Path, Rizal Street', NULL, 10),
(3, 3, 'resident', 'WRS-2026-96649', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Discarded paint buckets, chemical thinner cans, and fluorescent tubes left beside the health center fence.', 15.56110000, 120.80690000, 0, '2026-08-15 14:20:00', NULL, '2026-08-15 06:20:00', '2026-08-15 14:20:00', 6, 1, 4, 2, 1, 'Purok 1 Health Center perimeter alleyway', NULL, 10),
(4, 3, 'resident', 'WRS-2026-33101', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Regular household trash bins left uncollected since Tuesday. Bags are beginning to pile up and need immediate collection.', 15.56140000, 120.80710000, 0, '2026-08-12 11:00:00', NULL, '2026-08-12 03:00:00', '2026-08-12 11:00:00', 3, 2, 2, 4, 1, 'Bonifacio St. alley near Purok 1 Day Care Center', NULL, 4),
(5, 3, 'resident', 'WRS-2026-80100', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Semento, graba, at sirang hollow blocks na iniwan matapos ang fencing repair. Nakaharang sa daanan ng mga tricycle.', 15.56010000, 120.80440000, 0, '2026-08-15 16:45:00', NULL, '2026-08-15 08:45:00', '2026-08-15 16:45:00', 4, 3, 6, 3, 1, 'Corner Mabini St. & Purok 1 Barangay Road', NULL, 3),
(6, 3, 'resident', 'WRS-2026-15520', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Piles of dry bamboo cuttings, coconut fronds, and pruned mango branches after weekend neighborhood clearing.', 15.55910000, 120.80350000, 0, '2026-08-10 10:30:00', NULL, '2026-08-10 02:30:00', '2026-08-10 10:30:00', 5, 3, 2, 4, 1, 'Purok 1 West Boundary, Riverside Pathway', NULL, 5),
(7, 3, 'resident', 'WRS-2026-81474', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Tambak ng plastic wrappers, sako ng ipa, at sirang karton sa tabi ng feeder road. Kailangan mahakot ng utility truck.', 15.56300000, 120.82400000, 0, '2026-08-17 07:45:00', NULL, '2026-08-16 23:45:00', '2026-08-17 07:45:00', 1, 3, 1, 1, 2, 'Purok 2 North Access Road, near Rice Mill', NULL, 7),
(8, 3, 'resident', 'WRS-2026-32379', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Overflowing steel garbage drums after barangay sports event. Single-use plastic cups and snack packs scattered.', 15.56200000, 120.82200000, 0, '2026-08-16 13:10:00', NULL, '2026-08-16 05:10:00', '2026-08-16 13:10:00', 2, 2, 3, 2, 2, 'Purok 2 Secondary Alley, near Purok Basketball Half-court', NULL, 1),
(9, 3, 'resident', 'WRS-2026-53660', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Baradong culvert dahil sa mga naipit na sanga at plastic sacks ng domestic trash. Bahagyang tumataas ang tubig.', 15.56050000, 120.81600000, 0, '2026-08-15 15:30:00', NULL, '2026-08-15 07:30:00', '2026-08-15 15:30:00', 7, 3, 5, 3, 2, 'Purok 2 East Sub-feeder Canal Road', NULL, 2),
(10, 3, 'resident', 'WRS-2026-31044', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Leftover concrete masonry fragments and broken hollow blocks after wall reconstruction. Need clearing.', 15.55850000, 120.81800000, 0, '2026-08-11 09:00:00', NULL, '2026-08-11 01:00:00', '2026-08-11 09:00:00', 4, 2, 4, 4, 2, 'Purok 2 South Sector, Boundary Pathway', NULL, 2),
(11, 3, 'resident', 'WRS-2026-86577', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Tree pruning debris and dry weeds from farm boundary trimming. Cleaned and processed for communal compost.', 15.56500000, 120.82500000, 0, '2026-08-09 14:00:00', NULL, '2026-08-09 06:00:00', '2026-08-09 14:00:00', 5, 4, 2, 4, 2, 'Purok 2 Farm-to-Market Road, Kilometro 2', NULL, 6),
(12, 3, 'resident', 'WRS-2026-35263', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Missed household waste collection along northern corner. Scheduled for priority dispatch this afternoon.', 15.56600000, 120.82300000, 0, '2026-08-16 11:20:00', NULL, '2026-08-16 03:20:00', '2026-08-16 11:20:00', 3, 2, 2, 2, 2, 'Purok 2 North Gate Entry Point', NULL, 3),
(13, 3, 'resident', 'WRS-2026-85731', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Commercial waste sacks and fast-food packaging discarded overnight beside the waiting shed bench.', 15.55600000, 120.80950000, 0, '2026-08-17 06:30:00', NULL, '2026-08-16 22:30:00', '2026-08-17 06:30:00', 1, 2, 1, 1, 3, 'Purok 3 Central Avenue, near Purok Waiting Shed', NULL, 5),
(14, 3, 'resident', 'WRS-2026-95842', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Baradong drainage inlet dahil sa mga naipong mineral water bottles at single-use plastic cups. Kailangan ng declogging.', 15.55450000, 120.80750000, 0, '2026-08-16 16:15:00', NULL, '2026-08-16 08:15:00', '2026-08-16 16:15:00', 7, 2, 5, 2, 3, 'Purok 3 Drainage Culvert Junction', NULL, 7),
(15, 3, 'resident', 'WRS-2026-23288', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Concrete debris, plaster residue, and discarded wall tiles left on the roadside. Road team dispatched for hauling.', 15.55350000, 120.80650000, 0, '2026-08-15 10:00:00', NULL, '2026-08-15 02:00:00', '2026-08-15 10:00:00', 4, 3, 6, 3, 3, 'Purok 3 South Road, near Purok Outpost', NULL, 11),
(16, 3, 'resident', 'WRS-2026-69669', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Biodegradable vegetable refuse and rotten fruit crates from weekend market vendors. Fully cleared and sanitized.', 15.55700000, 120.80880000, 0, '2026-08-13 15:40:00', NULL, '2026-08-13 07:40:00', '2026-08-13 15:40:00', 5, 3, 4, 4, 3, 'Purok 3 Market Feeder Road', NULL, 11),
(17, 3, 'resident', 'WRS-2026-58237', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Two household trash bins overflowing due to delayed pickup schedule. Resident requests immediate sweep.', 15.55500000, 120.81100000, 0, '2026-08-17 08:50:00', NULL, '2026-08-17 00:50:00', '2026-08-17 08:50:00', 2, 1, 3, 1, 3, 'Purok 3 East Alleyway, Residential Block 4', NULL, 6),
(18, 3, 'resident', 'WRS-2026-16026', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Old automotive batteries and motor oil containers left near mechanic shop. Hazardous chemicals disposed safely.', 15.55800000, 120.80800000, 0, '2026-08-12 13:25:00', NULL, '2026-08-12 05:25:00', '2026-08-12 13:25:00', 6, 2, 4, 4, 3, 'Purok 3 Main Crossing, Rizal Extension', NULL, 6),
(19, 3, 'resident', 'WRS-2026-23531', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Plastic snack packaging, styrofoam meal boxes, and plastic bottles scattered outside the campus fence.', 15.55800000, 120.82800000, 0, '2026-08-16 15:00:00', NULL, '2026-08-16 07:00:00', '2026-08-16 15:00:00', 1, 2, 4, 2, 4, 'Purok 4 Barangay Road, near High School Extension', NULL, 10),
(20, 3, 'resident', 'WRS-2026-42400', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Truck unloaded construction soil, broken pavement chunks, and asphalt debris blocking half of the bypass road.', 15.55600000, 120.82500000, 0, '2026-08-15 11:30:00', NULL, '2026-08-15 03:30:00', '2026-08-15 11:30:00', 4, 4, 6, 3, 4, 'Purok 4 Agri-Industrial Bypass, Corner Sitio Riverside', NULL, 1),
(21, 3, 'resident', 'WRS-2026-62850', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Silt and domestic waste accumulation restricting water flow to agricultural plots. Declogging operation planned.', 15.55400000, 120.82800000, 0, '2026-08-17 09:20:00', NULL, '2026-08-17 01:20:00', '2026-08-17 09:20:00', 7, 3, 5, 1, 4, 'Purok 4 Irrigation Gate 2 Canal', NULL, 13),
(22, 3, 'resident', 'WRS-2026-42876', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Piles of pruned acacia branches and garden weeds. Hauled by the barangay dump truck for organic processing.', 15.56000000, 120.82700000, 0, '2026-08-14 16:10:00', NULL, '2026-08-14 08:10:00', '2026-08-14 16:10:00', 5, 3, 2, 4, 4, 'Purok 4 Communal Nursery Perimeter', NULL, 1),
(23, 3, 'resident', 'WRS-2026-42885', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Community waste receptacle completely filled. Commuters dropping waste around the perimeter base.', 15.55700000, 120.82200000, 0, '2026-08-16 10:45:00', NULL, '2026-08-16 02:45:00', '2026-08-16 10:45:00', 2, 2, 3, 2, 4, 'Purok 4 Public Tricycle Terminal', NULL, 13),
(24, 3, 'resident', 'WRS-2026-22985', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Household garbage bags uncollected for 4 days. Waste properly collected and disposed by sanitation team.', 15.55300000, 120.82000000, 0, '2026-08-13 14:15:00', NULL, '2026-08-13 06:15:00', '2026-08-13 14:15:00', 3, 2, 2, 4, 4, 'Purok 4 South Access Way, Sitio Ilang-Ilang', NULL, 12),
(25, 3, 'resident', 'WRS-2026-83871', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Multiple sacks of commercial poultry feeds and torn plastic sheeting dumped on the road shoulder.', 15.55300000, 120.80000000, 0, '2026-08-17 08:00:00', NULL, '2026-08-17 00:00:00', '2026-08-17 08:00:00', 1, 3, 1, 1, 5, 'Purok 5 Main Road, near Barangay Boundary Marker', NULL, 2),
(26, 3, 'resident', 'WRS-2026-71513', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Baradong daluyan ng patubig dahil sa naipong plastic containers at sirang lambat. Apektado ang daloy ng tubig.', 15.55150000, 120.79800000, 0, '2026-08-16 14:50:00', NULL, '2026-08-16 06:50:00', '2026-08-16 14:50:00', 7, 2, 5, 2, 5, 'Purok 5 West Feeder Canal, near Rice Field Entry', NULL, 8),
(27, 3, 'resident', 'WRS-2026-18646', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Leftover concrete masonry fragments and broken culvert pieces after ditch repair. Cleared by utility crew.', 15.55400000, 120.80300000, 0, '2026-08-11 11:15:00', NULL, '2026-08-11 03:15:00', '2026-08-11 11:15:00', 4, 3, 6, 4, 5, 'Purok 5 East Perimeter Road, near Barangay Multi-purpose Hall', NULL, 3),
(28, 3, 'resident', 'WRS-2026-83764', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Piles of dry bamboo cuttings, pruned ipil-ipil branches, and dried foliage ready for municipal collection.', 15.55200000, 120.80200000, 0, '2026-08-15 13:00:00', NULL, '2026-08-15 05:00:00', '2026-08-15 13:00:00', 5, 2, 2, 3, 5, 'Purok 5 Central Crossing, Sitio Pag-asa', NULL, 6),
(29, 3, 'resident', 'WRS-2026-42164', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Overflowing communal garbage drum near the residential alleyway. Stray dogs tearing through discarded sacks.', 15.55500000, 120.80500000, 0, '2026-08-17 10:10:00', NULL, '2026-08-17 02:10:00', '2026-08-22 12:16:23', 2, 2, 3, 1, 5, 'Purok 5 North Crossing, near Purok 1 & 5 Boundary', NULL, 6),
(30, 3, 'resident', 'WRS-2026-69008', NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'Missed regular collection for household garbage bags. Swept and hauled by special barangay truck deployment.', 15.55350000, 120.79500000, 0, '2026-08-12 16:30:00', NULL, '2026-08-12 08:30:00', '2026-08-12 16:30:00', 3, 2, 2, 4, 5, 'Purok 5 Far West Access, Sitio Maligaya', NULL, 3),
(31, 3, 'resident', NULL, NULL, NULL, NULL, NULL, NULL, 'plausible', 0, 'update description', 15.55529400, 120.82413600, 1, '2026-08-22 12:21:24', 2, '2026-08-22 04:21:24', '2026-08-22 19:18:46', 8, 1, 2, 4, 4, '', NULL, 0),
(33, NULL, 'guest', 'WRS-2026-52135', '', 'hanseduinfo@gmail.com', 'hanseduinfo@gmail.com', 15.39051900, 120.94023027, 'high_risk', 0, 'reportttttttt', 15.56033820, 120.80114200, 0, '2026-08-22 19:00:50', 2, '2026-08-22 11:00:50', '2026-08-22 19:19:23', 3, 2, 3, 3, 1, '15.56034, 120.80114', NULL, 0),
(34, NULL, 'guest', 'WRS-2026-84736', 'hans', 'hanseduinfo@gmail.com', 'hanseduinfo@gmail.com', 15.39051900, 120.94023027, 'high_risk', 0, 'reportttttttt', 15.55711243, 120.83195447, 0, '2026-08-22 19:32:04', 2, '2026-08-22 11:32:04', '2026-08-22 19:37:55', 6, 2, 4, 2, 4, '15.55711, 120.83195', NULL, 0);

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `header_logo_left` varchar(255) DEFAULT NULL,
  `header_logo_right` varchar(255) DEFAULT NULL,
  `sub_header` varchar(255) DEFAULT NULL,
  `republic_header` varchar(255) DEFAULT 'Republic of the Philippines',
  `office_name` varchar(255) DEFAULT 'Office of the Barangay Solid Waste Management Committee',
  `signatory_approved_name` varchar(255) DEFAULT NULL,
  `signatory_approved_position` varchar(255) DEFAULT 'Punong Barangay'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_generation_settings`
--

INSERT INTO `report_generation_settings` (`setting_id`, `report_header`, `report_footer`, `signatory_name`, `signatory_position`, `disclaimer`, `updated_by`, `updated_at`, `header_logo_left`, `header_logo_right`, `sub_header`, `republic_header`, `office_name`, `signatory_approved_name`, `signatory_approved_position`) VALUES
(1, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', 'Rosa Medina', 'Barangay Secretary', '', 2, '2026-08-18 01:07:46', '/uploads/logos/rep_logo_left_1786932978.jpg', '/uploads/logos/rep_logo_right_1786932978.jpg', 'Province of Nueva Ecija · Municipality of Quezon', 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', '', 'Punong Barangay'),
(2, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-08-18 01:07:46', '/uploads/logos/rep_logo_left_1786932978.jpg', '/uploads/logos/rep_logo_right_1786932978.jpg', NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay'),
(3, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-08-18 01:07:46', '/uploads/logos/rep_logo_left_1786932978.jpg', '/uploads/logos/rep_logo_right_1786932978.jpg', NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay'),
(4, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-08-18 01:07:46', '/uploads/logos/rep_logo_left_1786932978.jpg', '/uploads/logos/rep_logo_right_1786932978.jpg', NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay'),
(5, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-08-18 01:07:46', '/uploads/logos/rep_logo_left_1786932978.jpg', '/uploads/logos/rep_logo_right_1786932978.jpg', NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay');

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

--
-- Dumping data for table `report_photos`
--

INSERT INTO `report_photos` (`photo_id`, `report_id`, `photo_path`, `is_primary`, `uploaded_at`) VALUES
(1, 1, '6a6f29f41892f_Screenshot 2025-06-22 181737.png', 1, '2026-08-14 08:30:00'),
(2, 2, '69e3f0fe0e532_images.jpg', 1, '2026-08-16 09:15:00'),
(3, 3, '6a6f32fddca0e_Screenshot 2025-06-18 225708.png', 1, '2026-08-15 14:20:00'),
(4, 4, '6a6f343691b11_Screenshot 2025-06-22 181737.png', 1, '2026-08-12 11:00:00'),
(5, 5, '6a75f133acbf0_Screenshot 2025-06-11 000452.png', 1, '2026-08-15 16:45:00'),
(6, 6, '6a6e76cf63dd9_Screenshot 2025-06-22 190140.png', 1, '2026-08-10 10:30:00'),
(7, 7, '69e4791c58512_images.jpg', 1, '2026-08-17 07:45:00'),
(8, 8, '69e47e9105490_images.jpg', 1, '2026-08-16 13:10:00'),
(9, 9, '6a6f36567812f_Screenshot 2025-08-18 220556.png', 1, '2026-08-15 15:30:00'),
(10, 10, '69e480a039c44_images.jpg', 1, '2026-08-11 09:00:00'),
(11, 11, '6a768d7d2ed66_Screenshot 2025-06-24 114030.png', 1, '2026-08-09 14:00:00'),
(12, 12, '69e482f21dfa7_images.jpg', 1, '2026-08-16 11:20:00'),
(13, 13, '69e485c125cd7_images.jpg', 1, '2026-08-17 06:30:00'),
(14, 14, '69e492223969c_images.jpg', 1, '2026-08-16 16:15:00'),
(15, 15, '69e4ab6846abd_images (1).jpg', 1, '2026-08-15 10:00:00'),
(16, 16, '6a6e672cc530e_Screenshot 2025-06-22 181737.png', 1, '2026-08-13 15:40:00'),
(17, 17, '6a6e76e0124a4_Screenshot 2025-06-22 190140.png', 1, '2026-08-17 08:50:00'),
(18, 18, '6a6e76f797d1d_Screenshot 2025-06-07 201142.png', 1, '2026-08-12 13:25:00'),
(19, 19, '6a6e770f6a72d_Screenshot 2025-06-18 131648.png', 1, '2026-08-16 15:00:00'),
(20, 20, '6a6f35d3ab354_Screenshot 2025-06-07 201121.png', 1, '2026-08-15 11:30:00'),
(21, 21, '69e18b79be572_peakpx.jpg', 1, '2026-08-17 09:20:00'),
(22, 22, '69e18b83b4a7c_peakpx.jpg', 1, '2026-08-14 16:10:00'),
(23, 23, 'guest_6a7913d81f718.jpg', 1, '2026-08-16 10:45:00'),
(24, 24, 'guest_6a810e2053ec1.png', 1, '2026-08-13 14:15:00'),
(25, 25, '69e3f0fe0e532_images.jpg', 1, '2026-08-17 08:00:00'),
(26, 26, '6a6f29f41892f_Screenshot 2025-06-22 181737.png', 1, '2026-08-16 14:50:00'),
(27, 27, '6a6f32fddca0e_Screenshot 2025-06-18 225708.png', 1, '2026-08-11 11:15:00'),
(28, 28, '6a6f343691b11_Screenshot 2025-06-22 181737.png', 1, '2026-08-15 13:00:00'),
(29, 29, '6a75f133acbf0_Screenshot 2025-06-11 000452.png', 1, '2026-08-17 10:10:00'),
(30, 30, '6a6e76cf63dd9_Screenshot 2025-06-22 190140.png', 1, '2026-08-12 16:30:00'),
(31, 31, '6a8923c43c61f_Screenshot 2026-03-29 233809.png', 1, '2026-08-22 12:21:24'),
(32, 33, 'guest_6a898090ec7fc.png', 1, '2026-08-22 19:00:50'),
(33, 34, 'guest_6a8988b25acdb.png', 1, '2026-08-22 19:32:04');

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
(1, 1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500, 2, '2026-08-08 20:10:12'),
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

--
-- Dumping data for table `report_status_history`
--

INSERT INTO `report_status_history` (`id`, `report_id`, `previous_status`, `new_status`, `remark`, `changed_by`, `changed_at`) VALUES
(1, 1, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-14 08:30:00'),
(2, 2, 'Submitted', 'Pending', 'Incident report submitted by resident.', 3, '2026-08-16 09:15:00'),
(3, 3, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-15 14:20:00'),
(4, 4, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-12 11:00:00'),
(5, 5, 'Submitted', 'In Progress', 'Incident report submitted by resident.', 3, '2026-08-15 16:45:00'),
(6, 6, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-10 10:30:00'),
(7, 7, 'Submitted', 'Pending', 'Incident report submitted by resident.', 3, '2026-08-17 07:45:00'),
(8, 8, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-16 13:10:00'),
(9, 9, 'Submitted', 'In Progress', 'Incident report submitted by resident.', 3, '2026-08-15 15:30:00'),
(10, 10, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-11 09:00:00'),
(11, 11, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-09 14:00:00'),
(12, 12, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-16 11:20:00'),
(13, 13, 'Submitted', 'Pending', 'Incident report submitted by resident.', 3, '2026-08-17 06:30:00'),
(14, 14, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-16 16:15:00'),
(15, 15, 'Submitted', 'In Progress', 'Incident report submitted by resident.', 3, '2026-08-15 10:00:00'),
(16, 16, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-13 15:40:00'),
(17, 17, 'Submitted', 'Pending', 'Incident report submitted by resident.', 3, '2026-08-17 08:50:00'),
(18, 18, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-12 13:25:00'),
(19, 19, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-16 15:00:00'),
(20, 20, 'Submitted', 'In Progress', 'Incident report submitted by resident.', 3, '2026-08-15 11:30:00'),
(21, 21, 'Submitted', 'Pending', 'Incident report submitted by resident.', 3, '2026-08-17 09:20:00'),
(22, 22, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-14 16:10:00'),
(23, 23, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-16 10:45:00'),
(24, 24, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-13 14:15:00'),
(25, 25, 'Submitted', 'Pending', 'Incident report submitted by resident.', 3, '2026-08-17 08:00:00'),
(26, 26, 'Submitted', 'Verified', 'Incident report submitted by resident.', 3, '2026-08-16 14:50:00'),
(27, 27, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-11 11:15:00'),
(28, 28, 'Submitted', 'In Progress', 'Incident report submitted by resident.', 3, '2026-08-15 13:00:00'),
(29, 29, 'Submitted', 'Pending', 'Incident report submitted by resident.', 3, '2026-08-17 10:10:00'),
(30, 30, 'Submitted', 'Resolved', 'Incident report submitted by resident.', 3, '2026-08-12 16:30:00');

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

--
-- Dumping data for table `report_summaries`
--

INSERT INTO `report_summaries` (`id`, `generated_by`, `filename`, `file_path`, `file_type`, `filters`, `total_reports`, `generated_at`) VALUES
(1, 2, 'report_summary_2026-08-01_09-11-52', '', 'csv', '{\"url\":\"admin\\/export\",\"format\":\"csv\"}', 2, '2026-08-01 15:11:52'),
(2, 2, 'report_summary_2026-08-02_02-20-30', '', 'csv', '{\"url\":\"admin\\/export\",\"format\":\"csv\"}', 3, '2026-08-02 08:20:30'),
(3, 2, 'report_summary_2026-08-02_13-57-16', '', 'csv', '{\"url\":\"admin\\/export\",\"format\":\"csv\"}', 3, '2026-08-02 19:57:16'),
(4, 2, 'report_summary_2026-08-02_13-57-31', '', 'csv', '{\"url\":\"admin\\/export\",\"format\":\"csv\"}', 3, '2026-08-02 19:57:31'),
(5, 2, 'analytics_2026-08-07_04-33-36', '', 'pdf', '{\"date_from\":\"2026-07-30\",\"date_to\":\"2026-08-07\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 4, '2026-08-07 10:33:36'),
(6, 2, 'analytics_report_2026-08-07.csv', '', 'csv', '{\"date_from\":\"2026-07-30\",\"date_to\":\"2026-08-07\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 4, '2026-08-07 10:33:52'),
(7, 2, 'report_summary_2026-08-10_07-01-08', '', 'pdf', '{\"date_from\":\"2026-07-11\",\"date_to\":\"2026-08-10\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-10 13:01:08'),
(8, 2, 'analytics_2026-08-10_07-16-25', '', 'pdf', '{\"date_from\":\"2026-07-11\",\"date_to\":\"2026-08-10\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-10 13:16:25'),
(9, 2, 'report_summary_2026-08-10_07-22-39', '', 'csv', '{\"url\":\"admin\\/export\",\"format\":\"csv\"}', 14, '2026-08-10 13:22:39'),
(10, 2, 'report_summary_2026-08-10_07-24-22', '', 'csv', '{\"url\":\"admin\\/export\",\"format\":\"csv\"}', 14, '2026-08-10 13:24:22'),
(11, 2, 'analytics_2026-08-10_07-39-17', '', 'pdf', '{\"date_from\":\"2026-07-11\",\"date_to\":\"2026-08-10\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-10 13:39:17'),
(12, 2, 'analytics_2026-08-14_19-42-12', '', 'pdf', '{\"date_from\":\"2026-07-15\",\"date_to\":\"2026-08-14\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-15 01:42:12'),
(13, 2, 'analytics_2026-08-14_19-47-23', '', 'pdf', '{\"date_from\":\"2026-07-15\",\"date_to\":\"2026-08-14\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-15 01:47:23'),
(14, 2, 'analytics_2026-08-15_01-40-28', '', 'pdf', '{\"date_from\":\"2026-07-16\",\"date_to\":\"2026-08-15\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-15 07:40:28'),
(15, 2, 'analytics_2026-08-15_03-15-23', '', 'pdf', '{\"date_from\":\"2026-07-16\",\"date_to\":\"2026-08-15\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-15 09:15:23'),
(16, 2, 'analytics_2026-08-16_12-38-03', '', 'pdf', '{\"date_from\":\"2026-07-17\",\"date_to\":\"2026-08-16\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-16 18:38:03'),
(17, 2, 'analytics_2026-08-16_19-23-36', '', 'pdf', '{\"date_from\":\"2026-07-17\",\"date_to\":\"2026-08-16\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-17 01:23:36'),
(18, 2, 'analytics_2026-08-18_19-25-08', '', 'pdf', '{\"date_from\":\"2026-07-19\",\"date_to\":\"2026-08-18\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-19 01:25:08'),
(19, 2, 'analytics_2026-08-20_13-25-27', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 19:25:27'),
(20, 2, 'analytics_report_2026-08-20.csv', '', 'csv', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 19:26:04'),
(21, 2, 'analytics_2026-08-20_13-26-10', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 19:26:10'),
(22, 2, 'analytics_2026-08-20_13-26-21', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 19:26:21'),
(23, 2, 'report_summary_2026-08-20_13-32-30', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 19:32:30'),
(24, 2, 'report_summary_2026-08-20.csv', '', 'csv', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 19:32:37'),
(25, 2, 'analytics_2026-08-20_16-49-34', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 22:49:34'),
(26, 2, 'analytics_2026-08-20_16-51-21', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 22:51:21'),
(27, 2, 'analytics_2026-08-20_16-55-10', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 22:55:10'),
(28, 2, 'analytics_2026-08-20_16-55-29', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 22:55:29'),
(29, 2, 'analytics_2026-08-20_16-59-18', '', 'pdf', '{\"date_from\":\"2026-07-21\",\"date_to\":\"2026-08-20\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-20 22:59:18'),
(30, 2, 'analytics_2026-08-22_04-39-41', '', 'pdf', '{\"date_from\":\"2026-07-23\",\"date_to\":\"2026-08-22\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 30, '2026-08-22 10:39:41');

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `is_custom` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`, `created_at`, `updated_at`, `permissions`, `is_custom`) VALUES
(1, 'Administrator', 'Full system access and configuration', '2026-07-25 13:56:31', '2026-07-25 13:56:31', NULL, 0),
(2, 'Supervisor', 'Monitoring and analytics access', '2026-07-25 13:56:31', '2026-07-25 13:56:31', NULL, 0),
(3, 'Resident', 'Report submission and tracking access', '2026-07-25 13:56:31', '2026-07-25 13:56:31', NULL, 0);

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
-- Table structure for table `system_maintenance`
--

CREATE TABLE `system_maintenance` (
  `id` int(11) NOT NULL DEFAULT 1,
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0,
  `maintenance_type` enum('scheduled','emergency') NOT NULL DEFAULT 'scheduled',
  `maintenance_message` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `allow_admin_access` tinyint(1) NOT NULL DEFAULT 1,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_maintenance`
--

INSERT INTO `system_maintenance` (`id`, `maintenance_mode`, `maintenance_type`, `maintenance_message`, `reason`, `start_at`, `end_at`, `allow_admin_access`, `updated_by`, `updated_at`) VALUES
(1, 0, 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'maintenance', NULL, NULL, 1, 2, '2026-08-20 17:42:31');

-- --------------------------------------------------------

--
-- Table structure for table `two_factor_tokens`
--

CREATE TABLE `two_factor_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(6) NOT NULL,
  `purpose` varchar(50) NOT NULL DEFAULT 'login_2fa',
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `two_factor_tokens`
--

INSERT INTO `two_factor_tokens` (`id`, `user_id`, `email`, `token`, `purpose`, `expires_at`, `is_used`, `attempts`, `created_at`) VALUES
(312, 18, 'floreshanslimuelle.neust@gmail.com', '387496', 'login_2fa', '2026-08-02 19:42:33', 1, 0, '2026-08-02 11:32:33'),
(334, 16, 'testingotp@gmail.com', '064512', 'login_2fa', '2026-08-05 12:48:25', 0, 0, '2026-08-05 04:38:25'),
(388, 20, '09951281511', '220447', 'login_2fa', '2026-08-10 12:22:56', 1, 0, '2026-08-10 04:12:56'),
(436, 23, 'limuelle.neust@gmail.com', '817689', 'login_2fa', '2026-08-17 17:21:03', 0, 0, '2026-08-17 09:11:03'),
(472, 3, 'floressktt11@gmail.com', '959507', 'login_2fa', '2026-08-22 14:13:32', 1, 0, '2026-08-22 06:03:32'),
(476, 2, 'floreshans.neust@gmail.com', '037820', 'login_2fa', '2026-08-23 11:09:31', 1, 0, '2026-08-23 02:59:31');

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
  `profile_pic` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `otp_verified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `middle_name`, `suffix`, `username`, `account_type`, `address`, `phone_number`, `email`, `password`, `id_front`, `id_back`, `role_id`, `position_id`, `purok_id`, `status`, `last_login`, `created_at`, `updated_at`, `phone_normalized`, `profile_pic`, `email_verified`, `otp_verified_at`, `deleted_at`) VALUES
(1, 'Barangay Captain', NULL, NULL, NULL, 'resident', 'Barangay Hall', '09123456789', 'captain@dulongbayan.ph', '$2y$10$Z/HeHO5k9Uu8kt3YsKCM9e0Q/5DnYYKhMTcDeQ3LDVX6KTj3iv2Gy', NULL, NULL, 1, 1, 1, 'active', NULL, '2026-04-01 00:28:49', '2026-08-08 18:21:46', NULL, NULL, 0, NULL, NULL),
(2, 'Secretary Rose', NULL, NULL, NULL, 'resident', 'Barangay Hall', '09123456788', 'floreshans.neust@gmail.com', '$2y$10$NFKPGxb7Hord13zMb9YLzeQOC8Te8geDMwW21XM48pMsuYDx49qSy', NULL, NULL, 1, 2, 1, 'active', NULL, '2026-04-01 00:28:49', '2026-08-21 18:58:19', NULL, '/uploads/profiles/profile_2_1787309899.png', 0, NULL, NULL),
(3, 'Hans Flores', NULL, NULL, NULL, 'resident', 'brgy.testing.testing', '09951281511', 'floressktt11@gmail.com', '$2y$10$y03L/tBgrsBFqgLuFMFRYOos6Y.svcXlfru15rNSc8dcg2cYLVew2', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-04-01 01:05:23', '2026-08-20 19:06:23', NULL, '/uploads/profiles/profile_3_1787223983.jpg', 0, NULL, NULL),
(15, 'asdasdadad', NULL, NULL, NULL, 'resident', '232323232323', '09951281511', 'floererererer@gmail.com', '$2y$10$fL/0SLQG2zLnUEniuGsTU.ulSh4yLbmpwPMkWFLIbqc2OU1xF1Niq', '/uploads/ids/front_6a6252ecf1d05.jpg', '/uploads/ids/back_6a6252ed00579.jpg', 3, 6, 1, 'active', NULL, '2026-07-24 01:44:13', '2026-08-09 05:54:43', NULL, NULL, 0, NULL, NULL),
(16, 'test email otp', NULL, NULL, NULL, 'resident', 'awwsdasdad', '09951281511', 'testingotp@gmail.com', '$2y$10$NlxWm4KHBTA2PPazo0MN7ehPrv3RmAtUukEAO8QavMGJAmG.0znou', '/uploads/ids/front_6a6258475b015.jpg', '/uploads/ids/back_6a6258475be3f.jpg', 3, 6, 1, 'active', NULL, '2026-07-24 02:07:03', '2026-08-08 01:10:08', NULL, NULL, 0, NULL, NULL),
(18, 'Hans Limuelle Flores', NULL, NULL, 'hansflores', 'resident', 'Barangay Dulong Bayan', '09171234567', 'floreshanslimuelle.neust@gmail.com', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', NULL, NULL, 2, 3, 1, 'active', NULL, '2026-07-26 13:35:01', '2026-07-26 13:47:54', NULL, NULL, 0, NULL, NULL),
(20, 'hans testinggphonesms', NULL, NULL, 'fhanstestingphonesms', 'resident', '', '09951281511', '', '$2y$10$UHXCgTQabJpLgMcE6cfbVOlXnSpLt21c55mkuX55ooqZjjf8Ard/e', NULL, NULL, 3, 6, 2, 'active', NULL, '2026-08-09 22:40:36', '2026-08-14 12:51:31', NULL, NULL, 0, NULL, NULL),
(23, 'limuel', NULL, NULL, 'limmms', 'resident', '', '', 'limuelle.neust@gmail.com', '$2y$10$8sxdbEPc7kja6m2qINCf4.PLTF1CPWtqtGGpWrY/NbSWJVKbDJ/R6', NULL, NULL, 3, 6, 3, 'active', NULL, '2026-08-17 16:59:53', '2026-08-17 17:00:14', NULL, NULL, 0, NULL, NULL);

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
  ADD KEY `created_at` (`created_at`),
  ADD KEY `fk_announcements_visibility` (`visibility_id`);

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
-- Indexes for table `audit_logs_archive`
--
ALTER TABLE `audit_logs_archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `archived_at` (`archived_at`);

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
-- Indexes for table `collection_notes`
--
ALTER TABLE `collection_notes`
  ADD PRIMARY KEY (`note_id`);

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
-- Indexes for table `guest_otp_tokens`
--
ALTER TABLE `guest_otp_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_phone` (`phone`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `guest_sms_rate_limits`
--
ALTER TABLE `guest_sms_rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_phone_window` (`phone`,`window_start`),
  ADD KEY `idx_ip_window` (`ip`,`window_start`);

--
-- Indexes for table `heatmap_settings`
--
ALTER TABLE `heatmap_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `maintenance_history`
--
ALTER TABLE `maintenance_history`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `penalty_rules`
--
ALTER TABLE `penalty_rules`
  ADD PRIMARY KEY (`rule_id`);

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
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
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
-- Indexes for table `system_maintenance`
--
ALTER TABLE `system_maintenance`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `announcement_visibilities`
--
ALTER TABLE `announcement_visibilities`
  MODIFY `visibility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barangay_boundaries`
--
ALTER TABLE `barangay_boundaries`
  MODIFY `boundary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `collection_notes`
--
ALTER TABLE `collection_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `collection_schedule_puroks`
--
ALTER TABLE `collection_schedule_puroks`
  MODIFY `schedule_purok_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `email_otp_rate_limits`
--
ALTER TABLE `email_otp_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `estimated_quantities`
--
ALTER TABLE `estimated_quantities`
  MODIFY `quantity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `guest_otp_tokens`
--
ALTER TABLE `guest_otp_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `guest_sms_rate_limits`
--
ALTER TABLE `guest_sms_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `heatmap_settings`
--
ALTER TABLE `heatmap_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `maintenance_history`
--
ALTER TABLE `maintenance_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `map_landmarks`
--
ALTER TABLE `map_landmarks`
  MODIFY `landmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `notification_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `penalty_rules`
--
ALTER TABLE `penalty_rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `puroks`
--
ALTER TABLE `puroks`
  MODIFY `purok_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `purok_boundaries`
--
ALTER TABLE `purok_boundaries`
  MODIFY `boundary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `report_flags`
--
ALTER TABLE `report_flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `report_generation_settings`
--
ALTER TABLE `report_generation_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_photos`
--
ALTER TABLE `report_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `report_settings`
--
ALTER TABLE `report_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_statuses`
--
ALTER TABLE `report_statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `report_status_history`
--
ALTER TABLE `report_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `report_summaries`
--
ALTER TABLE `report_summaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `report_supports`
--
ALTER TABLE `report_supports`
  MODIFY `support_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=477;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `waste_categories`
--
ALTER TABLE `waste_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `waste_conditions`
--
ALTER TABLE `waste_conditions`
  MODIFY `condition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_announcements_visibility` FOREIGN KEY (`visibility_id`) REFERENCES `announcement_visibilities` (`visibility_id`);

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
