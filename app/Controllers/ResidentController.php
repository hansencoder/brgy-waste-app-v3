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

                // FR-04.11 Boundary Check - Barangay Dulong Bayan
                // Point-in-polygon algorithm to check if location is within barangay boundaries
                $barangayBoundary = [
                    [15.56992, 120.80135], [15.56728, 120.80018], [15.56570, 120.79897],
                    [15.56528, 120.79751], [15.56375, 120.79516], [15.56032, 120.79464],
                    [15.55485, 120.79121], [15.54781, 120.80013], [15.55061, 120.80494],
                    [15.55288, 120.80886], [15.54962, 120.81743], [15.55121, 120.82609],
                    [15.55413, 120.83358], [15.55740, 120.83261], [15.56506, 120.82838],
                    [15.57034, 120.82364], [15.56455, 120.82033], [15.56098, 120.81492],
                    [15.56739, 120.80324], [15.56992, 120.80135]
                ];
                
                // Point-in-polygon check
                $isInside = false;
                $j = count($barangayBoundary) - 1;
                for ($i = 0; $i < count($barangayBoundary); $i++) {
                    $xi = $barangayBoundary[$i][0]; $yi = $barangayBoundary[$i][1];
                    $xj = $barangayBoundary[$j][0]; $yj = $barangayBoundary[$j][1];
                    
                    $intersect = (($yi > $lng) != ($yj > $lng)) && ($lat < ($xj - $xi) * ($lng - $yi) / ($yj - $yi) + $xi);
                    if ($intersect) $isInside = !$isInside;
                    $j = $i;
                }
                
                if (!$isInside) {
                    $data['error'] = 'This location is outside of Barangay Dulong Bayan coverage area. Reports can only be submitted within the barangay boundaries.';
                    // Delete uploaded file since validation failed
                    unlink($targetPath);
                    return $this->view('resident/submit_report', $data);
                }

                $reportData = [
                    'resident_id' => $_SESSION['user_id'],
                    'photo_path' => $fileName,
                    'description' => $description,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'location_verified' => true
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

    public function profile() {
        $data = ['error' => '', 'success' => ''];
        $db = new Database();

        // Get user data
        $db->query("SELECT * FROM users WHERE id = :id");
        $db->bind(':id', $_SESSION['user_id']);
        $data['user'] = $db->single();

        // Handle profile update
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $phone = trim($_POST['phone_number'] ?? '');

            // Validation
            if (empty($name)) {
                $data['error'] = 'Full name is required.';
                return $this->view('resident/profile', $data);
            }

            if (empty($address)) {
                $data['error'] = 'Address is required.';
                return $this->view('resident/profile', $data);
            }

            // PH phone number validation (11 digits starting with 09)
            if (!preg_match('/^09\d{9}$/', $phone)) {
                $data['error'] = 'Invalid Philippine phone number. Must be 11 digits starting with 09.';
                return $this->view('resident/profile', $data);
            }

            // Update profile
            $db->query("UPDATE users SET name = :name, address = :address, phone_number = :phone WHERE id = :id");
            $db->bind(':name', $name);
            $db->bind(':address', $address);
            $db->bind(':phone', $phone);
            $db->bind(':id', $_SESSION['user_id']);
            
            if ($db->execute()) {
                // Update session name
                $_SESSION['user_name'] = $name;
                $data['success'] = 'Profile updated successfully.';
                
                // Refresh user data
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();

                $this->auditModel->logAction($_SESSION['user_id'], 'Profile Updated', 'Profile', 'Updated personal information', 'success');
            } else {
                $data['error'] = 'Failed to update profile.';
            }
        }

        $this->view('resident/profile', $data);
    }

    public function change_password() {
        $data = ['error' => '', 'success' => ''];
        $db = new Database();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Get current user
            $db->query("SELECT password FROM users WHERE id = :id");
            $db->bind(':id', $_SESSION['user_id']);
            $user = $db->single();

            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                $data['error'] = 'Current password is incorrect.';
                
                // Get user data for view
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                
                return $this->view('resident/profile', $data);
            }

            // Validate new password
            if (strlen($newPassword) < 8) {
                $data['error'] = 'Password must be at least 8 characters long.';
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            // Check for uppercase, number, and special character
            if (!preg_match('/[A-Z]/', $newPassword)) {
                $data['error'] = 'Password must contain at least one uppercase letter.';
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if (!preg_match('/[0-9]/', $newPassword)) {
                $data['error'] = 'Password must contain at least one number.';
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if (!preg_match('/[!@#$%^&*]/', $newPassword)) {
                $data['error'] = 'Password must contain at least one special character (!@#$%^&*).';
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            // Check password match
            if ($newPassword !== $confirmPassword) {
                $data['error'] = 'New passwords do not match.';
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            // Check if new password is same as current
            if (password_verify($newPassword, $user['password'])) {
                $data['error'] = 'New password must be different from current password.';
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $db->query("UPDATE users SET password = :password WHERE id = :id");
            $db->bind(':password', $hashedPassword);
            $db->bind(':id', $_SESSION['user_id']);

            if ($db->execute()) {
                $data['success'] = 'Password changed successfully.';
                $this->auditModel->logAction($_SESSION['user_id'], 'Password Changed', 'Security', 'User changed their password', 'success');
            } else {
                $data['error'] = 'Failed to change password.';
            }

            // Get user data for view
            $db->query("SELECT * FROM users WHERE id = :id");
            $db->bind(':id', $_SESSION['user_id']);
            $data['user'] = $db->single();
        }

        $this->view('resident/profile', $data);
    }
}
