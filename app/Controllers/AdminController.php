<?php

class AdminController extends Controller {
    private $userModel;
    private $auditModel;

    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . app_url('index.php?url=auth'));
            exit;
        }

        // Get user role and status from database using role_id
        $db = new Database();
        $db->query("SELECT u.status, u.role_id, r.role_name, r.permissions FROM users u LEFT JOIN roles r ON u.role_id = r.role_id WHERE u.id = :id");
        $db->bind(':id', $_SESSION['user_id']);
        $user = $db->single();

        if ($user && $user['status'] === 'suspended') {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['flash_warning'] = 'This account has been suspended by the Barangay Administration. You have been signed out. Please contact the Barangay Hall for assistance.';
            header('Location: ' . app_url('index.php?url=auth'));
            exit;
        }

        $roleName = $user ? strtolower($user['role_name'] ?? '') : '';

        // Role routing guards
        if ($roleName === 'resident') {
            header('Location: ' . app_url('index.php?url=resident'));
            exit;
        }
        if ($roleName === 'supervisor') {
            header('Location: ' . app_url('index.php?url=supervisor'));
            exit;
        }

        if (empty($roleName)) {
            header('Location: ' . app_url('index.php?url=auth'));
            exit;
        }

        // Store role & permissions in session
        $_SESSION['user_role'] = $roleName;
        $_SESSION['user_role_id'] = $user['role_id'] ?? null;

        $permissions = [];
        if ($roleName === 'administrator') {
            $permissions = ['all'];
        } elseif (!empty($user['permissions'])) {
            $permissions = json_decode($user['permissions'], true) ?: [];
        }
        if (in_array($roleName, ['secretary', 'captain']) && empty($permissions)) {
            $permissions = ['all'];
        }
        $_SESSION['user_permissions'] = $permissions;

        $this->userModel = $this->model('User');
        $this->auditModel = $this->model('AuditLog');
    }

    // ============================================================
    // PROFILE OTP REQUEST & VERIFICATION
    // ============================================================

    public function requestProfileOTP() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . app_url('index.php?url=' . urlencode(strtolower($_SESSION['user_role']) . '/profile')));
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

        require_once dirname(__DIR__) . '/Models/Helpers/OtpMailer.php';
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
            header('Location: ' . app_url('index.php?url=' . urlencode(strtolower($_SESSION['user_role']) . '/profile')));
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
        
        $stats = ['total' => 0, 'Pending' => 0, 'Verified' => 0, 'In Progress' => 0, 'Resolved' => 0, 'Rejected' => 0];
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

        // ---- Recent 5 Reports (Including Guest Reports) ----
        $db->query("
            SELECT r.id, r.description, r.submission_date, r.reporter_type, r.guest_name, r.guest_phone,
                    COALESCE(u.name, r.guest_name, 'Guest') as resident_name,
                    rs.status_name as status,
                    rs.color_code as status_color,
                    wc.category_name as category,
                    p.purok_name as purok
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            ORDER BY r.submission_date DESC
            LIMIT 5
        ");
        $data['recent_reports'] = $db->resultSet();

        // ---- Recent Activity (audit logs) ----
        $db->query("
            SELECT a.action, a.details, a.created_at, u.name as user_name
            FROM audit_logs a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.action != 'Dashboard Access'
            ORDER BY a.created_at DESC
            LIMIT 7
        ");
        $data['recent_activity'] = $db->resultSet();

        // ---- Chart Data 1: Purok Distribution Breakdown ----
        $db->query("
            SELECT p.purok_name, COUNT(r.id) as count
            FROM puroks p
            LEFT JOIN reports r ON p.purok_id = r.purok_id
            GROUP BY p.purok_id, p.purok_name
            ORDER BY count DESC
        ");
        $data['purok_chart_data'] = $db->resultSet();

        // ---- Chart Data 2: Waste Category Breakdown ----
        $db->query("
            SELECT wc.category_name, COUNT(r.id) as count
            FROM waste_categories wc
            LEFT JOIN reports r ON wc.category_id = r.category_id
            GROUP BY wc.category_id, wc.category_name
            ORDER BY count DESC
        ");
        $data['category_chart_data'] = $db->resultSet();

        // ---- Chart Data 3: Monthly Submission Trends (Continuous 6 Months) ----
        $trendMap = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('M Y', strtotime("-$i months"));
            $trendMap[$monthKey] = 0;
        }

        $db->query("
            SELECT DATE_FORMAT(submission_date, '%b %Y') as period, COUNT(*) as count
            FROM reports
            WHERE submission_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY YEAR(submission_date), MONTH(submission_date)
            ORDER BY submission_date ASC
        ");
        $rawTrends = $db->resultSet();
        foreach ($rawTrends as $rt) {
            if (isset($trendMap[$rt['period']])) {
                $trendMap[$rt['period']] = (int)$rt['count'];
            }
        }

        $monthlyTrendData = [];
        foreach ($trendMap as $period => $count) {
            $monthlyTrendData[] = ['period' => $period, 'count' => $count];
        }
        $data['monthly_trend_data'] = $monthlyTrendData;

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success');
        $this->view('admin/dashboard', $data);
    }

    // ============================================================
    // GIS MONITORING
    // ============================================================
    public function gis() {
        if (!has_permission('view_reports')) {
            die("Unauthorized Access");
        }

        $db = new Database();

        // Get heatmap settings
        $heatmapModel = $this->model('HeatmapSetting');
        $data['heatmap_settings'] = $heatmapModel->getConfig();
        $minHotspotReports = (int)($data['heatmap_settings']['low_min'] ?? $data['heatmap_settings']['minimum_reports'] ?? 3);

        // Get all reports with coordinates
        $db->query("
            SELECT r.*, 
                   COALESCE(u.name, r.guest_name, 'Guest') as resident_name,
                   rs.status_name as status,
                   rs.color_code as status_color,
                   wc.category_name as waste_category,
                   p.purok_name as purok
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
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

        // Get active hotspots (puroks meeting or exceeding configured low density minimum in last 30 days)
        $db->query("
            SELECT p.purok_name, COUNT(*) as report_count, wc.category_name as dominant_category
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            WHERE r.submission_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY r.purok_id
            HAVING COUNT(*) >= :min_reports
            ORDER BY report_count DESC
        ");
        $db->bind(':min_reports', $minHotspotReports);
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

        // Get report statuses for filter
        $db->query("SELECT * FROM report_statuses ORDER BY status_id");
        $data['statuses'] = $db->resultSet();

        // Get landmarks for map overlay
        $db->query("SELECT * FROM map_landmarks ORDER BY landmark_name");
        $data['landmarks'] = $db->resultSet();

        // Get puroks with GeoJSON polygon boundaries
        $db->query("
            SELECT p.purok_id, p.purok_name, ST_AsGeoJSON(pb.polygon_geometry) AS polygon_geometry 
            FROM puroks p
            LEFT JOIN purok_boundaries pb ON p.purok_id = pb.purok_id
            WHERE p.is_active = 1
            ORDER BY p.purok_name
        ");
        $data['purok_polygons'] = $db->resultSet();

        // Get official barangay boundary and map center
        $barangayModel = $this->model('Barangay');
        $mapConfig = $barangayModel->getMapConfig();
        $data['barangay_boundary'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'GIS Monitoring', 'GIS', 'Admin viewed GIS monitoring', 'success');

        $this->view('admin/gis', $data);
    }

    // ============================================================
    // API: GET GIS DATA (AJAX)
    // ============================================================
    // GIS DATA ENDPOINT
    // ============================================================
    public function getGisData() {
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $purok = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $dateFrom = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
        $dateTo = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';

        $db = new Database();
        
        $query = "
            SELECT r.*, 
                   COALESCE(u.name, r.guest_name, 'Guest') as resident_name,
                   rs.status_name as status,
                   rs.color_code as status_color,
                   wc.category_name as waste_category,
                   p.purok_name as purok
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
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
        if (!empty($dateFrom)) {
            $query .= " AND DATE(r.submission_date) >= :date_from";
        }
        if (!empty($dateTo)) {
            $query .= " AND DATE(r.submission_date) <= :date_to";
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
        if (!empty($dateFrom)) {
            $db->bind(':date_from', $dateFrom);
        }
        if (!empty($dateTo)) {
            $db->bind(':date_to', $dateTo);
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
        if (!has_permission('view_residents') && !has_permission('manage_residents')) {
            die("Unauthorized Access: You do not have permission to manage accounts.");
        }

        // Handle POST actions (suspend, reactivate, deactivate, remove, delete)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $targetUserId = filter_var($_POST['user_id'] ?? 0, FILTER_VALIDATE_INT);
            $action = $_POST['action'] ?? '';

            if ($targetUserId && in_array($action, ['suspend', 'reactivate', 'deactivate', 'remove', 'delete'])) {
                $db = new Database();
                if ($action === 'suspend') {
                    $db->query("UPDATE users SET status = 'suspended' WHERE id = :id");
                    $db->bind(':id', $targetUserId);
                    $db->execute();
                    $this->auditModel->logAction($_SESSION['user_id'], 'Account Suspended', 'User Management', "Suspended user ID $targetUserId", 'success');
                    $_SESSION['success_message'] = "Account has been suspended.";
                } elseif ($action === 'reactivate') {
                    $db->query("UPDATE users SET status = 'active' WHERE id = :id");
                    $db->bind(':id', $targetUserId);
                    $db->execute();
                    $this->auditModel->logAction($_SESSION['user_id'], 'Account Reactivated', 'User Management', "Reactivated user ID $targetUserId", 'success');
                    $_SESSION['success_message'] = "Account has been reactivated.";
                } elseif ($action === 'deactivate') {
                    $db->query("UPDATE users SET status = 'deactivated' WHERE id = :id");
                    $db->bind(':id', $targetUserId);
                    $db->execute();
                    $this->auditModel->logAction($_SESSION['user_id'], 'Account Deactivated', 'User Management', "Deactivated user ID $targetUserId", 'success');
                    $_SESSION['success_message'] = "Account has been deactivated.";
                } elseif ($action === 'remove' || $action === 'delete') {
                    if ($targetUserId === (int)($_SESSION['user_id'] ?? 0)) {
                        $_SESSION['error_message'] = "You cannot delete your own active administrator account.";
                    } else {
                        // Fetch user name for audit log before delete
                        $db->query("SELECT name, email FROM users WHERE id = :id");
                        $db->bind(':id', $targetUserId);
                        $deletedUser = $db->single();
                        $deletedName = $deletedUser['name'] ?? "ID $targetUserId";

                        $db->query("DELETE FROM users WHERE id = :id");
                        $db->bind(':id', $targetUserId);
                        $db->execute();

                        $this->auditModel->logAction($_SESSION['user_id'], 'Account Deleted', 'User Management', "Permanently deleted user account '$deletedName' (ID $targetUserId)", 'success');
                        $_SESSION['success_message'] = "Account '$deletedName' has been permanently deleted from the database.";
                    }
                }
            }
            $currentTab = $_GET['tab'] ?? 'resident';
            header('Location: ' . app_url('admin/accounts?tab=' . urlencode($currentTab)));
            exit;
        }

        // Get filter parameters
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'resident'; // 'resident', 'staff', 'suspended'
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Build query with search
        $db = new Database();
        if ($tab === 'suspended') {
            $query = "
                SELECT u.*, r.role_name, p.position_name, pk.purok_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN positions p ON u.position_id = p.position_id
                LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                WHERE u.status = 'suspended'
            ";
        } elseif ($tab === 'staff') {
            $query = "
                SELECT u.*, r.role_name, p.position_name, pk.purok_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN positions p ON u.position_id = p.position_id
                LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                WHERE (u.role_id != 3 OR r.role_name IS NULL OR LOWER(r.role_name) != 'resident') AND u.status != 'suspended'
            ";
        } else {
            $query = "
                SELECT u.*, r.role_name, p.position_name, pk.purok_name
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN positions p ON u.position_id = p.position_id
                LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                WHERE (u.role_id = 3 OR LOWER(r.role_name) = 'resident') AND u.status != 'suspended'
            ";
        }

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
        $db->query("SELECT COUNT(*) as count FROM users u LEFT JOIN roles r ON u.role_id = r.role_id WHERE (u.role_id = 3 OR LOWER(r.role_name) = 'resident') AND u.status != 'suspended'");
        $residentCount = $db->single()['count'] ?? 0;
        $db->query("SELECT COUNT(*) as count FROM users u LEFT JOIN roles r ON u.role_id = r.role_id WHERE (u.role_id != 3 AND (r.role_name IS NULL OR LOWER(r.role_name) != 'resident')) AND u.status != 'suspended'");
        $staffCount = $db->single()['count'] ?? 0;
        $db->query("SELECT COUNT(*) as count FROM users WHERE status = 'suspended'");
        $suspendedCount = $db->single()['count'] ?? 0;

        $data['resident_count'] = (int)$residentCount;
        $data['staff_count'] = (int)$staffCount;
        $data['suspended_count'] = (int)$suspendedCount;

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
            if (!has_permission('manage_report_status')) {
                die("Unauthorized Access: You do not have permission to perform report actions.");
            }

            $report_id = filter_var($_POST['report_id'] ?? 0, FILTER_VALIDATE_INT);
            $action = htmlspecialchars(strip_tags($_POST['action'] ?? ''), ENT_QUOTES, 'UTF-8');
            $remark = isset($_POST['remark']) ? htmlspecialchars(strip_tags($_POST['remark']), ENT_QUOTES, 'UTF-8') : '';

            require_once __DIR__ . '/../Models/Notification.php';
            $notificationModel = new Notification();

            $db = new Database();

            // Get old status name, reporter info, and category details for notification
            $db->query("
                SELECT r.id, r.resident_id, r.reporter_type, r.guest_phone, r.guest_email, r.guest_name, 
                       r.tracking_number, r.location, rs.status_name as status,
                       wc.category_name, p.purok_name, u.email as resident_email, u.name as resident_name
                FROM reports r
                JOIN report_statuses rs ON r.status_id = rs.status_id
                LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
                LEFT JOIN puroks p ON r.purok_id = p.purok_id
                LEFT JOIN users u ON r.resident_id = u.id
                WHERE r.id = :id
            ");
            $db->bind(':id', $report_id);
            $oldReport = $db->single();
            $oldStatus = $oldReport ? $oldReport['status'] : 'pending';
            $resident_id = $oldReport ? $oldReport['resident_id'] : null;

            $newStatusKey = '';
            if ($action == 'verify') {
                $newStatusKey = 'verified';
                $status_id = $this->getStatusId('Verified');
                $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
                $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'verified', $_SESSION['user_id']);
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Verified', "Report ID $report_id", "Verified report. Remark: $remark", 'success');
            } elseif ($action == 'in_progress') {
                $newStatusKey = 'in_progress';
                $status_id = $this->getStatusId('In Progress');
                $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
                $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'in_progress', $_SESSION['user_id']);
                $this->auditModel->logAction($_SESSION['user_id'], 'Report In Progress', "Report ID $report_id", "Marked report in progress. Remark: $remark", 'success');
            } elseif ($action == 'resolve') {
                $newStatusKey = 'resolved';
                $status_id = $this->getStatusId('Resolved');
                $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
                $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'resolved', $_SESSION['user_id']);
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Resolved', "Report ID $report_id", "Resolved report. Remark: $remark", 'success');
            } elseif ($action == 'reject') {
                $newStatusKey = 'rejected';
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

            // Dispatch Email & SMS notifications to guest / submitter
            $this->dispatchStatusNotifications($oldReport, $newStatusKey, $remark);

            header('Location: ' . app_url('index.php?url=' . urlencode('admin/reports')));
            exit;
        }

        // Get search and status filters from GET parameters
        $search = isset($_GET['search']) ? htmlspecialchars(strip_tags($_GET['search']), ENT_QUOTES, 'UTF-8') : '';
        $status = isset($_GET['status']) ? htmlspecialchars(strip_tags($_GET['status']), ENT_QUOTES, 'UTF-8') : '';
        $date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
        $date_to = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
        $purok_filter = isset($_GET['purok']) ? trim($_GET['purok']) : '';
        $reporter_type = isset($_GET['reporter_type']) ? trim($_GET['reporter_type']) : '';

        $db = new Database();

        // ---- Fetch all Puroks for filter dropdown and grouping ----
        $db->query("SELECT * FROM puroks ORDER BY purok_name ASC");
        $allPuroks = $db->resultSet() ?: [];
        $data['puroks'] = $allPuroks;

        // ---- Status Counts for Metrics ----
        $countQuery = "
            SELECT rs.status_name, COUNT(*) as count
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE 1=1
        ";
        if (!empty($date_from)) {
            $countQuery .= " AND DATE(r.submission_date) >= :c_date_from";
        }
        if (!empty($date_to)) {
            $countQuery .= " AND DATE(r.submission_date) <= :c_date_to";
        }
        if (!empty($purok_filter)) {
            $countQuery .= " AND (p.purok_name = :c_purok_filter OR r.purok_id = :c_purok_id)";
        }
        if ($reporter_type === 'guest') {
            $countQuery .= " AND r.reporter_type = 'guest'";
        } elseif ($reporter_type === 'resident') {
            $countQuery .= " AND (r.reporter_type = 'resident' OR r.reporter_type IS NULL OR r.reporter_type = '')";
        }
        $countQuery .= " GROUP BY r.status_id";

        $db->query($countQuery);
        if (!empty($date_from)) {
            $db->bind(':c_date_from', $date_from);
        }
        if (!empty($date_to)) {
            $db->bind(':c_date_to', $date_to);
        }
        if (!empty($purok_filter)) {
            $db->bind(':c_purok_filter', $purok_filter);
            $db->bind(':c_purok_id', is_numeric($purok_filter) ? (int)$purok_filter : 0);
        }

        $statusCounts = $db->resultSet() ?: [];
        $statusMap = ['Total' => 0, 'Pending' => 0, 'Verified' => 0, 'Rejected' => 0, 'In Progress' => 0, 'Resolved' => 0];
        foreach ($statusCounts as $row) {
            $statusMap[$row['status_name']] = (int)$row['count'];
            $statusMap['Total'] += (int)$row['count'];
        }
        $data['status_counts'] = $statusMap;

        // Build query with joins to get all related data (LEFT JOIN so guest reports are included)
        $query = "
            SELECT r.*,
                   COALESCE(u.name, r.guest_name, 'Guest') as name,
                   u.email,
                   rf.flag_reason,
                   rs.status_name as status,
                   rs.color_code as status_color,
                   wc.category_name as waste_category,
                   eq.quantity_name as estimated_quantity,
                   wcnd.condition_name as waste_condition,
                   p.purok_name as purok,
                   (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) as photo_path,
                   (SELECT COUNT(*) FROM report_photos WHERE report_id = r.id) as photo_count
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            LEFT JOIN report_flags rf ON r.id = rf.report_id
            WHERE 1=1
        ";

        if (!empty($search)) {
            $query .= " AND (r.description LIKE :search OR u.name LIKE :search OR u.email LIKE :search OR r.guest_name LIKE :search OR r.guest_phone LIKE :search OR r.tracking_number LIKE :search)";
        }

        if (!empty($status)) {
            $query .= " AND rs.status_name = :status";
        }

        if (!empty($date_from)) {
            $query .= " AND DATE(r.submission_date) >= :date_from";
        }

        if (!empty($date_to)) {
            $query .= " AND DATE(r.submission_date) <= :date_to";
        }

        if (!empty($purok_filter)) {
            $query .= " AND (p.purok_name = :purok_filter OR r.purok_id = :purok_id)";
        }

        if ($reporter_type === 'guest') {
            $query .= " AND r.reporter_type = 'guest'";
        } elseif ($reporter_type === 'resident') {
            $query .= " AND (r.reporter_type = 'resident' OR r.reporter_type IS NULL OR r.reporter_type = '')";
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

        if (!empty($date_from)) {
            $db->bind(':date_from', $date_from);
        }

        if (!empty($date_to)) {
            $db->bind(':date_to', $date_to);
        }

        if (!empty($purok_filter)) {
            $db->bind(':purok_filter', $purok_filter);
            $db->bind(':purok_id', is_numeric($purok_filter) ? (int)$purok_filter : 0);
        }

        $reportsList = $db->resultSet() ?: [];
        $data['reports'] = $reportsList;

        // ---- Compute Grouped Reports by Purok / Zone ----
        $reportsByPurok = [];
        foreach ($allPuroks as $pk) {
            $pkName = $pk['purok_name'];
            $reportsByPurok[$pkName] = [
                'purok_id' => (int)$pk['purok_id'],
                'purok_name' => $pkName,
                'total' => 0,
                'pending' => 0,
                'verified' => 0,
                'in_progress' => 0,
                'resolved' => 0,
                'rejected' => 0,
                'reports' => []
            ];
        }
        $otherPurokKey = 'Unassigned / Other';
        $reportsByPurok[$otherPurokKey] = [
            'purok_id' => 0,
            'purok_name' => $otherPurokKey,
            'total' => 0,
            'pending' => 0,
            'verified' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'rejected' => 0,
            'reports' => []
        ];

        foreach ($reportsList as $rep) {
            $repPurok = !empty($rep['purok']) ? trim($rep['purok']) : $otherPurokKey;
            if (!isset($reportsByPurok[$repPurok])) {
                $reportsByPurok[$repPurok] = [
                    'purok_id' => (int)($rep['purok_id'] ?? 0),
                    'purok_name' => $repPurok,
                    'total' => 0,
                    'pending' => 0,
                    'verified' => 0,
                    'in_progress' => 0,
                    'resolved' => 0,
                    'rejected' => 0,
                    'reports' => []
                ];
            }
            $reportsByPurok[$repPurok]['total']++;
            $stKey = strtolower(str_replace(' ', '_', $rep['status'] ?? 'pending'));
            if (isset($reportsByPurok[$repPurok][$stKey])) {
                $reportsByPurok[$repPurok][$stKey]++;
            }
            $reportsByPurok[$repPurok]['reports'][] = $rep;
        }

        if ($reportsByPurok[$otherPurokKey]['total'] === 0) {
            unset($reportsByPurok[$otherPurokKey]);
        }

        $data['reports_by_purok'] = $reportsByPurok;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['selected_purok'] = $purok_filter;
        $data['selected_reporter_type'] = $reporter_type;
        $data['active_status'] = $status;

        $db->query("SELECT * FROM report_generation_settings LIMIT 1");
        $data['report_settings'] = $db->single() ?: [];
        $db->query("SELECT * FROM barangays LIMIT 1");
        $data['barangay'] = $db->single() ?: [];

        $this->view('admin/reports', $data);
    }

    // ============================================================
    // VIEW SINGLE REPORT (Admin)
    // ============================================================
    public function view_report($id) {
        return $this->viewReport($id);
    }

    public function viewReport($id) {
        // Check permission
        if (!has_permission('view_reports')) {
            die("Unauthorized Access");
        }

        $db = new Database();
        $reportModel = $this->model('Report');

        // Fetch report details with all joins (LEFT JOIN so guest reports are included)
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
            LEFT JOIN users u ON r.resident_id = u.id
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
            header('Location: ' . app_url('admin/reports'));
            exit;
        }

        // Get total reports count - for residents use resident_id, for guests use phone number
        if (!empty($report['resident_id'])) {
            $db->query("SELECT COUNT(*) as total FROM reports WHERE resident_id = :resident_id");
            $db->bind(':resident_id', $report['resident_id']);
        } else {
            $db->query("SELECT COUNT(*) as total FROM reports WHERE guest_phone = :phone AND reporter_type = 'guest'");
            $db->bind(':phone', $report['guest_phone']);
        }
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

        // Fetch all active purok boundaries formatted as GeoJSON for map preview
        $db->query("
            SELECT p.purok_id, p.purok_name, ST_AsGeoJSON(pb.polygon_geometry) AS polygon_geometry
            FROM puroks p
            JOIN purok_boundaries pb ON p.purok_id = pb.purok_id
            WHERE pb.polygon_geometry IS NOT NULL
        ");
        $data['purok_boundaries'] = $db->resultSet();

        // Get official barangay boundary and map center
        $barangayModel = $this->model('Barangay');
        $mapConfig = $barangayModel->getMapConfig();
        $data['barangay_boundary'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];
        $data['gis_detected_purok'] = $barangayModel->detectPurokDetails($report['latitude'], $report['longitude']);

        $data['report'] = $report;
        $this->view('admin/view_report', $data);
    }

    // ============================================================
    // UPDATE REPORT STATUS (from detail page)
    // ============================================================
        // ============================================================
    // UPDATE REPORT STATUS (from detail page)
    // ============================================================
    public function updateReportStatus() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . app_url('admin/reports'));
            exit;
        }

        if (!has_permission('manage_report_status')) {
            die("Unauthorized Access");
        }

        $report_id = filter_var($_POST['report_id'] ?? 0, FILTER_VALIDATE_INT);
        $action = htmlspecialchars(strip_tags($_POST['action'] ?? ''), ENT_QUOTES, 'UTF-8');
        $remark = isset($_POST['remark']) ? htmlspecialchars(strip_tags($_POST['remark']), ENT_QUOTES, 'UTF-8') : '';

        // UPDATED: Added 'in_progress' and 'resolve' to the allowed actions
        if (!$report_id || !in_array($action, ['verify', 'in_progress', 'reject', 'resolve'])) {
            header('Location: ' . app_url('admin/reports'));
            exit;
        }

        $reportModel = $this->model('Report');
        $db = new Database();
        require_once __DIR__ . '/../Models/Notification.php';
        $notificationModel = new Notification();

        // Get old status, reporter info, and category details
        $db->query("
            SELECT r.id, r.resident_id, r.reporter_type, r.guest_phone, r.guest_email, r.guest_name, 
                   r.tracking_number, r.location, rs.status_name as status,
                   wc.category_name, p.purok_name, u.email as resident_email, u.name as resident_name
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            LEFT JOIN users u ON r.resident_id = u.id
            WHERE r.id = :id
        ");
        $db->bind(':id', $report_id);
        $oldReport = $db->single();
        $oldStatus = $oldReport ? $oldReport['status'] : 'Pending';
        $resident_id = $oldReport ? $oldReport['resident_id'] : null;

        $newStatusKey = '';
        if ($action == 'verify') {
            $newStatusKey = 'verified';
            $status_id = $this->getStatusId('Verified');
            $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
            $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'verified', $_SESSION['user_id']);
            $this->auditModel->logAction($_SESSION['user_id'], 'Report Verified', "Report ID $report_id", "Verified report", 'success');
        } elseif ($action == 'reject') {
            $newStatusKey = 'rejected';
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

        } elseif ($action == 'in_progress') {
            $newStatusKey = 'in_progress';
            $status_id = $this->getStatusId('In Progress');
            $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
            $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'in_progress', $_SESSION['user_id']);
            $this->auditModel->logAction($_SESSION['user_id'], 'Report In Progress', "Report ID $report_id", "Marked report in progress", 'success');

        // Handle Resolve action
        } elseif ($action == 'resolve') {
            $newStatusKey = 'resolved';
            $status_id = $this->getStatusId('Resolved');
            $reportModel->updateReportStatus($report_id, $status_id, $_SESSION['user_id']);
            $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'resolved', $_SESSION['user_id']);
            $this->auditModel->logAction($_SESSION['user_id'], 'Report Resolved', "Report ID $report_id", "Resolved report. Remark: $remark", 'success');
        }

        // Dispatch Email & SMS notifications to guest / submitter
        $this->dispatchStatusNotifications($oldReport, $newStatusKey, $remark);

        // Redirect back to the detail page
        header('Location: ' . app_url('admin/viewReport/' . $report_id));
        exit;
    }

    // ============================================================
    // FLAGGED REPORTS
    // ============================================================
    public function flaggedReports() {
        if (!has_permission('view_reports')) {
            die("Unauthorized Access: You do not have permission to view flagged reports.");
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
        if (!has_permission('manage_residents')) {
            die("Unauthorized: Only administrators and designated staff can create staff accounts.");
        }

        $data = ['error' => '', 'success' => '', 'positions' => [], 'roles' => [], 'puroks' => []];
        $db = new Database();

        // Load dropdown data
        $db->query("SELECT * FROM positions WHERE is_active = 1 ORDER BY position_name");
        $data['positions'] = $db->resultSet();
        $db->query("SELECT * FROM roles WHERE LOWER(role_name) != 'resident' ORDER BY (CASE WHEN LOWER(role_name) = 'administrator' THEN 1 WHEN LOWER(role_name) = 'supervisor' THEN 2 ELSE 3 END), role_name ASC");
        $data['roles'] = $db->resultSet();
        $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
        $data['puroks'] = $db->resultSet();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $position_id = (int)($_POST['position_id'] ?? 0);
            $role_id = (int)($_POST['role_id'] ?? 0);
            $purok_id = !empty($_POST['purok_id']) ? (int)$_POST['purok_id'] : 1;
            $username = trim($_POST['username'] ?? '');

            if (empty($name) || empty($email) || empty($phone) || empty($username) || !$position_id || !$role_id) {
                $data['error'] = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Invalid email format.';
            } elseif (!preg_match('/^09\d{9}$/', $phone)) {
                $data['error'] = 'Invalid Philippine phone number (must be 11 digits starting with 09).';
            } elseif ($this->userModel->findUserByEmail($email)) {
                $data['error'] = 'This email address is already in use. Please use a different email.';
            } elseif ($this->userModel->findUserByUsername($username)) {
                $data['error'] = 'This username is already in use. Please choose a different username.';
            } else {
                // Check if manual password provided or auto-generate
                $passwordType = $_POST['password_type'] ?? 'auto';
                $manualPass = trim($_POST['manual_password'] ?? '');
                
                if ($passwordType === 'manual' && !empty($manualPass)) {
                    if (strlen($manualPass) < 6) {
                        $data['error'] = 'Manual password must be at least 6 characters long.';
                        $this->view('admin/create_staff', $data);
                        return;
                    }
                    $tempPassword = $manualPass;
                } else {
                    $tempPassword = bin2hex(random_bytes(6)); // 12 chars
                }
                
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
                    $data['generated_password'] = $tempPassword;
                    // Send email with temporary password
                    require_once dirname(__DIR__) . '/Models/Helpers/OtpMailer.php';
                    try {
                        OtpMailer::sendTempPasswordEmail($email, $tempPassword, $name);
                        $data['success'] = 'Staff account created successfully! Credentials sent to ' . htmlspecialchars($email);
                    } catch (Exception $e) {
                        $data['success'] = 'Staff account created successfully!';
                        $data['error'] = 'Note: Automated email sending failed (' . $e->getMessage() . '). Please provide credentials directly.';
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
        if (!has_permission('export_reports')) {
            die("Unauthorized Access");
        }

        $db = new Database();
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';

        $query = "
            SELECT 
                r.id,
                COALESCE(r.tracking_number, CONCAT('WR-', LPAD(r.id, 6, '0'))) as tracking_number,
                r.submission_date,
                COALESCE(u.name, r.guest_name, 'Guest') as reporter_name,
                COALESCE(u.phone_number, r.guest_phone, 'N/A') as contact_number,
                r.reporter_type,
                wc.category_name as waste_category,
                eq.quantity_name as estimated_quantity,
                wcnd.condition_name as waste_condition,
                p.purok_name as purok,
                r.description,
                rs.status_name as status,
                r.latitude,
                r.longitude
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE 1=1
        ";

        if (!empty($search)) {
            $query .= " AND (r.description LIKE :search OR u.name LIKE :search OR u.email LIKE :search OR r.guest_name LIKE :search OR r.guest_phone LIKE :search OR r.tracking_number LIKE :search)";
        }
        if (!empty($status)) {
            $query .= " AND rs.status_name = :status";
        }
        $query .= " ORDER BY r.submission_date DESC";

        $db->query($query);
        if (!empty($search)) {
            $db->bind(':search', "%{$search}%");
        }
        if (!empty($status)) {
            $db->bind(':status', $status);
        }

        $reports = $db->resultSet();

        // Audit Log
        $this->auditModel->logAction($_SESSION['user_id'], 'Export Reports', 'Reports', 'Admin exported waste reports to CSV', 'success');

        // Send CSV Headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Waste_Reports_Export_' . date('Y-m-d_H-i') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Report ID / Tracking',
            'Submission Date',
            'Reporter Name',
            'Contact Number',
            'Reporter Type',
            'Waste Category',
            'Estimated Quantity',
            'Condition',
            'Purok / Zone',
            'Status',
            'Description',
            'Latitude',
            'Longitude'
        ]);

        foreach ($reports as $row) {
            fputcsv($output, [
                $row['tracking_number'],
                $row['submission_date'],
                $row['reporter_name'],
                $row['contact_number'],
                ucfirst($row['reporter_type'] ?? 'resident'),
                $row['waste_category'] ?? 'N/A',
                $row['estimated_quantity'] ?? 'N/A',
                $row['waste_condition'] ?? 'N/A',
                $row['purok'] ?? 'N/A',
                $row['status'],
                $row['description'],
                $row['latitude'],
                $row['longitude']
            ]);
        }
        fclose($output);
        exit;
    }

    // ============================================================
    // STATISTICS & ANALYTICS (Report Summaries)
    // ============================================================
    public function report_summaries() {
        if (!has_permission('view_analytics')) {
            header('Location: ' . app_url('index.php?url=admin'));
            exit;
        }
        $filters = $this->parseAnalyticsFilters($_GET);
        $data = $this->buildAnalyticsData($filters);
        $data['exports'] = $this->getRecentExports();
        if (!empty($_SESSION['user_id'])) {
            try {
                $this->auditModel->logAction($_SESSION['user_id'], 'Analytics View', 'Analytics', 'Admin viewed statistics & analytics', 'success');
            } catch (Exception $e) {}
        }
        $this->view('admin/report_summaries', $data);
    }

    // ============================================================
    // AUDIT LOGS
    // ============================================================
    public function auditLogs() {
        if (!has_permission('view_audit_logs')) {
            die("Unauthorized Access");
        }

        $db = new Database();

        $isArchiveView = isset($_GET['view']) && $_GET['view'] === 'archive';
        $targetTable = $isArchiveView ? 'audit_logs_archive' : 'audit_logs';

        // Ensure archive table exists
        try {
            $db->query("CREATE TABLE IF NOT EXISTS `audit_logs_archive` (
              `id` int(11) NOT NULL,
              `user_id` int(11) DEFAULT NULL,
              `action` varchar(255) NOT NULL,
              `affected_record` varchar(255) DEFAULT NULL,
              `details` text DEFAULT NULL,
              `result` varchar(50) DEFAULT 'success',
              `ip_address` varchar(45) DEFAULT NULL,
              `user_agent` text DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `user_id` (`user_id`),
              KEY `created_at` (`created_at`),
              KEY `archived_at` (`archived_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
            $db->execute();
        } catch (Exception $e) {}

        // Auto-purge archived logs older than 30 days (unless restored)
        try {
            $db->query("DELETE FROM `audit_logs_archive` WHERE `archived_at` < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $db->execute();
        } catch (Exception $e) {}

        // Fetch logs with user and role details (plus retention countdown for archive)
        $selectFields = "a.*, u.name as user_name, u.email as user_email, r.role_name";
        if ($isArchiveView) {
            $selectFields .= ", DATEDIFF(DATE_ADD(a.archived_at, INTERVAL 30 DAY), NOW()) as days_until_purge, DATE_ADD(a.archived_at, INTERVAL 30 DAY) as purge_date";
        }

        $db->query("
            SELECT {$selectFields}
            FROM {$targetTable} a 
            LEFT JOIN users u ON a.user_id = u.id 
            LEFT JOIN roles r ON u.role_id = r.role_id
            ORDER BY " . ($isArchiveView ? "a.archived_at DESC, a.created_at DESC" : "a.created_at DESC") . "
            LIMIT 2000
        ");
        $logs = $db->resultSet();

        // Get count of active and archived logs
        $db->query("SELECT COUNT(*) as cnt FROM audit_logs");
        $activeCount = (int)($db->single()['cnt'] ?? 0);

        $db->query("SELECT COUNT(*) as cnt FROM audit_logs_archive");
        $archivedCount = (int)($db->single()['cnt'] ?? 0);

        // Calculate KPI Metrics
        $totalLogs = count($logs);
        $todayLogs = 0;
        $successLogs = 0;
        $failedLogs = 0;
        $uniqueUsers = [];
        $uniqueActions = [];

        $todayStr = date('Y-m-d');
        foreach ($logs as $l) {
            if (substr($l['created_at'], 0, 10) === $todayStr) {
                $todayLogs++;
            }
            if (strtolower($l['result'] ?? '') === 'success') {
                $successLogs++;
            } else {
                $failedLogs++;
            }
            if (!empty($l['user_name'])) {
                $uniqueUsers[$l['user_name']] = true;
            }
            if (!empty($l['action'])) {
                $uniqueActions[$l['action']] = true;
            }
        }

        // Fetch Barangay branding for print view
        $db->query("SELECT * FROM barangays LIMIT 1");
        $barangay = $db->single();

        $data = [
            'logs' => $logs,
            'is_archive_view' => $isArchiveView,
            'active_count' => $activeCount,
            'archived_count' => $archivedCount,
            'stats' => [
                'total' => $totalLogs,
                'today' => $todayLogs,
                'success' => $successLogs,
                'failed' => $failedLogs,
                'unique_users_count' => count($uniqueUsers)
            ],
            'unique_users' => array_keys($uniqueUsers),
            'unique_actions' => array_keys($uniqueActions),
            'barangay' => $barangay,
            'flash_success' => $_SESSION['flash_success'] ?? null,
            'flash_error' => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->auditModel->logAction($_SESSION['user_id'], $isArchiveView ? 'Audit Archive Access' : 'Audit Trail Access', 'Audit Logs', 'Admin accessed system audit trail', 'success');
        $this->view('admin/audit_logs', $data);
    }

    public function audit_logs() {
        return $this->auditLogs();
    }

    /**
     * Archive old or selected audit logs to audit_logs_archive table to keep active table fast
     */
    public function archiveAuditLogs() {
        if (!has_permission('view_audit_logs')) {
            die("Unauthorized Access");
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . app_url('index.php?url=admin/audit_logs'));
            exit;
        }

        $db = new Database();
        $scope = $_POST['archive_scope'] ?? 'days';
        $archivedCount = 0;

        if ($scope === 'selected' && !empty($_POST['selected_ids'])) {
            $ids = is_array($_POST['selected_ids']) ? $_POST['selected_ids'] : explode(',', $_POST['selected_ids']);
            $sanitizedIds = array_filter(array_map('intval', $ids));

            if (!empty($sanitizedIds)) {
                $placeholders = implode(',', array_fill(0, count($sanitizedIds), '?'));
                
                // Copy to archive
                $db->query("INSERT IGNORE INTO audit_logs_archive (id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at, archived_at)
                            SELECT id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at, NOW()
                            FROM audit_logs WHERE id IN ($placeholders)");
                foreach ($sanitizedIds as $i => $idVal) {
                    $db->bind($i + 1, $idVal);
                }
                $db->execute();

                // Delete from active
                $db->query("DELETE FROM audit_logs WHERE id IN ($placeholders)");
                foreach ($sanitizedIds as $i => $idVal) {
                    $db->bind($i + 1, $idVal);
                }
                $db->execute();
                $archivedCount = count($sanitizedIds);
            }
        } else {
            // Days preset: 30, 60, 90, 180, 365
            $days = max(1, (int)($_POST['days'] ?? 60));

            // Copy to archive
            $db->query("INSERT IGNORE INTO audit_logs_archive (id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at, archived_at)
                        SELECT id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at, NOW()
                        FROM audit_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)");
            $db->bind(':days', $days);
            $db->execute();

            // Count how many deleted
            $db->query("DELETE FROM audit_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)");
            $db->bind(':days', $days);
            $db->execute();
            $archivedCount = $db->rowCount();
        }

        $this->auditModel->logAction($_SESSION['user_id'], 'Archive Audit Logs', 'Audit Logs', "Admin moved {$archivedCount} audit records to archive storage", 'success');
        $_SESSION['flash_success'] = "Successfully archived {$archivedCount} log records to the Archive Vault.";
        header('Location: ' . app_url('index.php?url=admin/audit_logs'));
        exit;
    }

    public function archive_audit_logs() {
        return $this->archiveAuditLogs();
    }

    /**
     * Restore archived audit logs back to active table
     */
    public function restoreArchivedLogs() {
        if (!has_permission('view_audit_logs')) {
            die("Unauthorized Access");
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . app_url('index.php?url=admin/audit_logs&view=archive'));
            exit;
        }

        $db = new Database();
        $scope = $_POST['restore_scope'] ?? 'selected';
        $restoredCount = 0;

        if ($scope === 'all') {
            $db->query("INSERT IGNORE INTO audit_logs (id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at)
                        SELECT id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at
                        FROM audit_logs_archive");
            $db->execute();

            $db->query("DELETE FROM audit_logs_archive");
            $db->execute();
            $restoredCount = $db->rowCount();
        } else {
            $ids = is_array($_POST['selected_ids'] ?? null) ? $_POST['selected_ids'] : explode(',', $_POST['selected_ids'] ?? '');
            $sanitizedIds = array_filter(array_map('intval', $ids));

            if (!empty($sanitizedIds)) {
                $placeholders = implode(',', array_fill(0, count($sanitizedIds), '?'));

                $db->query("INSERT IGNORE INTO audit_logs (id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at)
                            SELECT id, user_id, action, affected_record, details, result, ip_address, user_agent, created_at
                            FROM audit_logs_archive WHERE id IN ($placeholders)");
                foreach ($sanitizedIds as $i => $idVal) {
                    $db->bind($i + 1, $idVal);
                }
                $db->execute();

                $db->query("DELETE FROM audit_logs_archive WHERE id IN ($placeholders)");
                foreach ($sanitizedIds as $i => $idVal) {
                    $db->bind($i + 1, $idVal);
                }
                $db->execute();
                $restoredCount = count($sanitizedIds);
            }
        }

        $this->auditModel->logAction($_SESSION['user_id'], 'Restore Audit Logs', 'Audit Logs', "Admin restored {$restoredCount} audit records from archive", 'success');
        $_SESSION['flash_success'] = "Successfully restored {$restoredCount} log records back to the Active Audit Trail.";
        header('Location: ' . app_url('index.php?url=admin/audit_logs&view=archive'));
        exit;
    }

    public function restore_audit_logs() {
        return $this->restoreArchivedLogs();
    }

    /**
     * Export Audit Logs as CSV
     */
    public function exportAuditLogs() {
        if (!has_permission('view_audit_logs')) {
            die("Unauthorized Access");
        }

        $isArchiveView = isset($_GET['view']) && $_GET['view'] === 'archive';
        $targetTable = $isArchiveView ? 'audit_logs_archive' : 'audit_logs';

        $db = new Database();
        $db->query("
            SELECT a.*, u.name as user_name, u.email as user_email, r.role_name
            FROM {$targetTable} a 
            LEFT JOIN users u ON a.user_id = u.id 
            LEFT JOIN roles r ON u.role_id = r.role_id
            ORDER BY a.created_at DESC
        ");
        $logs = $db->resultSet();

        $this->auditModel->logAction($_SESSION['user_id'], 'Export Audit Logs', 'Audit Logs', "Admin exported {$targetTable} trail to CSV", 'success');

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=System_' . ($isArchiveView ? 'Archived' : 'Active') . '_Audit_Logs_' . date('Y-m-d_His') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['Log ID', 'Timestamp', 'User', 'Email', 'Role', 'Action', 'Affected Record', 'Details', 'Result', 'IP Address', 'User Agent']);

        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['user_name'] ?? 'System / Anonymous',
                $log['user_email'] ?? 'N/A',
                $log['role_name'] ?? 'System',
                $log['action'],
                $log['affected_record'] ?? 'N/A',
                $log['details'] ?? 'N/A',
                strtoupper($log['result'] ?? 'SUCCESS'),
                $log['ip_address'] ?? 'N/A',
                $log['user_agent'] ?? 'N/A'
            ]);
        }
        fclose($output);
        exit;
    }

    public function export_audit_logs() {
        return $this->exportAuditLogs();
    }

    // ============================================================
    // ANNOUNCEMENTS
    // ============================================================
    public function announcements() {
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && has_permission('manage_announcements')) {
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
                            $cover_image = '/uploads/announcements/' . $fileName;
                        }
                    }
                }

                $title = trim($_POST['title'] ?? '');
                $content = trim($_POST['content'] ?? '');

                $db->query("INSERT INTO announcements (title, content, cover_image, created_by, visibility_id, publish_date, expiration_date, is_published) 
                            VALUES (:title, :content, :cover_image, :created_by, :visibility_id, :publish_date, :expiration_date, :is_published)");
                $db->bind(':title', $title);
                $db->bind(':content', $content);
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

                $this->auditModel->logAction($_SESSION['user_id'], 'Post Announcement', 'Announcements', "Posted '{$title}'", 'success');
                $_SESSION['flash_success'] = 'Announcement created and published successfully.';
                header('Location: ' . app_url('admin/announcements'));
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
        if (!has_permission('delete_announcements')) {
            die("Unauthorized Access: You do not have permission to delete announcements.");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['announcement_id'])) {
            $announcementId = filter_var($_POST['announcement_id'], FILTER_VALIDATE_INT);

            $db = new Database();
            $db->query("DELETE FROM announcements WHERE id = :id");
            $db->bind(':id', $announcementId);
            $db->execute();

            $this->auditModel->logAction($_SESSION['user_id'], 'Delete Announcement', "Announcement ID $announcementId", "Deleted announcement", 'success');
            $_SESSION['flash_success'] = 'Announcement deleted successfully.';
        }

        header('Location: ' . app_url('admin/announcements'));
        exit;
    }

    /**
     * Edit announcement
     */
    public function edit_announcement($id = null) {
        if (!has_permission('manage_announcements')) {
            die("Unauthorized Access");
        }

        $id = $id ? (int)$id : (int)($_POST['announcement_id'] ?? 0);

        $db = new Database();
        $db->query("SELECT * FROM announcements WHERE id = :id");
        $db->bind(':id', $id);
        $announcement = $db->single();

        if (!$announcement) {
            $_SESSION['flash_error'] = 'Announcement not found.';
            header('Location: ' . app_url('admin/announcements'));
            exit;
        }

        $data['announcement'] = $announcement;
        $db->query("SELECT * FROM announcement_visibilities ORDER BY visibility_id");
        $data['visibilities'] = $db->resultSet();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $visibility_id = (int)($_POST['visibility_id'] ?? 1);
            $is_published = isset($_POST['is_published']) ? 1 : 0;
            $publish_date = !empty($_POST['publish_date']) ? $_POST['publish_date'] : ($announcement['publish_date'] ?? date('Y-m-d H:i:s'));
            $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

            // Handle cover image upload
            $cover_image = null;
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['cover_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (in_array($file['type'], $allowedTypes) && $file['size'] <= 5 * 1024 * 1024) {
                    $uploadDir = '../public/uploads/announcements/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $fileName = 'announce_' . time() . '_' . basename($file['name']);
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                        $cover_image = '/uploads/announcements/' . $fileName;
                    }
                }
            }

            $query = "UPDATE announcements SET 
                title = :title,
                content = :content,
                visibility_id = :visibility_id,
                publish_date = :publish_date,
                expiration_date = :expiration_date,
                is_published = :is_published";
            if ($cover_image) {
                $query .= ", cover_image = :cover_image";
            }
            $query .= " WHERE id = :id";

            $db->query($query);
            $db->bind(':title', $title);
            $db->bind(':content', $content);
            $db->bind(':visibility_id', $visibility_id);
            $db->bind(':publish_date', $publish_date);
            $db->bind(':expiration_date', $expiration_date);
            $db->bind(':is_published', $is_published);
            if ($cover_image) {
                $db->bind(':cover_image', $cover_image);
            }
            $db->bind(':id', $id);

            $db->execute();

            $this->auditModel->logAction($_SESSION['user_id'], 'Edit Announcement', "Announcement ID $id", "Updated announcement '{$title}'", 'success');
            $_SESSION['flash_success'] = 'Announcement updated successfully.';
            header('Location: ' . app_url('admin/announcements'));
            exit;
        }

        $this->view('admin/edit_announcement', $data);
    }

    // ============================================================
// SCHEDULE MANAGEMENT
// ============================================================
public function schedule() {
    if (!has_permission('view_schedules')) {
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

    // Fetch active collection notes for display
    try {
        $db->query("SELECT * FROM collection_notes WHERE is_active = 1 ORDER BY sort_order ASC, note_id ASC");
        $data['collection_notes'] = $db->resultSet();
    } catch (Exception $e) {
        $data['collection_notes'] = [];
    }

    $this->auditModel->logAction($_SESSION['user_id'], 'Schedule Management', 'Schedule', 'Admin viewed schedule management', 'success');
    $this->view('admin/schedule', $data);
}

// ============================================================
// GENERATE CALENDAR DATA
// ============================================================
private function generateCalendarData($month, $year, $schedules) {
    // Get first day of month and number of days
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = (int)date('t', $firstDay);
    $firstDayOfWeek = (int)date('w', $firstDay); // 0=Sunday, 1=Monday, ..., 6=Saturday

    // Map collection days to day of week numbers matching Sunday-first (0=Sunday ... 6=Saturday)
    $dayMap = [
        'Sunday'    => 0,
        'Monday'    => 1,
        'Tuesday'   => 2,
        'Wednesday' => 3,
        'Thursday'  => 4,
        'Friday'    => 5,
        'Saturday'  => 6
    ];

    // Group schedules by day of week
    $scheduleMap = [];
    foreach ($schedules as $schedule) {
        $dayName = ucfirst(strtolower(trim($schedule['collection_day'] ?? '')));
        if (isset($dayMap[$dayName])) {
            $dayNum = $dayMap[$dayName];
            $scheduleMap[$dayNum][] = $schedule;
        }
    }

    // Build calendar days array
    $calendarDays = [];

    // Fill empty days before first day of month (Sunday-first)
    for ($i = 0; $i < $firstDayOfWeek; $i++) {
        $calendarDays[] = null;
    }

    // Fill actual days
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dayOfWeek = (int)date('w', mktime(0, 0, 0, $month, $day, $year));
        $dayData = [
            'day' => $day,
            'is_today' => ($day == (int)date('j') && $month == (int)date('n') && $year == (int)date('Y')),
            'schedules' => $scheduleMap[$dayOfWeek] ?? []
        ];
        $calendarDays[] = $dayData;
    }

    // Fill trailing empty cells to complete the last week
    while (count($calendarDays) % 7 !== 0) {
        $calendarDays[] = null;
    }

    return $calendarDays;
}

    // ============================================================
    // ADD SCHEDULE
    // ============================================================
    public function addSchedule() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !has_permission('manage_schedules')) {
            header('Location: ' . app_url('admin/schedule'));
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
        header('Location: ' . app_url('admin/schedule'));
        exit;
    }

    // ============================================================
    // EDIT SCHEDULE (Show Form)
    // ============================================================
    public function editSchedule($id) {
        if (!has_permission('manage_schedules')) {
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
            header('Location: ' . app_url('admin/schedule'));
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
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !has_permission('manage_schedules')) {
            header('Location: ' . app_url('admin/schedule'));
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
        header('Location: ' . app_url('admin/schedule'));
        exit;
    }

    // ============================================================
    // DELETE SCHEDULE
    // ============================================================
    public function deleteSchedule() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !has_permission('delete_schedules')) {
            header('Location: ' . app_url('admin/schedule'));
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
        header('Location: ' . app_url('admin/schedule'));
        exit;
    }

    /**
     * Postpone/Reschedule collection schedule
     */
    public function postpone_schedule() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !has_permission('manage_schedules')) {
            header('Location: ' . app_url('admin/schedule'));
            exit;
        }

        $schedule_id = (int)$_POST['schedule_id'];
        $new_date = $_POST['new_date'] ?? '';
        $reason = htmlspecialchars($_POST['reason'] ?? '', ENT_QUOTES, 'UTF-8');

        if (!$schedule_id || empty($new_date)) {
            $_SESSION['flash_error'] = 'Please provide a new date.';
            header('Location: ' . app_url('admin/schedule'));
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

        header('Location: ' . app_url('admin/schedule'));
        exit;
    }

    // ============================================================
    // API: GET FILTERED REPORTS
    // ============================================================
    public function getFilteredReports() {
        $filters = $this->parseAnalyticsFilters($_GET);
        $data = $this->buildAnalyticsData($filters);

        $reports = array_map(function ($r) {
            return [
                'id' => $r['id'],
                'name' => $r['name'],
                'location' => $r['location'] ?? '',
                'status' => $r['status'],
                'date' => date('m/d/Y', strtotime($r['submission_date'])),
                'submission_date' => $r['submission_date'],
            ];
        }, $data['filtered_reports']);

        $summary = [
            'total' => (int)($data['kpis']['total'] ?? 0),
            'pending' => (int)($data['kpis']['pending'] ?? 0),
            'verified' => (int)($data['kpis']['verified'] ?? 0),
            'resolved' => (int)($data['kpis']['resolved'] ?? 0),
            'in_progress' => (int)($data['kpis']['in_progress'] ?? 0),
        ];

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'summary' => $summary,
            'reports' => $reports,
        ]);
        exit;
    }

    // ============================================================
    // EXPORT ANALYTICS - PDF (Print View)
    // ============================================================
    public function exportAnalyticsPDF() {
        $filters = $this->parseAnalyticsFilters($_GET);
        $analytics = $this->buildAnalyticsData($filters);

        $data = [
            'reports' => array_map(function ($r) {
                return [
                    'id' => $r['id'],
                    'submission_date' => $r['submission_date'],
                    'reporter' => $r['reporter_formatted'] ?? ($r['name'] ?? 'Unknown (Guest)'),
                    'category' => $r['waste_category'] ?? 'N/A',
                    'purok' => $r['purok'] ?? 'N/A',
                    'status' => $r['status'],
                    'support_count' => $r['support_count'] ?? 0,
                ];
            }, $analytics['filtered_reports']),
            'stats' => $analytics['kpis'],
            'category_data' => $analytics['category_data'],
            'purok_data' => $analytics['purok_data'],
            'status_data' => $analytics['status_data'],
            'condition_data' => $analytics['condition_data'],
            'trend_data' => $analytics['trend_data'],
            'resident_count' => $analytics['resident_count'],
            'guest_count' => $analytics['guest_count'],
            'resident_pct' => $analytics['resident_pct'],
            'guest_pct' => $analytics['guest_pct'],
            'dateFrom' => $filters['date_from'],
            'dateTo' => $filters['date_to'],
            'category' => $filters['category'],
            'purok' => $filters['purok'],
            'status' => $filters['status'],
            'category_name' => $this->getFilterLabel('waste_categories', 'category_id', 'category_name', $filters['category']),
            'purok_name' => $this->getFilterLabel('puroks', 'purok_id', 'purok_name', $filters['purok']),
            'hotspot_intelligence' => $analytics['hotspot_intelligence'],
            'trend_comparison' => $analytics['trend_comparison'],
            'decision_support' => $analytics['decision_support'],
            'user_name' => $_SESSION['user_name'] ?? 'Administrator',
        ];

        $db = new Database();
        $db->query("SELECT * FROM report_generation_settings LIMIT 1");
        $data['report_settings'] = $db->single() ?: [];
        $db->query("SELECT * FROM barangays LIMIT 1");
        $data['barangay'] = $db->single() ?: [];

        $this->logReportExport('analytics_' . date('Y-m-d_H-i-s'), 'pdf', count($data['reports']), $filters);
        $this->auditModel->logAction($_SESSION['user_id'], 'Analytics Export', 'Analytics', 'Exported analytics PDF', 'success');
        $this->view('admin/analytics_print', $data);
        exit;
    }

    // ============================================================
    // EXPORT ANALYTICS - Excel (CSV)
    // ============================================================
    public function exportAnalyticsExcel() {
        $filters = $this->parseAnalyticsFilters($_GET);
        $analytics = $this->buildAnalyticsData($filters);

        $filename = 'analytics_report_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Statistics & Analytics Report']);
        fputcsv($output, ['Period', $filters['date_from'] . ' to ' . $filters['date_to']]);
        fputcsv($output, ['Generated By', $_SESSION['user_name'] ?? 'Administrator']);
        fputcsv($output, ['Generated At', date('Y-m-d H:i:s')]);
        fputcsv($output, []);

        fputcsv($output, ['KPI Summary']);
        fputcsv($output, ['Metric', 'Value']);
        fputcsv($output, ['Total Reports', $analytics['kpis']['total'] ?? 0]);
        fputcsv($output, ['Pending', $analytics['kpis']['pending'] ?? 0]);
        fputcsv($output, ['Verified', $analytics['kpis']['verified'] ?? 0]);
        fputcsv($output, ['In Progress', $analytics['kpis']['in_progress'] ?? 0]);
        fputcsv($output, ['Resolved', $analytics['kpis']['resolved'] ?? 0]);
        fputcsv($output, ['Resolution Rate (%)', $analytics['kpis']['resolution_rate'] ?? 0]);
        fputcsv($output, ['Avg Resolution Time (hrs)', $analytics['avg_resolution_hours']]);
        fputcsv($output, ['Active Hotspots', $analytics['kpis']['active_hotspots'] ?? 0]);
        fputcsv($output, []);

        fputcsv($output, ['Trend Comparison']);
        fputcsv($output, ['Metric', 'Current', 'Previous', 'Change (%)']);
        fputcsv($output, [
            'Total Reports',
            $analytics['trend_comparison']['total_reports']['current'] ?? 0,
            $analytics['trend_comparison']['total_reports']['previous'] ?? 0,
            $analytics['trend_comparison']['total_reports']['change'] ?? 0,
        ]);
        fputcsv($output, [
            'Resolution Rate',
            $analytics['trend_comparison']['resolution_rate']['current'] ?? 0,
            $analytics['trend_comparison']['resolution_rate']['previous'] ?? 0,
            $analytics['trend_comparison']['resolution_rate']['change'] ?? 0,
        ]);
        fputcsv($output, []);

        fputcsv($output, ['Reports by Category']);
        fputcsv($output, ['Category', 'Count']);
        foreach ($analytics['category_data'] as $row) {
            fputcsv($output, [$row['category_name'], $row['count']]);
        }
        fputcsv($output, []);

        fputcsv($output, ['Hotspot Intelligence']);
        fputcsv($output, ['Purok', 'Reports', 'Dominant Category', 'Latest Report']);
        foreach ($analytics['hotspot_intelligence'] as $spot) {
            fputcsv($output, [
                $spot['purok_name'],
                $spot['report_count'],
                $spot['dominant_category'] ?? 'N/A',
                date('Y-m-d', strtotime($spot['latest_report'])),
            ]);
        }
        fputcsv($output, []);

        fputcsv($output, ['Report List']);
        fputcsv($output, ['Report ID', 'Resident', 'Category', 'Purok', 'Status', 'Date', 'Supports']);
        foreach ($analytics['filtered_reports'] as $r) {
            fputcsv($output, [
                $r['id'],
                $r['name'],
                $r['waste_category'] ?? 'N/A',
                $r['purok'] ?? 'N/A',
                $r['status'],
                date('Y-m-d', strtotime($r['submission_date'])),
                $r['support_count'] ?? 0,
            ]);
        }

        fclose($output);
        $this->logReportExport($filename, 'csv', count($analytics['filtered_reports']), $filters);
        $this->auditModel->logAction($_SESSION['user_id'], 'Analytics Export', 'Analytics', 'Exported analytics Excel/CSV', 'success');
        exit;
    }

    // ============================================================
    // EXPORT REPORT SUMMARY - PDF (Routes to Formal Analytics Print View)
    // ============================================================
    public function exportReportSummaryPDF() {
        return $this->exportAnalyticsPDF();
    }

    // ============================================================
    // EXPORT REPORT SUMMARY - XLSX (CSV)
    // ============================================================
    public function exportReportSummaryXLSX() {
        $filters = $this->parseAnalyticsFilters($_GET);
        $analytics = $this->buildAnalyticsData($filters);
        $reports = $analytics['filtered_reports'];

        $filename = "report_summary_" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Report ID', 'Resident Name', 'Email', 'Category', 'Purok', 'Description', 'Status', 'Submission Date']);

        foreach ($reports as $report) {
            fputcsv($output, [
                $report['id'],
                $report['name'],
                $report['email'],
                $report['waste_category'] ?? 'N/A',
                $report['purok'] ?? 'N/A',
                $report['description'],
                $report['status'],
                date('m/d/Y H:i', strtotime($report['submission_date'])),
            ]);
        }

        fclose($output);
        $this->logReportExport($filename, 'csv', count($reports), $filters);
        $this->auditModel->logAction($_SESSION['user_id'], 'Report Generated', 'Report Summary', 'Format: csv', 'success');
        exit;
    }

    private function getFilterLabel($table, $idCol, $nameCol, $id) {
        if ($id <= 0) {
            return '';
        }
        $db = new Database();
        $db->query("SELECT $nameCol FROM $table WHERE $idCol = :id");
        $db->bind(':id', $id);
        $row = $db->single();
        return $row ? $row[$nameCol] : '';
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

            // Handle Profile Picture Upload
            $profilePic = null;
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_pic'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if (!in_array($file['type'], $allowedTypes)) {
                    $data['error'] = 'Invalid image type. Please upload JPG, PNG, or WEBP.';
                    return $this->view('admin/profile', $data);
                }
                if ($file['size'] > $maxSize) {
                    $data['error'] = 'File size too large. Maximum size is 5MB.';
                    return $this->view('admin/profile', $data);
                }

                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $fileName = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $profilePic = '/uploads/profiles/' . $fileName;
                } else {
                    $data['error'] = 'Failed to upload profile picture.';
                    return $this->view('admin/profile', $data);
                }
            }

            if ($profilePic !== null) {
                $db->query("UPDATE users SET name = :name, address = :address, phone_number = :phone, profile_pic = :profile_pic WHERE id = :id");
                $db->bind(':profile_pic', $profilePic);
            } else {
                $db->query("UPDATE users SET name = :name, address = :address, phone_number = :phone WHERE id = :id");
            }
            $db->bind(':name', $name);
            $db->bind(':address', $address);
            $db->bind(':phone', $phone);
            $db->bind(':id', $_SESSION['user_id']);

            if ($db->execute()) {
                $_SESSION['user_name'] = $name;
                if ($profilePic !== null) {
                    $_SESSION['user_pic'] = $profilePic;
                    $_SESSION['profile_pic'] = $profilePic;
                }
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
    // ANALYTICS HELPERS
    // ============================================================
    private function parseAnalyticsFilters($get) {
        $dateFrom = !empty($get['date_from']) ? $get['date_from'] : (!empty($get['dateFrom']) ? $get['dateFrom'] : date('Y-m-d', strtotime('-30 days')));
        $dateTo = !empty($get['date_to']) ? $get['date_to'] : (!empty($get['dateTo']) ? $get['dateTo'] : date('Y-m-d'));
        $granularity = $get['trend_granularity'] ?? 'monthly';

        return [
            'date_from' => htmlspecialchars(strip_tags($dateFrom), ENT_QUOTES, 'UTF-8'),
            'date_to' => htmlspecialchars(strip_tags($dateTo), ENT_QUOTES, 'UTF-8'),
            'category' => isset($get['category']) ? (int)$get['category'] : 0,
            'purok' => isset($get['purok']) ? (int)$get['purok'] : 0,
            'status' => isset($get['status']) ? htmlspecialchars(strip_tags($get['status']), ENT_QUOTES, 'UTF-8') : '',
            'quantity' => isset($get['quantity']) ? (int)$get['quantity'] : 0,
            'condition' => isset($get['condition']) ? (int)$get['condition'] : 0,
            'trend_granularity' => in_array($granularity, ['daily', 'weekly', 'monthly', 'yearly'], true) ? $granularity : 'monthly',
        ];
    }

    private function buildAnalyticsWhere($filters) {
        $where = " WHERE DATE(r.submission_date) BETWEEN :date_from AND :date_to ";
        $params = [
            ':date_from' => $filters['date_from'],
            ':date_to' => $filters['date_to'],
        ];

        if ($filters['category'] > 0) {
            $where .= " AND r.category_id = :category ";
            $params[':category'] = $filters['category'];
        }
        if ($filters['purok'] > 0) {
            $where .= " AND r.purok_id = :purok ";
            $params[':purok'] = $filters['purok'];
        }
        if (!empty($filters['status'])) {
            $where .= " AND rs.status_name = :status ";
            $params[':status'] = $filters['status'];
        }
        if ($filters['quantity'] > 0) {
            $where .= " AND r.quantity_id = :quantity ";
            $params[':quantity'] = $filters['quantity'];
        }
        if ($filters['condition'] > 0) {
            $where .= " AND r.condition_id = :condition ";
            $params[':condition'] = $filters['condition'];
        }

        return [$where, $params];
    }

    private function bindAnalyticsParams($db, $params) {
        foreach ($params as $key => $val) {
            $db->bind($key, $val);
        }
    }

    private function getRecentExports() {
        $exports = [];
        try {
            $db = new Database();
            $db->query("SELECT * FROM report_summaries ORDER BY generated_at DESC LIMIT 10");
            $result = $db->resultSet();
            if ($result) {
                $exports = $result;
            }
        } catch (Exception $e) {}
        return $exports;
    }

    private function logReportExport($filename, $fileType, $total, $filters) {
        try {
            $db = new Database();
            $db->query("INSERT INTO report_summaries (generated_by, filename, file_type, total_reports, filters)
                        VALUES (:generated_by, :filename, :file_type, :total, :filters)");
            $db->bind(':generated_by', $_SESSION['user_id']);
            $db->bind(':filename', $filename);
            $db->bind(':file_type', $fileType);
            $db->bind(':total', $total);
            $db->bind(':filters', json_encode($filters));
            $db->execute();
        } catch (Exception $e) {}
    }

    private function buildAnalyticsData($filters) {
        $db = new Database();
        [$where, $params] = $this->buildAnalyticsWhere($filters);

        // KPI Cards
        $db->query("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN rs.status_name = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN rs.status_name = 'Verified' THEN 1 ELSE 0 END) as verified,
                SUM(CASE WHEN rs.status_name = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN rs.status_name = 'Resolved' THEN 1 ELSE 0 END) as resolved,
                SUM(CASE WHEN rs.status_name = 'Rejected' THEN 1 ELSE 0 END) as rejected
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
        ");
        $this->bindAnalyticsParams($db, $params);
        $kpis = $db->single();

        $total = (int)($kpis['total'] ?? 0);
        $resolved = (int)($kpis['resolved'] ?? 0);
        $kpis['resolution_rate'] = $total > 0 ? round(($resolved / $total) * 100) : 0;

        $db->query("
            SELECT AVG(TIMESTAMPDIFF(HOUR, r.submission_date, h.changed_at)) as avg_hours
            FROM reports r
            JOIN report_status_history h ON r.id = h.report_id
            WHERE h.new_status = 'Resolved'
            AND DATE(r.submission_date) BETWEEN :date_from AND :date_to
        ");
        $db->bind(':date_from', $filters['date_from']);
        $db->bind(':date_to', $filters['date_to']);
        $avgTime = $db->single();
        $kpis['avg_resolution_hours'] = $avgTime ? round($avgTime['avg_hours'], 1) : 0;

        $db->query("
            SELECT AVG(TIMESTAMPDIFF(HOUR, r.submission_date, h.changed_at)) as avg_hours
            FROM reports r
            JOIN report_status_history h ON r.id = h.report_id
            WHERE h.new_status = 'Verified'
            AND DATE(r.submission_date) BETWEEN :date_from AND :date_to
        ");
        $db->bind(':date_from', $filters['date_from']);
        $db->bind(':date_to', $filters['date_to']);
        $avgVerify = $db->single();
        $kpis['avg_verification_hours'] = $avgVerify ? round($avgVerify['avg_hours'], 1) : 0;

        $db->query("
            SELECT COUNT(*) as count FROM (
                SELECT r.purok_id
                FROM reports r
                JOIN report_statuses rs ON r.status_id = rs.status_id
                $where
                GROUP BY r.purok_id
                HAVING COUNT(*) >= 3
            ) hotspots
        ");
        $this->bindAnalyticsParams($db, $params);
        $hotspotRow = $db->single();
        $kpis['active_hotspots'] = (int)($hotspotRow['count'] ?? 0);

        // Trend data — daily, weekly, monthly, yearly
        $trendFormats = [
            'daily' => '%Y-%m-%d',
            'weekly' => '%x-W%v',
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
        ];
        $trendDataByGranularity = [];
        foreach ($trendFormats as $key => $format) {
            $db->query("
                SELECT DATE_FORMAT(r.submission_date, '$format') as period, COUNT(*) as count
                FROM reports r
                JOIN report_statuses rs ON r.status_id = rs.status_id
                $where
                GROUP BY DATE_FORMAT(r.submission_date, '$format')
                ORDER BY period ASC
            ");
            $this->bindAnalyticsParams($db, $params);
            $trendDataByGranularity[$key] = $db->resultSet() ?: [];
        }

        // Category distribution
        $db->query("
            SELECT wc.category_name, COUNT(*) as count
            FROM reports r
            JOIN waste_categories wc ON r.category_id = wc.category_id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
            GROUP BY wc.category_id, wc.category_name
            ORDER BY count DESC
        ");
        $this->bindAnalyticsParams($db, $params);
        $categoryData = $db->resultSet() ?: [];

        // Status distribution
        $db->query("
            SELECT rs.status_name, rs.color_code, COUNT(*) as count
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
            GROUP BY rs.status_id, rs.status_name, rs.color_code
        ");
        $this->bindAnalyticsParams($db, $params);
        $statusData = $db->resultSet() ?: [];

        // Condition distribution
        $db->query("
            SELECT wcnd.condition_name, COUNT(*) as count
            FROM reports r
            JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
            GROUP BY wcnd.condition_id, wcnd.condition_name
            ORDER BY count DESC
        ");
        $this->bindAnalyticsParams($db, $params);
        $conditionData = $db->resultSet() ?: [];

        // Purok analysis
        $db->query("
            SELECT p.purok_name, COUNT(*) as total_reports,
                   (SELECT wc2.category_name FROM reports r2
                    JOIN waste_categories wc2 ON r2.category_id = wc2.category_id
                    JOIN report_statuses rs2 ON r2.status_id = rs2.status_id
                    WHERE r2.purok_id = r.purok_id
                    AND DATE(r2.submission_date) BETWEEN :date_from AND :date_to
                    GROUP BY wc2.category_id, wc2.category_name ORDER BY COUNT(*) DESC LIMIT 1) as dominant_category
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
            GROUP BY r.purok_id, p.purok_name
            ORDER BY total_reports DESC
        ");
        $this->bindAnalyticsParams($db, $params);
        $purokData = $db->resultSet() ?: [];

        // Purok category breakdown for stacked chart
        $db->query("
            SELECT p.purok_name, wc.category_name, COUNT(*) as count
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            JOIN waste_categories wc ON r.category_id = wc.category_id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
            GROUP BY r.purok_id, p.purok_name, r.category_id, wc.category_name
            ORDER BY p.purok_name, count DESC
        ");
        $this->bindAnalyticsParams($db, $params);
        $purokCategoryRows = $db->resultSet() ?: [];

        $purokStacked = ['labels' => [], 'datasets' => []];
        $categorySet = [];
        $purokMap = [];
        foreach ($purokCategoryRows as $row) {
            $purokMap[$row['purok_name']][$row['category_name']] = (int)$row['count'];
            $categorySet[$row['category_name']] = true;
        }
        $purokStacked['labels'] = array_keys($purokMap);
        $colors = ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#F97316'];
        $ci = 0;
        foreach (array_keys($categorySet) as $catName) {
            $purokStacked['datasets'][] = [
                'label' => $catName,
                'data' => array_map(function ($purok) use ($purokMap, $catName) {
                    return $purokMap[$purok][$catName] ?? 0;
                }, $purokStacked['labels']),
                'backgroundColor' => $colors[$ci % count($colors)],
            ];
            $ci++;
        }

        // Hotspot intelligence
        $db->query("
            SELECT p.purok_name, COUNT(*) as report_count,
                   (SELECT wc2.category_name FROM reports r2
                    JOIN waste_categories wc2 ON r2.category_id = wc2.category_id
                    WHERE r2.purok_id = r.purok_id
                    GROUP BY wc2.category_id, wc2.category_name ORDER BY COUNT(*) DESC LIMIT 1) as dominant_category,
                   MAX(r.submission_date) as latest_report,
                   SUM(CASE WHEN rs.status_name NOT IN ('Resolved', 'Rejected') THEN 1 ELSE 0 END) as unresolved_count
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
            GROUP BY r.purok_id, p.purok_name
            HAVING COUNT(*) >= 3
            ORDER BY report_count DESC
            LIMIT 5
        ");
        $this->bindAnalyticsParams($db, $params);
        $hotspotIntelligence = $db->resultSet() ?: [];

        // Community & operational performance
        $db->query("
            SELECT SUM(r.support_count) as total_supports
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
        ");
        $this->bindAnalyticsParams($db, $params);
        $supportTotal = $db->single();
        $totalSupports = (int)($supportTotal['total_supports'] ?? 0);
        $supportToReportRatio = $total > 0 ? round($totalSupports / $total, 2) : 0;

        $db->query("
            SELECT MIN(TIMESTAMPDIFF(HOUR, r.submission_date, h.changed_at)) as fastest,
                   MAX(TIMESTAMPDIFF(HOUR, r.submission_date, h.changed_at)) as longest
            FROM reports r
            JOIN report_status_history h ON r.id = h.report_id
            WHERE h.new_status = 'Resolved'
            AND DATE(r.submission_date) BETWEEN :date_from AND :date_to
        ");
        $db->bind(':date_from', $filters['date_from']);
        $db->bind(':date_to', $filters['date_to']);
        $resolutionTimes = $db->single();

        // Resident vs Guest Participation Breakdown
        $db->query("
            SELECT
                SUM(CASE WHEN r.reporter_type = 'resident' OR (r.resident_id IS NOT NULL AND r.resident_id > 0) THEN 1 ELSE 0 END) as resident_reports,
                SUM(CASE WHEN r.reporter_type = 'guest' OR (r.resident_id IS NULL OR r.resident_id = 0) THEN 1 ELSE 0 END) as guest_reports,
                COUNT(*) as total_reports
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            $where
        ");
        $this->bindAnalyticsParams($db, $params);
        $participationRow = $db->single() ?: ['resident_reports' => 0, 'guest_reports' => 0, 'total_reports' => 0];

        $residentCount = (int)($participationRow['resident_reports'] ?? 0);
        $guestCount = (int)($participationRow['guest_reports'] ?? 0);
        $partTotal = $residentCount + $guestCount;
        $residentPct = $partTotal > 0 ? round(($residentCount / $partTotal) * 100, 1) : 0;
        $guestPct = $partTotal > 0 ? round(($guestCount / $partTotal) * 100, 1) : 0;

        $participationData = [
            'resident_count' => $residentCount,
            'guest_count' => $guestCount,
            'total_count' => $partTotal,
            'resident_pct' => $residentPct,
            'guest_pct' => $guestPct,
        ];

        // Trend comparison vs previous period
        $periodDays = max(1, (strtotime($filters['date_to']) - strtotime($filters['date_from'])) / 86400 + 1);
        $previousFrom = date('Y-m-d', strtotime($filters['date_from'] . ' -' . (int)$periodDays . ' days'));
        $previousTo = date('Y-m-d', strtotime($filters['date_from'] . ' -1 day'));

        $db->query("
            SELECT COUNT(*) as total,
                   SUM(CASE WHEN rs.status_name = 'Resolved' THEN 1 ELSE 0 END) as resolved
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            WHERE DATE(r.submission_date) BETWEEN :from AND :to
        ");
        $db->bind(':from', $previousFrom);
        $db->bind(':to', $previousTo);
        $prevPeriod = $db->single();
        $prevTotal = (int)($prevPeriod['total'] ?? 0);
        $prevResolved = (int)($prevPeriod['resolved'] ?? 0);
        $prevResolutionRate = $prevTotal > 0 ? round(($prevResolved / $prevTotal) * 100) : 0;

        $trendComparison = [
            'total_reports' => [
                'current' => $total,
                'previous' => $prevTotal,
                'change' => $prevTotal > 0 ? round((($total - $prevTotal) / $prevTotal) * 100, 1) : ($total > 0 ? 100 : 0),
            ],
            'resolution_rate' => [
                'current' => $kpis['resolution_rate'],
                'previous' => $prevResolutionRate,
                'change' => $prevResolutionRate > 0 ? round((($kpis['resolution_rate'] - $prevResolutionRate) / $prevResolutionRate) * 100, 1) : ($kpis['resolution_rate'] > 0 ? 100 : 0),
            ],
        ];

        // Decision support
        $decisionSupport = [];
        if (!empty($hotspotIntelligence)) {
            $decisionSupport['highest_hotspot'] = $hotspotIntelligence[0];
            $decisionSupport['emerging_hotspot'] = end($hotspotIntelligence);
        }
        $monthlyTrend = $trendDataByGranularity['monthly'] ?? [];
        $trendIncrease = false;
        if (count($monthlyTrend) >= 2) {
            $last = end($monthlyTrend);
            $prev = prev($monthlyTrend);
            if ($last && $prev && ($last['count'] ?? 0) > ($prev['count'] ?? 0)) {
                $trendIncrease = true;
            }
        }
        $decisionSupport['trend_increasing'] = $trendIncrease;

        // Filtered report list for table
        $db->query("
            SELECT r.id, r.description, r.submission_date, r.support_count,
                   r.resident_id, r.reporter_type, r.guest_name,
                   u.name, u.email, r.latitude, r.longitude,
                   rs.status_name as status,
                   wc.category_name as waste_category,
                   p.purok_name as purok
            FROM reports r
            LEFT JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            $where
            ORDER BY r.submission_date DESC
        ");
        $this->bindAnalyticsParams($db, $params);
        $filteredReports = $db->resultSet() ?: [];

        foreach ($filteredReports as $key => $report) {
            $rawName = !empty($report['name']) ? $report['name'] : (!empty($report['guest_name']) ? $report['guest_name'] : '');
            $isResident = !empty($report['resident_id']) || (($report['reporter_type'] ?? '') === 'resident');
            if (!empty($rawName)) {
                $titleName = mb_convert_case(trim($rawName), MB_CASE_TITLE, 'UTF-8');
                $filteredReports[$key]['name'] = $titleName;
                $filteredReports[$key]['reporter_formatted'] = $isResident ? "{$titleName} (Resident)" : "{$titleName} (Guest)";
            } else {
                $filteredReports[$key]['name'] = $isResident ? 'Resident' : 'Unknown (Guest)';
                $filteredReports[$key]['reporter_formatted'] = $isResident ? 'Resident' : 'Unknown (Guest)';
            }

            $filteredReports[$key]['location'] = !empty($report['purok']) 
                ? $report['purok'] . ', Barangay Dulong Bayan' 
                : ((!empty($report['latitude']) && !empty($report['longitude'])) ? 'Lat: ' . round($report['latitude'], 4) . ', Lng: ' . round($report['longitude'], 4) : 'Barangay Dulong Bayan');
        }

        // Filter dropdown options
        $db->query("SELECT * FROM waste_categories WHERE is_active = 1 ORDER BY category_name");
        $categories = $db->resultSet();
        $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
        $puroks = $db->resultSet();
        $db->query("SELECT * FROM report_statuses ORDER BY status_id");
        $statuses = $db->resultSet();
        $db->query("SELECT * FROM estimated_quantities ORDER BY quantity_id");
        $quantities = $db->resultSet();
        $db->query("SELECT * FROM waste_conditions ORDER BY condition_id");
        $conditions = $db->resultSet();

        $activeTrend = $trendDataByGranularity[$filters['trend_granularity']] ?? $trendDataByGranularity['monthly'];
        foreach ($activeTrend as &$t) {
            $t['month'] = $t['period'];
        }
        unset($t);

        return [
            'kpis' => $kpis,
            'trend_data' => $activeTrend,
            'trend_data_by_granularity' => $trendDataByGranularity,
            'category_data' => $categoryData,
            'status_data' => $statusData,
            'condition_data' => $conditionData,
            'participation_data' => $participationData,
            'purok_data' => $purokData,
            'purok_stacked' => $purokStacked,
            'hotspot_intelligence' => $hotspotIntelligence,
            'total_supports' => $totalSupports,
            'support_to_report_ratio' => $supportToReportRatio,
            'avg_resolution_hours' => $kpis['avg_resolution_hours'],
            'avg_verification_hours' => $kpis['avg_verification_hours'],
            'fastest_resolution' => $resolutionTimes ? (int)($resolutionTimes['fastest'] ?? 0) : 0,
            'longest_resolution' => $resolutionTimes ? (int)($resolutionTimes['longest'] ?? 0) : 0,
            'trend_comparison' => $trendComparison,
            'decision_support' => $decisionSupport,
            'filtered_reports' => $filteredReports,
            'categories' => $categories,
            'puroks' => $puroks,
            'statuses' => $statuses,
            'quantities' => $quantities,
            'conditions' => $conditions,
            'date_from' => $filters['date_from'],
            'date_to' => $filters['date_to'],
            'selected_category' => $filters['category'],
            'selected_purok' => $filters['purok'],
            'selected_status' => $filters['status'],
            'selected_quantity' => $filters['quantity'],
            'selected_condition' => $filters['condition'],
            'trend_granularity' => $filters['trend_granularity'],
        ];
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

    /**
     * Dispatch status notifications (Email & SMS) to report submitter (Guest or Resident).
     */
    private function dispatchStatusNotifications($reportData, $newStatusKey, $remark = '') {
        if (empty($reportData) || empty($newStatusKey)) return;

        $trackingNumber = !empty($reportData['tracking_number']) ? $reportData['tracking_number'] : ('WR-' . str_pad($reportData['id'] ?? 0, 6, '0', STR_PAD_LEFT));
        $recipientName  = !empty($reportData['guest_name']) ? $reportData['guest_name'] : (!empty($reportData['resident_name']) ? $reportData['resident_name'] : 'Citizen');
        $extraDetails   = [
            'category_name' => $reportData['category_name'] ?? 'Waste Incident',
            'purok_name'    => $reportData['purok_name'] ?? '',
            'location'      => $reportData['location'] ?? ''
        ];

        // 1. Check for Guest or Resident Email
        $targetEmail = '';
        if (!empty($reportData['reporter_type']) && $reportData['reporter_type'] === 'guest') {
            if (!empty($reportData['guest_email']) && filter_var($reportData['guest_email'], FILTER_VALIDATE_EMAIL)) {
                $targetEmail = trim($reportData['guest_email']);
            } elseif (!empty($reportData['guest_phone']) && filter_var($reportData['guest_phone'], FILTER_VALIDATE_EMAIL)) {
                $targetEmail = trim($reportData['guest_phone']);
            }
        } elseif (!empty($reportData['resident_email']) && filter_var($reportData['resident_email'], FILTER_VALIDATE_EMAIL)) {
            $targetEmail = trim($reportData['resident_email']);
        }

        if (!empty($targetEmail)) {
            require_once __DIR__ . '/../Models/Helpers/OtpMailer.php';
            try {
                OtpMailer::sendReportStatusEmail(
                    $targetEmail,
                    $trackingNumber,
                    $newStatusKey,
                    $recipientName,
                    $remark,
                    $extraDetails
                );
            } catch (\Throwable $e) {
                error_log('[AdminController] Status Email dispatch failed: ' . $e->getMessage());
            }
        }

        // 2. Check for SMS notification (if guest phone is in PH mobile format 09XXXXXXXXX)
        $phone = !empty($reportData['guest_phone']) ? trim($reportData['guest_phone']) : '';
        if (!empty($phone) && preg_match('/^09\d{9}$/', $phone)) {
            require_once __DIR__ . '/../Models/Helpers/SmsHelper.php';
            try {
                SmsHelper::sendStatusUpdate($phone, $trackingNumber, $newStatusKey, $recipientName);
            } catch (\Throwable $e) {
                error_log('[AdminController] Status SMS dispatch failed: ' . $e->getMessage());
            }
        }
    }
}