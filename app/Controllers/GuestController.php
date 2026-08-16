<?php

class GuestController extends Controller {

    private $reportModel;
    private $db;

    public function __construct() {
        $this->reportModel = $this->model('Report');
        $this->db = new Database();
    }

    // ============================================================
    // HELPER: Get dropdown data for form
    // ============================================================
    private function getFormDropdowns() {
        $this->db->query('SELECT * FROM waste_categories ORDER BY category_name');
        $categories = $this->db->resultSet();

        $this->db->query('SELECT * FROM estimated_quantities ORDER BY quantity_id');
        $quantities = $this->db->resultSet();

        $this->db->query('SELECT * FROM waste_conditions ORDER BY condition_id');
        $conditions = $this->db->resultSet();

        $this->db->query('SELECT * FROM puroks ORDER BY purok_name');
        $puroks = $this->db->resultSet();

        return compact('categories', 'quantities', 'conditions', 'puroks');
    }

    // ============================================================
    // HELPER: SMS OTP Rate Limit Check
    // ============================================================
    private function canSendSmsOtp($phone, $ip) {
        // 60-second cooldown on last unused token
        $this->db->query('SELECT UNIX_TIMESTAMP(created_at) as ts FROM guest_otp_tokens
                          WHERE phone = :phone AND is_used = 0
                          ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':phone', $phone);
        $row = $this->db->single();
        if ($row) {
            $secondsLeft = max(0, 60 - (time() - (int)$row['ts']));
            if ($secondsLeft > 0) {
                return ['ok' => false, 'reason' => 'cooldown', 'retry_after' => $secondsLeft];
            }
        }

        // Hourly limit: max 3 per phone per hour
        $this->db->query('SELECT SUM(send_count) as cnt FROM guest_sms_rate_limits
                          WHERE phone = :phone AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':phone', $phone);
        $r = $this->db->single();
        if ($r && $r['cnt'] >= 3) {
            return ['ok' => false, 'reason' => 'hourly_limit'];
        }

        return ['ok' => true];
    }

    // ============================================================
    // HELPER: Record SMS rate
    // ============================================================
    private function recordSmsRate($phone, $ip) {
        $windowStart = date('Y-m-d H:00:00');
        $this->db->query('INSERT INTO guest_sms_rate_limits (phone, ip, window_start, send_count)
                          VALUES (:phone, :ip, :ws, 1)
                          ON DUPLICATE KEY UPDATE send_count = send_count + 1');
        $this->db->bind(':phone', $phone);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':ws', $windowStart);
        $this->db->execute();
    }

    // ============================================================
    // HELPER: Calculate location plausibility
    // ============================================================
    private function calcPlausibility($wasteLat, $wasteLng, $reporterLat, $reporterLng) {
        if (!$reporterLat || !$reporterLng) {
            return 'plausible'; // No reporter location provided, skip check
        }
        // Haversine distance in meters
        $earthRadius = 6371000;
        $dLat = deg2rad($reporterLat - $wasteLat);
        $dLng = deg2rad($reporterLng - $wasteLng);
        $a = sin($dLat/2)*sin($dLat/2) + cos(deg2rad($wasteLat))*cos(deg2rad($reporterLat))*sin($dLng/2)*sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        if ($distance <= 500)       return 'plausible';
        elseif ($distance <= 2000)  return 'requires_review';
        else                        return 'high_risk';
    }

    // ============================================================
    // STEP 1: Privacy Notice
    // ============================================================
    public function index() {
        $barangayModel = $this->model('Barangay');
        $barangay = $barangayModel->getInfo();
        $data = [
            'barangay' => $barangay,
            'error'    => '',
            'success'  => ''
        ];
        $this->view('guest/privacy', $data);
    }

    // ============================================================
    // STEP 2: Mobile Number & Name Entry
    // ============================================================
    public function phone() {
        $barangayModel = $this->model('Barangay');
        $barangay = $barangayModel->getInfo();
        $data = [
            'barangay'   => $barangay,
            'error'      => '',
            'success'    => '',
            'guest_name' => $_SESSION['guest_name'] ?? '',
            'phone'      => $_SESSION['guest_phone'] ?? '',
        ];
        $this->view('guest/phone', $data);
    }

    // ============================================================
    // STEP 3: Send OTP via SMS
    // ============================================================
    public function sendOtp() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest/phone');
            exit;
        }

        $phone = trim($_POST['phone'] ?? '');
        $name  = trim($_POST['guest_name'] ?? '');
        $ip    = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $barangayModel = $this->model('Barangay');
        $barangay = $barangayModel->getInfo();

        // Validate PH mobile format
        if (!preg_match('/^09\d{9}$/', $phone)) {
            $this->view('guest/phone', [
                'barangay'   => $barangay,
                'error'      => 'Invalid phone number. Please use the format 09XXXXXXXXX.',
                'guest_name' => $name,
                'phone'      => $phone
            ]);
            return;
        }

        // Check report submission limit (3 reports per hour)
        if (!$this->reportModel->canGuestSubmit($phone)) {
            $this->view('guest/phone', [
                'barangay'   => $barangay,
                'error'      => 'You have reached the submission limit (3 reports per hour). Please try again later.',
                'guest_name' => $name,
                'phone'      => $phone
            ]);
            return;
        }

        // Rate limit check
        $can = $this->canSendSmsOtp($phone, $ip);
        if (!$can['ok']) {
            if ($can['reason'] === 'cooldown') {
                $wait = $can['retry_after'];
                $this->view('guest/phone', [
                    'barangay'   => $barangay,
                    'error'      => "Please wait {$wait} seconds before requesting a new code.",
                    'guest_name' => $name,
                    'phone'      => $phone
                ]);
            } else {
                $this->view('guest/phone', [
                    'barangay'   => $barangay,
                    'error'      => 'Too many OTP requests. Please try again in an hour.',
                    'guest_name' => $name,
                    'phone'      => $phone
                ]);
            }
            return;
        }

        // Invalidate previous OTPs for this phone
        $this->db->query('UPDATE guest_otp_tokens SET is_used = 1 WHERE phone = :phone AND is_used = 0');
        $this->db->bind(':phone', $phone);
        $this->db->execute();

        // Generate & save OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->db->query('INSERT INTO guest_otp_tokens (phone, token, expires_at, ip)
                          VALUES (:phone, :token, DATE_ADD(NOW(), INTERVAL 5 MINUTE), :ip)');
        $this->db->bind(':phone', $phone);
        $this->db->bind(':token', $otp);
        $this->db->bind(':ip', $ip);
        $this->db->execute();

        // Send SMS OTP
        require_once '../app/Models/Helpers/SmsHelper.php';
        try {
            SmsHelper::sendOtp($phone, $otp, $name);
            $this->recordSmsRate($phone, $ip);
        } catch (Exception $e) {
            error_log('[GuestController] SMS send failed: ' . $e->getMessage());
        }

        // Store session for OTP verification step
        $_SESSION['guest_phone'] = $phone;
        $_SESSION['guest_name']  = $name;

        header('Location: /brgy-waste-app-v3/public/index.php?url=guest/verifyOtp');
        exit;
    }

    // ============================================================
    // STEP 4: OTP Verification Page
    // ============================================================
    public function verifyOtp() {
        if (!isset($_SESSION['guest_phone'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest/phone');
            exit;
        }

        $phone = $_SESSION['guest_phone'];

        $barangayModel = $this->model('Barangay');
        $barangay = $barangayModel->getInfo();

        // Query token metadata for exact countdown timers
        $this->db->query('SELECT UNIX_TIMESTAMP(created_at) as created_ts, UNIX_TIMESTAMP(expires_at) as expires_ts
                          FROM guest_otp_tokens
                          WHERE phone = :phone AND is_used = 0
                          ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':phone', $phone);
        $tokenMeta = $this->db->single();

        $now = time();
        $expiresIn = 300;
        $resendCooldown = 0;

        if ($tokenMeta) {
            $expiresIn = max(0, (int)$tokenMeta['expires_ts'] - $now);
            $resendCooldown = max(0, 60 - ($now - (int)$tokenMeta['created_ts']));
        }

        $data = [
            'barangay'                => $barangay,
            'error'                   => !empty($_GET['resend_error']) ? htmlspecialchars($_GET['resend_error'], ENT_QUOTES, 'UTF-8') : '',
            'success'                 => !empty($_GET['resent']) ? 'A new 6-digit verification code has been sent to your phone.' : '',
            'phone'                   => $phone,
            'expires_in_seconds'      => $expiresIn,
            'resend_cooldown_seconds' => $resendCooldown,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otp   = trim($_POST['otp'] ?? '');
            $phone = $_SESSION['guest_phone'];

            // Validate OTP
            $this->db->query('SELECT * FROM guest_otp_tokens
                              WHERE phone = :phone AND token = :token
                                AND is_used = 0 AND expires_at >= NOW()
                              ORDER BY created_at DESC LIMIT 1');
            $this->db->bind(':phone', $phone);
            $this->db->bind(':token', $otp);
            $tokenRow = $this->db->single();

            if ($tokenRow) {
                // Check report submission limit (3 reports per hour)
                if (!$this->reportModel->canGuestSubmit($phone)) {
                    $data['error'] = 'You have reached the submission limit (3 reports per hour). Please try again later.';
                    $this->view('guest/verify_otp', $data);
                    return;
                }

                // Mark token as used
                $this->db->query('UPDATE guest_otp_tokens SET is_used = 1 WHERE id = :id');
                $this->db->bind(':id', $tokenRow['id']);
                $this->db->execute();

                // Set session verified
                $_SESSION['guest_verified_phone'] = $phone;
                $_SESSION['guest_verified_at']    = time();

                header('Location: /brgy-waste-app-v3/public/index.php?url=guest/reportForm');
                exit;
            } else {
                // Increment attempts
                $this->db->query('SELECT id, attempts FROM guest_otp_tokens
                                  WHERE phone = :phone AND is_used = 0
                                  ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':phone', $phone);
                $r = $this->db->single();
                if ($r) {
                    $attempts = (int)$r['attempts'] + 1;
                    if ($attempts >= 5) {
                        $this->db->query('UPDATE guest_otp_tokens SET is_used = 1 WHERE id = :id');
                        $this->db->bind(':id', $r['id']);
                        $this->db->execute();
                        $data['error'] = 'Too many failed attempts. Please request a new code.';
                    } else {
                        $this->db->query('UPDATE guest_otp_tokens SET attempts = :a WHERE id = :id');
                        $this->db->bind(':a', $attempts);
                        $this->db->bind(':id', $r['id']);
                        $this->db->execute();
                        $remaining = 5 - $attempts;
                        $data['error'] = "Incorrect code. {$remaining} attempt(s) remaining.";
                    }
                } else {
                    $data['error'] = 'Invalid or expired code. Please request a new one.';
                }
            }
        }

        $this->view('guest/verify_otp', $data);
    }

    // ============================================================
    // STEP 4: Resend OTP
    // ============================================================
    public function resendOtp() {
        if (!isset($_SESSION['guest_phone'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest');
            exit;
        }
        $phone = $_SESSION['guest_phone'];
        $name  = $_SESSION['guest_name'] ?? '';
        $ip    = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $can = $this->canSendSmsOtp($phone, $ip);
        if (!$can['ok'] && $can['reason'] === 'cooldown') {
            $wait = $can['retry_after'];
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest/verifyOtp&resend_error=' . urlencode("Wait {$wait}s before resending."));
            exit;
        }

        // Invalidate previous
        $this->db->query('UPDATE guest_otp_tokens SET is_used = 1 WHERE phone = :phone AND is_used = 0');
        $this->db->bind(':phone', $phone);
        $this->db->execute();

        // New OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->db->query('INSERT INTO guest_otp_tokens (phone, token, expires_at, ip)
                          VALUES (:phone, :token, DATE_ADD(NOW(), INTERVAL 5 MINUTE), :ip)');
        $this->db->bind(':phone', $phone);
        $this->db->bind(':token', $otp);
        $this->db->bind(':ip', $ip);
        $this->db->execute();

        require_once '../app/Models/Helpers/SmsHelper.php';
        SmsHelper::sendOtp($phone, $otp, $name);
        $this->recordSmsRate($phone, $ip);

        header('Location: /brgy-waste-app-v3/public/index.php?url=guest/verifyOtp&resent=1');
        exit;
    }

    // ============================================================
    // STEP 5: Report Form (requires verified session)
    // ============================================================
    public function reportForm() {
        if (empty($_SESSION['guest_verified_phone'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest');
            exit;
        }

        $phone       = $_SESSION['guest_verified_phone'];
        $dropdowns   = $this->getFormDropdowns();
        $reportCount = $this->reportModel->getGuestReportCount($phone);
        $hourlyCount = $this->reportModel->getGuestHourlyReportCount($phone);

        $error = '';
        if (!$this->reportModel->canGuestSubmit($phone)) {
            $error = 'You have reached the submission limit (3 reports per hour). Please try again later.';
        }

        $data = array_merge($dropdowns, [
            'error'        => $error,
            'phone'        => $phone,
            'name'         => $_SESSION['guest_name'] ?? '',
            'report_count' => $reportCount,
            'hourly_count' => $hourlyCount,
        ]);

        // Dynamic Barangay Boundary & Center
        $barangayModel = $this->model('Barangay');
        $mapConfig = $barangayModel->getMapConfig();
        $data['barangay_boundary'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];

        $this->view('guest/report_form', $data);
    }

    // ============================================================
    // STEP 6: Review (POST from report form, save to session)
    // ============================================================
    public function review() {
        if (empty($_SESSION['guest_verified_phone'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest/reportForm');
            exit;
        }

        $phone = $_SESSION['guest_verified_phone'];

        if (!$this->reportModel->canGuestSubmit($phone)) {
            $dropdowns   = $this->getFormDropdowns();
            $reportCount = $this->reportModel->getGuestReportCount($phone);
            $hourlyCount = $this->reportModel->getGuestHourlyReportCount($phone);
            $data = array_merge($dropdowns, [
                'error'        => 'You have reached the submission limit (3 reports per hour). Please try again later.',
                'phone'        => $phone,
                'name'         => $_SESSION['guest_name'] ?? '',
                'report_count' => $reportCount,
                'hourly_count' => $hourlyCount,
            ]);
            $this->view('guest/report_form', $data);
            return;
        }

        $name  = $_SESSION['guest_name'] ?? '';

        // Handle photo upload
        $photos = [];
        if (!empty($_FILES['photos']['name'][0])) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/';
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                    $ext      = strtolower(pathinfo($_FILES['photos']['name'][$key], PATHINFO_EXTENSION));
                    $allowed  = ['jpg','jpeg','png','webp'];
                    if (!in_array($ext, $allowed)) continue;
                    $fileName = 'guest_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                        $photos[] = $fileName;
                    }
                }
            }
        }

        // Sanitize inputs
        $post = array_map(function($v) {
            return is_string($v) ? htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8') : $v;
        }, $_POST);

        $wasteLat      = (float)($post['latitude'] ?? 0);
        $wasteLng      = (float)($post['longitude'] ?? 0);
        $reporterLat   = !empty($post['reporter_latitude'])  ? (float)$post['reporter_latitude']  : null;
        $reporterLng   = !empty($post['reporter_longitude']) ? (float)$post['reporter_longitude'] : null;
        $plausibility  = $this->calcPlausibility($wasteLat, $wasteLng, $reporterLat, $reporterLng);

        // Store pending report in session for review step
        $_SESSION['guest_pending_report'] = [
            'guest_name'          => $name,
            'guest_phone'         => $phone,
            'description'         => $post['description'] ?? '',
            'latitude'            => $wasteLat,
            'longitude'           => $wasteLng,
            'reporter_latitude'   => $reporterLat,
            'reporter_longitude'  => $reporterLng,
            'location_plausibility' => $plausibility,
            'category_id'         => (int)($post['category_id'] ?? 0),
            'quantity_id'         => (int)($post['quantity_id'] ?? 0),
            'condition_id'        => (int)($post['condition_id'] ?? 0),
            'purok_id'            => !empty($post['purok_id']) ? (int)$post['purok_id'] : null,
            'location'            => $post['location'] ?? '',
            'photos'              => $photos,
        ];

        // Fetch category/quantity/condition names for review display
        $dropdowns = $this->getFormDropdowns();
        $catMap  = array_column($dropdowns['categories'], 'category_name', 'category_id');
        $qtyMap  = array_column($dropdowns['quantities'],  'quantity_name', 'quantity_id');
        $condMap = array_column($dropdowns['conditions'],  'condition_name', 'condition_id');
        $purokMap = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');

        $reportCount = $this->reportModel->getGuestReportCount($phone);
        $hourlyCount = $this->reportModel->getGuestHourlyReportCount($phone);

        $data = [
            'report'        => $_SESSION['guest_pending_report'],
            'category_name' => $catMap[$_SESSION['guest_pending_report']['category_id']] ?? 'Unknown',
            'quantity_name' => $qtyMap[$_SESSION['guest_pending_report']['quantity_id']]  ?? 'Unknown',
            'condition_name'=> $condMap[$_SESSION['guest_pending_report']['condition_id']] ?? 'Unknown',
            'purok_name'    => $purokMap[$_SESSION['guest_pending_report']['purok_id'] ?? 0] ?? 'N/A',
            'report_count'  => $reportCount,
            'hourly_count'  => $hourlyCount,
            'error'         => '',
        ];

        // Dynamic Barangay Boundary & Center
        $barangayModel = $this->model('Barangay');
        $mapConfig = $barangayModel->getMapConfig();
        $data['barangay_boundary'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];

        $this->view('guest/review', $data);
    }

    // ============================================================
    // STEP 7: Submit Report (from review confirmation)
    // ============================================================
    public function submitReport() {
        if (empty($_SESSION['guest_verified_phone']) || empty($_SESSION['guest_pending_report'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest/reportForm');
            exit;
        }

        $phone  = $_SESSION['guest_verified_phone'];
        $report = $_SESSION['guest_pending_report'];

        // Rate limit: max 3 reports per phone per hour
        if (!$this->reportModel->canGuestSubmit($phone)) {
            $dropdowns   = $this->getFormDropdowns();
            $catMap      = array_column($dropdowns['categories'], 'category_name', 'category_id');
            $qtyMap      = array_column($dropdowns['quantities'],  'quantity_name', 'quantity_id');
            $condMap     = array_column($dropdowns['conditions'],  'condition_name', 'condition_id');
            $purokMap    = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');
            $data = [
                'report'        => $report,
                'category_name' => $catMap[$report['category_id']] ?? 'Unknown',
                'quantity_name' => $qtyMap[$report['quantity_id']] ?? 'Unknown',
                'condition_name'=> $condMap[$report['condition_id']] ?? 'Unknown',
                'purok_name'    => $purokMap[$report['purok_id'] ?? 0] ?? 'N/A',
                'error'         => 'You have reached the submission limit (3 reports per hour). Please try again later.',
            ];
            $this->view('guest/review', $data);
            return;
        }

        // Duplicate check
        $isDuplicate = $this->reportModel->checkDuplicateReport(
            $report['latitude'],
            $report['longitude'],
            $report['category_id']
        ) ? 1 : 0;
        $report['is_duplicate'] = $isDuplicate;

        // Create guest report
        $result = $this->reportModel->createGuestReport($report);
        if (!$result) {
            $dropdowns   = $this->getFormDropdowns();
            $catMap      = array_column($dropdowns['categories'], 'category_name', 'category_id');
            $qtyMap      = array_column($dropdowns['quantities'],  'quantity_name', 'quantity_id');
            $condMap     = array_column($dropdowns['conditions'],  'condition_name', 'condition_id');
            $purokMap    = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');
            $data = [
                'report'        => $report,
                'category_name' => $catMap[$report['category_id']] ?? 'Unknown',
                'quantity_name' => $qtyMap[$report['quantity_id']] ?? 'Unknown',
                'condition_name'=> $condMap[$report['condition_id']] ?? 'Unknown',
                'purok_name'    => $purokMap[$report['purok_id'] ?? 0] ?? 'N/A',
                'error'         => 'Failed to submit report. Please try again.',
            ];
            $this->view('guest/review', $data);
            return;
        }

        $trackingNumber = $result['tracking_number'];

        // Send SMS confirmation
        require_once '../app/Models/Helpers/SmsHelper.php';
        try {
            SmsHelper::sendStatusUpdate($phone, $trackingNumber, 'pending', $report['guest_name']);
        } catch (Exception $e) {
            error_log('[GuestController] SMS confirmation failed: ' . $e->getMessage());
        }

        // Clear pending report session
        unset($_SESSION['guest_pending_report']);
        unset($_SESSION['guest_verified_phone']);
        unset($_SESSION['guest_verified_at']);
        unset($_SESSION['guest_phone']);
        unset($_SESSION['guest_name']);

        // Store tracking number for confirmation screen
        $_SESSION['guest_confirmed_tracking'] = $trackingNumber;
        $_SESSION['guest_confirmed_phone']    = $phone;

        header('Location: /brgy-waste-app-v3/public/index.php?url=guest/confirmation');
        exit;
    }

    // ============================================================
    // STEP 8: Confirmation Screen
    // ============================================================
    public function confirmation() {
        if (empty($_SESSION['guest_confirmed_tracking'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest');
            exit;
        }

        $data = [
            'tracking_number' => $_SESSION['guest_confirmed_tracking'],
            'phone'           => $_SESSION['guest_confirmed_phone'] ?? '',
        ];

        // Clear confirmation session after displaying
        unset($_SESSION['guest_confirmed_tracking']);
        unset($_SESSION['guest_confirmed_phone']);

        $this->view('guest/confirmation', $data);
    }

    // ============================================================
    // TRACK: Search Form
    // ============================================================
    public function track() {
        $data = ['error' => '', 'success' => ''];

        // Pre-fill from query string (e.g. redirect from confirmation)
        $data['tracking_number'] = $_GET['tn'] ?? '';

        $this->db->query("SELECT * FROM barangays LIMIT 1");
        $data['barangay'] = $this->db->single() ?: [];

        $this->view('guest/track', $data);
    }

    // ============================================================
    // TRACK: Status View (POST from track search form)
    // ============================================================
    public function trackStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=guest/track');
            exit;
        }

        $this->db->query("SELECT * FROM barangays LIMIT 1");
        $barangay = $this->db->single() ?: [];

        $trackingNumber = trim($_POST['tracking_number'] ?? '');
        $phone          = trim($_POST['phone'] ?? '');

        if (empty($trackingNumber) || empty($phone)) {
            $this->view('guest/track', [
                'error'           => 'Please enter your tracking number and phone number.',
                'success'         => '',
                'tracking_number' => $trackingNumber,
                'barangay'        => $barangay
            ]);
            return;
        }

        $report = $this->reportModel->getReportByTrackingAndPhone($trackingNumber, $phone);
        if (!$report) {
            $this->view('guest/track', [
                'error'           => 'No report found with that tracking number and phone number combination.',
                'success'         => '',
                'tracking_number' => $trackingNumber,
                'barangay'        => $barangay
            ]);
            return;
        }

        $barangayModel = $this->model('Barangay');
        $mapConfig = $barangayModel->getMapConfig();

        $data = [
            'report' => $report, 
            'error' => '', 
            'success' => '', 
            'barangay' => $barangay,
            'barangay_boundary' => $mapConfig['boundary_geojson'],
            'map_center' => $mapConfig['center']
        ];
        $this->view('guest/track_status', $data);
    }
}
