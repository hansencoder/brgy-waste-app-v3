<?php
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

        return '/' . $cleaned;
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
        header("Location: /brgy-waste-app-v3/public/index.php?url=auth&error=" . urlencode("You have been automatically logged out due to inactivity."));
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
        header('Location: /brgy-waste-app-v3/public/index.php?url=maintenance');
        exit;
    }
}
// End maintenance guard
