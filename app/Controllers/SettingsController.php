<?php

class SettingsController extends Controller {
    private $userModel;
    private $auditModel;

    public function __construct() {
        // Check if user is logged in and is administrator
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'administrator') {
            header('Location: /brgy-waste-app-v3/public/index.php?url=auth');
            exit;
        }
        $this->userModel = $this->model('User');
        $this->auditModel = $this->model('AuditLog');
    }

    /**
     * Settings Dashboard – list all settings sections.
     */
    public function index() {
        $data = [];
        $this->view('settings/index', $data);
    }

    // ============================================================
    // 1. BARANGAY INFORMATION
    // ============================================================

    public function barangay() {
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        // Get existing barangay info (assume only one record, id=1)
        $db->query("SELECT * FROM barangays LIMIT 1");
        $barangay = $db->single();
        if (!$barangay) {
            // Insert default if not exists
            $db->query("INSERT INTO barangays (barangay_name, municipality, province, region) VALUES ('Dulong Bayan', 'Talavera', 'Nueva Ecija', 'Central Luzon')");
            $db->execute();
            $db->query("SELECT * FROM barangays LIMIT 1");
            $barangay = $db->single();
        }
        $data['barangay'] = $barangay;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $barangay_name = trim($_POST['barangay_name'] ?? '');
            $municipality = trim($_POST['municipality'] ?? '');
            $province = trim($_POST['province'] ?? '');
            $region = trim($_POST['region'] ?? '');
            $official_address = trim($_POST['official_address'] ?? '');
            $contact_number = trim($_POST['contact_number'] ?? '');
            $official_email = trim($_POST['official_email'] ?? '');

            $db->query("UPDATE barangays SET 
                barangay_name = :barangay_name,
                municipality = :municipality,
                province = :province,
                region = :region,
                official_address = :official_address,
                contact_number = :contact_number,
                official_email = :official_email
                WHERE barangay_id = :id
            ");
            $db->bind(':barangay_name', $barangay_name);
            $db->bind(':municipality', $municipality);
            $db->bind(':province', $province);
            $db->bind(':region', $region);
            $db->bind(':official_address', $official_address);
            $db->bind(':contact_number', $contact_number);
            $db->bind(':official_email', $official_email);
            $db->bind(':id', $barangay['barangay_id']);
            if ($db->execute()) {
                $data['success'] = 'Barangay information updated successfully.';
                $this->auditModel->logAction($_SESSION['user_id'], 'Update Barangay Info', 'Settings', 'Updated barangay information', 'success');
                // Refresh data
                $db->query("SELECT * FROM barangays LIMIT 1");
                $data['barangay'] = $db->single();
            } else {
                $data['error'] = 'Failed to update barangay information.';
            }
        }

        $this->view('settings/barangay', $data);
    }

    // ============================================================
    // 2. WASTE REPORT FORM SETTINGS
    // ============================================================

    public function report_form() {
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        // Get current settings (assume only one record, id=1)
        $db->query("SELECT * FROM report_settings LIMIT 1");
        $settings = $db->single();
        if (!$settings) {
            // Insert default
            $db->query("INSERT INTO report_settings (photo_required, allowed_file_types, max_upload_size, duplicate_distance, duplicate_time_window, max_reports_per_day, enable_remarks, remarks_character_limit) 
                        VALUES (1, 'jpg,jpeg,png', 5242880, 50, 7, 10, 1, 500)");
            $db->execute();
            $db->query("SELECT * FROM report_settings LIMIT 1");
            $settings = $db->single();
        }
        $data['settings'] = $settings;

        // Get categories, quantities, conditions for management
        $db->query("SELECT * FROM waste_categories ORDER BY category_name");
        $data['categories'] = $db->resultSet();
        $db->query("SELECT * FROM estimated_quantities ORDER BY sort_order");
        $data['quantities'] = $db->resultSet();
        $db->query("SELECT * FROM waste_conditions ORDER BY condition_name");
        $data['conditions'] = $db->resultSet();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Update settings
            if (isset($_POST['update_settings'])) {
                $photo_required = isset($_POST['photo_required']) ? 1 : 0;
                $allowed_file_types = trim($_POST['allowed_file_types'] ?? 'jpg,jpeg,png');
                $max_upload_size = (int)($_POST['max_upload_size'] ?? 5242880);
                $duplicate_distance = (int)($_POST['duplicate_distance'] ?? 50);
                $duplicate_time_window = (int)($_POST['duplicate_time_window'] ?? 7);
                $max_reports_per_day = (int)($_POST['max_reports_per_day'] ?? 10);
                $enable_remarks = isset($_POST['enable_remarks']) ? 1 : 0;
                $remarks_character_limit = (int)($_POST['remarks_character_limit'] ?? 500);

                $db->query("UPDATE report_settings SET 
                    photo_required = :photo_required,
                    allowed_file_types = :allowed_file_types,
                    max_upload_size = :max_upload_size,
                    duplicate_distance = :duplicate_distance,
                    duplicate_time_window = :duplicate_time_window,
                    max_reports_per_day = :max_reports_per_day,
                    enable_remarks = :enable_remarks,
                    remarks_character_limit = :remarks_character_limit,
                    updated_by = :updated_by,
                    updated_at = NOW()
                    WHERE setting_id = :id
                ");
                $db->bind(':photo_required', $photo_required);
                $db->bind(':allowed_file_types', $allowed_file_types);
                $db->bind(':max_upload_size', $max_upload_size);
                $db->bind(':duplicate_distance', $duplicate_distance);
                $db->bind(':duplicate_time_window', $duplicate_time_window);
                $db->bind(':max_reports_per_day', $max_reports_per_day);
                $db->bind(':enable_remarks', $enable_remarks);
                $db->bind(':remarks_character_limit', $remarks_character_limit);
                $db->bind(':updated_by', $_SESSION['user_id']);
                $db->bind(':id', $settings['setting_id']);
                if ($db->execute()) {
                    $data['success'] = 'Report form settings updated.';
                    $this->auditModel->logAction($_SESSION['user_id'], 'Update Report Form Settings', 'Settings', 'Updated report form settings', 'success');
                    // Refresh
                    $db->query("SELECT * FROM report_settings LIMIT 1");
                    $data['settings'] = $db->single();
                } else {
                    $data['error'] = 'Failed to update settings.';
                }
            }

            // Add Category
            if (isset($_POST['add_category'])) {
                $name = trim($_POST['category_name'] ?? '');
                $desc = trim($_POST['category_description'] ?? '');
                if (!empty($name)) {
                    $db->query("INSERT INTO waste_categories (category_name, description) VALUES (:name, :desc)");
                    $db->bind(':name', $name);
                    $db->bind(':desc', $desc);
                    if ($db->execute()) {
                        $data['success'] = 'Category added.';
                        $this->auditModel->logAction($_SESSION['user_id'], 'Add Waste Category', 'Settings', "Added category: $name", 'success');
                    } else {
                        $data['error'] = 'Failed to add category.';
                    }
                }
            }

            // Edit Category
            if (isset($_POST['edit_category'])) {
                $id = (int)$_POST['category_id'];
                $name = trim($_POST['category_name'] ?? '');
                $desc = trim($_POST['category_description'] ?? '');
                $active = isset($_POST['category_active']) ? 1 : 0;
                if ($id && !empty($name)) {
                    $db->query("UPDATE waste_categories SET category_name = :name, description = :desc, is_active = :active WHERE category_id = :id");
                    $db->bind(':name', $name);
                    $db->bind(':desc', $desc);
                    $db->bind(':active', $active);
                    $db->bind(':id', $id);
                    if ($db->execute()) {
                        $data['success'] = 'Category updated.';
                        $this->auditModel->logAction($_SESSION['user_id'], 'Edit Waste Category', 'Settings', "Updated category ID $id", 'success');
                    } else {
                        $data['error'] = 'Failed to update category.';
                    }
                }
            }

            // Delete Category
            if (isset($_POST['delete_category'])) {
                $id = (int)$_POST['category_id'];
                $db->query("DELETE FROM waste_categories WHERE category_id = :id");
                $db->bind(':id', $id);
                if ($db->execute()) {
                    $data['success'] = 'Category deleted.';
                    $this->auditModel->logAction($_SESSION['user_id'], 'Delete Waste Category', 'Settings', "Deleted category ID $id", 'success');
                } else {
                    $data['error'] = 'Failed to delete category.';
                }
            }

            // Similar for quantities and conditions (you can add later)
            // Refresh data after POST
            $db->query("SELECT * FROM waste_categories ORDER BY category_name");
            $data['categories'] = $db->resultSet();
            $db->query("SELECT * FROM estimated_quantities ORDER BY sort_order");
            $data['quantities'] = $db->resultSet();
            $db->query("SELECT * FROM waste_conditions ORDER BY condition_name");
            $data['conditions'] = $db->resultSet();
        }

        $this->view('settings/report_form', $data);
    }

    // ============================================================
    // 3. HEATMAP CONFIGURATION
    // ============================================================

    public function heatmap() {
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        $db->query("SELECT * FROM heatmap_settings LIMIT 1");
        $settings = $db->single();
        if (!$settings) {
            $db->query("INSERT INTO heatmap_settings (radius_meters, minimum_reports, low_density_color, medium_density_color, high_density_color) 
                        VALUES (50, 3, '#FDE68A', '#F97316', '#EF4444')");
            $db->execute();
            $db->query("SELECT * FROM heatmap_settings LIMIT 1");
            $settings = $db->single();
        }
        $data['settings'] = $settings;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $radius = (int)($_POST['radius_meters'] ?? 50);
            $min_reports = (int)($_POST['minimum_reports'] ?? 3);
            $low = trim($_POST['low_density_color'] ?? '#FDE68A');
            $medium = trim($_POST['medium_density_color'] ?? '#F97316');
            $high = trim($_POST['high_density_color'] ?? '#EF4444');

            $db->query("UPDATE heatmap_settings SET 
                radius_meters = :radius,
                minimum_reports = :min_reports,
                low_density_color = :low,
                medium_density_color = :medium,
                high_density_color = :high,
                updated_by = :updated_by,
                updated_at = NOW()
                WHERE setting_id = :id
            ");
            $db->bind(':radius', $radius);
            $db->bind(':min_reports', $min_reports);
            $db->bind(':low', $low);
            $db->bind(':medium', $medium);
            $db->bind(':high', $high);
            $db->bind(':updated_by', $_SESSION['user_id']);
            $db->bind(':id', $settings['setting_id']);
            if ($db->execute()) {
                $data['success'] = 'Heatmap settings updated.';
                $this->auditModel->logAction($_SESSION['user_id'], 'Update Heatmap Settings', 'Settings', 'Updated heatmap settings', 'success');
                $db->query("SELECT * FROM heatmap_settings LIMIT 1");
                $data['settings'] = $db->single();
            } else {
                $data['error'] = 'Failed to update heatmap settings.';
            }
        }

        $this->view('settings/heatmap', $data);
    }

    // ============================================================
    // 4. REPORT GENERATION SETTINGS
    // ============================================================

    public function report_generation() {
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        $db->query("SELECT * FROM report_generation_settings LIMIT 1");
        $settings = $db->single();
        if (!$settings) {
            $db->query("INSERT INTO report_generation_settings (report_header, report_footer, signatory_name, signatory_position, disclaimer) 
                        VALUES ('Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', '')");
            $db->execute();
            $db->query("SELECT * FROM report_generation_settings LIMIT 1");
            $settings = $db->single();
        }
        $data['settings'] = $settings;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $header = trim($_POST['report_header'] ?? '');
            $footer = trim($_POST['report_footer'] ?? '');
            $signatory = trim($_POST['signatory_name'] ?? '');
            $position = trim($_POST['signatory_position'] ?? '');
            $disclaimer = trim($_POST['disclaimer'] ?? '');

            $db->query("UPDATE report_generation_settings SET 
                report_header = :header,
                report_footer = :footer,
                signatory_name = :signatory,
                signatory_position = :position,
                disclaimer = :disclaimer,
                updated_by = :updated_by,
                updated_at = NOW()
                WHERE setting_id = :id
            ");
            $db->bind(':header', $header);
            $db->bind(':footer', $footer);
            $db->bind(':signatory', $signatory);
            $db->bind(':position', $position);
            $db->bind(':disclaimer', $disclaimer);
            $db->bind(':updated_by', $_SESSION['user_id']);
            $db->bind(':id', $settings['setting_id']);
            if ($db->execute()) {
                $data['success'] = 'Report generation settings updated.';
                $this->auditModel->logAction($_SESSION['user_id'], 'Update Report Generation Settings', 'Settings', 'Updated report generation settings', 'success');
                $db->query("SELECT * FROM report_generation_settings LIMIT 1");
                $data['settings'] = $db->single();
            } else {
                $data['error'] = 'Failed to update report generation settings.';
            }
        }

        $this->view('settings/report_generation', $data);
    }

    // ============================================================
    // 5. EDITABLE MAP – LANDMARKS
    // ============================================================

    public function landmarks() {
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        // Get all landmarks
        $db->query("SELECT * FROM map_landmarks ORDER BY landmark_name");
        $data['landmarks'] = $db->resultSet();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Add landmark
            if (isset($_POST['add_landmark'])) {
                $name = trim($_POST['landmark_name'] ?? '');
                $type = trim($_POST['landmark_type'] ?? '');
                $lat = (float)($_POST['latitude'] ?? 0);
                $lng = (float)($_POST['longitude'] ?? 0);
                $desc = trim($_POST['description'] ?? '');
                if (!empty($name) && $lat && $lng) {
                    $db->query("INSERT INTO map_landmarks (landmark_name, landmark_type, latitude, longitude, description, created_by) 
                                VALUES (:name, :type, :lat, :lng, :desc, :created_by)");
                    $db->bind(':name', $name);
                    $db->bind(':type', $type);
                    $db->bind(':lat', $lat);
                    $db->bind(':lng', $lng);
                    $db->bind(':desc', $desc);
                    $db->bind(':created_by', $_SESSION['user_id']);
                    if ($db->execute()) {
                        $data['success'] = 'Landmark added.';
                        $this->auditModel->logAction($_SESSION['user_id'], 'Add Landmark', 'Settings', "Added landmark: $name", 'success');
                    } else {
                        $data['error'] = 'Failed to add landmark.';
                    }
                } else {
                    $data['error'] = 'Name and coordinates are required.';
                }
            }

            // Delete landmark
            if (isset($_POST['delete_landmark'])) {
                $id = (int)$_POST['landmark_id'];
                $db->query("DELETE FROM map_landmarks WHERE landmark_id = :id");
                $db->bind(':id', $id);
                if ($db->execute()) {
                    $data['success'] = 'Landmark deleted.';
                    $this->auditModel->logAction($_SESSION['user_id'], 'Delete Landmark', 'Settings', "Deleted landmark ID $id", 'success');
                } else {
                    $data['error'] = 'Failed to delete landmark.';
                }
            }

            // Refresh landmarks
            $db->query("SELECT * FROM map_landmarks ORDER BY landmark_name");
            $data['landmarks'] = $db->resultSet();
        }

        $this->view('settings/landmarks', $data);
    }

    // ============================================================
    // 6. PUROK BOUNDARY EDITOR (Simplified)
    // ============================================================

    public function purok_boundaries() {
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['save_boundary'])) {
                $purok_id = (int)($_POST['purok_id'] ?? 0);
                $polygon_geojson = $_POST['polygon_geojson'] ?? '';
                if ($purok_id && !empty($polygon_geojson)) {
                    // Check if boundary record exists for this purok_id
                    $db->query("SELECT boundary_id FROM purok_boundaries WHERE purok_id = :purok_id LIMIT 1");
                    $db->bind(':purok_id', $purok_id);
                    $existing = $db->single();

                    if ($existing) {
                        $db->query("UPDATE purok_boundaries 
                                    SET polygon_geometry = ST_GeomFromGeoJSON(:geojson),
                                        updated_by = :updated_by,
                                        updated_at = NOW()
                                    WHERE purok_id = :purok_id");
                        $db->bind(':geojson', $polygon_geojson);
                        $db->bind(':updated_by', $_SESSION['user_id']);
                        $db->bind(':purok_id', $purok_id);
                        $saved = $db->execute();
                    } else {
                        $db->query("INSERT INTO purok_boundaries (purok_id, polygon_geometry, updated_by) 
                                    VALUES (:purok_id, ST_GeomFromGeoJSON(:geojson), :updated_by)");
                        $db->bind(':purok_id', $purok_id);
                        $db->bind(':geojson', $polygon_geojson);
                        $db->bind(':updated_by', $_SESSION['user_id']);
                        $saved = $db->execute();
                    }

                    if ($saved) {
                        $data['success'] = 'Purok boundary saved successfully!';
                        $this->auditModel->logAction($_SESSION['user_id'], 'Update Purok Boundary', 'Settings', "Updated boundary for purok ID $purok_id", 'success');
                    } else {
                        $data['error'] = 'Failed to save boundary to database.';
                    }
                } else {
                    $data['error'] = 'Please select a purok and draw a valid polygon first.';
                }
            }
        }

        // Get puroks with their boundaries formatted as GeoJSON text
        $db->query("
            SELECT p.*, ST_AsGeoJSON(pb.polygon_geometry) AS polygon_geometry 
            FROM puroks p
            LEFT JOIN purok_boundaries pb ON p.purok_id = pb.purok_id
            WHERE p.is_active = 1
            ORDER BY p.purok_name
        ");
        $data['puroks'] = $db->resultSet();

        $this->view('settings/purok_boundaries', $data);
    }
}