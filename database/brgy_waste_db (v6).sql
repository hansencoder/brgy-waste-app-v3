-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2026 at 03:00 AM
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
(951, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 12:46:08', NULL, NULL, NULL, NULL),
(952, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 12:46:25', NULL, NULL, NULL, NULL),
(953, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-25 13:35:08', NULL, NULL, NULL, NULL),
(954, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 14:59:47', NULL, NULL, NULL, NULL),
(955, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 15:18:35', NULL, NULL, NULL, NULL),
(956, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 15:18:48', NULL, NULL, NULL, NULL),
(957, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-25 16:08:59', NULL, NULL, NULL, NULL),
(958, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 16:23:17', NULL, NULL, NULL, NULL),
(959, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 16:23:28', NULL, NULL, NULL, NULL),
(960, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-25 22:15:16', NULL, NULL, NULL, NULL),
(961, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 22:15:24', NULL, NULL, NULL, NULL),
(962, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 22:15:31', NULL, NULL, NULL, NULL),
(963, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-25 22:55:52', NULL, NULL, NULL, NULL),
(964, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 22:58:32', NULL, NULL, NULL, NULL),
(965, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 22:58:44', NULL, NULL, NULL, NULL),
(966, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:29:14', NULL, NULL, NULL, NULL),
(967, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:55:48', NULL, NULL, NULL, NULL),
(968, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:56:04', NULL, NULL, NULL, NULL),
(969, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:56:04', NULL, NULL, NULL, NULL),
(970, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:56:06', NULL, NULL, NULL, NULL),
(971, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:57:42', NULL, NULL, NULL, NULL),
(972, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:57:54', NULL, NULL, NULL, NULL),
(973, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:58:04', NULL, NULL, NULL, NULL),
(974, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-25 23:58:27', NULL, NULL, NULL, NULL),
(975, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-26 01:00:32', NULL, NULL, NULL, NULL),
(976, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 01:10:10', NULL, NULL, NULL, NULL),
(977, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 01:10:21', NULL, NULL, NULL, NULL),
(978, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 04:21:35', NULL, NULL, NULL, NULL),
(979, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 04:21:46', NULL, NULL, NULL, NULL),
(980, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-26 06:19:32', NULL, NULL, NULL, NULL),
(981, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 06:19:40', NULL, NULL, NULL, NULL),
(982, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 06:19:47', NULL, NULL, NULL, NULL),
(983, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-26 08:10:29', NULL, NULL, NULL, NULL),
(984, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 08:10:40', NULL, NULL, NULL, NULL),
(985, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 08:10:46', NULL, NULL, NULL, NULL),
(986, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 08:25:04', NULL, NULL, NULL, NULL),
(987, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 08:25:24', NULL, NULL, NULL, NULL),
(988, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 08:25:30', NULL, NULL, NULL, NULL),
(989, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 09:44:15', NULL, NULL, NULL, NULL),
(990, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 09:44:23', NULL, NULL, NULL, NULL),
(991, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 09:44:32', NULL, NULL, NULL, NULL),
(992, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 09:45:13', NULL, NULL, NULL, NULL),
(993, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 09:45:33', NULL, NULL, NULL, NULL),
(994, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 09:45:40', NULL, NULL, NULL, NULL),
(995, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-26 12:18:07', NULL, NULL, NULL, NULL),
(996, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:18:15', NULL, NULL, NULL, NULL),
(997, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:18:22', NULL, NULL, NULL, NULL),
(998, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:21:55', NULL, NULL, NULL, NULL),
(999, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:10', NULL, NULL, NULL, NULL),
(1000, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:20', NULL, NULL, NULL, NULL),
(1001, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:20', NULL, NULL, NULL, NULL),
(1002, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:24', NULL, NULL, NULL, NULL),
(1003, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:26', NULL, NULL, NULL, NULL),
(1004, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:30', NULL, NULL, NULL, NULL),
(1005, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:33', NULL, NULL, NULL, NULL),
(1006, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:35', NULL, NULL, NULL, NULL),
(1007, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:36', NULL, NULL, NULL, NULL),
(1008, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 12:22:42', NULL, NULL, NULL, NULL),
(1009, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-07-26 13:28:35', NULL, NULL, NULL, NULL),
(1010, NULL, 'Login failed', 'User', 'Invalid credentials for hansflores', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-26 13:36:02', NULL, NULL, NULL, NULL),
(1011, NULL, 'Login failed', 'User', 'Invalid credentials for floreshanslimuelle.neust@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-26 13:36:17', NULL, NULL, NULL, NULL),
(1012, NULL, 'Login failed', 'User', 'Invalid credentials for floreshanslimuelle.neust@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-26 13:36:30', NULL, NULL, NULL, NULL),
(1013, NULL, 'Login failed', 'User', 'Invalid credentials for floreshanslimuelle.neust@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-26 13:38:22', NULL, NULL, NULL, NULL),
(1014, NULL, 'Account locked', 'User', 'Exceeded login attempts for floreshanslimuelle.neust@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-26 13:38:46', NULL, NULL, NULL, NULL),
(1015, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:39:25', NULL, NULL, NULL, NULL),
(1016, NULL, 'Account locked', 'User', 'Exceeded login attempts for floreshanslimuelle.neust@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-26 13:39:50', NULL, NULL, NULL, NULL),
(1017, NULL, 'Account locked', 'User', 'Exceeded login attempts for floreshanslimuelle.neust@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-26 13:45:16', NULL, NULL, NULL, NULL),
(1018, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:45:31', NULL, NULL, NULL, NULL),
(1019, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:46:03', NULL, NULL, NULL, NULL),
(1020, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:46:03', NULL, NULL, NULL, NULL),
(1021, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:46:11', NULL, NULL, NULL, NULL),
(1022, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:46:15', NULL, NULL, NULL, NULL),
(1023, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:46:19', NULL, NULL, NULL, NULL),
(1024, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:13', NULL, NULL, NULL, NULL),
(1025, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:29', NULL, NULL, NULL, NULL),
(1026, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:29', NULL, NULL, NULL, NULL),
(1027, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:32', NULL, NULL, NULL, NULL),
(1028, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:33', NULL, NULL, NULL, NULL),
(1029, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:36', NULL, NULL, NULL, NULL),
(1030, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:37', NULL, NULL, NULL, NULL),
(1031, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:40', NULL, NULL, NULL, NULL),
(1032, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:41', NULL, NULL, NULL, NULL),
(1033, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:42', NULL, NULL, NULL, NULL),
(1034, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:43', NULL, NULL, NULL, NULL),
(1035, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:47', NULL, NULL, NULL, NULL),
(1036, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:49:57', NULL, NULL, NULL, NULL),
(1037, 18, 'View Report', 'Report ID 33', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:50:12', NULL, NULL, NULL, NULL),
(1038, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:56:01', NULL, NULL, NULL, NULL),
(1039, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:56:18', NULL, NULL, NULL, NULL),
(1040, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:56:34', NULL, NULL, NULL, NULL),
(1041, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 13:59:49', NULL, NULL, NULL, NULL),
(1042, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:00:06', NULL, NULL, NULL, NULL),
(1043, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:00:07', NULL, NULL, NULL, NULL),
(1044, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-07-26 14:00:16', NULL, NULL, NULL, NULL),
(1045, 18, 'View Report', 'Report ID 31', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-07-26 14:00:28', NULL, NULL, NULL, NULL),
(1046, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:07:01', NULL, NULL, NULL, NULL),
(1047, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:07:04', NULL, NULL, NULL, NULL),
(1048, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:12:37', NULL, NULL, NULL, NULL),
(1049, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:18:48', NULL, NULL, NULL, NULL),
(1050, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:18:51', NULL, NULL, NULL, NULL),
(1051, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:18:52', NULL, NULL, NULL, NULL),
(1052, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:18:54', NULL, NULL, NULL, NULL),
(1053, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:19:18', NULL, NULL, NULL, NULL),
(1054, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:22:08', NULL, NULL, NULL, NULL),
(1055, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:22:12', NULL, NULL, NULL, NULL),
(1056, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:22:13', NULL, NULL, NULL, NULL),
(1057, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:22:18', NULL, NULL, NULL, NULL),
(1058, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:22:22', NULL, NULL, NULL, NULL),
(1059, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:30:35', NULL, NULL, NULL, NULL),
(1060, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:38:01', NULL, NULL, NULL, NULL),
(1061, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:38:20', NULL, NULL, NULL, NULL),
(1062, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:45:03', NULL, NULL, NULL, NULL),
(1063, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:45:23', NULL, NULL, NULL, NULL),
(1064, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:45:25', NULL, NULL, NULL, NULL),
(1065, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:45:32', NULL, NULL, NULL, NULL),
(1066, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:50:24', NULL, NULL, NULL, NULL),
(1067, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:50:34', NULL, NULL, NULL, NULL),
(1068, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:50:37', NULL, NULL, NULL, NULL),
(1069, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:50:39', NULL, NULL, NULL, NULL),
(1070, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-07-26 14:51:11', NULL, NULL, NULL, NULL),
(1071, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:54:08', NULL, NULL, NULL, NULL),
(1072, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:54:18', NULL, NULL, NULL, NULL),
(1073, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 14:58:56', NULL, NULL, NULL, NULL),
(1074, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:04:34', NULL, NULL, NULL, NULL),
(1075, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:08:33', NULL, NULL, NULL, NULL),
(1076, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:14:24', NULL, NULL, NULL, NULL),
(1077, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:20:58', NULL, NULL, NULL, NULL),
(1078, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:21:39', NULL, NULL, NULL, NULL),
(1079, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:22:56', NULL, NULL, NULL, NULL),
(1080, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:23:00', NULL, NULL, NULL, NULL),
(1081, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:23:52', NULL, NULL, NULL, NULL),
(1082, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:23:59', NULL, NULL, NULL, NULL),
(1083, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:25:43', NULL, NULL, NULL, NULL),
(1084, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:38:29', NULL, NULL, NULL, NULL),
(1085, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:38:41', NULL, NULL, NULL, NULL),
(1086, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:47:07', NULL, NULL, NULL, NULL),
(1087, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:48:03', NULL, NULL, NULL, NULL),
(1088, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:48:36', NULL, NULL, NULL, NULL),
(1089, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:49:11', NULL, NULL, NULL, NULL),
(1090, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:50:05', NULL, NULL, NULL, NULL),
(1091, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:50:13', NULL, NULL, NULL, NULL),
(1092, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:50:21', NULL, NULL, NULL, NULL),
(1093, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:52:10', NULL, NULL, NULL, NULL),
(1094, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:57:17', NULL, NULL, NULL, NULL),
(1095, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:57:35', NULL, NULL, NULL, NULL),
(1096, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:57:49', NULL, NULL, NULL, NULL),
(1097, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:58:06', NULL, NULL, NULL, NULL),
(1098, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 15:58:16', NULL, NULL, NULL, NULL),
(1099, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:35:20', NULL, NULL, NULL, NULL),
(1100, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:35:59', NULL, NULL, NULL, NULL),
(1101, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:35:59', NULL, NULL, NULL, NULL),
(1102, 18, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:37:41', NULL, NULL, NULL, NULL),
(1103, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:37:52', NULL, NULL, NULL, NULL),
(1104, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:38:12', NULL, NULL, NULL, NULL),
(1105, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:38:12', NULL, NULL, NULL, NULL),
(1106, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:38:14', NULL, NULL, NULL, NULL),
(1107, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:38:21', NULL, NULL, NULL, NULL),
(1108, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 20:38:25', NULL, NULL, NULL, NULL),
(1109, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:05:29', NULL, NULL, NULL, NULL),
(1110, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:05:37', NULL, NULL, NULL, NULL),
(1111, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:09:09', NULL, NULL, NULL, NULL),
(1112, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:12:01', NULL, NULL, NULL, NULL),
(1113, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:25:29', NULL, NULL, NULL, NULL),
(1114, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:25:47', NULL, NULL, NULL, NULL),
(1115, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:26:02', NULL, NULL, NULL, NULL),
(1116, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:26:22', NULL, NULL, NULL, NULL),
(1117, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:26:22', NULL, NULL, NULL, NULL),
(1118, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:26:54', NULL, NULL, NULL, NULL),
(1119, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:27:21', NULL, NULL, NULL, NULL),
(1120, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:27:50', NULL, NULL, NULL, NULL),
(1121, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:52:25', NULL, NULL, NULL, NULL),
(1122, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:52:39', NULL, NULL, NULL, NULL),
(1123, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 21:53:24', NULL, NULL, NULL, NULL),
(1124, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:16:18', NULL, NULL, NULL, NULL),
(1125, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:16:27', NULL, NULL, NULL, NULL),
(1126, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:24:49', NULL, NULL, NULL, NULL),
(1127, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:24:53', NULL, NULL, NULL, NULL),
(1128, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:24:56', NULL, NULL, NULL, NULL),
(1129, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:24:58', NULL, NULL, NULL, NULL),
(1130, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:30:38', NULL, NULL, NULL, NULL),
(1131, 2, 'Report Generated', 'Report Summary', 'Format: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:36:58', NULL, NULL, NULL, NULL),
(1132, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:37:04', NULL, NULL, NULL, NULL),
(1133, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:37:05', NULL, NULL, NULL, NULL),
(1134, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:37:08', NULL, NULL, NULL, NULL),
(1135, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:37:14', NULL, NULL, NULL, NULL),
(1136, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:37:33', NULL, NULL, NULL, NULL),
(1137, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 22:59:48', NULL, NULL, NULL, NULL),
(1138, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:01:15', NULL, NULL, NULL, NULL),
(1139, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:19:50', NULL, NULL, NULL, NULL),
(1140, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:21:33', NULL, NULL, NULL, NULL),
(1141, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:21:36', NULL, NULL, NULL, NULL),
(1142, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:21:43', NULL, NULL, NULL, NULL),
(1143, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:21:44', NULL, NULL, NULL, NULL),
(1144, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:21:46', NULL, NULL, NULL, NULL),
(1145, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:21:48', NULL, NULL, NULL, NULL),
(1146, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:21:51', NULL, NULL, NULL, NULL),
(1147, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:22:39', NULL, NULL, NULL, NULL),
(1148, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:22:40', NULL, NULL, NULL, NULL),
(1149, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:36:46', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(1150, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:37:10', NULL, NULL, NULL, NULL),
(1151, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:37:41', NULL, NULL, NULL, NULL),
(1152, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:38:42', NULL, NULL, NULL, NULL),
(1153, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:38:58', NULL, NULL, NULL, NULL),
(1154, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:44:15', NULL, NULL, NULL, NULL),
(1155, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:44:18', NULL, NULL, NULL, NULL),
(1156, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:44:19', NULL, NULL, NULL, NULL),
(1157, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:56:41', NULL, NULL, NULL, NULL),
(1158, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:56:51', NULL, NULL, NULL, NULL),
(1159, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:57:31', NULL, NULL, NULL, NULL),
(1160, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:58:08', NULL, NULL, NULL, NULL),
(1161, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-26 23:58:23', NULL, NULL, NULL, NULL),
(1162, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:07:57', NULL, NULL, NULL, NULL),
(1163, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:07:59', NULL, NULL, NULL, NULL),
(1164, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:14', NULL, NULL, NULL, NULL),
(1165, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:18', NULL, NULL, NULL, NULL),
(1166, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:20', NULL, NULL, NULL, NULL),
(1167, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:24', NULL, NULL, NULL, NULL),
(1168, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:26', NULL, NULL, NULL, NULL),
(1169, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:29', NULL, NULL, NULL, NULL),
(1170, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:31', NULL, NULL, NULL, NULL),
(1171, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:32', NULL, NULL, NULL, NULL),
(1172, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:40', NULL, NULL, NULL, NULL),
(1173, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:43', NULL, NULL, NULL, NULL),
(1174, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:45', NULL, NULL, NULL, NULL),
(1175, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:08:48', NULL, NULL, NULL, NULL),
(1176, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:11:16', NULL, NULL, NULL, NULL),
(1177, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:11:33', NULL, NULL, NULL, NULL),
(1178, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:11:35', NULL, NULL, NULL, NULL),
(1179, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:11:38', NULL, NULL, NULL, NULL),
(1180, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:14:30', NULL, NULL, NULL, NULL),
(1181, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:14:35', NULL, NULL, NULL, NULL),
(1182, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:14:43', NULL, NULL, NULL, NULL),
(1183, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:15:49', NULL, NULL, NULL, NULL),
(1184, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:16:41', NULL, NULL, NULL, NULL),
(1185, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:16:46', NULL, NULL, NULL, NULL),
(1186, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:16:47', NULL, NULL, NULL, NULL),
(1187, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:16:51', NULL, NULL, NULL, NULL),
(1188, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:16:52', NULL, NULL, NULL, NULL),
(1189, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:16:58', NULL, NULL, NULL, NULL),
(1190, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:17:00', NULL, NULL, NULL, NULL),
(1191, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:23:48', NULL, NULL, NULL, NULL),
(1192, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:23:50', NULL, NULL, NULL, NULL),
(1193, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:02', NULL, NULL, NULL, NULL),
(1194, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:25', NULL, NULL, NULL, NULL),
(1195, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:25', NULL, NULL, NULL, NULL),
(1196, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:46', NULL, NULL, NULL, NULL),
(1197, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:49', NULL, NULL, NULL, NULL),
(1198, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:50', NULL, NULL, NULL, NULL),
(1199, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:51', NULL, NULL, NULL, NULL),
(1200, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:53', NULL, NULL, NULL, NULL),
(1201, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:24:54', NULL, NULL, NULL, NULL),
(1202, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:28:29', NULL, NULL, NULL, NULL),
(1203, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:28:30', NULL, NULL, NULL, NULL),
(1204, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:29:15', NULL, NULL, NULL, NULL),
(1205, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:29:18', NULL, NULL, NULL, NULL),
(1206, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:29:19', NULL, NULL, NULL, NULL),
(1207, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:29:48', NULL, NULL, NULL, NULL),
(1208, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:29:56', NULL, NULL, NULL, NULL),
(1209, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:29:59', NULL, NULL, NULL, NULL),
(1210, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:30:00', NULL, NULL, NULL, NULL),
(1211, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:30:02', NULL, NULL, NULL, NULL),
(1212, 18, 'View Report', 'Report ID 35', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:30:10', NULL, NULL, NULL, NULL),
(1213, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:30:32', NULL, NULL, NULL, NULL),
(1214, 18, 'View Report', 'Report ID 34', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:30:35', NULL, NULL, NULL, NULL),
(1215, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:30:44', NULL, NULL, NULL, NULL),
(1216, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:30:58', NULL, NULL, NULL, NULL),
(1217, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:31:01', NULL, NULL, NULL, NULL),
(1218, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:31:02', NULL, NULL, NULL, NULL),
(1219, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:52:20', NULL, NULL, NULL, NULL),
(1220, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:52:22', NULL, NULL, NULL, NULL),
(1221, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:57:16', NULL, NULL, NULL, NULL),
(1222, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:57:52', NULL, NULL, NULL, NULL),
(1223, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:58:00', NULL, NULL, NULL, NULL),
(1224, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:58:02', NULL, NULL, NULL, NULL),
(1225, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:58:03', NULL, NULL, NULL, NULL),
(1226, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:59:05', NULL, NULL, NULL, NULL),
(1227, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 00:59:10', NULL, NULL, NULL, NULL),
(1228, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:02:39', NULL, NULL, NULL, NULL),
(1229, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:02:46', NULL, NULL, NULL, NULL),
(1230, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:02:48', NULL, NULL, NULL, NULL),
(1231, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:02:53', NULL, NULL, NULL, NULL),
(1232, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:02:55', NULL, NULL, NULL, NULL),
(1233, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:02:57', NULL, NULL, NULL, NULL),
(1234, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:05:42', NULL, NULL, NULL, NULL),
(1235, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:05:45', NULL, NULL, NULL, NULL),
(1236, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:05:46', NULL, NULL, NULL, NULL),
(1237, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:10:28', NULL, NULL, NULL, NULL),
(1238, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:10:31', NULL, NULL, NULL, NULL),
(1239, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:18:31', NULL, NULL, NULL, NULL),
(1240, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:18:32', NULL, NULL, NULL, NULL),
(1241, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:25:54', NULL, NULL, NULL, NULL),
(1242, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:25:55', NULL, NULL, NULL, NULL),
(1243, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:27:05', NULL, NULL, NULL, NULL),
(1244, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:27:07', NULL, NULL, NULL, NULL),
(1245, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:31:37', NULL, NULL, NULL, NULL),
(1246, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:31:39', NULL, NULL, NULL, NULL),
(1247, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:31:41', NULL, NULL, NULL, NULL),
(1248, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:31:41', NULL, NULL, NULL, NULL),
(1249, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:31:42', NULL, NULL, NULL, NULL),
(1250, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:32:46', NULL, NULL, NULL, NULL),
(1251, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:32:48', NULL, NULL, NULL, NULL),
(1252, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:32:49', NULL, NULL, NULL, NULL),
(1253, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:07', NULL, NULL, NULL, NULL),
(1254, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:09', NULL, NULL, NULL, NULL),
(1255, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:10', NULL, NULL, NULL, NULL),
(1256, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:13', NULL, NULL, NULL, NULL),
(1257, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:14', NULL, NULL, NULL, NULL),
(1258, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:15', NULL, NULL, NULL, NULL),
(1259, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:17', NULL, NULL, NULL, NULL),
(1260, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:33:21', NULL, NULL, NULL, NULL),
(1261, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:36:14', NULL, NULL, NULL, NULL),
(1262, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:36:15', NULL, NULL, NULL, NULL),
(1263, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:36:17', NULL, NULL, NULL, NULL),
(1264, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:36:18', NULL, NULL, NULL, NULL),
(1265, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:36:59', NULL, NULL, NULL, NULL),
(1266, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:01', NULL, NULL, NULL, NULL),
(1267, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:02', NULL, NULL, NULL, NULL),
(1268, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:03', NULL, NULL, NULL, NULL),
(1269, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:07', NULL, NULL, NULL, NULL),
(1270, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:40', NULL, NULL, NULL, NULL),
(1271, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:44', NULL, NULL, NULL, NULL),
(1272, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:45', NULL, NULL, NULL, NULL),
(1273, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:46', NULL, NULL, NULL, NULL),
(1274, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:47', NULL, NULL, NULL, NULL),
(1275, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:48', NULL, NULL, NULL, NULL),
(1276, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:49', NULL, NULL, NULL, NULL),
(1277, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:37:55', NULL, NULL, NULL, NULL),
(1278, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:39:11', NULL, NULL, NULL, NULL),
(1279, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:39:13', NULL, NULL, NULL, NULL),
(1280, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:39:48', NULL, NULL, NULL, NULL),
(1281, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:40:09', NULL, NULL, NULL, NULL),
(1282, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:40:10', NULL, NULL, NULL, NULL),
(1283, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:42:08', NULL, NULL, NULL, NULL),
(1284, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:43:47', NULL, NULL, NULL, NULL),
(1285, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:44:58', NULL, NULL, NULL, NULL),
(1286, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:00', NULL, NULL, NULL, NULL),
(1287, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:03', NULL, NULL, NULL, NULL),
(1288, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:06', NULL, NULL, NULL, NULL),
(1289, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:07', NULL, NULL, NULL, NULL),
(1290, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:09', NULL, NULL, NULL, NULL),
(1291, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:10', NULL, NULL, NULL, NULL),
(1292, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:11', NULL, NULL, NULL, NULL),
(1293, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:12', NULL, NULL, NULL, NULL),
(1294, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:14', NULL, NULL, NULL, NULL),
(1295, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:15', NULL, NULL, NULL, NULL),
(1296, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:16', NULL, NULL, NULL, NULL),
(1297, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:16', NULL, NULL, NULL, NULL),
(1298, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:17', NULL, NULL, NULL, NULL),
(1299, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:18', NULL, NULL, NULL, NULL),
(1300, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:20', NULL, NULL, NULL, NULL),
(1301, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:21', NULL, NULL, NULL, NULL),
(1302, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:45:26', NULL, NULL, NULL, NULL),
(1303, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:46:36', NULL, NULL, NULL, NULL),
(1304, 18, 'View Report', 'Report ID 35', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:46:44', NULL, NULL, NULL, NULL),
(1305, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:46:47', NULL, NULL, NULL, NULL),
(1306, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:46:50', NULL, NULL, NULL, NULL),
(1307, 18, 'View Report', 'Report ID 34', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:47:01', NULL, NULL, NULL, NULL),
(1308, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:47:11', NULL, NULL, NULL, NULL),
(1309, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:47:12', NULL, NULL, NULL, NULL),
(1310, 18, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:50:00', NULL, NULL, NULL, NULL),
(1311, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:50:17', NULL, NULL, NULL, NULL),
(1312, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:51:13', NULL, NULL, NULL, NULL),
(1313, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 01:51:22', NULL, NULL, NULL, NULL),
(1314, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 02:18:55', NULL, NULL, NULL, NULL),
(1315, 3, 'OTP Email failed', 'User', 'SMTP Error: Could not connect to SMTP host. Failed to connect to server', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-27 13:09:09', NULL, NULL, NULL, NULL),
(1316, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 13:10:21', NULL, NULL, NULL, NULL),
(1317, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 13:10:53', NULL, NULL, NULL, NULL),
(1318, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-27 13:12:51', NULL, NULL, NULL, NULL),
(1319, 2, 'OTP Email failed', 'User', 'SMTP Error: Could not connect to SMTP host. Failed to connect to server', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-27 13:12:59', NULL, NULL, NULL, NULL),
(1320, 18, 'OTP Email failed', 'User', 'SMTP Error: Could not connect to SMTP host. Failed to connect to server', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-07-27 13:13:16', NULL, NULL, NULL, NULL),
(1321, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 08:41:47', NULL, NULL, NULL, NULL),
(1322, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:28:18', NULL, NULL, NULL, NULL),
(1323, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:28:44', NULL, NULL, NULL, NULL),
(1324, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:28:44', NULL, NULL, NULL, NULL),
(1325, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:28:58', NULL, NULL, NULL, NULL),
(1326, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:29:31', NULL, NULL, NULL, NULL),
(1327, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:30:29', NULL, NULL, NULL, NULL),
(1328, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:30:59', NULL, NULL, NULL, NULL),
(1329, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:34:11', NULL, NULL, NULL, NULL),
(1330, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:34:17', NULL, NULL, NULL, NULL),
(1331, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:34:41', NULL, NULL, NULL, NULL),
(1332, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:34:45', NULL, NULL, NULL, NULL),
(1333, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:34:49', NULL, NULL, NULL, NULL),
(1334, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:34:58', NULL, NULL, NULL, NULL),
(1335, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:35:44', NULL, NULL, NULL, NULL),
(1336, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:35:52', NULL, NULL, NULL, NULL),
(1337, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:36:11', NULL, NULL, NULL, NULL),
(1338, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:36:41', NULL, NULL, NULL, NULL),
(1339, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:36:45', NULL, NULL, NULL, NULL),
(1340, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 09:36:49', NULL, NULL, NULL, NULL),
(1341, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:10:37', NULL, NULL, NULL, NULL),
(1342, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:10:47', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(1343, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:10:47', NULL, NULL, NULL, NULL),
(1344, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:14:42', NULL, NULL, NULL, NULL),
(1345, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:14:53', NULL, NULL, NULL, NULL),
(1346, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:02', NULL, NULL, NULL, NULL),
(1347, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:02', NULL, NULL, NULL, NULL),
(1348, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:25', NULL, NULL, NULL, NULL),
(1349, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:40', NULL, NULL, NULL, NULL),
(1350, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:43', NULL, NULL, NULL, NULL),
(1351, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:44', NULL, NULL, NULL, NULL),
(1352, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:52', NULL, NULL, NULL, NULL),
(1353, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:15:52', NULL, NULL, NULL, NULL),
(1354, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:19', NULL, NULL, NULL, NULL),
(1355, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:22', NULL, NULL, NULL, NULL),
(1356, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:22', NULL, NULL, NULL, NULL),
(1357, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:24', NULL, NULL, NULL, NULL),
(1358, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:25', NULL, NULL, NULL, NULL),
(1359, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:28', NULL, NULL, NULL, NULL),
(1360, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:29', NULL, NULL, NULL, NULL),
(1361, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:31', NULL, NULL, NULL, NULL),
(1362, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:33', NULL, NULL, NULL, NULL),
(1363, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:38', NULL, NULL, NULL, NULL),
(1364, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:41', NULL, NULL, NULL, NULL),
(1365, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:45', NULL, NULL, NULL, NULL),
(1366, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:16:48', NULL, NULL, NULL, NULL),
(1367, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:17:50', NULL, NULL, NULL, NULL),
(1368, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:17:50', NULL, NULL, NULL, NULL),
(1369, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:17:54', NULL, NULL, NULL, NULL),
(1370, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:17:54', NULL, NULL, NULL, NULL),
(1371, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:18:11', NULL, NULL, NULL, NULL),
(1372, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-07-31 23:18:12', NULL, NULL, NULL, NULL),
(1373, 18, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-01 00:39:46', NULL, NULL, NULL, NULL),
(1374, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 00:40:12', NULL, NULL, NULL, NULL),
(1375, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 00:40:27', NULL, NULL, NULL, NULL),
(1376, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-01 02:14:46', NULL, NULL, NULL, NULL),
(1377, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 02:14:54', NULL, NULL, NULL, NULL),
(1378, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 02:15:07', NULL, NULL, NULL, NULL),
(1379, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 02:21:45', NULL, NULL, NULL, NULL),
(1380, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 02:21:52', NULL, NULL, NULL, NULL),
(1381, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 02:21:58', NULL, NULL, NULL, NULL),
(1382, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-01 08:44:49', NULL, NULL, NULL, NULL),
(1383, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 08:44:58', NULL, NULL, NULL, NULL),
(1384, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 08:45:07', NULL, NULL, NULL, NULL),
(1385, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:41:04', NULL, NULL, NULL, NULL),
(1386, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:41:15', NULL, NULL, NULL, NULL),
(1387, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:41:32', NULL, NULL, NULL, NULL),
(1388, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:41:32', NULL, NULL, NULL, NULL),
(1389, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:41:45', NULL, NULL, NULL, NULL),
(1390, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:41:55', NULL, NULL, NULL, NULL),
(1391, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:42:39', NULL, NULL, NULL, NULL),
(1392, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:42:41', NULL, NULL, NULL, NULL),
(1393, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:42:44', NULL, NULL, NULL, NULL),
(1394, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:15', NULL, NULL, NULL, NULL),
(1395, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:19', NULL, NULL, NULL, NULL),
(1396, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:21', NULL, NULL, NULL, NULL),
(1397, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:30', NULL, NULL, NULL, NULL),
(1398, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:35', NULL, NULL, NULL, NULL),
(1399, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:47', NULL, NULL, NULL, NULL),
(1400, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:49', NULL, NULL, NULL, NULL),
(1401, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:58', NULL, NULL, NULL, NULL),
(1402, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:48:59', NULL, NULL, NULL, NULL),
(1403, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:49:16', NULL, NULL, NULL, NULL),
(1404, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 09:49:26', NULL, NULL, NULL, NULL),
(1405, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-01 10:47:32', NULL, NULL, NULL, NULL),
(1406, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 10:47:39', NULL, NULL, NULL, NULL),
(1407, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 10:47:49', NULL, NULL, NULL, NULL),
(1408, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-01 13:46:32', NULL, NULL, NULL, NULL),
(1409, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 13:55:17', NULL, NULL, NULL, NULL),
(1410, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 13:55:24', NULL, NULL, NULL, NULL),
(1411, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 13:55:24', NULL, NULL, NULL, NULL),
(1412, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 13:55:59', NULL, NULL, NULL, NULL),
(1413, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-08-01 13:56:44', NULL, NULL, NULL, NULL),
(1414, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 13:58:13', NULL, NULL, NULL, NULL),
(1415, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 13:58:15', NULL, NULL, NULL, NULL),
(1416, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 13:58:17', NULL, NULL, NULL, NULL),
(1417, 2, 'Edit Announcement', 'Announcement ID 8', 'Updated announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:09:38', NULL, NULL, NULL, NULL),
(1418, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:09:43', NULL, NULL, NULL, NULL),
(1419, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:09:44', NULL, NULL, NULL, NULL),
(1420, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:09:47', NULL, NULL, NULL, NULL),
(1421, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:05', NULL, NULL, NULL, NULL),
(1422, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:08', NULL, NULL, NULL, NULL),
(1423, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:21', NULL, NULL, NULL, NULL),
(1424, 2, 'Update Schedule', 'Schedule ID 1', 'Updated schedule for Monday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:31', NULL, NULL, NULL, NULL),
(1425, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:31', NULL, NULL, NULL, NULL),
(1426, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:35', NULL, NULL, NULL, NULL),
(1427, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:39', NULL, NULL, NULL, NULL),
(1428, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:42', NULL, NULL, NULL, NULL),
(1429, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:13:52', NULL, NULL, NULL, NULL),
(1430, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:18:15', NULL, NULL, NULL, NULL),
(1431, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:19:29', NULL, NULL, NULL, NULL),
(1432, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:20:04', NULL, NULL, NULL, NULL),
(1433, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:20:04', NULL, NULL, NULL, NULL),
(1434, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:20:47', NULL, NULL, NULL, NULL),
(1435, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:21:33', NULL, NULL, NULL, NULL),
(1436, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:21:49', NULL, NULL, NULL, NULL),
(1437, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:22:05', NULL, NULL, NULL, NULL),
(1438, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:22:07', NULL, NULL, NULL, NULL),
(1439, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:22:09', NULL, NULL, NULL, NULL),
(1440, 2, 'Edit Announcement', 'Announcement ID 8', 'Updated announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:45:07', NULL, NULL, NULL, NULL),
(1441, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 14:50:23', NULL, NULL, NULL, NULL),
(1442, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:05:20', NULL, NULL, NULL, NULL),
(1443, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:05:24', NULL, NULL, NULL, NULL),
(1444, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:05:26', NULL, NULL, NULL, NULL),
(1445, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:05:28', NULL, NULL, NULL, NULL),
(1446, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:05:32', NULL, NULL, NULL, NULL),
(1447, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:01', NULL, NULL, NULL, NULL),
(1448, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:05', NULL, NULL, NULL, NULL),
(1449, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:14', NULL, NULL, NULL, NULL),
(1450, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:32', NULL, NULL, NULL, NULL),
(1451, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:32', NULL, NULL, NULL, NULL),
(1452, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:44', NULL, NULL, NULL, NULL),
(1453, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:44', NULL, NULL, NULL, NULL),
(1454, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:48', NULL, NULL, NULL, NULL),
(1455, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:48', NULL, NULL, NULL, NULL),
(1456, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:51', NULL, NULL, NULL, NULL),
(1457, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:51', NULL, NULL, NULL, NULL),
(1458, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:53', NULL, NULL, NULL, NULL),
(1459, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:53', NULL, NULL, NULL, NULL),
(1460, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:55', NULL, NULL, NULL, NULL),
(1461, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:55', NULL, NULL, NULL, NULL),
(1462, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:06:59', NULL, NULL, NULL, NULL),
(1463, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:01', NULL, NULL, NULL, NULL),
(1464, 18, 'View Report', 'Report ID 35', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:03', NULL, NULL, NULL, NULL),
(1465, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:13', NULL, NULL, NULL, NULL),
(1466, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:15', NULL, NULL, NULL, NULL),
(1467, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:15', NULL, NULL, NULL, NULL),
(1468, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:16', NULL, NULL, NULL, NULL),
(1469, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:17', NULL, NULL, NULL, NULL),
(1470, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:18', NULL, NULL, NULL, NULL),
(1471, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:20', NULL, NULL, NULL, NULL),
(1472, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:20', NULL, NULL, NULL, NULL),
(1473, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:34', NULL, NULL, NULL, NULL),
(1474, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:40', NULL, NULL, NULL, NULL),
(1475, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:46', NULL, NULL, NULL, NULL),
(1476, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:54', NULL, NULL, NULL, NULL),
(1477, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:56', NULL, NULL, NULL, NULL),
(1478, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:57', NULL, NULL, NULL, NULL),
(1479, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:07:58', NULL, NULL, NULL, NULL),
(1480, 18, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:08:12', NULL, NULL, NULL, NULL),
(1481, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:08:27', NULL, NULL, NULL, NULL),
(1482, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:08:47', NULL, NULL, NULL, NULL),
(1483, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:10:35', NULL, NULL, NULL, NULL),
(1484, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:10:45', NULL, NULL, NULL, NULL),
(1485, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:10:54', NULL, NULL, NULL, NULL),
(1486, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:10:54', NULL, NULL, NULL, NULL),
(1487, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:11:00', NULL, NULL, NULL, NULL),
(1488, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:11:05', NULL, NULL, NULL, NULL),
(1489, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:11:27', NULL, NULL, NULL, NULL),
(1490, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:11:42', NULL, NULL, NULL, NULL),
(1491, 2, 'Report Generated', 'Report Summary', 'Format: csv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:11:53', NULL, NULL, NULL, NULL),
(1492, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:12:21', NULL, NULL, NULL, NULL),
(1493, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:12:24', NULL, NULL, NULL, NULL),
(1494, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:13:04', NULL, NULL, NULL, NULL),
(1495, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:13:09', NULL, NULL, NULL, NULL),
(1496, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:14:02', NULL, NULL, NULL, NULL),
(1497, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:14:33', NULL, NULL, NULL, NULL),
(1498, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:14:51', NULL, NULL, NULL, NULL),
(1499, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:14:59', NULL, NULL, NULL, NULL),
(1500, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:15:13', NULL, NULL, NULL, NULL),
(1501, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:15:47', NULL, NULL, NULL, NULL),
(1502, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:16:00', NULL, NULL, NULL, NULL),
(1503, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:16:16', NULL, NULL, NULL, NULL),
(1504, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:16:28', NULL, NULL, NULL, NULL),
(1505, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:16:38', NULL, NULL, NULL, NULL),
(1506, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:16:56', NULL, NULL, NULL, NULL),
(1507, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:17:07', NULL, NULL, NULL, NULL),
(1508, 2, 'Add Landmark', 'Settings', 'Added landmark: barngay hall', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:17:46', NULL, NULL, NULL, NULL),
(1509, 2, 'Delete Landmark', 'Settings', 'Deleted landmark ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-01 15:17:59', NULL, NULL, NULL, NULL),
(1510, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-01 19:19:48', NULL, NULL, NULL, NULL),
(1511, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 04:39:34', NULL, NULL, NULL, NULL),
(1512, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 04:40:21', NULL, NULL, NULL, NULL),
(1513, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 04:40:21', NULL, NULL, NULL, NULL),
(1514, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 04:40:30', NULL, NULL, NULL, NULL),
(1515, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 04:41:11', NULL, NULL, NULL, NULL),
(1516, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 04:41:18', NULL, NULL, NULL, NULL),
(1517, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-02 06:21:37', NULL, NULL, NULL, NULL),
(1518, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:21:46', NULL, NULL, NULL, NULL),
(1519, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:21:55', NULL, NULL, NULL, NULL),
(1520, 3, 'Report Submitted', 'Waste Report', 'User submitted report ID 42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:48:59', NULL, NULL, NULL, NULL),
(1521, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:55:19', NULL, NULL, NULL, NULL),
(1522, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:55:32', NULL, NULL, NULL, NULL),
(1523, 18, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-08-02 06:55:44', NULL, NULL, NULL, NULL),
(1524, 18, '2FA Resend', 'User', 'Code resent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:56:34', NULL, NULL, NULL, NULL),
(1525, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:56:47', NULL, NULL, NULL, NULL),
(1526, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:56:47', NULL, NULL, NULL, NULL),
(1527, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:56:57', NULL, NULL, NULL, NULL),
(1528, 18, 'View Report', 'Report ID 42', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:56:59', NULL, NULL, NULL, NULL),
(1529, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:27', NULL, NULL, NULL, NULL),
(1530, 18, 'View Report', 'Report ID 35', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:35', NULL, NULL, NULL, NULL),
(1531, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:38', NULL, NULL, NULL, NULL),
(1532, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:41', NULL, NULL, NULL, NULL),
(1533, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:43', NULL, NULL, NULL, NULL),
(1534, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:46', NULL, NULL, NULL, NULL),
(1535, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:50', NULL, NULL, NULL, NULL),
(1536, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:52', NULL, NULL, NULL, NULL),
(1537, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:52', NULL, NULL, NULL, NULL),
(1538, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:55', NULL, NULL, NULL, NULL),
(1539, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:57:56', NULL, NULL, NULL, NULL),
(1540, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:02', NULL, NULL, NULL, NULL),
(1541, 18, 'GIS Monitoring', 'GIS', 'Supervisor viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:02', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(1542, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:27', NULL, NULL, NULL, NULL),
(1543, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:28', NULL, NULL, NULL, NULL),
(1544, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:30', NULL, NULL, NULL, NULL),
(1545, 18, 'Analytics View', 'Analytics', 'Supervisor viewed analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:31', NULL, NULL, NULL, NULL),
(1546, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:32', NULL, NULL, NULL, NULL),
(1547, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:34', NULL, NULL, NULL, NULL),
(1548, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:40', NULL, NULL, NULL, NULL),
(1549, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:41', NULL, NULL, NULL, NULL),
(1550, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:47', NULL, NULL, NULL, NULL),
(1551, 18, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:50', NULL, NULL, NULL, NULL),
(1552, 18, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:58:56', NULL, NULL, NULL, NULL),
(1553, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:59:07', NULL, NULL, NULL, NULL),
(1554, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:59:21', NULL, NULL, NULL, NULL),
(1555, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:59:35', NULL, NULL, NULL, NULL),
(1556, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 06:59:35', NULL, NULL, NULL, NULL),
(1557, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 07:00:01', NULL, NULL, NULL, NULL),
(1558, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 07:00:28', NULL, NULL, NULL, NULL),
(1559, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 07:00:38', NULL, NULL, NULL, NULL),
(1560, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 07:00:41', NULL, NULL, NULL, NULL),
(1561, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 07:00:52', NULL, NULL, NULL, NULL),
(1562, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 07:01:54', NULL, NULL, NULL, NULL),
(1563, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-02 08:19:07', NULL, NULL, NULL, NULL),
(1564, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:19:22', NULL, NULL, NULL, NULL),
(1565, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:19:34', NULL, NULL, NULL, NULL),
(1566, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:19:34', NULL, NULL, NULL, NULL),
(1567, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:20:17', NULL, NULL, NULL, NULL),
(1568, 2, 'Report Generated', 'Report Summary', 'Format: csv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:20:31', NULL, NULL, NULL, NULL),
(1569, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:20:43', NULL, NULL, NULL, NULL),
(1570, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:20:44', NULL, NULL, NULL, NULL),
(1571, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:20:46', NULL, NULL, NULL, NULL),
(1572, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:20:59', NULL, NULL, NULL, NULL),
(1573, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:21:13', NULL, NULL, NULL, NULL),
(1574, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:23:04', NULL, NULL, NULL, NULL),
(1575, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:23:37', NULL, NULL, NULL, NULL),
(1576, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:31:36', NULL, NULL, NULL, NULL),
(1577, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:34:30', NULL, NULL, NULL, NULL),
(1578, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:34:37', NULL, NULL, NULL, NULL),
(1579, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:34:40', NULL, NULL, NULL, NULL),
(1580, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:41:27', NULL, NULL, NULL, NULL),
(1581, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:41:28', NULL, NULL, NULL, NULL),
(1582, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:41:29', NULL, NULL, NULL, NULL),
(1583, 2, 'View Report', 'Report ID 42', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:41:35', NULL, NULL, NULL, NULL),
(1584, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:42:38', NULL, NULL, NULL, NULL),
(1585, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:42:47', NULL, NULL, NULL, NULL),
(1586, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:42:57', NULL, NULL, NULL, NULL),
(1587, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:42:57', NULL, NULL, NULL, NULL),
(1588, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:42:59', NULL, NULL, NULL, NULL),
(1589, 18, 'View Report', 'Report ID 42', 'Supervisor viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:01', NULL, NULL, NULL, NULL),
(1590, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:04', NULL, NULL, NULL, NULL),
(1591, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:07', NULL, NULL, NULL, NULL),
(1592, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:08', NULL, NULL, NULL, NULL),
(1593, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:10', NULL, NULL, NULL, NULL),
(1594, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:11', NULL, NULL, NULL, NULL),
(1595, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:12', NULL, NULL, NULL, NULL),
(1596, 18, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:12', NULL, NULL, NULL, NULL),
(1597, 18, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:18', NULL, NULL, NULL, NULL),
(1598, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:21', NULL, NULL, NULL, NULL),
(1599, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:23', NULL, NULL, NULL, NULL),
(1600, 18, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:28', NULL, NULL, NULL, NULL),
(1601, 18, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:31', NULL, NULL, NULL, NULL),
(1602, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:43:39', NULL, NULL, NULL, NULL),
(1603, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:44:43', NULL, NULL, NULL, NULL),
(1604, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:44:43', NULL, NULL, NULL, NULL),
(1605, 2, 'View Report', 'Report ID 42', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:44:50', NULL, NULL, NULL, NULL),
(1606, 2, 'View Report', 'Report ID 35', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:45:16', NULL, NULL, NULL, NULL),
(1607, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:45:26', NULL, NULL, NULL, NULL),
(1608, 2, 'Report Rejected', 'Report ID 34', 'Rejected report. Reason: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:45:42', NULL, NULL, NULL, NULL),
(1609, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:45:43', NULL, NULL, NULL, NULL),
(1610, 2, 'View Report', 'Report ID 35', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:47:25', NULL, NULL, NULL, NULL),
(1611, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:14', NULL, NULL, NULL, NULL),
(1612, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:17', NULL, NULL, NULL, NULL),
(1613, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:20', NULL, NULL, NULL, NULL),
(1614, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:21', NULL, NULL, NULL, NULL),
(1615, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:22', NULL, NULL, NULL, NULL),
(1616, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:48', NULL, NULL, NULL, NULL),
(1617, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:50', NULL, NULL, NULL, NULL),
(1618, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:49:51', NULL, NULL, NULL, NULL),
(1619, 2, 'Schedule Postponed', 'Schedule ID 1', 'Postponed to 2026-07-23. Reason: holiday testing', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:50:16', NULL, NULL, NULL, NULL),
(1620, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:50:16', NULL, NULL, NULL, NULL),
(1621, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:50:30', NULL, NULL, NULL, NULL),
(1622, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:50:33', NULL, NULL, NULL, NULL),
(1623, 2, 'Add Schedule', 'Schedule ID 5', 'Added new schedule for Monday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:51:19', NULL, NULL, NULL, NULL),
(1624, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:51:19', NULL, NULL, NULL, NULL),
(1625, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:51:42', NULL, NULL, NULL, NULL),
(1626, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:51:55', NULL, NULL, NULL, NULL),
(1627, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:52:05', NULL, NULL, NULL, NULL),
(1628, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 08:52:12', NULL, NULL, NULL, NULL),
(1629, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-02 14:37:11', NULL, NULL, NULL, NULL),
(1630, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 14:37:20', NULL, NULL, NULL, NULL),
(1631, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 14:41:13', NULL, NULL, NULL, NULL),
(1632, 3, 'Report Deleted', 'Report ID 42', 'Resident deleted their pending report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 16:39:20', NULL, NULL, NULL, NULL),
(1633, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-02 19:06:17', NULL, NULL, NULL, NULL),
(1634, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:06:26', NULL, NULL, NULL, NULL),
(1635, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:06:42', NULL, NULL, NULL, NULL),
(1636, 3, 'Report Submitted', 'Waste Report', 'User submitted report ID 43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:28:52', NULL, NULL, NULL, NULL),
(1637, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:32:03', NULL, NULL, NULL, NULL),
(1638, 18, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:32:37', NULL, NULL, NULL, NULL),
(1639, 18, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:32:58', NULL, NULL, NULL, NULL),
(1640, 18, 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:32:58', NULL, NULL, NULL, NULL),
(1641, 18, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:33:11', NULL, NULL, NULL, NULL),
(1642, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:33:24', NULL, NULL, NULL, NULL),
(1643, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:33:38', NULL, NULL, NULL, NULL),
(1644, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:33:38', NULL, NULL, NULL, NULL),
(1645, 2, 'View Report', 'Report ID 43', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:34:08', NULL, NULL, NULL, NULL),
(1646, 2, 'Report Verified', 'Report ID 43', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:35:21', NULL, NULL, NULL, NULL),
(1647, 2, 'View Report', 'Report ID 43', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:35:21', NULL, NULL, NULL, NULL),
(1648, 2, 'View Report', 'Report ID 35', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:35:48', NULL, NULL, NULL, NULL),
(1649, 2, 'View Report', 'Report ID 35', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:35:55', NULL, NULL, NULL, NULL),
(1650, 2, 'View Report', 'Report ID 43', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:36:36', NULL, NULL, NULL, NULL),
(1651, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:37:13', NULL, NULL, NULL, NULL),
(1652, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:37:22', NULL, NULL, NULL, NULL),
(1653, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:37:37', NULL, NULL, NULL, NULL),
(1654, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:37:50', NULL, NULL, NULL, NULL),
(1655, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:39:54', NULL, NULL, NULL, NULL),
(1656, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:40:06', NULL, NULL, NULL, NULL),
(1657, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:40:16', NULL, NULL, NULL, NULL),
(1658, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:40:39', NULL, NULL, NULL, NULL),
(1659, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:40:39', NULL, NULL, NULL, NULL),
(1660, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:41:00', NULL, NULL, NULL, NULL),
(1661, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:43:10', NULL, NULL, NULL, NULL),
(1662, 2, 'Add Landmark', 'Settings', 'Added landmark: SCHOOL', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:46:03', NULL, NULL, NULL, NULL),
(1663, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:46:16', NULL, NULL, NULL, NULL),
(1664, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:46:28', NULL, NULL, NULL, NULL),
(1665, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:46:35', NULL, NULL, NULL, NULL),
(1666, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:48:32', NULL, NULL, NULL, NULL),
(1667, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:48:41', NULL, NULL, NULL, NULL),
(1668, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:48:49', NULL, NULL, NULL, NULL),
(1669, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:48:49', NULL, NULL, NULL, NULL),
(1670, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:53:17', NULL, NULL, NULL, NULL),
(1671, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:53:47', NULL, NULL, NULL, NULL),
(1672, 2, 'Update Report Generation Settings', 'Settings', 'Updated report generation settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:56:49', NULL, NULL, NULL, NULL),
(1673, 2, 'Report Generated', 'Report Summary', 'Format: csv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:57:18', NULL, NULL, NULL, NULL),
(1674, 2, 'Report Generated', 'Report Summary', 'Format: csv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:57:33', NULL, NULL, NULL, NULL),
(1675, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 19:57:39', NULL, NULL, NULL, NULL),
(1676, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:02:44', NULL, NULL, NULL, NULL),
(1677, 2, 'View Report', 'Report ID 35', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:03:09', NULL, NULL, NULL, NULL),
(1678, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:05:12', NULL, NULL, NULL, NULL),
(1679, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:05:21', NULL, NULL, NULL, NULL),
(1680, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:05:30', NULL, NULL, NULL, NULL),
(1681, 3, 'Report Submitted', 'Waste Report', 'User submitted report ID 44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:07:25', NULL, NULL, NULL, NULL),
(1682, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:07:47', NULL, NULL, NULL, NULL),
(1683, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:07:56', NULL, NULL, NULL, NULL),
(1684, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:08:11', NULL, NULL, NULL, NULL),
(1685, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:08:11', NULL, NULL, NULL, NULL),
(1686, 2, 'View Report', 'Report ID 44', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:08:23', NULL, NULL, NULL, NULL),
(1687, 2, 'Report Verified', 'Report ID 44', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:09:52', NULL, NULL, NULL, NULL),
(1688, 2, 'View Report', 'Report ID 44', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:09:53', NULL, NULL, NULL, NULL),
(1689, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:09:58', NULL, NULL, NULL, NULL),
(1690, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:10:10', NULL, NULL, NULL, NULL),
(1691, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:10:20', NULL, NULL, NULL, NULL),
(1692, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:10:28', NULL, NULL, NULL, NULL),
(1693, 3, 'Report Submitted', 'Waste Report', 'User submitted report ID 45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:12:38', NULL, NULL, NULL, NULL),
(1694, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:13:31', NULL, NULL, NULL, NULL),
(1695, NULL, 'Registration OTP sent', 'User', 'OTP sent to umalicedrick29@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:15:55', NULL, NULL, NULL, NULL),
(1696, 19, 'Registration verified', 'User', 'Account activated via email OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:16:17', NULL, NULL, NULL, NULL),
(1697, 19, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:16:43', NULL, NULL, NULL, NULL),
(1698, 19, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:17:07', NULL, NULL, NULL, NULL),
(1699, 19, 'Report Submitted', 'Waste Report', 'User submitted report ID 46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:21:42', NULL, NULL, NULL, NULL),
(1700, 19, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:22:01', NULL, NULL, NULL, NULL),
(1701, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:22:12', NULL, NULL, NULL, NULL),
(1702, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:22:39', NULL, NULL, NULL, NULL),
(1703, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:22:39', NULL, NULL, NULL, NULL),
(1704, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:23:05', NULL, NULL, NULL, NULL),
(1705, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:24:35', NULL, NULL, NULL, NULL),
(1706, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:24:36', NULL, NULL, NULL, NULL),
(1707, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:24:52', NULL, NULL, NULL, NULL),
(1708, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:26:44', NULL, NULL, NULL, NULL),
(1709, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:26:47', NULL, NULL, NULL, NULL),
(1710, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:30:12', NULL, NULL, NULL, NULL),
(1711, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:30:18', NULL, NULL, NULL, NULL),
(1712, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:30:22', NULL, NULL, NULL, NULL),
(1713, 2, 'Edit Announcement', 'Announcement ID 9', 'Updated announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:32:12', NULL, NULL, NULL, NULL),
(1714, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:32:17', NULL, NULL, NULL, NULL),
(1715, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:32:25', NULL, NULL, NULL, NULL),
(1716, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:32:31', NULL, NULL, NULL, NULL),
(1717, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:32:34', NULL, NULL, NULL, NULL),
(1718, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:34:43', NULL, NULL, NULL, NULL),
(1719, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-02 20:34:49', NULL, NULL, NULL, NULL),
(1720, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 12:23:36', NULL, NULL, NULL, NULL),
(1721, 3, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-08-05 12:23:50', NULL, NULL, NULL, NULL),
(1722, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 12:24:17', NULL, NULL, NULL, NULL),
(1723, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 12:26:55', NULL, NULL, NULL, NULL),
(1724, 2, 'Password Reset', 'User', 'Password reset via email OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:30:50', NULL, NULL, NULL, NULL),
(1725, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:31:08', NULL, NULL, NULL, NULL),
(1726, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:31:16', NULL, NULL, NULL, NULL),
(1727, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:31:16', NULL, NULL, NULL, NULL),
(1728, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:31:31', NULL, NULL, NULL, NULL),
(1729, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:33:51', NULL, NULL, NULL, NULL),
(1730, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:33:56', NULL, NULL, NULL, NULL),
(1731, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:34:18', NULL, NULL, NULL, NULL),
(1732, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-05 15:34:20', NULL, NULL, NULL, NULL),
(1733, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-06 20:51:46', NULL, NULL, NULL, NULL),
(1734, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-06 20:53:15', NULL, NULL, NULL, NULL),
(1735, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-06 20:53:15', NULL, NULL, NULL, NULL),
(1736, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-06 21:44:15', NULL, NULL, NULL, NULL),
(1737, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-06 21:44:16', NULL, NULL, NULL, NULL),
(1738, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-06 21:50:44', NULL, NULL, NULL, NULL),
(1739, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-07 08:21:41', NULL, NULL, NULL, NULL),
(1740, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:37:51', NULL, NULL, NULL, NULL),
(1741, 2, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-08-07 08:38:04', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(1742, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:38:18', NULL, NULL, NULL, NULL),
(1743, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:38:18', NULL, NULL, NULL, NULL),
(1744, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:39:06', NULL, NULL, NULL, NULL),
(1745, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:39:42', NULL, NULL, NULL, NULL),
(1746, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:40:02', NULL, NULL, NULL, NULL),
(1747, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:57:22', NULL, NULL, NULL, NULL),
(1748, 2, 'Report Verified', 'Report ID 46', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:57:26', NULL, NULL, NULL, NULL),
(1749, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:57:26', NULL, NULL, NULL, NULL),
(1750, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:57:32', NULL, NULL, NULL, NULL),
(1751, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:57:45', NULL, NULL, NULL, NULL),
(1752, 2, 'Report Resolved', 'Report ID 46', 'Resolved report. Remark: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:57:47', NULL, NULL, NULL, NULL),
(1753, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 08:57:48', NULL, NULL, NULL, NULL),
(1754, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:01:03', NULL, NULL, NULL, NULL),
(1755, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:02:51', NULL, NULL, NULL, NULL),
(1756, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:03:17', NULL, NULL, NULL, NULL),
(1757, 2, 'View Report', 'Report ID 45', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:03:31', NULL, NULL, NULL, NULL),
(1758, 2, 'Report Verified', 'Report ID 45', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:03:34', NULL, NULL, NULL, NULL),
(1759, 2, 'View Report', 'Report ID 45', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:03:35', NULL, NULL, NULL, NULL),
(1760, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:03:49', NULL, NULL, NULL, NULL),
(1761, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:06:51', NULL, NULL, NULL, NULL),
(1762, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:08:36', NULL, NULL, NULL, NULL),
(1763, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:08:38', NULL, NULL, NULL, NULL),
(1764, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:08:39', NULL, NULL, NULL, NULL),
(1765, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:09:04', NULL, NULL, NULL, NULL),
(1766, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:09:48', NULL, NULL, NULL, NULL),
(1767, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:25:46', NULL, NULL, NULL, NULL),
(1768, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:26:00', NULL, NULL, NULL, NULL),
(1769, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:37:51', NULL, NULL, NULL, NULL),
(1770, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:39:18', NULL, NULL, NULL, NULL),
(1771, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:39:53', NULL, NULL, NULL, NULL),
(1772, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:39:57', NULL, NULL, NULL, NULL),
(1773, 2, 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 09:41:11', NULL, NULL, NULL, NULL),
(1774, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-07 10:26:55', NULL, NULL, NULL, NULL),
(1775, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:27:13', NULL, NULL, NULL, NULL),
(1776, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:27:36', NULL, NULL, NULL, NULL),
(1777, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:27:36', NULL, NULL, NULL, NULL),
(1778, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:27:42', NULL, NULL, NULL, NULL),
(1779, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:32:36', NULL, NULL, NULL, NULL),
(1780, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:32:45', NULL, NULL, NULL, NULL),
(1781, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:33:36', NULL, NULL, NULL, NULL),
(1782, 2, 'Analytics Export', 'Analytics', 'Exported analytics Excel/CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:33:52', NULL, NULL, NULL, NULL),
(1783, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 10:38:12', NULL, NULL, NULL, NULL),
(1784, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-07 12:03:32', NULL, NULL, NULL, NULL),
(1785, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:45:37', NULL, NULL, NULL, NULL),
(1786, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:45:48', NULL, NULL, NULL, NULL),
(1787, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:45:48', NULL, NULL, NULL, NULL),
(1788, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:46:13', NULL, NULL, NULL, NULL),
(1789, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:46:23', NULL, NULL, NULL, NULL),
(1790, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:46:23', NULL, NULL, NULL, NULL),
(1791, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:54:33', NULL, NULL, NULL, NULL),
(1792, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 14:54:35', NULL, NULL, NULL, NULL),
(1793, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:38:09', NULL, NULL, NULL, NULL),
(1794, 2, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-08-07 22:38:18', NULL, NULL, NULL, NULL),
(1795, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:38:29', NULL, NULL, NULL, NULL),
(1796, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:38:29', NULL, NULL, NULL, NULL),
(1797, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:44:45', NULL, NULL, NULL, NULL),
(1798, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:46:08', NULL, NULL, NULL, NULL),
(1799, 2, 'View Report', 'Report ID 35', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:46:25', NULL, NULL, NULL, NULL),
(1800, 2, 'View Report', 'Report ID 45', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:46:29', NULL, NULL, NULL, NULL),
(1801, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:49:17', NULL, NULL, NULL, NULL),
(1802, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-07 22:51:00', NULL, NULL, NULL, NULL),
(1803, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:51:20', NULL, NULL, NULL, NULL),
(1804, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:51:21', NULL, NULL, NULL, NULL),
(1805, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:51:31', NULL, NULL, NULL, NULL),
(1806, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:51:36', NULL, NULL, NULL, NULL),
(1807, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:51:36', NULL, NULL, NULL, NULL),
(1808, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:51:48', NULL, NULL, NULL, NULL),
(1809, 3, 'Report Submitted', 'Waste Report', 'User submitted report ID 47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:52:35', NULL, NULL, NULL, NULL),
(1810, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:52:52', NULL, NULL, NULL, NULL),
(1811, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:53:10', NULL, NULL, NULL, NULL),
(1812, 2, 'Report Verified', 'Report ID 47', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:53:15', NULL, NULL, NULL, NULL),
(1813, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:53:16', NULL, NULL, NULL, NULL),
(1814, 2, 'View Report', 'Report ID 44', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:53:38', NULL, NULL, NULL, NULL),
(1815, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 22:59:11', NULL, NULL, NULL, NULL),
(1816, 2, 'Delete Landmark', 'Settings', 'Deleted landmark ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:10:50', NULL, NULL, NULL, NULL),
(1817, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:11:18', NULL, NULL, NULL, NULL),
(1818, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:11:54', NULL, NULL, NULL, NULL),
(1819, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:12:14', NULL, NULL, NULL, NULL),
(1820, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:12:18', NULL, NULL, NULL, NULL),
(1821, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:12:31', NULL, NULL, NULL, NULL),
(1822, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:13:26', NULL, NULL, NULL, NULL),
(1823, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:37:18', NULL, NULL, NULL, NULL),
(1824, 2, 'Report In Progress', 'Report ID 47', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:37:47', NULL, NULL, NULL, NULL),
(1825, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:37:48', NULL, NULL, NULL, NULL),
(1826, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:40:06', NULL, NULL, NULL, NULL),
(1827, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-07 23:40:28', NULL, NULL, NULL, NULL),
(1828, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 00:28:44', NULL, NULL, NULL, NULL),
(1829, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 00:28:56', NULL, NULL, NULL, NULL),
(1830, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:28:57', NULL, NULL, NULL, NULL),
(1831, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:29:04', NULL, NULL, NULL, NULL),
(1832, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:29:21', NULL, NULL, NULL, NULL),
(1833, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:29:31', NULL, NULL, NULL, NULL),
(1834, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:29:31', NULL, NULL, NULL, NULL),
(1835, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:42:49', NULL, NULL, NULL, NULL),
(1836, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:48:41', NULL, NULL, NULL, NULL),
(1837, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:52:39', NULL, NULL, NULL, NULL),
(1838, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:52:49', NULL, NULL, NULL, NULL),
(1839, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:52:50', NULL, NULL, NULL, NULL),
(1840, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:52:57', NULL, NULL, NULL, NULL),
(1841, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:53:04', NULL, NULL, NULL, NULL),
(1842, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 00:57:32', NULL, NULL, NULL, NULL),
(1843, 2, 'Account Suspended', 'User Management', 'Suspended user ID 16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:08:35', NULL, NULL, NULL, NULL),
(1844, 2, 'Account Reactivated', 'User Management', 'Reactivated user ID 16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:08:41', NULL, NULL, NULL, NULL),
(1845, 2, 'Account Suspended', 'User Management', 'Suspended user ID 16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:08:46', NULL, NULL, NULL, NULL),
(1846, 2, 'Account Suspended', 'User Management', 'Suspended user ID 17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:09:05', NULL, NULL, NULL, NULL),
(1847, 2, 'Account Reactivated', 'User Management', 'Reactivated user ID 17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:09:10', NULL, NULL, NULL, NULL),
(1848, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:09:15', NULL, NULL, NULL, NULL),
(1849, 2, 'Account Reactivated', 'User Management', 'Reactivated user ID 16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:10:08', NULL, NULL, NULL, NULL),
(1850, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:11:21', NULL, NULL, NULL, NULL),
(1851, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:34:31', NULL, NULL, NULL, NULL),
(1852, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:34:40', NULL, NULL, NULL, NULL),
(1853, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:34:56', NULL, NULL, NULL, NULL),
(1854, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:37:15', NULL, NULL, NULL, NULL),
(1855, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:37:44', NULL, NULL, NULL, NULL),
(1856, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:39:06', NULL, NULL, NULL, NULL),
(1857, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:39:22', NULL, NULL, NULL, NULL),
(1858, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:39:51', NULL, NULL, NULL, NULL),
(1859, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:40:26', NULL, NULL, NULL, NULL),
(1860, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 01:41:05', NULL, NULL, NULL, NULL),
(1861, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 09:25:01', NULL, NULL, NULL, NULL),
(1862, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:25:30', NULL, NULL, NULL, NULL),
(1863, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:25:40', NULL, NULL, NULL, NULL),
(1864, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:25:40', NULL, NULL, NULL, NULL),
(1865, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 09:35:51', NULL, NULL, NULL, NULL),
(1866, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:35:58', NULL, NULL, NULL, NULL),
(1867, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:36:12', NULL, NULL, NULL, NULL),
(1868, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:37:09', NULL, NULL, NULL, NULL),
(1869, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:37:44', NULL, NULL, NULL, NULL),
(1870, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:37:53', NULL, NULL, NULL, NULL),
(1871, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:40:02', NULL, NULL, NULL, NULL),
(1872, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:40:16', NULL, NULL, NULL, NULL),
(1873, 2, 'Report Rejected', 'Report ID 47', 'Rejected report. Reason: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:40:27', NULL, NULL, NULL, NULL),
(1874, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:40:28', NULL, NULL, NULL, NULL),
(1875, 2, 'View Report', 'Report ID 45', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:41:18', NULL, NULL, NULL, NULL),
(1876, 2, 'Report In Progress', 'Report ID 45', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:41:23', NULL, NULL, NULL, NULL),
(1877, 2, 'View Report', 'Report ID 45', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:41:23', NULL, NULL, NULL, NULL),
(1878, 2, 'Report Resolved', 'Report ID 45', 'Resolved report. Remark: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:41:32', NULL, NULL, NULL, NULL),
(1879, 2, 'View Report', 'Report ID 45', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:41:33', NULL, NULL, NULL, NULL),
(1880, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:47:04', NULL, NULL, NULL, NULL),
(1881, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:47:30', NULL, NULL, NULL, NULL),
(1882, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:47:33', NULL, NULL, NULL, NULL),
(1883, 2, 'View Report', 'Report ID 34', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:47:43', NULL, NULL, NULL, NULL),
(1884, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:48:23', NULL, NULL, NULL, NULL),
(1885, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:48:40', NULL, NULL, NULL, NULL),
(1886, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:48:56', NULL, NULL, NULL, NULL),
(1887, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:49:07', NULL, NULL, NULL, NULL),
(1888, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:49:09', NULL, NULL, NULL, NULL),
(1889, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:49:12', NULL, NULL, NULL, NULL),
(1890, 3, 'Report Submitted', 'Waste Report', 'User submitted report ID 48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 09:59:25', NULL, NULL, NULL, NULL),
(1891, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 10:05:42', NULL, NULL, NULL, NULL),
(1892, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 11:01:38', NULL, NULL, NULL, NULL),
(1893, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:02:19', NULL, NULL, NULL, NULL),
(1894, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:02:46', NULL, NULL, NULL, NULL),
(1895, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:02:46', NULL, NULL, NULL, NULL),
(1896, 2, 'Account Suspended', 'User Management', 'Suspended user ID 19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:03:21', NULL, NULL, NULL, NULL),
(1897, 2, 'Account Reactivated', 'User Management', 'Reactivated user ID 19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:03:30', NULL, NULL, NULL, NULL),
(1898, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:03:39', NULL, NULL, NULL, NULL),
(1899, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:19:39', NULL, NULL, NULL, NULL),
(1900, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:19:41', NULL, NULL, NULL, NULL),
(1901, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:22:20', NULL, NULL, NULL, NULL),
(1902, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:22:34', NULL, NULL, NULL, NULL),
(1903, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-08-08 11:25:17', NULL, NULL, NULL, NULL),
(1904, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-08-08 11:25:25', NULL, NULL, NULL, NULL),
(1905, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-08-08 11:25:35', NULL, NULL, NULL, NULL),
(1906, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:26:01', NULL, NULL, NULL, NULL),
(1907, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:26:12', NULL, NULL, NULL, NULL),
(1908, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-08-08 11:26:53', NULL, NULL, NULL, NULL),
(1909, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:27:06', NULL, NULL, NULL, NULL),
(1910, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:27:20', NULL, NULL, NULL, NULL),
(1911, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:30:41', NULL, NULL, NULL, NULL),
(1912, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:31:11', NULL, NULL, NULL, NULL),
(1913, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:31:14', NULL, NULL, NULL, NULL),
(1914, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 11:31:20', NULL, NULL, NULL, NULL),
(1915, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 14:21:17', NULL, NULL, NULL, NULL),
(1916, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 14:21:34', NULL, NULL, NULL, NULL),
(1917, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 14:22:44', NULL, NULL, NULL, NULL),
(1918, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 14:22:54', NULL, NULL, NULL, NULL),
(1919, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 14:22:55', NULL, NULL, NULL, NULL),
(1920, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 16:15:53', NULL, NULL, NULL, NULL),
(1921, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 16:16:07', NULL, NULL, NULL, NULL),
(1922, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 16:16:27', NULL, NULL, NULL, NULL),
(1923, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 16:16:27', NULL, NULL, NULL, NULL),
(1924, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:12:40', NULL, NULL, NULL, NULL),
(1925, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 17:14:51', NULL, NULL, NULL, NULL),
(1926, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:14:58', NULL, NULL, NULL, NULL),
(1927, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:15:13', NULL, NULL, NULL, NULL),
(1928, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:16:04', NULL, NULL, NULL, NULL),
(1929, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-08-08 17:16:42', NULL, NULL, NULL, NULL),
(1930, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:17:12', NULL, NULL, NULL, NULL),
(1931, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:17:24', NULL, NULL, NULL, NULL),
(1932, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:18:01', NULL, NULL, NULL, NULL),
(1933, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:18:42', NULL, NULL, NULL, NULL),
(1934, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:22:49', NULL, NULL, NULL, NULL),
(1935, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:41:18', NULL, NULL, NULL, NULL),
(1936, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:49:29', NULL, NULL, NULL, NULL),
(1937, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:52:38', NULL, NULL, NULL, NULL),
(1938, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 17:57:20', NULL, NULL, NULL, NULL),
(1939, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:04:21', NULL, NULL, NULL, NULL),
(1940, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:05:12', NULL, NULL, NULL, NULL),
(1941, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:06:38', NULL, NULL, NULL, NULL),
(1942, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:07:51', NULL, NULL, NULL, NULL),
(1943, 2, 'Account Suspended', 'User Management', 'Suspended user ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:21:36', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(1944, 2, 'Account Reactivated', 'User Management', 'Reactivated user ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:21:46', NULL, NULL, NULL, NULL),
(1945, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 18:24:22', NULL, NULL, NULL, NULL),
(1946, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:24:30', NULL, NULL, NULL, NULL),
(1947, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:24:40', NULL, NULL, NULL, NULL),
(1948, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 18:29:01', NULL, NULL, NULL, NULL),
(1949, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 19:58:19', NULL, NULL, NULL, NULL),
(1950, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 19:58:35', NULL, NULL, NULL, NULL),
(1951, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 19:58:46', NULL, NULL, NULL, NULL),
(1952, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 19:58:46', NULL, NULL, NULL, NULL),
(1953, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 19:58:49', NULL, NULL, NULL, NULL),
(1954, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'success', '2026-08-08 20:00:39', NULL, NULL, NULL, NULL),
(1955, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:01:38', NULL, NULL, NULL, NULL),
(1956, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:01:48', NULL, NULL, NULL, NULL),
(1957, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:01:59', NULL, NULL, NULL, NULL),
(1958, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:02:13', NULL, NULL, NULL, NULL),
(1959, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:02:27', NULL, NULL, NULL, NULL),
(1960, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:02:43', NULL, NULL, NULL, NULL),
(1961, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:02:56', NULL, NULL, NULL, NULL),
(1962, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:03:10', NULL, NULL, NULL, NULL),
(1963, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:03:18', NULL, NULL, NULL, NULL),
(1964, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:03:20', NULL, NULL, NULL, NULL),
(1965, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 20:04:08', NULL, NULL, NULL, NULL),
(1966, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:04:19', NULL, NULL, NULL, NULL),
(1967, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:04:35', NULL, NULL, NULL, NULL),
(1968, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:05:45', NULL, NULL, NULL, NULL),
(1969, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:07:06', NULL, NULL, NULL, NULL),
(1970, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:08:34', NULL, NULL, NULL, NULL),
(1971, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:08:54', NULL, NULL, NULL, NULL),
(1972, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:09:06', NULL, NULL, NULL, NULL),
(1973, 2, 'Update Report Form Settings', 'Settings', 'Updated report form settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:10:12', NULL, NULL, NULL, NULL),
(1974, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:21:23', NULL, NULL, NULL, NULL),
(1975, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 20:21:26', NULL, NULL, NULL, NULL),
(1976, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 21:57:37', NULL, NULL, NULL, NULL),
(1977, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-08 21:59:11', NULL, NULL, NULL, NULL),
(1978, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 21:59:28', NULL, NULL, NULL, NULL),
(1979, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 21:59:40', NULL, NULL, NULL, NULL),
(1980, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 21:59:40', NULL, NULL, NULL, NULL),
(1981, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 22:00:08', NULL, NULL, NULL, NULL),
(1982, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 22:00:09', NULL, NULL, NULL, NULL),
(1983, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 22:00:34', NULL, NULL, NULL, NULL),
(1984, 3, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-08-08 22:00:49', NULL, NULL, NULL, NULL),
(1985, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 22:00:57', NULL, NULL, NULL, NULL),
(1986, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 22:03:12', NULL, NULL, NULL, NULL),
(1987, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-08 22:03:15', NULL, NULL, NULL, NULL),
(1988, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-09 01:37:27', NULL, NULL, NULL, NULL),
(1989, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 03:53:16', NULL, NULL, NULL, NULL),
(1990, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:02:46', NULL, NULL, NULL, NULL),
(1991, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-09 04:03:33', NULL, NULL, NULL, NULL),
(1992, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:03:46', NULL, NULL, NULL, NULL),
(1993, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:04:04', NULL, NULL, NULL, NULL),
(1994, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:04:04', NULL, NULL, NULL, NULL),
(1995, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:04:08', NULL, NULL, NULL, NULL),
(1996, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:04:14', NULL, NULL, NULL, NULL),
(1997, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:05:16', NULL, NULL, NULL, NULL),
(1998, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:05:31', NULL, NULL, NULL, NULL),
(1999, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:05:42', NULL, NULL, NULL, NULL),
(2000, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:05:42', NULL, NULL, NULL, NULL),
(2001, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-09 04:52:23', NULL, NULL, NULL, NULL),
(2002, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:52:36', NULL, NULL, NULL, NULL),
(2003, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:52:54', NULL, NULL, NULL, NULL),
(2004, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:52:54', NULL, NULL, NULL, NULL),
(2005, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:53:08', NULL, NULL, NULL, NULL),
(2006, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:53:29', NULL, NULL, NULL, NULL),
(2007, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:53:40', NULL, NULL, NULL, NULL),
(2008, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:53:41', NULL, NULL, NULL, NULL),
(2009, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 04:53:42', NULL, NULL, NULL, NULL),
(2010, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:05:17', NULL, NULL, NULL, NULL),
(2011, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:14:20', NULL, NULL, NULL, NULL),
(2012, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:23:47', NULL, NULL, NULL, NULL),
(2013, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:23:48', NULL, NULL, NULL, NULL),
(2014, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:24:10', NULL, NULL, NULL, NULL),
(2015, 2, 'Account Suspended', 'User Management', 'Suspended user ID 15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:54:35', NULL, NULL, NULL, NULL),
(2016, 2, 'Account Reactivated', 'User Management', 'Reactivated user ID 15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:54:43', NULL, NULL, NULL, NULL),
(2017, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:54:52', NULL, NULL, NULL, NULL),
(2018, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-09 05:55:00', NULL, NULL, NULL, NULL),
(2019, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:55:12', NULL, NULL, NULL, NULL),
(2020, 3, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'failed', '2026-08-09 05:55:25', NULL, NULL, NULL, NULL),
(2021, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 05:55:34', NULL, NULL, NULL, NULL),
(2022, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-09 06:27:50', NULL, NULL, NULL, NULL),
(2023, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-09 08:55:42', NULL, NULL, NULL, NULL),
(2024, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 08:55:50', NULL, NULL, NULL, NULL),
(2025, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 08:56:08', NULL, NULL, NULL, NULL);

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
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `special_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_schedules`
--

INSERT INTO `collection_schedules` (`schedule_id`, `collection_day`, `start_time`, `end_time`, `waste_type`, `status`, `created_by`, `created_at`, `updated_at`, `special_notes`) VALUES
(1, 'Monday', '06:00:00', '10:00:00', 'Biodegradable', 'active', 1, '2026-07-26 06:58:41', '2026-07-26 06:58:41', 'Segregate biodegradable waste'),
(2, 'Wednesday', '06:00:00', '10:00:00', 'Non-Biodegradable', 'active', 1, '2026-07-26 06:58:41', '2026-07-26 06:58:41', 'Plastic, bottles, and recyclables'),
(3, 'Friday', '06:00:00', '12:00:00', 'Residual Waste', 'active', 1, '2026-07-26 06:58:41', '2026-07-26 06:58:41', 'Non-recyclable waste only'),
(4, 'Saturday', '07:00:00', '11:00:00', 'Special / Hazardous', 'active', 1, '2026-07-26 06:58:41', '2026-07-26 06:58:41', 'By appointment – call office 24h ahead'),
(5, 'Monday', '10:50:00', '20:50:00', 'Non-Biodegradable', 'active', 2, '2026-08-02 08:51:19', '2026-08-02 08:51:19', 'testing notes');

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
(19, 1, 3),
(20, 1, 4),
(21, 1, 5),
(4, 2, 4),
(5, 2, 5),
(7, 3, 1),
(8, 3, 2),
(9, 3, 3),
(10, 3, 4),
(11, 3, 5),
(14, 4, 1),
(15, 4, 2),
(16, 4, 3),
(17, 4, 4),
(18, 4, 5),
(22, 5, 1),
(23, 5, 2),
(24, 5, 3),
(25, 5, 4),
(26, 5, 5);

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
(12, 'floressktt11@gmail.com', '::1', '2026-07-25 06:00:00', 1),
(13, 'floressktt11@gmail.com', '::1', '2026-07-25 08:00:00', 1),
(14, 'floressktt11@gmail.com', '::1', '2026-07-25 09:00:00', 1),
(15, 'floressktt11@gmail.com', '::1', '2026-07-25 10:00:00', 1),
(16, 'floressktt11@gmail.com', '::1', '2026-07-25 16:00:00', 2),
(18, 'floreshans.neust@gmail.com', '::1', '2026-07-25 17:00:00', 2),
(20, 'floressktt11@gmail.com', '::1', '2026-07-25 17:00:00', 1),
(21, 'floressktt11@gmail.com', '::1', '2026-07-25 19:00:00', 1),
(22, 'floressktt11@gmail.com', '::1', '2026-07-25 22:00:00', 1),
(23, 'floressktt11@gmail.com', '::1', '2026-07-26 00:00:00', 1),
(24, 'floressktt11@gmail.com', '::1', '2026-07-26 02:00:00', 2),
(26, 'floressktt11@gmail.com', '::1', '2026-07-26 03:00:00', 2),
(28, 'floressktt11@gmail.com', '::1', '2026-07-26 06:00:00', 1),
(29, 'floreshans.neust@gmail.com', '::1', '2026-07-26 06:00:00', 1),
(30, 'floreshans.neust@gmail.com', '::1', '2026-07-26 07:00:00', 2),
(32, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-07-26 07:00:00', 1),
(33, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-07-26 14:00:00', 1),
(34, 'floreshans.neust@gmail.com', '::1', '2026-07-26 14:00:00', 1),
(35, 'floreshans.neust@gmail.com', '::1', '2026-07-26 15:00:00', 1),
(36, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-07-26 18:00:00', 1),
(37, 'floreshans.neust@gmail.com', '::1', '2026-07-26 19:00:00', 1),
(38, 'floressktt11@gmail.com', '::1', '2026-07-26 19:00:00', 1),
(39, 'floressktt11@gmail.com', '::1', '2026-07-27 07:00:00', 1),
(40, 'floressktt11@gmail.com', '::1', '2026-07-31 02:00:00', 1),
(41, 'floreshans.neust@gmail.com', '::1', '2026-07-31 03:00:00', 1),
(42, 'floreshans.neust@gmail.com', '::1', '2026-07-31 17:00:00', 1),
(43, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-07-31 17:00:00', 1),
(44, 'floressktt11@gmail.com', '::1', '2026-07-31 18:00:00', 1),
(45, 'floressktt11@gmail.com', '::1', '2026-07-31 20:00:00', 2),
(47, 'floressktt11@gmail.com', '::1', '2026-08-01 02:00:00', 1),
(48, 'floreshans.neust@gmail.com', '::1', '2026-08-01 03:00:00', 1),
(49, 'floressktt11@gmail.com', '::1', '2026-08-01 03:00:00', 1),
(50, 'floressktt11@gmail.com', '::1', '2026-08-01 04:00:00', 1),
(51, 'floreshans.neust@gmail.com', '::1', '2026-08-01 07:00:00', 1),
(52, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-08-01 09:00:00', 1),
(53, 'floressktt11@gmail.com', '::1', '2026-08-01 09:00:00', 1),
(54, 'floreshans.neust@gmail.com', '::1', '2026-08-01 09:00:00', 1),
(55, 'floreshans.neust@gmail.com', '::1', '2026-08-01 22:00:00', 1),
(56, 'floressktt11@gmail.com', '::1', '2026-08-01 22:00:00', 1),
(57, 'floressktt11@gmail.com', '::1', '2026-08-02 00:00:00', 1),
(58, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-08-02 00:00:00', 3),
(61, 'floreshans.neust@gmail.com', '::1', '2026-08-02 00:00:00', 1),
(62, 'floreshans.neust@gmail.com', '::1', '2026-08-02 02:00:00', 2),
(63, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-08-02 02:00:00', 1),
(65, 'floressktt11@gmail.com', '::1', '2026-08-02 02:00:00', 1),
(66, 'floressktt11@gmail.com', '::1', '2026-08-02 08:00:00', 1),
(67, 'floressktt11@gmail.com', '::1', '2026-08-02 13:00:00', 4),
(68, 'floreshanslimuelle.neust@gmail.com', '::1', '2026-08-02 13:00:00', 1),
(69, 'floreshans.neust@gmail.com', '::1', '2026-08-02 13:00:00', 3),
(75, 'floressktt11@gmail.com', '::1', '2026-08-02 14:00:00', 2),
(76, 'floreshans.neust@gmail.com', '::1', '2026-08-02 14:00:00', 2),
(78, 'umalicedrick29@gmail.com', '::1', '2026-08-02 14:00:00', 1),
(80, 'floressktt11@gmail.com', '::1', '2026-08-05 06:00:00', 1),
(81, 'floreshans.neust@gmail.com', '::1', '2026-08-05 09:00:00', 1),
(82, 'floreshans.neust@gmail.com', '::1', '2026-08-06 14:00:00', 1),
(83, 'floreshans.neust@gmail.com', '::1', '2026-08-07 02:00:00', 1),
(84, 'floreshans.neust@gmail.com', '::1', '2026-08-07 04:00:00', 1),
(85, 'floreshans.neust@gmail.com', '::1', '2026-08-07 08:00:00', 2),
(87, 'floreshans.neust@gmail.com', '::1', '2026-08-07 16:00:00', 2),
(89, 'floressktt11@gmail.com', '::1', '2026-08-07 16:00:00', 1),
(90, 'floreshans.neust@gmail.com', '::1', '2026-08-07 18:00:00', 1),
(91, 'floressktt11@gmail.com', '::1', '2026-08-07 18:00:00', 1),
(92, 'floreshans.neust@gmail.com', '::1', '2026-08-08 03:00:00', 1),
(93, 'floressktt11@gmail.com', '::1', '2026-08-08 03:00:00', 1),
(94, 'floreshans.neust@gmail.com', '::1', '2026-08-08 05:00:00', 1),
(95, 'floreshans.neust@gmail.com', '::1', '2026-08-08 08:00:00', 2),
(97, 'floreshans.neust@gmail.com', '::1', '2026-08-08 10:00:00', 1),
(98, 'floressktt11@gmail.com', '::1', '2026-08-08 11:00:00', 1),
(99, 'floressktt11@gmail.com', '::1', '2026-08-08 12:00:00', 1),
(100, 'floreshans.neust@gmail.com', '::1', '2026-08-08 13:00:00', 1),
(101, 'floressktt11@gmail.com', '::1', '2026-08-08 14:00:00', 1),
(102, 'floreshans.neust@gmail.com', '::1', '2026-08-08 15:00:00', 1),
(103, 'floressktt11@gmail.com', '::1', '2026-08-08 16:00:00', 1),
(104, 'floressktt11@gmail.com', '::1', '2026-08-08 21:00:00', 1),
(105, 'floreshans.neust@gmail.com', '::1', '2026-08-08 22:00:00', 3),
(108, 'floressktt11@gmail.com', '::1', '2026-08-08 23:00:00', 1),
(109, 'floressktt11@gmail.com', '::1', '2026-08-09 02:00:00', 1);

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

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `report_id`, `announcement_id`, `type`, `title`, `content`, `is_read`, `send_to_all`, `created_at`) VALUES
(43, 3, NULL, NULL, 'Collection Schedule Update', 'Collection rescheduled to Saturday', 'Your purok\'s waste collection has been moved to Saturday at 6:00 AM due to the holiday.', 1, 0, '2026-07-26 08:43:32'),
(44, 3, NULL, NULL, 'New Announcement', 'New hazardous waste drop-off point', 'A new drop-off site is now available near the barangay center for batteries and electronics.', 1, 0, '2026-07-26 07:53:32'),
(45, 3, NULL, NULL, 'New Announcement', 'Clean-up drive reminder', 'Please join the riverbank clean-up this Sunday at 7:00 AM and bring reusable gloves.', 1, 0, '2026-07-25 08:53:32'),
(46, 3, NULL, NULL, 'Report Status Update', 'Your report has been verified', 'Your waste report #WR-001 has been verified by the barangay team.', 1, 0, '2026-07-26 06:53:32'),
(47, 3, NULL, NULL, 'Report Resolved', 'Your report has been resolved', 'Your waste report #WR-002 has been resolved. Thank you for your contribution!', 0, 0, '2026-07-26 08:23:32'),
(48, NULL, NULL, NULL, 'New Announcement', 'Barangay Clean-Up Drive this Saturday', 'Join us for a community clean-up drive this Saturday at 7:00 AM. Meet at the barangay hall.', 1, 1, '2026-07-24 08:53:32'),
(49, NULL, NULL, NULL, 'Collection Schedule Update', 'Holiday schedule changes', 'Collection schedule will be adjusted for the upcoming holidays. Please check the collection schedule page for details.', 1, 1, '2026-07-23 08:53:32'),
(50, 1, NULL, NULL, 'New Announcement', 'Monthly meeting reminder', 'Monthly barangay meeting scheduled for August 1, 2026 at 2:00 PM.', 0, 0, '2026-07-26 04:53:32'),
(51, 2, NULL, NULL, 'Report Status Update', 'New reports pending review', 'There are 5 new waste reports pending your review.', 1, 0, '2026-07-26 03:53:32'),
(56, 1, 34, NULL, 'report_rejected', 'Report Rejected', 'Your waste report has been rejected. Reason: No reason provided', 0, 0, '2026-08-02 08:45:42'),
(57, 1, 43, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingg phaseeeeee', 0, 0, '2026-08-02 19:28:52'),
(58, 2, 43, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingg phaseeeeee', 1, 0, '2026-08-02 19:28:52'),
(59, 17, 43, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingg phaseeeeee', 0, 0, '2026-08-02 19:28:52'),
(60, 18, 43, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingg phaseeeeee', 0, 0, '2026-08-02 19:28:52'),
(61, 3, 43, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-02 19:35:21'),
(62, 1, 44, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: description testttingg', 0, 0, '2026-08-02 20:07:25'),
(63, 2, 44, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: description testttingg', 1, 0, '2026-08-02 20:07:25'),
(64, 17, 44, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: description testttingg', 0, 0, '2026-08-02 20:07:25'),
(65, 18, 44, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: description testttingg', 0, 0, '2026-08-02 20:07:25'),
(66, 3, 44, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-02 20:09:52'),
(67, 1, 45, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingggggggg', 0, 0, '2026-08-02 20:12:38'),
(68, 2, 45, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingggggggg', 1, 0, '2026-08-02 20:12:38'),
(69, 17, 45, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingggggggg', 0, 0, '2026-08-02 20:12:38'),
(70, 18, 45, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: testingggggggg', 0, 0, '2026-08-02 20:12:38'),
(71, 1, 46, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Cedrick Umali: testinggggg', 0, 0, '2026-08-02 20:21:42'),
(72, 2, 46, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Cedrick Umali: testinggggg', 1, 0, '2026-08-02 20:21:42'),
(73, 17, 46, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Cedrick Umali: testinggggg', 0, 0, '2026-08-02 20:21:42'),
(74, 18, 46, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Cedrick Umali: testinggggg', 0, 0, '2026-08-02 20:21:42'),
(75, 19, 46, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-07 08:57:26'),
(76, 19, 46, NULL, 'report_update', 'Report Resolved', 'Your report has been resolved. Thank you for reporting!', 0, 0, '2026-08-07 08:57:47'),
(77, 3, 45, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-07 09:03:34'),
(78, 1, 47, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: tessstinnggg', 0, 0, '2026-08-07 22:52:35'),
(79, 2, 47, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: tessstinnggg', 1, 0, '2026-08-07 22:52:35'),
(80, 17, 47, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: tessstinnggg', 0, 0, '2026-08-07 22:52:35'),
(81, 18, 47, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: tessstinnggg', 0, 0, '2026-08-07 22:52:35'),
(82, 3, 47, NULL, 'report_update', 'Report Verified', 'Your report has been verified by Secretary Rose.', 0, 0, '2026-08-07 22:53:15'),
(83, 3, 47, NULL, 'report_update', 'Report In Progress', 'Your report is now in progress and being addressed.', 0, 0, '2026-08-07 23:37:47'),
(84, 3, 47, NULL, 'report_rejected', 'Report Rejected', 'Your waste report has been rejected. Reason: No reason provided', 0, 0, '2026-08-08 09:40:27'),
(85, 3, 45, NULL, 'report_update', 'Report In Progress', 'Your report is now in progress and being addressed.', 0, 0, '2026-08-08 09:41:23'),
(86, 3, 45, NULL, 'report_update', 'Report Resolved', 'Your report has been resolved. Thank you for reporting!', 0, 0, '2026-08-08 09:41:32'),
(87, 1, 48, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: test test test', 0, 0, '2026-08-08 09:59:25'),
(88, 2, 48, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: test test test', 1, 0, '2026-08-08 09:59:25'),
(89, 17, 48, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: test test test', 0, 0, '2026-08-08 09:59:25'),
(90, 18, 48, NULL, 'report_update', 'New Report Submitted', 'New report submitted by Hans Flores: test test test', 0, 0, '2026-08-08 09:59:25');

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

--
-- Dumping data for table `purok_boundaries`
--

INSERT INTO `purok_boundaries` (`boundary_id`, `purok_id`, `polygon_geometry`, `updated_by`, `updated_at`) VALUES
(1, 1, 0x000000000103000000010000000d000000f1811dff05335e4001fc53aa44212f4044be4ba94b335e40d670917bba222f40992ec4ea8f335e4041800c1d3b202f4062484e266e335e402a1c412ac51e2f4032022a1c41335e40ac8f87bebb1d2f40fd4ffeee1d335e406e313f37341d2f40a777f17edc325e40fdbe7ff3e21c2f4097715303cd325e406765fb90b71c2f40978bf84ecc325e4061c1fd80071e2f40cd57c9c7ee325e403579ca6aba1e2f40cd57c9c7ee325e40bbd1c77c40202f40ca6dfb1ef5325e408f899466f3202f40f1811dff05335e4001fc53aa44212f40, 2, '2026-08-08 17:18:01'),
(2, 2, 0x0000000001030000000100000006000000742502d53f345e400745f30016212f4091f2936a9f345e40a79201a08a232f40d39ffd4811355e401024ef1cca202f40f65ca626c1345e405439ed29391f2f40ef01ba2f67345e403bc269c18b1e2f40742502d53f345e400745f30016212f40, 2, '2026-08-08 17:16:42'),
(3, 3, 0x00000000010300000001000000060000008bfcfa2136345e4030b95164ad192f4097ab1f9be4335e40e753c72aa5172f408ee89e758d335e4045f5d6c056192f40a226fa7c94335e40e3c4573b8a1b2f4033a9a10dc0335e408f6fef1af41d2f408bfcfa2136345e4030b95164ad192f40, 2, '2026-08-08 09:48:40'),
(4, 4, 0x000000000103000000010000000c0000008066101fd8335e408f6fef1af41d2f409d67ec4b36345e4046b6f3fdd4202f4016dc0f7860345e401a6cea3c2a1e2f40075dc2a1b7345e4008aef204c21e2f40bebf417bf5345e40b4006dab591f2f40fd4cbd6e11355e40996379573d202f4063b7cf2a33355e40a12de7525c1d2f4084d89942e7345e4019575c1c951b2f40a7c98cb795345e40df89592f861a2f4069a7e67283345e40e2afc91af5182f402e55698b6b345e402a00c63368182f408066101fd8335e408f6fef1af41d2f40, 2, '2026-08-08 09:48:56'),
(5, 5, 0x000000000103000000010000000a000000d8d825aab7325e40a6d1e4620c1c2f40ec1681b1be325e4022365838491b2f408e3ba583f5325e409cdd5a26c3192f4059dc7f643a335e402c7e5358a9182f40fa980f0874335e408466d7bd15192f4038876bb587335e409a5fcd0182192f40361fd7868a335e40791edc9db51b2f40c4d155babb335e401a6cea3c2a1e2f401cee23b726335e404a4563edef1c2f40d8d825aab7325e40a6d1e4620c1c2f40, 2, '2026-08-08 17:16:04');

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

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `resident_id`, `description`, `latitude`, `longitude`, `location_verified`, `submission_date`, `reviewed_by`, `created_at`, `updated_at`, `category_id`, `quantity_id`, `condition_id`, `status_id`, `purok_id`, `location`, `rejected_reason`, `support_count`) VALUES
(34, 1, 'Hazardous waste dumped near the creek', 15.55480000, 120.80450000, 0, '2026-07-23 13:27:29', 2, '2026-07-26 05:27:29', '2026-08-02 08:45:42', 6, 2, 1, 5, 2, NULL, NULL, 0),
(35, 1, 'Construction waste blocking roadway', 15.56120000, 120.80680000, 0, '2026-07-25 13:27:29', NULL, '2026-07-26 05:27:29', '2026-07-26 13:27:29', 4, 3, 6, 3, NULL, NULL, NULL, 0),
(43, 3, 'testingg phaseeeeee', 15.56003000, 120.80475900, 1, '2026-08-02 19:28:52', 2, '2026-08-02 11:28:52', '2026-08-02 19:35:21', 1, 3, 2, 2, 1, '', NULL, 0),
(44, 3, 'description testttingg', 15.56139800, 120.80701400, 1, '2026-08-02 20:07:25', 2, '2026-08-02 12:07:25', '2026-08-02 20:09:52', 8, 1, 2, 2, 1, '', NULL, 0),
(45, 3, 'testingggggggg', 15.56152000, 120.80696900, 1, '2026-08-02 20:12:38', 2, '2026-08-02 12:12:38', '2026-08-08 09:41:32', 7, 3, 2, 4, 1, '', NULL, 1),
(46, 19, 'testinggggg', 15.56185100, 120.80662400, 1, '2026-08-02 20:21:42', 2, '2026-08-02 12:21:42', '2026-08-07 08:57:47', 2, 3, 4, 4, 1, '', NULL, 0),
(47, 3, 'tessstinnggg', 15.56035300, 120.82494400, 1, '2026-08-07 22:52:35', 2, '2026-08-07 14:52:35', '2026-08-08 09:40:27', 1, 2, 6, 5, 4, '', NULL, 0),
(48, 3, 'test test test', 15.55713300, 120.81367700, 1, '2026-08-08 09:59:25', NULL, '2026-08-08 01:59:25', '2026-08-08 09:59:25', 6, 3, 3, 1, 4, '', NULL, 0);

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

--
-- Dumping data for table `report_flags`
--

INSERT INTO `report_flags` (`id`, `report_id`, `flag_reason`, `flagged_by`, `flagged_at`, `reviewed_by`, `reviewed_at`) VALUES
(8, 34, 'Rejected by admin', 2, '2026-08-02 08:45:42', NULL, NULL),
(9, 47, 'Rejected by admin', 2, '2026-08-08 09:40:27', NULL, NULL);

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
(1, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', 'Rosa Medina', 'Barangay Secretary', '', 2, '2026-08-02 19:56:49'),
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

--
-- Dumping data for table `report_photos`
--

INSERT INTO `report_photos` (`photo_id`, `report_id`, `photo_path`, `is_primary`, `uploaded_at`) VALUES
(2, 43, '6a6f29f41892f_Screenshot 2025-06-22 181737.png', 1, '2026-08-02 19:28:52'),
(3, 44, '6a6f32fddca0e_Screenshot 2025-06-18 225708.png', 1, '2026-08-02 20:07:25'),
(4, 45, '6a6f343691b11_Screenshot 2025-06-22 181737.png', 1, '2026-08-02 20:12:38'),
(5, 46, '6a6f36567812f_Screenshot 2025-08-18 220556.png', 1, '2026-08-02 20:21:42'),
(6, 47, '6a75f133acbf0_Screenshot 2025-06-11 000452.png', 1, '2026-08-07 22:52:35'),
(7, 48, '6a768d7d2ed66_Screenshot 2025-06-24 114030.png', 1, '2026-08-08 09:59:25');

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
(4, 34, 'Resolved', 'rejected', 'Rejected by admin', 2, '2026-08-02 08:45:42'),
(5, 47, 'In Progress', 'rejected', 'Rejected by admin', 2, '2026-08-08 09:40:27');

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
(6, 2, 'analytics_report_2026-08-07.csv', '', 'csv', '{\"date_from\":\"2026-07-30\",\"date_to\":\"2026-08-07\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 4, '2026-08-07 10:33:52');

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

--
-- Dumping data for table `report_supports`
--

INSERT INTO `report_supports` (`support_id`, `report_id`, `user_id`, `supported_at`) VALUES
(1, 45, 19, '2026-08-02 20:18:34');

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
(323, 19, 'umalicedrick29@gmail.com', '441724', 'login_2fa', '2026-08-02 20:26:39', 1, 0, '2026-08-02 12:16:39'),
(334, 16, 'testingotp@gmail.com', '064512', 'login_2fa', '2026-08-05 12:48:25', 0, 0, '2026-08-05 04:38:25'),
(377, 2, 'floreshans.neust@gmail.com', '980669', 'login_2fa', '2026-08-09 05:02:32', 1, 0, '2026-08-08 20:52:32'),
(379, 3, 'floressktt11@gmail.com', '517088', 'login_2fa', '2026-08-09 09:05:44', 1, 0, '2026-08-09 00:55:44');

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
(2, 'Secretary Rose', NULL, NULL, NULL, 'resident', 'Barangay Hall', '09123456788', 'floreshans.neust@gmail.com', '$2y$10$NFKPGxb7Hord13zMb9YLzeQOC8Te8geDMwW21XM48pMsuYDx49qSy', NULL, NULL, 1, 2, 1, 'active', NULL, '2026-04-01 00:28:49', '2026-08-05 15:30:50', NULL, NULL, 0, NULL, NULL),
(3, 'Hans Flores', NULL, NULL, NULL, 'resident', 'brgy.testing.testing', '09951281511', 'floressktt11@gmail.com', '$2y$10$.jggu7XHDkz65Y2Q5L0mOOdnhB9MFl3TjKeSFVfSLrMERH.de9AAy', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-04-01 01:05:23', '2026-07-25 13:54:04', NULL, NULL, 0, NULL, NULL),
(15, 'asdasdadad', NULL, NULL, NULL, 'resident', '232323232323', '09951281511', 'floererererer@gmail.com', '$2y$10$fL/0SLQG2zLnUEniuGsTU.ulSh4yLbmpwPMkWFLIbqc2OU1xF1Niq', '/uploads/ids/front_6a6252ecf1d05.jpg', '/uploads/ids/back_6a6252ed00579.jpg', 3, 6, 1, 'active', NULL, '2026-07-24 01:44:13', '2026-08-09 05:54:43', NULL, NULL, 0, NULL, NULL),
(16, 'test email otp', NULL, NULL, NULL, 'resident', 'awwsdasdad', '09951281511', 'testingotp@gmail.com', '$2y$10$NlxWm4KHBTA2PPazo0MN7ehPrv3RmAtUukEAO8QavMGJAmG.0znou', '/uploads/ids/front_6a6258475b015.jpg', '/uploads/ids/back_6a6258475be3f.jpg', 3, 6, 1, 'active', NULL, '2026-07-24 02:07:03', '2026-08-08 01:10:08', NULL, NULL, 0, NULL, NULL),
(17, 'Supervisor User', NULL, NULL, NULL, 'resident', '', '', 'supervisor@dulongbayan.ph', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', NULL, NULL, 2, 3, NULL, 'active', NULL, '2026-07-25 23:52:14', '2026-08-08 01:09:10', NULL, NULL, 0, NULL, NULL),
(18, 'Hans Limuelle Flores', NULL, NULL, 'hansflores', 'resident', 'Barangay Dulong Bayan', '09171234567', 'floreshanslimuelle.neust@gmail.com', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', NULL, NULL, 2, 3, 1, 'active', NULL, '2026-07-26 13:35:01', '2026-07-26 13:47:54', NULL, NULL, 0, NULL, NULL),
(19, 'Cedrick Umali', NULL, NULL, 'umalicedrick', 'resident', '', '09664185246', 'umalicedrick29@gmail.com', '$2y$10$/6ZSX1XKD5fMpEwneahyIOU8IfbZxxWwoFfKt48t8Z0Z4PmY3Pg3O', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-08-02 20:15:51', '2026-08-08 11:03:30', NULL, NULL, 0, NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `announcement_visibilities`
--
ALTER TABLE `announcement_visibilities`
  MODIFY `visibility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2026;

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
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `collection_schedule_puroks`
--
ALTER TABLE `collection_schedule_puroks`
  MODIFY `schedule_purok_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `email_otp_rate_limits`
--
ALTER TABLE `email_otp_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `estimated_quantities`
--
ALTER TABLE `estimated_quantities`
  MODIFY `quantity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `heatmap_settings`
--
ALTER TABLE `heatmap_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `map_landmarks`
--
ALTER TABLE `map_landmarks`
  MODIFY `landmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
  MODIFY `notification_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `report_flags`
--
ALTER TABLE `report_flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `report_generation_settings`
--
ALTER TABLE `report_generation_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_photos`
--
ALTER TABLE `report_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_summaries`
--
ALTER TABLE `report_summaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `report_supports`
--
ALTER TABLE `report_supports`
  MODIFY `support_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
