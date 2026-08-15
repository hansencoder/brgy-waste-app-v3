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
            $count = $notificationModel->getUnreadCount($userId);
            echo json_encode(['success' => (bool)$result, 'unread_count' => $count]);
        }
        // Mark all notifications as read
        else if (isset($input['mark_all']) && $input['mark_all'] === true) {
            $result = $notificationModel->markAllAsRead($userId);
            echo json_encode(['success' => (bool)$result, 'unread_count' => 0]);
        }
        // Delete a single notification
        else if (isset($input['delete_id'])) {
            $result = $notificationModel->deleteNotification($input['delete_id'], $userId);
            $count = $notificationModel->getUnreadCount($userId);
            echo json_encode(['success' => (bool)$result, 'unread_count' => $count]);
        }
        // Clear all read notifications
        else if (isset($input['clear_read']) && $input['clear_read'] === true) {
            $result = $notificationModel->clearReadNotifications($userId);
            $count = $notificationModel->getUnreadCount($userId);
            echo json_encode(['success' => (bool)$result, 'unread_count' => $count]);
        }
        // Broadcast an alert (Admin only)
        else if (isset($input['broadcast']) && $input['broadcast'] === true) {
            $userRole = $_SESSION['user_role'] ?? '';
            if (!in_array($userRole, ['administrator', 'captain', 'secretary', 'kagawad'])) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }
            $title = trim($input['title'] ?? '');
            $content = trim($input['content'] ?? '');
            $type = trim($input['type'] ?? 'system');
            if (empty($title) || empty($content)) {
                echo json_encode(['success' => false, 'message' => 'Title and content required']);
                exit;
            }
            $result = $notificationModel->broadcastSystemAlert($title, $content, $type);
            echo json_encode(['success' => (bool)$result]);
        }
        // Get fresh notifications list & unread count
        else if (isset($input['get_list']) && $input['get_list'] === true) {
            $limit = isset($input['limit']) ? (int)$input['limit'] : 30;
            $items = $notificationModel->getUserNotifications($userId, $limit);
            $count = $notificationModel->getUnreadCount($userId);
            echo json_encode(['success' => true, 'notifications' => $items, 'unread_count' => $count]);
        }
        // Get unread count only
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
