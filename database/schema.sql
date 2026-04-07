CREATE DATABASE IF NOT EXISTS brgy_waste_db;
USE brgy_waste_db;

-- D1: User Accounts
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('resident', 'secretary', 'captain') NOT NULL DEFAULT 'resident',
    status ENUM('pending', 'active', 'deactivated') NOT NULL DEFAULT 'pending',
    reports_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (role),
    INDEX (status)
);

-- D2: Waste Reports
CREATE TABLE IF NOT EXISTS waste_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    latitude DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL,
    is_out_of_bounds BOOLEAN DEFAULT FALSE,
    status ENUM('pending', 'verified', 'resolved') NOT NULL DEFAULT 'pending',
    admin_remark TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (resident_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (status),
    INDEX (created_at)
);

-- D3: Notifications & Announcements
-- type: 'notification' for personalized, 'announcement' for broadcast
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL, -- NULL if global announcement
    type ENUM('notification', 'announcement') NOT NULL DEFAULT 'notification',
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id),
    INDEX (created_at)
);

-- D4: Report Summaries
CREATE TABLE IF NOT EXISTS report_summaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    generated_by INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    filter_criteria TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE CASCADE
);

-- D5: Audit Logs
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL, -- Optional, some actions might not be tied to logged-in user
    action_type VARCHAR(100) NOT NULL,
    target_entity VARCHAR(100) DEFAULT NULL,
    action_details TEXT NOT NULL,
    result ENUM('success', 'failed') NOT NULL DEFAULT 'success',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX (action_type),
    INDEX (created_at)
);

-- D6: 2FA Tokens
CREATE TABLE IF NOT EXISTS mfa_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(6) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (token)
);

-- Default admin accounts — Password for both is: Password@123
-- Hash generated via: password_hash('Password@123', PASSWORD_BCRYPT)
INSERT IGNORE INTO users (id, full_name, address, contact_number, email, password_hash, role, status) VALUES 
(1, 'Barangay Captain', 'Barangay Hall', '09123456789', 'captain@dulongbayan.ph', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', 'captain', 'active'),
(2, 'Barangay Secretary', 'Barangay Hall', '09123456788', 'secretary@dulongbayan.ph', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', 'secretary', 'active');
