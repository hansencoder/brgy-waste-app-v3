-- ============================================
-- DATABASE MIGRATION SCRIPT
-- Run this to update existing database to new schema
-- This preserves existing data where possible
-- ============================================

USE brgy_waste_db;

-- ============================================
-- STEP 1: RENAME AND MODIFY USERS TABLE
-- ============================================

-- Rename columns in users table
ALTER TABLE users 
    CHANGE COLUMN full_name name VARCHAR(100) NOT NULL,
    CHANGE COLUMN contact_number phone_number VARCHAR(20) NOT NULL,
    CHANGE COLUMN password_hash password VARCHAR(255) NOT NULL;

-- Remove reports_count column (no longer in schema)
ALTER TABLE users DROP COLUMN IF EXISTS reports_count;

-- Modify status to VARCHAR instead of ENUM (more flexible)
ALTER TABLE users MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending';

-- Add last_login column
ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login DATETIME DEFAULT NULL AFTER status;

-- ============================================
-- STEP 2: RENAME AND MODIFY WASTE_REPORTS TABLE
-- ============================================

-- Rename table
RENAME TABLE waste_reports TO reports;

-- Drop is_out_of_bounds (not in new schema)
ALTER TABLE reports DROP COLUMN IF EXISTS is_out_of_bounds;

-- Add new columns first
ALTER TABLE reports 
    ADD COLUMN IF NOT EXISTS location_verified BOOLEAN DEFAULT FALSE AFTER longitude,
    ADD COLUMN IF NOT EXISTS submission_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER status,
    ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER submission_date;

-- Drop admin_remark (was TEXT, now we need INT for reviewed_by)
ALTER TABLE reports DROP COLUMN IF EXISTS admin_remark;

-- Add reviewed_by as new column
ALTER TABLE reports 
    ADD COLUMN IF NOT EXISTS reviewed_by INT DEFAULT NULL AFTER updated_at;

-- Now add foreign key for reviewed_by
ALTER TABLE reports 
    ADD CONSTRAINT fk_reports_reviewed_by 
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL;

-- ============================================
-- STEP 3: CREATE NEW TABLES
-- ============================================

-- Create report_status_history table
CREATE TABLE IF NOT EXISTS report_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    previous_status VARCHAR(50) NOT NULL,
    new_status VARCHAR(50) NOT NULL,
    remark TEXT DEFAULT NULL,
    changed_by INT NOT NULL,
    changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (report_id),
    INDEX (changed_at)
);

-- Create report_flags table
CREATE TABLE IF NOT EXISTS report_flags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    flag_reason VARCHAR(255) NOT NULL,
    flagged_by INT NOT NULL,
    flagged_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    reviewed_by INT DEFAULT NULL,
    reviewed_at DATETIME DEFAULT NULL,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    FOREIGN KEY (flagged_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX (report_id),
    INDEX (flagged_at)
);

-- Create account_deactivations table
CREATE TABLE IF NOT EXISTS account_deactivations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reason TEXT NOT NULL,
    deactivated_by INT NOT NULL,
    deactivated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (deactivated_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id),
    INDEX (deactivated_at)
);

-- Create announcements table
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (created_at)
);

-- ============================================
-- STEP 4: MIGRATE NOTIFICATIONS TABLE
-- ============================================

-- Backup existing notifications
CREATE TABLE IF NOT EXISTS notifications_backup AS SELECT * FROM notifications;

-- Drop old notifications table
DROP TABLE IF EXISTS notifications;

-- Create new notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    report_id INT DEFAULT NULL,
    announcement_id INT DEFAULT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    send_to_all BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE,
    INDEX (user_id),
    INDEX (report_id),
    INDEX (created_at)
);

-- Migrate old notifications to new structure
-- Convert old 'announcement' type to new structure
INSERT INTO announcements (title, content, created_by, created_at)
SELECT title, message, 1, created_at 
FROM notifications_backup 
WHERE type = 'announcement';

-- Convert old 'notification' type to new structure
INSERT INTO notifications (user_id, type, title, content, is_read, created_at)
SELECT user_id, type, title, message, is_read, created_at 
FROM notifications_backup 
WHERE type = 'notification';

-- ============================================
-- STEP 5: MIGRATE REPORT SUMMARIES TABLE
-- ============================================

-- Backup existing summaries
CREATE TABLE IF NOT EXISTS report_summaries_backup AS SELECT * FROM report_summaries;

-- Drop old table
DROP TABLE IF EXISTS report_summaries;

-- Create new report_summaries table
CREATE TABLE IF NOT EXISTS report_summaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    generated_by INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    filters TEXT DEFAULT NULL,
    total_reports INT DEFAULT 0,
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (generated_at)
);

-- ============================================
-- STEP 6: MIGRATE AUDIT LOGS TABLE
-- ============================================

-- Backup existing audit logs
CREATE TABLE IF NOT EXISTS audit_logs_backup AS SELECT * FROM audit_logs;

-- Drop old table
DROP TABLE IF EXISTS audit_logs;

-- Create new audit_logs table
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    affected_record VARCHAR(255) NOT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    result VARCHAR(50) NOT NULL DEFAULT 'success',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX (action),
    INDEX (created_at),
    INDEX (user_id)
);

-- ============================================
-- STEP 7: MIGRATE MFA TOKENS TABLE
-- ============================================

-- Rename table
RENAME TABLE IF EXISTS mfa_tokens TO two_factor_tokens;

-- Add new columns
ALTER TABLE two_factor_tokens 
    ADD COLUMN IF NOT EXISTS is_used BOOLEAN DEFAULT FALSE AFTER expires_at;

-- ============================================
-- STEP 8: UPDATE TIMESTAMP COLUMNS
-- ============================================

-- Change all TIMESTAMP to DATETIME for consistency
ALTER TABLE users 
    MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    MODIFY updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ============================================
-- MIGRATION COMPLETE
-- ============================================

SELECT 'Migration completed successfully!' AS status;
SELECT 'Backup tables created: notifications_backup, report_summaries_backup, audit_logs_backup' AS info;
SELECT 'Please verify data integrity and remove backup tables when confident.' AS warning;
