<?php
class AuthController extends Controller {
    private $userModel;
    private $auditModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->auditModel = $this->model('AuditLog');
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] == 'resident') {
                header('Location: /brgy-waste-app-v3/public/resident');
            } else {
                header('Location: /brgy-waste-app-v3/public/admin');
            }
            exit;
        }
        // Show login page
        $data = ['error' => isset($_GET['error']) ? filter_var($_GET['error'], FILTER_SANITIZE_STRING) : ''];
        $this->view('auth/login', $data);
    }

    // Process login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                return $this->view('auth/login', ['error' => 'Please fill in all fields.']);
            }

            // check attempts lock
            if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 50) {
                if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
                    return $this->view('auth/login', ['error' => 'Account temporarily locked. Try again later.']);
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

                // Gen 2FA
                $otp = rand(100000, 999999);
                $this->userModel->saveMfaToken($user['id'], $otp);
                
                // Mocking OTP sending for testing. In prod this calls an SMS API
                $_SESSION['debug_otp'] = $otp; 
                $_SESSION['mfa_user_id'] = $user['id'];
                
                $this->auditModel->logAction($user['id'], 'Login partial success', 'User', '2FA code sent', 'success');

                header('Location: /brgy-waste-app-v3/public/auth/mfa');
                exit;
            } else {
                $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['lockout_time'] = time() + (5 * 60); // 5 mins
                    $this->auditModel->logAction(null, 'Account locked', 'User', "Exceeded login attempts for $email", 'failed');
                    return $this->view('auth/login', ['error' => 'Account temporarily locked due to multiple failed attempts.']);
                }

                $this->auditModel->logAction(null, 'Login failed', 'User', "Invalid credentials for $email", 'failed');
                return $this->view('auth/login', ['error' => 'Incorrect email or password.']);
            }
        }
    }

    public function mfa() {
        if (!isset($_SESSION['mfa_user_id'])) {
            header('Location: /brgy-waste-app-v3/public/auth');
            exit;
        }

        $data = ['error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = trim($_POST['otp']);
            $user_id = $_SESSION['mfa_user_id'];

            if ($this->userModel->verifyMfaToken($user_id, $otp)) {
                // Success
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
                unset($_SESSION['debug_otp']);

                if ($user['role'] == 'resident') {
                    header('Location: /brgy-waste-app-v3/public/resident');
                } else {
                    header('Location: /brgy-waste-app-v3/public/admin');
                }
                exit;
            } else {
                $this->auditModel->logAction($_SESSION['mfa_user_id'], '2FA failed', 'User', 'Invalid or expired OTP', 'failed');
                $data['error'] = 'Invalid or expired OTP code.';
            }
        }

        // Handle Resend
        if (isset($_GET['action']) && $_GET['action'] == 'resend') {
            $otp = rand(100000, 999999);
            $this->userModel->saveMfaToken($_SESSION['mfa_user_id'], $otp);
            $_SESSION['debug_otp'] = $otp; 
            $this->auditModel->logAction($_SESSION['mfa_user_id'], '2FA Resend', 'User', 'Code resent', 'success');
            $data['success'] = 'New OTP sent!';
        }

        $this->view('auth/mfa', $data);
    }

    // RESIDENT REGISTRATION
    public function register() {
        $data = [
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Extract & Validate
            $password = $_POST['password'];
            $c_password = $_POST['confirm_password'];
            
            if ($password !== $c_password) {
                $data['error'] = "Passwords do not match.";
                return $this->view('auth/register', $data);
            }

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
                $this->auditModel->logAction(null, 'User Registration', 'User', "Pending registration for {$post['email']}", 'success');
                $data['success'] = "Registration successful! Your account is pending approval by the Barangay Secretary.";
            } else {
                $data['error'] = "Something went wrong.";
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
        header('Location: /brgy-waste-app-v3/public/auth');
        exit;
    }
}
