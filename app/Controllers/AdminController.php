<?php
class AdminController extends Controller {
    private $userModel;
    private $auditModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['secretary', 'captain'])) {
            header('Location: /brgy-waste-app-v3/public/auth');
            exit;
        }

        $this->userModel = $this->model('User');
        $this->auditModel = $this->model('AuditLog');
    }

    public function index() {
        $reportModel = $this->model('Report');
        $db = new Database();

        // Core stats
        $data['stats'] = $reportModel->getDashboardStats();
        $data['heatmap'] = $reportModel->getHeatmapData();

        // New reports submitted today
        $db->query("SELECT COUNT(*) as count FROM reports WHERE DATE(submission_date) = CURDATE()");
        $todayRow = $db->single();
        $data['today_count'] = $todayRow ? (int)$todayRow['count'] : 0;

        // Pending reports count
        $data['pending_count'] = (int)($data['stats']['pending'] ?? 0);

        // Active residents count
        $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'resident' AND status = 'active'");
        $resRow = $db->single();
        $data['active_residents'] = $resRow ? (int)$resRow['count'] : 0;

        // Resolution rate
        $total = (int)($data['stats']['total'] ?? 0);
        $resolved = (int)($data['stats']['resolved'] ?? 0);
        $data['resolution_rate'] = $total > 0 ? round(($resolved / $total) * 100) : 0;

        // Recent 5 reports with submitter name
        $db->query("SELECT r.id, r.description, r.status, r.submission_date, u.name as resident_name
                    FROM reports r
                    JOIN users u ON r.resident_id = u.id
                    ORDER BY r.submission_date DESC
                    LIMIT 5");
        $data['recent_reports'] = $db->resultSet();

        // Recent 5 activity log entries
        $db->query("SELECT a.action, a.details, a.created_at, u.name as user_name
                    FROM audit_logs a
                    LEFT JOIN users u ON a.user_id = u.id
                    WHERE a.action != 'Dashboard Access'
                    ORDER BY a.created_at DESC
                    LIMIT 5");
        $data['recent_activity'] = $db->resultSet();

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'Dashboard Access', 'Dashboard', 'Admin accessed dashboard', 'success');
        $this->view('admin/dashboard', $data);
    }

    public function accounts() {
        // Only secretary manages accounts per FR-02
        if ($_SESSION['user_role'] != 'secretary') {
            die("Unauthorized Access: Only Barangay Secretary can manage accounts.");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
            
            // Validate that user_id is a valid integer
            if (!$user_id || $user_id === false) {
                header("Location: /brgy-waste-app-v3/public/admin/accounts");
                exit;
            }
            
            $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING) ?: '';
            $action = $_POST['action'];

            // Load Notification model
            require_once __DIR__ . '/../Models/Notification.php';
            $notificationModel = new Notification();

            if ($action == 'approve') {
                $this->userModel->updateUserStatus($user_id, 'active');
                
                // Create notification for account approval
                $notificationModel->createAccountApprovedNotification($user_id, $_SESSION['user_id']);
                
                $this->auditModel->logAction($_SESSION['user_id'], 'Account Approved', "User ID $user_id", "Approved account ID $user_id", 'success');
            } elseif ($action == 'reject') {
                $this->userModel->deleteUser($user_id);
                $this->auditModel->logAction($_SESSION['user_id'], 'Account Rejected', "User ID $user_id", "Rejected account ID $user_id. Reason: $reason", 'success');
            } elseif ($action == 'deactivate') {
                $result = $this->userModel->updateUserStatus($user_id, 'deactivated');
                $this->auditModel->logAction($_SESSION['user_id'], 'Account Deactivated', "User ID $user_id", "Deactivated account ID $user_id. Reason: $reason", $result ? 'success' : 'failed');
            } elseif ($action == 'reactivate') {
                $result = $this->userModel->updateUserStatus($user_id, 'active');
                $this->auditModel->logAction($_SESSION['user_id'], 'Account Reactivated', "User ID $user_id", "Reactivated account ID $user_id", $result ? 'success' : 'failed');
            } elseif ($action == 'remove') {
                $this->userModel->deleteUser($user_id);
                $this->auditModel->logAction($_SESSION['user_id'], 'Account Removed', "User ID $user_id", "Removed account ID $user_id. Reason: $reason", 'success');
            }

            header("Location: /brgy-waste-app-v3/public/admin/accounts");
            exit;
        }

        $data['users'] = $this->userModel->getAllUsers();
        
        // Add report counts for each resident user
        $reportModel = $this->model('Report');
        foreach ($data['users'] as &$user) {
            if ($user['role'] == 'resident') {
                $userReports = $reportModel->getReportsByResident($user['id']);
                $user['report_count'] = count($userReports);
            } else {
                $user['report_count'] = 0;
            }
        }
        
        $this->view('admin/accounts', $data);
    }

    public function reports() {
        if ($_SESSION['user_role'] != 'secretary') {
            die("Unauthorized Access: Only Barangay Secretary can manage reports.");
        }

        $reportModel = $this->model('Report');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $report_id = filter_var($post['report_id'], FILTER_VALIDATE_INT);
            $action = $post['action'];
            $remark = filter_var($post['remark'], FILTER_SANITIZE_STRING) ?: '';

            // Load Notification model
            require_once __DIR__ . '/../Models/Notification.php';
            $notificationModel = new Notification();

            // Get old status for notification
            $db = new Database();
            $db->query("SELECT status FROM reports WHERE id = :id");
            $db->bind(':id', $report_id);
            $oldReport = $db->single();
            $oldStatus = $oldReport ? $oldReport['status'] : 'pending';

            if ($action == 'verify') {
                $reportModel->updateReportStatus($report_id, 'verified', $_SESSION['user_id']);
                
                // Create notification for report status change
                $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'verified', $_SESSION['user_id']);
                
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Verified', "Report ID $report_id", "Verified report. Remark: $remark", 'success');
            } elseif ($action == 'resolve') {
                $reportModel->updateReportStatus($report_id, 'resolved', $_SESSION['user_id']);
                
                // Create notification for report status change
                $notificationModel->createReportStatusNotification($report_id, $oldStatus, 'resolved', $_SESSION['user_id']);
                
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Resolved', "Report ID $report_id", "Resolved report. Remark: $remark", 'success');
            } elseif ($action == 'delete') {
                $reportModel->deleteReport($report_id);
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Deleted', "Report ID $report_id", "Deleted report due to: $remark", 'success');
            }

            header("Location: /brgy-waste-app-v3/public/admin/reports");
            exit;
        }

        // Get search and status filters from GET parameters
        $search = isset($_GET['search']) ? filter_var($_GET['search'], FILTER_SANITIZE_STRING) : '';
        $status = isset($_GET['status']) ? filter_var($_GET['status'], FILTER_SANITIZE_STRING) : '';

        // Build query with filters
        $db = new Database();
        $query = "SELECT r.*, u.name, u.email FROM reports r JOIN users u ON r.resident_id = u.id WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND (r.description LIKE :search OR u.name LIKE :search OR u.email LIKE :search)";
        }
        
        if (!empty($status)) {
            $query .= " AND r.status = :status";
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
        require_once '../app/Core/Geocoding.php';
        foreach ($data['reports'] as &$report) {
            $report['location_name'] = Geocoding::getLocationName(
                $report['latitude'],
                $report['longitude']
            );
        }
        
        $this->view('admin/reports', $data);
    }

    public function export() {
        if ($_SESSION['user_role'] != 'secretary') {
            die("Unauthorized Access: Only Secretary can generate summaries.");
        }
        
        $reportModel = $this->model('Report');
        
        if (isset($_GET['format'])) {
            $format = $_GET['format'];
            $reports = $reportModel->getAllReports();
            
            // Add location names to each report
            require_once '../app/Core/Geocoding.php';
            foreach ($reports as &$r) {
                $r['location_name'] = Geocoding::getLocationName(
                    $r['latitude'],
                    $r['longitude']
                );
            }

            $this->auditModel->logAction($_SESSION['user_id'], 'Report Generated', 'Report Summary', "Format: $format", 'success');

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

    public function report_summaries() {
        if ($_SESSION['user_role'] != 'secretary') {
            die("Unauthorized Access: Only Barangay Secretary can generate report summaries.");
        }

        $reportModel = $this->model('Report');
        $db = new Database();

        // Get previous exports (recent exports) - safely handle if table doesn't exist
        $data['exports'] = array();
        try {
            $db->query("SELECT * FROM exports ORDER BY created_at DESC LIMIT 10");
            $result = $db->resultSet();
            if ($result) {
                $data['exports'] = $result;
            }
        } catch (Exception $e) {
            // Table doesn't exist yet, that's okay - just continue with empty exports
            $data['exports'] = array();
        }

        // Log access
        $this->auditModel->logAction($_SESSION['user_id'], 'Report Summaries Access', 'Report Summaries', 'Accessed report summaries page', 'success');

        $this->view('admin/report_summaries', $data);
    }

    public function auditLogs() {
        if ($_SESSION['user_role'] != 'secretary') {
            die("Unauthorized Access: Only Secretary can view audit logs.");
        }

        // Get all logs
        $db = new Database();
        $db->query("SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC");
        $data['logs'] = $db->resultSet();
        $this->view('admin/audit_logs', $data);
    }

    public function announcements() {
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user_role'] == 'secretary') {
            if (!empty($_POST['title']) && !empty($_POST['content'])) {
                $db->query("INSERT INTO announcements (title, content, created_by) VALUES (:title, :content, :created_by)");
                $db->bind(':title', filter_var($_POST['title'], FILTER_SANITIZE_STRING));
                $db->bind(':content', filter_var($_POST['content'], FILTER_SANITIZE_STRING));
                $db->bind(':created_by', $_SESSION['user_id']);
                $db->execute();
                
                // Get the announcement ID
                $announcementId = $db->lastInsertId();

                // Create notification for all users about the new announcement
                require_once __DIR__ . '/../Models/Notification.php';
                $notificationModel = new Notification();
                $notificationModel->createAnnouncementNotification($announcementId, $_SESSION['user_id']);

                $this->auditModel->logAction($_SESSION['user_id'], 'Post Announcement', 'Announcements', "Posted '{$_POST['title']}'", 'success');
                header("Location: /brgy-waste-app-v3/public/admin/announcements");
                exit;
            }
        }

        $db->query("SELECT * FROM announcements ORDER BY created_at DESC");
        $data['announcements'] = $db->resultSet();
        $this->view('admin/announcements', $data);
    }

    public function delete_announcement() {
        if ($_SESSION['user_role'] != 'secretary') {
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

        header("Location: /brgy-waste-app-v3/public/admin/announcements");
        exit;
    }

    // API endpoint for getting filtered reports
    public function getFilteredReports() {
        if ($_SESSION['user_role'] != 'secretary') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $dateFrom = isset($_GET['dateFrom']) ? filter_var($_GET['dateFrom'], FILTER_SANITIZE_STRING) : '';
        $dateTo = isset($_GET['dateTo']) ? filter_var($_GET['dateTo'], FILTER_SANITIZE_STRING) : '';
        $status = isset($_GET['status']) ? filter_var($_GET['status'], FILTER_SANITIZE_STRING) : '';

        $db = new Database();
        $reportModel = $this->model('Report');

        // Build query with filters
        $query = "SELECT r.id, r.description, r.status, r.submission_date, u.name, u.email, r.latitude, r.longitude 
                  FROM reports r 
                  JOIN users u ON r.resident_id = u.id 
                  WHERE 1=1";

        if ($dateFrom) {
            $query .= " AND DATE(r.submission_date) >= :dateFrom";
        }

        if ($dateTo) {
            $query .= " AND DATE(r.submission_date) <= :dateTo";
        }

        if ($status && $status !== '') {
            $query .= " AND r.status = :status";
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

        // Add location names and format data
        require_once '../app/Core/Geocoding.php';
        foreach ($reports as &$report) {
            $report['location'] = Geocoding::getLocationName($report['latitude'], $report['longitude']);
            $report['date'] = date('m/d/Y', strtotime($report['submission_date']));
        }

        // Calculate summary
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

    // Export reports to PDF
    public function exportReportSummaryPDF() {
        if ($_SESSION['user_role'] != 'secretary') {
            die("Unauthorized Access");
        }

        $dateFrom = isset($_GET['dateFrom']) ? filter_var($_GET['dateFrom'], FILTER_SANITIZE_STRING) : '';
        $dateTo = isset($_GET['dateTo']) ? filter_var($_GET['dateTo'], FILTER_SANITIZE_STRING) : '';
        $status = isset($_GET['status']) ? filter_var($_GET['status'], FILTER_SANITIZE_STRING) : '';

        $db = new Database();

        // Build query with filters
        $query = "SELECT r.id, r.description, r.status, r.submission_date, u.name, u.email, r.latitude, r.longitude 
                  FROM reports r 
                  JOIN users u ON r.resident_id = u.id 
                  WHERE 1=1";

        if ($dateFrom) {
            $query .= " AND DATE(r.submission_date) >= :dateFrom";
        }
        if ($dateTo) {
            $query .= " AND DATE(r.submission_date) <= :dateTo";
        }
        if ($status && $status !== '') {
            $query .= " AND r.status = :status";
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

        // Generate simple HTML for PDF conversion
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
        </body>
        </html>";

        // For now, provide download as HTML that can be printed to PDF
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="report_summary_' . date('Y-m-d') . '.html"');
        echo $html;
        exit;
    }

    // Export reports to XLSX
    public function exportReportSummaryXLSX() {
        if ($_SESSION['user_role'] != 'secretary') {
            die("Unauthorized Access");
        }

        $dateFrom = isset($_GET['dateFrom']) ? filter_var($_GET['dateFrom'], FILTER_SANITIZE_STRING) : '';
        $dateTo = isset($_GET['dateTo']) ? filter_var($_GET['dateTo'], FILTER_SANITIZE_STRING) : '';
        $status = isset($_GET['status']) ? filter_var($_GET['status'], FILTER_SANITIZE_STRING) : '';

        $db = new Database();

        // Build query with filters
        $query = "SELECT r.id, r.description, r.status, r.submission_date, u.name, u.email, r.latitude, r.longitude 
                  FROM reports r 
                  JOIN users u ON r.resident_id = u.id 
                  WHERE 1=1";

        if ($dateFrom) {
            $query .= " AND DATE(r.submission_date) >= :dateFrom";
        }
        if ($dateTo) {
            $query .= " AND DATE(r.submission_date) <= :dateTo";
        }
        if ($status && $status !== '') {
            $query .= " AND r.status = :status";
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

        // Generate CSV for XLSX
        $filename = "report_summary_" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Add headers
        fputcsv($output, ['Report ID', 'Resident Name', 'Email', 'Description', 'Status', 'Submission Date']);

        // Add data
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
}
