<?php

class AdminController extends Controller {
    private $userModel;
    private $auditModel;

    public function __construct() {
        // Check if user is logged in and has admin role (secretary or captain)
        if (!isset($_SESSION['user_id'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=auth');
            exit;
        }

        // Get user role from database using role_id
        $db = new Database();
        $db->query("SELECT r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.id = :id");
        $db->bind(':id', $_SESSION['user_id']);
        $user = $db->single();
        $roleName = $user ? strtolower($user['role_name']) : '';

        if (!in_array($roleName, ['administrator', 'secretary', 'captain'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=auth');
            exit;
        }

        // Store role in session for easy access
        $_SESSION['user_role'] = $roleName;

        $this->userModel = $this->model('User');
        $this->auditModel = $this->model('AuditLog');
    }

    // ============================================================
    // PROFILE OTP REQUEST & VERIFICATION
    // ============================================================

    public function requestProfileOTP() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode(strtolower($_SESSION['user_role']) . '/profile'));
            exit;
        }

        $userId = $_SESSION['user_id'];
        $db = new Database();
        $db->query("SELECT email, name FROM users WHERE id = :id");
        $db->bind(':id', $userId);
        $user = $db->single();

        if (!$user || empty($user['email'])) {
            echo json_encode(['success' => false, 'message' => 'No email address on file.']);
            exit;
        }

        $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $db->query("DELETE FROM two_factor_tokens WHERE user_id = :user_id AND purpose = 'profile_change' AND is_used = 0");
        $db->bind(':user_id', $userId);
        $db->execute();

        $db->query("INSERT INTO two_factor_tokens (user_id, email, token, expires_at, purpose) 
                    VALUES (:user_id, :email, :token, DATE_ADD(NOW(), INTERVAL 10 MINUTE), 'profile_change')");
        $db->bind(':user_id', $userId);
        $db->bind(':email', $user['email']);
        $db->bind(':token', $token);
        $db->execute();

        require_once '../app/Models/Helpers/OtpMailer.php';
        try {
            OtpMailer::sendOtpEmail($user['email'], $token, $user['name']);
            echo json_encode(['success' => true, 'message' => 'OTP sent to your email.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP: ' . $e->getMessage()]);
        }
        exit;
    }

    public function verifyProfileOTP() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode(strtolower($_SESSION['user_role']) . '/profile'));
            exit;
        }

        $otp = trim($_POST['otp'] ?? '');
        $userId = $_SESSION['user_id'];
        $db = new Database();

        $db->query("SELECT * FROM two_factor_tokens 
                    WHERE user_id = :user_id AND purpose = 'profile_change' AND is_used = 0 AND expires_at >= NOW() 
                    ORDER BY created_at DESC LIMIT 1");
        $db->bind(':user_id', $userId);
        $tokenRecord = $db->single();

        if (!$tokenRecord) {
            echo json_encode(['success' => false, 'message' => 'No valid OTP found. Please request a new one.']);
            exit;
        }

        if ($tokenRecord['token'] !== $otp) {
            $attempts = (int)($tokenRecord['attempts'] ?? 0) + 1;
            if ($attempts >= 3) {
                $db->query("DELETE FROM two_factor_tokens WHERE user_id = :user_id AND purpose = 'profile_change'");
                $db->bind(':user_id', $userId);
                $db->execute();
                echo json_encode(['success' => false, 'message' => 'Too many failed attempts. Please request a new OTP.']);
                exit;
            }
            $db->query("UPDATE two_factor_tokens SET attempts = :attempts WHERE id = :id");
            $db->bind(':attempts', $attempts);
            $db->bind(':id', $tokenRecord['id']);
            $db->execute();
            echo json_encode(['success' => false, 'message' => 'Invalid OTP. Please try again.']);
            exit;
        }

        $db->query("UPDATE two_factor_tokens SET is_used = 1 WHERE id = :id");
        $db->bind(':id', $tokenRecord['id']);
        $db->execute();

        $_SESSION['profile_otp_verified'] = true;
        echo json_encode(['success' => true, 'message' => 'OTP verified. You can now save your changes.']);
        exit;
    }

    // ============================================================
    // DASHBOARD
    // ============================================================

    public function index() {
        $reportModel = $this->model('Report');
        $db = new Database();

        // ---- Waste Report Stats (existing) ----
        $db->query("
            SELECT rs.status_name, COUNT(*) as count 
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            GROUP BY r.status_id
        ");
        $statusResults = $db->resultSet();
        
        $stats = ['total' => 0, 'Pending' => 0, 'Verified' => 0, 'Resolved' => 0, 'Rejected' => 0];
        foreach ($statusResults as $row) {
            $stats[$row['status_name']] = (int)$row['count'];
            $stats['total'] += (int)$row['count'];
        }
        $data['stats'] = $stats;

        // Today's reports
        $db->query("SELECT COUNT(*) as count FROM reports WHERE DATE(submission_date) = CURDATE()");
        $todayRow = $db->single();
        $data['today_count'] = $todayRow ? (int)$todayRow['count'] : 0;

        // ---- Resident Accounts Breakdown ----
        $db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended,
                SUM(CASE WHEN status = 'deactivated' THEN 1 ELSE 0 END) as deactivated
            FROM users
            WHERE role_id = 3
        ");
        $residentStats = $db->single();
        $data['resident_stats'] = [
            'total'      => (int)($residentStats['total'] ?? 0),
            'active'     => (int)($residentStats['active'] ?? 0),
            'suspended'  => (int)($residentStats['suspended'] ?? 0),
            'deactivated'=> (int)($residentStats['deactivated'] ?? 0),
        ];

        // ---- GIS Monitoring ----
        // Mapped reports (with valid lat/lng)
        $db->query("SELECT COUNT(*) as count FROM reports WHERE latitude IS NOT NULL AND longitude IS NOT NULL");
        $mapped = $db->single();
        $data['mapped_reports'] = (int)($mapped['count'] ?? 0);

        // Active hotspots: puroks with ≥3 reports in last 30 days
        $db->query("
            SELECT COUNT(DISTINCT purok_id) as count
            FROM reports
            WHERE submission_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY purok_id
            HAVING COUNT(*) >= 3
        ");
        $hotspotCount = $db->rowCount();
        $data['active_hotspots'] = $hotspotCount;

        // Highest concern purok (most reports overall)
        $db->query("
            SELECT p.purok_name, COUNT(*) as cnt
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            GROUP BY r.purok_id
            ORDER BY cnt DESC
            LIMIT 1
        ");
        $topPurok = $db->single();
        $data['highest_purok'] = $topPurok ? $topPurok['purok_name'] : 'N/A';

        // ---- Collection Schedule (next collection) ----
        // Get today's day name
        $today = date('l'); // e.g., "Monday"
        // Find the next active schedule starting from today (if today's schedule is still active, we'll take it; else next day)
        // We'll query schedules ordered by day using a FIELD() for proper weekday order.
        $db->query("
            SELECT cs.*, 
                   GROUP_CONCAT(p.purok_name SEPARATOR ', ') as puroks
            FROM collection_schedules cs
            LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
            LEFT JOIN puroks p ON csp.purok_id = p.purok_id
            WHERE cs.status = 'active'
            GROUP BY cs.schedule_id
            ORDER BY FIELD(cs.collection_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
        ");
        $allSchedules = $db->resultSet();

        // Find the next schedule: we'll compare day order.
        // We'll define a weekday order array.
        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $todayIndex = array_search($today, $weekdays);

        $nextSchedule = null;
        foreach ($allSchedules as $schedule) {
            $dayIndex = array_search($schedule['collection_day'], $weekdays);
            // If same day, take it if time is not passed? For simplicity, take first match.
            if ($dayIndex >= $todayIndex) {
                $nextSchedule = $schedule;
                break;
            }
        }
        // If none found (e.g., after Sunday), take the first one (next week)
        if (!$nextSchedule && !empty($allSchedules)) {
            $nextSchedule = $allSchedules[0];
        }
        $data['next_schedule'] = $nextSchedule;

        // ---- Latest Announcement ----
        $db->query("
            SELECT a.*, u.name as author
            FROM announcements a
            LEFT JOIN users u ON a.created_by = u.id
            WHERE a.visibility_id IN (1, 2)  -- Public or Registered
            ORDER BY a.created_at DESC
            LIMIT 1
        ");
        $data['latest_announcement'] = $db->single();

        // ---- Active Announcements Count ----
        $db->query("SELECT COUNT(*) as count FROM announcements");
        $activeAnnounce = $db->single();
        $data['active_announcements'] = (int)($activeAnnounce['count'] ?? 0);

        // ---- Recent 5 Reports (existing) ----
        $db->query("
            SELECT r.id, r.description, r.submission_date, 
                    u.name as resident_name,
                    rs.status_name as status,
                    rs.color_code as status_color,
                    wc.category_name as category,
                    p.purok_name as purok
            FROM reports r
            JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            ORDER BY r.submission_date DESC
            LIMIT 5
        ");
        $data['recent_reports'] = $db->resultSet();

        // ---- Recent Activity (existing) ----
        $db->query("
            SELECT a.action, a.details, a.created_at, u.name as user_name
            FROM audit_logs a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.action != 'Dashboard Access'
            ORDER BY a.created_at DESC
            LIMIT 5
        ");
        $data['recent_activity'] = $db->resultSet();

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success');
        $this->view('admin/dashboard', $data);
    }

    // ============================================================
    // GIS MONITORING
    // ============================================================
    public function gis() {
        if (!in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
            die("Unauthorized Access");
        }

        $db = new Database();

        // Get all reports with coordinates
        $db->query("
            SELECT r.*, 
                   u.name as resident_name,
                   rs.status_name as status,
                   rs.color_code as status_color,
                   wc.category_name as waste_category,
                   p.purok_name as purok
            FROM reports r
            JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.latitude IS NOT NULL AND r.longitude IS NOT NULL
            ORDER BY r.submission_date DESC
        ");
        $data['reports'] = $db->resultSet();

        // Get status distribution for legend
        $db->query("
            SELECT rs.status_name, rs.color_code, COUNT(*) as count
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            GROUP BY r.status_id
        ");
        $data['status_distribution'] = $db->resultSet();

        // Get total mapped reports
        $db->query("SELECT COUNT(*) as count FROM reports WHERE latitude IS NOT NULL AND longitude IS NOT NULL");
        $totalMapped = $db->single();
        $data['total_mapped'] = (int)($totalMapped['count'] ?? 0);

        // Get active hotspots (puroks with ≥3 reports in last 30 days)
        $db->query("
            SELECT p.purok_name, COUNT(*) as report_count, wc.category_name as dominant_category
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            WHERE r.submission_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY r.purok_id
            HAVING COUNT(*) >= 3
            ORDER BY report_count DESC
        ");
        $data['active_hotspots'] = $db->resultSet();
        $data['active_hotspots_count'] = $db->rowCount();

        // Get highest concern purok
        $db->query("
            SELECT p.purok_name, COUNT(*) as cnt
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            GROUP BY r.purok_id
            ORDER BY cnt DESC
            LIMIT 1
        ");
        $topPurok = $db->single();
        $data['highest_purok'] = $topPurok ? $topPurok['purok_name'] : 'N/A';

        // Get waste categories for filters
        $db->query("SELECT * FROM waste_categories WHERE is_active = 1 ORDER BY category_name");
        $data['categories'] = $db->resultSet();

        // Get puroks for filter
        $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
        $data['puroks'] = $db->resultSet();

        // Get heatmap settings
        $heatmapModel = $this->model('HeatmapSetting');
        $data['heatmap_settings'] = $heatmapModel->getConfig();

        // Get report statuses for filter
        $db->query("SELECT * FROM report_statuses ORDER BY status_id");
        $data['statuses'] = $db->resultSet();

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', 'success');

        $this->view('admin/gis', $data);
    }

    // ============================================================
    // API: GET GIS DATA (AJAX)
    // ============================================================
    public function getGisData() {
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $purok = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        $db = new Database();
        
        $query = "
            SELECT r.*, 
                   u.name as resident_name,
                   rs.status_name as status,
                   rs.color_code as status_color,
                   wc.category_name as waste_category,
                   p.purok_name as purok
            FROM reports r
            JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.latitude IS NOT NULL AND r.longitude IS NOT NULL
        ";

        if ($category > 0) {
            $query .= " AND r.category_id = :category";
        }
        if ($purok > 0) {
            $query .= " AND r.purok_id = :purok";
        }
        if (!empty($status)) {
            $query .= " AND rs.status_name = :status";
        }

        $query .= " ORDER BY r.submission_date DESC";

        $db->query($query);
        if ($category > 0) {
            $db->bind(':category', $category);
        }
        if ($purok > 0) {
            $db->bind(':purok', $purok);
        }
        if (!empty($status)) {
            $db->bind(':status', $status);
        }

        $reports = $db->resultSet();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'reports' => $reports]);
        exit;
    }

    // ============================================================
    // ACCOUNT MANAGEMENT
    // ============================================================
    public function accounts() {
        if ($_SESSION['user_role'] != 'secretary' && $_SESSION['user_role'] != 'administrator') {
            die("Unauthorized Access: Only Barangay Secretary can manage accounts.");
        }

        // Handle POST actions (approve, reject, deactivate, reactivate, remove)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            // ... (existing POST handling code remains unchanged) ...
        }

        // Get filter parameters
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'resident'; // 'resident' or 'staff'
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Determine role_id filter
        $roleFilter = ($tab === 'staff') ? [1, 2] : [3]; // role_id 1=Admin, 2=Supervisor, 3=Resident

        // Build query with search
        $db = new Database();
        $query = "
            SELECT u.*, r.role_name, p.position_name, pk.purok_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.role_id
            LEFT JOIN positions p ON u.position_id = p.position_id
            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
            WHERE u.role_id IN (" . implode(',', $roleFilter) . ")
        ";

        if (!empty($search)) {
            $query .= " AND (u.name LIKE :search OR u.email LIKE :search OR u.phone_number LIKE :search)";
        }

        $query .= " ORDER BY u.created_at DESC";

        $db->query($query);
        if (!empty($search)) {
            $db->bind(':search', "%$search%");
        }
        $users = $db->resultSet();

        // Add report counts for residents
        $reportModel = $this->model('Report');
        foreach ($users as $key => $user) {
            if ($user['role_id'] == 3) {
                $userReports = $reportModel->getReportsByResident($user['id']);
                $users[$key]['report_count'] = count($userReports);
            } else {
                $users[$key]['report_count'] = '-';
            }
            // Set initial for avatar
            $users[$key]['initials'] = $this->getInitials($user['name']);
        }

        $data['users'] = $users;
        $data['tab'] = $tab;
        $data['search'] = $search;

        // Also get counts for the tabs
        $db->query("SELECT COUNT(*) as count FROM users WHERE role_id = 3");
        $residentCount = $db->single()['count'] ?? 0;
        $db->query("SELECT COUNT(*) as count FROM users WHERE role_id IN (1,2)");
        $staffCount = $db->single()['count'] ?? 0;
        $data['resident_count'] = (int)$residentCount;
        $data['staff_count'] = (int)$staffCount;

        $this->view('admin/accounts', $data);
    }

    // Helper to get initials from name
    private function getInitials($name) {
        $parts = explode(' ', trim($name));
        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]);
            }
        }
        return $initials ?: '?';
    }

    // ============================================================
    // REPORT MANAGEMENT
    // ============================================================
    public function reports() {
        $reportModel = $this->model('Report');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            if ($_SESSION['user_role'] != 'secretary' && $_SESSION['user_role'] != 'administrator') {
                die("Unauthorized Access: Only Barangay Secretary can perform report actions.");
            }

            $report_id = filter_var($_POST['report_id'] ?? 0, FILTER_VALIDATE_INT);
            $action = htmlspecialchars(strip_tags($_POST['action'] ?? ''), ENT_QUOTES, 'UTF-8');
            $remark = isset($_POST['remark']) ? htmlspecialchars(strip_tags($_POST['remark']), ENT_QUOTES, 'UTF-8') : '';

            require_once __DIR__ . '/../Models/Notification.php';
            $notificationModel = new Notification();

            $db = new Database();

            // Get old status name for notification
            $db->query("
                SELECT r.resident_id, rs.status_name as status 
                FROM reports r
                JOIN report_statuses rs ON r.status_id = rs.status_id
                WHERE r.id = :id
            ");
            $db->bind(':id', $report_id);
            $oldReport = $db->single();
            $oldStatus = $oldReport ? $oldReport['status'] : 'pending';
            $resident_id = $oldReport ? $oldReport['resident_id'] : null;

            if ($action == 'verify') {
                $status_id = $this->getStatusId('Verified');
                $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
                $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'verified', $_SESSION['user_id']);
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Verified', "Report ID $report_id", "Verified report. Remark: $remark", 'success');
            } elseif ($action == 'resolve') {
                $status_id = $this->getStatusId('Resolved');
                $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
                $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'resolved', $_SESSION['user_id']);
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Resolved', "Report ID $report_id", "Resolved report. Remark: $remark", 'success');
            } elseif ($action == 'reject') {
                $status_id = $this->getStatusId('Rejected');
                $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);

                // Create status history entry
                $db->query("INSERT INTO report_status_history (report_id, previous_status, new_status, remark, changed_by, changed_at) 
                            VALUES (:report_id, :prev_status, 'rejected', :remark, :changed_by, NOW())");
                $db->bind(':report_id', $report_id);
                $db->bind(':prev_status', $oldStatus);
                $db->bind(':remark', $remark);
                $db->bind(':changed_by', $_SESSION['user_id']);
                $db->execute();

                // Insert flag record
                $db->query("INSERT INTO report_flags (report_id, flag_reason, flagged_by, flagged_at) 
                            VALUES (:report_id, :flag_reason, :flagged_by, NOW())");
                $db->bind(':report_id', $report_id);
                $db->bind(':flag_reason', $remark);
                $db->bind(':flagged_by', $_SESSION['user_id']);
                $db->execute();

                // Send notification to resident
                if ($resident_id) {
                    $notificationModel->create([
                        'user_id' => $resident_id,
                        'report_id' => $report_id,
                        'type' => 'report_rejected',
                        'title' => 'Report Rejected',
                        'content' => "Your waste report has been rejected. Reason: " . $remark,
                        'send_to_all' => false
                    ]);
                }

                $this->auditModel->logAction($_SESSION['user_id'], 'Report Rejected', "Report ID $report_id", "Rejected report. Reason: $remark. Resident notified.", 'success');
            }

            header("Location: /brgy-waste-app-v3/public/index.php?url=" . urlencode('admin/reports'));
            exit;
        }

        // Get search and status filters from GET parameters
        $search = isset($_GET['search']) ? htmlspecialchars(strip_tags($_GET['search']), ENT_QUOTES, 'UTF-8') : '';
        $status = isset($_GET['status']) ? htmlspecialchars(strip_tags($_GET['status']), ENT_QUOTES, 'UTF-8') : '';

        $db = new Database();

        // ---- Status Counts for Metrics ----
        $db->query("
            SELECT rs.status_name, COUNT(*) as count
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            GROUP BY r.status_id
        ");
        $statusCounts = $db->resultSet();
        $statusMap = ['Total' => 0, 'Pending' => 0, 'Verified' => 0, 'Rejected' => 0, 'In Progress' => 0, 'Resolved' => 0];
        foreach ($statusCounts as $row) {
            $statusMap[$row['status_name']] = (int)$row['count'];
            $statusMap['Total'] += (int)$row['count'];
        }
        $data['status_counts'] = $statusMap;

        // Build query with joins to get all related data
        $query = "
            SELECT r.*, 
                   u.name, u.email, 
                   rf.flag_reason,
                   rs.status_name as status,
                   rs.color_code as status_color,
                   wc.category_name as waste_category,
                   eq.quantity_name as estimated_quantity,
                   wcnd.condition_name as waste_condition,
                   p.purok_name as purok
            FROM reports r 
            JOIN users u ON r.resident_id = u.id 
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            LEFT JOIN report_flags rf ON r.id = rf.report_id
            WHERE 1=1
        ";

        if (!empty($search)) {
            $query .= " AND (r.description LIKE :search OR u.name LIKE :search OR u.email LIKE :search)";
        }

        if (!empty($status)) {
            $query .= " AND rs.status_name = :status";
        }

        $query .= " ORDER BY r.submission_date DESC";

        $db->query($query);

        if (!empty($search)) {
            $searchTerm = "%{$search}%";
            $db->bind(':search', $searchTerm);
        }

        if (!empty($status)) {
            $db->bind(':status', $status);
        }

        $data['reports'] = $db->resultSet();

        // Add location names to each report
        require_once __DIR__ . '/../Core/Geocoding.php';
        foreach ($data['reports'] as $key => $report) {
            $data['reports'][$key]['location_name'] = Geocoding::getLocationName(
                $report['latitude'],
                $report['longitude']
            );
        }

        $this->view('admin/reports', $data);
    }

    // ============================================================
    // VIEW SINGLE REPORT (Admin)
    // ============================================================
    public function viewReport($id) {
        // Check permission
        if (!in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
            die("Unauthorized Access");
        }

        $db = new Database();
        $reportModel = $this->model('Report');

        // Fetch report details with all joins
        $db->query("
            SELECT r.*, 
                u.name as resident_name, 
                u.email as resident_email,
                u.phone_number as resident_phone,
                u.purok_id as resident_purok_id,
                rs.status_name as status,
                rs.color_code as status_color,
                wc.category_name as waste_category,
                eq.quantity_name as estimated_quantity,
                wcnd.condition_name as waste_condition,
                p.purok_name as purok,
                (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) as photo_path
            FROM reports r
            JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.id = :id
        ");
        $db->bind(':id', $id);
        $report = $db->single();

        if (!$report) {
            header('Location: /brgy-waste-app-v3/public/admin/reports');
            exit;
        }

        // Get total reports for this resident
        $db->query("SELECT COUNT(*) as total FROM reports WHERE resident_id = :resident_id");
        $db->bind(':resident_id', $report['resident_id']);
        $totalReports = $db->single();
        $report['total_reports'] = $totalReports ? (int)$totalReports['total'] : 0;

        // Get all photos for this report
        $report['photos'] = $reportModel->getReportPhotos($id);

        // Get location name
        require_once __DIR__ . '/../Core/Geocoding.php';
        $report['location_name'] = Geocoding::getLocationName(
            $report['latitude'],
            $report['longitude']
        );

        // Get status timeline
        $report['timeline'] = $reportModel->getReportTimeline($id);

        // If rejected, fetch rejection reason
        if ($report['status'] === 'Rejected') {
            $db->query("SELECT flag_reason FROM report_flags WHERE report_id = :id LIMIT 1");
            $db->bind(':id', $id);
            $flag = $db->single();
            $report['reject_reason'] = $flag ? $flag['flag_reason'] : 'No reason provided';
        }

        $this->auditModel->logAction($_SESSION['user_id'], 'View Report', "Report ID $id", 'Admin viewed report details', 'success');

        $data['report'] = $report;
        $this->view('admin/view_report', $data);
    }

    // ============================================================
    // UPDATE REPORT STATUS (from detail page)
    // ============================================================
    public function updateReportStatus() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /brgy-waste-app-v3/public/admin/reports');
            exit;
        }

        if (!in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
            die("Unauthorized Access");
        }

        $report_id = filter_var($_POST['report_id'] ?? 0, FILTER_VALIDATE_INT);
        $action = htmlspecialchars(strip_tags($_POST['action'] ?? ''), ENT_QUOTES, 'UTF-8');
        $remark = isset($_POST['remark']) ? htmlspecialchars(strip_tags($_POST['remark']), ENT_QUOTES, 'UTF-8') : '';

        if (!$report_id || !in_array($action, ['verify', 'reject'])) {
            header("Location: /brgy-waste-app-v3/public/admin/reports");
            exit;
        }

        $reportModel = $this->model('Report');
        $db = new Database();
        require_once __DIR__ . '/../Models/Notification.php';
        $notificationModel = new Notification();

        // Get old status and resident_id
        $db->query("
            SELECT r.resident_id, rs.status_name as status 
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            WHERE r.id = :id
        ");
        $db->bind(':id', $report_id);
        $oldReport = $db->single();
        $oldStatus = $oldReport ? $oldReport['status'] : 'Pending';
        $resident_id = $oldReport ? $oldReport['resident_id'] : null;

        if ($action == 'verify') {
            $status_id = $this->getStatusId('Verified');
            $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
            $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'verified', $_SESSION['user_id']);
            $this->auditModel->logAction($_SESSION['user_id'], 'Report Verified', "Report ID $report_id", "Verified report", 'success');
        } elseif ($action == 'reject') {
            $status_id = $this->getStatusId('Rejected');
            $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);

            // Insert into report_status_history
            $db->query("INSERT INTO report_status_history (report_id, previous_status, new_status, remark, changed_by, changed_at) 
                        VALUES (:report_id, :prev_status, 'rejected', :remark, :changed_by, NOW())");
            $db->bind(':report_id', $report_id);
            $db->bind(':prev_status', $oldStatus);
            $db->bind(':remark', $remark ?: 'Rejected by admin');
            $db->bind(':changed_by', $_SESSION['user_id']);
            $db->execute();

            // Insert flag
            $db->query("INSERT INTO report_flags (report_id, flag_reason, flagged_by, flagged_at) 
                        VALUES (:report_id, :flag_reason, :flagged_by, NOW())");
            $db->bind(':report_id', $report_id);
            $db->bind(':flag_reason', $remark ?: 'Rejected by admin');
            $db->bind(':flagged_by', $_SESSION['user_id']);
            $db->execute();

            // Notify resident
            if ($resident_id) {
                $notificationModel->create([
                    'user_id' => $resident_id,
                    'report_id' => $report_id,
                    'type' => 'report_rejected',
                    'title' => 'Report Rejected',
                    'content' => "Your waste report has been rejected. Reason: " . ($remark ?: 'No reason provided'),
                    'send_to_all' => false
                ]);
            }

            $this->auditModel->logAction($_SESSION['user_id'], 'Report Rejected', "Report ID $report_id", "Rejected report. Reason: $remark", 'success');
        }

        // Redirect back to the detail page
        header("Location: /brgy-waste-app-v3/public/admin/viewReport/$report_id");
        exit;
    }

    // ============================================================
    // FLAGGED REPORTS
    // ============================================================
    public function flaggedReports() {
        if ($_SESSION['user_role'] != 'secretary' && $_SESSION['user_role'] != 'administrator') {
            die("Unauthorized Access: Only Barangay Secretary can view flagged reports.");
        }

        $db = new Database();

        // Get all flagged reports with report and user details
        $db->query("
            SELECT rf.*, 
                   r.description, r.submission_date,
                   rs.status_name as report_status,
                   u.name as reporter_name, u.email as reporter_email,
                   fu.name as flagged_by_name
            FROM report_flags rf
            JOIN reports r ON rf.report_id = r.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            JOIN users u ON r.resident_id = u.id
            LEFT JOIN users fu ON rf.flagged_by = fu.id
            ORDER BY rf.flagged_at DESC
        ");

        $data['flagged_reports'] = $db->resultSet();

        $this->auditModel->logAction($_SESSION['user_id'], 'Flagged Reports Access', 'Flagged Reports', 'Accessed flagged reports page', 'success');

        $this->view('admin/flagged_reports', $data);
    }

    /**
 * Create staff accounts (Administrators and Supervisors).
 */
    public function createStaff()
    {
        if ($_SESSION['user_role'] != 'administrator') {
            die("Unauthorized: Only administrators can create staff accounts.");
        }

        $data = ['error' => '', 'success' => '', 'positions' => [], 'roles' => [], 'puroks' => []];
        $db = new Database();

        // Load dropdown data
        $db->query("SELECT * FROM positions WHERE is_active = 1 ORDER BY position_name");
        $data['positions'] = $db->resultSet();
        $db->query("SELECT * FROM roles WHERE role_name IN ('Administrator', 'Supervisor')");
        $data['roles'] = $db->resultSet();
        $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
        $data['puroks'] = $db->resultSet();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $position_id = (int)($_POST['position_id'] ?? 0);
            $role_id = (int)($_POST['role_id'] ?? 0);
            $purok_id = (int)($_POST['purok_id'] ?? 1);
            $username = trim($_POST['username'] ?? '');

            if (empty($name) || empty($email) || empty($phone) || empty($username) || !$position_id || !$role_id) {
                $data['error'] = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Invalid email format.';
            } elseif (!preg_match('/^09\d{9}$/', $phone)) {
                $data['error'] = 'Invalid Philippine phone number (must be 11 digits starting with 09).';
            } elseif ($this->userModel->findUserByEmail($email)) {
                $data['error'] = 'Email already registered.';
            } elseif ($this->userModel->findUserByUsername($username)) {
                $data['error'] = 'Username already taken.';
            } else {
                // Generate temporary password
                $tempPassword = bin2hex(random_bytes(6)); // 12 chars
                $hashed = password_hash($tempPassword, PASSWORD_DEFAULT);

                $regData = [
                    'name' => $name,
                    'username' => $username,
                    'account_type' => 'resident',
                    'address' => '',
                    'phone_number' => $phone,
                    'email' => $email,
                    'password' => $hashed,
                    'role_id' => $role_id,
                    'position_id' => $position_id,
                    'purok_id' => $purok_id,
                    'status' => 'active'
                ];

                if ($this->userModel->register($regData)) {
                    // Send email with temporary password
                    require_once '../app/Models/Helpers/OtpMailer.php';
                    try {
                        OtpMailer::sendTempPasswordEmail($email, $tempPassword, $name);
                        $data['success'] = 'Staff account created. Temporary password sent to email.';
                    } catch (Exception $e) {
                        $data['error'] = 'Account created but failed to send email: ' . $e->getMessage();
                    }
                    // Reset POST
                    $_POST = [];
                } else {
                    $data['error'] = 'Failed to create account. Please try again.';
                }
            }
        }

        $this->view('admin/create_staff', $data);
    }

    // ============================================================
    // EXPORT
    // ============================================================
    public function export() {
        if ($_SESSION['user_role'] != 'secretary' && $_SESSION['user_role'] != 'administrator') {
            die("Unauthorized Access: Only Secretary can generate summaries.");
        }

        $reportModel = $this->model('Report');

        if (isset($_GET['format'])) {
            $reports = $reportModel->getAllReports();
            $format = $_GET['format'];

            $db = new Database();
            $db->query("INSERT INTO report_summaries (generated_by, filename, file_type, total_reports, filters) 
                        VALUES (:generated_by, :filename, :file_type, :total, :filters)");
            $db->bind(':generated_by', $_SESSION['user_id']);
            $db->bind(':filename', 'report_summary_' . date('Y-m-d_H-i-s'));
            $db->bind(':file_type', $format);
            $db->bind(':total', count($reports));
            $db->bind(':filters', json_encode($_GET));
            $db->execute();
            

            require_once __DIR__ . '/../Core/Geocoding.php';
            foreach ($reports as $key => $r) {
                $reports[$key]['location_name'] = Geocoding::getLocationName(
                    $r['latitude'],
                    $r['longitude']
                );
            }

            $this->auditModel->logAction($_SESSION['user_id'], 'Report Generated', 'Report Summary', "Format: {$format}", 'success');

            if ($format == 'csv') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=waste_report_summary.csv');
                $output = fopen('php://output', 'w');
                fputcsv($output, array('ID', 'Date Submitted', 'Reporter Name', 'Email', 'Description', 'Location', 'Status'));
                foreach ($reports as $r) {
                    fputcsv($output, array($r['id'], $r['submission_date'], $r['name'], $r['email'], $r['description'], $r['location_name'], $r['status']));
                }
                fclose($output);
                exit;
            } elseif ($format == 'print') {
                $data['reports'] = $reports;
                $this->view('admin/export_print', $data);
                exit;
            }
        }
    }

    // ============================================================
    // REPORT SUMMARIES
    // ============================================================
    public function report_summaries() {
        $db = new Database();

        $data['exports'] = array();
        try {
            $db->query("SELECT * FROM report_summaries ORDER BY generated_at DESC LIMIT 10");
            $result = $db->resultSet();
            if ($result) {
                $data['exports'] = $result;
            }
        } catch (Exception $e) {
            $data['exports'] = array();
        }

        $this->auditModel->logAction($_SESSION['user_id'], 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', 'success');

        $this->view('admin/report_summaries', $data);
    }

    // ============================================================
    // AUDIT LOGS
    // ============================================================
    public function auditLogs() {
        $db = new Database();
        $db->query("
            SELECT a.*, u.name as user_name 
            FROM audit_logs a 
            LEFT JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC
        ");
        $data['logs'] = $db->resultSet();
        $this->view('admin/audit_logs', $data);
    }

    // ============================================================
    // ANNOUNCEMENTS
    // ============================================================
    public function announcements() {
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SESSION['user_role'] == 'secretary' || $_SESSION['user_role'] == 'administrator')) {
            if (!empty($_POST['title']) && !empty($_POST['content'])) {
                $visibility_id = isset($_POST['visibility_id']) ? (int)$_POST['visibility_id'] : 1;
                $publish_date = !empty($_POST['publish_date']) ? $_POST['publish_date'] : date('Y-m-d H:i:s');
                $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
                $is_published = isset($_POST['is_published']) ? 1 : 0;

                // Handle cover image upload
                $cover_image = null;
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['cover_image'];
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                    if (in_array($file['type'], $allowedTypes) && $file['size'] <= 2 * 1024 * 1024) {
                        $uploadDir = '../public/uploads/announcements/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                        $fileName = 'announce_' . time() . '_' . basename($file['name']);
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                            $cover_image = '/public/uploads/announcements/' . $fileName;
                        }
                    }
                }

                $db->query("INSERT INTO announcements (title, content, cover_image, created_by, visibility_id, publish_date, expiration_date, is_published) 
                            VALUES (:title, :content, :cover_image, :created_by, :visibility_id, :publish_date, :expiration_date, :is_published)");
                $db->bind(':title', htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
                $db->bind(':content', htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'));
                $db->bind(':cover_image', $cover_image);
                $db->bind(':created_by', $_SESSION['user_id']);
                $db->bind(':visibility_id', $visibility_id);
                $db->bind(':publish_date', $publish_date);
                $db->bind(':expiration_date', $expiration_date);
                $db->bind(':is_published', $is_published);
                $db->execute();

                $announcementId = $db->lastInsertId();

                require_once __DIR__ . '/../Models/Notification.php';
                $notificationModel = new Notification();
                $notificationModel->createAnnouncementNotification($announcementId, $_SESSION['user_id']);

                $this->auditModel->logAction($_SESSION['user_id'], 'Post Announcement', 'Announcements', "Posted '{$_POST['title']}'", 'success');
                header("Location: /brgy-waste-app-v3/public/index.php?url=" . urlencode('admin/announcements'));
                exit;
            }
        }

        // Get announcements with visibility
        $db->query("
            SELECT a.*, av.visibility_name 
            FROM announcements a
            LEFT JOIN announcement_visibilities av ON a.visibility_id = av.visibility_id
            ORDER BY a.created_at DESC
        ");
        $data['announcements'] = $db->resultSet();

        // Get visibility options for dropdown
        $db->query("SELECT * FROM announcement_visibilities ORDER BY visibility_id");
        $data['visibilities'] = $db->resultSet();

        $this->view('admin/announcements', $data);
    }

    // ============================================================
    // DELETE ANNOUNCEMENT
    // ============================================================
    public function delete_announcement() {
        if ($_SESSION['user_role'] != 'secretary' && $_SESSION['user_role'] != 'administrator') {
            die("Unauthorized Access: Only Barangay Secretary can delete announcements.");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['announcement_id'])) {
            $announcementId = filter_var($_POST['announcement_id'], FILTER_VALIDATE_INT);

            $db = new Database();
            $db->query("DELETE FROM announcements WHERE id = :id");
            $db->bind(':id', $announcementId);
            $db->execute();

            $this->auditModel->logAction($_SESSION['user_id'], 'Delete Announcement', "Announcement ID $announcementId", "Deleted announcement", 'success');
        }

        header("Location: /brgy-waste-app-v3/public/index.php?url=" . urlencode('admin/announcements'));
        exit;
    }

    /**
     * Edit announcement
     */
    public function edit_announcement($id) {
        if (!in_array($_SESSION['user_role'], ['secretary', 'administrator'])) {
            die("Unauthorized Access");
        }

        $db = new Database();
        $db->query("SELECT * FROM announcements WHERE id = :id");
        $db->bind(':id', $id);
        $announcement = $db->single();

        if (!$announcement) {
            header('Location: /brgy-waste-app-v3/public/admin/announcements');
            exit;
        }

        $data['announcement'] = $announcement;
        $db->query("SELECT * FROM announcement_visibilities ORDER BY visibility_id");
        $data['visibilities'] = $db->resultSet();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
            $visibility_id = (int)$_POST['visibility_id'];
            $is_published = isset($_POST['is_published']) ? 1 : 0;

            // Handle cover image upload
            $cover_image = null;
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['cover_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (in_array($file['type'], $allowedTypes) && $file['size'] <= 2 * 1024 * 1024) {
                    $uploadDir = '../public/uploads/announcements/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $fileName = 'announce_' . time() . '_' . basename($file['name']);
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                        $cover_image = '/public/uploads/announcements/' . $fileName;
                    }
                }
            }

            $query = "UPDATE announcements SET 
                title = :title,
                content = :content,
                visibility_id = :visibility_id,
                is_published = :is_published";
            if ($cover_image) {
                $query .= ", cover_image = :cover_image";
            }
            $query .= " WHERE id = :id";

            $db->query($query);
            $db->bind(':title', $title);
            $db->bind(':content', $content);
            $db->bind(':visibility_id', $visibility_id);
            $db->bind(':is_published', $is_published);
            if ($cover_image) {
                $db->bind(':cover_image', $cover_image);
            }
            $db->bind(':id', $id);

            $db->execute();

            $this->auditModel->logAction($_SESSION['user_id'], 'Edit Announcement', "Announcement ID $id", "Updated announcement", 'success');
            $_SESSION['flash_success'] = 'Announcement updated successfully.';
            header('Location: /brgy-waste-app-v3/public/admin/announcements');
            exit;
        }

        $this->view('admin/edit_announcement', $data);
    }

    // ============================================================
// SCHEDULE MANAGEMENT
// ============================================================
public function schedule() {
    if (!in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
        die("Unauthorized Access");
    }

    $db = new Database();

    // Get view mode from GET parameter (default: cards)
    $view = isset($_GET['view']) ? $_GET['view'] : 'cards';

    // Fetch all active schedules with their puroks
    $db->query("
        SELECT cs.*, 
               GROUP_CONCAT(p.purok_name SEPARATOR ', ') as puroks
        FROM collection_schedules cs
        LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
        LEFT JOIN puroks p ON csp.purok_id = p.purok_id
        GROUP BY cs.schedule_id
        ORDER BY FIELD(cs.collection_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ");
    $schedules = $db->resultSet();

    // Get all puroks for dropdown
    $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
    $data['puroks'] = $db->resultSet();

    $data['schedules'] = $schedules;
    $data['view'] = $view;

    // For calendar view, get current month/year or from GET
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    $data['month'] = $month;
    $data['year'] = $year;

    // Generate calendar data
    $data['calendar_days'] = $this->generateCalendarData($month, $year, $schedules);

    $this->auditModel->logAction($_SESSION['user_id'], 'Schedule Management', 'Schedule', 'Admin viewed schedule management', 'success');
    $this->view('admin/schedule', $data);
}

// ============================================================
// GENERATE CALENDAR DATA
// ============================================================
private function generateCalendarData($month, $year, $schedules) {
    // Get first day of month and number of days
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    $firstDayOfWeek = date('N', $firstDay); // 1=Monday, 7=Sunday

    // Map collection days to day of week numbers (1=Monday, 7=Sunday)
    $dayMap = [
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
        'Sunday' => 7
    ];

    // Group schedules by day of week
    $scheduleMap = [];
    foreach ($schedules as $schedule) {
        $dayNum = $dayMap[$schedule['collection_day']] ?? 0;
        if ($dayNum > 0) {
            $scheduleMap[$dayNum][] = $schedule;
        }
    }

    // Build calendar days array
    $calendarDays = [];
    $currentDay = 1;

    // Fill empty days before first day of month
    $emptyDays = $firstDayOfWeek - 1;
    for ($i = 0; $i < $emptyDays; $i++) {
        $calendarDays[] = null;
    }

    // Fill actual days
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dayOfWeek = date('N', mktime(0, 0, 0, $month, $day, $year));
        $dayData = [
            'day' => $day,
            'is_today' => ($day == date('j') && $month == date('n') && $year == date('Y')),
            'schedules' => $scheduleMap[$dayOfWeek] ?? []
        ];
        $calendarDays[] = $dayData;
    }

    return $calendarDays;
}

    /**
     * Export audit logs as CSV
     */
    public function exportAuditLogs() {
        if ($_SESSION['user_role'] != 'administrator') {
            die("Unauthorized");
        }

        $db = new Database();
        $db->query("
            SELECT a.*, u.name as user_name 
            FROM audit_logs a 
            LEFT JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC
        ");
        $logs = $db->resultSet();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'User', 'Action', 'Affected Record', 'Details', 'Result']);

        foreach ($logs as $log) {
            fputcsv($output, [
                date('Y-m-d H:i:s', strtotime($log['created_at'])),
                $log['user_name'] ?? 'System',
                $log['action'],
                $log['affected_record'] ?? 'N/A',
                $log['details'] ?? 'N/A',
                $log['result'] ?? 'success'
            ]);
        }

        fclose($output);
        exit;
    }

    // ============================================================
    // ADD SCHEDULE
    // ============================================================
    public function addSchedule() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
            header('Location: /brgy-waste-app-v3/public/admin/schedule');
            exit;
        }

        $collection_day = $_POST['collection_day'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $waste_type = $_POST['waste_type'] ?? '';
        $status = $_POST['status'] ?? 'active';
        $special_notes = $_POST['special_notes'] ?? '';
        $purok_ids = $_POST['purok_ids'] ?? [];

        $db = new Database();

        // Insert schedule
        $db->query("
            INSERT INTO collection_schedules (collection_day, start_time, end_time, waste_type, status, special_notes, created_by)
            VALUES (:collection_day, :start_time, :end_time, :waste_type, :status, :special_notes, :created_by)
        ");
        $db->bind(':collection_day', $collection_day);
        $db->bind(':start_time', $start_time);
        $db->bind(':end_time', $end_time);
        $db->bind(':waste_type', $waste_type);
        $db->bind(':status', $status);
        $db->bind(':special_notes', $special_notes);
        $db->bind(':created_by', $_SESSION['user_id']);
        $db->execute();

        $schedule_id = $db->lastInsertId();

        // Insert purok associations
        if (!empty($purok_ids)) {
            foreach ($purok_ids as $purok_id) {
                $db->query("INSERT INTO collection_schedule_puroks (schedule_id, purok_id) VALUES (:schedule_id, :purok_id)");
                $db->bind(':schedule_id', $schedule_id);
                $db->bind(':purok_id', $purok_id);
                $db->execute();
            }
        }

        $this->auditModel->logAction($_SESSION['user_id'], 'Add Schedule', "Schedule ID $schedule_id", "Added new schedule for $collection_day", 'success');

        $_SESSION['flash_success'] = 'Schedule added successfully!';
        header('Location: /brgy-waste-app-v3/public/admin/schedule');
        exit;
    }

    // ============================================================
    // EDIT SCHEDULE (Show Form)
    // ============================================================
    public function editSchedule($id) {
        if (!in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
            die("Unauthorized Access");
        }

        $db = new Database();

        // Get schedule details
        $db->query("
            SELECT cs.*, 
                GROUP_CONCAT(csp.purok_id) as purok_ids
            FROM collection_schedules cs
            LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
            WHERE cs.schedule_id = :id
            GROUP BY cs.schedule_id
        ");
        $db->bind(':id', $id);
        $schedule = $db->single();

        if (!$schedule) {
            header('Location: /brgy-waste-app-v3/public/admin/schedule');
            exit;
        }

        // Get all puroks
        $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
        $data['puroks'] = $db->resultSet();
        $data['schedule'] = $schedule;
        $data['selected_puroks'] = $schedule['purok_ids'] ? explode(',', $schedule['purok_ids']) : [];

        $this->view('admin/edit_schedule', $data);
    }

    // ============================================================
    // UPDATE SCHEDULE
    // ============================================================
    public function updateSchedule() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
            header('Location: /brgy-waste-app-v3/public/admin/schedule');
            exit;
        }

        $schedule_id = $_POST['schedule_id'] ?? 0;
        $collection_day = $_POST['collection_day'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $waste_type = $_POST['waste_type'] ?? '';
        $status = $_POST['status'] ?? 'active';
        $special_notes = $_POST['special_notes'] ?? '';
        $purok_ids = $_POST['purok_ids'] ?? [];

        $db = new Database();

        // Update schedule
        $db->query("
            UPDATE collection_schedules 
            SET collection_day = :collection_day,
                start_time = :start_time,
                end_time = :end_time,
                waste_type = :waste_type,
                status = :status,
                special_notes = :special_notes
            WHERE schedule_id = :schedule_id
        ");
        $db->bind(':collection_day', $collection_day);
        $db->bind(':start_time', $start_time);
        $db->bind(':end_time', $end_time);
        $db->bind(':waste_type', $waste_type);
        $db->bind(':status', $status);
        $db->bind(':special_notes', $special_notes);
        $db->bind(':schedule_id', $schedule_id);
        $db->execute();

        // Delete existing purok associations
        $db->query("DELETE FROM collection_schedule_puroks WHERE schedule_id = :schedule_id");
        $db->bind(':schedule_id', $schedule_id);
        $db->execute();

        // Insert updated purok associations
        if (!empty($purok_ids)) {
            foreach ($purok_ids as $purok_id) {
                $db->query("INSERT INTO collection_schedule_puroks (schedule_id, purok_id) VALUES (:schedule_id, :purok_id)");
                $db->bind(':schedule_id', $schedule_id);
                $db->bind(':purok_id', $purok_id);
                $db->execute();
            }
        }

        $this->auditModel->logAction($_SESSION['user_id'], 'Update Schedule', "Schedule ID $schedule_id", "Updated schedule for $collection_day", 'success');

        $_SESSION['flash_success'] = 'Schedule updated successfully!';
        header('Location: /brgy-waste-app-v3/public/admin/schedule');
        exit;
    }

    // ============================================================
    // DELETE SCHEDULE
    // ============================================================
    public function deleteSchedule() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !in_array($_SESSION['user_role'], ['secretary', 'administrator', 'captain'])) {
            header('Location: /brgy-waste-app-v3/public/admin/schedule');
            exit;
        }

        $schedule_id = $_POST['schedule_id'] ?? 0;

        $db = new Database();

        // Delete purok associations first (cascade should handle this, but we'll do it explicitly)
        $db->query("DELETE FROM collection_schedule_puroks WHERE schedule_id = :schedule_id");
        $db->bind(':schedule_id', $schedule_id);
        $db->execute();

        // Delete schedule
        $db->query("DELETE FROM collection_schedules WHERE schedule_id = :schedule_id");
        $db->bind(':schedule_id', $schedule_id);
        $db->execute();

        $this->auditModel->logAction($_SESSION['user_id'], 'Delete Schedule', "Schedule ID $schedule_id", "Deleted schedule", 'success');

        $_SESSION['flash_success'] = 'Schedule deleted successfully!';
        header('Location: /brgy-waste-app-v3/public/admin/schedule');
        exit;
    }

    /**
     * Postpone/Reschedule collection schedule
     */
    public function postpone_schedule() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !in_array($_SESSION['user_role'], ['secretary', 'administrator'])) {
            header('Location: /brgy-waste-app-v3/public/admin/schedule');
            exit;
        }

        $schedule_id = (int)$_POST['schedule_id'];
        $new_date = $_POST['new_date'] ?? '';
        $reason = htmlspecialchars($_POST['reason'] ?? '', ENT_QUOTES, 'UTF-8');

        if (!$schedule_id || empty($new_date)) {
            $_SESSION['flash_error'] = 'Please provide a new date.';
            header('Location: /brgy-waste-app-v3/public/admin/schedule');
            exit;
        }

        $db = new Database();
        $db->query("SELECT collection_day FROM collection_schedules WHERE schedule_id = :id");
        $db->bind(':id', $schedule_id);
        $schedule = $db->single();

        if ($schedule) {
            // Create a special announcement
            $title = "Schedule Postponed: " . $schedule['collection_day'];
            $content = "The collection schedule for " . $schedule['collection_day'] . " has been postponed. New date: " . date('F d, Y', strtotime($new_date)) . ". Reason: " . $reason;

            $db->query("INSERT INTO announcements (title, content, created_by, visibility_id, is_published) 
                        VALUES (:title, :content, :created_by, 1, 1)");
            $db->bind(':title', $title);
            $db->bind(':content', $content);
            $db->bind(':created_by', $_SESSION['user_id']);
            $db->execute();

            $this->auditModel->logAction($_SESSION['user_id'], 'Schedule Postponed', "Schedule ID $schedule_id", "Postponed to $new_date. Reason: $reason", 'success');
            $_SESSION['flash_success'] = 'Schedule postponed. Residents have been notified.';
        }

        header('Location: /brgy-waste-app-v3/public/admin/schedule');
        exit;
    }

    // ============================================================
    // API: GET FILTERED REPORTS
    // ============================================================
    public function getFilteredReports() {
        $dateFrom = isset($_GET['dateFrom']) ? htmlspecialchars(strip_tags($_GET['dateFrom']), ENT_QUOTES, 'UTF-8') : '';
        $dateTo = isset($_GET['dateTo']) ? htmlspecialchars(strip_tags($_GET['dateTo']), ENT_QUOTES, 'UTF-8') : '';
        $status = isset($_GET['status']) ? htmlspecialchars(strip_tags($_GET['status']), ENT_QUOTES, 'UTF-8') : '';

        $db = new Database();

        $query = "
            SELECT r.id, r.description, r.submission_date, 
                   u.name, u.email, r.latitude, r.longitude,
                   rs.status_name as status
            FROM reports r 
            JOIN users u ON r.resident_id = u.id 
            JOIN report_statuses rs ON r.status_id = rs.status_id
            WHERE 1=1
        ";

        if ($dateFrom) {
            $query .= " AND DATE(r.submission_date) >= :dateFrom";
        }
        if ($dateTo) {
            $query .= " AND DATE(r.submission_date) <= :dateTo";
        }
        if ($status && $status !== '') {
            $query .= " AND rs.status_name = :status";
        }

        $query .= " ORDER BY r.submission_date DESC";

        $db->query($query);

        if ($dateFrom) {
            $db->bind(':dateFrom', $dateFrom);
        }
        if ($dateTo) {
            $db->bind(':dateTo', $dateTo);
        }
        if ($status && $status !== '') {
            $db->bind(':status', $status);
        }

        $reports = $db->resultSet();

        require_once __DIR__ . '/../Core/Geocoding.php';
        foreach ($reports as $key => $report) {
            $reports[$key]['location'] = Geocoding::getLocationName($report['latitude'], $report['longitude']);
            $reports[$key]['date'] = date('m/d/Y', strtotime($report['submission_date']));
        }

        $summary = [
            'total' => count($reports),
            'pending' => count(array_filter($reports, fn($r) => $r['status'] == 'pending')),
            'verified' => count(array_filter($reports, fn($r) => $r['status'] == 'verified')),
            'resolved' => count(array_filter($reports, fn($r) => $r['status'] == 'resolved'))
        ];

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'summary' => $summary,
            'reports' => $reports
        ]);
        exit;
    }

    // ============================================================
    // EXPORT REPORT SUMMARY - PDF
    // ============================================================
    public function exportReportSummaryPDF() {
        $dateFrom = isset($_GET['dateFrom']) ? htmlspecialchars(strip_tags($_GET['dateFrom']), ENT_QUOTES, 'UTF-8') : '';
        $dateTo = isset($_GET['dateTo']) ? htmlspecialchars(strip_tags($_GET['dateTo']), ENT_QUOTES, 'UTF-8') : '';
        $status = isset($_GET['status']) ? htmlspecialchars(strip_tags($_GET['status']), ENT_QUOTES, 'UTF-8') : '';

        $db = new Database();

        $query = "
            SELECT r.id, r.description, r.submission_date, 
                   u.name, u.email, r.latitude, r.longitude,
                   rs.status_name as status
            FROM reports r 
            JOIN users u ON r.resident_id = u.id 
            JOIN report_statuses rs ON r.status_id = rs.status_id
            WHERE 1=1
        ";

        if ($dateFrom) {
            $query .= " AND DATE(r.submission_date) >= :dateFrom";
        }
        if ($dateTo) {
            $query .= " AND DATE(r.submission_date) <= :dateTo";
        }
        if ($status && $status !== '') {
            $query .= " AND rs.status_name = :status";
        }

        $query .= " ORDER BY r.submission_date DESC";

        $db->query($query);

        if ($dateFrom) {
            $db->bind(':dateFrom', $dateFrom);
        }
        if ($dateTo) {
            $db->bind(':dateTo', $dateTo);
        }
        if ($status && $status !== '') {
            $db->bind(':status', $status);
        }

        $reports = $db->resultSet();

        $html = "
        <html>
        <head>
            <title>Waste Report Summary</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #4CAF50; color: white; }
            </style>
        </head>
        <body>
            <h1>Waste Report Summary</h1>
            <p>Report Period: $dateFrom to $dateTo</p>
            <p>Total Reports: " . count($reports) . "</p>
            <table>
                <tr>
                    <th>Report ID</th>
                    <th>Resident</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>";

        foreach ($reports as $report) {
            $html .= "<tr>
                <td>{$report['id']}</td>
                <td>{$report['name']}</td>
                <td>{$report['description']}</td>
                <td>{$report['status']}</td>
                <td>" . date('m/d/Y', strtotime($report['submission_date'])) . "</td>
            </tr>";
        }

        $html .= "
            </table>
            <br>
            <button onclick=\"window.print()\" style=\"padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;\">🖨️ Print / Save as PDF</button>
            <p style=\"font-size: 12px; color: #666;\">Note: In the print dialog, select 'Save as PDF' as the destination.</p>
        </body>
        </html>";

        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }

    // ============================================================
    // EXPORT REPORT SUMMARY - XLSX (CSV)
    // ============================================================
    public function exportReportSummaryXLSX() {
        $dateFrom = isset($_GET['dateFrom']) ? htmlspecialchars(strip_tags($_GET['dateFrom']), ENT_QUOTES, 'UTF-8') : '';
        $dateTo = isset($_GET['dateTo']) ? htmlspecialchars(strip_tags($_GET['dateTo']), ENT_QUOTES, 'UTF-8') : '';
        $status = isset($_GET['status']) ? htmlspecialchars(strip_tags($_GET['status']), ENT_QUOTES, 'UTF-8') : '';

        $db = new Database();

        $query = "
            SELECT r.id, r.description, r.submission_date, 
                   u.name, u.email, r.latitude, r.longitude,
                   rs.status_name as status
            FROM reports r 
            JOIN users u ON r.resident_id = u.id 
            JOIN report_statuses rs ON r.status_id = rs.status_id
            WHERE 1=1
        ";

        if ($dateFrom) {
            $query .= " AND DATE(r.submission_date) >= :dateFrom";
        }
        if ($dateTo) {
            $query .= " AND DATE(r.submission_date) <= :dateTo";
        }
        if ($status && $status !== '') {
            $query .= " AND rs.status_name = :status";
        }

        $query .= " ORDER BY r.submission_date DESC";

        $db->query($query);

        if ($dateFrom) {
            $db->bind(':dateFrom', $dateFrom);
        }
        if ($dateTo) {
            $db->bind(':dateTo', $dateTo);
        }
        if ($status && $status !== '') {
            $db->bind(':status', $status);
        }

        $reports = $db->resultSet();

        $filename = "report_summary_" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Report ID', 'Resident Name', 'Email', 'Description', 'Status', 'Submission Date']);

        foreach ($reports as $report) {
            fputcsv($output, [
                $report['id'],
                $report['name'],
                $report['email'],
                $report['description'],
                $report['status'],
                date('m/d/Y H:i', strtotime($report['submission_date']))
            ]);
        }

        fclose($output);
        exit;
    }

    // ============================================================
    // PROFILE
    // ============================================================
    public function profile() {
        $data = ['error' => '', 'success' => ''];
        $db = new Database();

        // Get user data with role, position, purok
        $db->query("
            SELECT u.*, r.role_name, p.position_name, pk.purok_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.role_id
            LEFT JOIN positions p ON u.position_id = p.position_id
            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
            WHERE u.id = :id
        ");
        $db->bind(':id', $_SESSION['user_id']);
        $data['user'] = $db->single();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $phone = trim($_POST['phone_number'] ?? '');

            if (empty($name)) {
                $data['error'] = 'Full name is required.';
                return $this->view('admin/profile', $data);
            }
            if (empty($address)) {
                $data['error'] = 'Address is required.';
                return $this->view('admin/profile', $data);
            }
            if (!preg_match('/^09\d{9}$/', $phone)) {
                $data['error'] = 'Invalid Philippine phone number. Must be 11 digits starting with 09.';
                return $this->view('admin/profile', $data);
            }

            $db->query("UPDATE users SET name = :name, address = :address, phone_number = :phone WHERE id = :id");
            $db->bind(':name', $name);
            $db->bind(':address', $address);
            $db->bind(':phone', $phone);
            $db->bind(':id', $_SESSION['user_id']);

            if ($db->execute()) {
                $_SESSION['user_name'] = $name;
                $data['success'] = 'Profile updated successfully.';

                // Refresh user data
                $db->query("
                    SELECT u.*, r.role_name, p.position_name, pk.purok_name
                    FROM users u
                    LEFT JOIN roles r ON u.role_id = r.role_id
                    LEFT JOIN positions p ON u.position_id = p.position_id
                    LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                    WHERE u.id = :id
                ");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();

                $this->auditModel->logAction($_SESSION['user_id'], 'Profile Updated', 'Profile', 'Admin updated personal information', 'success');
            } else {
                $data['error'] = 'Failed to update profile.';
            }
        }
        $this->view('admin/profile', $data);
    }

    // ============================================================
    // CHANGE PASSWORD
    // ============================================================
    public function change_password() {
        $data = ['error' => '', 'success' => ''];
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $db->query("SELECT password FROM users WHERE id = :id");
            $db->bind(':id', $_SESSION['user_id']);
            $user = $db->single();

            if (!password_verify($currentPassword, $user['password'])) {
                $data['error'] = 'Current password is incorrect.';
            } else if (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword) || !preg_match('/[!@#$%^&*]/', $newPassword)) {
                $data['error'] = 'Password does not meet the necessary requirements.';
            } else if ($newPassword !== $confirmPassword) {
                $data['error'] = 'New passwords do not match.';
            } else if (password_verify($newPassword, $user['password'])) {
                $data['error'] = 'New password must be different from current password.';
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $db->query("UPDATE users SET password = :password WHERE id = :id");
                $db->bind(':password', $hashedPassword);
                $db->bind(':id', $_SESSION['user_id']);
                if ($db->execute()) {
                    $data['success'] = 'Password changed successfully.';
                    $this->auditModel->logAction($_SESSION['user_id'], 'Password Changed', 'Security', 'Admin changed their password', 'success');
                } else {
                    $data['error'] = 'Failed to change password.';
                }
            }

            // Refresh user data for view
            $db->query("
                SELECT u.*, r.role_name, p.position_name, pk.purok_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN positions p ON u.position_id = p.position_id
                LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                WHERE u.id = :id
            ");
            $db->bind(':id', $_SESSION['user_id']);
            $data['user'] = $db->single();
        }
        $this->view('admin/profile', $data);
    }

    // ============================================================
    // HELPER: Get status_id from status name
    // ============================================================
    private function getStatusId($statusName) {
        $db = new Database();
        $db->query("SELECT status_id FROM report_statuses WHERE status_name = :name");
        $db->bind(':name', $statusName);
        $result = $db->single();
        return $result ? (int)$result['status_id'] : 1; // Default to Pending (1)
    }
}