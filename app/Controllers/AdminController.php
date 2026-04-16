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
                $this->userModel->updateUserStatus($user_id, 'deactivated');
                $this->auditModel->logAction($_SESSION['user_id'], 'Account Deactivated', "User ID $user_id", "Deactivated account ID $user_id. Reason: $reason", 'success');
            } elseif ($action == 'delete') {
                $this->userModel->deleteUser($user_id);
                $this->auditModel->logAction($_SESSION['user_id'], 'Account Deleted', "User ID $user_id", "Deleted account ID $user_id", 'success');
            }

            header("Location: /brgy-waste-app-v3/public/admin/accounts");
            exit;
        }

        $data['users'] = $this->userModel->getAllUsers();
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

        $data['reports'] = $reportModel->getAllReports();
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

            $this->auditModel->logAction($_SESSION['user_id'], 'Report Generated', 'Report Summary', "Format: $format", 'success');

            if ($format == 'csv') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=waste_report_summary.csv');
                $output = fopen('php://output', 'w');
                fputcsv($output, array('ID', 'Date Submitted', 'Reporter Name', 'Email', 'Description', 'Status', 'Latitude', 'Longitude'));
                foreach ($reports as $r) {
                    fputcsv($output, array($r['id'], $r['submission_date'], $r['name'], $r['email'], $r['description'], $r['status'], $r['latitude'], $r['longitude']));
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
}
