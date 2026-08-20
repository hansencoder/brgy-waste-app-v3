<?php
if (!isset($data) || !is_array($data)) {
    $data = [
        'error' => '',
        'success' => '',
        'boundary' => null,
        'boundary_geojson' => null,
        'map_center' => ['lat' => 15.558, 'lng' => 120.803, 'zoom' => 15],
        'puroks' => [],
        'barangay' => []
    ];
}
$brgyName = $data['barangay']['barangay_name'] ?? 'Dulong Bayan';
$municipality = $data['barangay']['municipality'] ?? 'Talavera';
$province = $data['barangay']['province'] ?? 'Nueva Ecija';
$centerLat = (float)($data['map_center']['lat'] ?? 15.558);
$centerLng = (float)($data['map_center']['lng'] ?? 120.803);
$defaultZoom = (int)($data['map_center']['zoom'] ?? 15);
?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Leaflet & Leaflet Draw CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    
    /* Custom Leaflet Tooltip Styling */
    .purok-sub-tooltip {
        background: #0F172A !important;
        color: #F8FAFC !important;
        border: 1px solid #38BDF8 !important;
        border-radius: 8px !important;
        font-weight: 800 !important;
        font-size: 11px !important;
        padding: 3px 8px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
    }
    .brgy-tooltip {
        background: #064E3B !important;
        color: #FFFFFF !important;
        border: 1.5px solid #10B981 !important;
        border-radius: 8px !important;
        font-weight: 900 !important;
        font-size: 12px !important;
        padding: 4px 10px !important;
        box-shadow: 0 4px 14px rgba(6, 78, 59, 0.3) !important;
    }
    .center-pin-marker {
        background: #F59E0B;
        width: 24px;
        height: 24px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid #FFFFFF;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.5);
    }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 w-full flex font-sans antialiased">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <!-- Layout Wrapper -->
    <div class="lg:flex lg:min-h-screen w-full">
        
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <!-- Main Scrollable Canvas -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="<?php echo app_url('settings'); ?>" class="text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Master GIS Boundary
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                                <span>Barangay Boundaries &amp; Location</span>
                                <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg border border-slate-200">
                                    <?php echo htmlspecialchars($brgyName); ?>
                                </span>
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Customize the official barangay perimeter polygon, default map center coordinates, and zoom level applied across all user maps.
                            </p>
                        </div>

                        <!-- Top Quick Action Buttons -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <button type="button" onclick="openGeoJsonModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-extrabold rounded-xl transition border border-slate-300 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                                <span>Import / Export GeoJSON</span>
                            </button>
                            <form action="<?php echo app_url('settings/barangay_boundaries'); ?>" method="POST" onsubmit="return confirm('Are you sure you want to reset the official boundary to default coordinates?');" class="inline">
                                <input type="hidden" name="reset_default" value="1">
                                <button type="submit" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-800 text-sm font-extrabold rounded-xl transition border border-rose-200 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                    <span>Reset Default</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-5 bg-red-50 border-2 border-red-200 text-red-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Content Layout -->
                    <div class="flex flex-col lg:flex-row gap-6 items-start">
                        <?php 
                        $activeTab = 'barangay_boundaries'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <div class="flex-1 min-w-0 space-y-6">
                            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                                
                                <!-- Left Control Panel (4 Cols) -->
                                <div class="xl:col-span-4 space-y-6">
                                    
                                    <!-- 1. Center Location & Coordinates Card -->
                                    <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 shadow-xs space-y-5">
                                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                            <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>
                                                Map Center &amp; Focal Point
                                            </h3>
                                            <span class="text-xs font-mono font-extrabold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full border border-emerald-300">
                                                Global Sync
                                            </span>
                                        </div>

                                        <p class="text-xs text-slate-600 font-semibold leading-relaxed">
                                            Sets the default camera coordinates and zoom when residents, drivers, supervisors, and admins open map views.
                                        </p>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-extrabold text-slate-700 mb-1">Center Latitude</label>
                                                <input type="number" step="0.000001" id="inputCenterLat" value="<?php echo htmlspecialchars($centerLat); ?>" 
                                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition" onchange="updateCenterInputs()">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-extrabold text-slate-700 mb-1">Center Longitude</label>
                                                <input type="number" step="0.000001" id="inputCenterLng" value="<?php echo htmlspecialchars($centerLng); ?>" 
                                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-mono font-bold text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition" onchange="updateCenterInputs()">
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <label class="text-xs font-extrabold text-slate-700">Default Zoom Level</label>
                                                <span id="zoomLevelDisplay" class="text-xs font-mono font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                    Zoom: <?php echo $defaultZoom; ?>
                                                </span>
                                            </div>
                                            <input type="range" id="inputZoom" min="11" max="18" value="<?php echo $defaultZoom; ?>" 
                                                   class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600" oninput="updateZoomDisplay(this.value)">
                                            <div class="flex justify-between text-[10px] text-slate-400 font-extrabold pt-1">
                                                <span>11 (Town Level)</span>
                                                <span>15 (Barangay Default)</span>
                                                <span>18 (Street Level)</span>
                                            </div>
                                        </div>

                                        <div class="pt-2 flex flex-col gap-2">
                                            <button type="button" onclick="captureCurrentMapView()" class="w-full py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-extrabold rounded-xl transition border border-slate-300 flex items-center justify-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                                <span>Capture Current Map View as Center</span>
                                            </button>
                                            <button type="button" onclick="toggleCenterPinMarker()" id="btnCenterPin" class="w-full py-2.5 px-3 bg-amber-50 hover:bg-amber-100 text-amber-900 text-xs font-extrabold rounded-xl transition border border-amber-200 flex items-center justify-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <span id="centerPinBtnText">Show Draggable Center Pin</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- 2. Spatial Analytics & Geometry Info Card -->
                                    <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 shadow-xs space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                            <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                                                Spatial Metrics
                                            </h3>
                                            <span id="statStatusBadge" class="text-xs font-mono font-extrabold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full border border-emerald-300">
                                                Valid Polygon
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3 text-xs">
                                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Estimated Area</span>
                                                <span id="statAreaHectares" class="text-base font-extrabold text-slate-900 mt-1 block">-- ha</span>
                                                <span id="statAreaSqKm" class="text-[10px] font-mono text-slate-500 block">-- sq km</span>
                                            </div>
                                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Perimeter</span>
                                                <span id="statPerimeter" class="text-base font-extrabold text-slate-900 mt-1 block">-- km</span>
                                                <span id="statPerimeterM" class="text-[10px] font-mono text-slate-500 block">-- meters</span>
                                            </div>
                                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Polygon Vertices</span>
                                                <span id="statVertices" class="text-base font-extrabold text-slate-900 mt-1 block">-- points</span>
                                            </div>
                                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Bounding Box</span>
                                                <span id="statBounds" class="text-[10px] font-mono font-bold text-slate-700 mt-1 block truncate">--</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 3. Purok Sub-Zones Overview Toggle Card -->
                                    <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 shadow-xs space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                            <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                                Purok Sub-Zones
                                            </h3>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" id="togglePurokSubZones" class="sr-only peer" checked onchange="togglePurokLayers(this.checked)">
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                                            </label>
                                        </div>

                                        <p class="text-xs text-slate-600 font-semibold">
                                            Overlays all internal Purok polygons inside the master boundary to ensure sub-zone alignment.
                                        </p>

                                        <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                                            <?php foreach ($data['puroks'] as $p): ?>
                                                <div class="flex items-center justify-between text-xs py-1.5 px-2.5 rounded-lg bg-slate-50 border border-slate-200">
                                                    <span class="font-extrabold text-slate-800"><?php echo htmlspecialchars($p['purok_name']); ?></span>
                                                    <?php if (!empty($p['polygon_geometry'])): ?>
                                                        <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Mapped</span>
                                                    <?php else: ?>
                                                        <span class="text-[10px] font-extrabold text-slate-400 bg-slate-200 px-2 py-0.5 rounded">No Polygon</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                </div>

                                <!-- Right Map Editor Canvas (8 Cols) -->
                                <div class="xl:col-span-8 bg-white rounded-2xl border-2 border-slate-250 p-5 sm:p-6 shadow-xs flex flex-col justify-between space-y-4">
                                    <div>
                                        <!-- Editor Header & Actions -->
                                        <div class="flex flex-col  lg:items-center justify-between gap-3 mb-4 border-b border-slate-200 pb-4">
                                            <div class="min-w-0">
                                                <h2 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2 tracking-tight">
                                                    <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200/60 inline-flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                                                    </span>
                                                    <span>Interactive Perimeter &amp; Polygon Editor</span>
                                                </h2>
                                                <p class="text-xs text-slate-500 font-semibold mt-1">
                                                    Use drawing tools on the map to redraw or drag vertices to modify boundaries.
                                                </p>
                                            </div>

                                            <!-- Action Buttons Group -->
                                            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                                                <button id="btnSaveMasterBoundary" type="button" onclick="submitMasterBoundary()" 
                                                        class="px-4 py-2.5 bg-[#0B2E22] hover:bg-[#07241a] text-white text-xs sm:text-sm font-black rounded-xl transition shadow-xs hover:shadow-md flex items-center gap-2 cursor-pointer active:scale-95 whitespace-nowrap">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                                    <span>Save Master Boundary</span>
                                                </button>
                                                <button type="button" onclick="fitMapToBoundary()" 
                                                        class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 text-xs sm:text-sm font-bold rounded-xl transition border border-slate-250 flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                                    <span>Fit View</span>
                                                </button>
                                                <button type="button" onclick="clearDrawnBoundary()" 
                                                        class="px-3 py-2.5 bg-slate-100 hover:bg-rose-50 hover:border-rose-200 text-slate-700 hover:text-rose-600 text-xs sm:text-sm font-bold rounded-xl transition border border-slate-250 cursor-pointer whitespace-nowrap">
                                                    Clear
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Map Canvas -->
                                        <div id="masterBoundaryMap" class="h-[560px] sm:h-[620px] w-full rounded-2xl border-2 border-slate-250 relative overflow-hidden shadow-inner">
                                            <!-- Coordinates HUD tracker at bottom right -->
                                            <div id="mouseCoordHUD" class="absolute bottom-3 right-3 z-[1000] bg-slate-900/85 backdrop-blur-xs text-white text-[11px] font-mono font-bold px-3 py-1.5 rounded-lg border border-slate-700 shadow-md pointer-events-none">
                                                Lat: -- | Lng: --
                                            </div>
                                        </div>

                                        <!-- Legend & Reference Info -->
                                        <div class="mt-4 pt-3.5 border-t border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
                                            <div class="flex items-center gap-2.5 flex-wrap">
                                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-emerald-50/80 border border-emerald-200/80 text-slate-700 font-bold text-[11px] sm:text-xs">
                                                    <span class="w-3.5 h-3.5 rounded-sm border-2 border-emerald-600 bg-emerald-500/25 shrink-0"></span>
                                                    <span>Official Boundary</span>
                                                </span>
                                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-teal-50/80 border border-teal-200/80 text-slate-700 font-bold text-[11px] sm:text-xs">
                                                    <span class="w-3.5 h-3.5 rounded-sm border-2 border-dashed border-teal-500 bg-teal-500/15 shrink-0"></span>
                                                    <span>Purok Sub-Zones</span>
                                                </span>
                                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-amber-50/80 border border-amber-200/80 text-slate-700 font-bold text-[11px] sm:text-xs">
                                                    <span class="w-3 h-3 rounded-full bg-amber-500 border-2 border-white shadow-xs shrink-0"></span>
                                                    <span>Map Center Pin</span>
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-slate-400 font-mono text-[11px] shrink-0 self-end md:self-auto">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Spatial Ref: WGS 84 (EPSG:4326)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- Hidden Form for Boundary Submission -->
<form id="masterBoundaryForm" action="<?php echo app_url('settings/barangay_boundaries'); ?>" method="POST" class="hidden">
    <input type="hidden" name="save_boundary" value="1">
    <input type="hidden" name="polygon_geojson" id="formGeoJson" value="">
    <input type="hidden" name="center_latitude" id="formCenterLat" value="">
    <input type="hidden" name="center_longitude" id="formCenterLng" value="">
    <input type="hidden" name="default_zoom" id="formDefaultZoom" value="">
</form>

<!-- GeoJSON Import / Export Modal -->
<div id="geoJsonModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                GeoJSON Import &amp; Export Tool
            </h3>
            <button type="button" onclick="closeGeoJsonModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="space-y-4 text-xs">
            <div class="flex items-center justify-between">
                <label class="font-extrabold text-slate-800">Raw GeoJSON Geometry Payload:</label>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="copyGeoJsonToClipboard()" class="px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-extrabold rounded-lg border border-emerald-300 transition inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                        <span>Copy Payload</span>
                    </button>
                    <button type="button" onclick="downloadGeoJsonFile()" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold rounded-lg border border-slate-300 transition inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <span>Download .geojson</span>
                    </button>
                </div>
            </div>

            <textarea id="modalGeoJsonText" rows="10" 
                      class="w-full p-3.5 rounded-xl border border-slate-300 font-mono text-[11px] text-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none leading-relaxed resize-none bg-slate-50"></textarea>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                <span class="font-extrabold text-slate-800 block">Import File from Local Storage:</span>
                <input type="file" id="geoJsonFileInput" accept=".geojson,.json" class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
            <button type="button" onclick="closeGeoJsonModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-xl transition text-xs cursor-pointer">
                Cancel
            </button>
            <button type="button" onclick="applyImportedGeoJson()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-xs transition text-xs cursor-pointer">
                Apply to Map
            </button>
        </div>
    </div>
</div>

<!-- Custom Action Confirmation Modal -->
<div id="actionConfirmModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-[9999] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-5">
        <div class="flex items-start gap-4">
            <div id="confirmModalIconContainer" class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-xs">
                <!-- SVG Icon injected dynamically -->
            </div>
            <div class="space-y-1 min-w-0 flex-1">
                <h3 id="confirmModalTitle" class="text-base sm:text-lg font-black text-slate-900 leading-tight">
                    Confirm Action
                </h3>
                <p id="confirmModalMessage" class="text-xs text-slate-600 font-semibold leading-relaxed">
                    Are you sure you want to proceed with this action?
                </p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
            <button type="button" onclick="closeConfirmModal()" 
                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-xs sm:text-sm cursor-pointer">
                Cancel
            </button>
            <button id="confirmModalProceedBtn" type="button" 
                    class="px-5 py-2.5 text-white font-black rounded-xl shadow-xs transition text-xs sm:text-sm flex items-center gap-1.5 cursor-pointer active:scale-95">
                <span id="confirmModalProceedBtnText">Confirm</span>
            </button>
        </div>
    </div>
</div>

<script>
let map = null;
let drawnItems = null;
let purokSubZonesGroup = null;
let centerMarker = null;
let isCenterPinActive = false;

// Initial Data from PHP
const initialBoundaryGeoJSON = <?php echo json_encode($data['boundary_geojson'] ?? null); ?>;
const initialCenter = [<?php echo $centerLat; ?>, <?php echo $centerLng; ?>];
const initialZoom = <?php echo $defaultZoom; ?>;
const purokListData = <?php echo json_encode($data['puroks'] ?? []); ?>;

document.addEventListener('DOMContentLoaded', function() {
    if (typeof L === 'undefined') return;

    // 1. Initialize Map
    map = L.map('masterBoundaryMap', {
        zoomControl: true,
        attributionControl: true
    }).setView(initialCenter, initialZoom);

    // 2. Basemap Tile Layers
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Esri World Imagery', maxZoom: 19
    });
    const labelsLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    });
    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19
    });

    const satelliteGroup = L.layerGroup([satelliteLayer, labelsLayer]).addTo(map);

    L.control.layers({
        "Satellite Imagery": satelliteGroup,
        "Clean Street Map": streetLayer
    }, null, { position: 'topright' }).addTo(map);

    // 3. Feature Groups
    purokSubZonesGroup = L.featureGroup().addTo(map);
    drawnItems = new L.FeatureGroup().addTo(map);

    // Render Sub-Zones
    renderPurokSubZones();

    // 4. Setup Leaflet Draw Toolbar
    if (typeof L.Control.Draw !== 'undefined') {
        const drawControl = new L.Control.Draw({
            edit: { 
                featureGroup: drawnItems,
                poly: { allowIntersection: false }
            },
            draw: {
                polygon: {
                    allowIntersection: false,
                    showArea: true,
                    drawError: { color: '#EF4444', message: '<strong>Error:</strong> Boundaries cannot cross!' },
                    shapeOptions: {
                        color: '#10B981',
                        fillColor: '#10B981',
                        fillOpacity: 0.18,
                        weight: 3.5
                    }
                },
                rectangle: {
                    shapeOptions: {
                        color: '#10B981',
                        fillColor: '#10B981',
                        fillOpacity: 0.18,
                        weight: 3.5
                    }
                },
                polyline: false,
                circle: false,
                marker: false,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        // Draw Events
        map.on(L.Draw.Event.CREATED, function(e) {
            drawnItems.clearLayers();
            drawnItems.addLayer(e.layer);
            calculateSpatialMetrics();
        });

        map.on(L.Draw.Event.EDITED, function() {
            calculateSpatialMetrics();
        });

        map.on(L.Draw.Event.DELETED, function() {
            calculateSpatialMetrics();
        });
    }

    // 5. Load Initial Boundary Geometry
    if (initialBoundaryGeoJSON) {
        loadGeoJsonOntoMap(initialBoundaryGeoJSON, false);
    }

    // 6. Coordinates Tracker HUD
    const coordHUD = document.getElementById('mouseCoordHUD');
    map.on('mousemove', function(e) {
        if (coordHUD) {
            coordHUD.textContent = `Lat: ${e.latlng.lat.toFixed(6)} | Lng: ${e.latlng.lng.toFixed(6)}`;
        }
    });

    // Map Click when Center Pin is active
    map.on('click', function(e) {
        if (isCenterPinActive && centerMarker) {
            centerMarker.setLatLng(e.latlng);
            syncCenterCoordinates(e.latlng.lat, e.latlng.lng);
        }
    });
});

// Load GeoJSON onto drawnItems layer
function loadGeoJsonOntoMap(geoJsonData, shouldFitBounds = true) {
    drawnItems.clearLayers();
    try {
        const geoObj = (typeof geoJsonData === 'string') ? JSON.parse(geoJsonData) : geoJsonData;
        const layer = L.geoJSON(geoObj, {
            style: {
                color: '#10B981',
                weight: 3.5,
                fillColor: '#10B981',
                fillOpacity: 0.18
            }
        });

        layer.eachLayer(l => drawnItems.addLayer(l));

        if (shouldFitBounds && drawnItems.getLayers().length > 0) {
            map.fitBounds(drawnItems.getBounds(), { padding: [40, 40] });
        }
        calculateSpatialMetrics();
    } catch(e) {
        console.error('Error loading GeoJSON:', e);
    }
}

// Render Purok Sub-Zones
function renderPurokSubZones() {
    purokSubZonesGroup.clearLayers();
    purokListData.forEach(p => {
        if (p.polygon_geometry) {
            try {
                const geo = (typeof p.polygon_geometry === 'string') ? JSON.parse(p.polygon_geometry) : p.polygon_geometry;
                const layer = L.geoJSON(geo, {
                    style: {
                        color: '#38BDF8',
                        weight: 2,
                        fillColor: '#0284C7',
                        fillOpacity: 0.12,
                        dashArray: '5, 5'
                    }
                });
                layer.bindTooltip(`<b>${p.purok_name}</b>`, { permanent: false, direction: 'center', className: 'purok-sub-tooltip' });
                purokSubZonesGroup.addLayer(layer);
            } catch(err) {}
        }
    });
}

function togglePurokLayers(show) {
    if (show) {
        if (!map.hasLayer(purokSubZonesGroup)) map.addLayer(purokSubZonesGroup);
    } else {
        if (map.hasLayer(purokSubZonesGroup)) map.removeLayer(purokSubZonesGroup);
    }
}

// Draggable Center Pin Marker
function toggleCenterPinMarker() {
    isCenterPinActive = !isCenterPinActive;
    const btn = document.getElementById('btnCenterPin');
    const btnText = document.getElementById('centerPinBtnText');

    if (isCenterPinActive) {
        const lat = parseFloat(document.getElementById('inputCenterLat').value) || initialCenter[0];
        const lng = parseFloat(document.getElementById('inputCenterLng').value) || initialCenter[1];

        const pinIcon = L.divIcon({
            html: '<div class="center-pin-marker"></div>',
            className: '',
            iconSize: [24, 24],
            iconAnchor: [12, 24]
        });

        if (centerMarker) map.removeLayer(centerMarker);
        centerMarker = L.marker([lat, lng], { icon: pinIcon, draggable: true }).addTo(map);
        centerMarker.bindPopup('<b>Map Focal Center</b><br>Drag to reposition default center').openPopup();

        centerMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            syncCenterCoordinates(pos.lat, pos.lng);
        });

        btn.className = 'w-full py-2.5 px-3 bg-amber-500 hover:bg-amber-600 text-white text-xs font-extrabold rounded-xl transition border border-amber-600 flex items-center justify-center gap-2';
        btnText.textContent = 'Hide Draggable Center Pin';
    } else {
        if (centerMarker) {
            map.removeLayer(centerMarker);
            centerMarker = null;
        }
        btn.className = 'w-full py-2.5 px-3 bg-amber-50 hover:bg-amber-100 text-amber-900 text-xs font-extrabold rounded-xl transition border border-amber-200 flex items-center justify-center gap-2';
        btnText.textContent = 'Show Draggable Center Pin';
    }
}

function syncCenterCoordinates(lat, lng) {
    document.getElementById('inputCenterLat').value = lat.toFixed(6);
    document.getElementById('inputCenterLng').value = lng.toFixed(6);
}

// ============================================================
// CUSTOM ACTION CONFIRMATION MODAL LOGIC
// ============================================================
let onConfirmCallback = null;

function showConfirmationModal({ title, message, iconType, confirmText, confirmClass, onConfirm }) {
    document.getElementById('confirmModalTitle').textContent = title;
    document.getElementById('confirmModalMessage').textContent = message;
    
    const iconContainer = document.getElementById('confirmModalIconContainer');
    const proceedBtn = document.getElementById('confirmModalProceedBtn');
    const proceedBtnText = document.getElementById('confirmModalProceedBtnText');
    
    proceedBtnText.textContent = confirmText || 'Confirm';
    proceedBtn.className = `px-5 py-2.5 text-white font-black rounded-xl shadow-xs transition text-xs sm:text-sm flex items-center gap-1.5 cursor-pointer active:scale-95 ${confirmClass || 'bg-emerald-600 hover:bg-emerald-700'}`;

    if (iconType === 'danger') {
        iconContainer.className = 'w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-xs';
        iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>';
    } else if (iconType === 'info') {
        iconContainer.className = 'w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center shrink-0 shadow-xs';
        iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
    } else if (iconType === 'warning') {
        iconContainer.className = 'w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0 shadow-xs';
        iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
    } else { // success / default
        iconContainer.className = 'w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0 shadow-xs';
        iconContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>';
    }

    onConfirmCallback = onConfirm;
    document.getElementById('actionConfirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('actionConfirmModal').classList.add('hidden');
    onConfirmCallback = null;
}

document.getElementById('confirmModalProceedBtn')?.addEventListener('click', function() {
    if (typeof onConfirmCallback === 'function') {
        const callback = onConfirmCallback;
        closeConfirmModal();
        callback();
    }
});

function captureCurrentMapView() {
    const center = map.getCenter();
    const zoom = map.getZoom();
    
    showConfirmationModal({
        title: 'Capture Map View as Focal Center?',
        message: `Set camera coordinates (Lat: ${center.lat.toFixed(6)}, Lng: ${center.lng.toFixed(6)}) and Zoom level ${zoom} as the default focal center?`,
        iconType: 'info',
        confirmText: 'Yes, Capture Coordinates',
        confirmClass: 'bg-indigo-600 hover:bg-indigo-700',
        onConfirm: function() {
            syncCenterCoordinates(center.lat, center.lng);
            document.getElementById('inputZoom').value = zoom;
            updateZoomDisplay(zoom);
            if (centerMarker) centerMarker.setLatLng(center);
        }
    });
}

function updateCenterInputs() {
    const lat = parseFloat(document.getElementById('inputCenterLat').value);
    const lng = parseFloat(document.getElementById('inputCenterLng').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        map.panTo([lat, lng]);
        if (centerMarker) centerMarker.setLatLng([lat, lng]);
    }
}

function updateZoomDisplay(val) {
    document.getElementById('zoomLevelDisplay').textContent = `Zoom: ${val}`;
}

function fitMapToBoundary() {
    if (drawnItems && drawnItems.getLayers().length > 0) {
        map.fitBounds(drawnItems.getBounds(), { padding: [40, 40] });
    } else {
        alert('No boundary drawn yet to fit view.');
    }
}

function clearDrawnBoundary() {
    if (!drawnItems || drawnItems.getLayers().length === 0) {
        return;
    }
    showConfirmationModal({
        title: 'Clear Drawn Boundary?',
        message: 'Are you sure you want to remove the current drawn polygon from the map editor? Any unsaved edits will be discarded.',
        iconType: 'danger',
        confirmText: 'Yes, Clear Boundary',
        confirmClass: 'bg-rose-600 hover:bg-rose-700',
        onConfirm: function() {
            drawnItems.clearLayers();
            calculateSpatialMetrics();
        }
    });
}

// Calculate Surface Area, Perimeter, Vertices
function calculateSpatialMetrics() {
    const layers = drawnItems.getLayers();
    if (layers.length === 0) {
        document.getElementById('statAreaHectares').textContent = '-- ha';
        document.getElementById('statAreaSqKm').textContent = '-- sq km';
        document.getElementById('statPerimeter').textContent = '-- km';
        document.getElementById('statPerimeterM').textContent = '-- meters';
        document.getElementById('statVertices').textContent = '0 points';
        document.getElementById('statBounds').textContent = 'No active polygon';
        document.getElementById('statStatusBadge').textContent = 'Empty';
        document.getElementById('statStatusBadge').className = 'text-xs font-mono font-extrabold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full border border-amber-300';
        return;
    }

    const layer = layers[0];
    const geoJson = layer.toGeoJSON();
    const coords = geoJson.geometry.coordinates[0];
    const numVertices = coords.length;

    // Calculate Area (spherical approximation)
    let areaSqMeters = 0;
    if (coords.length > 2) {
        const rad = Math.PI / 180;
        const R = 6378137;
        let total = 0;
        for (let i = 0; i < coords.length - 1; i++) {
            const p1 = coords[i];
            const p2 = coords[i + 1];
            total += (p2[0] * rad - p1[0] * rad) * (2 + Math.sin(p1[1] * rad) + Math.sin(p2[1] * rad));
        }
        areaSqMeters = Math.abs(total * (R * R) / 2);
    }

    // Perimeter (Haversine)
    let perimeterMeters = 0;
    for (let i = 0; i < coords.length - 1; i++) {
        const latlng1 = L.latLng(coords[i][1], coords[i][0]);
        const latlng2 = L.latLng(coords[i + 1][1], coords[i + 1][0]);
        perimeterMeters += latlng1.distanceTo(latlng2);
    }

    const hectares = (areaSqMeters / 10000).toFixed(2);
    const sqKm = (areaSqMeters / 1000000).toFixed(3);
    const periKm = (perimeterMeters / 1000).toFixed(2);

    document.getElementById('statAreaHectares').textContent = `${hectares} ha`;
    document.getElementById('statAreaSqKm').textContent = `${sqKm} sq km (${Math.round(areaSqMeters).toLocaleString()} m²)`;
    document.getElementById('statPerimeter').textContent = `${periKm} km`;
    document.getElementById('statPerimeterM').textContent = `${Math.round(perimeterMeters).toLocaleString()} m perimeter`;
    document.getElementById('statVertices').textContent = `${numVertices} vertices`;

    const bounds = layer.getBounds();
    document.getElementById('statBounds').textContent = `${bounds.getSouth().toFixed(4)}, ${bounds.getWest().toFixed(4)} to ${bounds.getNorth().toFixed(4)}, ${bounds.getEast().toFixed(4)}`;

    document.getElementById('statStatusBadge').textContent = 'Valid Polygon';
    document.getElementById('statStatusBadge').className = 'text-xs font-mono font-extrabold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full border border-emerald-300';
}

// Submit Master Boundary Form
function submitMasterBoundary() {
    const layers = drawnItems.getLayers();
    if (layers.length === 0) {
        alert('Please draw or import a polygon boundary first before saving.');
        return;
    }

    showConfirmationModal({
        title: 'Save Master Barangay Boundary?',
        message: 'This will update the official boundary perimeter and default camera center coordinates across ALL system maps (Admin GIS, Supervisor GIS, Resident Portal, and Guest Reporting).',
        iconType: 'success',
        confirmText: 'Save & Sync Globally',
        confirmClass: 'bg-[#0B2E22] hover:bg-[#07241a]',
        onConfirm: function() {
            const geoJson = layers[0].toGeoJSON();
            const geoJsonStr = JSON.stringify(geoJson.geometry || geoJson);
            const centerLat = parseFloat(document.getElementById('inputCenterLat').value) || initialCenter[0];
            const centerLng = parseFloat(document.getElementById('inputCenterLng').value) || initialCenter[1];
            const zoom = parseInt(document.getElementById('inputZoom').value) || initialZoom;

            document.getElementById('formGeoJson').value = geoJsonStr;
            document.getElementById('formCenterLat').value = centerLat;
            document.getElementById('formCenterLng').value = centerLng;
            document.getElementById('formDefaultZoom').value = zoom;

            document.getElementById('masterBoundaryForm').submit();
        }
    });
}

// Modal GeoJSON Handling
function openGeoJsonModal() {
    const layers = drawnItems.getLayers();
    let payload = '';
    if (layers.length > 0) {
        payload = JSON.stringify(layers[0].toGeoJSON(), null, 2);
    } else if (initialBoundaryGeoJSON) {
        payload = (typeof initialBoundaryGeoJSON === 'string') ? initialBoundaryGeoJSON : JSON.stringify(initialBoundaryGeoJSON, null, 2);
    }
    document.getElementById('modalGeoJsonText').value = payload;
    document.getElementById('geoJsonModal').classList.remove('hidden');
}

function closeGeoJsonModal() {
    document.getElementById('geoJsonModal').classList.add('hidden');
}

function copyGeoJsonToClipboard() {
    const txt = document.getElementById('modalGeoJsonText').value;
    navigator.clipboard.writeText(txt).then(() => {
        alert('GeoJSON copied to clipboard!');
    });
}

function downloadGeoJsonFile() {
    const txt = document.getElementById('modalGeoJsonText').value;
    if (!txt) {
        alert('No GeoJSON to download.');
        return;
    }
    const blob = new Blob([txt], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `barangay_boundary_${Date.now()}.geojson`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function applyImportedGeoJson() {
    const txt = document.getElementById('modalGeoJsonText').value.trim();
    if (!txt) {
        alert('Please paste valid GeoJSON or choose a file.');
        return;
    }
    try {
        const parsed = JSON.parse(txt);
        showConfirmationModal({
            title: 'Apply Imported GeoJSON Geometry?',
            message: 'This will replace the active polygon on the map canvas with the imported GeoJSON geometry.',
            iconType: 'warning',
            confirmText: 'Yes, Apply to Map',
            confirmClass: 'bg-emerald-600 hover:bg-emerald-700',
            onConfirm: function() {
                loadGeoJsonOntoMap(parsed, true);
                closeGeoJsonModal();
            }
        });
    } catch(e) {
        alert('Error parsing JSON payload: ' + e.message);
    }
}

// File Reader
document.getElementById('geoJsonFileInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(evt) {
        document.getElementById('modalGeoJsonText').value = evt.target.result;
    };
    reader.readAsText(file);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
