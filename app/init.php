<?php
date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/Config/Database.php';
require_once __DIR__ . '/Core/App.php';
require_once __DIR__ . '/Core/Controller.php';

// ============================================================
// DYNAMIC BASE URL & ASSET RESOLVERS (Local & Production)
// ============================================================
if (!function_exists('get_base_url')) {
    function get_base_url() {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        if (strpos($uri, '/brgy-waste-app-v3') !== false || strpos($script, '/brgy-waste-app-v3') !== false) {
            return '/brgy-waste-app-v3/public';
        }
        return '';
    }
}

if (!function_exists('app_url')) {
    function app_url($path = '') {
        $base = get_base_url();
        $path = ltrim($path, '/');
        $path = preg_replace('#^brgy-waste-app-v3/public/#', '', $path);
        $path = preg_replace('#^public/#', '', $path);
        $path = ltrim($path, '/');
        if ($base === '') {
            return '/' . $path;
        }
        return $base . '/' . $path;
    }
}

if (!function_exists('format_asset_url')) {
    function format_asset_url($path, $fallback = '') {
        if (empty($path)) return $fallback;
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0 || strpos($path, 'data:') === 0) {
            return $path;
        }

        // Clean any leading hardcoded prefixes
        $cleaned = preg_replace('#^/brgy-waste-app-v3/public/#', '', $path);
        $cleaned = preg_replace('#^/public/#', '', $cleaned);
        $cleaned = preg_replace('#^/brgy-waste-app-v3/#', '', $cleaned);
        $cleaned = ltrim($cleaned, '/');

        // Ensure root-relative asset directory
        if (!preg_match('#^(uploads/|images/|css/|js/|assets/)#', $cleaned)) {
            $cleaned = 'uploads/' . $cleaned;
        }

        return app_url($cleaned);
    }
}

if (!function_exists('get_client_ip')) {
    function get_client_ip() {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

if (!function_exists('has_permission')) {
    function has_permission($permission) {
        $role = strtolower($_SESSION['user_role'] ?? '');
        if (empty($role) || $role === 'resident') {
            return false;
        }
        if ($role === 'administrator') {
            return true;
        }
        $perms = $_SESSION['user_permissions'] ?? [];
        if (!is_array($perms)) {
            $perms = json_decode($perms, true) ?: [];
        }
        if (in_array('all', $perms)) {
            return true;
        }
        if (in_array($role, ['secretary', 'captain']) && empty($perms)) {
            return true;
        }
        return in_array($permission, $perms);
    }
}

// Session Timeout Handler 
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) { // 30 mins
        // We log the timeout
        $db = new Database();
        $db->query("INSERT INTO audit_logs (user_id, action, affected_record, details, result) VALUES (:user_id, 'Auto Logout', 'Session', 'User logged out due to inactivity', 'success')");
        $db->bind(':user_id', $_SESSION['user_id']);
        $db->execute();
        
        session_unset();
        session_destroy();
        header('Location: ' . app_url('index.php?url=auth&error=' . urlencode("You have been automatically logged out due to inactivity.")));
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// ============================================================
// MAINTENANCE MODE GUARD
// Runs on every request before controllers are loaded.
// Admin roles bypass; all other authenticated and guest
// users are redirected to the maintenance page.
// ============================================================
require_once __DIR__ . '/Models/SystemMaintenance.php';

$_maintenanceModel = new SystemMaintenance();

if ($_maintenanceModel->isMaintenanceActive()) {

    // Resolve current URL segment
    $_urlRaw     = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
    $_urlSegment = strtolower(explode('/', $_urlRaw)[0]);

    // Pages that must always be reachable (login, maintenance page itself)
    $_publicSegments = ['auth', '', 'home', 'maintenance'];

    $isAdminRole = SystemMaintenance::isAdminSession();

    if (!$isAdminRole && !in_array($_urlSegment, $_publicSegments)) {
        header('Location: ' . app_url('index.php?url=maintenance'));
        exit;
    }
}
// End maintenance guard
