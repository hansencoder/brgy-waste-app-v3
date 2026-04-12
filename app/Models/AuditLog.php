<?php
class AuditLog {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function logAction($user_id, $action, $affected_record, $details, $result = 'success', $ip_address = null, $user_agent = null) {
        $this->db->query('INSERT INTO audit_logs (user_id, action, affected_record, details, result, ip_address, user_agent) VALUES (:user_id, :action, :affected_record, :details, :result, :ip_address, :user_agent)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':action', $action);
        $this->db->bind(':affected_record', $affected_record);
        $this->db->bind(':details', $details);
        $this->db->bind(':result', $result);
        $this->db->bind(':ip_address', $ip_address ?? $_SERVER['REMOTE_ADDR'] ?? null);
        $this->db->bind(':user_agent', $user_agent ?? $_SERVER['HTTP_USER_AGENT'] ?? null);

        return $this->db->execute();
    }
}
