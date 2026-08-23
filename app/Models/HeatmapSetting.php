<?php
/**
 * HeatmapSetting Model
 * Handles heatmap configuration settings for GIS monitoring
 */
class HeatmapSetting {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->ensureSchema();
    }

    /**
     * Self-healing database schema check
     * Automatically creates table or adds missing columns on production
     */
    public function ensureSchema() {
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS heatmap_settings (
                setting_id INT AUTO_INCREMENT PRIMARY KEY,
                radius_meters INT DEFAULT 50,
                minimum_reports INT DEFAULT 3,
                low_density_color VARCHAR(10) DEFAULT '#FDE68A',
                medium_density_color VARCHAR(10) DEFAULT '#F97316',
                high_density_color VARCHAR(10) DEFAULT '#EF4444',
                low_min INT DEFAULT 3,
                low_max INT DEFAULT 5,
                moderate_min INT DEFAULT 6,
                moderate_max INT DEFAULT 10,
                severe_min INT DEFAULT 11,
                updated_by INT DEFAULT NULL,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->execute();

            $cols = [
                'low_min' => 'INT DEFAULT 3',
                'low_max' => 'INT DEFAULT 5',
                'moderate_min' => 'INT DEFAULT 6',
                'moderate_max' => 'INT DEFAULT 10',
                'severe_min' => 'INT DEFAULT 11',
                'radius_meters' => 'INT DEFAULT 50',
                'minimum_reports' => 'INT DEFAULT 3',
                'low_density_color' => 'VARCHAR(10) DEFAULT \'#FDE68A\'',
                'medium_density_color' => 'VARCHAR(10) DEFAULT \'#F97316\'',
                'high_density_color' => 'VARCHAR(10) DEFAULT \'#EF4444\'',
                'updated_by' => 'INT DEFAULT NULL'
            ];

            foreach ($cols as $colName => $colDef) {
                try {
                    $this->db->query("SHOW COLUMNS FROM heatmap_settings LIKE '$colName'");
                    if (!$this->db->single()) {
                        $this->db->query("ALTER TABLE heatmap_settings ADD COLUMN $colName $colDef");
                        $this->db->execute();
                    }
                } catch (\Throwable $e) {}
            }

            // Ensure default record exists
            $this->db->query("SELECT setting_id FROM heatmap_settings LIMIT 1");
            if (!$this->db->single()) {
                $this->db->query("INSERT INTO heatmap_settings (radius_meters, minimum_reports, low_density_color, medium_density_color, high_density_color, low_min, low_max, moderate_min, moderate_max, severe_min) 
                    VALUES (50, 3, '#FDE68A', '#F97316', '#EF4444', 3, 5, 6, 10, 11)");
                $this->db->execute();
            }
        } catch (\Throwable $e) {
            error_log('Heatmap ensureSchema notice: ' . $e->getMessage());
        }
    }

    /**
     * Get the current heatmap configuration
     * If no configuration exists, returns default values
     * 
     * @return array Configuration array with keys: radius_meters, minimum_reports, 
     *               low_density_color, medium_density_color, high_density_color
     */
    public function getConfig() {
        try {
            $this->db->query("SELECT * FROM heatmap_settings ORDER BY updated_at DESC, setting_id DESC LIMIT 1");
            $config = $this->db->single();

            // If no config exists, return default values
            if (!$config) {
                return $this->getDefaultValues();
            }

            return $config;
        } catch (\Throwable $e) {
            return $this->getDefaultValues();
        }
    }

    /**
     * Update heatmap configuration
     * 
     * @param array $data Associative array with configuration values
     * @return bool True on success, false on failure
     */
    public function updateConfig($data) {
        $this->ensureSchema();
        try {
            $current = $this->getConfig();
            $setting_id = $data['setting_id'] ?? $current['setting_id'] ?? 1;

            $radius = (int)($data['radius_meters'] ?? 50);
            $min_reports = (int)($data['minimum_reports'] ?? $data['low_min'] ?? 3);
            $low = $data['low_density_color'] ?? '#FDE68A';
            $med = $data['medium_density_color'] ?? '#F97316';
            $high = $data['high_density_color'] ?? '#EF4444';
            $low_min = (int)($data['low_min'] ?? 3);
            $low_max = (int)($data['low_max'] ?? 5);
            $moderate_min = (int)($data['moderate_min'] ?? 6);
            $moderate_max = (int)($data['moderate_max'] ?? 10);
            $severe_min = (int)($data['severe_min'] ?? 11);
            $updated_by = !empty($data['updated_by']) ? (int)$data['updated_by'] : null;

            if ($updated_by !== null) {
                try {
                    $this->db->query("SELECT id FROM users WHERE id = :uid LIMIT 1");
                    $this->db->bind(':uid', $updated_by);
                    if (!$this->db->single()) {
                        $updated_by = null;
                    }
                } catch (\Throwable $t) {
                    $updated_by = null;
                }
            }

            $this->db->query("
                UPDATE heatmap_settings SET 
                    radius_meters = :radius_meters,
                    minimum_reports = :minimum_reports,
                    low_density_color = :low_density_color,
                    medium_density_color = :medium_density_color,
                    high_density_color = :high_density_color,
                    low_min = :low_min,
                    low_max = :low_max,
                    moderate_min = :moderate_min,
                    moderate_max = :moderate_max,
                    severe_min = :severe_min,
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE setting_id = :setting_id
            ");
            $this->db->bind(':radius_meters', $radius);
            $this->db->bind(':minimum_reports', $min_reports);
            $this->db->bind(':low_density_color', $low);
            $this->db->bind(':medium_density_color', $med);
            $this->db->bind(':high_density_color', $high);
            $this->db->bind(':low_min', $low_min);
            $this->db->bind(':low_max', $low_max);
            $this->db->bind(':moderate_min', $moderate_min);
            $this->db->bind(':moderate_max', $moderate_max);
            $this->db->bind(':severe_min', $severe_min);
            $this->db->bind(':updated_by', $updated_by);
            $this->db->bind(':setting_id', $setting_id);

            $res = $this->db->execute();

            // Also sync all existing rows so no stale duplicate row exists
            try {
                $this->db->query("
                    UPDATE heatmap_settings SET 
                        radius_meters = :radius_meters,
                        minimum_reports = :minimum_reports,
                        low_density_color = :low_density_color,
                        medium_density_color = :medium_density_color,
                        high_density_color = :high_density_color,
                        low_min = :low_min,
                        low_max = :low_max,
                        moderate_min = :moderate_min,
                        moderate_max = :moderate_max,
                        severe_min = :severe_min,
                        updated_at = NOW()
                ");
                $this->db->bind(':radius_meters', $radius);
                $this->db->bind(':minimum_reports', $min_reports);
                $this->db->bind(':low_density_color', $low);
                $this->db->bind(':medium_density_color', $med);
                $this->db->bind(':high_density_color', $high);
                $this->db->bind(':low_min', $low_min);
                $this->db->bind(':low_max', $low_max);
                $this->db->bind(':moderate_min', $moderate_min);
                $this->db->bind(':moderate_max', $moderate_max);
                $this->db->bind(':severe_min', $severe_min);
                $this->db->execute();
            } catch (\Throwable $e) {}

            return $res;
        } catch (\Throwable $e) {
            error_log('Heatmap updateConfig error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get default heatmap values
     * Used when no configuration exists in the database
     * 
     * @return array Default configuration
     */
    public function getDefaultValues() {
        return [
            'radius_meters' => 50,
            'minimum_reports' => 3,
            'low_density_color' => '#FDE68A',    // Yellow
            'medium_density_color' => '#F97316',  // Orange
            'high_density_color' => '#EF4444',    // Red
            'low_min' => 3,
            'low_max' => 5,
            'moderate_min' => 6,
            'moderate_max' => 10,
            'severe_min' => 11
        ];
    }

    /**
     * Create a new heatmap configuration with default values
     * 
     * @param int $createdBy User ID of the creator
     * @return bool True on success, false on failure
     */
    public function createDefaultConfig($createdBy) {
        $defaults = $this->getDefaultValues();

        $this->db->query("
            INSERT INTO heatmap_settings (
                radius_meters, 
                minimum_reports, 
                low_density_color, 
                medium_density_color, 
                high_density_color, 
                low_min,
                low_max,
                moderate_min,
                moderate_max,
                severe_min,
                updated_by
            ) VALUES (
                :radius_meters,
                :minimum_reports,
                :low_density_color,
                :medium_density_color,
                :high_density_color,
                :low_min,
                :low_max,
                :moderate_min,
                :moderate_max,
                :severe_min,
                :updated_by
            )
        ");
        $this->db->bind(':radius_meters', $defaults['radius_meters']);
        $this->db->bind(':minimum_reports', $defaults['minimum_reports']);
        $this->db->bind(':low_density_color', $defaults['low_density_color']);
        $this->db->bind(':medium_density_color', $defaults['medium_density_color']);
        $this->db->bind(':high_density_color', $defaults['high_density_color']);
        $this->db->bind(':low_min', $defaults['low_min']);
        $this->db->bind(':low_max', $defaults['low_max']);
        $this->db->bind(':moderate_min', $defaults['moderate_min']);
        $this->db->bind(':moderate_max', $defaults['moderate_max']);
        $this->db->bind(':severe_min', $defaults['severe_min']);
        $this->db->bind(':updated_by', $createdBy);

        return $this->db->execute();
    }
}