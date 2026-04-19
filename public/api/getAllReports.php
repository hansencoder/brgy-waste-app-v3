<?php
header('Content-Type: application/json');

// Check authentication
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['secretary', 'captain'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

try {
    // Load database
    require_once __DIR__ . '/../../app/Config/Database.php';
    
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
