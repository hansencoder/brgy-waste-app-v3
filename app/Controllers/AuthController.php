<?php

class AuthController extends Controller {
    private $userModel;
    private $auditModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->auditModel = $this->model('AuditLog');
    }

    private function formatRetryTime(int $seconds): string {
        $minutes = floor($seconds / 60);
        $remaining = $seconds % 60;
        if ($minutes > 0) {
            return $remaining > 0 ? "{$minutes}:" . str_pad($remaining, 2, '0', STR_PAD_LEFT) : "{$minutes} minutes";
        }
        return "{$remaining} seconds";
    }

    private function getLockoutSeconds(): int {
        if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
            return $_SESSION['lockout_time'] - time();
        }
        return 0;
    }

    // ============================================================
    // LOGIN PAGE
    // ============================================================
    public function index() {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] == 'resident') {
                header('Location: /brgy-waste-app-v3/public/index.php?url=resident');
            } else {
                header('Location: /brgy-waste-app-v3/public/index.php?url=admin');
            }
            exit;
        }
        $data = ['error' => isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : ''];
        $this->view('auth/login', $data);
    }

    // ============================================================
    // PROCESS LOGIN
    // ============================================================
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = filter_var(trim($_POST['username_email']), FILTER_SANITIZE_STRING);
            $password = $_POST['password'];

            if (empty($input) || empty($password)) {
                return $this->view('auth/login', ['error' => 'Please fill in all fields.']);
            }

            // Lockout check (same as before)
            if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 50) {
                if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
                    $data = [
                        'error' => 'Account temporarily locked. Try again later.',
                        'lockout_seconds' => $this->getLockoutSeconds()
                    ];
                    return $this->view('auth/login', $data);
                } else {
                    $_SESSION['login_attempts'] = 0;
                }
            }

            // Find user by email OR username
            $user = $this->userModel->findUserByEmailOrUsername($input);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] == 'pending') {
                    return $this->view('auth/login', ['error' => 'Account is pending email verification. Please check your email.']);
                }
                if ($user['status'] == 'deactivated') {
                    return $this->view('auth/login', ['error' => 'Account is deactivated.']);
                }

                // Generate and send OTP via email
                require_once '../app/Models/Helpers/OtpMailer.php';

                $email = $user['email'];
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $this->view('auth/login', ['error' => 'No valid email address on file.']);
                }

                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $can = $this->userModel->canSendEmailOtp($email, $ip);
                if (!$can['ok']) {
                    if ($can['reason'] === 'cooldown') {
                        $wait = isset($can['retry_after']) ? $can['retry_after'] : 60;
                        $timeLabel = $this->formatRetryTime((int) $wait);
                        return $this->view('auth/login', ['error' => "Please wait {$timeLabel} before requesting a new code."]);
                    }
                    return $this->view('auth/login', ['error' => 'Too many OTP requests. Please try later.']);
                }

                $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $saved = $this->userModel->saveMfaToken($user['id'], $email, $token);
                if (!$saved) {
                    return $this->view('auth/login', ['error' => 'Could not generate OTP.']);
                }

                try {
                    OtpMailer::sendOtpEmail($email, $token, $user['name']);
                    $this->userModel->recordEmailRate($email, $ip);

                    $_SESSION['mfa_user_id'] = $user['id'];
                    $_SESSION['mfa_email'] = $email;

                    $this->auditModel->logAction($user['id'], 'Login partial success', 'User', 'OTP Email sent', 'success');

                    header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth/mfa'));
                    exit;
                } catch (Exception $e) {
                    $this->auditModel->logAction($user['id'], 'OTP Email failed', 'User', $e->getMessage(), 'failed');
                    return $this->view('auth/login', ['error' => 'We could not send the verification email. Please try again later.']);
                }
            } else {
                $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['lockout_time'] = time() + (5 * 60);
                    $this->auditModel->logAction(null, 'Account locked', 'User', "Exceeded login attempts for $input", 'failed');
                    $data = [
                        'error' => 'Account temporarily locked due to multiple failed attempts.',
                        'lockout_seconds' => $this->getLockoutSeconds()
                    ];
                    return $this->view('auth/login', $data);
                }

                $this->auditModel->logAction(null, 'Login failed', 'User', "Invalid credentials for $input", 'failed');
                return $this->view('auth/login', ['error' => 'Incorrect email/username or password.']);
            }
        }
    }

    // ============================================================
    // MFA VERIFICATION PAGE
    // ============================================================
    public function mfa() {
        if (!isset($_SESSION['mfa_user_id'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth'));
            exit;
        }

        $data = ['error' => '', 'success' => ''];

        $email = $_SESSION['mfa_email'] ?? null;
        if ($email) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $can = $this->userModel->canSendEmailOtp($email, $ip);
            if (!$can['ok'] && $can['reason'] === 'cooldown') {
                $data['retry_after_seconds'] = (int)$can['retry_after'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = trim($_POST['otp']);
            $user_id = $_SESSION['mfa_user_id'];
            $email = $_SESSION['mfa_email'] ?? null;

            if (!$email) {
                header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth'));
                exit;
            }

            $res = $this->userModel->verifyEmailOtp($email, $otp);
            if ($res['ok']) {
                // Success - regenerate session ID for security
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user_id;

                // Fetch user with role, position, and purok names
                $db = new Database(); 
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
                $db->bind(':id', $user_id);
                $user = $db->single();

                $_SESSION['user_role'] = strtolower($user['role_name'] ?? 'resident');
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_purok'] = $user['purok_name'] ?? 'Purok 1';
                $_SESSION['user_position'] = $user['position_name'] ?? 'Resident';
                $_SESSION['last_activity'] = time();

                $this->auditModel->logAction($user_id, 'Login successful', 'User', 'Successfully completed 2FA', 'success');
                unset($_SESSION['mfa_user_id']);
                unset($_SESSION['mfa_email']);

                // Redirect based on role
                if ($_SESSION['user_role'] == 'resident') {
                    header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('resident'));
                } elseif ($_SESSION['user_role'] == 'supervisor') {
                    header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('supervisor'));
                } else {
                    header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('admin'));
                }
                exit;
            } else {
                $this->auditModel->logAction($_SESSION['mfa_user_id'], '2FA failed', 'User', 'Invalid or expired OTP', 'failed');
                if (isset($res['reason']) && $res['reason'] === 'locked') {
                    $data['error'] = 'Too many failed attempts. Please request a new OTP.';
                } else {
                    $data['error'] = 'Invalid or expired OTP code.';
                }
            }
        }

        // Handle Resend
        if (isset($_GET['action']) && $_GET['action'] == 'resend') {
            require_once '../app/Models/Helpers/OtpMailer.php';

            $email = $_SESSION['mfa_email'] ?? null;
            if (!$email) {
                $data['error'] = 'No email available to resend code.';
            } else {
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $can = $this->userModel->canSendEmailOtp($email, $ip);
                if (!$can['ok']) {
                    if ($can['reason'] === 'cooldown') {
                        $wait = (int)$can['retry_after'];
                        $timeLabel = $this->formatRetryTime($wait);
                        $data['retry_after_seconds'] = $wait;
                        $data['error'] = "Please wait {$timeLabel} before requesting a new code.";
                    } else {
                        $data['error'] = 'Too many OTP requests. Please try again later.';
                    }
                } else {
                    $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    $saved = $this->userModel->saveMfaToken($_SESSION['mfa_user_id'], $email, $token);
                    if (!$saved) {
                        $data['error'] = 'Could not generate OTP.';
                    } else {
                        try {
                            $db = new Database();
                            $db->query("SELECT name FROM users WHERE id = :id");
                            $db->bind(':id', $_SESSION['mfa_user_id']);
                            $userName = $db->single()['name'] ?? '';

                            OtpMailer::sendOtpEmail($email, $token, $userName);
                            $this->userModel->recordEmailRate($email, $ip);
                            $this->auditModel->logAction($_SESSION['mfa_user_id'], '2FA Resend', 'User', 'Code resent', 'success');
                            $data['success'] = 'A new OTP has been sent to your email address.';
                            unset($data['retry_after_seconds']);
                        } catch (Exception $e) {
                            $this->auditModel->logAction($_SESSION['mfa_user_id'], 'OTP resend failed', 'User', $e->getMessage(), 'failed');
                            $data['error'] = 'We could not resend the verification email. Please try again.';
                        }
                    }
                }
            }
        }

        $this->view('auth/mfa', $data);
    }

    // ============================================================
    // REGISTRATION
    // ============================================================
    public function register() {
        $data = [
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = array_map(function($v) { return is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v; }, $_POST);

            $password = $_POST['password'];
            $c_password = $_POST['confirm_password'];

            if ($password !== $c_password) {
                $data['error'] = "Passwords do not match.";
                return $this->view('auth/register', $data);
            }

            // Password strength
            if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[\W]/", $password)) {
                $data['error'] = "Password must be at least 8 chars long with uppercase, lowercase, number, and special char.";
                return $this->view('auth/register', $data);
            }

            // Validate email
            $email = trim(strtolower($post['email']));
            if ($this->userModel->findUserByEmail($email)) {
                $data['error'] = "Email is already registered.";
                return $this->view('auth/register', $data);
            }

            // Validate username (if provided)
            $username = trim($post['username'] ?? '');
            if (empty($username)) {
                $data['error'] = "Username is required.";
                return $this->view('auth/register', $data);
            }
            if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
                $data['error'] = "Username must be 3-30 characters and contain only letters, numbers, and underscores.";
                return $this->view('auth/register', $data);
            }
            if ($this->userModel->findUserByUsername($username)) {
                $data['error'] = "Username is already taken.";
                return $this->view('auth/register', $data);
            }

            // Validate phone number (PH format)
            if (!preg_match("/^09\d{9}$/", $post['phone_number'])) {
                $data['error'] = "Invalid PH mobile number format. Standard format: 09XXXXXXXXX.";
                return $this->view('auth/register', $data);
            }

            // Account type
            $account_type = $post['account_type'] ?? 'resident';
            if (!in_array($account_type, ['resident', 'non-resident'])) {
                $account_type = 'resident';
            }

            $hashed = password_hash($password, PASSWORD_BCRYPT);

            $regData = [
                'name' => trim($post['name']),
                'username' => $username,
                'account_type' => $account_type,
                'address' => trim($post['address'] ?? ''),
                'phone_number' => trim($post['phone_number']),
                'email' => $email,
                'password' => $hashed,
                'role_id' => 3, // Resident
                'position_id' => 6, // Resident position
                'purok_id' => 1, // Default Purok 1
                'status' => 'pending' // Will be set to 'active' after OTP verification
            ];

            if ($this->userModel->register($regData)) {
                $db = new Database();
                $userId = $db->lastInsertId();

                // Generate OTP token
                $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $this->userModel->saveMfaToken($userId, $email, $token);

                // Send OTP email
                require_once '../app/Models/Helpers/OtpMailer.php';
                try {
                    OtpMailer::sendOtpEmail($email, $token, $regData['name']);
                    $this->auditModel->logAction(null, 'Registration OTP sent', 'User', "OTP sent to {$email}", 'success');
                } catch (Exception $e) {
                    $this->auditModel->logAction(null, 'Registration OTP failed', 'User', $e->getMessage(), 'failed');
                }

                $_SESSION['reg_user_id'] = $userId;
                $_SESSION['reg_email'] = $email;
                $_SESSION['reg_name'] = $regData['name'];

                header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth/verifyRegistration'));
                exit;
            } else {
                $data['error'] = 'Something went wrong. Please try again.';
            }
        }

        $this->view('auth/register', $data);
    }

    // ============================================================
    // REGISTRATION OTP VERIFICATION
    // ============================================================
    public function verifyRegistration() {
        if (!isset($_SESSION['reg_user_id']) || !isset($_SESSION['reg_email'])) {
            header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth/register'));
            exit;
        }

        $data = ['error' => '', 'success' => ''];
        $email = $_SESSION['reg_email'];
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // Check for existing cooldown
        $can = $this->userModel->canSendEmailOtp($email, $ip);
        if (!$can['ok'] && $can['reason'] === 'cooldown') {
            $data['retry_after_seconds'] = (int)$can['retry_after'];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = trim($_POST['otp']);
            $user_id = $_SESSION['reg_user_id'];

            $res = $this->userModel->verifyEmailOtp($email, $otp);
            if ($res['ok']) {
                // Activate user
                $this->userModel->updateUserStatus($user_id, 'active');
                $this->auditModel->logAction($user_id, 'Registration verified', 'User', "Account activated via email OTP", 'success');

                unset($_SESSION['reg_user_id']);
                unset($_SESSION['reg_email']);
                unset($_SESSION['reg_name']);

                $_SESSION['flash_success'] = 'Your account has been verified. You can now log in.';
                header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth'));
                exit;
            } else {
                $this->auditModel->logAction($user_id, 'Registration OTP failed', 'User', 'Invalid or expired OTP', 'failed');
                if (isset($res['reason']) && $res['reason'] === 'locked') {
                    $data['error'] = 'Too many failed attempts. Please request a new OTP.';
                } else {
                    $data['error'] = 'Invalid or expired OTP code.';
                }
            }
        }

        // Handle Resend
        if (isset($_GET['action']) && $_GET['action'] == 'resend') {
            require_once '../app/Models/Helpers/OtpMailer.php';

            $user_id = $_SESSION['reg_user_id'];
            $email = $_SESSION['reg_email'];
            $name = $_SESSION['reg_name'] ?? '';

            $can = $this->userModel->canSendEmailOtp($email, $ip);
            if (!$can['ok']) {
                if ($can['reason'] === 'cooldown') {
                    $wait = (int)$can['retry_after'];
                    $data['retry_after_seconds'] = $wait;
                    $data['error'] = "Please wait " . $this->formatRetryTime($wait) . " before requesting a new code.";
                } else {
                    $data['error'] = 'Too many OTP requests. Please try later.';
                }
            } else {
                $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $this->userModel->saveMfaToken($user_id, $email, $token);
                try {
                    OtpMailer::sendOtpEmail($email, $token, $name);
                    $this->userModel->recordEmailRate($email, $ip);
                    $this->auditModel->logAction($user_id, 'Registration OTP resent', 'User', 'Code resent', 'success');
                    $data['success'] = 'A new OTP has been sent to your email address.';
                    unset($data['retry_after_seconds']);
                } catch (Exception $e) {
                    $this->auditModel->logAction($user_id, 'Registration OTP resend failed', 'User', $e->getMessage(), 'failed');
                    $data['error'] = 'Could not resend the verification email. Please try again.';
                }
            }
        }

        $this->view('auth/verify_registration', $data);
    }

    // ============================================================
    // LOGOUT
    // ============================================================
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->auditModel->logAction($_SESSION['user_id'], 'Logout', 'User', 'User logged out manually', 'success');
        }
        session_unset();
        session_destroy();
        header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth'));
        exit;
    }

    // ============================================================
    // HELPER: Get role name from role_id
    // ============================================================
    private function getRoleName($role_id) {
        $db = new Database();
        $db->query("SELECT role_name FROM roles WHERE role_id = :role_id");
        $db->bind(':role_id', $role_id);
        $row = $db->single();
        return $row ? strtolower($row['role_name']) : 'resident';
    }
}