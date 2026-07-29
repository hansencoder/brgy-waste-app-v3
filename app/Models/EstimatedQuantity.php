<?php

class EstimatedQuantity {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM estimated_quantities";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY sort_order";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM estimated_quantities WHERE quantity_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}