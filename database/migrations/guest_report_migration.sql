-- ============================================================
-- MIGRATION: Guest Account Waste Reporting System
-- ============================================================

-- 1. Modify reports table to support guest reports
ALTER TABLE `reports`
    MODIFY COLUMN `resident_id` INT(11) NULL,
    ADD COLUMN `reporter_type` ENUM('resident', 'guest') NOT NULL DEFAULT 'resident' AFTER `resident_id`,
    ADD COLUMN `tracking_number` VARCHAR(30) UNIQUE NULL AFTER `reporter_type`,
    ADD COLUMN `guest_name` VARCHAR(100) NULL AFTER `tracking_number`,
    ADD COLUMN `guest_phone` VARCHAR(20) NULL AFTER `guest_name`,
    ADD COLUMN `reporter_latitude` DECIMAL(10,8) NULL AFTER `guest_phone`,
    ADD COLUMN `reporter_longitude` DECIMAL(11,8) NULL AFTER `reporter_latitude`,
    ADD COLUMN `location_plausibility` ENUM('plausible', 'requires_review', 'high_risk') NOT NULL DEFAULT 'plausible' AFTER `reporter_longitude`,
    ADD COLUMN `is_duplicate` TINYINT(1) NOT NULL DEFAULT 0 AFTER `location_plausibility`;

CREATE TABLE IF NOT EXISTS `guest_otp_tokens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `phone` VARCHAR(20) NOT NULL,
    `token` VARCHAR(10) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `is_used` TINYINT(1) NOT NULL DEFAULT 0,
    `attempts` INT(11) NOT NULL DEFAULT 0,
    `ip` VARCHAR(45) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_phone` (`phone`),
    INDEX `idx_token` (`token`),
    INDEX `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `guest_sms_rate_limits` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `phone` VARCHAR(20) NOT NULL,
    `ip` VARCHAR(45) NOT NULL,
    `window_start` DATETIME NOT NULL,
    `send_count` INT(11) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_phone_window` (`phone`, `window_start`),
    INDEX `idx_ip_window` (`ip`, `window_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
