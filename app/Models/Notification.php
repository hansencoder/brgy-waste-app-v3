<?php
class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get notifications for a specific user
     */
    public function getUserNotifications($userId, $limit = 10) {
        $this->db->query("
            SELECT n.*, 
                   r.id as report_id, 
                   rs.status_name as report_status,
                   a.id as announcement_id, 
                   a.title as announcement_title
            FROM notifications n
            LEFT JOIN reports r ON n.report_id = r.id
            LEFT JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN announcements a ON n.announcement_id = a.id
            WHERE n.user_id = :user_id OR n.send_to_all = TRUE
            ORDER BY n.created_at DESC
            LIMIT :limit
        ");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId) {
        $this->db->query("
            SELECT COUNT(*) as count
            FROM notifications
            WHERE (user_id = :user_id OR send_to_all = TRUE) AND is_read = FALSE
        ");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($notificationId, $userId) {
        $this->db->query("
            UPDATE notifications 
            SET is_read = TRUE 
            WHERE id = :id AND (user_id = :user_id OR send_to_all = TRUE)
        ");
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId) {
        $this->db->query("
            UPDATE notifications 
            SET is_read = TRUE 
            WHERE (user_id = :user_id OR send_to_all = TRUE) AND is_read = FALSE
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Create a notification
     */
    public function create($data) {
        $this->db->query("
            INSERT INTO notifications (user_id, report_id, announcement_id, type, title, content, send_to_all)
            VALUES (:user_id, :report_id, :announcement_id, :type, :title, :content, :send_to_all)
        ");
        $this->db->bind(':user_id', $data['user_id'] ?? null);
        $this->db->bind(':report_id', $data['report_id'] ?? null);
        $this->db->bind(':announcement_id', $data['announcement_id'] ?? null);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':send_to_all', $data['send_to_all'] ?? false ? 1 : 0);
        return $this->db->execute();
    }

    /**
     * Create notification when report status changes
     */
    public function createReportStatusNotification($reportId, $oldStatus, $newStatus, $changedBy) {
        // Get report owner
        $this->db->query("SELECT resident_id FROM reports WHERE id = :id");
        $this->db->bind(':id', $reportId);
        $report = $this->db->single();

        if (!$report) return false;

        $residentId = $report['resident_id'];

        // Get changer name
        $this->db->query("SELECT name FROM users WHERE id = :id");
        $this->db->bind(':id', $changedBy);
        $changer = $this->db->single();
        $changerName = $changer ? $changer['name'] : 'Admin';

        // Create title and message based on status
        $title = "Report Status Updated";
        $message = "";

        switch ($newStatus) {
            case 'verified':
                $title = "Report Verified";
                $message = "Your report has been verified by {$changerName}.";
                break;
            case 'in_progress':
            case 'In Progress':
                $title = "Report In Progress";
                $message = "Your report is now in progress and being addressed.";
                break;
            case 'resolved':
                $title = "Report Resolved";
                $message = "Your report has been resolved. Thank you for reporting!";
                break;
            case 'rejected':
                $title = "Report Rejected";
                $message = "Your report has been rejected. Please check the details and resubmit.";
                break;
            default:
                $message = "Your report status has been updated to " . ucfirst($newStatus) . ".";
        }

        return $this->create([
            'user_id' => $residentId,
            'report_id' => $reportId,
            'type' => 'report_update',
            'title' => $title,
            'content' => $message,
            'send_to_all' => false
        ]);
    }

    /**
     * Create notification for new announcement
     */
    public function createAnnouncementNotification($announcementId, $createdBy) {
        // Get announcement details
        $this->db->query("SELECT title, content FROM announcements WHERE id = :id");
        $this->db->bind(':id', $announcementId);
        $announcement = $this->db->single();

        if (!$announcement) return false;

        // Create notification for all residents
        return $this->create([
            'user_id' => null,
            'announcement_id' => $announcementId,
            'type' => 'announcement',
            'title' => $announcement['title'],
            'content' => $announcement['content'],
            'send_to_all' => true
        ]);
    }

    /**
     * Create notification for account approval
     */
    public function createAccountApprovedNotification($userId, $approvedBy) {
        // Get approver name
        $this->db->query("SELECT name FROM users WHERE id = :id");
        $this->db->bind(':id', $approvedBy);
        $approver = $this->db->single();
        $approverName = $approver ? $approver['name'] : 'Admin';

        return $this->create([
            'user_id' => $userId,
            'type' => 'account',
            'title' => 'Account Approved',
            'content' => "Your account has been approved by {$approverName}. You can now submit waste reports.",
            'send_to_all' => false
        ]);
    }

    /**
     * Create notification for new report submission (notify all admins)
     */
    public function createReportSubmittedNotification($reportId) {
        // Get report and resident details
        $this->db->query("
            SELECT r.id, r.description, r.submission_date, u.name as resident_name 
            FROM reports r 
            JOIN users u ON r.resident_id = u.id 
            WHERE r.id = :id
        ");
        $this->db->bind(':id', $reportId);
        $report = $this->db->single();

        if (!$report) return false;

        // Get all admin IDs (secretary and captain)
        $this->db->query("SELECT id FROM users WHERE role_id IN (1, 2)"); // Admin and Supervisor
        $admins = $this->db->resultSet();

        // Create notification for each admin
        foreach ($admins as $admin) {
            $this->create([
                'user_id' => $admin['id'],
                'report_id' => $reportId,
                'type' => 'report_update',
                'title' => 'New Report Submitted',
                'content' => "New report submitted by {$report['resident_name']}: " . substr($report['description'], 0, 50) . (strlen($report['description']) > 50 ? '...' : ''),
                'send_to_all' => false
            ]);
        }

        return true;
    }

    /**
     * Delete a single notification for user
     */
    public function deleteNotification($notificationId, $userId) {
        $this->db->query("
            DELETE FROM notifications 
            WHERE id = :id AND (user_id = :user_id OR send_to_all = TRUE)
        ");
        $this->db->bind(':id', $notificationId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Clear all read notifications for user
     */
    public function clearReadNotifications($userId) {
        $this->db->query("
            DELETE FROM notifications 
            WHERE is_read = TRUE AND (user_id = :user_id OR send_to_all = TRUE)
        ");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Broadcast a system announcement / alert
     */
    public function broadcastSystemAlert($title, $content, $type = 'system') {
        return $this->create([
            'user_id' => null,
            'report_id' => null,
            'announcement_id' => null,
            'type' => $type,
            'title' => $title,
            'content' => $content,
            'send_to_all' => true
        ]);
    }
}