<?php

class Role {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM roles ORDER BY role_id");
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM roles WHERE role_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getByName($name) {
        $this->db->query("SELECT * FROM roles WHERE LOWER(role_name) = LOWER(:name)");
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    public function getStaffRoles() {
        $this->db->query("SELECT * FROM roles WHERE LOWER(role_name) != 'resident' ORDER BY (CASE WHEN LOWER(role_name) = 'administrator' THEN 1 WHEN LOWER(role_name) = 'supervisor' THEN 2 ELSE 3 END), role_name ASC");
        return $this->db->resultSet();
    }
}