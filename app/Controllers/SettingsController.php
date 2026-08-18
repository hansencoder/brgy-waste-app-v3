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
     * Settings Dashboard – defaults directly to Barangay Information section.
     */
    public function index() {
        header('Location: /brgy-waste-app-v3/public/settings/barangay');
        exit;
    }

    // ============================================================
    // 1. BARANGAY INFORMATION
    // ============================================================

    public function barangay() {
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        // Ensure columns exist
        $cols = ['system_name', 'system_short_name', 'system_motto', 'system_logo'];
        foreach ($cols as $col) {
            try {
                $db->query("SHOW COLUMNS FROM barangays LIKE '$col'");
                if (!$db->single()) {
                    $db->query("ALTER TABLE barangays ADD COLUMN $col VARCHAR(255) DEFAULT NULL");
                    $db->execute();
                }
            } catch (Exception $e) {}
        }

        // Get existing barangay info (assume only one record, id=1)
        $db->query("SELECT * FROM barangays LIMIT 1");
        $barangay = $db->single();
        if (!$barangay) {
            // Insert default if not exists
            $db->query("INSERT INTO barangays (barangay_name, municipality, province, region, system_name, system_short_name) VALUES ('Dulong Bayan', 'Talavera', 'Nueva Ecija', 'Central Luzon', 'Barangay Waste Management System', 'WasteWatch')");
            $db->execute();
            $db->query("SELECT * FROM barangays LIMIT 1");
            $barangay = $db->single();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $system_name = trim($_POST['system_name'] ?? '');
            $system_short_name = trim($_POST['system_short_name'] ?? '');
            $system_motto = trim($_POST['system_motto'] ?? '');
            $barangay_name = trim($_POST['barangay_name'] ?? '');
            $municipality = trim($_POST['municipality'] ?? '');
            $province = trim($_POST['province'] ?? '');
            $region = trim($_POST['region'] ?? '');
            $official_address = trim($_POST['official_address'] ?? '');
            $contact_number = trim($_POST['contact_number'] ?? '');
            $official_email = trim($_POST['official_email'] ?? '');

            $system_logo_path = $barangay['system_logo'] ?? null;
            $barangay_logo_path = $barangay['barangay_logo'] ?? null;

            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/logos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Handle System Logo Upload
            if (isset($_FILES['system_logo']) && $_FILES['system_logo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['system_logo'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
                if (in_array($ext, $allowed) && $file['size'] <= 5 * 1024 * 1024) {
                    $newFile = 'sys_logo_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFile)) {
                        $system_logo_path = '/uploads/logos/' . $newFile;
                    }
                }
            }

            // Handle Barangay Seal Upload
            if (isset($_FILES['barangay_logo']) && $_FILES['barangay_logo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['barangay_logo'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
                if (in_array($ext, $allowed) && $file['size'] <= 5 * 1024 * 1024) {
                    $newFile = 'brgy_seal_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFile)) {
                        $barangay_logo_path = '/uploads/logos/' . $newFile;
                    }
                }
            }

            $db->query("UPDATE barangays SET 
                system_name = :system_name,
                system_short_name = :system_short_name,
                system_motto = :system_motto,
                system_logo = :system_logo,
                barangay_logo = :barangay_logo,
                barangay_name = :barangay_name,
                municipality = :municipality,
                province = :province,
                region = :region,
                official_address = :official_address,
                contact_number = :contact_number,
                official_email = :official_email
                WHERE barangay_id = :id
            ");
            $db->bind(':system_name', $system_name);
            $db->bind(':system_short_name', $system_short_name);
            $db->bind(':system_motto', $system_motto);
            $db->bind(':system_logo', $system_logo_path);
            $db->bind(':barangay_logo', $barangay_logo_path);
            $db->bind(':barangay_name', $barangay_name);
            $db->bind(':municipality', $municipality);
            $db->bind(':province', $province);
            $db->bind(':region', $region);
            $db->bind(':official_address', $official_address);
            $db->bind(':contact_number', $contact_number);
            $db->bind(':official_email', $official_email);
            $db->bind(':id', $barangay['barangay_id']);

            if ($db->execute()) {
                $data['success'] = 'System branding & barangay details updated successfully!';
                $this->auditModel->logAction($_SESSION['user_id'], 'Update System Branding', 'Settings', 'Updated system logo, name & barangay details', 'success');
                // Refresh data
                $db->query("SELECT * FROM barangays LIMIT 1");
                $barangay = $db->single();
            } else {
                $data['error'] = 'Failed to update system branding details.';
            }
        }

        $data['barangay'] = $barangay;
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

        // Get official barangay boundary and map center
        $barangayModel = $this->model('Barangay');
        $mapConfig = $barangayModel->getMapConfig();
        $data['barangay_boundary'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];

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

        // Ensure all required columns exist in report_generation_settings
        $cols = [
            'header_logo_left' => 'VARCHAR(255) DEFAULT NULL',
            'header_logo_right' => 'VARCHAR(255) DEFAULT NULL',
            'sub_header' => 'VARCHAR(255) DEFAULT NULL',
            'republic_header' => 'VARCHAR(255) DEFAULT "Republic of the Philippines"',
            'office_name' => 'VARCHAR(255) DEFAULT "Office of the Barangay Solid Waste Management Committee"',
            'signatory_approved_name' => 'VARCHAR(255) DEFAULT NULL',
            'signatory_approved_position' => 'VARCHAR(255) DEFAULT "Punong Barangay"'
        ];
        foreach ($cols as $colName => $colDef) {
            try {
                $db->query("SHOW COLUMNS FROM report_generation_settings LIKE '$colName'");
                if (!$db->single()) {
                    $db->query("ALTER TABLE report_generation_settings ADD COLUMN $colName $colDef");
                    $db->execute();
                }
            } catch (Exception $e) {}
        }

        // Fetch Barangay information for fallback logos & context
        $db->query("SELECT * FROM barangays LIMIT 1");
        $data['barangay'] = $db->single() ?: [];

        $db->query("SELECT * FROM report_generation_settings LIMIT 1");
        $settings = $db->single();
        if (!$settings) {
            $db->query("INSERT INTO report_generation_settings (report_header, report_footer, signatory_name, signatory_position, disclaimer, republic_header, sub_header, office_name) 
                        VALUES ('Barangay Dulong Bayan Waste Management Report', 'This report is for official use only.', '', 'Barangay Secretary', '', 'Republic of the Philippines', 'Province of Nueva Ecija · Municipality of Talavera', 'Office of the Barangay Solid Waste Management Committee')");
            $db->execute();
            $db->query("SELECT * FROM report_generation_settings LIMIT 1");
            $settings = $db->single();
        }
        $data['settings'] = $settings;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $republic_header = trim($_POST['republic_header'] ?? 'Republic of the Philippines');
            $sub_header = trim($_POST['sub_header'] ?? '');
            $header = trim($_POST['report_header'] ?? '');
            $office_name = trim($_POST['office_name'] ?? '');
            $footer = trim($_POST['report_footer'] ?? '');
            $signatory = trim($_POST['signatory_name'] ?? '');
            $position = trim($_POST['signatory_position'] ?? '');
            $approved_name = trim($_POST['signatory_approved_name'] ?? '');
            $approved_position = trim($_POST['signatory_approved_position'] ?? '');
            $disclaimer = trim($_POST['disclaimer'] ?? '');

            $logo_left_path = $settings['header_logo_left'] ?? null;
            $logo_right_path = $settings['header_logo_right'] ?? null;

            // Handle Reset Flags
            if (isset($_POST['remove_logo_left']) && $_POST['remove_logo_left'] == '1') {
                $logo_left_path = null;
            }
            if (isset($_POST['remove_logo_right']) && $_POST['remove_logo_right'] == '1') {
                $logo_right_path = null;
            }

            // Upload directory
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/logos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Handle Left Logo Upload (Primary / Barangay Seal)
            if (isset($_FILES['header_logo_left']) && $_FILES['header_logo_left']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['header_logo_left'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
                if (in_array($ext, $allowed) && $file['size'] <= 5 * 1024 * 1024) {
                    $newFile = 'rep_logo_left_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFile)) {
                        $logo_left_path = '/uploads/logos/' . $newFile;
                    }
                }
            }

            // Handle Right Logo Upload (Secondary / Department / System Logo)
            if (isset($_FILES['header_logo_right']) && $_FILES['header_logo_right']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['header_logo_right'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
                if (in_array($ext, $allowed) && $file['size'] <= 5 * 1024 * 1024) {
                    $newFile = 'rep_logo_right_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFile)) {
                        $logo_right_path = '/uploads/logos/' . $newFile;
                    }
                }
            }

            $db->query("UPDATE report_generation_settings SET 
                republic_header = :republic_header,
                sub_header = :sub_header,
                report_header = :header,
                office_name = :office_name,
                report_footer = :footer,
                signatory_name = :signatory,
                signatory_position = :position,
                signatory_approved_name = :approved_name,
                signatory_approved_position = :approved_position,
                disclaimer = :disclaimer,
                header_logo_left = :logo_left,
                header_logo_right = :logo_right,
                updated_by = :updated_by,
                updated_at = NOW()
                WHERE setting_id = :id
            ");
            $db->bind(':republic_header', $republic_header);
            $db->bind(':sub_header', $sub_header);
            $db->bind(':header', $header);
            $db->bind(':office_name', $office_name);
            $db->bind(':footer', $footer);
            $db->bind(':signatory', $signatory);
            $db->bind(':position', $position);
            $db->bind(':approved_name', $approved_name);
            $db->bind(':approved_position', $approved_position);
            $db->bind(':disclaimer', $disclaimer);
            $db->bind(':logo_left', $logo_left_path);
            $db->bind(':logo_right', $logo_right_path);
            $db->bind(':updated_by', $_SESSION['user_id']);
            $db->bind(':id', $settings['setting_id']);
            if ($db->execute()) {
                $data['success'] = 'Report generation & letterhead settings updated successfully.';
                $this->auditModel->logAction($_SESSION['user_id'], 'Update Report Generation Settings', 'Settings', 'Updated dual logo and report letterhead settings', 'success');
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

        // Get active puroks with GeoJSON polygon boundaries
        $db->query("
            SELECT p.purok_id, p.purok_name, ST_AsGeoJSON(pb.polygon_geometry) AS polygon_geometry 
            FROM puroks p
            LEFT JOIN purok_boundaries pb ON p.purok_id = pb.purok_id
            WHERE p.is_active = 1
            ORDER BY p.purok_name
        ");
        $data['puroks'] = $db->resultSet();

        // Get official barangay boundary and map center
        $barangayModel = $this->model('Barangay');
        $mapConfig = $barangayModel->getMapConfig();
        $data['barangay_boundary'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];

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

            // Refresh landmarks and puroks
            $db->query("SELECT * FROM map_landmarks ORDER BY landmark_name");
            $data['landmarks'] = $db->resultSet();

            $db->query("
                SELECT p.purok_id, p.purok_name, ST_AsGeoJSON(pb.polygon_geometry) AS polygon_geometry 
                FROM puroks p
                LEFT JOIN purok_boundaries pb ON p.purok_id = pb.purok_id
                WHERE p.is_active = 1
                ORDER BY p.purok_name
            ");
            $data['puroks'] = $db->resultSet();
        }

        $this->view('settings/landmarks', $data);
    }

    // ============================================================
    // 6. BARANGAY BOUNDARY & LOCATION EDITOR
    // ============================================================

    public function barangay_boundaries() {
        $barangayModel = $this->model('Barangay');
        $db = new Database();
        $data = ['error' => '', 'success' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['save_boundary'])) {
                $polygon_geojson = trim($_POST['polygon_geojson'] ?? '');
                $center_lat = (float)($_POST['center_latitude'] ?? 15.558);
                $center_lng = (float)($_POST['center_longitude'] ?? 120.803);
                $default_zoom = (int)($_POST['default_zoom'] ?? 15);

                if (!empty($polygon_geojson)) {
                    // Normalize and validate GeoJSON structure
                    $geoArray = json_decode($polygon_geojson, true);
                    if ($geoArray && isset($geoArray['type'])) {
                        // Extract geometry if full Feature or FeatureCollection was passed
                        if ($geoArray['type'] === 'Feature' && isset($geoArray['geometry'])) {
                            $geoArray = $geoArray['geometry'];
                        } elseif ($geoArray['type'] === 'FeatureCollection' && !empty($geoArray['features'][0]['geometry'])) {
                            $geoArray = $geoArray['features'][0]['geometry'];
                        }

                        $cleanGeoJson = json_encode($geoArray);
                        $saved = $barangayModel->saveBoundary(1, $cleanGeoJson, $center_lat, $center_lng, $default_zoom, $_SESSION['user_id']);

                        if ($saved) {
                            $data['success'] = 'Barangay boundaries and center location saved successfully! Applied across all user maps.';
                            $this->auditModel->logAction($_SESSION['user_id'], 'Update Barangay Boundary', 'Settings', 'Updated official Barangay boundary polygon and map center', 'success');
                        } else {
                            $data['error'] = 'Database error: Could not save polygon boundary.';
                        }
                    } else {
                        $data['error'] = 'Invalid GeoJSON format. Please ensure valid polygon geometry coordinates.';
                    }
                } else {
                    $data['error'] = 'Please draw or import a polygon boundary before saving.';
                }
            }

            // Handle Reset to Default
            if (isset($_POST['reset_default'])) {
                $defaultGeoJson = json_encode([
                    "type" => "Polygon",
                    "coordinates" => [[
                        [120.8013517, 15.5699279], [120.8008898, 15.569572], [120.8008276, 15.5686578],
                        [120.8006126, 15.5685788], [120.8005542, 15.5678398], [120.8001844, 15.5672858],
                        [120.8000725, 15.5668847], [120.8001665, 15.566531], [120.7995785, 15.5663685],
                        [120.7989717, 15.5657033], [120.7987031, 15.5658025], [120.7984537, 15.5654243],
                        [120.7980956, 15.5652], [120.7977553, 15.5652043], [120.7975135, 15.5652862],
                        [120.7971285, 15.5652259], [120.7964691, 15.5648604], [120.7961709, 15.5643821],
                        [120.795562, 15.5643993], [120.7951681, 15.5637567], [120.7953561, 15.5632478],
                        [120.7952523, 15.562581], [120.7950598, 15.5617529], [120.7950416, 15.5611835],
                        [120.7945939, 15.5608471], [120.7946431, 15.5603295], [120.7943504, 15.5596467],
                        [120.7937415, 15.5597848], [120.7930393, 15.55916], [120.7928646, 15.5570187],
                        [120.7921781, 15.555107], [120.7912123, 15.554853], [120.7913399, 15.5543176],
                        [120.7915605, 15.5533236], [120.7918092, 15.5534046], [120.8001316, 15.5478115],
                        [120.8011058, 15.5481325], [120.8021398, 15.5484701], [120.8027807, 15.5485113],
                        [120.8032508, 15.5489723], [120.8030798, 15.5500426], [120.8038043, 15.5501365],
                        [120.8044282, 15.5502517], [120.8049495, 15.550614], [120.8058211, 15.5508445],
                        [120.8062911, 15.551569], [120.8071584, 15.5520964], [120.8076635, 15.5520903],
                        [120.8081181, 15.5524005], [120.8083454, 15.5523519], [120.8085979, 15.5525708],
                        [120.8088668, 15.5528807], [120.8118007, 15.5512389], [120.8126332, 15.550257],
                        [120.8153176, 15.5523838], [120.817434, 15.549628], [120.8219183, 15.5518119],
                        [120.8232918, 15.5522367], [120.8253946, 15.5516159], [120.8260956, 15.5512188],
                        [120.8281375, 15.5526533], [120.8298546, 15.5518644], [120.8310955, 15.5519514],
                        [120.8335885, 15.5541358], [120.8325752, 15.5557229], [120.8326161, 15.5574083],
                        [120.8332704, 15.5602447], [120.8283841, 15.5650646], [120.8236492, 15.5703491],
                        [120.82189, 15.5689622], [120.8219651, 15.5676998], [120.8203353, 15.5645562],
                        [120.8205697, 15.5594636], [120.8185042, 15.5617437], [120.8149287, 15.5609879],
                        [120.8126889, 15.5623097], [120.8092582, 15.5595308], [120.8032464, 15.5673914],
                        [120.8014669, 15.5699463], [120.8013517, 15.5699279]
                    ]]
                ]);
                $barangayModel->saveBoundary(1, $defaultGeoJson, 15.55800000, 120.80300000, 15, $_SESSION['user_id']);
                $data['success'] = 'Barangay boundary reset to official default territory.';
                $this->auditModel->logAction($_SESSION['user_id'], 'Reset Barangay Boundary', 'Settings', 'Reset boundary to default coordinates', 'success');
            }
        }

        // Load configuration and data
        $mapConfig = $barangayModel->getMapConfig(1);
        $data['boundary'] = $mapConfig['boundary'];
        $data['boundary_geojson'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];
        $data['puroks'] = $mapConfig['puroks'];
        $data['barangay'] = $barangayModel->getInfo();

        $this->view('settings/barangay_boundaries', $data);
    }

    // ============================================================
    // 7. PUROK BOUNDARY EDITOR
    // ============================================================

    public function purok_boundaries() {
        $db = new Database();
        $barangayModel = $this->model('Barangay');
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

        // Pass master barangay boundary and center for reference
        $mapConfig = $barangayModel->getMapConfig();
        $data['barangay_boundary'] = $mapConfig['boundary_geojson'];
        $data['map_center'] = $mapConfig['center'];

        $this->view('settings/purok_boundaries', $data);
    }

    // ============================================================
    // 8. BARANGAY RULES & PENALTIES
    // ============================================================

    public function penalty_rules() {
        $db   = new Database();
        $data = ['error' => '', 'success' => ''];

        // Auto-migrate: create table if not exists
        $db->query("CREATE TABLE IF NOT EXISTS penalty_rules (
            rule_id     INT AUTO_INCREMENT PRIMARY KEY,
            offense_no  INT NOT NULL DEFAULT 0,
            title       VARCHAR(255) NOT NULL,
            description TEXT,
            legal_ref   VARCHAR(150),
            fine_range  VARCHAR(150),
            alt_penalty VARCHAR(255),
            is_active   TINYINT(1) NOT NULL DEFAULT 1,
            sort_order  INT NOT NULL DEFAULT 0,
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $db->execute();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ── Add Rule ────────────────────────────────────────
            if (isset($_POST['add_rule'])) {
                $offense_no  = (int)($_POST['offense_no']  ?? 0);
                $title       = trim($_POST['title']       ?? '');
                $description = trim($_POST['description'] ?? '');
                $legal_ref   = trim($_POST['legal_ref']   ?? '');
                $fine_range  = trim($_POST['fine_range']  ?? '');
                $alt_penalty = trim($_POST['alt_penalty'] ?? '');
                if (!empty($title)) {
                    $db->query("INSERT INTO penalty_rules (offense_no, title, description, legal_ref, fine_range, alt_penalty) VALUES (:offense_no, :title, :desc, :legal_ref, :fine_range, :alt_penalty)");
                    $db->bind(':offense_no',  $offense_no);
                    $db->bind(':title',       $title);
                    $db->bind(':desc',        $description);
                    $db->bind(':legal_ref',   $legal_ref);
                    $db->bind(':fine_range',  $fine_range);
                    $db->bind(':alt_penalty', $alt_penalty);
                    if ($db->execute()) {
                        $data['success'] = "Rule '{$title}' added successfully.";
                        $this->auditModel->logAction($_SESSION['user_id'], 'Add Penalty Rule', 'Settings', "Added rule: $title", 'success');
                    } else {
                        $data['error'] = 'Failed to add rule.';
                    }
                } else {
                    $data['error'] = 'Rule title is required.';
                }
            }

            // ── Edit Rule ────────────────────────────────────────
            if (isset($_POST['edit_rule'])) {
                $rule_id     = (int)($_POST['rule_id']    ?? 0);
                $offense_no  = (int)($_POST['offense_no'] ?? 0);
                $title       = trim($_POST['title']       ?? '');
                $description = trim($_POST['description'] ?? '');
                $legal_ref   = trim($_POST['legal_ref']   ?? '');
                $fine_range  = trim($_POST['fine_range']  ?? '');
                $alt_penalty = trim($_POST['alt_penalty'] ?? '');
                $is_active   = isset($_POST['is_active']) ? 1 : 0;
                if ($rule_id && !empty($title)) {
                    $db->query("UPDATE penalty_rules SET offense_no=:offense_no, title=:title, description=:desc, legal_ref=:legal_ref, fine_range=:fine_range, alt_penalty=:alt_penalty, is_active=:is_active WHERE rule_id=:rule_id");
                    $db->bind(':offense_no',  $offense_no);
                    $db->bind(':title',       $title);
                    $db->bind(':desc',        $description);
                    $db->bind(':legal_ref',   $legal_ref);
                    $db->bind(':fine_range',  $fine_range);
                    $db->bind(':alt_penalty', $alt_penalty);
                    $db->bind(':is_active',   $is_active);
                    $db->bind(':rule_id',     $rule_id);
                    if ($db->execute()) {
                        $data['success'] = "Rule updated.";
                        $this->auditModel->logAction($_SESSION['user_id'], 'Edit Penalty Rule', 'Settings', "Edited rule ID $rule_id", 'success');
                    } else {
                        $data['error'] = 'Failed to update rule.';
                    }
                }
            }

            // ── Delete Rule ──────────────────────────────────────
            if (isset($_POST['delete_rule'])) {
                $rule_id = (int)($_POST['rule_id'] ?? 0);
                if ($rule_id) {
                    $db->query("DELETE FROM penalty_rules WHERE rule_id = :rule_id");
                    $db->bind(':rule_id', $rule_id);
                    if ($db->execute()) {
                        $data['success'] = 'Rule deleted.';
                        $this->auditModel->logAction($_SESSION['user_id'], 'Delete Penalty Rule', 'Settings', "Deleted rule ID $rule_id", 'success');
                    } else {
                        $data['error'] = 'Failed to delete rule.';
                    }
                }
            }
        }

        $db->query("SELECT * FROM penalty_rules ORDER BY offense_no ASC, sort_order ASC, rule_id ASC");
        $data['rules'] = $db->resultSet();
        $this->view('settings/penalty_rules', $data);
    }

    // ============================================================
    // 9. ROLE MANAGEMENT
    // ============================================================

    public function role_management() {
        $db   = new Database();
        $data = ['error' => '', 'success' => ''];

        // Auto-migrate roles table columns
        foreach (['permissions JSON DEFAULT NULL', 'is_custom TINYINT(1) DEFAULT 0', 'description VARCHAR(255) DEFAULT NULL', 'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP'] as $colDef) {
            $col = explode(' ', $colDef)[0];
            try {
                $db->query("SHOW COLUMNS FROM roles LIKE '$col'");
                if (!$db->single()) {
                    $db->query("ALTER TABLE roles ADD COLUMN $colDef");
                    $db->execute();
                }
            } catch (Exception $e) {}
        }

        // Permission catalogue (used by view too via $data)
        $permissionGroups = [
            'Reports' => [
                'view_reports'         => 'View Reports',
                'manage_report_status' => 'Manage Report Status',
                'delete_reports'       => 'Delete Reports',
                'export_reports'       => 'Export Reports',
            ],
            'Residents & Accounts' => [
                'view_residents'   => 'View Residents',
                'manage_residents' => 'Manage / Edit Residents',
                'suspend_residents'=> 'Suspend / Deactivate Accounts',
            ],
            'Schedules' => [
                'view_schedules'   => 'View Schedules',
                'manage_schedules' => 'Manage Schedules',
                'delete_schedules' => 'Delete Schedules',
            ],
            'Announcements' => [
                'view_announcements'   => 'View Announcements',
                'manage_announcements' => 'Manage Announcements',
                'delete_announcements' => 'Delete Announcements',
            ],
            'Analytics & Reports' => [
                'view_analytics'   => 'View Analytics',
                'export_analytics' => 'Export Analytics',
            ],
            'Settings' => [
                'view_settings'   => 'View Settings',
                'manage_settings' => 'Manage Settings',
            ],
            'Audit Logs' => [
                'view_audit_logs' => 'View Audit Logs',
            ],
        ];
        $data['permissionGroups'] = $permissionGroups;

        // System roles that cannot be edited/deleted
        $systemRoles = ['administrator', 'supervisor', 'resident'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ── Add Role ─────────────────────────────────────────
            if (isset($_POST['add_role'])) {
                $role_name   = trim($_POST['role_name']   ?? '');
                $description = trim($_POST['description'] ?? '');
                $perms       = $_POST['permissions'] ?? [];
                if (!empty($role_name)) {
                    // Check duplicate
                    $db->query("SELECT role_id FROM roles WHERE LOWER(role_name) = LOWER(:name)");
                    $db->bind(':name', $role_name);
                    if ($db->single()) {
                        $data['error'] = "A role named '{$role_name}' already exists.";
                    } else {
                        $permsJson = json_encode(array_values($perms));
                        $db->query("INSERT INTO roles (role_name, description, permissions, is_custom) VALUES (:name, :desc, :perms, 1)");
                        $db->bind(':name',  $role_name);
                        $db->bind(':desc',  $description);
                        $db->bind(':perms', $permsJson);
                        if ($db->execute()) {
                            $data['success'] = "Role '{$role_name}' created.";
                            $this->auditModel->logAction($_SESSION['user_id'], 'Add Role', 'Settings', "Created role: $role_name", 'success');
                        } else {
                            $data['error'] = 'Failed to create role.';
                        }
                    }
                } else {
                    $data['error'] = 'Role name is required.';
                }
            }

            // ── Edit Role ─────────────────────────────────────────
            if (isset($_POST['edit_role'])) {
                $role_id     = (int)($_POST['role_id']    ?? 0);
                $description = trim($_POST['description'] ?? '');
                $perms       = $_POST['permissions'] ?? [];
                if ($role_id) {
                    // Verify it's a custom role
                    $db->query("SELECT role_name, is_custom FROM roles WHERE role_id = :id");
                    $db->bind(':id', $role_id);
                    $roleRow = $db->single();
                    if ($roleRow && (!in_array(strtolower($roleRow['role_name']), $systemRoles))) {
                        $permsJson = json_encode(array_values($perms));
                        $db->query("UPDATE roles SET description=:desc, permissions=:perms WHERE role_id=:id");
                        $db->bind(':desc',  $description);
                        $db->bind(':perms', $permsJson);
                        $db->bind(':id',    $role_id);
                        if ($db->execute()) {
                            $data['success'] = 'Role updated.';
                            $this->auditModel->logAction($_SESSION['user_id'], 'Edit Role', 'Settings', "Edited role ID $role_id", 'success');
                        } else {
                            $data['error'] = 'Failed to update role.';
                        }
                    } else {
                        $data['error'] = 'System roles cannot be modified.';
                    }
                }
            }

            // ── Delete Role ───────────────────────────────────────
            if (isset($_POST['delete_role'])) {
                $role_id = (int)($_POST['role_id'] ?? 0);
                if ($role_id) {
                    $db->query("SELECT role_name FROM roles WHERE role_id = :id");
                    $db->bind(':id', $role_id);
                    $roleRow = $db->single();
                    if ($roleRow && !in_array(strtolower($roleRow['role_name']), $systemRoles)) {
                        $db->query("DELETE FROM roles WHERE role_id = :id AND LOWER(role_name) NOT IN ('administrator','supervisor','resident')");
                        $db->bind(':id', $role_id);
                        if ($db->execute()) {
                            $data['success'] = 'Role deleted.';
                            $this->auditModel->logAction($_SESSION['user_id'], 'Delete Role', 'Settings', "Deleted role ID $role_id", 'success');
                        } else {
                            $data['error'] = 'Failed to delete role.';
                        }
                    } else {
                        $data['error'] = 'System roles cannot be deleted.';
                    }
                }
            }
        }

        $db->query("SELECT * FROM roles ORDER BY role_id ASC");
        $data['roles'] = $db->resultSet();
        $data['systemRoles'] = $systemRoles;
        $this->view('settings/role_management', $data);
    }

    // ============================================================
    // 10. IMPORTANT NOTES ON GARBAGE COLLECTION
    // ============================================================

    public function collection_notes() {
        $db   = new Database();
        $data = ['error' => '', 'success' => ''];

        // Auto-migrate
        $db->query("CREATE TABLE IF NOT EXISTS collection_notes (
            note_id    INT AUTO_INCREMENT PRIMARY KEY,
            title      VARCHAR(255) NOT NULL,
            content    TEXT NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            is_active  TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $db->execute();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ── Add Note ──────────────────────────────────────────
            if (isset($_POST['add_note'])) {
                $title   = trim($_POST['title']   ?? '');
                $content = trim($_POST['content'] ?? '');
                if (!empty($title) && !empty($content)) {
                    // Default sort_order = max + 1
                    $db->query("SELECT COALESCE(MAX(sort_order),0)+1 AS next_order FROM collection_notes");
                    $nextOrder = (int)($db->single()['next_order'] ?? 1);
                    $db->query("INSERT INTO collection_notes (title, content, sort_order) VALUES (:title, :content, :sort_order)");
                    $db->bind(':title',      $title);
                    $db->bind(':content',    $content);
                    $db->bind(':sort_order', $nextOrder);
                    if ($db->execute()) {
                        $data['success'] = "Note '{$title}' added.";
                        $this->auditModel->logAction($_SESSION['user_id'], 'Add Collection Note', 'Settings', "Added note: $title", 'success');
                    } else {
                        $data['error'] = 'Failed to add note.';
                    }
                } else {
                    $data['error'] = 'Title and content are required.';
                }
            }

            // ── Edit Note ─────────────────────────────────────────
            if (isset($_POST['edit_note'])) {
                $note_id   = (int)($_POST['note_id']    ?? 0);
                $title     = trim($_POST['title']       ?? '');
                $content   = trim($_POST['content']     ?? '');
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                if ($note_id && !empty($title) && !empty($content)) {
                    $db->query("UPDATE collection_notes SET title=:title, content=:content, is_active=:is_active WHERE note_id=:note_id");
                    $db->bind(':title',     $title);
                    $db->bind(':content',   $content);
                    $db->bind(':is_active', $is_active);
                    $db->bind(':note_id',   $note_id);
                    if ($db->execute()) {
                        $data['success'] = 'Note updated.';
                        $this->auditModel->logAction($_SESSION['user_id'], 'Edit Collection Note', 'Settings', "Edited note ID $note_id", 'success');
                    } else {
                        $data['error'] = 'Failed to update note.';
                    }
                }
            }

            // ── Delete Note ───────────────────────────────────────
            if (isset($_POST['delete_note'])) {
                $note_id = (int)($_POST['note_id'] ?? 0);
                if ($note_id) {
                    $db->query("DELETE FROM collection_notes WHERE note_id = :note_id");
                    $db->bind(':note_id', $note_id);
                    if ($db->execute()) {
                        $data['success'] = 'Note deleted.';
                        $this->auditModel->logAction($_SESSION['user_id'], 'Delete Collection Note', 'Settings', "Deleted note ID $note_id", 'success');
                    } else {
                        $data['error'] = 'Failed to delete note.';
                    }
                }
            }

            // ── Update Sort Order ─────────────────────────────────
            if (isset($_POST['update_order'])) {
                $order = $_POST['note_order'] ?? [];
                foreach ($order as $pos => $note_id) {
                    $db->query("UPDATE collection_notes SET sort_order=:pos WHERE note_id=:note_id");
                    $db->bind(':pos',     (int)$pos);
                    $db->bind(':note_id', (int)$note_id);
                    $db->execute();
                }
                $data['success'] = 'Order updated.';
            }
        }

        $db->query("SELECT * FROM collection_notes ORDER BY sort_order ASC, note_id ASC");
        $data['notes'] = $db->resultSet();
        $this->view('settings/collection_notes', $data);
    }

    // ============================================================
    // SYSTEM AVAILABILITY / MAINTENANCE MODE
    // ============================================================

    public function system_availability() {
        require_once dirname(__DIR__) . '/Models/SystemMaintenance.php';
        $maintenanceModel = new SystemMaintenance();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $action  = trim($_POST['action'] ?? '');
            $userId  = $_SESSION['user_id'];
            $ip      = $_SERVER['REMOTE_ADDR'] ?? null;

            // Common input sanitisation
            $type    = in_array($_POST['maintenance_type'] ?? '', ['scheduled', 'emergency'])
                       ? $_POST['maintenance_type'] : 'scheduled';
            $message = trim($_POST['maintenance_message'] ?? '');
            $reason  = trim($_POST['reason'] ?? '');
            $startAt = !empty($_POST['start_at']) ? date('Y-m-d H:i:s', strtotime($_POST['start_at'])) : null;
            $endAt   = !empty($_POST['end_at'])   ? date('Y-m-d H:i:s', strtotime($_POST['end_at']))   : null;

            // Validate message is non-empty for activate actions
            if (in_array($action, ['activate', 'emergency_lockdown', 'save_settings'])) {
                if (empty($message)) {
                    echo json_encode(['success' => false, 'message' => 'Maintenance message cannot be empty.']);
                    exit;
                }
            }

            // Validate end_at >= start_at
            if ($startAt && $endAt && strtotime($endAt) <= strtotime($startAt)) {
                echo json_encode(['success' => false, 'message' => 'End date/time must be after the start date/time.']);
                exit;
            }

            $currentStatus = $maintenanceModel->getStatus();
            $prevMode      = (int)($currentStatus['maintenance_mode'] ?? 0);

            $data = [
                'maintenance_type'    => $type,
                'maintenance_message' => $message,
                'reason'              => $reason,
                'start_at'            => $startAt,
                'end_at'              => $endAt,
                'previous_status'     => $prevMode,
                'new_status'          => $prevMode,
            ];

            switch ($action) {

                // ── Save settings without changing active state ──────────
                case 'save_settings':
                    $maintenanceModel->saveSettings($data, $userId);
                    $data['new_status'] = $prevMode;
                    $maintenanceModel->logHistory('UPDATE_MAINTENANCE_SETTINGS', $data, $userId, $ip);
                    $this->auditModel->logAction($userId, 'Update Maintenance Settings', 'SystemMaintenance', "Updated maintenance settings (type: $type)", 'success');
                    echo json_encode(['success' => true, 'message' => 'Maintenance settings saved successfully.']);
                    break;

                // ── Activate maintenance mode ────────────────────────────
                case 'activate':
                    $data['new_status'] = 1;
                    $maintenanceModel->activate($data, $userId);
                    $maintenanceModel->logHistory('ENABLE_MAINTENANCE_MODE', $data, $userId, $ip);
                    $this->auditModel->logAction($userId, 'Enable Maintenance Mode', 'SystemMaintenance', "Maintenance mode activated (type: $type). Reason: $reason", 'success');
                    echo json_encode(['success' => true, 'message' => 'Maintenance mode has been activated. Non-admin users are now blocked.']);
                    break;

                // ── Deactivate maintenance mode ──────────────────────────
                case 'deactivate':
                    $data['new_status'] = 0;
                    $maintenanceModel->deactivate($userId);
                    $maintenanceModel->logHistory('DISABLE_MAINTENANCE_MODE', $data, $userId, $ip);
                    $this->auditModel->logAction($userId, 'Disable Maintenance Mode', 'SystemMaintenance', 'Maintenance mode deactivated. System restored to operational.', 'success');
                    echo json_encode(['success' => true, 'message' => 'System is now operational. All users can access the system.']);
                    break;

                // ── Emergency lockdown ───────────────────────────────────
                case 'emergency_lockdown':
                    // Require explicit confirmation field
                    if (empty($_POST['confirm_emergency']) || $_POST['confirm_emergency'] !== '1') {
                        echo json_encode(['success' => false, 'message' => 'Emergency lockdown requires explicit confirmation.']);
                        exit;
                    }
                    $data['maintenance_type'] = 'emergency';
                    $data['new_status']       = 1;
                    $maintenanceModel->activate($data, $userId);
                    $maintenanceModel->logHistory('ENABLE_EMERGENCY_LOCKDOWN', $data, $userId, $ip);
                    $this->auditModel->logAction($userId, 'Enable Emergency Lockdown', 'SystemMaintenance', "EMERGENCY LOCKDOWN activated. Reason: $reason", 'success');
                    echo json_encode(['success' => true, 'message' => 'EMERGENCY LOCKDOWN is now active. All non-admin access is immediately blocked.']);
                    break;

                // ── Deactivate emergency lockdown ────────────────────────
                case 'deactivate_emergency':
                    $data['new_status'] = 0;
                    $maintenanceModel->deactivate($userId);
                    $maintenanceModel->logHistory('DISABLE_EMERGENCY_LOCKDOWN', $data, $userId, $ip);
                    $this->auditModel->logAction($userId, 'Disable Emergency Lockdown', 'SystemMaintenance', 'Emergency lockdown deactivated. System restored to operational.', 'success');
                    echo json_encode(['success' => true, 'message' => 'Emergency lockdown lifted. System is now operational.']);
                    break;

                default:
                    echo json_encode(['success' => false, 'message' => 'Unknown action.']);
                    break;
            }
            exit;
        }

        // GET — render the settings view
        $status  = $maintenanceModel->getStatus();
        $history = $maintenanceModel->getHistory(50);

        $data = [
            'status'  => $status,
            'history' => $history,
        ];
        $this->view('settings/system_availability', $data);
    }

    /**
     * AJAX endpoint: returns maintenance history as JSON.
     */
    public function maintenanceHistory() {
        require_once dirname(__DIR__) . '/Models/SystemMaintenance.php';
        $maintenanceModel = new SystemMaintenance();
        $limit   = min((int)($_GET['limit'] ?? 50), 200);
        $history = $maintenanceModel->getHistory($limit);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'history' => $history]);
        exit;
    }
}
