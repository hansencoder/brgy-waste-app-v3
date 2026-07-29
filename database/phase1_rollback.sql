-- ============================================================
-- PHASE 1 ROLLBACK
-- This script reverts the Phase 1 migration
-- ============================================================

USE brgy_waste_db;

SET FOREIGN_KEY_CHECKS = 0;

-- Drop new tables
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS positions;
DROP TABLE IF EXISTS barangays;
DROP TABLE IF EXISTS barangay_boundaries;
DROP TABLE IF EXISTS puroks;
DROP TABLE IF EXISTS purok_boundaries;
DROP TABLE IF EXISTS waste_categories;
DROP TABLE IF EXISTS estimated_quantities;
DROP TABLE IF EXISTS waste_conditions;
DROP TABLE IF EXISTS report_statuses;
DROP TABLE IF EXISTS report_photos;
DROP TABLE IF EXISTS report_supports;
DROP TABLE IF EXISTS collection_schedules;
DROP TABLE IF EXISTS collection_schedule_puroks;
DROP TABLE IF EXISTS announcement_visibilities;
DROP TABLE IF EXISTS notification_types;
DROP TABLE IF EXISTS report_settings;
DROP TABLE IF EXISTS heatmap_settings;
DROP TABLE IF EXISTS report_generation_settings;
DROP TABLE IF EXISTS map_landmarks;
DROP VIEW IF EXISTS v_reports_full;

-- Restore old columns
ALTER TABLE users 
    DROP COLUMN IF EXISTS role_id,
    DROP COLUMN IF EXISTS position_id,
    DROP COLUMN IF EXISTS purok_id,
    DROP COLUMN IF EXISTS middle_name,
    DROP COLUMN IF EXISTS suffix,
    DROP COLUMN IF EXISTS phone_normalized,
    DROP COLUMN IF EXISTS email_verified,
    DROP COLUMN IF EXISTS otp_verified_at,
    DROP COLUMN IF EXISTS deleted_at;

ALTER TABLE reports 
    DROP COLUMN IF EXISTS category_id,
    DROP COLUMN IF EXISTS quantity_id,
    DROP COLUMN IF EXISTS condition_id,
    DROP COLUMN IF EXISTS status_id,
    DROP COLUMN IF EXISTS purok_id,
    DROP COLUMN IF EXISTS location,
    DROP COLUMN IF EXISTS rejected_reason,
    DROP COLUMN IF EXISTS support_count;

SET FOREIGN_KEY_CHECKS = 1;

SELECT '✅ Rollback complete!' AS status;