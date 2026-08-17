-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2026 at 06:39 PM
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
(2025, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 08:56:08', NULL, NULL, NULL, NULL),
(2026, 3, 'Profile Updated', 'Profile', 'Updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:19:57', NULL, NULL, NULL, NULL),
(2027, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:29:15', NULL, NULL, NULL, NULL),
(2028, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:30:43', NULL, NULL, NULL, NULL),
(2029, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:30:55', NULL, NULL, NULL, NULL),
(2030, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:33:26', NULL, NULL, NULL, NULL),
(2031, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:33:39', NULL, NULL, NULL, NULL),
(2032, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:33:39', NULL, NULL, NULL, NULL),
(2033, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:33:58', NULL, NULL, NULL, NULL),
(2034, 2, 'Report Verified', 'Report ID 48', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:34:05', NULL, NULL, NULL, NULL),
(2035, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:34:06', NULL, NULL, NULL, NULL),
(2036, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:34:44', NULL, NULL, NULL, NULL),
(2037, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:35:24', NULL, NULL, NULL, NULL),
(2038, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:36:46', NULL, NULL, NULL, NULL),
(2039, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:37:04', NULL, NULL, NULL, NULL),
(2040, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:37:34', NULL, NULL, NULL, NULL),
(2041, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:37:37', NULL, NULL, NULL, NULL),
(2042, 2, 'Delete Schedule', 'Schedule ID 1', 'Deleted schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:37:53', NULL, NULL, NULL, NULL),
(2043, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:37:53', NULL, NULL, NULL, NULL),
(2044, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:37:56', NULL, NULL, NULL, NULL),
(2045, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:37:58', NULL, NULL, NULL, NULL),
(2046, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:38:01', NULL, NULL, NULL, NULL),
(2047, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'success', '2026-08-09 09:39:15', NULL, NULL, NULL, NULL),
(2048, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-09 21:16:05', NULL, NULL, NULL, NULL),
(2049, 20, 'User Registered', 'User', 'Registered with phone number 09951281511', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-09 22:40:36', NULL, NULL, NULL, NULL),
(2050, 20, 'Login successful', 'User', 'Direct login (phone-only user)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-09 22:43:48', NULL, NULL, NULL, NULL),
(2051, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-09 22:44:31', NULL, NULL, NULL, NULL),
(2052, NULL, 'Login failed', 'User', 'Invalid credentials for fhanstestingphonesmsw', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-09 23:03:03', NULL, NULL, NULL, NULL),
(2053, NULL, 'Login failed', 'User', 'Invalid credentials for 09951281511', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-09 23:03:39', NULL, NULL, NULL, NULL),
(2054, 3, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-09 23:04:02', NULL, NULL, NULL, NULL),
(2055, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-09 23:04:30', NULL, NULL, NULL, NULL),
(2056, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-09 23:04:33', NULL, NULL, NULL, NULL),
(2057, 20, 'Login successful', 'User', 'Direct login (phone-only user)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 00:32:48', NULL, NULL, NULL, NULL),
(2058, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 00:32:57', NULL, NULL, NULL, NULL),
(2059, 20, 'Login successful', 'User', 'Direct login (phone-only user)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 00:33:01', NULL, NULL, NULL, NULL),
(2060, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 00:33:04', NULL, NULL, NULL, NULL),
(2061, NULL, 'Login failed', 'User', 'Invalid credentials for fhanstestingphonesms s', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-10 00:33:11', NULL, NULL, NULL, NULL),
(2062, NULL, 'Login failed', 'User', 'Invalid credentials for fhanstestingphonesms s', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-10 00:35:51', NULL, NULL, NULL, NULL),
(2063, NULL, 'Login failed', 'User', 'Invalid credentials for fhanstestingphonesms s', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-10 00:36:46', NULL, NULL, NULL, NULL),
(2064, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-10 02:06:21', NULL, NULL, NULL, NULL),
(2065, 2, 'Login partial success', 'User', 'OTP Email sent', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 02:06:43', NULL, NULL, NULL, NULL),
(2066, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 02:06:54', NULL, NULL, NULL, NULL),
(2067, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 02:06:54', NULL, NULL, NULL, NULL),
(2068, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 02:08:00', NULL, NULL, NULL, NULL),
(2069, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 02:08:43', NULL, NULL, NULL, NULL),
(2070, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-10 07:24:10', NULL, NULL, NULL, NULL),
(2071, 20, 'Login successful', 'User', 'Direct login (phone-only user)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 07:24:39', NULL, NULL, NULL, NULL),
(2072, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 07:24:56', NULL, NULL, NULL, NULL),
(2073, 20, 'Login successful', 'User', 'Direct login (phone-only user)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 07:39:49', NULL, NULL, NULL, NULL),
(2074, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 07:39:56', NULL, NULL, NULL, NULL),
(2075, 20, 'Login partial success', 'User', 'OTP sent to phone', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 07:43:17', NULL, NULL, NULL, NULL),
(2076, 20, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 07:43:49', NULL, NULL, NULL, NULL),
(2077, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 07:44:01', NULL, NULL, NULL, NULL),
(2078, 20, 'Login partial success', 'User', 'OTP sent to phone', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 08:41:08', NULL, NULL, NULL, NULL),
(2079, 20, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 08:41:25', NULL, NULL, NULL, NULL),
(2080, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 08:42:02', NULL, NULL, NULL, NULL),
(2081, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:29:54', NULL, NULL, NULL, NULL),
(2082, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:30:11', NULL, NULL, NULL, NULL),
(2083, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:30:11', NULL, NULL, NULL, NULL),
(2084, 2, 'View Report', 'Report ID 50', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:30:50', NULL, NULL, NULL, NULL),
(2085, 2, 'Report Verified', 'Report ID 50', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:31:30', NULL, NULL, NULL, NULL),
(2086, 2, 'View Report', 'Report ID 50', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:31:31', NULL, NULL, NULL, NULL),
(2087, 2, 'Report In Progress', 'Report ID 50', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:32:49', NULL, NULL, NULL, NULL),
(2088, 2, 'View Report', 'Report ID 50', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:32:50', NULL, NULL, NULL, NULL),
(2089, 2, 'Report Resolved', 'Report ID 50', 'Resolved report. Remark: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:33:38', NULL, NULL, NULL, NULL),
(2090, 2, 'View Report', 'Report ID 50', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:33:38', NULL, NULL, NULL, NULL),
(2091, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:34:13', NULL, NULL, NULL, NULL),
(2092, 2, 'Report Verified', 'Report ID 52', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:34:26', NULL, NULL, NULL, NULL),
(2093, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:34:27', NULL, NULL, NULL, NULL),
(2094, 2, 'Report In Progress', 'Report ID 52', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:34:33', NULL, NULL, NULL, NULL),
(2095, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:34:33', NULL, NULL, NULL, NULL),
(2096, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:38:21', NULL, NULL, NULL, NULL),
(2097, 2, 'Report Rejected', 'Report ID 52', 'Rejected report. Reason: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:40:28', NULL, NULL, NULL, NULL),
(2098, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:40:28', NULL, NULL, NULL, NULL),
(2099, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:44:14', NULL, NULL, NULL, NULL),
(2100, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:56:03', NULL, NULL, NULL, NULL),
(2101, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:57:15', NULL, NULL, NULL, NULL),
(2102, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 11:58:02', NULL, NULL, NULL, NULL),
(2103, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:00:36', NULL, NULL, NULL, NULL),
(2104, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:00:58', NULL, NULL, NULL, NULL),
(2105, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:03:08', NULL, NULL, NULL, NULL),
(2106, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:10:47', NULL, NULL, NULL, NULL),
(2107, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:11:21', NULL, NULL, NULL, NULL),
(2108, 20, 'Login partial success', 'User', 'OTP sent to phone', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:12:56', NULL, NULL, NULL, NULL),
(2109, 20, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:13:17', NULL, NULL, NULL, NULL),
(2110, 2, 'Profile Updated', 'Profile', 'Admin updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:21:40', NULL, NULL, NULL, NULL),
(2111, 2, 'Profile Updated', 'Profile', 'Admin updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:27:53', NULL, NULL, NULL, NULL),
(2112, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:28:25', NULL, NULL, NULL, NULL),
(2113, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:28:45', NULL, NULL, NULL, NULL),
(2114, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:29:04', NULL, NULL, NULL, NULL),
(2115, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:32:02', NULL, NULL, NULL, NULL),
(2116, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:32:08', NULL, NULL, NULL, NULL),
(2117, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:32:43', NULL, NULL, NULL, NULL),
(2118, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:32:45', NULL, NULL, NULL, NULL),
(2119, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:35:49', NULL, NULL, NULL, NULL),
(2120, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:36:22', NULL, NULL, NULL, NULL),
(2121, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:36:45', NULL, NULL, NULL, NULL),
(2122, 20, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:37:24', NULL, NULL, NULL, NULL),
(2123, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:37:41', NULL, NULL, NULL, NULL),
(2124, 2, 'View Report', 'Report ID 53', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:43:37', NULL, NULL, NULL, NULL),
(2125, 2, 'Report Verified', 'Report ID 53', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:43:54', NULL, NULL, NULL, NULL),
(2126, 2, 'View Report', 'Report ID 53', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:43:55', NULL, NULL, NULL, NULL),
(2127, 2, 'Report In Progress', 'Report ID 53', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:44:07', NULL, NULL, NULL, NULL),
(2128, 2, 'View Report', 'Report ID 53', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:44:07', NULL, NULL, NULL, NULL),
(2129, 2, 'Report Resolved', 'Report ID 53', 'Resolved report. Remark: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:44:18', NULL, NULL, NULL, NULL),
(2130, 2, 'View Report', 'Report ID 53', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:44:19', NULL, NULL, NULL, NULL),
(2131, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:51:12', NULL, NULL, NULL, NULL),
(2132, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:56:25', NULL, NULL, NULL, NULL),
(2133, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:57:23', NULL, NULL, NULL, NULL),
(2134, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:58:49', NULL, NULL, NULL, NULL),
(2135, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 12:59:21', NULL, NULL, NULL, NULL),
(2136, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:00:38', NULL, NULL, NULL, NULL),
(2137, 2, 'Report Generated', 'Report Summary', 'Format: pdf', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:01:08', NULL, NULL, NULL, NULL),
(2138, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:02:22', NULL, NULL, NULL, NULL),
(2139, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:09:00', NULL, NULL, NULL, NULL),
(2140, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:09:50', NULL, NULL, NULL, NULL),
(2141, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:15:05', NULL, NULL, NULL, NULL),
(2142, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:15:17', NULL, NULL, NULL, NULL),
(2143, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:16:00', NULL, NULL, NULL, NULL),
(2144, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:16:25', NULL, NULL, NULL, NULL),
(2145, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:18:07', NULL, NULL, NULL, NULL),
(2146, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:19:47', NULL, NULL, NULL, NULL),
(2147, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:21:50', NULL, NULL, NULL, NULL),
(2148, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:21:51', NULL, NULL, NULL, NULL),
(2149, 2, 'Report Generated', 'Report Summary', 'Format: csv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:22:49', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(2150, 2, 'Report Generated', 'Report Summary', 'Format: csv', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:24:32', NULL, NULL, NULL, NULL),
(2151, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:25:43', NULL, NULL, NULL, NULL),
(2152, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:25:46', NULL, NULL, NULL, NULL),
(2153, 2, 'Add Landmark', 'Settings', 'Added landmark: brgy hall', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:32:14', NULL, NULL, NULL, NULL),
(2154, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:32:44', NULL, NULL, NULL, NULL),
(2155, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:33:02', NULL, NULL, NULL, NULL),
(2156, 3, 'Profile Updated', 'Profile', 'Updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:35:13', NULL, NULL, NULL, NULL),
(2157, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:37:26', NULL, NULL, NULL, NULL),
(2158, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:38:44', NULL, NULL, NULL, NULL),
(2159, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:39:17', NULL, NULL, NULL, NULL),
(2160, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-10 13:39:22', NULL, NULL, NULL, NULL),
(2161, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-12 02:54:03', NULL, NULL, NULL, NULL),
(2162, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-12 02:54:18', NULL, NULL, NULL, NULL),
(2163, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-12 02:55:21', NULL, NULL, NULL, NULL),
(2164, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-12 02:55:35', NULL, NULL, NULL, NULL),
(2165, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-12 02:55:35', NULL, NULL, NULL, NULL),
(2166, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-12 02:56:30', NULL, NULL, NULL, NULL),
(2167, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-12 02:56:51', NULL, NULL, NULL, NULL),
(2168, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-14 10:14:16', NULL, NULL, NULL, NULL),
(2169, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:14:48', NULL, NULL, NULL, NULL),
(2170, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:14:59', NULL, NULL, NULL, NULL),
(2171, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:15:06', NULL, NULL, NULL, NULL),
(2172, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:15:06', NULL, NULL, NULL, NULL),
(2173, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:15:17', NULL, NULL, NULL, NULL),
(2174, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:42:06', NULL, NULL, NULL, NULL),
(2175, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:43:32', NULL, NULL, NULL, NULL),
(2176, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:44:20', NULL, NULL, NULL, NULL),
(2177, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 10:44:46', NULL, NULL, NULL, NULL),
(2178, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:44:55', NULL, NULL, NULL, NULL),
(2179, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:45:06', NULL, NULL, NULL, NULL),
(2180, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:45:16', NULL, NULL, NULL, NULL),
(2181, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:45:17', NULL, NULL, NULL, NULL),
(2182, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:45:21', NULL, NULL, NULL, NULL),
(2183, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:45:41', NULL, NULL, NULL, NULL),
(2184, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:45:48', NULL, NULL, NULL, NULL),
(2185, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:46:02', NULL, NULL, NULL, NULL),
(2186, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:46:06', NULL, NULL, NULL, NULL),
(2187, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:46:40', NULL, NULL, NULL, NULL),
(2188, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:46:47', NULL, NULL, NULL, NULL),
(2189, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:46:49', NULL, NULL, NULL, NULL),
(2190, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 10:48:15', NULL, NULL, NULL, NULL),
(2191, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 10:55:50', NULL, NULL, NULL, NULL),
(2192, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:09:29', NULL, NULL, NULL, NULL),
(2193, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:11:18', NULL, NULL, NULL, NULL),
(2194, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:13:33', NULL, NULL, NULL, NULL),
(2195, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-14 11:15:50', NULL, NULL, NULL, NULL),
(2196, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:16:51', NULL, NULL, NULL, NULL),
(2197, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 11:17:34', NULL, NULL, NULL, NULL),
(2198, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:17:47', NULL, NULL, NULL, NULL),
(2199, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:18:09', NULL, NULL, NULL, NULL),
(2200, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:19:47', NULL, NULL, NULL, NULL),
(2201, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:19:54', NULL, NULL, NULL, NULL),
(2202, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:19:59', NULL, NULL, NULL, NULL),
(2203, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:20:09', NULL, NULL, NULL, NULL),
(2204, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:20:21', NULL, NULL, NULL, NULL),
(2205, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:20:27', NULL, NULL, NULL, NULL),
(2206, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:20:30', NULL, NULL, NULL, NULL),
(2207, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:20:46', NULL, NULL, NULL, NULL),
(2208, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:20:50', NULL, NULL, NULL, NULL),
(2209, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:20:55', NULL, NULL, NULL, NULL),
(2210, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:21:01', NULL, NULL, NULL, NULL),
(2211, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:21:04', NULL, NULL, NULL, NULL),
(2212, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:21:11', NULL, NULL, NULL, NULL),
(2213, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:21:20', NULL, NULL, NULL, NULL),
(2214, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:22:14', NULL, NULL, NULL, NULL),
(2215, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:22:21', NULL, NULL, NULL, NULL),
(2216, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:22:29', NULL, NULL, NULL, NULL),
(2217, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:22:36', NULL, NULL, NULL, NULL),
(2218, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:22:44', NULL, NULL, NULL, NULL),
(2219, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:22:49', NULL, NULL, NULL, NULL),
(2220, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:22:55', NULL, NULL, NULL, NULL),
(2221, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:23:05', NULL, NULL, NULL, NULL),
(2222, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:23:22', NULL, NULL, NULL, NULL),
(2223, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 11:23:57', NULL, NULL, NULL, NULL),
(2224, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:24:33', NULL, NULL, NULL, NULL),
(2225, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:24:38', NULL, NULL, NULL, NULL),
(2226, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:26:30', NULL, NULL, NULL, NULL),
(2227, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:27:36', NULL, NULL, NULL, NULL),
(2228, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:28:29', NULL, NULL, NULL, NULL),
(2229, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:28:59', NULL, NULL, NULL, NULL),
(2230, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:29:27', NULL, NULL, NULL, NULL),
(2231, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:29:38', NULL, NULL, NULL, NULL),
(2232, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:30:20', NULL, NULL, NULL, NULL),
(2233, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:30:32', NULL, NULL, NULL, NULL),
(2234, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:30:40', NULL, NULL, NULL, NULL),
(2235, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 11:34:27', NULL, NULL, NULL, NULL),
(2236, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:54:20', NULL, NULL, NULL, NULL),
(2237, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:54:22', NULL, NULL, NULL, NULL),
(2238, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:54:25', NULL, NULL, NULL, NULL),
(2239, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:54:27', NULL, NULL, NULL, NULL),
(2240, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:54:52', NULL, NULL, NULL, NULL),
(2241, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:54:59', NULL, NULL, NULL, NULL),
(2242, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:55:03', NULL, NULL, NULL, NULL),
(2243, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:55:05', NULL, NULL, NULL, NULL),
(2244, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:55:09', NULL, NULL, NULL, NULL),
(2245, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:55:20', NULL, NULL, NULL, NULL),
(2246, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:55:24', NULL, NULL, NULL, NULL),
(2247, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:55:25', NULL, NULL, NULL, NULL),
(2248, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:58:22', NULL, NULL, NULL, NULL),
(2249, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:58:26', NULL, NULL, NULL, NULL),
(2250, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:58:27', NULL, NULL, NULL, NULL),
(2251, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:58:32', NULL, NULL, NULL, NULL),
(2252, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:58:33', NULL, NULL, NULL, NULL),
(2253, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 11:58:51', NULL, NULL, NULL, NULL),
(2254, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:07:35', NULL, NULL, NULL, NULL),
(2255, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:28:04', NULL, NULL, NULL, NULL),
(2256, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:28:23', NULL, NULL, NULL, NULL),
(2257, 2, 'Export Reports', 'Reports', 'Admin exported waste reports to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:43:13', NULL, NULL, NULL, NULL),
(2258, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:43:24', NULL, NULL, NULL, NULL),
(2259, 2, 'View Report', 'Report ID 53', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:43:34', NULL, NULL, NULL, NULL),
(2260, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:43:42', NULL, NULL, NULL, NULL),
(2261, 2, 'Account Suspended', 'User Management', 'Suspended user ID 20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:51:24', NULL, NULL, NULL, NULL),
(2262, 2, 'Account Reactivated', 'User Management', 'Reactivated user ID 20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:51:31', NULL, NULL, NULL, NULL),
(2263, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 12:56:20', NULL, NULL, NULL, NULL),
(2264, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:02:15', NULL, NULL, NULL, NULL),
(2265, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:05:49', NULL, NULL, NULL, NULL),
(2266, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:19:23', NULL, NULL, NULL, NULL),
(2267, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:24:52', NULL, NULL, NULL, NULL),
(2268, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:25:04', NULL, NULL, NULL, NULL),
(2269, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:29:24', NULL, NULL, NULL, NULL),
(2270, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:29:45', NULL, NULL, NULL, NULL),
(2271, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:14', NULL, NULL, NULL, NULL),
(2272, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:21', NULL, NULL, NULL, NULL),
(2273, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:24', NULL, NULL, NULL, NULL),
(2274, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:31', NULL, NULL, NULL, NULL),
(2275, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:33', NULL, NULL, NULL, NULL),
(2276, 2, 'View Report', 'Report ID 53', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:43', NULL, NULL, NULL, NULL),
(2277, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:46', NULL, NULL, NULL, NULL),
(2278, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:50', NULL, NULL, NULL, NULL),
(2279, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:53', NULL, NULL, NULL, NULL),
(2280, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:30:55', NULL, NULL, NULL, NULL),
(2281, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:35:30', NULL, NULL, NULL, NULL),
(2282, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:35:32', NULL, NULL, NULL, NULL),
(2283, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:35:38', NULL, NULL, NULL, NULL),
(2284, 2, 'View Report', 'Report ID 50', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:35:43', NULL, NULL, NULL, NULL),
(2285, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 13:35:50', NULL, NULL, NULL, NULL),
(2286, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-14 14:12:31', NULL, NULL, NULL, NULL),
(2287, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:05', NULL, NULL, NULL, NULL),
(2288, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:14', NULL, NULL, NULL, NULL),
(2289, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:14', NULL, NULL, NULL, NULL),
(2290, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:16', NULL, NULL, NULL, NULL),
(2291, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:17', NULL, NULL, NULL, NULL),
(2292, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:19', NULL, NULL, NULL, NULL),
(2293, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:19', NULL, NULL, NULL, NULL),
(2294, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:21', NULL, NULL, NULL, NULL),
(2295, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:26', NULL, NULL, NULL, NULL),
(2296, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:25:30', NULL, NULL, NULL, NULL),
(2297, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:26:23', NULL, NULL, NULL, NULL),
(2298, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:26:25', NULL, NULL, NULL, NULL),
(2299, 2, 'Delete Landmark', 'Settings', 'Deleted landmark ID 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:28:18', NULL, NULL, NULL, NULL),
(2300, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:50:14', NULL, NULL, NULL, NULL),
(2301, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:50:16', NULL, NULL, NULL, NULL),
(2302, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:50:19', NULL, NULL, NULL, NULL),
(2303, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:52:07', NULL, NULL, NULL, NULL),
(2304, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:52:09', NULL, NULL, NULL, NULL),
(2305, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:52:11', NULL, NULL, NULL, NULL),
(2306, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:52:14', NULL, NULL, NULL, NULL),
(2307, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:58:37', NULL, NULL, NULL, NULL),
(2308, 2, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-14 18:58:59', NULL, NULL, NULL, NULL),
(2309, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:59:12', NULL, NULL, NULL, NULL),
(2310, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:59:12', NULL, NULL, NULL, NULL),
(2311, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:59:21', NULL, NULL, NULL, NULL),
(2312, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:59:22', NULL, NULL, NULL, NULL),
(2313, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:59:29', NULL, NULL, NULL, NULL),
(2314, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:59:31', NULL, NULL, NULL, NULL),
(2315, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 18:59:41', NULL, NULL, NULL, NULL),
(2316, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 19:04:11', NULL, NULL, NULL, NULL),
(2317, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 19:04:25', NULL, NULL, NULL, NULL),
(2318, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:04:42', NULL, NULL, NULL, NULL),
(2319, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:05:06', NULL, NULL, NULL, NULL),
(2320, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:05:06', NULL, NULL, NULL, NULL),
(2321, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:05:07', NULL, NULL, NULL, NULL),
(2322, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:05:09', NULL, NULL, NULL, NULL),
(2323, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:05:33', NULL, NULL, NULL, NULL),
(2324, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:07:45', NULL, NULL, NULL, NULL),
(2325, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:07:52', NULL, NULL, NULL, NULL),
(2326, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:07:58', NULL, NULL, NULL, NULL),
(2327, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:12:10', NULL, NULL, NULL, NULL),
(2328, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:12:13', NULL, NULL, NULL, NULL),
(2329, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:12:27', NULL, NULL, NULL, NULL),
(2330, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:12:29', NULL, NULL, NULL, NULL),
(2331, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:12:30', NULL, NULL, NULL, NULL),
(2332, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:20:20', NULL, NULL, NULL, NULL),
(2333, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:20:44', NULL, NULL, NULL, NULL),
(2334, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:20:44', NULL, NULL, NULL, NULL),
(2335, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:21:18', NULL, NULL, NULL, NULL),
(2336, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:21:44', NULL, NULL, NULL, NULL),
(2337, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:21:57', NULL, NULL, NULL, NULL),
(2338, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:22:10', NULL, NULL, NULL, NULL),
(2339, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:22:11', NULL, NULL, NULL, NULL),
(2340, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:22:13', NULL, NULL, NULL, NULL),
(2341, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:22:14', NULL, NULL, NULL, NULL),
(2342, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:22:17', NULL, NULL, NULL, NULL),
(2343, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:22:19', NULL, NULL, NULL, NULL),
(2344, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:22:20', NULL, NULL, NULL, NULL),
(2345, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 19:22:52', NULL, NULL, NULL, NULL),
(2346, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:23:08', NULL, NULL, NULL, NULL),
(2347, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:26:43', NULL, NULL, NULL, NULL),
(2348, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-14 19:32:49', NULL, NULL, NULL, NULL),
(2349, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:40:35', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(2350, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:41:10', NULL, NULL, NULL, NULL),
(2351, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:47:11', NULL, NULL, NULL, NULL),
(2352, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 19:54:16', NULL, NULL, NULL, NULL),
(2353, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:03:50', NULL, NULL, NULL, NULL),
(2354, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:04:05', NULL, NULL, NULL, NULL),
(2355, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:04:26', NULL, NULL, NULL, NULL),
(2356, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:05:19', NULL, NULL, NULL, NULL),
(2357, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:08:55', NULL, NULL, NULL, NULL),
(2358, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:13:17', NULL, NULL, NULL, NULL),
(2359, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:21:44', NULL, NULL, NULL, NULL),
(2360, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:21:48', NULL, NULL, NULL, NULL),
(2361, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:48:22', NULL, NULL, NULL, NULL),
(2362, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:48:27', NULL, NULL, NULL, NULL),
(2363, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:49:38', NULL, NULL, NULL, NULL),
(2364, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:58:53', NULL, NULL, NULL, NULL),
(2365, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 20:59:55', NULL, NULL, NULL, NULL),
(2366, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 21:05:14', NULL, NULL, NULL, NULL),
(2367, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 21:20:48', NULL, NULL, NULL, NULL),
(2368, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 21:21:14', NULL, NULL, NULL, NULL),
(2369, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 21:21:17', NULL, NULL, NULL, NULL),
(2370, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-14 21:56:58', NULL, NULL, NULL, NULL),
(2371, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 21:57:13', NULL, NULL, NULL, NULL),
(2372, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 21:57:30', NULL, NULL, NULL, NULL),
(2373, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 21:57:30', NULL, NULL, NULL, NULL),
(2374, 2, 'Update Report Generation Settings', 'Settings', 'Updated report generation settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:07:41', NULL, NULL, NULL, NULL),
(2375, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:40:14', NULL, NULL, NULL, NULL),
(2376, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:44:32', NULL, NULL, NULL, NULL),
(2377, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:44:49', NULL, NULL, NULL, NULL),
(2378, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:44:55', NULL, NULL, NULL, NULL),
(2379, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:44:59', NULL, NULL, NULL, NULL),
(2380, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:45:57', NULL, NULL, NULL, NULL),
(2381, 2, 'Add Schedule', 'Schedule ID 6', 'Added new schedule for Saturday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:47:29', NULL, NULL, NULL, NULL),
(2382, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:47:29', NULL, NULL, NULL, NULL),
(2383, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:47:39', NULL, NULL, NULL, NULL),
(2384, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:47:49', NULL, NULL, NULL, NULL),
(2385, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:47:52', NULL, NULL, NULL, NULL),
(2386, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:47:54', NULL, NULL, NULL, NULL),
(2387, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:48:02', NULL, NULL, NULL, NULL),
(2388, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:48:06', NULL, NULL, NULL, NULL),
(2389, 2, 'Add Schedule', 'Schedule ID 7', 'Added new schedule for Tuesday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:49:57', NULL, NULL, NULL, NULL),
(2390, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:49:57', NULL, NULL, NULL, NULL),
(2391, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:49:59', NULL, NULL, NULL, NULL),
(2392, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:50:09', NULL, NULL, NULL, NULL),
(2393, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:56:42', NULL, NULL, NULL, NULL),
(2394, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:56:44', NULL, NULL, NULL, NULL),
(2395, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:56:45', NULL, NULL, NULL, NULL),
(2396, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:56:47', NULL, NULL, NULL, NULL),
(2397, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 22:56:48', NULL, NULL, NULL, NULL),
(2398, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-14 23:59:10', NULL, NULL, NULL, NULL),
(2399, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 23:59:20', NULL, NULL, NULL, NULL),
(2400, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 23:59:31', NULL, NULL, NULL, NULL),
(2401, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 23:59:31', NULL, NULL, NULL, NULL),
(2402, 2, 'View Report', 'Report ID 51', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-14 23:59:56', NULL, NULL, NULL, NULL),
(2403, 2, 'Report Verified', 'Report ID 51', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:00:10', NULL, NULL, NULL, NULL),
(2404, 2, 'View Report', 'Report ID 51', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:00:10', NULL, NULL, NULL, NULL),
(2405, 2, 'Report In Progress', 'Report ID 51', 'Marked report in progress', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:00:13', NULL, NULL, NULL, NULL),
(2406, 2, 'View Report', 'Report ID 51', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:00:14', NULL, NULL, NULL, NULL),
(2407, 2, 'Report Rejected', 'Report ID 51', 'Rejected report. Reason: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:00:17', NULL, NULL, NULL, NULL),
(2408, 2, 'View Report', 'Report ID 51', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:00:17', NULL, NULL, NULL, NULL),
(2409, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:02:08', NULL, NULL, NULL, NULL),
(2410, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 00:04:09', NULL, NULL, NULL, NULL),
(2411, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 01:16:00', NULL, NULL, NULL, NULL),
(2412, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:16:22', NULL, NULL, NULL, NULL),
(2413, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:16:33', NULL, NULL, NULL, NULL),
(2414, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:16:33', NULL, NULL, NULL, NULL),
(2415, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:30:40', NULL, NULL, NULL, NULL),
(2416, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:30:56', NULL, NULL, NULL, NULL),
(2417, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-15 01:35:53', NULL, NULL, NULL, NULL),
(2418, 2, 'Update Report Generation Settings', 'Settings', 'Updated dual logo and report letterhead settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:40:22', NULL, NULL, NULL, NULL),
(2419, 2, 'Update Report Generation Settings', 'Settings', 'Updated dual logo and report letterhead settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:40:26', NULL, NULL, NULL, NULL),
(2420, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:42:05', NULL, NULL, NULL, NULL),
(2421, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:42:12', NULL, NULL, NULL, NULL),
(2422, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:42:58', NULL, NULL, NULL, NULL),
(2423, 2, 'Update Report Generation Settings', 'Settings', 'Updated dual logo and report letterhead settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:46:59', NULL, NULL, NULL, NULL),
(2424, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:47:11', NULL, NULL, NULL, NULL),
(2425, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:47:16', NULL, NULL, NULL, NULL),
(2426, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:47:23', NULL, NULL, NULL, NULL),
(2427, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:50:36', NULL, NULL, NULL, NULL),
(2428, 2, 'Add Landmark', 'Settings', 'Added landmark: brgy hall', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:52:38', NULL, NULL, NULL, NULL),
(2429, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 01:52:43', NULL, NULL, NULL, NULL),
(2430, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:00:41', NULL, NULL, NULL, NULL),
(2431, 2, 'View Report', 'Report ID 47', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:01:05', NULL, NULL, NULL, NULL),
(2432, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:08:28', NULL, NULL, NULL, NULL),
(2433, 2, 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:12:51', NULL, NULL, NULL, NULL),
(2434, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:12:54', NULL, NULL, NULL, NULL),
(2435, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:13:58', NULL, NULL, NULL, NULL),
(2436, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:16:25', NULL, NULL, NULL, NULL),
(2437, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:16:27', NULL, NULL, NULL, NULL),
(2438, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:24:57', NULL, NULL, NULL, NULL),
(2439, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:25:02', NULL, NULL, NULL, NULL),
(2440, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:25:49', NULL, NULL, NULL, NULL),
(2441, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:25:59', NULL, NULL, NULL, NULL),
(2442, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:26:12', NULL, NULL, NULL, NULL),
(2443, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:26:31', NULL, NULL, NULL, NULL),
(2444, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:26:35', NULL, NULL, NULL, NULL),
(2445, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:26:40', NULL, NULL, NULL, NULL),
(2446, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:26:41', NULL, NULL, NULL, NULL),
(2447, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:26:57', NULL, NULL, NULL, NULL),
(2448, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:01', NULL, NULL, NULL, NULL),
(2449, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:18', NULL, NULL, NULL, NULL),
(2450, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:22', NULL, NULL, NULL, NULL),
(2451, 2, 'Delete Schedule', 'Schedule ID 5', 'Deleted schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:27', NULL, NULL, NULL, NULL),
(2452, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:27', NULL, NULL, NULL, NULL),
(2453, 2, 'Delete Schedule', 'Schedule ID 2', 'Deleted schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:30', NULL, NULL, NULL, NULL),
(2454, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:30', NULL, NULL, NULL, NULL),
(2455, 2, 'Delete Schedule', 'Schedule ID 7', 'Deleted schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:32', NULL, NULL, NULL, NULL),
(2456, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:32', NULL, NULL, NULL, NULL),
(2457, 2, 'Delete Schedule', 'Schedule ID 3', 'Deleted schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:34', NULL, NULL, NULL, NULL),
(2458, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:34', NULL, NULL, NULL, NULL),
(2459, 2, 'Delete Schedule', 'Schedule ID 4', 'Deleted schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:37', NULL, NULL, NULL, NULL),
(2460, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:37', NULL, NULL, NULL, NULL),
(2461, 2, 'Delete Schedule', 'Schedule ID 6', 'Deleted schedule', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:39', NULL, NULL, NULL, NULL),
(2462, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:39', NULL, NULL, NULL, NULL),
(2463, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:47', NULL, NULL, NULL, NULL),
(2464, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:27:49', NULL, NULL, NULL, NULL),
(2465, 2, 'Add Schedule', 'Schedule ID 8', 'Added new schedule for Sunday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:30:46', NULL, NULL, NULL, NULL),
(2466, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:30:46', NULL, NULL, NULL, NULL),
(2467, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:11', NULL, NULL, NULL, NULL),
(2468, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:15', NULL, NULL, NULL, NULL),
(2469, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:20', NULL, NULL, NULL, NULL),
(2470, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:26', NULL, NULL, NULL, NULL),
(2471, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:32', NULL, NULL, NULL, NULL),
(2472, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:34', NULL, NULL, NULL, NULL),
(2473, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:39', NULL, NULL, NULL, NULL),
(2474, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:41', NULL, NULL, NULL, NULL),
(2475, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:31:49', NULL, NULL, NULL, NULL),
(2476, 2, 'Update Schedule', 'Schedule ID 8', 'Updated schedule for Saturday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:32:00', NULL, NULL, NULL, NULL),
(2477, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:32:00', NULL, NULL, NULL, NULL),
(2478, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:32:04', NULL, NULL, NULL, NULL),
(2479, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:32:08', NULL, NULL, NULL, NULL),
(2480, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:32:11', NULL, NULL, NULL, NULL),
(2481, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:32:26', NULL, NULL, NULL, NULL),
(2482, 3, 'Password Reset', 'User', 'Password reset via email OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:34:16', NULL, NULL, NULL, NULL),
(2483, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:34:27', NULL, NULL, NULL, NULL),
(2484, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:34:36', NULL, NULL, NULL, NULL),
(2485, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:36:08', NULL, NULL, NULL, NULL),
(2486, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:37:13', NULL, NULL, NULL, NULL),
(2487, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:37:25', NULL, NULL, NULL, NULL),
(2488, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:37:43', NULL, NULL, NULL, NULL),
(2489, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 02:40:21', NULL, NULL, NULL, NULL),
(2490, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 07:14:15', NULL, NULL, NULL, NULL),
(2491, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:14:27', NULL, NULL, NULL, NULL),
(2492, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:15:29', NULL, NULL, NULL, NULL),
(2493, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:15:42', NULL, NULL, NULL, NULL),
(2494, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:15:42', NULL, NULL, NULL, NULL),
(2495, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:16:08', NULL, NULL, NULL, NULL),
(2496, 2, 'Update Report Generation Settings', 'Settings', 'Updated dual logo and report letterhead settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:31:38', NULL, NULL, NULL, NULL),
(2497, 2, 'Update Report Generation Settings', 'Settings', 'Updated dual logo and report letterhead settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:31:45', NULL, NULL, NULL, NULL),
(2498, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:32:44', NULL, NULL, NULL, NULL),
(2499, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:32:58', NULL, NULL, NULL, NULL),
(2500, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:33:01', NULL, NULL, NULL, NULL),
(2501, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:33:42', NULL, NULL, NULL, NULL),
(2502, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:34:09', NULL, NULL, NULL, NULL),
(2503, 2, 'Export Reports', 'Reports', 'Admin exported waste reports to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:36:19', NULL, NULL, NULL, NULL),
(2504, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:40:22', NULL, NULL, NULL, NULL),
(2505, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:40:28', NULL, NULL, NULL, NULL),
(2506, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:53:17', NULL, NULL, NULL, NULL),
(2507, 2, 'View Report', 'Report ID 53', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:54:02', NULL, NULL, NULL, NULL),
(2508, 2, 'View Report', 'Report ID 51', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:54:07', NULL, NULL, NULL, NULL),
(2509, 2, 'View Report', 'Report ID 50', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:54:14', NULL, NULL, NULL, NULL),
(2510, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:54:22', NULL, NULL, NULL, NULL),
(2511, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 07:56:19', NULL, NULL, NULL, NULL),
(2512, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 08:00:24', NULL, NULL, NULL, NULL),
(2513, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 08:20:30', NULL, NULL, NULL, NULL),
(2514, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 08:46:22', NULL, NULL, NULL, NULL),
(2515, 2, 'View Report', 'Report ID 44', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 08:47:34', NULL, NULL, NULL, NULL),
(2516, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:12:00', NULL, NULL, NULL, NULL),
(2517, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:15:17', NULL, NULL, NULL, NULL),
(2518, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:15:23', NULL, NULL, NULL, NULL),
(2519, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:15:36', NULL, NULL, NULL, NULL),
(2520, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:16:03', NULL, NULL, NULL, NULL),
(2521, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 09:25:01', NULL, NULL, NULL, NULL),
(2522, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:25:10', NULL, NULL, NULL, NULL),
(2523, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:25:14', NULL, NULL, NULL, NULL),
(2524, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:25:30', NULL, NULL, NULL, NULL),
(2525, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:11', NULL, NULL, NULL, NULL),
(2526, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:16', NULL, NULL, NULL, NULL),
(2527, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:19', NULL, NULL, NULL, NULL),
(2528, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:21', NULL, NULL, NULL, NULL),
(2529, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:22', NULL, NULL, NULL, NULL),
(2530, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:26', NULL, NULL, NULL, NULL),
(2531, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:47', NULL, NULL, NULL, NULL),
(2532, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:48', NULL, NULL, NULL, NULL),
(2533, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:49', NULL, NULL, NULL, NULL),
(2534, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:52', NULL, NULL, NULL, NULL),
(2535, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:55', NULL, NULL, NULL, NULL),
(2536, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:26:58', NULL, NULL, NULL, NULL),
(2537, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:02', NULL, NULL, NULL, NULL),
(2538, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:05', NULL, NULL, NULL, NULL),
(2539, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:27', NULL, NULL, NULL, NULL),
(2540, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:29', NULL, NULL, NULL, NULL),
(2541, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:31', NULL, NULL, NULL, NULL),
(2542, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:33', NULL, NULL, NULL, NULL),
(2543, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:39', NULL, NULL, NULL, NULL),
(2544, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:40', NULL, NULL, NULL, NULL),
(2545, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 09:27:42', NULL, NULL, NULL, NULL),
(2546, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 10:28:11', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(2547, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:28:27', NULL, NULL, NULL, NULL),
(2548, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:28:57', NULL, NULL, NULL, NULL),
(2549, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:28:57', NULL, NULL, NULL, NULL),
(2550, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:29:05', NULL, NULL, NULL, NULL),
(2551, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:31:08', NULL, NULL, NULL, NULL),
(2552, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:31:10', NULL, NULL, NULL, NULL),
(2553, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:31:11', NULL, NULL, NULL, NULL),
(2554, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:31:13', NULL, NULL, NULL, NULL),
(2555, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:31:37', NULL, NULL, NULL, NULL),
(2556, 2, 'View Notification Logs', 'Notifications', 'Admin opened notification log center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:31:38', NULL, NULL, NULL, NULL),
(2557, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:56:54', NULL, NULL, NULL, NULL),
(2558, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:56:55', NULL, NULL, NULL, NULL),
(2559, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:56:55', NULL, NULL, NULL, NULL),
(2560, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:56:56', NULL, NULL, NULL, NULL),
(2561, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:57:55', NULL, NULL, NULL, NULL),
(2562, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:57:57', NULL, NULL, NULL, NULL),
(2563, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:58:01', NULL, NULL, NULL, NULL),
(2564, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:58:02', NULL, NULL, NULL, NULL),
(2565, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:58:07', NULL, NULL, NULL, NULL),
(2566, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:58:08', NULL, NULL, NULL, NULL),
(2567, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 10:58:14', NULL, NULL, NULL, NULL),
(2568, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:00:01', NULL, NULL, NULL, NULL),
(2569, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:00:03', NULL, NULL, NULL, NULL),
(2570, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:00:04', NULL, NULL, NULL, NULL),
(2571, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:00:04', NULL, NULL, NULL, NULL),
(2572, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-15 11:00:16', NULL, NULL, NULL, NULL),
(2573, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:00:27', NULL, NULL, NULL, NULL),
(2574, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:00:56', NULL, NULL, NULL, NULL),
(2575, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:01:07', NULL, NULL, NULL, NULL),
(2576, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:01:08', NULL, NULL, NULL, NULL),
(2577, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:01:12', NULL, NULL, NULL, NULL),
(2578, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:01:21', NULL, NULL, NULL, NULL),
(2579, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:01:22', NULL, NULL, NULL, NULL),
(2580, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:07:31', NULL, NULL, NULL, NULL),
(2581, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:07:33', NULL, NULL, NULL, NULL),
(2582, 1, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', NULL, NULL, 'success', '2026-08-15 11:09:03', NULL, NULL, NULL, NULL),
(2583, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:27:20', NULL, NULL, NULL, NULL),
(2584, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:27:32', NULL, NULL, NULL, NULL),
(2585, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:27:53', NULL, NULL, NULL, NULL),
(2586, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:27:54', NULL, NULL, NULL, NULL),
(2587, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:30:08', NULL, NULL, NULL, NULL),
(2588, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:31:11', NULL, NULL, NULL, NULL),
(2589, 1, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', NULL, NULL, 'success', '2026-08-15 11:35:03', NULL, NULL, NULL, NULL),
(2590, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:35:34', NULL, NULL, NULL, NULL),
(2591, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:35:58', NULL, NULL, NULL, NULL),
(2592, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:35:59', NULL, NULL, NULL, NULL),
(2593, 2, 'Export Audit Logs', 'Audit Logs', 'Admin exported system audit trail to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:38:46', NULL, NULL, NULL, NULL),
(2594, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:39:35', NULL, NULL, NULL, NULL),
(2595, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:39:55', NULL, NULL, NULL, NULL),
(2596, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:39:58', NULL, NULL, NULL, NULL),
(2597, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:40:00', NULL, NULL, NULL, NULL),
(2598, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:40:11', NULL, NULL, NULL, NULL),
(2599, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:40:14', NULL, NULL, NULL, NULL),
(2600, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:40:50', NULL, NULL, NULL, NULL),
(2601, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:40:53', NULL, NULL, NULL, NULL),
(2602, 3, '2FA failed', 'User', 'Invalid or expired OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-15 11:42:02', NULL, NULL, NULL, NULL),
(2603, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:42:14', NULL, NULL, NULL, NULL),
(2604, 2, 'Profile Updated', 'Profile', 'Admin updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:51:12', NULL, NULL, NULL, NULL),
(2605, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:51:40', NULL, NULL, NULL, NULL),
(2606, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:53:24', NULL, NULL, NULL, NULL),
(2607, 2, 'Report Verified', 'Report ID 54', 'Verified report', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:53:27', NULL, NULL, NULL, NULL),
(2608, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:53:28', NULL, NULL, NULL, NULL),
(2609, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 11:54:05', NULL, NULL, NULL, NULL),
(2610, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:01:16', NULL, NULL, NULL, NULL),
(2611, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:01:17', NULL, NULL, NULL, NULL),
(2612, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:01:23', NULL, NULL, NULL, NULL),
(2613, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:01:26', NULL, NULL, NULL, NULL),
(2614, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:10:02', NULL, NULL, NULL, NULL),
(2615, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:10:14', NULL, NULL, NULL, NULL),
(2616, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:13:39', NULL, NULL, NULL, NULL),
(2617, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:14:01', NULL, NULL, NULL, NULL),
(2618, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:14:03', NULL, NULL, NULL, NULL),
(2619, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:14:06', NULL, NULL, NULL, NULL),
(2620, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:14:12', NULL, NULL, NULL, NULL),
(2621, 2, 'Profile Updated', 'Profile', 'Admin updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:14:17', NULL, NULL, NULL, NULL),
(2622, 2, 'Profile Updated', 'Profile', 'Admin updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:17:58', NULL, NULL, NULL, NULL),
(2623, 2, 'Profile Updated', 'Profile', 'Admin updated personal information', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:18:12', NULL, NULL, NULL, NULL),
(2624, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:18:26', NULL, NULL, NULL, NULL),
(2625, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 12:18:31', NULL, NULL, NULL, NULL),
(2626, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 13:22:46', NULL, NULL, NULL, NULL),
(2627, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 13:22:54', NULL, NULL, NULL, NULL),
(2628, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 13:23:13', NULL, NULL, NULL, NULL),
(2629, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 14:03:01', NULL, NULL, NULL, NULL),
(2630, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 14:03:10', NULL, NULL, NULL, NULL),
(2631, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 14:03:26', NULL, NULL, NULL, NULL),
(2632, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 14:54:36', NULL, NULL, NULL, NULL),
(2633, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 14:54:45', NULL, NULL, NULL, NULL),
(2634, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 14:55:07', NULL, NULL, NULL, NULL),
(2635, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 17:29:52', NULL, NULL, NULL, NULL),
(2636, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-15 19:13:18', NULL, NULL, NULL, NULL),
(2637, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 19:13:40', NULL, NULL, NULL, NULL),
(2638, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 19:14:00', NULL, NULL, NULL, NULL),
(2639, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 19:14:00', NULL, NULL, NULL, NULL),
(2640, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 19:15:16', NULL, NULL, NULL, NULL),
(2641, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 19:15:47', NULL, NULL, NULL, NULL),
(2642, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-15 19:24:46', NULL, NULL, NULL, NULL),
(2643, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-16 06:18:21', NULL, NULL, NULL, NULL),
(2644, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:18:39', NULL, NULL, NULL, NULL),
(2645, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:18:50', NULL, NULL, NULL, NULL),
(2646, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:18:50', NULL, NULL, NULL, NULL),
(2647, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:24:16', NULL, NULL, NULL, NULL),
(2648, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:24:44', NULL, NULL, NULL, NULL),
(2649, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:24:51', NULL, NULL, NULL, NULL),
(2650, 2, 'View Report', 'Report ID 52', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:26:10', NULL, NULL, NULL, NULL),
(2651, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:33:37', NULL, NULL, NULL, NULL),
(2652, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:33:58', NULL, NULL, NULL, NULL),
(2653, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:33:58', NULL, NULL, NULL, NULL),
(2654, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:35:34', NULL, NULL, NULL, NULL),
(2655, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:49:37', NULL, NULL, NULL, NULL),
(2656, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:49:52', NULL, NULL, NULL, NULL),
(2657, 2, 'Reset Barangay Boundary', 'Settings', 'Reset boundary to default coordinates', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:51:13', NULL, NULL, NULL, NULL),
(2658, 2, 'Reset Barangay Boundary', 'Settings', 'Reset boundary to default coordinates', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:55:54', NULL, NULL, NULL, NULL),
(2659, 2, 'Update Barangay Boundary', 'Settings', 'Updated official Barangay boundary polygon and map center', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-16 06:56:10', NULL, NULL, NULL, NULL),
(2660, 2, 'Update Barangay Boundary', 'Settings', 'Updated official Barangay boundary polygon and map center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 06:58:10', NULL, NULL, NULL, NULL),
(2661, 2, 'Update Barangay Boundary', 'Settings', 'Updated official Barangay boundary polygon and map center', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-16 07:00:20', NULL, NULL, NULL, NULL),
(2662, 2, 'Update Barangay Boundary', 'Settings', 'Updated official Barangay boundary polygon and map center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:01:58', NULL, NULL, NULL, NULL),
(2663, 2, 'Update Barangay Boundary', 'Settings', 'Updated official Barangay boundary polygon and map center', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:02:39', NULL, NULL, NULL, NULL),
(2664, 2, 'Reset Barangay Boundary', 'Settings', 'Reset boundary to default coordinates', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:02:44', NULL, NULL, NULL, NULL),
(2665, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:14:46', NULL, NULL, NULL, NULL),
(2666, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:15:07', NULL, NULL, NULL, NULL),
(2667, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-16 07:36:08', NULL, NULL, NULL, NULL),
(2668, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:36:27', NULL, NULL, NULL, NULL),
(2669, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:36:40', NULL, NULL, NULL, NULL),
(2670, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:36:40', NULL, NULL, NULL, NULL),
(2671, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:36:44', NULL, NULL, NULL, NULL),
(2672, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:36:46', NULL, NULL, NULL, NULL),
(2673, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:36:48', NULL, NULL, NULL, NULL),
(2674, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:36:50', NULL, NULL, NULL, NULL),
(2675, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:40:23', NULL, NULL, NULL, NULL),
(2676, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:40:25', NULL, NULL, NULL, NULL),
(2677, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:40:26', NULL, NULL, NULL, NULL),
(2678, 2, 'Add Schedule', 'Schedule ID 9', 'Added new schedule for Sunday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:40:51', NULL, NULL, NULL, NULL),
(2679, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:40:51', NULL, NULL, NULL, NULL),
(2680, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:40:53', NULL, NULL, NULL, NULL),
(2681, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:40:58', NULL, NULL, NULL, NULL),
(2682, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:41:59', NULL, NULL, NULL, NULL),
(2683, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:45:36', NULL, NULL, NULL, NULL),
(2684, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-16 07:46:12', NULL, NULL, NULL, NULL),
(2685, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-16 07:47:11', NULL, NULL, NULL, NULL),
(2686, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:47:31', NULL, NULL, NULL, NULL),
(2687, 2, 'Export Audit Logs', 'Audit Logs', 'Admin exported system audit trail to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:47:57', NULL, NULL, NULL, NULL),
(2688, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 07:49:47', NULL, NULL, NULL, NULL),
(2689, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 08:05:17', NULL, NULL, NULL, NULL),
(2690, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 08:07:48', NULL, NULL, NULL, NULL),
(2691, 1, 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', NULL, NULL, 'success', '2026-08-16 13:38:22', NULL, NULL, NULL, NULL),
(2692, 1, 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', NULL, NULL, 'success', '2026-08-16 13:38:22', NULL, NULL, NULL, NULL),
(2693, 1, 'Announcements View', 'Announcements', 'Supervisor viewed announcements', NULL, NULL, 'success', '2026-08-16 13:38:22', NULL, NULL, NULL, NULL),
(2694, 1, 'Notifications View', 'Notifications', 'Supervisor viewed notifications', NULL, NULL, 'success', '2026-08-16 13:38:22', NULL, NULL, NULL, NULL),
(2695, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-16 17:35:24', NULL, NULL, NULL, NULL),
(2696, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:36:24', NULL, NULL, NULL, NULL),
(2697, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:36:37', NULL, NULL, NULL, NULL),
(2698, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:36:37', NULL, NULL, NULL, NULL),
(2699, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:36:44', NULL, NULL, NULL, NULL),
(2700, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:36:55', NULL, NULL, NULL, NULL),
(2701, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:37:13', NULL, NULL, NULL, NULL),
(2702, 2, 'Update Schedule', 'Schedule ID 8', 'Updated schedule for Saturday', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:37:44', NULL, NULL, NULL, NULL),
(2703, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:37:44', NULL, NULL, NULL, NULL),
(2704, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:40:01', NULL, NULL, NULL, NULL),
(2705, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:40:24', NULL, NULL, NULL, NULL),
(2706, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:40:26', NULL, NULL, NULL, NULL),
(2707, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:40:30', NULL, NULL, NULL, NULL),
(2708, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:40:38', NULL, NULL, NULL, NULL),
(2709, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:43:54', NULL, NULL, NULL, NULL),
(2710, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:49:48', NULL, NULL, NULL, NULL),
(2711, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:49:53', NULL, NULL, NULL, NULL),
(2712, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:49:56', NULL, NULL, NULL, NULL),
(2713, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:50:00', NULL, NULL, NULL, NULL),
(2714, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:50:03', NULL, NULL, NULL, NULL),
(2715, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:50:06', NULL, NULL, NULL, NULL),
(2716, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 17:50:11', NULL, NULL, NULL, NULL),
(2717, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:20:32', NULL, NULL, NULL, NULL),
(2718, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:20:52', NULL, NULL, NULL, NULL),
(2719, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:22:55', NULL, NULL, NULL, NULL),
(2720, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:36:25', NULL, NULL, NULL, NULL),
(2721, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:37:03', NULL, NULL, NULL, NULL),
(2722, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:37:48', NULL, NULL, NULL, NULL),
(2723, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:38:03', NULL, NULL, NULL, NULL),
(2724, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:38:13', NULL, NULL, NULL, NULL),
(2725, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:50:07', NULL, NULL, NULL, NULL),
(2726, 2, 'Add Role', 'Settings', 'Created role: brgy tanod', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 18:57:08', NULL, NULL, NULL, NULL),
(2727, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:10:06', NULL, NULL, NULL, NULL),
(2728, 2, 'Delete Role', 'Settings', 'Deleted role ID 14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:16:55', NULL, NULL, NULL, NULL),
(2729, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:17:12', NULL, NULL, NULL, NULL),
(2730, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:17:42', NULL, NULL, NULL, NULL),
(2731, 2, 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:19:41', NULL, NULL, NULL, NULL),
(2732, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:23:13', NULL, NULL, NULL, NULL),
(2733, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:24:03', NULL, NULL, NULL, NULL),
(2734, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 19:24:37', NULL, NULL, NULL, NULL),
(2735, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-16 21:57:37', NULL, NULL, NULL, NULL),
(2736, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 21:58:31', NULL, NULL, NULL, NULL),
(2737, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 21:58:47', NULL, NULL, NULL, NULL),
(2738, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 21:58:47', NULL, NULL, NULL, NULL),
(2739, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 21:58:53', NULL, NULL, NULL, NULL),
(2740, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-16 22:00:38', NULL, NULL, NULL, NULL),
(2741, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'success', '2026-08-16 22:01:21', NULL, NULL, NULL, NULL),
(2742, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:01:33', NULL, NULL, NULL, NULL),
(2743, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:05:53', NULL, NULL, NULL, NULL),
(2744, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:06:26', NULL, NULL, NULL, NULL),
(2745, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:08:49', NULL, NULL, NULL, NULL),
(2746, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:09:18', NULL, NULL, NULL, NULL);
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `affected_record`, `details`, `ip_address`, `user_agent`, `result`, `created_at`, `module`, `record_id`, `old_value`, `new_value`) VALUES
(2747, 2, 'View Report', 'Report ID 54', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:09:29', NULL, NULL, NULL, NULL),
(2748, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:24:19', NULL, NULL, NULL, NULL),
(2749, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:35:50', NULL, NULL, NULL, NULL),
(2750, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:36:26', NULL, NULL, NULL, NULL),
(2751, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:37:02', NULL, NULL, NULL, NULL),
(2752, 2, 'Update Purok Boundary', 'Settings', 'Updated boundary for purok ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:38:05', NULL, NULL, NULL, NULL),
(2753, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:39:53', NULL, NULL, NULL, NULL),
(2754, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:40:33', NULL, NULL, NULL, NULL),
(2755, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:40:39', NULL, NULL, NULL, NULL),
(2756, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:41:01', NULL, NULL, NULL, NULL),
(2757, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:41:01', NULL, NULL, NULL, NULL),
(2758, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:53:29', NULL, NULL, NULL, NULL),
(2759, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:53:43', NULL, NULL, NULL, NULL),
(2760, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-16 22:54:10', NULL, NULL, NULL, NULL),
(2761, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 00:13:29', NULL, NULL, NULL, NULL),
(2762, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:13:46', NULL, NULL, NULL, NULL),
(2763, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:14:08', NULL, NULL, NULL, NULL),
(2764, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:14:08', NULL, NULL, NULL, NULL),
(2765, 2, 'Disable Maintenance Mode', 'SystemMaintenance', 'Maintenance mode deactivated. System restored to operational.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:15:57', NULL, NULL, NULL, NULL),
(2766, 2, 'Update Maintenance Settings', 'SystemMaintenance', 'Updated maintenance settings (type: scheduled)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:17:40', NULL, NULL, NULL, NULL),
(2767, 2, 'Disable Maintenance Mode', 'SystemMaintenance', 'Maintenance mode deactivated. System restored to operational.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:18:07', NULL, NULL, NULL, NULL),
(2768, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:34:36', NULL, NULL, NULL, NULL),
(2769, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:34:42', NULL, NULL, NULL, NULL),
(2770, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:47:49', NULL, NULL, NULL, NULL),
(2771, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:48:17', NULL, NULL, NULL, NULL),
(2772, 2, 'View Report', 'Report ID 35', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:49:42', NULL, NULL, NULL, NULL),
(2773, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:54:19', NULL, NULL, NULL, NULL),
(2774, 2, 'View Report', 'Report ID 49', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 00:54:33', NULL, NULL, NULL, NULL),
(2775, 2, 'View Report', 'Report ID 48', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:11:05', NULL, NULL, NULL, NULL),
(2776, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:13:34', NULL, NULL, NULL, NULL),
(2777, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:15:29', NULL, NULL, NULL, NULL),
(2778, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:18:46', NULL, NULL, NULL, NULL),
(2779, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:22:56', NULL, NULL, NULL, NULL),
(2780, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:23:00', NULL, NULL, NULL, NULL),
(2781, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:23:15', NULL, NULL, NULL, NULL),
(2782, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:23:17', NULL, NULL, NULL, NULL),
(2783, 2, 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:23:28', NULL, NULL, NULL, NULL),
(2784, 2, 'Analytics Export', 'Analytics', 'Exported analytics PDF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:23:36', NULL, NULL, NULL, NULL),
(2785, 2, 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:23:53', NULL, NULL, NULL, NULL),
(2786, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:24:29', NULL, NULL, NULL, NULL),
(2787, 2, 'View Report', 'Report ID 46', 'Admin viewed report details', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:25:07', NULL, NULL, NULL, NULL),
(2788, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:27:09', NULL, NULL, NULL, NULL),
(2789, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 01:27:26', NULL, NULL, NULL, NULL),
(2790, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 10:02:24', NULL, NULL, NULL, NULL),
(2791, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:06:06', NULL, NULL, NULL, NULL),
(2792, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:06:21', NULL, NULL, NULL, NULL),
(2793, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:06:22', NULL, NULL, NULL, NULL),
(2794, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:06:40', NULL, NULL, NULL, NULL),
(2795, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:07:52', NULL, NULL, NULL, NULL),
(2796, 2, 'Post Announcement', 'Announcements', 'Posted \'Sobrang Dumi\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:11:11', NULL, NULL, NULL, NULL),
(2797, 2, 'Delete Announcement', 'Announcement ID 10', 'Deleted announcement', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:11:29', NULL, NULL, NULL, NULL),
(2798, 2, 'Update Report Generation Settings', 'Settings', 'Updated dual logo and report letterhead settings', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:16:18', NULL, NULL, NULL, NULL),
(2799, 2, 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:16:26', NULL, NULL, NULL, NULL),
(2800, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:16:29', NULL, NULL, NULL, NULL),
(2801, 2, 'Schedule Management', 'Schedule', 'Admin viewed schedule management', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:17:40', NULL, NULL, NULL, NULL),
(2802, 2, 'Update Maintenance Settings', 'SystemMaintenance', 'Updated maintenance settings (type: emergency)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:20:12', NULL, NULL, NULL, NULL),
(2803, 2, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:20:18', NULL, NULL, NULL, NULL),
(2804, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:21:42', NULL, NULL, NULL, NULL),
(2805, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 10:21:43', NULL, NULL, NULL, NULL),
(2806, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:21:58', NULL, NULL, NULL, NULL),
(2807, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:21:58', NULL, NULL, NULL, NULL),
(2808, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:22:00', NULL, NULL, NULL, NULL),
(2809, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:23:26', NULL, NULL, NULL, NULL),
(2810, 2, 'Enable Emergency Lockdown', 'SystemMaintenance', 'EMERGENCY LOCKDOWN activated. Reason: Under Maintenance', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:24:22', NULL, NULL, NULL, NULL),
(2811, 2, 'Disable Emergency Lockdown', 'SystemMaintenance', 'Emergency lockdown deactivated. System restored to operational.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 10:27:10', NULL, NULL, NULL, NULL),
(2812, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 11:18:55', NULL, NULL, NULL, NULL),
(2813, 3, 'OTP send failed', 'User', 'SMTP Error: Could not connect to SMTP host. Failed to connect to server', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-17 11:19:18', NULL, NULL, NULL, NULL),
(2814, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 11:20:58', NULL, NULL, NULL, NULL),
(2815, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 11:22:41', NULL, NULL, NULL, NULL),
(2816, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 13:00:07', NULL, NULL, NULL, NULL),
(2817, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 13:32:16', NULL, NULL, NULL, NULL),
(2818, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 13:32:42', NULL, NULL, NULL, NULL),
(2819, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 13:32:42', NULL, NULL, NULL, NULL),
(2820, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 16:28:14', NULL, NULL, NULL, NULL),
(2821, 2, 'OTP send failed', 'User', 'SMTP Error: Could not authenticate.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-17 16:28:38', NULL, NULL, NULL, NULL),
(2822, 2, 'OTP send failed', 'User', 'SMTP Error: Could not authenticate.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-17 16:34:08', NULL, NULL, NULL, NULL),
(2823, 2, 'OTP send failed', 'User', 'SMTP Error: Could not authenticate.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'failed', '2026-08-17 16:36:03', NULL, NULL, NULL, NULL),
(2824, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 16:45:32', NULL, NULL, NULL, NULL),
(2825, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 16:46:21', NULL, NULL, NULL, NULL),
(2826, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 16:46:27', NULL, NULL, NULL, NULL),
(2827, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 16:50:13', NULL, NULL, NULL, NULL),
(2828, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 16:56:39', NULL, NULL, NULL, NULL),
(2829, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 16:57:09', NULL, NULL, NULL, NULL),
(2830, NULL, 'Registration OTP sent', 'User', 'Email OTP sent to limuelle.neust@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 16:59:58', NULL, NULL, NULL, NULL),
(2831, 23, 'Registration verified', 'User', 'Account activated via OTP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:00:14', NULL, NULL, NULL, NULL),
(2832, 23, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:00:26', NULL, NULL, NULL, NULL),
(2833, 23, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:00:35', NULL, NULL, NULL, NULL),
(2834, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:03:44', NULL, NULL, NULL, NULL),
(2835, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:03:53', NULL, NULL, NULL, NULL),
(2836, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:04:12', NULL, NULL, NULL, NULL),
(2837, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:04:15', NULL, NULL, NULL, NULL),
(2838, 3, 'Login partial success', 'User', 'OTP sent to phone', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:04:24', NULL, NULL, NULL, NULL),
(2839, 3, '2FA Resend', 'User', 'Code resent to phone', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:07:32', NULL, NULL, NULL, NULL),
(2840, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:07:47', NULL, NULL, NULL, NULL),
(2841, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:09:54', NULL, NULL, NULL, NULL),
(2842, 23, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:10:55', NULL, NULL, NULL, NULL),
(2843, 23, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:11:04', NULL, NULL, NULL, NULL),
(2844, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:12:50', NULL, NULL, NULL, NULL),
(2845, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:18:12', NULL, NULL, NULL, NULL),
(2846, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:18:32', NULL, NULL, NULL, NULL),
(2847, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:19:42', NULL, NULL, NULL, NULL),
(2848, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:20:00', NULL, NULL, NULL, NULL),
(2849, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 17:20:00', NULL, NULL, NULL, NULL),
(2850, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 17:50:46', NULL, NULL, NULL, NULL),
(2851, 2, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 18:11:26', NULL, NULL, NULL, NULL),
(2852, 3, 'Login partial success', 'User', 'OTP sent to phone', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 18:17:07', NULL, NULL, NULL, NULL),
(2853, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-17 18:17:32', NULL, NULL, NULL, NULL),
(2854, 3, 'Auto Logout', 'Session', 'User logged out due to inactivity', NULL, NULL, 'success', '2026-08-17 20:09:42', NULL, NULL, NULL, NULL),
(2917, 3, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-18 00:24:35', NULL, NULL, NULL, NULL),
(2918, 3, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-18 00:24:59', NULL, NULL, NULL, NULL),
(2919, 3, 'Logout', 'User', 'User logged out manually', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-18 00:28:29', NULL, NULL, NULL, NULL),
(2920, 2, 'Login partial success', 'User', 'OTP sent to email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-18 00:29:14', NULL, NULL, NULL, NULL),
(2921, 2, 'Login successful', 'User', 'Successfully completed 2FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-18 00:29:29', NULL, NULL, NULL, NULL),
(2922, 2, 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'success', '2026-08-18 00:29:29', NULL, NULL, NULL, NULL);

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
(1, 'Dulong Bayan', 'Quezon', 'Nueva Ecija', 'Central Luzon', 'brgy dulong bayan, quezon, nueva ecija', '09951281511', 'floreshans.neust@gmail.com', 'uploads/logos/brgy_seal_1786751779.jpg', '2026-07-25 14:27:25', '2026-08-18 00:06:33', 'Barangay Waste Management System', 'LINARAYA', '', 'uploads/logos/sys_logo_1786879181.jpg');

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
(109, 'floressktt11@gmail.com', '::1', '2026-08-09 02:00:00', 1),
(110, 'floressktt11@gmail.com', '::1', '2026-08-09 03:00:00', 1),
(111, 'floreshans.neust@gmail.com', '::1', '2026-08-09 03:00:00', 1),
(112, 'floressktt11@gmail.com', '::1', '2026-08-09 17:00:00', 1),
(113, 'floreshans.neust@gmail.com', '::1', '2026-08-09 20:00:00', 1),
(114, '09951281511', '::1', '2026-08-10 01:00:00', 1),
(115, '09951281511', '::1', '2026-08-10 02:00:00', 1),
(116, 'floreshans.neust@gmail.com', '::1', '2026-08-10 05:00:00', 1),
(117, '09951281511', '::1', '2026-08-10 06:00:00', 1),
(118, 'floressktt11@gmail.com', '::1', '2026-08-10 07:00:00', 1),
(119, 'floreshans.neust@gmail.com', '::1', '2026-08-11 20:00:00', 2),
(121, 'floreshans.neust@gmail.com', '::1', '2026-08-14 04:00:00', 1),
(122, 'floressktt11@gmail.com', '::1', '2026-08-14 04:00:00', 1),
(123, 'floreshans.neust@gmail.com', '::1', '2026-08-14 12:00:00', 2),
(125, 'floreshans.neust@gmail.com', '::1', '2026-08-14 15:00:00', 1),
(126, 'floreshans.neust@gmail.com', '::1', '2026-08-14 17:00:00', 1),
(127, 'floreshans.neust@gmail.com', '::1', '2026-08-14 19:00:00', 1),
(128, 'floressktt11@gmail.com', '::1', '2026-08-14 20:00:00', 1),
(129, 'floreshans.neust@gmail.com', '::1', '2026-08-15 01:00:00', 2),
(131, 'floreshans.neust@gmail.com', '::1', '2026-08-15 04:00:00', 1),
(132, 'floressktt11@gmail.com', '::1', '2026-08-15 05:00:00', 1),
(133, 'floressktt11@gmail.com', '::1', '2026-08-15 07:00:00', 1),
(134, 'floressktt11@gmail.com', '::1', '2026-08-15 08:00:00', 2),
(136, 'floreshans.neust@gmail.com', '::1', '2026-08-15 13:00:00', 1),
(137, 'floreshans.neust@gmail.com', '::1', '2026-08-16 00:00:00', 1),
(138, 'floressktt11@gmail.com', '::1', '2026-08-16 01:00:00', 1),
(139, 'floreshans.neust@gmail.com', '::1', '2026-08-16 01:00:00', 1),
(140, 'floreshans.neust@gmail.com', '::1', '2026-08-16 11:00:00', 1),
(141, 'floreshans.neust@gmail.com', '::1', '2026-08-16 15:00:00', 1),
(142, 'floreshans.neust@gmail.com', '::1', '2026-08-16 18:00:00', 1),
(143, 'floressktt11@gmail.com', '::1', '2026-08-16 19:00:00', 1),
(144, 'floreshans.neust@gmail.com', '::1', '2026-08-17 04:00:00', 2),
(146, 'floressktt11@gmail.com', '::1', '2026-08-17 04:00:00', 1),
(147, 'floressktt11@gmail.com', '::1', '2026-08-17 05:00:00', 2),
(149, 'floreshans.neust@gmail.com', '::1', '2026-08-17 07:00:00', 1),
(150, 'floressktt11@gmail.com', '::1', '2026-08-17 10:00:00', 3),
(153, 'limuelle.neust@gmail.com', '::1', '2026-08-17 11:00:00', 2),
(154, 'floressktt11@gmail.com', '::1', '2026-08-17 11:00:00', 5),
(155, '09951281511', '::1', '2026-08-17 11:00:00', 2),
(162, 'floreshans.neust@gmail.com', '::1', '2026-08-17 11:00:00', 1),
(163, '09951281511', '::1', '2026-08-17 12:00:00', 1),
(164, 'floressktt11@gmail.com', '::1', '2026-08-17 18:00:00', 1),
(165, 'floreshans.neust@gmail.com', '::1', '2026-08-17 18:00:00', 1);

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
  `phone` varchar(20) NOT NULL,
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
(36, '09121212121', '963061', '2026-08-16 16:16:58', 0, 0, '::1', '2026-08-16 08:11:58'),
(37, '09951281511', '802020', '2026-08-17 16:42:02', 1, 0, '::1', '2026-08-17 08:37:02'),
(38, '09951281511', '325038', '2026-08-17 16:43:49', 1, 0, '::1', '2026-08-17 08:38:49'),
(39, '09951281511', '624216', '2026-08-17 17:55:56', 1, 0, '::1', '2026-08-17 09:50:56'),
(40, '09951281511', '964366', '2026-08-17 18:18:39', 1, 0, '::1', '2026-08-17 10:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `guest_sms_rate_limits`
--

CREATE TABLE `guest_sms_rate_limits` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
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
(40, '09951281511', '::1', '2026-08-17 12:00:00', 1);

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
(1, 85, 3, '#bae6fd', '#f59e0b', '#b91c1c', 2, '2026-08-15 02:12:51'),
(2, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:27:25'),
(3, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:30:39'),
(4, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:31:01'),
(5, 50, 3, '#FDE68A', '#F97316', '#EF4444', NULL, '2026-07-25 14:35:15');

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
(6, 'DISABLE_EMERGENCY_LOCKDOWN', 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'Under Maintenance', 1, 0, NULL, NULL, 2, '::1', '2026-08-17 02:27:10');

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
(51, 2, NULL, NULL, 'Report Status Update', 'New reports pending review', 'There are 5 new waste reports pending your review.', 1, 0, '2026-07-26 03:53:32');

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
  `guest_phone` varchar(20) DEFAULT NULL,
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

INSERT INTO `reports` (`id`, `resident_id`, `reporter_type`, `tracking_number`, `guest_name`, `guest_phone`, `reporter_latitude`, `reporter_longitude`, `location_plausibility`, `is_duplicate`, `description`, `latitude`, `longitude`, `location_verified`, `submission_date`, `reviewed_by`, `created_at`, `updated_at`, `category_id`, `quantity_id`, `condition_id`, `status_id`, `purok_id`, `location`, `rejected_reason`, `support_count`) VALUES
(1, 3, 'resident', 'WRS-2026-32459', NULL, NULL, NULL, NULL, 'plausible', 0, 'Overflowing public concrete waste bin near the chapel. Trash spilling onto the sidewalk and causing unpleasant odor in the morning.', 15.56045000, 120.80490000, 0, '2026-08-14 08:30:00', NULL, '2026-08-14 00:30:00', '2026-08-14 08:30:00', 2, 2, 3, 2, 1, 'Near Purok 1 Community Chapel, Corner Mabini St.', NULL, 12),
(2, 3, 'resident', 'WRS-2026-31419', NULL, NULL, NULL, NULL, 'plausible', 0, 'May nagtapon ng mga sirang sako at halo-halong plastic waste sa tabi ng kanal. May mga lumulutang na plastic bottles.', 15.55980000, 120.80410000, 0, '2026-08-16 09:15:00', NULL, '2026-08-16 01:15:00', '2026-08-16 09:15:00', 1, 3, 1, 1, 1, 'Purok 1 Irrigation Canal Path, Rizal Street', NULL, 10),
(3, 3, 'resident', 'WRS-2026-96649', NULL, NULL, NULL, NULL, 'plausible', 0, 'Discarded paint buckets, chemical thinner cans, and fluorescent tubes left beside the health center fence.', 15.56110000, 120.80690000, 0, '2026-08-15 14:20:00', NULL, '2026-08-15 06:20:00', '2026-08-15 14:20:00', 6, 1, 4, 2, 1, 'Purok 1 Health Center perimeter alleyway', NULL, 10),
(4, 3, 'resident', 'WRS-2026-33101', NULL, NULL, NULL, NULL, 'plausible', 0, 'Regular household trash bins left uncollected since Tuesday. Bags are beginning to pile up and need immediate collection.', 15.56140000, 120.80710000, 0, '2026-08-12 11:00:00', NULL, '2026-08-12 03:00:00', '2026-08-12 11:00:00', 3, 2, 2, 4, 1, 'Bonifacio St. alley near Purok 1 Day Care Center', NULL, 4),
(5, 3, 'resident', 'WRS-2026-80100', NULL, NULL, NULL, NULL, 'plausible', 0, 'Semento, graba, at sirang hollow blocks na iniwan matapos ang fencing repair. Nakaharang sa daanan ng mga tricycle.', 15.56010000, 120.80440000, 0, '2026-08-15 16:45:00', NULL, '2026-08-15 08:45:00', '2026-08-15 16:45:00', 4, 3, 6, 3, 1, 'Corner Mabini St. & Purok 1 Barangay Road', NULL, 3),
(6, 3, 'resident', 'WRS-2026-15520', NULL, NULL, NULL, NULL, 'plausible', 0, 'Piles of dry bamboo cuttings, coconut fronds, and pruned mango branches after weekend neighborhood clearing.', 15.55910000, 120.80350000, 0, '2026-08-10 10:30:00', NULL, '2026-08-10 02:30:00', '2026-08-10 10:30:00', 5, 3, 2, 4, 1, 'Purok 1 West Boundary, Riverside Pathway', NULL, 5),
(7, 3, 'resident', 'WRS-2026-81474', NULL, NULL, NULL, NULL, 'plausible', 0, 'Tambak ng plastic wrappers, sako ng ipa, at sirang karton sa tabi ng feeder road. Kailangan mahakot ng utility truck.', 15.56300000, 120.82400000, 0, '2026-08-17 07:45:00', NULL, '2026-08-16 23:45:00', '2026-08-17 07:45:00', 1, 3, 1, 1, 2, 'Purok 2 North Access Road, near Rice Mill', NULL, 7),
(8, 3, 'resident', 'WRS-2026-32379', NULL, NULL, NULL, NULL, 'plausible', 0, 'Overflowing steel garbage drums after barangay sports event. Single-use plastic cups and snack packs scattered.', 15.56200000, 120.82200000, 0, '2026-08-16 13:10:00', NULL, '2026-08-16 05:10:00', '2026-08-16 13:10:00', 2, 2, 3, 2, 2, 'Purok 2 Secondary Alley, near Purok Basketball Half-court', NULL, 1),
(9, 3, 'resident', 'WRS-2026-53660', NULL, NULL, NULL, NULL, 'plausible', 0, 'Baradong culvert dahil sa mga naipit na sanga at plastic sacks ng domestic trash. Bahagyang tumataas ang tubig.', 15.56050000, 120.81600000, 0, '2026-08-15 15:30:00', NULL, '2026-08-15 07:30:00', '2026-08-15 15:30:00', 7, 3, 5, 3, 2, 'Purok 2 East Sub-feeder Canal Road', NULL, 2),
(10, 3, 'resident', 'WRS-2026-31044', NULL, NULL, NULL, NULL, 'plausible', 0, 'Leftover concrete masonry fragments and broken hollow blocks after wall reconstruction. Need clearing.', 15.55850000, 120.81800000, 0, '2026-08-11 09:00:00', NULL, '2026-08-11 01:00:00', '2026-08-11 09:00:00', 4, 2, 4, 4, 2, 'Purok 2 South Sector, Boundary Pathway', NULL, 2),
(11, 3, 'resident', 'WRS-2026-86577', NULL, NULL, NULL, NULL, 'plausible', 0, 'Tree pruning debris and dry weeds from farm boundary trimming. Cleaned and processed for communal compost.', 15.56500000, 120.82500000, 0, '2026-08-09 14:00:00', NULL, '2026-08-09 06:00:00', '2026-08-09 14:00:00', 5, 4, 2, 4, 2, 'Purok 2 Farm-to-Market Road, Kilometro 2', NULL, 6),
(12, 3, 'resident', 'WRS-2026-35263', NULL, NULL, NULL, NULL, 'plausible', 0, 'Missed household waste collection along northern corner. Scheduled for priority dispatch this afternoon.', 15.56600000, 120.82300000, 0, '2026-08-16 11:20:00', NULL, '2026-08-16 03:20:00', '2026-08-16 11:20:00', 3, 2, 2, 2, 2, 'Purok 2 North Gate Entry Point', NULL, 3),
(13, 3, 'resident', 'WRS-2026-85731', NULL, NULL, NULL, NULL, 'plausible', 0, 'Commercial waste sacks and fast-food packaging discarded overnight beside the waiting shed bench.', 15.55600000, 120.80950000, 0, '2026-08-17 06:30:00', NULL, '2026-08-16 22:30:00', '2026-08-17 06:30:00', 1, 2, 1, 1, 3, 'Purok 3 Central Avenue, near Purok Waiting Shed', NULL, 5),
(14, 3, 'resident', 'WRS-2026-95842', NULL, NULL, NULL, NULL, 'plausible', 0, 'Baradong drainage inlet dahil sa mga naipong mineral water bottles at single-use plastic cups. Kailangan ng declogging.', 15.55450000, 120.80750000, 0, '2026-08-16 16:15:00', NULL, '2026-08-16 08:15:00', '2026-08-16 16:15:00', 7, 2, 5, 2, 3, 'Purok 3 Drainage Culvert Junction', NULL, 7),
(15, 3, 'resident', 'WRS-2026-23288', NULL, NULL, NULL, NULL, 'plausible', 0, 'Concrete debris, plaster residue, and discarded wall tiles left on the roadside. Road team dispatched for hauling.', 15.55350000, 120.80650000, 0, '2026-08-15 10:00:00', NULL, '2026-08-15 02:00:00', '2026-08-15 10:00:00', 4, 3, 6, 3, 3, 'Purok 3 South Road, near Purok Outpost', NULL, 11),
(16, 3, 'resident', 'WRS-2026-69669', NULL, NULL, NULL, NULL, 'plausible', 0, 'Biodegradable vegetable refuse and rotten fruit crates from weekend market vendors. Fully cleared and sanitized.', 15.55700000, 120.80880000, 0, '2026-08-13 15:40:00', NULL, '2026-08-13 07:40:00', '2026-08-13 15:40:00', 5, 3, 4, 4, 3, 'Purok 3 Market Feeder Road', NULL, 11),
(17, 3, 'resident', 'WRS-2026-58237', NULL, NULL, NULL, NULL, 'plausible', 0, 'Two household trash bins overflowing due to delayed pickup schedule. Resident requests immediate sweep.', 15.55500000, 120.81100000, 0, '2026-08-17 08:50:00', NULL, '2026-08-17 00:50:00', '2026-08-17 08:50:00', 2, 1, 3, 1, 3, 'Purok 3 East Alleyway, Residential Block 4', NULL, 6),
(18, 3, 'resident', 'WRS-2026-16026', NULL, NULL, NULL, NULL, 'plausible', 0, 'Old automotive batteries and motor oil containers left near mechanic shop. Hazardous chemicals disposed safely.', 15.55800000, 120.80800000, 0, '2026-08-12 13:25:00', NULL, '2026-08-12 05:25:00', '2026-08-12 13:25:00', 6, 2, 4, 4, 3, 'Purok 3 Main Crossing, Rizal Extension', NULL, 6),
(19, 3, 'resident', 'WRS-2026-23531', NULL, NULL, NULL, NULL, 'plausible', 0, 'Plastic snack packaging, styrofoam meal boxes, and plastic bottles scattered outside the campus fence.', 15.55800000, 120.82800000, 0, '2026-08-16 15:00:00', NULL, '2026-08-16 07:00:00', '2026-08-16 15:00:00', 1, 2, 4, 2, 4, 'Purok 4 Barangay Road, near High School Extension', NULL, 10),
(20, 3, 'resident', 'WRS-2026-42400', NULL, NULL, NULL, NULL, 'plausible', 0, 'Truck unloaded construction soil, broken pavement chunks, and asphalt debris blocking half of the bypass road.', 15.55600000, 120.82500000, 0, '2026-08-15 11:30:00', NULL, '2026-08-15 03:30:00', '2026-08-15 11:30:00', 4, 4, 6, 3, 4, 'Purok 4 Agri-Industrial Bypass, Corner Sitio Riverside', NULL, 1),
(21, 3, 'resident', 'WRS-2026-62850', NULL, NULL, NULL, NULL, 'plausible', 0, 'Silt and domestic waste accumulation restricting water flow to agricultural plots. Declogging operation planned.', 15.55400000, 120.82800000, 0, '2026-08-17 09:20:00', NULL, '2026-08-17 01:20:00', '2026-08-17 09:20:00', 7, 3, 5, 1, 4, 'Purok 4 Irrigation Gate 2 Canal', NULL, 13),
(22, 3, 'resident', 'WRS-2026-42876', NULL, NULL, NULL, NULL, 'plausible', 0, 'Piles of pruned acacia branches and garden weeds. Hauled by the barangay dump truck for organic processing.', 15.56000000, 120.82700000, 0, '2026-08-14 16:10:00', NULL, '2026-08-14 08:10:00', '2026-08-14 16:10:00', 5, 3, 2, 4, 4, 'Purok 4 Communal Nursery Perimeter', NULL, 1),
(23, 3, 'resident', 'WRS-2026-42885', NULL, NULL, NULL, NULL, 'plausible', 0, 'Community waste receptacle completely filled. Commuters dropping waste around the perimeter base.', 15.55700000, 120.82200000, 0, '2026-08-16 10:45:00', NULL, '2026-08-16 02:45:00', '2026-08-16 10:45:00', 2, 2, 3, 2, 4, 'Purok 4 Public Tricycle Terminal', NULL, 13),
(24, 3, 'resident', 'WRS-2026-22985', NULL, NULL, NULL, NULL, 'plausible', 0, 'Household garbage bags uncollected for 4 days. Waste properly collected and disposed by sanitation team.', 15.55300000, 120.82000000, 0, '2026-08-13 14:15:00', NULL, '2026-08-13 06:15:00', '2026-08-13 14:15:00', 3, 2, 2, 4, 4, 'Purok 4 South Access Way, Sitio Ilang-Ilang', NULL, 12),
(25, 3, 'resident', 'WRS-2026-83871', NULL, NULL, NULL, NULL, 'plausible', 0, 'Multiple sacks of commercial poultry feeds and torn plastic sheeting dumped on the road shoulder.', 15.55300000, 120.80000000, 0, '2026-08-17 08:00:00', NULL, '2026-08-17 00:00:00', '2026-08-17 08:00:00', 1, 3, 1, 1, 5, 'Purok 5 Main Road, near Barangay Boundary Marker', NULL, 2),
(26, 3, 'resident', 'WRS-2026-71513', NULL, NULL, NULL, NULL, 'plausible', 0, 'Baradong daluyan ng patubig dahil sa naipong plastic containers at sirang lambat. Apektado ang daloy ng tubig.', 15.55150000, 120.79800000, 0, '2026-08-16 14:50:00', NULL, '2026-08-16 06:50:00', '2026-08-16 14:50:00', 7, 2, 5, 2, 5, 'Purok 5 West Feeder Canal, near Rice Field Entry', NULL, 8),
(27, 3, 'resident', 'WRS-2026-18646', NULL, NULL, NULL, NULL, 'plausible', 0, 'Leftover concrete masonry fragments and broken culvert pieces after ditch repair. Cleared by utility crew.', 15.55400000, 120.80300000, 0, '2026-08-11 11:15:00', NULL, '2026-08-11 03:15:00', '2026-08-11 11:15:00', 4, 3, 6, 4, 5, 'Purok 5 East Perimeter Road, near Barangay Multi-purpose Hall', NULL, 3),
(28, 3, 'resident', 'WRS-2026-83764', NULL, NULL, NULL, NULL, 'plausible', 0, 'Piles of dry bamboo cuttings, pruned ipil-ipil branches, and dried foliage ready for municipal collection.', 15.55200000, 120.80200000, 0, '2026-08-15 13:00:00', NULL, '2026-08-15 05:00:00', '2026-08-15 13:00:00', 5, 2, 2, 3, 5, 'Purok 5 Central Crossing, Sitio Pag-asa', NULL, 6),
(29, 3, 'resident', 'WRS-2026-42164', NULL, NULL, NULL, NULL, 'plausible', 0, 'Overflowing communal garbage drum near the residential alleyway. Stray dogs tearing through discarded sacks.', 15.55500000, 120.80500000, 0, '2026-08-17 10:10:00', NULL, '2026-08-17 02:10:00', '2026-08-17 10:10:00', 2, 2, 3, 1, 5, 'Purok 5 North Crossing, near Purok 1 & 5 Boundary', NULL, 6),
(30, 3, 'resident', 'WRS-2026-69008', NULL, NULL, NULL, NULL, 'plausible', 0, 'Missed regular collection for household garbage bags. Swept and hauled by special barangay truck deployment.', 15.55350000, 120.79500000, 0, '2026-08-12 16:30:00', NULL, '2026-08-12 08:30:00', '2026-08-12 16:30:00', 3, 2, 2, 4, 5, 'Purok 5 Far West Access, Sitio Maligaya', NULL, 3);

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
(1, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', 'Rosa Medina', 'Barangay Secretary', '', 2, '2026-08-17 10:16:18', '/brgy-waste-app-v3/public/uploads/logos/rep_logo_left_1786932978.jpg', '/brgy-waste-app-v3/public/uploads/logos/rep_logo_right_1786932978.jpg', 'Province of Nueva Ecija · Municipality of Quezon', 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', '', 'Punong Barangay'),
(2, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:27:25', NULL, NULL, NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay'),
(3, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:30:39', NULL, NULL, NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay'),
(4, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:31:01', NULL, NULL, NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay'),
(5, 'Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', NULL, NULL, '2026-07-25 14:35:15', NULL, NULL, NULL, 'Republic of the Philippines', 'Office of the Barangay Solid Waste Management Committee', NULL, 'Punong Barangay');

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
(30, 30, '6a6e76cf63dd9_Screenshot 2025-06-22 190140.png', 1, '2026-08-12 16:30:00');

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
(17, 2, 'analytics_2026-08-16_19-23-36', '', 'pdf', '{\"date_from\":\"2026-07-17\",\"date_to\":\"2026-08-16\",\"category\":0,\"purok\":0,\"status\":\"\",\"quantity\":0,\"condition\":0,\"trend_granularity\":\"monthly\"}', 8, '2026-08-17 01:23:36');

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
(1, 0, 'emergency', 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.', 'Under Maintenance', NULL, NULL, 1, 2, '2026-08-17 10:27:10');

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
(388, 20, '09951281511', '220447', 'login_2fa', '2026-08-10 12:22:56', 1, 0, '2026-08-10 04:12:56'),
(436, 23, 'limuelle.neust@gmail.com', '817689', 'login_2fa', '2026-08-17 17:21:03', 0, 0, '2026-08-17 09:11:03'),
(441, 3, 'floressktt11@gmail.com', '725865', 'login_2fa', '2026-08-18 00:34:34', 1, 0, '2026-08-17 16:24:34'),
(442, 2, 'floreshans.neust@gmail.com', '406182', 'login_2fa', '2026-08-18 00:39:13', 1, 0, '2026-08-17 16:29:13');

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
(2, 'Secretary Rose', NULL, NULL, NULL, 'resident', 'Barangay Hall', '09123456788', 'floreshans.neust@gmail.com', '$2y$10$NFKPGxb7Hord13zMb9YLzeQOC8Te8geDMwW21XM48pMsuYDx49qSy', NULL, NULL, 1, 2, 1, 'active', NULL, '2026-04-01 00:28:49', '2026-08-10 12:27:53', NULL, '/public/uploads/profiles/profile_2_1786336073.png', 0, NULL, NULL),
(3, 'Hans Flores', NULL, NULL, NULL, 'resident', 'brgy.testing.testing', '09951281511', 'floressktt11@gmail.com', '$2y$10$y03L/tBgrsBFqgLuFMFRYOos6Y.svcXlfru15rNSc8dcg2cYLVew2', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-04-01 01:05:23', '2026-08-15 02:34:16', NULL, '/public/uploads/profiles/profile_3_1786340113.jpg', 0, NULL, NULL),
(15, 'asdasdadad', NULL, NULL, NULL, 'resident', '232323232323', '09951281511', 'floererererer@gmail.com', '$2y$10$fL/0SLQG2zLnUEniuGsTU.ulSh4yLbmpwPMkWFLIbqc2OU1xF1Niq', '/uploads/ids/front_6a6252ecf1d05.jpg', '/uploads/ids/back_6a6252ed00579.jpg', 3, 6, 1, 'active', NULL, '2026-07-24 01:44:13', '2026-08-09 05:54:43', NULL, NULL, 0, NULL, NULL),
(16, 'test email otp', NULL, NULL, NULL, 'resident', 'awwsdasdad', '09951281511', 'testingotp@gmail.com', '$2y$10$NlxWm4KHBTA2PPazo0MN7ehPrv3RmAtUukEAO8QavMGJAmG.0znou', '/uploads/ids/front_6a6258475b015.jpg', '/uploads/ids/back_6a6258475be3f.jpg', 3, 6, 1, 'active', NULL, '2026-07-24 02:07:03', '2026-08-08 01:10:08', NULL, NULL, 0, NULL, NULL),
(18, 'Hans Limuelle Flores', NULL, NULL, 'hansflores', 'resident', 'Barangay Dulong Bayan', '09171234567', 'floreshanslimuelle.neust@gmail.com', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', NULL, NULL, 2, 3, 1, 'active', NULL, '2026-07-26 13:35:01', '2026-07-26 13:47:54', NULL, NULL, 0, NULL, NULL),
(19, 'Cedrick Umali', NULL, NULL, 'umalicedrick', 'resident', '', '09664185246', 'umalicedrick29@gmail.com', '$2y$10$/6ZSX1XKD5fMpEwneahyIOU8IfbZxxWwoFfKt48t8Z0Z4PmY3Pg3O', NULL, NULL, 3, 6, 1, 'active', NULL, '2026-08-02 20:15:51', '2026-08-08 11:03:30', NULL, NULL, 0, NULL, NULL),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2923;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `estimated_quantities`
--
ALTER TABLE `estimated_quantities`
  MODIFY `quantity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `guest_otp_tokens`
--
ALTER TABLE `guest_otp_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `guest_sms_rate_limits`
--
ALTER TABLE `guest_sms_rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `heatmap_settings`
--
ALTER TABLE `heatmap_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `maintenance_history`
--
ALTER TABLE `maintenance_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `map_landmarks`
--
ALTER TABLE `map_landmarks`
  MODIFY `landmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=443;

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
