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

        // Get official barangay boundary and map settings
        require_once __DIR__ . '/../Models/Barangay.php';
        $barangayModel = new Barangay();
        $mapConfig = $barangayModel->getMapConfig();

        // Fetch geo-tagged reports for community map
        // RULE: Resolved reports remain visible for 15 days before automatic public cleanup.
        $db->query("
            SELECT r.id, r.latitude, r.longitude, r.description,
                   rs.status_name as status, rs.color_code as status_color,
                   wc.category_name as waste_category,
                   p.purok_name as purok,
                   r.submission_date,
                   COALESCE(r.updated_at, r.submission_date) as resolved_at,
                   (SELECT photo_path FROM report_photos WHERE report_id = r.id AND is_primary = 1 LIMIT 1) as photo_path
            FROM reports r
            JOIN report_statuses rs ON r.status_id = rs.status_id
            LEFT JOIN waste_categories wc ON r.category_id = wc.category_id
            LEFT JOIN puroks p ON r.purok_id = p.purok_id
            WHERE r.latitude IS NOT NULL AND r.longitude IS NOT NULL AND r.latitude != 0 AND r.longitude != 0
              AND LOWER(rs.status_name) != 'rejected'
              AND (
                  LOWER(rs.status_name) != 'resolved'
                  OR (LOWER(rs.status_name) = 'resolved' AND COALESCE(r.updated_at, r.submission_date) >= DATE_SUB(NOW(), INTERVAL 15 DAY))
              )
            ORDER BY r.submission_date DESC
            LIMIT 150
        ");
        $publicReports = $db->resultSet();

        // Get unread notification count (if logged in)
        $unreadCount = 0;
        if ($isLoggedIn) {
            require_once __DIR__ . '/../Models/Notification.php';
            $notificationModel = new Notification();
            $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);
        }

        // Fetch active collection notes for the public portal
        $collectionNotes = [];
        try {
            $db->query("SELECT title, content FROM collection_notes WHERE is_active = 1 ORDER BY sort_order ASC, note_id ASC");
            $collectionNotes = $db->resultSet();
        } catch (Exception $e) {
            // Table may not exist yet — silently fall back to empty array
        }

        // Fetch active prohibited actions and penalties for the public portal
        $prohibitedActions = [];
        $penalties = [];
        $penaltyRules = [];
        try {
            $db->query("SELECT offense_no, title, description, legal_ref, fine_range, alt_penalty, rule_type FROM penalty_rules WHERE is_active = 1 AND (rule_type = 'prohibited_action' OR rule_type = 'prohibited') ORDER BY sort_order ASC, rule_id ASC");
            $prohibitedActions = $db->resultSet();
            
            $db->query("SELECT offense_no, title, description, legal_ref, fine_range, alt_penalty, rule_type FROM penalty_rules WHERE is_active = 1 AND (rule_type = 'penalty' OR rule_type IS NULL OR rule_type = '' OR rule_type NOT IN ('prohibited_action', 'prohibited')) ORDER BY offense_no ASC, sort_order ASC, rule_id ASC");
            $penalties = $db->resultSet();
            
            // If no prohibited_action labeled rows exist, populate both gracefully
            if (empty($prohibitedActions) && !empty($penalties)) {
                $penaltyRules = $penalties;
            } else {
                $penaltyRules = array_merge($prohibitedActions, $penalties);
            }
        } catch (Exception $e) {
            $penaltyRules = [];
        }

        $this->view('home/index', [
            'isLoggedIn' => $isLoggedIn,
            'role' => $role,
            'announcements' => $announcements,
            'schedules' => $schedules,
            'barangay' => $barangay,
            'unreadCount' => $unreadCount,
            'mapConfig' => $mapConfig,
            'publicReports' => $publicReports,
            'collectionNotes' => $collectionNotes,
            'penaltyRules' => $penaltyRules,
            'prohibitedActions' => $prohibitedActions,
            'penalties' => $penalties
        ]);
    }
}