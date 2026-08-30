<?php

class GuestController extends Controller {

    private $reportModel;
    private $db;

    public function __construct() {
        // Block guest flows during maintenance mode
        // (init.php covers page routes; this covers any direct instantiation)
        require_once dirname(__DIR__) . '/Models/SystemMaintenance.php';
        $_guestMaintenance = new SystemMaintenance();
        if ($_guestMaintenance->isMaintenanceActive()) {
            header('Location: ' . app_url('index.php?url=maintenance'));
            exit;
        }

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
    // HELPER: OTP Rate Limit & Cooldown Check
    // ============================================================
    private function canSendOtp($contact, $ip) {
        // 60-second cooldown on last unused token using database timestamp
        $this->db->query('SELECT TIMESTAMPDIFF(SECOND, created_at, NOW()) as elapsed 
                          FROM guest_otp_tokens
                          WHERE phone = :contact AND is_used = 0
                          ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':contact', $contact);
        $row = $this->db->single();
        if ($row && isset($row['elapsed'])) {
            $elapsed = (int)$row['elapsed'];
            if ($elapsed >= 0 && $elapsed < 60) {
                return ['ok' => false, 'reason' => 'cooldown', 'retry_after' => (60 - $elapsed)];
            }
        }

        // Hourly limit: max 10 per contact per hour
        $this->db->query('SELECT SUM(send_count) as cnt FROM guest_sms_rate_limits
                          WHERE phone = :contact AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':contact', $contact);
        $r = $this->db->single();
        if ($r && (int)$r['cnt'] >= 10) {
            return ['ok' => false, 'reason' => 'hourly_limit'];
        }

        // IP hourly limit: max 30 per IP per hour
        $this->db->query('SELECT SUM(send_count) as cnt FROM guest_sms_rate_limits
                          WHERE ip = :ip AND window_start >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $this->db->bind(':ip', $ip);
        $r2 = $this->db->single();
        if ($r2 && (int)$r2['cnt'] >= 30) {
            return ['ok' => false, 'reason' => 'hourly_limit'];
        }

        return ['ok' => true];
    }

    // ============================================================
    // HELPER: Record OTP rate
    // ============================================================
    private function recordOtpRate($contact, $ip) {
        $windowStart = date('Y-m-d H:00:00');
        $this->db->query('INSERT INTO guest_sms_rate_limits (phone, ip, window_start, send_count)
                          VALUES (:contact, :ip, :ws, 1)
                          ON DUPLICATE KEY UPDATE send_count = send_count + 1');
        $this->db->bind(':contact', $contact);
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
    // STEP 2: Contact (Phone / Email) & Name Entry
    // ============================================================
    public function phone() {
        $barangayModel = $this->model('Barangay');
        $barangay = $barangayModel->getInfo();
        $data = [
            'barangay'                 => $barangay,
            'error'                    => '',
            'success'                  => '',
            'guest_name'               => $_SESSION['guest_name'] ?? '',
            'contact'                  => $_SESSION['guest_contact'] ?? ($_SESSION['guest_phone'] ?? ''),
            'channel'                  => $_SESSION['guest_channel'] ?? 'phone',
            'is_registered_resident'   => false,
        ];
        $this->view('guest/phone', $data);
    }

    // ============================================================
    // STEP 3: Send OTP via SMS or Email
    // ============================================================
    public function sendOtp() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . app_url('index.php?url=guest/phone'));
            exit;
        }

        $channel = trim($_POST['channel'] ?? 'phone'); // 'phone' or 'email'
        if ($channel === 'email') {
            $contact = trim($_POST['email'] ?? ($_POST['contact'] ?? ''));
        } else {
            $contact = trim($_POST['phone'] ?? ($_POST['contact'] ?? ''));
        }
        $name    = trim($_POST['guest_name'] ?? '');
        $ip      = get_client_ip();

        $barangayModel = $this->model('Barangay');
        $barangay = $barangayModel->getInfo();

        if ($channel === 'email' || filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $channel = 'email';
            $contact = strtolower(trim($contact));
            if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                $this->view('guest/phone', [
                    'barangay'                 => $barangay,
                    'error'                    => 'Please enter a valid email address.',
                    'field_error'              => 'email',
                    'field_error_message'      => 'Invalid email address format.',
                    'guest_name'               => $name,
                    'contact'                  => $contact,
                    'channel'                  => 'email',
                    'is_registered_resident'   => false
                ]);
                return;
            }

            // Check if email is already in use by a resident account
            $userModel = $this->model('User');
            $existingUser = $userModel->findUserByEmail($contact);

            if ($existingUser) {
                $this->view('guest/phone', [
                    'barangay'                 => $barangay,
                    'error'                    => 'This email is already in use by a registered resident. Please sign in to your resident account to submit reports.',
                    'field_error'              => 'email',
                    'field_error_message'      => 'This email is already in use.',
                    'guest_name'               => $name,
                    'contact'                  => $contact,
                    'channel'                  => 'email',
                    'is_registered_resident'   => true
                ]);
                return;
            }
        } else {
            $channel = 'phone';
            // Validate PH mobile format (09XXXXXXXXX)
            if (!preg_match('/^09\d{9}$/', $contact)) {
                $this->view('guest/phone', [
                    'barangay'                 => $barangay,
                    'error'                    => 'Invalid mobile number. Please use the format 09XXXXXXXXX.',
                    'field_error'              => 'phone',
                    'field_error_message'      => 'Invalid mobile number format (e.g. 09XXXXXXXXX).',
                    'guest_name'               => $name,
                    'contact'                  => $contact,
                    'channel'                  => 'phone',
                    'is_registered_resident'   => false
                ]);
                return;
            }

            // Check if mobile number is already in use by a resident account
            $userModel = $this->model('User');
            $existingUser = $userModel->findUserByPhone($contact);

            if ($existingUser) {
                $this->view('guest/phone', [
                    'barangay'                 => $barangay,
                    'error'                    => 'This mobile number is already in use by a registered resident. Please sign in to your resident account to submit reports.',
                    'field_error'              => 'phone',
                    'field_error_message'      => 'This mobile number is already in use.',
                    'guest_name'               => $name,
                    'contact'                  => $contact,
                    'channel'                  => 'phone',
                    'is_registered_resident'   => true
                ]);
                return;
            }
        }

        // Check report submission limit (3 reports per hour per contact)
        if (!$this->reportModel->canGuestSubmit($contact)) {
            $this->view('guest/phone', [
                'barangay'                 => $barangay,
                'error'                    => 'You have reached the submission limit (3 reports per hour). Please try again later.',
                'guest_name'               => $name,
                'contact'                  => $contact,
                'channel'                  => $channel,
                'is_registered_resident'   => false
            ]);
            return;
        }

        // Rate limit check
        $can = $this->canSendOtp($contact, $ip);
        if (!$can['ok']) {
            if ($can['reason'] === 'cooldown') {
                // If there is already an active, unexpired, unused token for this contact, forward directly to OTP verification
                $this->db->query('SELECT token FROM guest_otp_tokens 
                                  WHERE phone = :contact AND is_used = 0 AND expires_at > NOW() 
                                  ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':contact', $contact);
                $activeToken = $this->db->single();

                if ($activeToken) {
                    $_SESSION['guest_contact'] = $contact;
                    $_SESSION['guest_channel'] = $channel;
                    $_SESSION['guest_phone']   = $contact;
                    $_SESSION['guest_name']    = $name;
                    header('Location: ' . app_url('index.php?url=guest/verifyOtp'));
                    exit;
                }

                $wait = $can['retry_after'];
                $this->view('guest/phone', [
                    'barangay'                 => $barangay,
                    'error'                    => "Please wait {$wait} seconds before requesting a new code.",
                    'guest_name'               => $name,
                    'contact'                  => $contact,
                    'channel'                  => $channel,
                    'is_registered_resident'   => false
                ]);
            } else {
                $this->view('guest/phone', [
                    'barangay'                 => $barangay,
                    'error'                    => 'Too many OTP requests. Please try again in an hour.',
                    'guest_name'               => $name,
                    'contact'                  => $contact,
                    'channel'                  => $channel,
                    'is_registered_resident'   => false
                ]);
            }
            return;
        }

        // Invalidate previous OTPs for this contact
        $this->db->query('UPDATE guest_otp_tokens SET is_used = 1 WHERE phone = :contact AND is_used = 0');
        $this->db->bind(':contact', $contact);
        $this->db->execute();

        // Generate & save OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->db->query('INSERT INTO guest_otp_tokens (phone, token, expires_at, ip)
                          VALUES (:contact, :token, DATE_ADD(NOW(), INTERVAL 5 MINUTE), :ip)');
        $this->db->bind(':contact', $contact);
        $this->db->bind(':token', $otp);
        $this->db->bind(':ip', $ip);
        $this->db->execute();

        // Send OTP via chosen channel
        if ($channel === 'email') {
            require_once dirname(__DIR__) . '/Models/Helpers/OtpMailer.php';
            try {
                OtpMailer::sendOtpEmail($contact, $otp, $name ?: 'Guest Citizen');
                $this->recordOtpRate($contact, $ip);
            } catch (Exception $e) {
                error_log('[GuestController] Email OTP send failed: ' . $e->getMessage());
            }
        } else {
            require_once dirname(__DIR__) . '/Models/Helpers/SmsHelper.php';
            try {
                SmsHelper::sendOtp($contact, $otp, $name);
                $this->recordOtpRate($contact, $ip);
            } catch (Exception $e) {
                error_log('[GuestController] SMS send failed: ' . $e->getMessage());
            }
        }

        // Store session for OTP verification step
        $_SESSION['guest_contact'] = $contact;
        $_SESSION['guest_channel'] = $channel;
        $_SESSION['guest_phone']   = $contact; // backward compatibility
        $_SESSION['guest_name']    = $name;

        header('Location: ' . app_url('index.php?url=guest/verifyOtp'));
        exit;
    }

    // ============================================================
    // STEP 4: OTP Verification Page
    // ============================================================
    public function verifyOtp() {
        $contact = $_SESSION['guest_contact'] ?? ($_SESSION['guest_phone'] ?? null);
        $channel = $_SESSION['guest_channel'] ?? 'phone';

        if (!$contact) {
            header('Location: ' . app_url('index.php?url=guest/phone'));
            exit;
        }

        $barangayModel = $this->model('Barangay');
        $barangay = $barangayModel->getInfo();

        // Query token metadata for exact countdown timers
        $this->db->query('SELECT UNIX_TIMESTAMP(created_at) as created_ts, UNIX_TIMESTAMP(expires_at) as expires_ts
                          FROM guest_otp_tokens
                          WHERE phone = :contact AND is_used = 0
                          ORDER BY created_at DESC LIMIT 1');
        $this->db->bind(':contact', $contact);
        $tokenMeta = $this->db->single();

        $now = time();
        $expiresIn = 300;
        $resendCooldown = 0;

        if ($tokenMeta) {
            $expiresIn = max(0, (int)$tokenMeta['expires_ts'] - $now);
            $resendCooldown = max(0, 60 - ($now - (int)$tokenMeta['created_ts']));
        }

        $destinationLabel = ($channel === 'email') ? 'email address' : 'mobile number';
        $data = [
            'barangay'                => $barangay,
            'error'                   => !empty($_GET['resend_error']) ? htmlspecialchars($_GET['resend_error'], ENT_QUOTES, 'UTF-8') : '',
            'success'                 => !empty($_GET['resent']) ? "A new 6-digit verification code has been sent to your {$destinationLabel}." : '',
            'contact'                 => $contact,
            'phone'                   => $contact,
            'channel'                 => $channel,
            'expires_in_seconds'      => $expiresIn,
            'resend_cooldown_seconds' => $resendCooldown,
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otp = trim($_POST['otp'] ?? '');

            // Validate OTP
            $this->db->query('SELECT * FROM guest_otp_tokens
                              WHERE phone = :contact AND token = :token
                                AND is_used = 0 AND expires_at >= NOW()
                              ORDER BY created_at DESC LIMIT 1');
            $this->db->bind(':contact', $contact);
            $this->db->bind(':token', $otp);
            $tokenRow = $this->db->single();

            if ($tokenRow) {
                // Check report submission limit
                if (!$this->reportModel->canGuestSubmit($contact)) {
                    $data['error'] = 'You have reached the submission limit (3 reports per hour). Please try again later.';
                    $this->view('guest/verify_otp', $data);
                    return;
                }

                // Mark token as used
                $this->db->query('UPDATE guest_otp_tokens SET is_used = 1 WHERE id = :id');
                $this->db->bind(':id', $tokenRow['id']);
                $this->db->execute();

                // Set session verified
                $_SESSION['guest_verified_contact'] = $contact;
                $_SESSION['guest_verified_phone']   = $contact;
                $_SESSION['guest_verified_at']      = time();

                header('Location: ' . app_url('index.php?url=guest/reportForm'));
                exit;
            } else {
                // Increment attempts
                $this->db->query('SELECT id, attempts FROM guest_otp_tokens
                                  WHERE phone = :contact AND is_used = 0
                                  ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':contact', $contact);
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
        $contact = $_SESSION['guest_contact'] ?? ($_SESSION['guest_phone'] ?? null);
        $channel = $_SESSION['guest_channel'] ?? 'phone';
        $name    = $_SESSION['guest_name'] ?? '';
        $ip      = get_client_ip();

        if (!$contact) {
            header('Location: ' . app_url('index.php?url=guest'));
            exit;
        }

        $can = $this->canSendOtp($contact, $ip);
        if (!$can['ok'] && $can['reason'] === 'cooldown') {
            $wait = $can['retry_after'];
            header('Location: ' . app_url('index.php?url=guest/verifyOtp&resend_error=' . urlencode("Wait {$wait}s before resending.")));
            exit;
        }

        // Invalidate previous
        $this->db->query('UPDATE guest_otp_tokens SET is_used = 1 WHERE phone = :contact AND is_used = 0');
        $this->db->bind(':contact', $contact);
        $this->db->execute();

        // New OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->db->query('INSERT INTO guest_otp_tokens (phone, token, expires_at, ip)
                          VALUES (:contact, :token, DATE_ADD(NOW(), INTERVAL 5 MINUTE), :ip)');
        $this->db->bind(':contact', $contact);
        $this->db->bind(':token', $otp);
        $this->db->bind(':ip', $ip);
        $this->db->execute();

        if ($channel === 'email') {
            require_once dirname(__DIR__) . '/Models/Helpers/OtpMailer.php';
            OtpMailer::sendOtpEmail($contact, $otp, $name ?: 'Guest Citizen');
            $this->recordOtpRate($contact, $ip);
        } else {
            require_once dirname(__DIR__) . '/Models/Helpers/SmsHelper.php';
            SmsHelper::sendOtp($contact, $otp, $name);
            $this->recordOtpRate($contact, $ip);
        }

        header('Location: ' . app_url('index.php?url=guest/verifyOtp&resent=1'));
        exit;
    }

    // ============================================================
    // STEP 5: Report Form (requires verified session)
    // ============================================================
    public function reportForm() {
        if (empty($_SESSION['guest_verified_phone'])) {
            header('Location: ' . app_url('index.php?url=guest'));
            exit;
        }

        $contact     = $_SESSION['guest_verified_phone'];
        $dropdowns   = $this->getFormDropdowns();
        $reportCount = $this->reportModel->getGuestReportCount($contact);
        $hourlyCount = $this->reportModel->getGuestHourlyReportCount($contact);

        $error = '';
        if (!$this->reportModel->canGuestSubmit($contact)) {
            $error = 'You have reached the submission limit (3 reports per hour). Please try again later.';
        }

        $data = array_merge($dropdowns, [
            'error'        => $error,
            'phone'        => $contact,
            'contact'      => $contact,
            'channel'      => $_SESSION['guest_channel'] ?? 'phone',
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
            header('Location: ' . app_url('index.php?url=guest'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . app_url('index.php?url=guest/reportForm'));
            exit;
        }

        $contact = $_SESSION['guest_verified_phone'];

        if (!$this->reportModel->canGuestSubmit($contact)) {
            $dropdowns   = $this->getFormDropdowns();
            $reportCount = $this->reportModel->getGuestReportCount($contact);
            $hourlyCount = $this->reportModel->getGuestHourlyReportCount($contact);
            $data = array_merge($dropdowns, [
                'error'        => 'You have reached the submission limit (3 reports per hour). Please try again later.',
                'phone'        => $contact,
                'contact'      => $contact,
                'name'         => $_SESSION['guest_name'] ?? '',
                'report_count' => $reportCount,
                'hourly_count' => $hourlyCount,
            ]);
            $this->view('guest/report_form', $data);
            return;
        }

        $name = $_SESSION['guest_name'] ?? '';

        // Sanitize inputs
        $post = array_map(function($v) {
            return is_string($v) ? htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8') : $v;
        }, $_POST);

        $wasteLat = (float)($post['latitude'] ?? 0);
        $wasteLng = (float)($post['longitude'] ?? 0);

        // Strict boundary check
        $barangayModel = $this->model('Barangay');
        $brgyInfo = $barangayModel->getInfo();
        $brgyName = $brgyInfo['barangay_name'] ?? 'Dulong Bayan';

        if (!$barangayModel->isPointInsideBoundary($wasteLat, $wasteLng)) {
            $dropdowns = $this->getFormDropdowns();
            $reportCount = $this->reportModel->getGuestReportCount($contact);
            $hourlyCount = $this->reportModel->getGuestHourlyReportCount($contact);
            $mapConfig = $barangayModel->getMapConfig();
            $data = array_merge($dropdowns, [
                'error'             => "The selected location is outside Barangay {$brgyName} boundary. Only locations inside the barangay can be reported.",
                'phone'             => $contact,
                'contact'           => $contact,
                'name'              => $name,
                'report_count'      => $reportCount,
                'hourly_count'      => $hourlyCount,
                'barangay_boundary' => $mapConfig['boundary_geojson'],
                'map_center'        => $mapConfig['center'],
            ]);
            $this->view('guest/report_form', $data);
            return;
        }

        // Handle photo upload (multi-photo support)
        $photos = [];
        if (!empty($_FILES['photos']['name'][0])) {
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['photos']['name'][$key], PATHINFO_EXTENSION));
                    $allowed = ['jpg','jpeg','png','webp'];
                    if (!in_array($ext, $allowed)) continue;
                    $fileName = 'guest_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                        $photos[] = $fileName;
                    }
                }
            }
        }

        $reporterLat   = !empty($post['reporter_latitude'])  ? (float)$post['reporter_latitude']  : null;
        $reporterLng   = !empty($post['reporter_longitude']) ? (float)$post['reporter_longitude'] : null;
        $plausibility  = $this->calcPlausibility($wasteLat, $wasteLng, $reporterLat, $reporterLng);

        $guestEmail = trim($post['guest_email'] ?? '');
        if (empty($guestEmail) && filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $guestEmail = $contact;
        }

        // Auto-detect Purok from coordinates
        $detectedPurokId = $barangayModel->detectPurok($wasteLat, $wasteLng);
        $purokId = !empty($post['purok_id']) ? (int)$post['purok_id'] : $detectedPurokId;
        if (!$purokId) {
            $purokId = $detectedPurokId ?: 1;
        }

        // Store pending report in session for review step
        $_SESSION['guest_pending_report'] = [
            'guest_name'          => $name,
            'guest_phone'         => $contact,
            'guest_email'         => $guestEmail,
            'description'         => $post['description'] ?? '',
            'latitude'            => $wasteLat,
            'longitude'           => $wasteLng,
            'reporter_latitude'   => $reporterLat,
            'reporter_longitude'  => $reporterLng,
            'location_plausibility' => $plausibility,
            'category_id'         => (int)($post['category_id'] ?? 0),
            'quantity_id'         => (int)($post['quantity_id'] ?? 0),
            'condition_id'        => (int)($post['condition_id'] ?? 0),
            'purok_id'            => $purokId,
            'location'            => $post['location'] ?? '',
            'photos'              => $photos,
        ];

        // Fetch category/quantity/condition names for review display
        $dropdowns = $this->getFormDropdowns();
        $catMap   = array_column($dropdowns['categories'], 'category_name', 'category_id');
        $qtyMap   = array_column($dropdowns['quantities'],  'quantity_name', 'quantity_id');
        $condMap  = array_column($dropdowns['conditions'],  'condition_name', 'condition_id');
        $purokMap = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');

        $reportCount = $this->reportModel->getGuestReportCount($contact);
        $hourlyCount = $this->reportModel->getGuestHourlyReportCount($contact);

        $mapConfig = $barangayModel->getMapConfig();
        $data = [
            'report'            => $_SESSION['guest_pending_report'],
            'category_name'     => $catMap[$_SESSION['guest_pending_report']['category_id']] ?? 'Unknown',
            'quantity_name'     => $qtyMap[$_SESSION['guest_pending_report']['quantity_id']]  ?? 'Unknown',
            'condition_name'    => $condMap[$_SESSION['guest_pending_report']['condition_id']] ?? 'Unknown',
            'purok_name'        => $purokMap[$_SESSION['guest_pending_report']['purok_id'] ?? 0] ?? 'N/A',
            'report_count'      => $reportCount,
            'hourly_count'      => $hourlyCount,
            'error'             => '',
            'barangay_boundary' => $mapConfig['boundary_geojson'],
            'map_center'        => $mapConfig['center'],
        ];

        $this->view('guest/review', $data);
    }

    // ============================================================
    // STEP 7: Submit Report (from review confirmation)
    // ============================================================
    public function submitReport() {
        if (empty($_SESSION['guest_verified_phone']) || empty($_SESSION['guest_pending_report'])) {
            header('Location: ' . app_url('index.php?url=guest'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . app_url('index.php?url=guest/reportForm'));
            exit;
        }

        $contact = $_SESSION['guest_verified_phone'];
        $report  = $_SESSION['guest_pending_report'];

        // Strict boundary validation
        $barangayModel = $this->model('Barangay');
        if (!$barangayModel->isPointInsideBoundary($report['latitude'], $report['longitude'])) {
            $dropdowns = $this->getFormDropdowns();
            $catMap    = array_column($dropdowns['categories'], 'category_name', 'category_id');
            $qtyMap    = array_column($dropdowns['quantities'],  'quantity_name', 'quantity_id');
            $condMap   = array_column($dropdowns['conditions'],  'condition_name', 'condition_id');
            $purokMap  = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');
            $mapConfig = $barangayModel->getMapConfig();
            $data = [
                'report'            => $report,
                'category_name'     => $catMap[$report['category_id']] ?? 'Unknown',
                'quantity_name'     => $qtyMap[$report['quantity_id']] ?? 'Unknown',
                'condition_name'    => $condMap[$report['condition_id']] ?? 'Unknown',
                'purok_name'        => $purokMap[$report['purok_id'] ?? 0] ?? 'N/A',
                'report_count'      => $this->reportModel->getGuestReportCount($contact),
                'hourly_count'      => $this->reportModel->getGuestHourlyReportCount($contact),
                'error'             => 'Cannot submit report. The coordinates are outside the official Barangay boundary.',
                'barangay_boundary' => $mapConfig['boundary_geojson'],
                'map_center'        => $mapConfig['center'],
            ];
            $this->view('guest/review', $data);
            return;
        }

        // Rate limit check
        if (!$this->reportModel->canGuestSubmit($contact)) {
            $dropdowns = $this->getFormDropdowns();
            $catMap    = array_column($dropdowns['categories'], 'category_name', 'category_id');
            $qtyMap    = array_column($dropdowns['quantities'],  'quantity_name', 'quantity_id');
            $condMap   = array_column($dropdowns['conditions'],  'condition_name', 'condition_id');
            $purokMap  = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');
            $mapConfig = $barangayModel->getMapConfig();
            $data = [
                'report'            => $report,
                'category_name'     => $catMap[$report['category_id']] ?? 'Unknown',
                'quantity_name'     => $qtyMap[$report['quantity_id']] ?? 'Unknown',
                'condition_name'    => $condMap[$report['condition_id']] ?? 'Unknown',
                'purok_name'        => $purokMap[$report['purok_id'] ?? 0] ?? 'N/A',
                'report_count'      => $this->reportModel->getGuestReportCount($contact),
                'hourly_count'      => $this->reportModel->getGuestHourlyReportCount($contact),
                'error'             => 'You have reached the submission limit (3 reports per hour). Please try again later.',
                'barangay_boundary' => $mapConfig['boundary_geojson'],
                'map_center'        => $mapConfig['center'],
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

        // Ensure purok_id is auto-detected
        if (empty($report['purok_id'])) {
            $report['purok_id'] = $barangayModel->detectPurok($report['latitude'], $report['longitude']) ?: 1;
        }

        // Create guest report
        $result = $this->reportModel->createGuestReport($report);
        if (!$result) {
            $dropdowns = $this->getFormDropdowns();
            $catMap    = array_column($dropdowns['categories'], 'category_name', 'category_id');
            $qtyMap    = array_column($dropdowns['quantities'],  'quantity_name', 'quantity_id');
            $condMap   = array_column($dropdowns['conditions'],  'condition_name', 'condition_id');
            $purokMap  = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');
            $mapConfig = $barangayModel->getMapConfig();
            $data = [
                'report'            => $report,
                'category_name'     => $catMap[$report['category_id']] ?? 'Unknown',
                'quantity_name'     => $qtyMap[$report['quantity_id']] ?? 'Unknown',
                'condition_name'    => $condMap[$report['condition_id']] ?? 'Unknown',
                'purok_name'        => $purokMap[$report['purok_id'] ?? 0] ?? 'N/A',
                'report_count'      => $this->reportModel->getGuestReportCount($contact),
                'hourly_count'      => $this->reportModel->getGuestHourlyReportCount($contact),
                'error'             => 'Failed to submit report. Please try again.',
                'barangay_boundary' => $mapConfig['boundary_geojson'],
                'map_center'        => $mapConfig['center'],
            ];
            $this->view('guest/review', $data);
            return;
        }

        $trackingNumber = $result['tracking_number'];

        // 1. Dispatch Email confirmation if guest has email address
        $dropdowns = $this->getFormDropdowns();
        $catMap    = array_column($dropdowns['categories'], 'category_name', 'category_id');
        $purokMap  = array_column($dropdowns['puroks'], 'purok_name', 'purok_id');

        $guestEmail = !empty($report['guest_email']) ? trim($report['guest_email']) : '';
        if (empty($guestEmail) && filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $guestEmail = trim($contact);
        }

        if (!empty($guestEmail) && filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
            require_once dirname(__DIR__) . '/Models/Helpers/OtpMailer.php';
            try {
                OtpMailer::sendReportStatusEmail(
                    $guestEmail,
                    $trackingNumber,
                    'pending',
                    $report['guest_name'] ?? 'Citizen',
                    '',
                    [
                        'category_name' => $catMap[$report['category_id']] ?? 'Waste Incident',
                        'purok_name'    => $purokMap[$report['purok_id'] ?? 0] ?? '',
                        'location'      => $report['location'] ?? ''
                    ]
                );
            } catch (\Throwable $e) {
                error_log('[GuestController] Email confirmation failed: ' . $e->getMessage());
            }
        }

        // 2. Dispatch SMS confirmation if contact is a mobile number
        if (preg_match('/^09\d{9}$/', $contact)) {
            require_once dirname(__DIR__) . '/Models/Helpers/SmsHelper.php';
            try {
                SmsHelper::sendStatusUpdate($contact, $trackingNumber, 'pending', $report['guest_name']);
            } catch (\Throwable $e) {
                error_log('[GuestController] SMS confirmation failed: ' . $e->getMessage());
            }
        }

        // Clear pending report session
        unset($_SESSION['guest_pending_report']);
        unset($_SESSION['guest_verified_phone']);
        unset($_SESSION['guest_verified_contact']);
        unset($_SESSION['guest_verified_at']);
        unset($_SESSION['guest_phone']);
        unset($_SESSION['guest_contact']);
        unset($_SESSION['guest_name']);

        // Store tracking number for confirmation screen
        $_SESSION['guest_confirmed_tracking'] = $trackingNumber;
        $_SESSION['guest_confirmed_contact']  = $contact;

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        header('Location: ' . app_url('index.php?url=guest/confirmation&tn=' . urlencode($trackingNumber)));
        exit;
    }

    // ============================================================
    // STEP 8: Confirmation Screen
    // ============================================================
    public function confirmation() {
        $trackingNumber = $_SESSION['guest_confirmed_tracking'] ?? ($_GET['tn'] ?? '');
        $contact        = $_SESSION['guest_confirmed_contact'] ?? '';

        if (empty($trackingNumber)) {
            header('Location: ' . app_url('index.php?url=guest'));
            exit;
        }

        $data = [
            'tracking_number' => $trackingNumber,
            'contact'         => $contact,
            'phone'           => $contact,
        ];

        $this->view('guest/confirmation', $data);
    }
}
