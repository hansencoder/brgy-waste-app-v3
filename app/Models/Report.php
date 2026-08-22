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
        $stats = ['total' => 0, 'pending' => 0, 'verified' => 0, 'in_progress' => 0, 'resolved' => 0, 'rejected' => 0];
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
            $statusKey = strtolower(str_replace(' ', '_', trim($row['status_name'])));
            $stats[$statusKey] = (int)$row['count'];
            $stats['total'] += (int)$row['count'];
        }
        return $stats;
    }

    // ============================================================
    // GET HEATMAP DATA FOR RESIDENT
    // ============================================================
    public function getHeatmapDataByResident($resident_id) {
        $this->db->query("
            SELECT
                r.id,
                r.latitude,
                r.longitude,
                r.status_id,
                r.description,
                r.submission_date,
                wc.category_name,
                (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) AS photo_path
            FROM reports r
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            WHERE r.resident_id = :resident_id
        ");
        $this->db->bind(':resident_id', $resident_id);
        return $this->db->resultSet();
    }

    // ============================================================
    // GET COMMUNITY REPORTS FOR RESIDENT DASHBOARD MAP
    // ============================================================
    public function getCommunityReportsForMap() {
        $this->db->query("
            SELECT
                r.id,
                r.latitude,
                r.longitude,
                r.status_id,
                r.description,
                r.submission_date,
                r.support_count,
                rs.status_name,
                wc.category_name,
                p.purok_name,
                (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) AS photo_path
            FROM reports r
            LEFT JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.latitude IS NOT NULL AND r.longitude IS NOT NULL AND r.latitude != 0 AND r.longitude != 0
              AND LOWER(rs.status_name) != 'rejected'
              AND (
                  LOWER(rs.status_name) != 'resolved'
                  OR (LOWER(rs.status_name) = 'resolved' AND COALESCE(r.updated_at, r.submission_date) >= DATE_SUB(NOW(), INTERVAL 15 DAY))
              )
            ORDER BY r.submission_date DESC
            LIMIT 200
        ");
        return $this->db->resultSet();
    }

    // ============================================================
    // UPDATE REPORT DETAILS (FOR RESIDENTS WHILE STATUS IS PENDING)
    // ============================================================
    public function updateReportDetails($id, $resident_id, $data) {
        $this->db->query("
            UPDATE reports SET
                category_id = :category_id,
                quantity_id = :quantity_id,
                condition_id = :condition_id,
                description = :description,
                purok_id = :purok_id,
                latitude = :latitude,
                longitude = :longitude,
                location = :location,
                updated_at = NOW()
            WHERE id = :id AND resident_id = :resident_id AND status_id = 1
        ");
        $this->db->bind(':id', $id);
        $this->db->bind(':resident_id', $resident_id);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':quantity_id', $data['quantity_id']);
        $this->db->bind(':condition_id', $data['condition_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':purok_id', $data['purok_id']);
        $this->db->bind(':latitude', $data['latitude']);
        $this->db->bind(':longitude', $data['longitude']);
        $this->db->bind(':location', $data['location'] ?? '');
        return $this->db->execute();
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
    public function findNearbyReports($latitude, $longitude, $categoryId, $radiusMeters = 50, $days = 7) {
    $db = new Database();

    // Exclude resolved and rejected
    $excludedStatuses = ['Resolved', 'Rejected'];
    $placeholders = implode(',', array_fill(0, count($excludedStatuses), '?'));

    $query = "
        SELECT 
            r.id,
            r.category_id,
            r.description,
            r.submission_date,
            rs.status_name as status,
            wc.category_name,
            r.support_count,
            (
                6371 * acos(
                    cos(radians(?)) * cos(radians(r.latitude)) *
                    cos(radians(r.longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(r.latitude))
                )
            ) AS distance_km
        FROM reports r
        JOIN report_statuses rs ON r.status_id = rs.status_id
        LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
        WHERE r.category_id = ?
            AND r.latitude IS NOT NULL
            AND r.longitude IS NOT NULL
            AND rs.status_name NOT IN ($placeholders)
            AND r.submission_date >= DATE_SUB(NOW(), INTERVAL ? DAY)
        HAVING distance_km <= (? / 1000)
        ORDER BY distance_km ASC
        LIMIT 10
    ";

    $db->query($query);
    $db->bind(1, $latitude);
    $db->bind(2, $longitude);
    $db->bind(3, $latitude);
    $db->bind(4, $categoryId);
    // bind excluded statuses
    foreach ($excludedStatuses as $idx => $status) {
        $db->bind(5 + $idx, $status);
    }
    $offset = 5 + count($excludedStatuses);
    $db->bind($offset, $days);
    $db->bind($offset + 1, $radiusMeters);

    return $db->resultSet();
    }

    // ============================================================
    // REPORT SUPPORT (for future implementation)
    // ============================================================
    public function supportReport($reportId, $userId) {
    $db = new Database();
    // Check if already supported
    $db->query("SELECT * FROM report_supports WHERE report_id = :report_id AND user_id = :user_id");
    $db->bind(':report_id', $reportId);
    $db->bind(':user_id', $userId);
    if ($db->single()) {
        return false;
    }
    // Insert support
    $db->query("INSERT INTO report_supports (report_id, user_id) VALUES (:report_id, :user_id)");
    $db->bind(':report_id', $reportId);
    $db->bind(':user_id', $userId);
    if (!$db->execute()) {
        return false;
    }
    // Increment support_count on report
    $db->query("UPDATE reports SET support_count = support_count + 1 WHERE id = :id");
    $db->bind(':id', $reportId);
    return $db->execute();
    }

    // ============================================================
    // GUEST REPORT: CREATE
    // ============================================================
    public function createGuestReport($data) {
        // Generate unique tracking number: WRS-YYYY-NNNNN
        $trackingNumber = $this->generateTrackingNumber();

        $guestEmail = !empty($data['guest_email']) ? trim($data['guest_email']) : null;
        if (empty($guestEmail) && !empty($data['guest_phone']) && filter_var($data['guest_phone'], FILTER_VALIDATE_EMAIL)) {
            $guestEmail = strtolower(trim($data['guest_phone']));
        }

        $this->db->query('INSERT INTO reports (
            resident_id,
            reporter_type,
            tracking_number,
            guest_name,
            guest_phone,
            guest_email,
            description,
            latitude,
            longitude,
            location_verified,
            reporter_latitude,
            reporter_longitude,
            location_plausibility,
            is_duplicate,
            category_id,
            quantity_id,
            condition_id,
            status_id,
            purok_id,
            location
        ) VALUES (
            NULL,
            \'guest\',
            :tracking_number,
            :guest_name,
            :guest_phone,
            :guest_email,
            :description,
            :latitude,
            :longitude,
            0,
            :reporter_latitude,
            :reporter_longitude,
            :location_plausibility,
            :is_duplicate,
            :category_id,
            :quantity_id,
            :condition_id,
            :status_id,
            :purok_id,
            :location
        )');

        $this->db->bind(':tracking_number',      $trackingNumber);
        $this->db->bind(':guest_name',           $data['guest_name'] ?? '');
        $this->db->bind(':guest_phone',          $data['guest_phone']);
        $this->db->bind(':guest_email',          $guestEmail);
        $this->db->bind(':description',          $data['description']);
        $this->db->bind(':latitude',             $data['latitude']);
        $this->db->bind(':longitude',            $data['longitude']);
        $this->db->bind(':reporter_latitude',    $data['reporter_latitude'] ?? null);
        $this->db->bind(':reporter_longitude',   $data['reporter_longitude'] ?? null);
        $this->db->bind(':location_plausibility', $data['location_plausibility'] ?? 'plausible');
        $this->db->bind(':is_duplicate',         $data['is_duplicate'] ?? 0);
        $this->db->bind(':category_id',          $data['category_id']);
        $this->db->bind(':quantity_id',          $data['quantity_id']);
        $this->db->bind(':condition_id',         $data['condition_id']);
        $this->db->bind(':status_id',            1); // 1 = Pending
        $this->db->bind(':purok_id',             $data['purok_id'] ?? null);
        $this->db->bind(':location',             $data['location'] ?? '');

        if ($this->db->execute()) {
            $reportId = $this->db->lastInsertId();

            // Insert photos if any
            if (!empty($data['photos']) && is_array($data['photos'])) {
                foreach ($data['photos'] as $index => $photoPath) {
                    $isPrimary = ($index === 0) ? 1 : 0;
                    $this->db->query('INSERT INTO report_photos (report_id, photo_path, is_primary) 
                                        VALUES (:report_id, :photo_path, :is_primary)');
                    $this->db->bind(':report_id',  $reportId);
                    $this->db->bind(':photo_path', $photoPath);
                    $this->db->bind(':is_primary', $isPrimary);
                    $this->db->execute();
                }
            }

            return ['id' => $reportId, 'tracking_number' => $trackingNumber];
        }

        return false;
    }

    // ============================================================
    // GUEST REPORT: GET BY TRACKING NUMBER + PHONE
    // ============================================================
    public function getReportByTrackingAndPhone($trackingNumber, $phone) {
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
            WHERE r.tracking_number = :tracking_number
              AND r.guest_phone = :phone
              AND r.reporter_type = \'guest\'
            LIMIT 1
        ');
        $this->db->bind(':tracking_number', $trackingNumber);
        $this->db->bind(':phone', $phone);
        return $this->db->single();
    }

    // ============================================================
    // GUEST REPORT: CHECK DUPLICATE (same category within 200m, last 2 hrs)
    // ============================================================
    public function checkDuplicateReport($lat, $lng, $categoryId) {
        // Haversine in SQL to find nearby reports within 200m submitted in last 2 hours
        $this->db->query('
            SELECT id FROM reports
            WHERE category_id = :category_id
              AND submission_date >= DATE_SUB(NOW(), INTERVAL 2 HOUR)
              AND (
                6371000 * 2 * ASIN(SQRT(
                    POWER(SIN((RADIANS(:lat) - RADIANS(latitude)) / 2), 2) +
                    COS(RADIANS(:lat2)) * COS(RADIANS(latitude)) *
                    POWER(SIN((RADIANS(:lng) - RADIANS(longitude)) / 2), 2)
                ))
              ) < 200
            LIMIT 1
        ');
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':lat',  $lat);
        $this->db->bind(':lat2', $lat);
        $this->db->bind(':lng',  $lng);
        return $this->db->single() ? true : false;
    }

    // ============================================================
    // GUEST REPORT: RATE LIMIT CHECK (max 3 reports per phone per hour)
    // ============================================================
    public function canGuestSubmit($phone) {
        $this->db->query('
            SELECT COUNT(*) as cnt FROM reports
            WHERE guest_phone = :phone
              AND reporter_type = \'guest\'
              AND submission_date >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ');
        $this->db->bind(':phone', $phone);
        $row = $this->db->single();
        return ($row['cnt'] ?? 0) < 3;
    }

    // ============================================================
    // GUEST REPORT: COUNT BY PHONE
    // ============================================================
    public function getGuestReportCount($phone) {
        $this->db->query('
            SELECT COUNT(*) as cnt FROM reports
            WHERE guest_phone = :phone
              AND reporter_type = \'guest\'
        ');
        $this->db->bind(':phone', $phone);
        $row = $this->db->single();
        return (int)($row['cnt'] ?? 0);
    }

    public function getGuestHourlyReportCount($phone) {
        $this->db->query('
            SELECT COUNT(*) as cnt FROM reports
            WHERE guest_phone = :phone
              AND reporter_type = \'guest\'
              AND submission_date >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ');
        $this->db->bind(':phone', $phone);
        $row = $this->db->single();
        return (int)($row['cnt'] ?? 0);
    }

    // ============================================================
    // INTERNAL: Generate Unique Tracking Number
    // ============================================================
    private function generateTrackingNumber() {
        $year = date('Y');
        $maxTries = 10;
        for ($i = 0; $i < $maxTries; $i++) {
            $number = strtoupper('WRS-' . $year . '-' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT));
            $this->db->query('SELECT id FROM reports WHERE tracking_number = :tn LIMIT 1');
            $this->db->bind(':tn', $number);
            if (!$this->db->single()) {
                return $number;
            }
        }
        // Fallback: timestamp-based unique
        return 'WRS-' . $year . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    }

}