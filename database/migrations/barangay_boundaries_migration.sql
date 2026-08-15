-- Migration: Barangay Boundaries & Map Customization
-- Date: 2026-08-16

CREATE TABLE IF NOT EXISTS `barangay_boundaries` (
  `boundary_id` int(11) NOT NULL AUTO_INCREMENT,
  `barangay_id` int(11) NOT NULL DEFAULT 1,
  `polygon_geometry` geometry NOT NULL,
  `center_latitude` decimal(10,8) DEFAULT 15.55800000,
  `center_longitude` decimal(11,8) DEFAULT 120.80300000,
  `default_zoom` int(11) DEFAULT 15,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`boundary_id`),
  KEY `idx_barangay_id` (`barangay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
