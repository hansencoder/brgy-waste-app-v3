<?php

class ReportStatus {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM report_statuses ORDER BY status_id");
        return $this->db->resultSet();
    }

    public function getByName($name) {
        $this->db->query("SELECT * FROM report_statuses WHERE status_name = :name");
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM report_statuses WHERE status_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}