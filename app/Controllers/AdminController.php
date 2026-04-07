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
        $data['stats'] = $reportModel->getDashboardStats();
        $data['heatmap'] = $reportModel->getHeatmapData();

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

            if ($action == 'approve') {
                $this->userModel->updateUserStatus($user_id, 'active');
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
            $report_id = filter_var($_POST['report_id'], FILTER_VALIDATE_INT);
            $action = $_POST['action'];
            $remark = filter_var($_POST['remark'], FILTER_SANITIZE_STRING) ?: '';

            if ($action == 'verify') {
                $reportModel->updateReportStatus($report_id, 'verified', $remark);
                $this->auditModel->logAction($_SESSION['user_id'], 'Report Verified', "Report ID $report_id", "Verified report. Remark: $remark", 'success');
            } elseif ($action == 'resolve') {
                $reportModel->updateReportStatus($report_id, 'resolved', $remark);
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
                    fputcsv($output, array($r['id'], $r['created_at'], $r['full_name'], $r['email'], $r['description'], $r['status'], $r['latitude'], $r['longitude']));
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
        $db->query("SELECT a.*, u.full_name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC");
        $data['logs'] = $db->resultSet();
        $this->view('admin/audit_logs', $data);
    }

    public function announcements() {
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user_role'] == 'secretary') {
            if (!empty($_POST['title']) && !empty($_POST['message'])) {
                $db->query("INSERT INTO notifications (type, title, message) VALUES ('announcement', :title, :message)");
                $db->bind(':title', filter_var($_POST['title'], FILTER_SANITIZE_STRING));
                $db->bind(':message', filter_var($_POST['message'], FILTER_SANITIZE_STRING));
                $db->execute();
                
                $this->auditModel->logAction($_SESSION['user_id'], 'Post Announcement', 'Announcements', "Posted '{$_POST['title']}'", 'success');
                header("Location: /brgy-waste-app-v3/public/admin/announcements");
                exit;
            }
        }

        $db->query("SELECT * FROM notifications WHERE type = 'announcement' ORDER BY created_at DESC");
        $data['announcements'] = $db->resultSet();
        $this->view('admin/announcements', $data);
    }
}
