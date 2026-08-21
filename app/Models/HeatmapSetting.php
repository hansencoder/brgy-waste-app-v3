<?php
/**
 * HeatmapSetting Model
 * Handles heatmap configuration settings for GIS monitoring
 */
class HeatmapSetting {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get the current heatmap configuration
     * If no configuration exists, returns default values
     * 
     * @return array Configuration array with keys: radius_meters, minimum_reports, 
     *               low_density_color, medium_density_color, high_density_color
     */
    public function getConfig() {
        $this->db->query("SELECT * FROM heatmap_settings ORDER BY setting_id DESC LIMIT 1");
        $config = $this->db->single();

        // If no config exists, return default values
        if (!$config) {
            return $this->getDefaultValues();
        }

        return $config;
    }

    /**
     * Update heatmap configuration
     * 
     * @param array $data Associative array with keys:
     *   - radius_meters (int)
     *   - minimum_reports (int)
     *   - low_density_color (string, hex)
     *   - medium_density_color (string, hex)
     *   - high_density_color (string, hex)
     *   - updated_by (int, user ID)
     *   - setting_id (int, optional, defaults to 1)
     * @return bool True on success, false on failure
     */
    public function updateConfig($data) {
        // Ensure setting_id exists
        $setting_id = $data['setting_id'] ?? 1;

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
        $this->db->bind(':radius_meters', $data['radius_meters']);
        $this->db->bind(':minimum_reports', $data['minimum_reports'] ?? $data['low_min'] ?? 3);
        $this->db->bind(':low_density_color', $data['low_density_color']);
        $this->db->bind(':medium_density_color', $data['medium_density_color']);
        $this->db->bind(':high_density_color', $data['high_density_color']);
        $this->db->bind(':low_min', $data['low_min'] ?? 3);
        $this->db->bind(':low_max', $data['low_max'] ?? 5);
        $this->db->bind(':moderate_min', $data['moderate_min'] ?? 6);
        $this->db->bind(':moderate_max', $data['moderate_max'] ?? 10);
        $this->db->bind(':severe_min', $data['severe_min'] ?? 11);
        $this->db->bind(':updated_by', $data['updated_by']);
        $this->db->bind(':setting_id', $setting_id);

        return $this->db->execute();
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