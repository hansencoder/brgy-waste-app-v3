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

    public function saveMfaToken($user_id, $token) {
        // Clear old tokens for this user
        $this->db->query('DELETE FROM two_factor_tokens WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();

        $this->db->query('INSERT INTO two_factor_tokens (user_id, token, expires_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 5 MINUTE))');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':token', $token);

        return $this->db->execute();
    }

    public function verifyMfaToken($user_id, $token) {
        $this->db->query('SELECT * FROM two_factor_tokens WHERE user_id = :user_id AND token = :token AND expires_at >= NOW()');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':token', $token);

        $row = $this->db->single();
        if ($this->db->rowCount() > 0) {
            // Invalidate token
            $this->db->query('DELETE FROM two_factor_tokens WHERE id = :id');
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
