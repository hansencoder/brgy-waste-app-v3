<?php

class AuthController extends Controller {
    private $userModel;
    private $auditModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->auditModel = $this->model('AuditLog');
    }
    // ============================================================
    // FORGOT PASSWORD PAGE
    // ============================================================
    public function forgotPassword() {
        $data = ['error' => '', 'success' => ''];
        $this->view('auth/forgot_password', $data);
    }

    

    private function formatRetryTime(int $seconds): string {
        $minutes = floor($seconds / 60);
        $remaining = $seconds % 60;
        if ($minutes > 0) {
            return $remaining > 0 ? "{$minutes}:" . str_pad($remaining, 2, '0', STR_PAD_LEFT) : "{$minutes} minutes";
        }
        return "{$remaining} seconds";
    }

    // ============================================================
    // RESET PASSWORD PAGE (OTP & New Password Form)
    // ============================================================
    public function resetPassword() {
        if (!isset($_SESSION['reset_email'])) {
            header('Location: ' . app_url('index.php?url=' . urlencode('auth/forgotPassword')));
            exit;
        }
        $data = ['error' => '', 'success' => ''];
        $this->view('auth/reset_password', $data);
    }

    public function verifyResetOtp() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . app_url('index.php?url=' . urlencode('auth/forgotPassword')));
            exit;
        }

        $otp = trim($_POST['otp'] ?? '');
        $email = $_SESSION['reset_email'] ?? null;
        $userId = $_SESSION['reset_user_id'] ?? null;

        if (!$email || !$userId) {
            header('Location: ' . app_url('index.php?url=' . urlencode('auth/forgotPassword')));
            exit;
        }

        $tokenRecord = $this->userModel->validatePasswordResetToken($email, $otp);
        if (!$tokenRecord) {
            return $this->view('auth/reset_password', ['error' => 'Invalid or expired OTP. Please request a new one.']);
        }

        $_SESSION['reset_otp_verified'] = true;
        $_SESSION['reset_otp'] = $otp;
        header('Location: ' . app_url('index.php?url=' . urlencode('auth/newPassword')));
        exit;
    }

    public function newPassword() {
        if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_user_id']) || empty($_SESSION['reset_otp_verified'])) {
            header('Location: ' . app_url('index.php?url=' . urlencode('auth/forgotPassword')));
            exit;
        }

        $data = ['error' => '', 'success' => ''];
        $this->view('auth/new_password', $data);
    }
    
    // ============================================================
    // PROCESS PASSWORD RESET (Verify OTP and Update Password)
    // ============================================================
    public function processResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = trim($_POST['otp'] ?? $_SESSION['reset_otp'] ?? '');
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $email = $_SESSION['reset_email'] ?? null;
            $user_id = $_SESSION['reset_user_id'] ?? null;

            if (!$email || !$user_id || empty($_SESSION['reset_otp_verified'])) {
                header('Location: ' . app_url('index.php?url=' . urlencode('auth/forgotPassword')));
                exit;
            }

            // Validate password requirements
            if (strlen($new_password) < 8 || !preg_match("/[A-Z]/", $new_password) || !preg_match("/[0-9]/", $new_password) || !preg_match("/[\W]/", $new_password)) {
                return $this->view('auth/new_password', ['error' => 'Password must be at least 8 chars with uppercase, number, and special char.']);
            }
            if ($new_password !== $confirm_password) {
                return $this->view('auth/new_password', ['error' => 'Passwords do not match.']);
            }

            // Validate OTP
            $tokenRecord = $this->userModel->validatePasswordResetToken($email, $otp);
            if (!$tokenRecord) {
                return $this->view('auth/new_password', ['error' => 'Invalid or expired OTP. Please request a new one.']);
            }

            // Update password
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $this->userModel->updatePassword($user_id, $hashed);
            $this->userModel->markResetTokenAsUsed($tokenRecord['id']);

            // Clean session and audit
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_user_id']);
            unset($_SESSION['reset_otp_verified']);
            unset($_SESSION['reset_otp']);
            $this->auditModel->logAction($user_id, 'Password Reset', 'User', 'Password reset via email OTP', 'success');

            $_SESSION['flash_success'] = 'Password reset successfully. You can now log in.';
            header('Location: ' . app_url('index.php?url=' . urlencode('auth')));
            exit;
        }
    }

    // ============================================================
    // SEND PASSWORD RESET OTP
    // ============================================================
    public function sendResetOtp() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->view('auth/forgot_password', ['error' => 'Invalid email address.']);
            }

            $user = $this->userModel->getUserByEmail($email);
            if (!$user) {
                // Security: Do not reveal if email exists. Just say "If an account exists, an email was sent."
                return $this->view('auth/forgot_password', ['success' => 'If an account exists with this email, a reset code has been sent.']);
            }
            

            // Generate and save OTP
            $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->userModel->savePasswordResetToken($user['id'], $email, $token);

            // Send email
            require_once '../app/Models/Helpers/OtpMailer.php';
            try {
                OtpMailer::sendPasswordResetEmail($email, $token, $user['name']);
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_user_id'] = $user['id'];
                unset($_SESSION['reset_otp_verified']);
                unset($_SESSION['reset_otp']);
                header('Location: ' . app_url('index.php?url=' . urlencode('auth/resetPassword')));
                exit;
            } catch (Exception $e) {
                return $this->view('auth/forgot_password', ['error' => 'Could not send reset email. Please try again later.']);
            }
        }
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
                header('Location: ' . app_url('index.php?url=resident'));
            } else {
                header('Location: ' . app_url('index.php?url=admin'));
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
            $input = htmlspecialchars(trim($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8');
            $password = $_POST['password'] ?? '';

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

                // Handle OTP verification: Match login input type (Phone vs Email)
                require_once '../app/Models/Helpers/OtpMailer.php';
                require_once '../app/Models/Helpers/SmsHelper.php';

                $email = $user['email'] ?? '';
                $phone = $user['phone_number'] ?? '';

                $isInputEmail = filter_var($input, FILTER_VALIDATE_EMAIL);
                $isInputPhone = preg_match('/^[0-9+\s()-]{7,20}$/', $input);

                if ($isInputPhone && !empty($phone)) {
                    $sendViaSms = true;
                    $contactTarget = $phone;
                } elseif ($isInputEmail && !empty($email)) {
                    $sendViaSms = false;
                    $contactTarget = $email;
                } else {
                    // Fallback for username login: prefer phone if available, else email
                    if (!empty($phone)) {
                        $sendViaSms = true;
                        $contactTarget = $phone;
                    } elseif (!empty($email)) {
                        $sendViaSms = false;
                        $contactTarget = $email;
                    } else {
                        $contactTarget = '';
                    }
                }

                if (empty($contactTarget)) {
                    return $this->view('auth/login', ['error' => 'No valid email or phone number on file for authentication.']);
                }

                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $can = $this->userModel->canSendEmailOtp($contactTarget, $ip);
                if (!$can['ok']) {
                    if ($can['reason'] === 'cooldown') {
                        $wait = isset($can['retry_after']) ? $can['retry_after'] : 60;
                        $timeLabel = $this->formatRetryTime((int) $wait);
                        return $this->view('auth/login', ['error' => "Please wait {$timeLabel} before requesting a new code."]);
                    }
                    return $this->view('auth/login', ['error' => 'Too many OTP requests. Please try later.']);
                }

                $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $saved = $this->userModel->saveMfaToken($user['id'], $contactTarget, $token);
                if (!$saved) {
                    return $this->view('auth/login', ['error' => 'Could not generate OTP.']);
                }

                try {
                    if ($sendViaSms) {
                        SmsHelper::sendOtp($contactTarget, $token, $user['name']);
                        $_SESSION['mfa_type'] = 'phone';
                    } else {
                        OtpMailer::sendOtpEmail($contactTarget, $token, $user['name']);
                        $_SESSION['mfa_type'] = 'email';
                    }

                    $this->userModel->recordEmailRate($contactTarget, $ip);

                    $_SESSION['mfa_user_id'] = $user['id'];
                    $_SESSION['mfa_email'] = $contactTarget;

                    $this->auditModel->logAction($user['id'], 'Login partial success', 'User', 'OTP sent to ' . ($sendViaSms ? 'phone' : 'email'), 'success');

                    header('Location: ' . app_url('index.php?url=' . urlencode('auth/mfa')));
                    exit;
                } catch (Exception $e) {
                    $this->auditModel->logAction($user['id'], 'OTP send failed', 'User', $e->getMessage(), 'failed');
                    return $this->view('auth/login', ['error' => 'We could not send the verification code. Please try again later.']);
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
            header('Location: ' . app_url('index.php?url=' . urlencode('auth')));
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
            $otp = trim($_POST['otp'] ?? '');
            $user_id = $_SESSION['mfa_user_id'] ?? null;
            $email = $_SESSION['mfa_email'] ?? null;

            if (!$user_id) {
                header('Location: ' . app_url('index.php?url=' . urlencode('auth')));
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

                $role_name = strtolower($user['role_name'] ?? 'resident');
                $_SESSION['user_role'] = $role_name;
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_purok'] = $user['purok_name'] ?? 'Purok 1';
                $_SESSION['user_position'] = $user['position_name'] ?? 'Resident';
                $_SESSION['last_activity'] = time();

                $this->auditModel->logAction($user_id, 'Login successful', 'User', 'Successfully completed 2FA', 'success');
                unset($_SESSION['mfa_user_id']);
                unset($_SESSION['mfa_email']);

                // Redirect based on role
                if ($role_name == 'resident') {
                    header('Location: ' . app_url('index.php?url=' . urlencode('resident')));
                } elseif ($role_name == 'supervisor') {
                    header('Location: ' . app_url('index.php?url=' . urlencode('supervisor')));
                } else {
                    header('Location: ' . app_url('index.php?url=' . urlencode('admin')));
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
            require_once '../app/Models/Helpers/SmsHelper.php';

            $contactTarget = $_SESSION['mfa_email'] ?? null;
            $mfaType = $_SESSION['mfa_type'] ?? 'email';

            if (!$contactTarget) {
                $data['error'] = 'No contact information available to resend code.';
            } else {
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $can = $this->userModel->canSendEmailOtp($contactTarget, $ip);
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
                    $saved = $this->userModel->saveMfaToken($_SESSION['mfa_user_id'], $contactTarget, $token);
                    if (!$saved) {
                        $data['error'] = 'Could not generate OTP.';
                    } else {
                        try {
                            $db = new Database();
                            $db->query("SELECT name FROM users WHERE id = :id");
                            $db->bind(':id', $_SESSION['mfa_user_id']);
                            $userName = $db->single()['name'] ?? '';

                            if ($mfaType === 'phone') {
                                SmsHelper::sendOtp($contactTarget, $token, $userName);
                                $data['success'] = 'A new OTP has been sent via SMS to your mobile number.';
                            } else {
                                OtpMailer::sendOtpEmail($contactTarget, $token, $userName);
                                $data['success'] = 'A new OTP has been sent to your email address.';
                            }

                            $this->userModel->recordEmailRate($contactTarget, $ip);
                            $this->auditModel->logAction($_SESSION['mfa_user_id'], '2FA Resend', 'User', 'Code resent to ' . $mfaType, 'success');
                            unset($data['retry_after_seconds']);
                        } catch (Exception $e) {
                            $this->auditModel->logAction($_SESSION['mfa_user_id'], 'OTP resend failed', 'User', $e->getMessage(), 'failed');
                            $data['error'] = 'We could not resend the verification code. Please try again.';
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

            // Validate contact info (either Email OR Phone Number must be provided)
            $email = trim(strtolower($post['email'] ?? ''));
            $phone_number = trim($post['phone_number'] ?? '');

            if (empty($email) && empty($phone_number)) {
                $data['error'] = "Please provide either an email address or a phone number.";
                return $this->view('auth/register', $data);
            }

            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $data['error'] = "Invalid email address format.";
                    return $this->view('auth/register', $data);
                }
                if ($this->userModel->findUserByEmail($email)) {
                    $data['error'] = "This email address is already registered.";
                    return $this->view('auth/register', $data);
                }
            }

            if (!empty($phone_number)) {
                if (!preg_match("/^09\d{9}$/", $phone_number)) {
                    $data['error'] = "Invalid PH mobile number format. Standard format: 09XXXXXXXXX.";
                    return $this->view('auth/register', $data);
                }
                if ($this->userModel->findUserByPhone($phone_number)) {
                    $data['error'] = "This phone number is already registered.";
                    return $this->view('auth/register', $data);
                }
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
                'phone_number' => $phone_number,
                'email' => $email,
                'password' => $hashed,
                'role_id' => 3, // Resident
                'position_id' => 6, // Resident position
                'purok_id' => (int)($post['purok_id'] ?? 1), // Default Purok 1
                'status' => 'pending' // Always pending until OTP verified
            ];

            if ($this->userModel->register($regData)) {
                $db = new Database();
                $userId = $db->lastInsertId();

                $contactTarget = !empty($email) ? $email : $phone_number;
                $isPhoneOnly = empty($email);

                // Generate OTP token
                $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $this->userModel->saveMfaToken($userId, $contactTarget, $token);

                require_once '../app/Models/Helpers/OtpMailer.php';
                require_once '../app/Models/Helpers/SmsHelper.php';
                try {
                    if ($isPhoneOnly) {
                        SmsHelper::sendOtp($phone_number, $token, $regData['name']);
                        $this->auditModel->logAction(null, 'Registration OTP sent', 'User', "SMS OTP sent to {$phone_number}", 'success');
                    } else {
                        OtpMailer::sendOtpEmail($email, $token, $regData['name']);
                        $this->auditModel->logAction(null, 'Registration OTP sent', 'User', "Email OTP sent to {$email}", 'success');
                    }
                } catch (Exception $e) {
                    $this->auditModel->logAction(null, 'Registration OTP failed', 'User', $e->getMessage(), 'failed');
                }

                $_SESSION['reg_user_id'] = $userId;
                $_SESSION['reg_email']   = $contactTarget;
                $_SESSION['reg_type']    = $isPhoneOnly ? 'phone' : 'email';
                $_SESSION['reg_name']    = $regData['name'];

                header('Location: ' . app_url('index.php?url=' . urlencode('auth/verifyRegistration')));
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
            header('Location: ' . app_url('index.php?url=' . urlencode('auth/register')));
            exit;
        }

        $data = ['error' => '', 'success' => ''];
        $email = $_SESSION['reg_email'];
        $regType = $_SESSION['reg_type'] ?? 'email';
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
                $this->auditModel->logAction($user_id, 'Registration verified', 'User', "Account activated via OTP", 'success');

                unset($_SESSION['reg_user_id']);
                unset($_SESSION['reg_email']);
                unset($_SESSION['reg_type']);
                unset($_SESSION['reg_name']);

                $_SESSION['flash_success'] = 'Your account has been verified. You can now log in.';
                header('Location: ' . app_url('index.php?url=' . urlencode('auth')));
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
            require_once '../app/Models/Helpers/SmsHelper.php';

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
                    if ($regType === 'phone') {
                        SmsHelper::sendOtp($email, $token, $name);
                        $data['success'] = 'A new verification code has been sent via SMS to your mobile number.';
                    } else {
                        OtpMailer::sendOtpEmail($email, $token, $name);
                        $data['success'] = 'A new OTP has been sent to your email address.';
                    }
                    $this->userModel->recordEmailRate($email, $ip);
                    $this->auditModel->logAction($user_id, 'Registration OTP resent', 'User', 'Code resent', 'success');
                    unset($data['retry_after_seconds']);
                } catch (Exception $e) {
                    $this->auditModel->logAction($user_id, 'Registration OTP resend failed', 'User', $e->getMessage(), 'failed');
                    $data['error'] = 'Could not resend the verification code. Please try again.';
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
        header('Location: ' . app_url('index.php?url=' . urlencode('auth')));
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