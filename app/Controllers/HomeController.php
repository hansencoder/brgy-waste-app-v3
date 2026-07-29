<?php

class HomeController extends Controller {
    public function index() {
        $isLoggedIn = isset($_SESSION['user_id']);
        $role = $isLoggedIn ? $_SESSION['user_role'] : null;

        // Fetch public announcements (visibility_id = 1 for Public)
        $db = new Database();
        $db->query("
            SELECT a.*, u.name as author, av.visibility_name
            FROM announcements a
            LEFT JOIN users u ON a.created_by = u.id
            LEFT JOIN announcement_visibilities av ON a.visibility_id = av.visibility_id
            WHERE a.visibility_id = 1
            ORDER BY a.created_at DESC
            LIMIT 10
        ");
        $announcements = $db->resultSet();

        // Fetch active collection schedules
        $db->query("
            SELECT cs.*, 
                    GROUP_CONCAT(p.purok_name SEPARATOR ', ') as puroks
            FROM collection_schedules cs
            LEFT JOIN collection_schedule_puroks csp ON cs.schedule_id = csp.schedule_id
            LEFT JOIN puroks p ON csp.purok_id = p.purok_id
            WHERE cs.status = 'active'
            GROUP BY cs.schedule_id
            ORDER BY FIELD(cs.collection_day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
            LIMIT 4
        ");
        $schedules = $db->resultSet();

        // Fetch barangay info
        $db->query("SELECT * FROM barangays LIMIT 1");
        $barangay = $db->single();

        // Get unread notification count (if logged in)
        $unreadCount = 0;
        if ($isLoggedIn) {
            require_once __DIR__ . '/../Models/Notification.php';
            $notificationModel = new Notification();
            $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);
        }

        $this->view('home/index', [
            'isLoggedIn' => $isLoggedIn,
            'role' => $role,
            'announcements' => $announcements,
            'schedules' => $schedules,
            'barangay' => $barangay,
            'unreadCount' => $unreadCount
        ]);
    }
}