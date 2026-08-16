<?php
header('Content-Type: application/json');

// Check authentication
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['secretary', 'captain'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Maintenance mode guard — admin roles bypass, non-admins get 503
require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Models/SystemMaintenance.php';
$_apiMaintenance = new SystemMaintenance();
if ($_apiMaintenance->isMaintenanceActive() && !SystemMaintenance::isAdminSession()) {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'System is under maintenance.']);
    exit;
}

try {
    // Load database
    
    $db = new Database();
    
    // Get all reports (excluding rejected) with basic info
    $db->query("SELECT id, submission_date, status FROM reports WHERE status IN ('pending', 'verified', 'resolved') ORDER BY submission_date DESC");
    $reports = $db->resultSet();
    
    if ($reports === false) {
        throw new Exception('Query returned false');
    }
    
    echo json_encode([
        'success' => true,
        'reports' => $reports ?: [],
        'count' => count($reports ?: [])
    ]);
    
} catch (Exception $e) {
    error_log('getAllReports API Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching reports: ' . $e->getMessage(),
        'reports' => []
    ]);
}
