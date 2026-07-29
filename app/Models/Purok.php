<?php

class Purok {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($activeOnly = true) {
        $sql = "SELECT p.*, b.barangay_name FROM puroks p 
                LEFT JOIN barangays b ON p.barangay_id = b.barangay_id";
        if ($activeOnly) {
            $sql .= " WHERE p.is_active = 1";
        }
        $sql .= " ORDER BY p.sort_order";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM puroks WHERE purok_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}