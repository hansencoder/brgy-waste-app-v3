<?php

class Barangay {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getInfo() {
        $this->db->query("SELECT * FROM barangays LIMIT 1");
        return $this->db->single();
    }

    public function updateInfo($data) {
        $this->db->query("
            UPDATE barangays SET 
                barangay_name = :barangay_name,
                municipality = :municipality,
                province = :province,
                region = :region,
                official_address = :official_address,
                contact_number = :contact_number,
                official_email = :official_email
            WHERE barangay_id = :id
        ");
        $this->db->bind(':barangay_name', $data['barangay_name']);
        $this->db->bind(':municipality', $data['municipality']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':region', $data['region']);
        $this->db->bind(':official_address', $data['official_address']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':official_email', $data['official_email']);
        $this->db->bind(':id', $data['barangay_id'] ?? 1);
        return $this->db->execute();
    }
}