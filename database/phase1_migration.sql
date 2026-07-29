-- ============================================================
-- PHASE 1 MIGRATION – FINAL CORRECTED VERSION
-- ============================================================

USE brgy_waste_db;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. CREATE NEW TABLES (skip if they exist)
-- ============================================================

CREATE TABLE IF NOT EXISTS roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO roles (role_name, description) VALUES
('Administrator', 'Full system access and configuration'),
('Supervisor', 'Monitoring and analytics access'),
('Resident', 'Report submission and tracking access');

CREATE TABLE IF NOT EXISTS positions (
    position_id INT PRIMARY KEY AUTO_INCREMENT,
    position_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO positions (position_name, description) VALUES
('Barangay Captain', 'Chief executive of the barangay'),
('Barangay Secretary', 'Administrative officer of the barangay'),
('Barangay Kagawad', 'Elected barangay council member'),
('Environmental Officer', 'Responsible for environmental programs'),
('Waste Collection Coordinator', 'Manages waste collection operations'),
('Resident', 'Registered barangay resident');

CREATE TABLE IF NOT EXISTS barangays (
    barangay_id INT PRIMARY KEY AUTO_INCREMENT,
    barangay_name VARCHAR(100) NOT NULL,
    municipality VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    region VARCHAR(100) NOT NULL,
    official_address TEXT,
    contact_number VARCHAR(20),
    official_email VARCHAR(100),
    barangay_logo VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO barangays (barangay_id, barangay_name, municipality, province, region) 
SELECT 1, 'Dulong Bayan', 'Talavera', 'Nueva Ecija', 'Central Luzon'
WHERE NOT EXISTS (SELECT 1 FROM barangays WHERE barangay_id = 1);

CREATE TABLE IF NOT EXISTS puroks (
    purok_id INT PRIMARY KEY AUTO_INCREMENT,
    barangay_id INT NOT NULL,
    purok_name VARCHAR(50) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (barangay_id) REFERENCES barangays(barangay_id) ON DELETE CASCADE,
    UNIQUE KEY (barangay_id, purok_name)
);

INSERT IGNORE INTO puroks (barangay_id, purok_name, sort_order) VALUES
(1, 'Purok 1', 1),
(1, 'Purok 2', 2),
(1, 'Purok 3', 3),
(1, 'Purok 4', 4),
(1, 'Purok 5', 5);

CREATE TABLE IF NOT EXISTS waste_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    recommended_action TEXT,
    severity_level ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    icon VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO waste_categories (category_name, description, recommended_action, severity_level) VALUES
('Illegal Dumping', 'Waste disposed in unauthorized areas', 'Conduct site inspection and investigate recurring dumping activities', 'high'),
('Overflowing Garbage Bin', 'Garbage bins filled beyond capacity', 'Increase collection frequency and evaluate need for additional bins', 'medium'),
('Uncollected Garbage', 'Waste not collected on scheduled day', 'Prioritize waste collection for the affected area', 'medium'),
('Construction Waste', 'Waste from construction or demolition activities', 'Coordinate with construction site owners for proper disposal', 'low'),
('Yard Waste', 'Leaves, branches, and garden waste', 'Schedule yard waste collection or composting', 'low'),
('Hazardous Waste', 'Waste containing hazardous materials', 'Coordinate with environmental agency for proper handling', 'critical'),
('Blocking Drainage', 'Waste blocking drainage or waterways', 'Coordinate immediate clearing to reduce flooding risks', 'high'),
('Blocking Roadway', 'Waste blocking public roads', 'Immediate removal required for public safety', 'critical'),
('Others', 'Waste that does not fit other categories', 'Review and assign appropriate category', 'medium');

CREATE TABLE IF NOT EXISTS estimated_quantities (
    quantity_id INT PRIMARY KEY AUTO_INCREMENT,
    quantity_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO estimated_quantities (quantity_name, description, sort_order) VALUES
('Small', '1-2 garbage bags', 1),
('Medium', '3-5 garbage bags', 2),
('Large', '6-10 garbage bags', 3),
('Very Large', 'More than 10 garbage bags', 4);

CREATE TABLE IF NOT EXISTS waste_conditions (
    condition_id INT PRIMARY KEY AUTO_INCREMENT,
    condition_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO waste_conditions (condition_name, description) VALUES
('Newly Dumped', 'Recently disposed waste'),
('Accumulating', 'Waste accumulating over time'),
('Overflowing', 'Waste exceeding containment'),
('Scattered', 'Waste spread over an area'),
('Blocking Drainage', 'Waste obstructing water flow'),
('Blocking Roadway', 'Waste obstructing public roads');

CREATE TABLE IF NOT EXISTS report_statuses (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    color_code VARCHAR(7) DEFAULT '#F59E0B',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO report_statuses (status_name, description, color_code) VALUES
('Pending', 'Report submitted and awaiting verification', '#F59E0B'),
('Verified', 'Report confirmed as valid waste concern', '#3B82F6'),
('In Progress', 'Report currently being addressed', '#8B5CF6'),
('Resolved', 'Waste concern has been addressed', '#10B981'),
('Rejected', 'Report identified as invalid', '#EF4444');

CREATE TABLE IF NOT EXISTS report_photos (
    photo_id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    INDEX (report_id)
);

CREATE TABLE IF NOT EXISTS report_supports (
    support_id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    user_id INT NOT NULL,
    supported_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (report_id, user_id),
    INDEX (report_id)
);

CREATE TABLE IF NOT EXISTS collection_schedules (
    schedule_id INT PRIMARY KEY AUTO_INCREMENT,
    collection_day ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    waste_type VARCHAR(100),
    status ENUM('active', 'inactive', 'special') DEFAULT 'active',
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (collection_day)
);

CREATE TABLE IF NOT EXISTS collection_schedule_puroks (
    schedule_purok_id INT PRIMARY KEY AUTO_INCREMENT,
    schedule_id INT NOT NULL,
    purok_id INT NOT NULL,
    FOREIGN KEY (schedule_id) REFERENCES collection_schedules(schedule_id) ON DELETE CASCADE,
    FOREIGN KEY (purok_id) REFERENCES puroks(purok_id) ON DELETE CASCADE,
    UNIQUE KEY (schedule_id, purok_id)
);

CREATE TABLE IF NOT EXISTS announcement_visibilities (
    visibility_id INT PRIMARY KEY AUTO_INCREMENT,
    visibility_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO announcement_visibilities (visibility_name, description) VALUES
('Public', 'Visible to all visitors including non-registered users'),
('Registered', 'Visible only to registered users (Residents, Supervisors, Administrators)'),
('Internal', 'Visible only to Supervisors and Administrators');

CREATE TABLE IF NOT EXISTS notification_types (
    notification_type_id INT PRIMARY KEY AUTO_INCREMENT,
    notification_type_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO notification_types (notification_type_name, description) VALUES
('Report Status Update', 'When a report status changes'),
('New Announcement', 'When a new announcement is published'),
('Collection Schedule Update', 'When collection schedule changes'),
('Report Submitted', 'When a new report is submitted'),
('Report Verified', 'When a report is verified'),
('Report Resolved', 'When a report is resolved'),
('Account Approved', 'When a resident account is approved');

CREATE TABLE IF NOT EXISTS report_settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    photo_required BOOLEAN DEFAULT TRUE,
    allowed_file_types VARCHAR(255) DEFAULT 'jpg,jpeg,png',
    max_upload_size INT DEFAULT 5242880,
    duplicate_distance INT DEFAULT 50,
    duplicate_time_window INT DEFAULT 7,
    max_reports_per_day INT DEFAULT 10,
    enable_remarks BOOLEAN DEFAULT TRUE,
    remarks_character_limit INT DEFAULT 500,
    updated_by INT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

INSERT IGNORE INTO report_settings (photo_required, allowed_file_types, max_upload_size, duplicate_distance, duplicate_time_window, max_reports_per_day, enable_remarks, remarks_character_limit) VALUES
(1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500);

CREATE TABLE IF NOT EXISTS heatmap_settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    radius_meters INT DEFAULT 50,
    minimum_reports INT DEFAULT 3,
    low_density_color VARCHAR(7) DEFAULT '#FDE68A',
    medium_density_color VARCHAR(7) DEFAULT '#F97316',
    high_density_color VARCHAR(7) DEFAULT '#EF4444',
    updated_by INT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

INSERT IGNORE INTO heatmap_settings (radius_meters, minimum_reports, low_density_color, medium_density_color, high_density_color) VALUES
(50, 3, '#FDE68A', '#F97316', '#EF4444');

CREATE TABLE IF NOT EXISTS report_generation_settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    report_header TEXT,
    report_footer TEXT,
    signatory_name VARCHAR(255),
    signatory_position VARCHAR(255),
    disclaimer TEXT,
    updated_by INT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

INSERT IGNORE INTO report_generation_settings (report_header, report_footer, signatory_name, signatory_position) VALUES
('Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary');

CREATE TABLE IF NOT EXISTS map_landmarks (
    landmark_id INT PRIMARY KEY AUTO_INCREMENT,
    landmark_name VARCHAR(100) NOT NULL,
    landmark_type VARCHAR(50),
    latitude DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX (landmark_type)
);

CREATE TABLE IF NOT EXISTS barangay_boundaries (
    boundary_id INT PRIMARY KEY AUTO_INCREMENT,
    barangay_id INT NOT NULL,
    polygon_geometry GEOMETRY NOT NULL,
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (barangay_id) REFERENCES barangays(barangay_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    SPATIAL INDEX (polygon_geometry)
);

CREATE TABLE IF NOT EXISTS purok_boundaries (
    boundary_id INT PRIMARY KEY AUTO_INCREMENT,
    purok_id INT NOT NULL,
    polygon_geometry GEOMETRY NOT NULL,
    updated_by INT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (purok_id) REFERENCES puroks(purok_id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    SPATIAL INDEX (polygon_geometry)
);

-- ============================================================
-- 2. ADD COLUMNS TO EXISTING TABLES (skip if already exist)
-- ============================================================

-- Check if role column exists before trying to reference it
SET @has_role_column = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'brgy_waste_db' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role');

-- Add columns to users (only if they don't exist)
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS role_id INT,
    ADD COLUMN IF NOT EXISTS position_id INT,
    ADD COLUMN IF NOT EXISTS purok_id INT,
    ADD COLUMN IF NOT EXISTS middle_name VARCHAR(100),
    ADD COLUMN IF NOT EXISTS suffix VARCHAR(20),
    ADD COLUMN IF NOT EXISTS phone_normalized VARCHAR(20),
    ADD COLUMN IF NOT EXISTS email_verified BOOLEAN DEFAULT FALSE,
    ADD COLUMN IF NOT EXISTS otp_verified_at DATETIME,
    ADD COLUMN IF NOT EXISTS deleted_at DATETIME;

-- Add columns to reports
ALTER TABLE reports
    ADD COLUMN IF NOT EXISTS category_id INT,
    ADD COLUMN IF NOT EXISTS quantity_id INT,
    ADD COLUMN IF NOT EXISTS condition_id INT,
    ADD COLUMN IF NOT EXISTS status_id INT,
    ADD COLUMN IF NOT EXISTS purok_id INT,
    ADD COLUMN IF NOT EXISTS location VARCHAR(255),
    ADD COLUMN IF NOT EXISTS rejected_reason TEXT,
    ADD COLUMN IF NOT EXISTS support_count INT DEFAULT 0;

-- ============================================================
-- 3. MIGRATE DATA (only if the 'role' column exists)
-- ============================================================

-- Set default role for users without role_id
UPDATE users 
SET role_id = (SELECT role_id FROM roles WHERE role_name = 'Resident' LIMIT 1)
WHERE role_id IS NULL;

-- Set default position
UPDATE users 
SET position_id = (SELECT position_id FROM positions WHERE position_name = 'Resident' LIMIT 1)
WHERE position_id IS NULL;

-- Set default purok
UPDATE users 
SET purok_id = (SELECT purok_id FROM puroks WHERE purok_name = 'Purok 1' LIMIT 1)
WHERE purok_id IS NULL;

-- Set default status for reports
UPDATE reports 
SET status_id = (SELECT status_id FROM report_statuses WHERE status_name = 'Pending' LIMIT 1)
WHERE status_id IS NULL;

-- Set default category
UPDATE reports 
SET category_id = (SELECT category_id FROM waste_categories WHERE category_name = 'Others' LIMIT 1)
WHERE category_id IS NULL;

-- Set default quantity
UPDATE reports 
SET quantity_id = (SELECT quantity_id FROM estimated_quantities WHERE quantity_name = 'Medium' LIMIT 1)
WHERE quantity_id IS NULL;

-- Set default condition
UPDATE reports 
SET condition_id = (SELECT condition_id FROM waste_conditions WHERE condition_name = 'Accumulating' LIMIT 1)
WHERE condition_id IS NULL;

-- Migrate report photos (if photo_path column exists)
INSERT IGNORE INTO report_photos (report_id, photo_path, is_primary)
SELECT id, photo_path, 1
FROM reports
WHERE photo_path IS NOT NULL AND photo_path != '';

-- ============================================================
-- 4. ADD FOREIGN KEYS
-- ============================================================

ALTER TABLE users
    ADD CONSTRAINT IF NOT EXISTS fk_users_role_id FOREIGN KEY (role_id) REFERENCES roles(role_id),
    ADD CONSTRAINT IF NOT EXISTS fk_users_position_id FOREIGN KEY (position_id) REFERENCES positions(position_id),
    ADD CONSTRAINT IF NOT EXISTS fk_users_purok_id FOREIGN KEY (purok_id) REFERENCES puroks(purok_id);

ALTER TABLE reports
    ADD CONSTRAINT IF NOT EXISTS fk_reports_category_id FOREIGN KEY (category_id) REFERENCES waste_categories(category_id),
    ADD CONSTRAINT IF NOT EXISTS fk_reports_quantity_id FOREIGN KEY (quantity_id) REFERENCES estimated_quantities(quantity_id),
    ADD CONSTRAINT IF NOT EXISTS fk_reports_condition_id FOREIGN KEY (condition_id) REFERENCES waste_conditions(condition_id),
    ADD CONSTRAINT IF NOT EXISTS fk_reports_status_id FOREIGN KEY (status_id) REFERENCES report_statuses(status_id),
    ADD CONSTRAINT IF NOT EXISTS fk_reports_purok_id FOREIGN KEY (purok_id) REFERENCES puroks(purok_id);

-- ============================================================
-- 5. CREATE VIEW
-- ============================================================

DROP VIEW IF EXISTS v_reports_full;
CREATE VIEW v_reports_full AS
SELECT 
    r.id,
    r.resident_id,
    u.name as reporter_name,
    u.email as reporter_email,
    u.phone_number as reporter_phone,
    r.description,
    r.latitude,
    r.longitude,
    r.location,
    rs.status_name as status,
    rs.color_code as status_color,
    wc.category_name as waste_category,
    eq.quantity_name as estimated_quantity,
    wcnd.condition_name as waste_condition,
    p.purok_name as purok,
    r.submission_date,
    r.updated_at,
    r.support_count,
    COALESCE(
        (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1),
        (SELECT photo_path FROM report_photos WHERE report_id = r.id LIMIT 1)
    ) as photo_path
FROM reports r
LEFT JOIN users u ON r.resident_id = u.id
LEFT JOIN report_statuses rs ON r.status_id = rs.status_id
LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
LEFT JOIN puroks p ON r.purok_id = p.purok_id;

-- ============================================================
-- 6. VERIFICATION
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;

SELECT '✅ PHASE 1 MIGRATION COMPLETE!' AS status;
SELECT 'Total tables:', COUNT(*) FROM information_schema.tables WHERE table_schema = 'brgy_waste_db';
SELECT 'Users with role_id:', COUNT(*) FROM users WHERE role_id IS NOT NULL;
SELECT 'Reports with status_id:', COUNT(*) FROM reports WHERE status_id IS NOT NULL;
SELECT 'Reports with photos:', COUNT(*) FROM report_photos;

-- Test the view
SELECT * FROM v_reports_full LIMIT 3;