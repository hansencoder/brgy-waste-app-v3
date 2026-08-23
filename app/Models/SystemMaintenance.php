<?php

/**
 * SystemMaintenance Model
 * Manages maintenance mode state, scheduling, and history.
 */
class SystemMaintenance {

    private $db;

    // Roles that bypass maintenance mode
    const ADMIN_ROLES = ['administrator', 'secretary', 'captain'];

    public function __construct() {
        $this->db = new Database();
    }

    // ============================================================
    // CURRENT STATUS
    // ============================================================

    /**
     * Get the current maintenance configuration row.
     */
    public function getStatus() {
        $this->db->query("SELECT sm.*, u.name as updated_by_name 
                          FROM system_maintenance sm
                          LEFT JOIN users u ON sm.updated_by = u.id
                          WHERE sm.id = 1");
        $row = $this->db->single();

        if (!$row) {
            // Auto-insert default if missing
            $this->db->query("INSERT IGNORE INTO system_maintenance (id, maintenance_mode, maintenance_message) 
                              VALUES (1, 0, 'The system is currently undergoing scheduled maintenance. We apologize for any inconvenience and will be back shortly.')");
            $this->db->execute();
            $this->db->query("SELECT sm.*, u.name as updated_by_name 
                              FROM system_maintenance sm
                              LEFT JOIN users u ON sm.updated_by = u.id
                              WHERE sm.id = 1");
            $row = $this->db->single();
        }

        return $row;
    }

    /**
     * Get live synchronized status information for badges, cards, and guards.
     */
    public function getLiveStatusInfo() {
        $this->autoDeactivateIfExpired();
        $status = $this->getStatus();
        
        $mode = (int)($status['maintenance_mode'] ?? 0);
        $type = strtolower($status['maintenance_type'] ?? 'scheduled');
        $startAt = !empty($status['start_at']) ? strtotime($status['start_at']) : null;
        $endAt = !empty($status['end_at']) ? strtotime($status['end_at']) : null;
        $now = time();

        $stateKey = 'operational';
        $stateLabel = 'Operational';
        $subLabel = 'Public & Resident Portals Online';
        $badgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
        $dotClass = 'bg-emerald-500';
        $heroBorder = 'border-slate-200';
        $heroBg = 'bg-gradient-to-r from-emerald-50/80 via-teal-50/50 to-white';

        if ($mode === 1) {
            if ($type === 'emergency') {
                $stateKey = 'emergency';
                $stateLabel = 'Emergency Lockdown';
                $subLabel = 'System access restricted to authorized personnel';
                $badgeClass = 'bg-red-50 text-red-800 border-red-200';
                $dotClass = 'bg-red-500';
                $heroBorder = 'border-red-300';
                $heroBg = 'bg-gradient-to-r from-red-50 via-rose-50/70 to-white';
            } elseif ($type === 'scheduled' && $startAt && $startAt > $now) {
                $stateKey = 'scheduled';
                $stateLabel = 'Maintenance Scheduled';
                $subLabel = 'Scheduled to begin ' . date('M d, Y h:i A', $startAt);
                $badgeClass = 'bg-blue-50 text-blue-800 border-blue-200';
                $dotClass = 'bg-blue-500';
                $heroBorder = 'border-blue-300';
                $heroBg = 'bg-gradient-to-r from-blue-50/80 via-indigo-50/50 to-white';
            } else {
                $stateKey = 'maintenance';
                $stateLabel = 'Maintenance Active';
                $subLabel = 'Public access paused for maintenance';
                $badgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                $dotClass = 'bg-amber-500';
                $heroBorder = 'border-amber-300';
                $heroBg = 'bg-gradient-to-r from-amber-50 via-yellow-50/70 to-white';
            }
        }

        return [
            'raw' => $status,
            'is_active' => ($stateKey === 'emergency' || $stateKey === 'maintenance'),
            'is_emergency' => ($stateKey === 'emergency'),
            'state_key' => $stateKey,
            'state_label' => $stateLabel,
            'sub_label' => $subLabel,
            'badge_class' => $badgeClass,
            'dot_class' => $dotClass,
            'hero_border' => $heroBorder,
            'hero_bg' => $heroBg
        ];
    }

    /**
     * Returns true if the system is currently in maintenance mode.
     * For scheduled type: only active if NOW() >= start_at (or start_at is null).
     * Also calls autoDeactivateIfExpired() to handle past end_at.
     */
    public function isMaintenanceActive() {
        $this->autoDeactivateIfExpired();

        $this->db->query("SELECT maintenance_mode, maintenance_type, start_at, end_at 
                          FROM system_maintenance WHERE id = 1");
        $row = $this->db->single();

        if (!$row || !$row['maintenance_mode']) {
            return false;
        }

        // If start_at is set and hasn't arrived yet, not active
        if (!empty($row['start_at']) && strtotime($row['start_at']) > time()) {
            return false;
        }

        return true;
    }

    /**
     * Automatically deactivate if end_at has passed.
     */
    public function autoDeactivateIfExpired() {
        $this->db->query("SELECT maintenance_mode, end_at, updated_by 
                          FROM system_maintenance WHERE id = 1");
        $row = $this->db->single();

        if ($row && $row['maintenance_mode'] && !empty($row['end_at'])) {
            if (strtotime($row['end_at']) < time()) {
                // Log in history before deactivating
                $this->logHistory('AUTO_DEACTIVATE_MAINTENANCE', [
                    'maintenance_type'    => 'scheduled',
                    'maintenance_message' => '',
                    'reason'              => 'Automatically deactivated — scheduled end time reached',
                    'previous_status'     => 1,
                    'new_status'          => 0,
                    'start_at'            => null,
                    'end_at'              => null,
                ], $row['updated_by'] ?? null, null);

                $this->db->query("UPDATE system_maintenance SET 
                                  maintenance_mode = 0, 
                                  updated_at = NOW()
                                  WHERE id = 1");
                $this->db->execute();
            }
        }
    }

    // ============================================================
    // SAVE / ACTIVATE / DEACTIVATE
    // ============================================================

    /**
     * Save/update maintenance settings (without changing active state).
     */
    public function saveSettings($data, $userId) {
        $this->db->query("UPDATE system_maintenance SET
                          maintenance_type    = :maintenance_type,
                          maintenance_message = :maintenance_message,
                          reason              = :reason,
                          start_at            = :start_at,
                          end_at              = :end_at,
                          allow_admin_access  = :allow_admin_access,
                          updated_by          = :updated_by,
                          updated_at          = NOW()
                          WHERE id = 1");
        $this->db->bind(':maintenance_type',    $data['maintenance_type']);
        $this->db->bind(':maintenance_message', $data['maintenance_message']);
        $this->db->bind(':reason',              $data['reason']);
        $this->db->bind(':start_at',            $data['start_at']);
        $this->db->bind(':end_at',              $data['end_at']);
        $this->db->bind(':allow_admin_access',  1); // Always keep admin access
        $this->db->bind(':updated_by',          $userId);
        return $this->db->execute();
    }

    /**
     * Activate maintenance mode.
     */
    public function activate($data, $userId) {
        $this->db->query("UPDATE system_maintenance SET
                          maintenance_mode    = 1,
                          maintenance_type    = :maintenance_type,
                          maintenance_message = :maintenance_message,
                          reason              = :reason,
                          start_at            = :start_at,
                          end_at              = :end_at,
                          allow_admin_access  = 1,
                          updated_by          = :updated_by,
                          updated_at          = NOW()
                          WHERE id = 1");
        $this->db->bind(':maintenance_type',    $data['maintenance_type']);
        $this->db->bind(':maintenance_message', $data['maintenance_message']);
        $this->db->bind(':reason',              $data['reason']);
        $this->db->bind(':start_at',            $data['start_at']);
        $this->db->bind(':end_at',              $data['end_at']);
        $this->db->bind(':updated_by',          $userId);
        return $this->db->execute();
    }

    /**
     * Deactivate maintenance mode (return to operational).
     */
    public function deactivate($userId) {
        $this->db->query("UPDATE system_maintenance SET
                          maintenance_mode = 0,
                          updated_by       = :updated_by,
                          updated_at       = NOW()
                          WHERE id = 1");
        $this->db->bind(':updated_by', $userId);
        return $this->db->execute();
    }

    // ============================================================
    // HISTORY
    // ============================================================

    /**
     * Log a maintenance action into maintenance_history.
     */
    public function logHistory($action, $data, $userId, $ip = null) {
        $this->db->query("INSERT INTO maintenance_history 
                          (action, maintenance_type, maintenance_message, reason, previous_status, new_status, start_at, end_at, performed_by, ip_address)
                          VALUES 
                          (:action, :maintenance_type, :maintenance_message, :reason, :previous_status, :new_status, :start_at, :end_at, :performed_by, :ip_address)");
        $this->db->bind(':action',               $action);
        $this->db->bind(':maintenance_type',     $data['maintenance_type'] ?? null);
        $this->db->bind(':maintenance_message',  $data['maintenance_message'] ?? null);
        $this->db->bind(':reason',               $data['reason'] ?? null);
        $this->db->bind(':previous_status',      $data['previous_status'] ?? null);
        $this->db->bind(':new_status',           $data['new_status'] ?? null);
        $this->db->bind(':start_at',             $data['start_at'] ?? null);
        $this->db->bind(':end_at',               $data['end_at'] ?? null);
        $this->db->bind(':performed_by',         $userId);
        $this->db->bind(':ip_address',           $ip ?? $_SERVER['REMOTE_ADDR'] ?? null);
        return $this->db->execute();
    }

    /**
     * Retrieve maintenance history with user names.
     */
    public function getHistory($limit = 50) {
        $this->db->query("SELECT mh.*, u.name as performed_by_name
                          FROM maintenance_history mh
                          LEFT JOIN users u ON mh.performed_by = u.id
                          ORDER BY mh.created_at DESC
                          LIMIT :limit");
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // ============================================================
    // HELPER
    // ============================================================

    /**
     * Check if current session user is an admin or staff role that bypasses maintenance.
     */
    public static function isAdminSession() {
        $role = strtolower($_SESSION['user_role'] ?? '');
        return (!empty($role) && $role !== 'resident');
    }
}
