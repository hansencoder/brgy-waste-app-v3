-- ============================================================
-- Hostinger Database Update Script
-- Barangay Waste Management System
-- Run this in Hostinger phpMyAdmin (SQL Tab)
-- ============================================================

-- 1. Heatmap Settings Intervals
-- Adds customizable Low / Moderate / Severe interval ranges
ALTER TABLE `heatmap_settings`
ADD COLUMN IF NOT EXISTS `low_min` INT(11) NOT NULL DEFAULT 2,
ADD COLUMN IF NOT EXISTS `low_max` INT(11) NOT NULL DEFAULT 5,
ADD COLUMN IF NOT EXISTS `moderate_min` INT(11) NOT NULL DEFAULT 6,
ADD COLUMN IF NOT EXISTS `moderate_max` INT(11) NOT NULL DEFAULT 10,
ADD COLUMN IF NOT EXISTS `severe_min` INT(11) NOT NULL DEFAULT 11;

-- 2. Penalty Rules: Distinguish Prohibited Actions vs Penalties
ALTER TABLE `penalty_rules`
ADD COLUMN IF NOT EXISTS `rule_type` ENUM('prohibited_action', 'penalty') NOT NULL DEFAULT 'penalty' AFTER `title`;

-- Update existing prohibited actions if any
UPDATE `penalty_rules` 
SET `rule_type` = 'prohibited_action' 
WHERE `offense_no` = 0 OR `title` LIKE '%Prohibited%' OR `title` LIKE '%Illegal%' OR `title` LIKE '%Disposal%';

-- 3. Collection Guidelines & Notes Table
CREATE TABLE IF NOT EXISTS `collection_notes` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Announcements publish and expiration date support
ALTER TABLE `announcements`
ADD COLUMN IF NOT EXISTS `publish_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `cover_image`,
ADD COLUMN IF NOT EXISTS `expiration_date` DATETIME NULL DEFAULT NULL AFTER `status`,
ADD COLUMN IF NOT EXISTS `is_published` TINYINT(1) NOT NULL DEFAULT 1;

-- 5. System Maintenance Mode default
CREATE TABLE IF NOT EXISTS `system_maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0,
  `bypass_key` varchar(64) DEFAULT NULL,
  `banner_message` text DEFAULT NULL,
  `allowed_ips` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default maintenance record if missing
INSERT IGNORE INTO `system_maintenance` (`id`, `maintenance_mode`, `banner_message`) 
VALUES (1, 0, 'System is currently undergoing scheduled maintenance. Please check back shortly.');
