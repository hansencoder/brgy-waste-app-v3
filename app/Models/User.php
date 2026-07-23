<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (name, address, phone_number, email, password, id_front, id_back, role, status) VALUES (:name, :address, :phone_number, :email, :password, :id_front, :id_back, "resident", "pending")');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':id_front', $data['id_front'] ?? null);
        $this->db->bind(':id_back', $data['id_back'] ?? null);

        return $this->db->execute();
    }

    public function saveMfaToken($user_id, $email, $token) {
        // Clear old tokens for this user
        $this->db->query('DELETE FROM two_factor_tokens WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();

        $this->db->query('INSERT INTO two_factor_tokens (user_id, email, token, expires_at, attempts, is_used) VALUES (:user_id, :email, :token, DATE_ADD(NOW(), INTERVAL 10 MINUTE), 0, 0)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);

        return $this->db->execute();
    }

    public function verifyMfaToken($user_id, $token) {
        $this->db->query('SELECT * FROM two_factor_tokens WHERE user_id = :user_id AND token = :token AND expires_at >= NOW() AND is_used = 0 ORDER BY created_at DESC LIMIT 1');
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

        if (!$row) {
            return 0;
        }

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

    public function getAllUsers() {
        $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function updateUserStatus($id, $status) {
        $this->db->query('UPDATE users SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteUser($id) {
        $this->db->query('SELECT id_front, id_back FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $user = $this->db->single();

        if ($user) {
            if (!empty($user['id_front'])) {
                $frontPath = '../public' . $user['id_front'];
                if (file_exists($frontPath)) {
                    unlink($frontPath);
                }
            }
            if (!empty($user['id_back'])) {
                $backPath = '../public' . $user['id_back'];
                if (file_exists($backPath)) {
                    unlink($backPath);
                }
            }
        }

        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- SMS OTP methods ---
    public function createOrUpdateSmsOtp($user_id, $phone)
    {
        $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Remove existing OTPs for this phone or user
        $this->db->query('DELETE FROM sms_otps WHERE phone = :phone OR user_id = :user_id');
        $this->db->bind(':phone', $phone);
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();

        $this->db->query('INSERT INTO sms_otps (user_id, phone, token, expires_at, attempts, is_used, last_sent_at) VALUES (:user_id, :phone, :token, DATE_ADD(NOW(), INTERVAL 5 MINUTE), 0, 0, NOW())');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':token', $token);

        return $this->db->execute() ? $token : false;
    }

    public function canSendSmsOtp($phone, $ip)
    {
        // 60s cooldown only for active, unused OTPs
        $this->db->query('SELECT last_sent_at FROM sms_otps WHERE phone = :phone AND is_used = 0 ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':phone', $phone);
        $row = $this->db->single();
        if ($row) {
            $secondsLeft = max(0, 60 - (time() - strtotime($row['last_sent_at'])));
            if ($secondsLeft > 0) {
                return ['ok' => false, 'reason' => 'cooldown', 'retry_after' => $secondsLeft];
            }
        }

        // Count per phone in last hour
        $this->db->query('SELECT SUM(sms_count) as cnt FROM sms_rate_limits WHERE phone = :phone AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':phone', $phone);
        $r = $this->db->single();
        if ($r && $r['cnt'] >= 3) {
            return ['ok' => false, 'reason' => 'phone_hourly_limit'];
        }

        // Count per IP in last hour
        $this->db->query('SELECT SUM(sms_count) as cnt FROM sms_rate_limits WHERE ip = :ip AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':ip', $ip);
        $r2 = $this->db->single();
        if ($r2 && $r2['cnt'] >= 3) {
            return ['ok' => false, 'reason' => 'ip_hourly_limit'];
        }

        return ['ok' => true];
    }

    public function recordSmsRate($phone, $ip)
    {
        $windowStart = date('Y-m-d H:00:00');
        $this->db->query('INSERT INTO sms_rate_limits (phone, ip, window_start, sms_count) VALUES (:phone, :ip, :window_start, 1) ON DUPLICATE KEY UPDATE sms_count = sms_count + 1');
        $this->db->bind(':phone', $phone);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':window_start', $windowStart);
        $this->db->execute();
    }

    public function verifySmsOtp($phone, $token)
    {
        $this->db->query('SELECT * FROM sms_otps WHERE phone = :phone AND token = :token AND expires_at >= NOW() AND is_used = 0 LIMIT 1');
        $this->db->bind(':phone', $phone);
        $this->db->bind(':token', $token);
        $row = $this->db->single();

        if ($row) {
            $this->db->query('UPDATE sms_otps SET is_used = 1 WHERE id = :id');
            $this->db->bind(':id', $row['id']);
            $this->db->execute();
            return ['ok' => true, 'user_id' => $row['user_id']];
        } else {
            $this->db->query('SELECT id, attempts FROM sms_otps WHERE phone = :phone ORDER BY created_at DESC LIMIT 1');
            $this->db->bind(':phone', $phone);
            $r = $this->db->single();
            if ($r) {
                $attempts = (int)$r['attempts'] + 1;
                if ($attempts >= 3) {
                    $this->db->query('DELETE FROM sms_otps WHERE id = :id');
                    $this->db->bind(':id', $r['id']);
                    $this->db->execute();
                    return ['ok' => false, 'reason' => 'locked'];
                } else {
                    $this->db->query('UPDATE sms_otps SET attempts = :attempts WHERE id = :id');
                    $this->db->bind(':attempts', $attempts);
                    $this->db->bind(':id', $r['id']);
                    $this->db->execute();
                    return ['ok' => false, 'reason' => 'invalid'];
                }
            }
            return ['ok' => false, 'reason' => 'not_found'];
        }
    }

    // --- Email OTP methods ---
    public function canSendEmailOtp($email, $ip)
    {
        // 60s cooldown only for active, unused OTPs
        $this->db->query('SELECT UNIX_TIMESTAMP(created_at) as created_at_ts FROM two_factor_tokens WHERE email = :email AND is_used = 0 ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if ($row) {
            $secondsLeft = max(0, 60 - (time() - (int)$row['created_at_ts']));
            if ($secondsLeft > 0) {
                return ['ok' => false, 'reason' => 'cooldown', 'retry_after' => $secondsLeft];
            }
        }

        // Count per email in last hour
        $this->db->query('SELECT SUM(send_count) as cnt FROM email_otp_rate_limits WHERE email = :email AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':email', $email);
        $r = $this->db->single();
        if ($r && $r['cnt'] >= 3) {
            return ['ok' => false, 'reason' => 'email_hourly_limit'];
        }

        // Count per IP in last hour
        $this->db->query('SELECT SUM(send_count) as cnt FROM email_otp_rate_limits WHERE ip = :ip AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':ip', $ip);
        $r2 = $this->db->single();
        if ($r2 && $r2['cnt'] >= 3) {
            return ['ok' => false, 'reason' => 'ip_hourly_limit'];
        }

        return ['ok' => true];
    }

    public function recordEmailRate($email, $ip)
    {
        $windowStart = date('Y-m-d H:00:00');
        $this->db->query('INSERT INTO email_otp_rate_limits (email, ip, window_start, send_count) VALUES (:email, :ip, :window_start, 1) ON DUPLICATE KEY UPDATE send_count = send_count + 1');
        $this->db->bind(':email', $email);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':window_start', $windowStart);
        $this->db->execute();
    }

    public function verifyEmailOtp($email, $token)
    {
        $this->db->query('SELECT * FROM two_factor_tokens WHERE email = :email AND token = :token AND expires_at >= NOW() AND is_used = 0 LIMIT 1');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        $row = $this->db->single();

        if ($row) {
            $this->db->query('UPDATE two_factor_tokens SET is_used = 1 WHERE id = :id');
            $this->db->bind(':id', $row['id']);
            $this->db->execute();
            return ['ok' => true, 'user_id' => $row['user_id']];
        } else {
            $this->db->query('SELECT id, attempts FROM two_factor_tokens WHERE email = :email ORDER BY created_at DESC LIMIT 1');
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
}
