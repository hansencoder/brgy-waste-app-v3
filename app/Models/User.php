<?php

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // ============================================================
    // FIND USER BY EMAIL OR USERNAME (for login)
    // ============================================================
    public function findUserByEmailOrUsername($input) {
        $this->db->query('SELECT * FROM users WHERE email = :input OR username = :input OR phone_number = :input');
        $this->db->bind(':input', $input);
        return $this->db->single();
    }

    // ============================================================
    // FIND USER BY EMAIL (for registration check)
    // ============================================================
    public function findUserByEmail($email) {
        if (empty($email)) return false;
        $this->db->query('SELECT * FROM users WHERE email = :email AND email != "" AND email IS NOT NULL');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    // ============================================================
    // FIND USER BY PHONE NUMBER (for registration check)
    // ============================================================
    public function findUserByPhone($phone) {
        if (empty($phone)) return false;
        $this->db->query('SELECT * FROM users WHERE phone_number = :phone AND phone_number != "" AND phone_number IS NOT NULL');
        $this->db->bind(':phone', $phone);
        return $this->db->single();
    }
    // ============================================================
    // PASSWORD RESET / FORGOT PASSWORD METHODS
    // ============================================================

    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email AND (status != "deactivated" OR status IS NULL) LIMIT 1');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function savePasswordResetToken($user_id, $email, $token) {
        // Delete previous unused reset tokens for this user
        $this->db->query('DELETE FROM two_factor_tokens WHERE user_id = :user_id AND purpose = "password_reset"');
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();

        // Insert new token with 'password_reset' purpose and 15-minute validity
        $this->db->query('INSERT INTO two_factor_tokens (user_id, email, token, expires_at, purpose, attempts, is_used) 
                        VALUES (:user_id, :email, :token, DATE_ADD(NOW(), INTERVAL 15 MINUTE), "password_reset", 0, 0)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }

    public function validatePasswordResetToken($email, $token) {
        $this->db->query('SELECT * FROM two_factor_tokens 
                        WHERE email = :email AND token = :token AND purpose = "password_reset" 
                        AND expires_at >= NOW() AND is_used = 0 
                        ORDER BY id DESC LIMIT 1');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        return $this->db->single(); // Returns token record or false
    }

    public function markResetTokenAsUsed($token_id) {
        $this->db->query('UPDATE two_factor_tokens SET is_used = 1 WHERE id = :id');
        $this->db->bind(':id', $token_id);
        return $this->db->execute();
    }

    public function updatePassword($user_id, $new_hashed_password) {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':password', $new_hashed_password);
        $this->db->bind(':id', $user_id);
        return $this->db->execute();
    }

    // ============================================================
    // FIND USER BY USERNAME
    // ============================================================
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    // ============================================================
    // REGISTER NEW USER
    // ============================================================
    public function register($data) {
        // Default role_id = Resident (3), position_id = Resident (6), purok_id = 1 (Purok 1)
        $role_id = $data['role_id'] ?? 3;
        $position_id = $data['position_id'] ?? 6;
        $purok_id = $data['purok_id'] ?? 1;

        $this->db->query('INSERT INTO users (
            name, 
            username, 
            account_type, 
            address, 
            phone_number, 
            email, 
            password, 
            role_id, 
            position_id, 
            purok_id, 
            status
        ) VALUES (
            :name, 
            :username, 
            :account_type, 
            :address, 
            :phone_number, 
            :email, 
            :password, 
            :role_id, 
            :position_id, 
            :purok_id, 
            :status
        )');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':account_type', $data['account_type'] ?? 'resident');
        $this->db->bind(':address', $data['address'] ?? '');
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', $role_id);
        $this->db->bind(':position_id', $position_id);
        $this->db->bind(':purok_id', $purok_id);
        $this->db->bind(':status', $data['status'] ?? 'pending'); // default pending until OTP verified

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // ============================================================
    // GET USER BY ID (with relationships)
    // ============================================================
    public function getUserById($id) {
        $this->db->query('
            SELECT u.*, 
                   r.role_name, 
                   p.position_name, 
                   pk.purok_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.role_id
            LEFT JOIN positions p ON u.position_id = p.position_id
            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
            WHERE u.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // ============================================================
    // GET ALL USERS (with relationships)
    // ============================================================
    public function getAllUsers() {
        $this->db->query('
            SELECT u.*, 
                   r.role_name, 
                   p.position_name, 
                   pk.purok_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.role_id
            LEFT JOIN positions p ON u.position_id = p.position_id
            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
            ORDER BY u.created_at DESC
        ');
        return $this->db->resultSet();
    }

    // ============================================================
    // UPDATE USER STATUS
    // ============================================================
    public function updateUserStatus($id, $status) {
        $this->db->query('UPDATE users SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ============================================================
    // DELETE USER
    // ============================================================
    public function deleteUser($id) {
        // No ID files to delete anymore
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ============================================================
    // MFA / OTP METHODS (unchanged)
    // ============================================================

    public function saveMfaToken($user_id, $email, $token) {
        $this->db->query('DELETE FROM two_factor_tokens WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();

        $this->db->query('INSERT INTO two_factor_tokens (user_id, email, token, expires_at, attempts, is_used) 
                          VALUES (:user_id, :email, :token, DATE_ADD(NOW(), INTERVAL 10 MINUTE), 0, 0)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }

    public function verifyMfaToken($user_id, $token) {
        $this->db->query('SELECT * FROM two_factor_tokens 
                          WHERE user_id = :user_id AND token = :token AND expires_at >= NOW() AND is_used = 0 
                          ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':token', $token);
        $row = $this->db->single();
        if ($row) {
            $this->db->query('UPDATE two_factor_tokens SET is_used = 1 WHERE id = :id');
            $this->db->bind(':id', $row['id']);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function incrementMfaAttempts($user_id) {
        $this->db->query('SELECT * FROM two_factor_tokens WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        if (!$row) return 0;

        $attempts = (int)$row['attempts'] + 1;
        if ($attempts >= 5) {
            $this->db->query('DELETE FROM two_factor_tokens WHERE user_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $this->db->execute();
            return 5;
        }
        $this->db->query('UPDATE two_factor_tokens SET attempts = :attempts WHERE id = :id');
        $this->db->bind(':attempts', $attempts);
        $this->db->bind(':id', $row['id']);
        $this->db->execute();
        return $attempts;
    }

    // ============================================================
    // EMAIL OTP METHODS
    // ============================================================

    public function canSendEmailOtp($email, $ip) {
        // 60s cooldown on last unused token using database timestamp
        $this->db->query('SELECT TIMESTAMPDIFF(SECOND, created_at, NOW()) as elapsed 
                          FROM two_factor_tokens 
                          WHERE email = :email AND is_used = 0 
                          ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if ($row && isset($row['elapsed'])) {
            $elapsed = (int)$row['elapsed'];
            if ($elapsed >= 0 && $elapsed < 60) {
                return ['ok' => false, 'reason' => 'cooldown', 'retry_after' => (60 - $elapsed)];
            }
        }

        // Hourly limits per email/contact (max 15 per hour)
        $this->db->query('SELECT SUM(send_count) as cnt FROM email_otp_rate_limits 
                          WHERE email = :email AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':email', $email);
        $r = $this->db->single();
        if ($r && (int)$r['cnt'] >= 15) {
            return ['ok' => false, 'reason' => 'email_hourly_limit'];
        }

        // Hourly limits per IP (max 50 per hour)
        $this->db->query('SELECT SUM(send_count) as cnt FROM email_otp_rate_limits 
                          WHERE ip = :ip AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':ip', $ip);
        $r2 = $this->db->single();
        if ($r2 && (int)$r2['cnt'] >= 50) {
            return ['ok' => false, 'reason' => 'ip_hourly_limit'];
        }

        return ['ok' => true];
    }

    public function recordEmailRate($email, $ip) {
        // Clean up old rate limit records older than 24 hours
        $this->db->query('DELETE FROM email_otp_rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL 24 HOUR)');
        $this->db->execute();

        $windowStart = date('Y-m-d H:00:00');
        $this->db->query('INSERT INTO email_otp_rate_limits (email, ip, window_start, send_count) 
                          VALUES (:email, :ip, :window_start, 1) 
                          ON DUPLICATE KEY UPDATE send_count = send_count + 1');
        $this->db->bind(':email', $email);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':window_start', $windowStart);
        $this->db->execute();
    }

    public function verifyEmailOtp($email, $token) {
        $this->db->query('SELECT * FROM two_factor_tokens 
                        WHERE email = :email AND token = :token AND expires_at >= NOW() AND is_used = 0 
                        LIMIT 1');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        $row = $this->db->single();

        if ($row) {
            $this->db->query('UPDATE two_factor_tokens SET is_used = 1 WHERE id = :id');
            $this->db->bind(':id', $row['id']);
            $this->db->execute();
            return ['ok' => true, 'user_id' => $row['user_id']];
        } else {
            // Increment attempts
            $this->db->query('SELECT id, attempts FROM two_factor_tokens 
                            WHERE email = :email ORDER BY created_at DESC LIMIT 1');
            $this->db->bind(':email', $email);
            $r = $this->db->single();
            if ($r) {
                $attempts = (int)$r['attempts'] + 1;
                if ($attempts >= 3) {
                    $this->db->query('DELETE FROM two_factor_tokens WHERE id = :id');
                    $this->db->bind(':id', $r['id']);
                    $this->db->execute();
                    return ['ok' => false, 'reason' => 'locked'];
                } else {
                    $this->db->query('UPDATE two_factor_tokens SET attempts = :attempts WHERE id = :id');
                    $this->db->bind(':attempts', $attempts);
                    $this->db->bind(':id', $r['id']);
                    $this->db->execute();
                    return ['ok' => false, 'reason' => 'invalid'];
                }
            }
            return ['ok' => false, 'reason' => 'not_found'];
        }
    }

    // ============================================================
    // SMS OTP METHODS – REMOVED (no longer used)
    // All SMS methods have been removed.
    // ============================================================
}