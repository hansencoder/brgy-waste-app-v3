<?php
class Report {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createReport($data) {
        $this->db->query('INSERT INTO waste_reports (resident_id, photo_path, description, latitude, longitude, is_out_of_bounds) VALUES (:resident_id, :photo_path, :description, :latitude, :longitude, :is_out_of_bounds)');
        $this->db->bind(':resident_id', $data['resident_id']);
        $this->db->bind(':photo_path', $data['photo_path']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':is_out_of_bounds', $data['is_out_of_bounds'] ? 1 : 0);
        return $this->db->execute();
    }

    public function getReportsByResident($resident_id) {
        $this->db->query('SELECT * FROM waste_reports WHERE resident_id = :resident_id ORDER BY created_at DESC');
        $this->db->bind(':resident_id', $resident_id);
        return $this->db->resultSet();
    }

    public function getAllReports() {
        $this->db->query('SELECT r.*, u.full_name, u.email FROM waste_reports r JOIN users u ON r.resident_id = u.id ORDER BY r.created_at DESC');
        return $this->db->resultSet();
    }

    public function updateReportStatus($id, $status, $remark) {
        $this->db->query('UPDATE waste_reports SET status = :status, admin_remark = :remark WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':remark', $remark);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteReport($id) {
        $this->db->query('DELETE FROM waste_reports WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getDashboardStats() {
        $stats = ['total' => 0, 'pending' => 0, 'verified' => 0, 'resolved' => 0];
        $this->db->query("SELECT status, COUNT(*) as count FROM waste_reports GROUP BY status");
        $results = $this->db->resultSet();
        foreach($results as $row) {
            $stats[$row['status']] = $row['count'];
            $stats['total'] += $row['count'];
        }
        return $stats;
    }

    public function getHeatmapData() {
        $this->db->query("SELECT latitude, longitude FROM waste_reports");
        return $this->db->resultSet();
    }
}
