<?php
class AuditLog {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function logAction($user_id, $action_type, $target_entity, $action_details, $result = 'success') {
        $this->db->query('INSERT INTO audit_logs (user_id, action_type, target_entity, action_details, result) VALUES (:user_id, :action_type, :target_entity, :action_details, :result)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':action_type', $action_type);
        $this->db->bind(':target_entity', $target_entity);
        $this->db->bind(':action_details', $action_details);
        $this->db->bind(':result', $result);
        
        return $this->db->execute();
    }
}
