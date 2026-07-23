<?php
class Report {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createReport($data) {
        $this->db->query('INSERT INTO reports (resident_id, photo_path, description, latitude, longitude, location_verified) VALUES (:resident_id, :photo_path, :description, :latitude, :longitude, :location_verified)');
        $this->db->bind(':resident_id', $data['resident_id']);
        $this->db->bind(':photo_path', $data['photo_path']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':location_verified', $data['location_verified'] ? 1 : 0);
        return $this->db->execute();
    }

    public function getReportsByResident($resident_id) {
        $this->db->query('SELECT * FROM reports WHERE resident_id = :resident_id ORDER BY submission_date DESC');
        $this->db->bind(':resident_id', $resident_id);
        return $this->db->resultSet();
    }

    public function getAllReports() {
        $this->db->query('SELECT r.*, u.name, u.email FROM reports r JOIN users u ON r.resident_id = u.id ORDER BY r.submission_date DESC');
        return $this->db->resultSet();
    }

    public function updateReportStatus($id, $status, $reviewed_by) {
        $this->db->query('UPDATE reports SET status = :status, reviewed_by = :reviewed_by WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':reviewed_by', $reviewed_by);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteReport($id, $resident_id = null) {
        // Fetch photo_path before deleting
        $this->db->query('SELECT photo_path FROM reports WHERE id = :id');
        $this->db->bind(':id', $id);
        $report = $this->db->single();

        // Delete the uploaded photo file
        if ($report && !empty($report['photo_path'])) {
            $filePath = '../public/uploads/' . $report['photo_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete the database record
        if ($resident_id !== null) {
            $this->db->query('DELETE FROM reports WHERE id = :id AND resident_id = :resident_id');
            $this->db->bind(':id', $id);
            $this->db->bind(':resident_id', $resident_id);
        } else {
            $this->db->query('DELETE FROM reports WHERE id = :id');
            $this->db->bind(':id', $id);
        }
        return $this->db->execute();
    }

    public function deleteReportsByResident($resident_id) {
        $this->db->query('SELECT photo_path FROM reports WHERE resident_id = :resident_id');
        $this->db->bind(':resident_id', $resident_id);
        $reports = $this->db->resultSet();

        foreach ($reports as $report) {
            if (!empty($report['photo_path'])) {
                $filePath = '../public/uploads/' . $report['photo_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        $this->db->query('DELETE FROM reports WHERE resident_id = :resident_id');
        $this->db->bind(':resident_id', $resident_id);
        return $this->db->execute();
    }

    public function getDashboardStats() {
        $stats = ['total' => 0, 'pending' => 0, 'verified' => 0, 'resolved' => 0];
        $this->db->query("SELECT status, COUNT(*) as count FROM reports GROUP BY status");
        $results = $this->db->resultSet();
        foreach($results as $row) {
            $stats[$row['status']] = $row['count'];
            $stats['total'] += $row['count'];
        }
        return $stats;
    }

    public function getHeatmapData() {
        $this->db->query("SELECT latitude, longitude FROM reports");
        return $this->db->resultSet();
    }

    public function getDashboardStatsByResident($resident_id) {
        $stats = ['total' => 0, 'pending' => 0, 'verified' => 0, 'resolved' => 0];
        $this->db->query("SELECT status, COUNT(*) as count FROM reports WHERE resident_id = :resident_id GROUP BY status");
        $this->db->bind(':resident_id', $resident_id);
        $results = $this->db->resultSet();
        foreach($results as $row) {
            $stats[$row['status']] = $row['count'];
            $stats['total'] += $row['count'];
        }
        return $stats;
    }

    public function getHeatmapDataByResident($resident_id) {
        $this->db->query("SELECT latitude, longitude, status FROM reports WHERE resident_id = :resident_id");
        $this->db->bind(':resident_id', $resident_id);
        return $this->db->resultSet();
    }

    public function getReportById($id, $resident_id = null) {
        $sql = "SELECT * FROM reports WHERE id = :id";
        if ($resident_id !== null) {
            $sql .= " AND resident_id = :resident_id";
        }
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        if ($resident_id !== null) {
            $this->db->bind(':resident_id', $resident_id);
        }
        return $this->db->single();
    }

    public function getReportTimeline($id) {
        $this->db->query("SELECT previous_status, new_status, remark, changed_at, (SELECT name FROM users WHERE id = changed_by) as changed_by_name FROM report_status_history WHERE report_id = :id ORDER BY changed_at ASC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }
}
