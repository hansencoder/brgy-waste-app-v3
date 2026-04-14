<?php
session_start();
require_once __DIR__ . '/../../app/init.php';
require_once __DIR__ . '/../../app/Models/Notification.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$notificationModel = new Notification();

// Get request method
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        // Mark single notification as read
        if (isset($input['notification_id'])) {
            $result = $notificationModel->markAsRead($input['notification_id'], $userId);
            echo json_encode(['success' => $result]);
        }
        // Mark all notifications as read
        else if (isset($input['mark_all']) && $input['mark_all'] === true) {
            $result = $notificationModel->markAllAsRead($userId);
            echo json_encode(['success' => $result]);
        }
        // Get unread count
        else if (isset($input['get_unread_count']) && $input['get_unread_count'] === true) {
            $count = $notificationModel->getUnreadCount($userId);
            echo json_encode(['success' => true, 'count' => $count]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
