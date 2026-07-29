<?php

class Report {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // ============================================================
    // CREATE NEW REPORT
    // ============================================================
    public function createReport($data) {
        // Insert into reports table
        $this->db->query('INSERT INTO reports (
            resident_id, 
            description, 
            latitude, 
            longitude, 
            location_verified, 
            category_id, 
            quantity_id, 
            condition_id, 
            status_id, 
            purok_id, 
            location
        ) VALUES (
            :resident_id, 
            :description, 
            :latitude, 
            :longitude, 
            :location_verified, 
            :category_id, 
            :quantity_id, 
            :condition_id, 
            :status_id, 
            :purok_id, 
            :location
        )');

        $this->db->bind(':resident_id', $data['resident_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':location_verified', $data['location_verified'] ? 1 : 0);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':quantity_id', $data['quantity_id']);
        $this->db->bind(':condition_id', $data['condition_id']);
        $this->db->bind(':status_id', $data['status_id']);
        $this->db->bind(':purok_id', $data['purok_id']);
        $this->db->bind(':location', $data['location'] ?? '');

        if ($this->db->execute()) {
            $reportId = $this->db->lastInsertId();

            // Insert photo(s) into report_photos table
            if (!empty($data['photos']) && is_array($data['photos'])) {
                foreach ($data['photos'] as $index => $photoPath) {
                    $isPrimary = ($index === 0) ? 1 : 0;
                    $this->db->query('INSERT INTO report_photos (report_id, photo_path, is_primary) 
                                        VALUES (:report_id, :photo_path, :is_primary)');
                    $this->db->bind(':report_id', $reportId);
                    $this->db->bind(':photo_path', $photoPath);
                    $this->db->bind(':is_primary', $isPrimary);
                    $this->db->execute();
                }
            }

            return $reportId;
        }

        return false;
    }

    // ============================================================
    // GET REPORTS BY RESIDENT
    // ============================================================
    public function getReportsByResident($resident_id) {
        $this->db->query('
            SELECT r.*, 
            rs.status_name as status,
            wc.category_name as waste_category,
            eq.quantity_name as estimated_quantity,
            wcnd.condition_name as waste_condition,
            p.purok_name as purok,
            (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) as photo_path
            FROM reports r
            LEFT JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.resident_id = :resident_id
            ORDER BY r.submission_date DESC
        ');
        $this->db->bind(':resident_id', $resident_id);
        return $this->db->resultSet();
    }

    // ============================================================
    // GET ALL REPORTS (for Admin/Supervisor)
    // ============================================================
    public function getAllReports() {
        $this->db->query('
            SELECT r.*, 
                u.name as reporter_name, 
                u.email as reporter_email,
                rs.status_name as status,
                wc.category_name as waste_category,
                eq.quantity_name as estimated_quantity,
                wcnd.condition_name as waste_condition,
                p.purok_name as purok,
                (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) as photo_path
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
            LEFT JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            ORDER BY r.submission_date DESC
        ');
        return $this->db->resultSet();
    }

    // ============================================================
    // GET REPORT BY ID (with relations)
    // ============================================================
    public function getReportById($id, $resident_id = null) {
        $sql = '
            SELECT r.*, 
                u.name as reporter_name, 
                u.email as reporter_email,
                u.phone_number as reporter_phone,
                rs.status_name as status,
                rs.color_code as status_color,
                wc.category_name as waste_category,
                eq.quantity_name as estimated_quantity,
                wcnd.condition_name as waste_condition,
                p.purok_name as purok,
                (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) as photo_path
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
            LEFT JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.id = :id
        ';
        if ($resident_id !== null) {
            $sql .= " AND r.resident_id = :resident_id";
        }
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        if ($resident_id !== null) {
            $this->db->bind(':resident_id', $resident_id);
        }
        return $this->db->single();
    }

    // ============================================================
    // GET ALL PHOTOS FOR A REPORT
    // ============================================================
    public function getReportPhotos($report_id) {
        $this->db->query("SELECT * FROM report_photos WHERE report_id = :report_id ORDER BY is_primary DESC, uploaded_at");
        $this->db->bind(':report_id', $report_id);
        return $this->db->resultSet();
    }

    // ============================================================
    // UPDATE REPORT STATUS
    // ============================================================
    public function updateReportStatus($id, $status_id, $reviewed_by) {
        $this->db->query('UPDATE reports SET status_id = :status_id, reviewed_by = :reviewed_by, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status_id', $status_id);
        $this->db->bind(':reviewed_by', $reviewed_by);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ============================================================
    // DELETE REPORT (with photos)
    // ============================================================
    public function deleteReport($id, $resident_id = null) {
        // Fetch photo paths before deleting
        $photos = $this->getReportPhotos($id);
        foreach ($photos as $photo) {
            $filePath = '../public/uploads/' . $photo['photo_path'];
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

    // ============================================================
    // DASHBOARD STATS FOR RESIDENT
    // ============================================================
    public function getDashboardStatsByResident($resident_id) {
        $stats = ['total' => 0, 'pending' => 0, 'verified' => 0, 'resolved' => 0, 'rejected' => 0];
        $this->db->query("
            SELECT rs.status_name, COUNT(*) as count 
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            WHERE r.resident_id = :resident_id
            GROUP BY r.status_id
        ");
        $this->db->bind(':resident_id', $resident_id);
        $results = $this->db->resultSet();
        foreach ($results as $row) {
            $stats[$row['status_name']] = $row['count'];
            $stats['total'] += $row['count'];
        }
        return $stats;
    }

    // ============================================================
    // GET HEATMAP DATA FOR RESIDENT
    // ============================================================
    public function getHeatmapDataByResident($resident_id) {
        $this->db->query("SELECT latitude, longitude, status_id FROM reports WHERE resident_id = :resident_id");
        $this->db->bind(':resident_id', $resident_id);
        return $this->db->resultSet();
    }

    // ============================================================
    // GET STATUS TIMELINE
    // ============================================================
    public function getReportTimeline($id) {
        $this->db->query("
            SELECT h.*, 
                   (SELECT name FROM users WHERE id = h.changed_by) as changed_by_name,
                   rs_new.status_name as new_status_name,
                   rs_old.status_name as old_status_name
            FROM report_status_history h
            LEFT JOIN report_statuses rs_new ON h.new_status = rs_new.status_name
            LEFT JOIN report_statuses rs_old ON h.previous_status = rs_old.status_name
            WHERE h.report_id = :id
            ORDER BY h.changed_at ASC
        ");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    // ============================================================
    // DUPLICATE DETECTION (for future implementation)
    // ============================================================
    public function findNearbyReports($latitude, $longitude, $category_id, $radius = 50, $days = 7) {
        // Placeholder for duplicate detection – we'll implement later
        return [];
    }

    // ============================================================
    // REPORT SUPPORT (for future implementation)
    // ============================================================
    public function supportReport($report_id, $user_id) {
        // Placeholder
        return false;
    }
}