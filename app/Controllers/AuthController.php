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
    private function getLockoutSeconds(): int
    {
        if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
            return $_SESSION['lockout_time'] - time();
        }
        return 0;
    }

    public function index() {
            if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] == 'resident') {
                header('Location: /brgy-waste-app-v3/public/index.php?url=resident');
            } else {
                header('Location: /brgy-waste-app-v3/public/index.php?url=admin');
            }
            exit;
        }
        // Show login page
        $data = ['error' => isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : ''];
        $this->view('auth/login', $data);
    }

    // Process login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            // Empty Field Checks, Even if JS is disabled, the login route manually checks
            if (empty($email) || empty($password)) {
                return $this->view('auth/login', ['error' => 'Please fill in all fields.']);
            }

            // check attempts lock
            if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 50) {
                if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
                    $data = [
                        'error' => 'Account temporarily locked. Try again later.',
                        'lockout_seconds' => $this->getLockoutSeconds()
                    ];
                    return $this->view('auth/login', $data);
                } else {
                    $_SESSION['login_attempts'] = 0; // reset
                }
            }

            $user = $this->userModel->findUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] == 'pending') {
                    return $this->view('auth/login', ['error' => 'Account is pending approval.']);
                }
                if ($user['status'] == 'deactivated') {
                    return $this->view('auth/login', ['error' => 'Account is deactivated.']);
                }

                // Generate secure 6-digit OTP and send it via Email
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
                    $this->auditModel->logAction(null, 'Account locked', 'User', "Exceeded login attempts for $email", 'failed');
                    $data = [
                        'error' => 'Account temporarily locked due to multiple failed attempts.',
                        'lockout_seconds' => $this->getLockoutSeconds()
                    ];
                    return $this->view('auth/login', $data);
                }

                $this->auditModel->logAction(null, 'Login failed', 'User', "Invalid credentials for $email", 'failed');
                return $this->view('auth/login', ['error' => 'Incorrect email or password.']);
            }
        }
    }

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
                $wait = isset($can['retry_after']) ? (int)$can['retry_after'] : 60;
                $data['retry_after_seconds'] = $wait;
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
                // Success
                // Regenerate session ID to prevent Session Fixation attacks
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user_id;

                // Hacky quick lookup for role
                $db = new Database(); 
                $db->query("SELECT * FROM users WHERE id = :id");
                $db->bind(':id', $user_id);
                $user = $db->single();
                
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['last_activity'] = time();

                $this->auditModel->logAction($user_id, 'Login successful', 'User', 'Successfully completed 2FA', 'success');
                unset($_SESSION['mfa_user_id']);
                unset($_SESSION['mfa_email']);

                if ($user['role'] == 'resident') {
                    header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('resident'));
                } else {
                    header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('admin'));
                }
                exit;
            } else {
                // $res may contain reason 'locked' or 'invalid'
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
                        $wait = isset($can['retry_after']) ? (int)$can['retry_after'] : 60;
                        $timeLabel = $this->formatRetryTime((int) $wait);
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

    // RESIDENT REGISTRATION

    
    public function verifyRegistration() {
    // Ensure we have a registration session
    if (!isset($_SESSION['reg_user_id']) || !isset($_SESSION['reg_email'])) {
        header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth/register'));
        exit;
    }

    $data = ['error' => '', 'success' => ''];
    $email = $_SESSION['reg_email'];
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    // Check for existing cooldown (if they requested a resend recently)
    $can = $this->userModel->canSendEmailOtp($email, $ip);
    if (!$can['ok'] && $can['reason'] === 'cooldown') {
        $data['retry_after_seconds'] = (int)$can['retry_after'];
    }

    // Handle POST (OTP submission)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $otp = trim($_POST['otp']);
        $user_id = $_SESSION['reg_user_id'];

        $res = $this->userModel->verifyEmailOtp($email, $otp);
        if ($res['ok']) {
            // OTP correct → activate the user
            $this->userModel->updateUserStatus($user_id, 'active');
            $this->auditModel->logAction($user_id, 'Registration verified', 'User', "Account activated via email OTP", 'success');

            // Clear session variables
            unset($_SESSION['reg_user_id']);
            unset($_SESSION['reg_email']);
            unset($_SESSION['reg_name']);

            // Redirect to login with a success message
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

    // Render the verification view
    $this->view('auth/verify_registration', $data);
    }


    public function register() {
        $data = [
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = array_map(function($v) { return is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v; }, $_POST);
            
            // Extract & Validate the password fields kahit ibypass yung frontend, ivavalidate parin sa database if match ba yung password
            $password = $_POST['password'];
            $c_password = $_POST['confirm_password'];
            
            if ($password !== $c_password) {
                $data['error'] = "Passwords do not match.";
                return $this->view('auth/register', $data);
            }
            // stricly na kailangan kompletuhin yung password requirement
            if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[\W]/", $password)) {
                $data['error'] = "Password must be at least 8 chars long with uppercase, lowercase, number, and special char.";
                return $this->view('auth/register', $data);
            }
            
            if ($this->userModel->findUserByEmail($post['email'])) {
                $data['error'] = "Email is already registered.";
                return $this->view('auth/register', $data);
            }

            if (!preg_match("/^09\d{9}$/", $post['phone_number'])) {
                $data['error'] = "Invalid PH mobile number format. Standard format: 09XXXXXXXXX.";
                return $this->view('auth/register', $data);
            }

            $hashed = password_hash($password, PASSWORD_BCRYPT);

            // Handle ID uploads
            $upload_dir = '../public/uploads/ids/';
            $id_front_path = null;
            $id_back_path = null;

            if (isset($_FILES['id_front']) && $_FILES['id_front']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['id_front']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('front_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['id_front']['tmp_name'], $upload_dir . $filename)) {
                        $id_front_path = '/uploads/ids/' . $filename;
                    }
                } else {
                    $data['error'] = "Invalid file format for Front ID. Only JPG and PNG allowed.";
                    return $this->view('auth/register', $data);
                }
            } else {
                $data['error'] = "Please upload a Valid ID (Front).";
                return $this->view('auth/register', $data);
            }

            if (isset($_FILES['id_back']) && $_FILES['id_back']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['id_back']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('back_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['id_back']['tmp_name'], $upload_dir . $filename)) {
                        $id_back_path = '/uploads/ids/' . $filename;
                    }
                } else {
                    $data['error'] = "Invalid file format for Back ID. Only JPG and PNG allowed.";
                    return $this->view('auth/register', $data);
                }
            } else {
                $data['error'] = "Please upload a Valid ID (Back).";
                return $this->view('auth/register', $data);
            }

            $regData = [
                'name' => trim($post['name']),
                'address' => trim($post['address']),
                'phone_number' => trim($post['phone_number']),
                'email' => trim(strtolower($post['email'])),
                'password' => $hashed,
                'id_front' => $id_front_path,
                'id_back' => $id_back_path
            ];
    
            if ($this->userModel->register($regData)) {
                // Get the newly created user ID
                $db = new Database();
                $userId = $db->lastInsertId();

                // Generate OTP token
                $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $this->userModel->saveMfaToken($userId, $regData['email'], $token);

                // Send OTP email
                require_once '../app/Models/Helpers/OtpMailer.php';
                try {
                    OtpMailer::sendOtpEmail($regData['email'], $token, $regData['name']);
                    $this->auditModel->logAction(null, 'Registration OTP sent', 'User', "OTP sent to {$regData['email']}", 'success');
                } catch (Exception $e) {
                    // If email fails, we still allow the user to request resend later
                    $this->auditModel->logAction(null, 'Registration OTP failed', 'User', $e->getMessage(), 'failed');
                }

                // Store registration data in session for verification step
                $_SESSION['reg_user_id'] = $userId;
                $_SESSION['reg_email'] = $regData['email'];
                $_SESSION['reg_name'] = $regData['name'];

                // Redirect to email verification page
                header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth/verifyRegistration'));
                exit;
            } else {
                $data['error'] = 'Something went wrong.';
            }
        }

        $this->view('auth/register', $data);
    }

    public function logout() {
        if(isset($_SESSION['user_id'])) {
            $this->auditModel->logAction($_SESSION['user_id'], 'Logout', 'User', 'User logged out manually', 'success');
        }
        session_unset();
        session_destroy();
        header('Location: /brgy-waste-app-v3/public/index.php?url=' . urlencode('auth'));
        exit;
    }
}
