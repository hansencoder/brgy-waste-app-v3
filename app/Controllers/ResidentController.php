<?php
class ResidentController extends Controller {
    private $reportModel;
    private $auditModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'resident') {
            header('Location: /brgy-waste-app-v3/public/auth');
            exit;
        }

        $this->reportModel = $this->model('Report');
        $this->auditModel = $this->model('AuditLog');
    }

    public function index() {
        // Track Submitted Reports (FR-05)
        $data['reports'] = $this->reportModel->getReportsByResident($_SESSION['user_id']);
        $data['stats'] = $this->reportModel->getDashboardStatsByResident($_SESSION['user_id']);
        $data['map_pins'] = $this->reportModel->getHeatmapDataByResident($_SESSION['user_id']);
        $this->view('resident/dashboard', $data);
    }

    public function submit() {
        $data = ['error' => '', 'success' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $description = trim($_POST['description']);
            $lat = $_POST['latitude'];
            $lng = $_POST['longitude'];

            if (strlen($description) < 10 || strlen($description) > 500) {
                $data['error'] = 'Description must be between 10 and 500 characters.';
                return $this->view('resident/submit_report', $data);
            }

            if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                $data['error'] = 'A photo of the waste is required.';
                return $this->view('resident/submit_report', $data);
            }

            // Image validation
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $fileType = $_FILES['photo']['type'];
            $fileSize = $_FILES['photo']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $data['error'] = 'Invalid file format. Only JPG, JPEG and PNG are allowed.';
                return $this->view('resident/submit_report', $data);
            }

            if ($fileSize > 5 * 1024 * 1024) { // 5MB limit
                $data['error'] = 'File size exceeds 5MB limit.';
                return $this->view('resident/submit_report', $data);
            }

            $uploadDir = '../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                
                // FR-04.11 Boundary Check
                // Bounding box approximation for Dulong Bayan
                $is_out_of_bounds = ($lat < 14.66 || $lat > 14.69 || $lng < 121.03 || $lng > 121.06);

                $reportData = [
                    'resident_id' => $_SESSION['user_id'],
                    'photo_path' => $fileName,
                    'description' => $description,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'location_verified' => !$is_out_of_bounds
                ];

                if ($this->reportModel->createReport($reportData)) {
                    $this->auditModel->logAction($_SESSION['user_id'], 'Report Submitted', 'Waste Report', "User submitted report", 'success');
                    $data['success'] = 'Report submitted successfully.';
                } else {
                    $data['error'] = 'Database error while saving report.';
                }
            } else {
                $data['error'] = 'Failed to upload photo.';
            }
        }

        $this->view('resident/submit_report', $data);
    }

    public function my_report() {
        $data['reports'] = $this->reportModel->getReportsByResident($_SESSION['user_id']);
        $this->view('resident/my_report', $data);
    }

    public function view_report($id) {
        $data['report'] = $this->reportModel->getReportById($id, $_SESSION['user_id']);

        if (!$data['report']) {
            header('Location: /brgy-waste-app-v3/public/resident/my_report');
            exit;
        }

        $data['timeline'] = $this->reportModel->getReportTimeline($id);
        $this->view('resident/view_report', $data);
    }

    public function delete_report($id) {
        $report = $this->reportModel->getReportById($id, $_SESSION['user_id']);

        if (!$report) {
            $_SESSION['error'] = 'Report not found or you do not have permission to delete it.';
            header('Location: /brgy-waste-app-v3/public/resident/my_report');
            exit;
        }

        // Only allow deletion of pending reports
        if ($report['status'] !== 'pending') {
            $_SESSION['error'] = 'Only pending reports can be deleted.';
            header('Location: /brgy-waste-app-v3/public/resident/view_report/' . $id);
            exit;
        }

        if ($this->reportModel->deleteReport($id, $_SESSION['user_id'])) {
            $this->auditModel->logAction($_SESSION['user_id'], 'Report Deleted', "Report ID $id", 'Resident deleted their pending report', 'success');
            $_SESSION['success'] = 'Report deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete report.';
        }

        header('Location: /brgy-waste-app-v3/public/resident/my_report');
        exit;
    }

    public function announcements() {
        $db = new Database();
        $db->query("SELECT * FROM announcements ORDER BY created_at DESC");
        $data['announcements'] = $db->resultSet();
        $this->view('resident/announcements', $data);
    }
}
