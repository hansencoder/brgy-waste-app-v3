    CREATE DATABASE IF NOT EXISTS brgy_waste_db;
    USE brgy_waste_db;

    -- ============================================
    -- USER MANAGEMENT
    -- ============================================

    -- Users table
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        address VARCHAR(255) NOT NULL,
        phone_number VARCHAR(20) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('resident', 'secretary', 'captain') NOT NULL DEFAULT 'resident',
        status VARCHAR(50) DEFAULT 'pending',
        last_login DATETIME DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX (email),
        INDEX (role),
        INDEX (status)
    );

    -- Account deactivations tracking
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

    -- ============================================
    -- REPORTS & REPORTING
    -- ============================================

    -- Waste reports
    CREATE TABLE IF NOT EXISTS reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        resident_id INT NOT NULL,
        photo_path VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        latitude DECIMAL(10,8) NOT NULL,
        longitude DECIMAL(11,8) NOT NULL,
        location_verified BOOLEAN DEFAULT FALSE,
        status VARCHAR(50) NOT NULL DEFAULT 'pending',
        submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        reviewed_by INT DEFAULT NULL,
        FOREIGN KEY (resident_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
        INDEX (status),
        INDEX (submission_date),
        INDEX (resident_id)
    );

    -- Report status history
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

    -- Report flags
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

    -- ============================================
    -- NOTIFICATIONS & ANNOUNCEMENTS
    -- ============================================

    -- Notifications
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

    -- Announcements
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
    -- REPORT SUMMARIES
    -- ============================================

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
    -- AUDIT LOGS
    -- ============================================

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
    -- TWO-FACTOR AUTHENTICATION
    -- ============================================

    CREATE TABLE IF NOT EXISTS two_factor_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(10) NOT NULL,
        expires_at DATETIME NOT NULL,
        is_used BOOLEAN DEFAULT FALSE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX (token),
        INDEX (expires_at)
    );

    -- ============================================
    -- DEFAULT ADMIN ACCOUNTS
    -- Password for both is: Password@123
    -- Hash generated via: password_hash('Password@123', PASSWORD_BCRYPT)
    -- ============================================

    INSERT IGNORE INTO users (id, name, address, phone_number, email, password, role, status) VALUES
    (1, 'Barangay Captain', 'Barangay Hall', '09123456789', 'captain@dulongbayan.ph', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', 'captain', 'active'),
    (2, 'Barangay Secretary', 'Barangay Hall', '09123456788', 'secretary@dulongbayan.ph', '$2y$10$E2mUTFGVt51XHw43Ie.kMuI9cvRZPmwbpaMR4i49KqQT5nrLASx.W', 'secretary', 'active');
