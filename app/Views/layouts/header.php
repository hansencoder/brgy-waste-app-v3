<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dulong Bayan Waste Reporting</title>
    <?php include __DIR__ . '/theme-scripts.php'; ?>
    <!-- Leaflet CSS and JS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .glassmorphism {
            background: hsl(0 0% 100% / 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid hsl(214 20% 88%);
            box-shadow: 0 10px 40px -10px hsl(215 60% 15% / 0.12);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body {
            background-color: #F8FAFC !important;
        }

        /* Ensure Leaflet Maps Never Bleed Over Modals & Lightboxes */
        .leaflet-container, #mapContainer, #viewReportMap, #reportLocationMap, .map-box {
            isolation: isolate !important;
            z-index: 1 !important;
            position: relative !important;
        }
        .leaflet-pane {
            z-index: 2 !important;
        }
        .leaflet-tile-pane {
            z-index: 2 !important;
        }
        .leaflet-overlay-pane {
            z-index: 3 !important;
        }
        .leaflet-shadow-pane {
            z-index: 3 !important;
        }
        .leaflet-marker-pane {
            z-index: 4 !important;
        }
        .leaflet-tooltip-pane {
            z-index: 5 !important;
        }
        .leaflet-popup-pane {
            z-index: 6 !important;
        }
        .leaflet-top, .leaflet-bottom, .leaflet-control {
            z-index: 8 !important;
        }

        /* Lightbox, Modals, Popups */
        #photoLightboxModal, #imageLightboxModal, #reviewModal, #editModal, #popupSystemContainer, .custom-modal-backdrop {
            z-index: 99999 !important;
            position: fixed !important;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 min-h-screen font-sans antialiased flex flex-col">
<?php include __DIR__ . '/loader.php'; ?>
<?php include __DIR__ . '/popup_system.php'; ?>

