<?php

class WasteCondition {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM waste_conditions";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY condition_name";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM waste_conditions WHERE condition_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}