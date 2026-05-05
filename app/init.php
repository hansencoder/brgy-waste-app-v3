<?php
require_once 'Config/Database.php';
require_once 'Core/App.php';
require_once 'Core/Controller.php';

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
        header("Location: /brgy-waste-app-v3/public/auth?error=" . urlencode("You have been automatically logged out due to inactivity."));
        exit;
    }
    $_SESSION['last_activity'] = time();
}
