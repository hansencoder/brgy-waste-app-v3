<?php

class SupervisorController extends Controller {
    private $auditModel;
    private $userModel;

    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=auth');
            exit;
        }

        // Get user role from database
        $db = new Database();
        $db->query("SELECT r.role_name FROM users u JOIN roles r ON u.role_id = r.role_id WHERE u.id = :id");
        $db->bind(':id', $_SESSION['user_id']);
        $user = $db->single();
        $roleName = $user ? strtolower($user['role_name']) : '';

        // Only allow supervisor access
        if ($roleName !== 'supervisor' && $roleName !== 'administrator') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=auth');
            exit;
        }

        $_SESSION['user_role'] = $roleName;

        $this->auditModel = $this->model('AuditLog');
        $this->userModel = $this->model('User');
    }

    // ============================================================
    // SUPERVISOR DASHBOARD
    // ============================================================
    public function index() {
        $db = new Database();

        // ---------- KPI Cards ----------
        // Total Reports
        $db->query("SELECT COUNT(*) as total FROM reports");
        $totalReports = $db->single()['total'] ?? 0;

        // Pending Verification
        $db->query("
            SELECT COUNT(*) as count 
            FROM reports r 
            JOIN report_statuses rs ON r.status_id = rs.status_id 
            WHERE rs.status_name = 'Pending'
        ");
        $pending = $db->single()['count'] ?? 0;

        // In Progress
        $db->query("
            SELECT COUNT(*) as count 
            FROM reports r 
            JOIN report_statuses rs ON r.status_id = rs.status_id 
            WHERE rs.status_name = 'In Progress'
        ");
        $inProgress = $db->single()['count'] ?? 0;

        // Resolved
        $db->query("
            SELECT COUNT(*) as count 
            FROM reports r 
            JOIN report_statuses rs ON r.status_id = rs.status_id 
            WHERE rs.status_name = 'Resolved'
        ");
        $resolved = $db->single()['count'] ?? 0;

        // Today's Reports
        $db->query("SELECT COUNT(*) as count FROM reports WHERE DATE(submission_date) = CURDATE()");
        $todayReports = $db->single()['count'] ?? 0;

        // Active Hotspots (count of puroks with high report density)
        $db->query("
            SELECT COUNT(DISTINCT purok_id) as count 
            FROM reports 
            WHERE submission_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY purok_id
            HAVING COUNT(*) >= 3
        ");
        $activeHotspots = $db->rowCount();

        // ---------- Report Status Distribution (for donut chart) ----------
        $db->query("
            SELECT rs.status_name, rs.color_code, COUNT(*) as count
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            GROUP BY r.status_id
        ");
        $statusDistribution = $db->resultSet();

        // ---------- Monthly Trends ----------
        $db->query("
            SELECT 
                MONTHNAME(submission_date) as month,
                YEAR(submission_date) as year,
                COUNT(*) as count
            FROM reports
            WHERE submission_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY YEAR(submission_date), MONTH(submission_date)
            ORDER BY YEAR(submission_date), MONTH(submission_date)
        ");
        $monthlyTrends = $db->resultSet();

        // ---------- Recent Reports ----------
        $db->query("
            SELECT 
                r.id,
                r.description,
                r.submission_date,
                u.name as reporter,
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
        $recentReports = $db->resultSet();

        // ---------- Active Hotspots Details ----------
        $db->query("
            SELECT 
                p.purok_name,
                COUNT(*) as report_count,
                wc.category_name as dominant_category
            FROM reports r
            JOIN puroks p ON r.purok_id = p.purok_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            WHERE r.submission_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY r.purok_id
            HAVING COUNT(*) >= 3
            ORDER BY report_count DESC
            LIMIT 4
        ");
        $hotspots = $db->resultSet();

        // ---------- Get current user info ----------
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        // ---------- Unread notifications count ----------
        $notificationModel = $this->model('Notification');
        $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);

        // Prepare data for view
        $data = [
            'total_reports' => $totalReports,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'today_reports' => $todayReports,
            'active_hotspots' => $activeHotspots,
            'status_distribution' => $statusDistribution,
            'monthly_trends' => $monthlyTrends,
            'recent_reports' => $recentReports,
            'hotspots' => $hotspots,
            'user' => $user,
            'unread_count' => $unreadCount
        ];

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'Dashboard Access', 'Supervisor Dashboard', 'Supervisor accessed dashboard', 'success');

        $this->view('supervisor/dashboard', $data);
    }

    // ============================================================
    // REPORTS MONITORING (Read-only)
    // ============================================================
    public function reports() {
        $db = new Database();

        // Get search and filter parameters
        $search = isset($_GET['search']) ? htmlspecialchars(strip_tags($_GET['search']), ENT_QUOTES, 'UTF-8') : '';
        $status = isset($_GET['status']) ? htmlspecialchars(strip_tags($_GET['status']), ENT_QUOTES, 'UTF-8') : '';
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $purok = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;
        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

        $query = "
            SELECT 
                r.*,
                u.name as reporter_name,
                u.email as reporter_email,
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
            WHERE 1=1
        ";

        if (!empty($search)) {
            $query .= " AND (r.description LIKE :search OR u.name LIKE :search OR u.email LIKE :search)";
        }
        if (!empty($status)) {
            $query .= " AND rs.status_name = :status";
        }
        if ($category > 0) {
            $query .= " AND r.category_id = :category";
        }
        if ($purok > 0) {
            $query .= " AND r.purok_id = :purok";
        }
        if (!empty($dateFrom)) {
            $query .= " AND DATE(r.submission_date) >= :date_from";
        }
        if (!empty($dateTo)) {
            $query .= " AND DATE(r.submission_date) <= :date_to";
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
        if ($category > 0) {
            $db->bind(':category', $category);
        }
        if ($purok > 0) {
            $db->bind(':purok', $purok);
        }
        if (!empty($dateFrom)) {
            $db->bind(':date_from', $dateFrom);
        }
        if (!empty($dateTo)) {
            $db->bind(':date_to', $dateTo);
        }

        $data['reports'] = $db->resultSet();

        // Get filter options
        $db->query("SELECT * FROM report_statuses ORDER BY status_id");
        $data['statuses'] = $db->resultSet();

        $db->query("SELECT * FROM waste_categories WHERE is_active = 1 ORDER BY category_name");
        $data['categories'] = $db->resultSet();

        $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
        $data['puroks'] = $db->resultSet();

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'Reports Monitoring', 'Reports', 'Supervisor viewed reports monitoring', 'success');

        $this->view('supervisor/reports', $data);
    }

    // ============================================================
    // VIEW SINGLE REPORT (Read-only)
    // ============================================================
    public function view_report($id) {
        $db = new Database();

        $db->query("
            SELECT 
                r.*,
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
            JOIN users u ON r.resident_id = u.id
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN estimated_quantities eq ON r.quantity_id = eq.quantity_id
            LEFT JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.id = :id
        ");
        $db->bind(':id', $id);
        $data['report'] = $db->single();

        if (!$data['report']) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=supervisor/reports');
            exit;
        }

        // Get timeline
        $reportModel = $this->model('Report');
        $data['timeline'] = $reportModel->getReportTimeline($id);

        // Get location name
        require_once '../app/Core/Geocoding.php';
        $data['report']['location_name'] = Geocoding::getLocationName(
            $data['report']['latitude'],
            $data['report']['longitude']
        );

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'View Report', "Report ID $id", 'Supervisor viewed report details', 'success');

        $this->view('supervisor/view_report', $data);
    }

    // ============================================================
    // GIS MONITORING
    // ============================================================
    /**
 * GIS Monitoring - Report Map and Heatmap
 */
public function gis() {
    // Check if user has supervisor or admin role
    if (!in_array($_SESSION['user_role'], ['supervisor', 'administrator'])) {
        header('Location: /brgy-waste-app-v3/public/index.php?url=auth');
        exit;
    }

    $db = new Database();

    // ---- Get all reports with coordinates ----
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
    $reports = $db->resultSet();

    // ---- Get total mapped reports ----
    $db->query("SELECT COUNT(*) as count FROM reports WHERE latitude IS NOT NULL AND longitude IS NOT NULL");
    $totalMapped = $db->single();
    $totalMapped = (int)($totalMapped['count'] ?? 0);

    // ---- Get active hotspots (puroks with ≥3 reports in last 30 days) ----
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
    $hotspots = $db->resultSet();
    $active_hotspots_count = $db->rowCount();

    // ---- Get highest concern purok (most reports overall) ----
    $db->query("
        SELECT p.purok_name, COUNT(*) as cnt
        FROM reports r
        JOIN puroks p ON r.purok_id = p.purok_id
        GROUP BY r.purok_id
        ORDER BY cnt DESC
        LIMIT 1
    ");
    $topPurok = $db->single();
    $highest_purok = $topPurok ? $topPurok['purok_name'] : 'N/A';

    // ---- Get filter options ----
    $db->query("SELECT * FROM report_statuses ORDER BY status_id");
    $statuses = $db->resultSet();

    $db->query("SELECT * FROM waste_categories WHERE is_active = 1 ORDER BY category_name");
    $categories = $db->resultSet();

    $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
    $puroks = $db->resultSet();

    // ---- Get heatmap settings ----
    $heatmapModel = $this->model('HeatmapSetting');
    $heatmap_settings = $heatmapModel->getConfig();

    // ---- Get current view from GET (default: 'map') ----
    $current_view = isset($_GET['view']) ? $_GET['view'] : 'map';

    // ---- Get filter values from GET (for pre-filling the form) ----
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
    $categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $purokFilter = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

    // ---- Apply filters if any ----
    // (The view will re-query with filters if needed, but we already have all reports)
    // For performance, we could filter here, but we'll let the view handle it.

    // ---- Prepare data for view ----
    $data['reports'] = $reports;
    $data['total_mapped'] = $totalMapped;
    $data['hotspots'] = $hotspots;
    $data['active_hotspots_count'] = $active_hotspots_count;
    $data['highest_purok'] = $highest_purok;
    $data['heatmap_settings'] = $heatmap_settings;
    $data['current_view'] = $current_view;
    $data['statuses'] = $statuses;
    $data['categories'] = $categories;
    $data['puroks'] = $puroks;
    $data['statusFilter'] = $statusFilter;
    $data['categoryFilter'] = $categoryFilter;
    $data['purokFilter'] = $purokFilter;
    $data['dateFrom'] = $dateFrom;
    $data['dateTo'] = $dateTo;

    // ---- Log access ----
    $this->auditModel->logAction(
        $_SESSION['user_id'],
        'GIS Monitoring',
        'GIS',
        'Supervisor viewed GIS monitoring',
        'success'
    );

    // ---- Load view ----
    $this->view('supervisor/gis', $data);
}


// ============================================================
// ANALYTICS
// ============================================================
public function analytics() {
    $db = new Database();

    // ---- Get filters ----
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d', strtotime('-30 days'));
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
    $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $purok = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Build WHERE clause for filters
    $where = " WHERE DATE(r.submission_date) BETWEEN :date_from AND :date_to ";
    $params = [':date_from' => $dateFrom, ':date_to' => $dateTo];
    if ($category > 0) {
        $where .= " AND r.category_id = :category ";
        $params[':category'] = $category;
    }
    if ($purok > 0) {
        $where .= " AND r.purok_id = :purok ";
        $params[':purok'] = $purok;
    }
    if (!empty($status)) {
        $where .= " AND rs.status_name = :status ";
        $params[':status'] = $status;
    }

    // ---------- KPI Cards ----------
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
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $kpis = $db->single();

    // Resolution rate
    $total = (int)($kpis['total'] ?? 0);
    $resolved = (int)($kpis['resolved'] ?? 0);
    $kpis['resolution_rate'] = $total > 0 ? round(($resolved / $total) * 100) : 0;

    // ---- Average Resolution Time (from status history) ----
    $db->query("
        SELECT AVG(TIMESTAMPDIFF(HOUR, r.submission_date, h.changed_at)) as avg_hours
        FROM reports r
        JOIN report_status_history h ON r.id = h.report_id
        WHERE h.new_status = 'Resolved'
        AND r.submission_date BETWEEN :date_from AND :date_to
    ");
    $db->bind(':date_from', $dateFrom);
    $db->bind(':date_to', $dateTo);
    $avgTime = $db->single();
    $kpis['avg_resolution_hours'] = $avgTime ? round($avgTime['avg_hours'], 1) : 0;

    // ---- Active Hotspots (for KPI) ----
    $db->query("
        SELECT COUNT(DISTINCT purok_id) as count
        FROM reports
        WHERE submission_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        AND submission_date BETWEEN :date_from AND :date_to
        GROUP BY purok_id
        HAVING COUNT(*) >= 3
    ");
    $db->bind(':date_from', $dateFrom);
    $db->bind(':date_to', $dateTo);
    $hotspotCount = $db->rowCount();
    $kpis['active_hotspots'] = $hotspotCount;

    // ---------- Report Trends (Monthly) ----------
    $db->query("
        SELECT 
            DATE_FORMAT(submission_date, '%Y-%m') as month,
            COUNT(*) as count
        FROM reports r
        JOIN report_statuses rs ON r.status_id = rs.status_id
        $where
        GROUP BY YEAR(submission_date), MONTH(submission_date)
        ORDER BY YEAR(submission_date), MONTH(submission_date)
    ");
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $trendData = $db->resultSet();

    // ---------- Reports by Category ----------
    $db->query("
        SELECT wc.category_name, COUNT(*) as count
        FROM reports r
        JOIN waste_categories wc ON r.category_id = wc.category_id
        JOIN report_statuses rs ON r.status_id = rs.status_id
        $where
        GROUP BY r.category_id
        ORDER BY count DESC
    ");
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $categoryData = $db->resultSet();

    // ---------- Reports by Status ----------
    $db->query("
        SELECT rs.status_name, rs.color_code, COUNT(*) as count
        FROM reports r
        JOIN report_statuses rs ON r.status_id = rs.status_id
        $where
        GROUP BY r.status_id
    ");
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $statusData = $db->resultSet();

    // ---------- Reports by Waste Condition ----------
    $db->query("
        SELECT wcnd.condition_name, COUNT(*) as count
        FROM reports r
        JOIN waste_conditions wcnd ON r.condition_id = wcnd.condition_id
        JOIN report_statuses rs ON r.status_id = rs.status_id
        $where
        GROUP BY r.condition_id
        ORDER BY count DESC
    ");
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $conditionData = $db->resultSet();

    // ---------- Purok Analysis ----------
    $db->query("
        SELECT p.purok_name, COUNT(*) as total_reports,
               MAX(wc.category_name) as dominant_category
        FROM reports r
        JOIN puroks p ON r.purok_id = p.purok_id
        JOIN waste_categories wc ON r.category_id = wc.category_id
        JOIN report_statuses rs ON r.status_id = rs.status_id
        $where
        GROUP BY r.purok_id
        ORDER BY total_reports DESC
    ");
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $purokData = $db->resultSet();

    // ---------- Hotspot Intelligence ----------
    // Priority ranking: puroks with most reports
    $db->query("
        SELECT p.purok_name, COUNT(*) as report_count,
               wc.category_name as dominant_category,
               MAX(r.submission_date) as latest_report
        FROM reports r
        JOIN puroks p ON r.purok_id = p.purok_id
        JOIN waste_categories wc ON r.category_id = wc.category_id
        JOIN report_statuses rs ON r.status_id = rs.status_id
        $where
        GROUP BY r.purok_id
        HAVING COUNT(*) >= 3
        ORDER BY report_count DESC
        LIMIT 5
    ");
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $hotspotIntelligence = $db->resultSet();

    // ---------- Community & Operational Performance ----------
    // Total support count
    $db->query("
        SELECT SUM(support_count) as total_supports
        FROM reports r
        JOIN report_statuses rs ON r.status_id = rs.status_id
        $where
    ");
    foreach ($params as $key => $val) {
        $db->bind($key, $val);
    }
    $supportTotal = $db->single();
    $totalSupports = (int)($supportTotal['total_supports'] ?? 0);
    $supportToReportRatio = $total > 0 ? round($totalSupports / $total, 2) : 0;

    // Fastest and longest resolution times
    $db->query("
        SELECT MIN(TIMESTAMPDIFF(HOUR, r.submission_date, h.changed_at)) as fastest,
               MAX(TIMESTAMPDIFF(HOUR, r.submission_date, h.changed_at)) as longest
        FROM reports r
        JOIN report_status_history h ON r.id = h.report_id
        WHERE h.new_status = 'Resolved'
        AND r.submission_date BETWEEN :date_from AND :date_to
    ");
    $db->bind(':date_from', $dateFrom);
    $db->bind(':date_to', $dateTo);
    $resolutionTimes = $db->single();

    // ---------- Trend Comparison (current period vs previous period) ----------
    $previousFrom = date('Y-m-d', strtotime($dateFrom . ' -' . (strtotime($dateTo) - strtotime($dateFrom)) . ' days'));
    $previousTo = date('Y-m-d', strtotime($dateFrom . ' -1 day'));

    // Get totals for previous period
    $db->query("
        SELECT COUNT(*) as total,
               SUM(CASE WHEN rs.status_name = 'Resolved' THEN 1 ELSE 0 END) as resolved
        FROM reports r
        JOIN report_statuses rs ON r.status_id = rs.status_id
        WHERE DATE(submission_date) BETWEEN :from AND :to
    ");
    $db->bind(':from', $previousFrom);
    $db->bind(':to', $previousTo);
    $prevPeriod = $db->single();
    $prevTotal = (int)($prevPeriod['total'] ?? 0);
    $prevResolved = (int)($prevPeriod['resolved'] ?? 0);

    $trendComparison = [
        'total_reports' => [
            'current' => $total,
            'previous' => $prevTotal,
            'change' => $prevTotal > 0 ? round((($total - $prevTotal) / $prevTotal) * 100, 1) : ($total > 0 ? 100 : 0)
        ],
        'resolution_rate' => [
            'current' => $kpis['resolution_rate'],
            'previous' => $prevTotal > 0 ? round(($prevResolved / $prevTotal) * 100) : 0,
            'change' => $prevTotal > 0 ? round((($kpis['resolution_rate'] - round(($prevResolved / $prevTotal) * 100)) / round(($prevResolved / $prevTotal) * 100)) * 100, 1) : ($kpis['resolution_rate'] > 0 ? 100 : 0)
        ]
    ];

    // ---------- Decision Support Summary ----------
    $decisionSupport = [];
    // Highest priority hotspot
    if (!empty($hotspotIntelligence)) {
        $decisionSupport['highest_hotspot'] = $hotspotIntelligence[0];
    }
    // Emerging hotspot (newest with high reports)
    $decisionSupport['emerging_hotspot'] = !empty($hotspotIntelligence) ? end($hotspotIntelligence) : null;
    // Increasing waste trends (month over month increase)
    $trendIncrease = false;
    if (count($trendData) >= 2) {
        $last = end($trendData);
        $prev = prev($trendData);
        if ($last && $prev && $last['count'] > $prev['count']) {
            $trendIncrease = true;
        }
    }
    $decisionSupport['trend_increasing'] = $trendIncrease;

    // ---------- Get filter options ----------
    $db->query("SELECT * FROM waste_categories WHERE is_active = 1 ORDER BY category_name");
    $data['categories'] = $db->resultSet();

    $db->query("SELECT * FROM puroks WHERE is_active = 1 ORDER BY purok_name");
    $data['puroks'] = $db->resultSet();

    $db->query("SELECT * FROM report_statuses ORDER BY status_id");
    $data['statuses'] = $db->resultSet();

    // ---------- Prepare data for view ----------
    $data['kpis'] = $kpis;
    $data['trend_data'] = $trendData;
    $data['category_data'] = $categoryData;
    $data['status_data'] = $statusData;
    $data['condition_data'] = $conditionData;
    $data['purok_data'] = $purokData;
    $data['hotspot_intelligence'] = $hotspotIntelligence;
    $data['total_supports'] = $totalSupports;
    $data['support_to_report_ratio'] = $supportToReportRatio;
    $data['avg_resolution_hours'] = $kpis['avg_resolution_hours'];
    $data['fastest_resolution'] = $resolutionTimes ? (int)($resolutionTimes['fastest'] ?? 0) : 0;
    $data['longest_resolution'] = $resolutionTimes ? (int)($resolutionTimes['longest'] ?? 0) : 0;
    $data['trend_comparison'] = $trendComparison;
    $data['decision_support'] = $decisionSupport;
    $data['date_from'] = $dateFrom;
    $data['date_to'] = $dateTo;
    $data['selected_category'] = $category;
    $data['selected_purok'] = $purok;
    $data['selected_status'] = $status;

    $this->auditModel->logAction($_SESSION['user_id'], 'Analytics View', 'Analytics', 'Supervisor viewed analytics', 'success');

    $this->view('supervisor/analytics', $data);
}
      // ============================================================
// COLLECTION SCHEDULE
// ============================================================
public function schedule() {
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
        WHERE cs.status = 'active'
        GROUP BY cs.schedule_id
        ORDER BY FIELD(cs.collection_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ");
    $schedules = $db->resultSet();

    $data['schedules'] = $schedules;
    $data['view'] = $view;

    // For calendar view, get current month/year or from GET
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    $data['month'] = $month;
    $data['year'] = $year;

    // Generate calendar data
    $data['calendar_days'] = $this->generateCalendarData($month, $year, $schedules);

    $this->auditModel->logAction($_SESSION['user_id'], 'Collection Schedule View', 'Schedule', 'Supervisor viewed collection schedule', 'success');

    $this->view('supervisor/schedule', $data);
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

    // ============================================================
    // ANNOUNCEMENTS
    // ============================================================
    public function announcements() {
        $db = new Database();
        $db->query("
            SELECT a.*, u.name as author, av.visibility_name
            FROM announcements a
            LEFT JOIN users u ON a.created_by = u.id
            LEFT JOIN announcement_visibilities av ON a.visibility_id = av.visibility_id
            ORDER BY a.created_at DESC
        ");
        $data['announcements'] = $db->resultSet();

        $this->auditModel->logAction($_SESSION['user_id'], 'Announcements View', 'Announcements', 'Supervisor viewed announcements', 'success');

        $this->view('supervisor/announcements', $data);
    }

    // ============================================================
    // NOTIFICATIONS
    // ============================================================
    public function notifications() {
        $userId = $_SESSION['user_id'];
        $notificationModel = $this->model('Notification');

        $data['notifications'] = $notificationModel->getUserNotifications($userId, 50);
        $data['unread_count'] = $notificationModel->getUnreadCount($userId);

        $this->auditModel->logAction($_SESSION['user_id'], 'Notifications View', 'Notifications', 'Supervisor viewed notifications', 'success');

        $this->view('supervisor/notifications', $data);
    }

    // ============================================================
    // PROFILE
    // ============================================================
    public function profile() {
        $data = ['error' => '', 'success' => ''];
        $db = new Database();

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
                return $this->view('supervisor/profile', $data);
            }
            if (!preg_match('/^09\d{9}$/', $phone)) {
                $data['error'] = 'Invalid Philippine phone number. Must be 11 digits starting with 09.';
                return $this->view('supervisor/profile', $data);
            }

            $db->query("UPDATE users SET name = :name, address = :address, phone_number = :phone WHERE id = :id");
            $db->bind(':name', $name);
            $db->bind(':address', $address);
            $db->bind(':phone', $phone);
            $db->bind(':id', $_SESSION['user_id']);

            if ($db->execute()) {
                $_SESSION['user_name'] = $name;
                $data['success'] = 'Profile updated successfully.';
                $this->auditModel->logAction($_SESSION['user_id'], 'Profile Updated', 'Profile', 'Supervisor updated profile', 'success');
                // Refresh user data
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u LEFT JOIN roles r ON u.role_id = r.role_id LEFT JOIN positions p ON u.position_id = p.position_id LEFT JOIN puroks pk ON u.purok_id = pk.purok_id WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
            } else {
                $data['error'] = 'Failed to update profile.';
            }
        }

        $this->view('supervisor/profile', $data);
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
                    $this->auditModel->logAction($_SESSION['user_id'], 'Password Changed', 'Security', 'Supervisor changed password', 'success');
                } else {
                    $data['error'] = 'Failed to change password.';
                }
            }

            // Refresh user data
            $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u LEFT JOIN roles r ON u.role_id = r.role_id LEFT JOIN positions p ON u.position_id = p.position_id LEFT JOIN puroks pk ON u.purok_id = pk.purok_id WHERE u.id = :id");
            $db->bind(':id', $_SESSION['user_id']);
            $data['user'] = $db->single();
        }

        $this->view('supervisor/profile', $data);
    }
}