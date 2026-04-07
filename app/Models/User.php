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
        $this->db->query('INSERT INTO users (full_name, address, contact_number, email, password_hash, role, status) VALUES (:full_name, :address, :contact_number, :email, :password, "resident", "pending")');
        
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        return $this->db->execute();
    }

    public function saveMfaToken($user_id, $token) {
        // Clear old tokens for this user
        $this->db->query('DELETE FROM mfa_tokens WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();

        $this->db->query('INSERT INTO mfa_tokens (user_id, token, expires_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 5 MINUTE))');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':token', $token);

        return $this->db->execute();
    }

    public function verifyMfaToken($user_id, $token) {
        $this->db->query('SELECT * FROM mfa_tokens WHERE user_id = :user_id AND token = :token AND expires_at >= NOW()');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':token', $token);
        
        $row = $this->db->single();
        if ($this->db->rowCount() > 0) {
            // Invalidate token
            $this->db->query('DELETE FROM mfa_tokens WHERE id = :id');
            $this->db->bind(':id', $row['id']);
            $this->db->execute();
            return true;
        }
        return false;
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
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
