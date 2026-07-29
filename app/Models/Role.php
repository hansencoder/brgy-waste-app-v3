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
}