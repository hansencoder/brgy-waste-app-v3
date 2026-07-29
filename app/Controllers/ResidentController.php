<?php

class ResidentController extends Controller {
    private $reportModel;
    private $auditModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'resident') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth'));
            exit;
        }

        $this->reportModel = $this->model('Report');
        $this->auditModel = $this->model('AuditLog');
    }

    public function collection_schedule() {
    $db = new Database();

    // Fetch all active schedules with puroks
    $db->query("
        SELECT cs.*, 
        GROUP_CONCAT(p.purok_name SEPARATOR ', ') as puroks,
        MAX(cs.updated_at) as last_updated
        FROM collection_schedules cs
        LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
        LEFT JOIN puroks p ON csp.purok_id = p.purok_id
        WHERE cs.status = 'active'
        GROUP BY cs.schedule_id
        ORDER BY FIELD(cs.collection_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
    ");
    $data['schedules'] = $db->resultSet();

    // Fetch the latest special notice (announcement about schedule)
    $db->query("
        SELECT * FROM announcements 
        WHERE visibility_id = 1 
        AND (title LIKE '%schedule%' OR title LIKE '%collection%')
        ORDER BY created_at DESC LIMIT 1
    ");
    $data['special_notice'] = $db->single();

    // Get last updated date (use the most recent schedule update or fallback)
    $db->query("SELECT MAX(updated_at) as last_updated FROM collection_schedules WHERE status = 'active'");
    $row = $db->single();
    $data['last_updated'] = $row['last_updated'] ? date('F j, Y', strtotime($row['last_updated'])) : date('F j, Y');

    $this->view('resident/collection_schedule', $data);
}

    // ============================================================
    // DASHBOARD
    // ============================================================
    public function index() {
        $data['reports'] = $this->reportModel->getReportsByResident($_SESSION['user_id']);
        $data['stats'] = $this->reportModel->getDashboardStatsByResident($_SESSION['user_id']);
        $data['map_pins'] = $this->reportModel->getHeatmapDataByResident($_SESSION['user_id']);
        $this->view('resident/dashboard', $data);
    }

    // ============================================================
    // SUBMIT REPORT
    // ============================================================
    public function submit() {
        $data = ['error' => '', 'success' => ''];

        // Load dropdown data from database
        $categoryModel = $this->model('WasteCategory');
        $quantityModel = $this->model('EstimatedQuantity');
        $conditionModel = $this->model('WasteCondition');
        $statusModel = $this->model('ReportStatus');

        $data['categories'] = $categoryModel->getAll();
        $data['quantities'] = $quantityModel->getAll();
        $data['conditions'] = $conditionModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $description = trim($_POST['description']);
            $lat = $_POST['latitude'];
            $lng = $_POST['longitude'];
            $category_id = (int) ($_POST['category_id'] ?? 0);
            $quantity_id = (int) ($_POST['quantity_id'] ?? 0);
            $condition_id = (int) ($_POST['condition_id'] ?? 0);
            $remarks = trim($_POST['remarks'] ?? '');

            // Validate description
            if (strlen($description) < 10 || strlen($description) > 500) {
                $data['error'] = 'Description must be between 10 and 500 characters.';
                return $this->view('resident/submit_report', $data);
            }

            // Validate required fields
            if (empty($category_id) || empty($quantity_id) || empty($condition_id)) {
                $data['error'] = 'Please select a waste category, quantity, and condition.';
                return $this->view('resident/submit_report', $data);
            }

            // Validate photo
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

            // Upload photo
            $uploadDir = '../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {

                // Boundary Check - Barangay Dulong Bayan
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
                    unlink($targetPath);
                    return $this->view('resident/submit_report', $data);
                }

                // Get Pending status ID
                $pendingStatus = $statusModel->getByName('Pending');
                $status_id = $pendingStatus ? $pendingStatus['status_id'] : 1;

                // Detect purok (optional - can be improved later)
                $purok_id = $this->detectPurok($lat, $lng);

                $reportData = [
                    'resident_id' => $_SESSION['user_id'],
                    'description' => $description,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'location_verified' => true,
                    'category_id' => $category_id,
                    'quantity_id' => $quantity_id,
                    'condition_id' => $condition_id,
                    'status_id' => $status_id,
                    'purok_id' => $purok_id,
                    'location' => '', // Can be filled with reverse geocoding if needed
                    'photos' => [$fileName]
                ];

                $reportId = $this->reportModel->createReport($reportData);

                if ($reportId) {
                    $this->auditModel->logAction($_SESSION['user_id'], 'Report Submitted', 'Waste Report', "User submitted report ID $reportId", 'success');

                    // Notify admins about new report submission
                    require_once __DIR__ . '/../Models/Notification.php';
                    $notificationModel = new Notification();
                    $notificationModel->createReportSubmittedNotification($reportId);

                    $data['success'] = 'Report submitted successfully.';
                } else {
                    $data['error'] = 'Database error while saving report.';
                    // Delete uploaded file since database insert failed
                    unlink($targetPath);
                }
            } else {
                $data['error'] = 'Failed to upload photo.';
            }
        }

        $this->view('resident/submit_report', $data);
    }

    // ============================================================
    // MY REPORTS LIST
    // ============================================================
    public function my_report() {
        $data['reports'] = $this->reportModel->getReportsByResident($_SESSION['user_id']);
        $this->view('resident/my_report', $data);
    }

    // ============================================================
    // VIEW SINGLE REPORT
    // ============================================================
    public function view_report($id) {
        $data['report'] = $this->reportModel->getReportById($id, $_SESSION['user_id']);

        if (!$data['report']) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('resident/my_report'));
            exit;
        }

        // Get location name from coordinates
        require_once '../app/Core/Geocoding.php';
        $data['report']['location_name'] = Geocoding::getLocationName(
            $data['report']['latitude'],
            $data['report']['longitude']
        );

        $data['timeline'] = $this->reportModel->getReportTimeline($id);

        // Get flag reason if report is rejected
        if ($data['report']['status'] === 'rejected') {
            $db = new Database();
            $db->query("SELECT flag_reason, flagged_at FROM report_flags WHERE report_id = :id LIMIT 1");
            $db->bind(':id', $id);
            $flag = $db->single();
            $data['flag_reason'] = $flag ? $flag['flag_reason'] : 'No reason provided';
            $data['flag_date'] = $flag ? $flag['flagged_at'] : null;
        }

        $this->view('resident/view_report', $data);
    }

    // ============================================================
    // DELETE REPORT
    // ============================================================
    public function delete_report($id) {
        $report = $this->reportModel->getReportById($id, $_SESSION['user_id']);

        if (!$report) {
            $_SESSION['error'] = 'Report not found or you do not have permission to delete it.';
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('resident/my_report'));
            exit;
        }

        // Only allow deletion of pending reports (check the status field from the joined query)
        if (strtolower($report['status']) !== 'pending') {
            $_SESSION['error'] = 'Only pending reports can be deleted.';
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('resident/view_report/' . $id));
            exit;
        }

        if ($this->reportModel->deleteReport($id, $_SESSION['user_id'])) {
            $this->auditModel->logAction($_SESSION['user_id'], 'Report Deleted', "Report ID $id", 'Resident deleted their pending report', 'success');
            $_SESSION['success'] = 'Report deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete report.';
        }

        header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('resident/my_report'));
        exit;
    }

    // ============================================================
    // ANNOUNCEMENTS
    // ============================================================

    public function notification() {
    $db = new Database();
    $user_id = $_SESSION['user_id'];

    // Fetch notifications for this user
    $db->query("
        SELECT n.*, 
                nt.notification_type_name as type
        FROM notifications n
        LEFT JOIN notification_types nt ON n.type = nt.notification_type_name
        WHERE n.user_id = :user_id OR n.send_to_all = 1
        ORDER BY n.created_at DESC
    ");
    $db->bind(':user_id', $user_id);
    $data['notifications'] = $db->resultSet();

    // Get unread count
    require_once __DIR__ . '/../Models/Notification.php';
    $notificationModel = new Notification();
    $data['unread_count'] = $notificationModel->getUnreadCount($user_id);

    $this->view('resident/notification', $data);
}





    public function announcements() {
    $db = new Database();
    
    // Get announcements visible to residents (Public or Registered)
    $db->query("
        SELECT a.*, u.name as author
        FROM announcements a
        LEFT JOIN users u ON a.created_by = u.id
        WHERE a.visibility_id IN (1, 2)  -- Public or Registered
        ORDER BY a.created_at DESC
    ");
    $data['announcements'] = $db->resultSet();
    
    // Get unread notifications count (optional)
    require_once __DIR__ . '/../Models/Notification.php';
    $notificationModel = new Notification();
    $data['unread_count'] = $notificationModel->getUnreadCount($_SESSION['user_id']);
    
    $this->view('resident/announcements', $data);
}

    // ============================================================
    // PROFILE
    // ============================================================
    public function profile() {
        $data = ['error' => '', 'success' => ''];
        $db = new Database();

        // Fetch user with role, position, purok names
        $db->query("
            SELECT u.*, 
                    r.role_name, 
                    p.position_name, 
                    pk.purok_name
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
                return $this->view('resident/profile', $data);
            }

            if (empty($address)) {
                $data['error'] = 'Address is required.';
                return $this->view('resident/profile', $data);
            }

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

                $this->auditModel->logAction($_SESSION['user_id'], 'Profile Updated', 'Profile', 'Updated personal information', 'success');
            } else {
                $data['error'] = 'Failed to update profile.';
            }
        }

        $this->view('resident/profile', $data);
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
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                            LEFT JOIN roles r ON u.role_id = r.role_id
                            LEFT JOIN positions p ON u.position_id = p.position_id
                            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                            WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if (strlen($newPassword) < 8) {
                $data['error'] = 'Password must be at least 8 characters long.';
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                            LEFT JOIN roles r ON u.role_id = r.role_id
                            LEFT JOIN positions p ON u.position_id = p.position_id
                            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                            WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if (!preg_match('/[A-Z]/', $newPassword)) {
                $data['error'] = 'Password must contain at least one uppercase letter.';
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                            LEFT JOIN roles r ON u.role_id = r.role_id
                            LEFT JOIN positions p ON u.position_id = p.position_id
                            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                            WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if (!preg_match('/[0-9]/', $newPassword)) {
                $data['error'] = 'Password must contain at least one number.';
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                            LEFT JOIN roles r ON u.role_id = r.role_id
                            LEFT JOIN positions p ON u.position_id = p.position_id
                            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                            WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if (!preg_match('/[!@#$%^&*]/', $newPassword)) {
                $data['error'] = 'Password must contain at least one special character (!@#$%^&*).';
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                            LEFT JOIN roles r ON u.role_id = r.role_id
                            LEFT JOIN positions p ON u.position_id = p.position_id
                            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                            WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if ($newPassword !== $confirmPassword) {
                $data['error'] = 'New passwords do not match.';
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                            LEFT JOIN roles r ON u.role_id = r.role_id
                            LEFT JOIN positions p ON u.position_id = p.position_id
                            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                            WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

            if (password_verify($newPassword, $user['password'])) {
                $data['error'] = 'New password must be different from current password.';
                $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                            LEFT JOIN roles r ON u.role_id = r.role_id
                            LEFT JOIN positions p ON u.position_id = p.position_id
                            LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                            WHERE u.id = :id");
                $db->bind(':id', $_SESSION['user_id']);
                $data['user'] = $db->single();
                return $this->view('resident/profile', $data);
            }

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

            $db->query("SELECT u.*, r.role_name, p.position_name, pk.purok_name FROM users u
                        LEFT JOIN roles r ON u.role_id = r.role_id
                        LEFT JOIN positions p ON u.position_id = p.position_id
                        LEFT JOIN puroks pk ON u.purok_id = pk.purok_id
                        WHERE u.id = :id");
            $db->bind(':id', $_SESSION['user_id']);
            $data['user'] = $db->single();
        }

        $this->view('resident/profile', $data);
    }

    // ============================================================
    // HELPER: Detect Purok from coordinates
    // ============================================================
    private function detectPurok($lat, $lng) {
        // For now, return default purok (Purok 1)
        // In the future, you can implement point-in-polygon for each purok boundary
        return 1;
    }
}