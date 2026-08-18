<?php

/**
 * MaintenanceController
 * Handles the public maintenance page shown to blocked users.
 * No authentication required — must be accessible to everyone.
 */
class MaintenanceController extends Controller {

    public function __construct() {
        // No auth check — this page is publicly accessible
    }

    /**
     * Display the maintenance page.
     * Fetches current maintenance config for display.
     */
    public function index() {
        require_once dirname(__DIR__) . '/Models/SystemMaintenance.php';
        $maintenanceModel = new SystemMaintenance();
        $status = $maintenanceModel->getStatus();

        // If maintenance is not actually active, redirect to home
        if (!$maintenanceModel->isMaintenanceActive()) {
            header('Location: ' . app_url(''));
            exit;
        }

        // Get barangay name for branding
        $db = new Database();
        $db->query("SELECT system_name, system_short_name, barangay_name, system_logo, barangay_logo FROM barangays LIMIT 1");
        $barangay = $db->single();

        $data = [
            'status'    => $status,
            'barangay'  => $barangay,
        ];

        $this->view('maintenance/index', $data);
    }
}
