<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$reports = $data['reports'] ?? [];
$total_mapped = $data['total_mapped'] ?? 0;
$active_hotspots = $data['active_hotspots'] ?? [];
$active_hotspots_count = $data['active_hotspots_count'] ?? 0;
$highest_purok = $data['highest_purok'] ?? 'N/A';
$categories = $data['categories'] ?? [];
$puroks = $data['puroks'] ?? [];
$statuses = $data['statuses'] ?? [];
$heatmap_settings = $data['heatmap_settings'] ?? ['radius_meters' => 40];
$landmarks = $data['landmarks'] ?? [];
$purok_polygons = $data['purok_polygons'] ?? [];

// Status color definitions
$statusColors = [
    'Pending'     => '#F59E0B',
    'Verified'    => '#0284C7',
    'In Progress' => '#F97316',
    'Resolved'    => '#059669',
    'Rejected'    => '#DC2626'
];

function getPriorityBadge($count) {
    if ($count >= 9) {
        return ['label' => 'HIGH DENSITY (HOTSPOT)', 'bg' => 'bg-red-50 text-red-900 border-red-200'];
    } elseif ($count >= 4) {
        return ['label' => 'MEDIUM DENSITY (VERIFIED)', 'bg' => 'bg-amber-50 text-amber-900 border-amber-200'];
    }
    return ['label' => 'LOW DENSITY (PENDING)', 'bg' => 'bg-emerald-50 text-emerald-900 border-emerald-200'];
}
?>

<!-- Leaflet & Heatmap Stylesheets & Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    
    /* Map Container and Fullscreen */
    #gisMapContainer.fullscreen-mode {
        position: fixed !important;
        inset: 0 !important;
        z-index: 9999 !important;
        border-radius: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    #gisMapContainer.fullscreen-mode #gisMap {
        height: 100vh !important;
        border-radius: 0 !important;
    }
    
    /* Leaflet Popup & Tooltip Custom Styles */
    .leaflet-popup-content-wrapper {
        border-radius: 16px !important;
        box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.18) !important;
        padding: 0 !important;
        overflow: hidden !important;
        border: 1px solid #e2e8f0 !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: 1.4 !important;
    }
    .purok-poly-tooltip {
        background: #0B2E22 !important;
        color: #FFFFFF !important;
        border: 1px solid #10B981 !important;
        border-radius: 8px !important;
        font-weight: 800 !important;
        font-size: 11px !important;
        padding: 3px 8px !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
    }
    .landmark-tooltip {
        background: #1e293b !important;
        color: #f8fafc !important;
        border: 1px solid #64748b !important;
        border-radius: 8px !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        padding: 3px 8px !important;
    }
</style>

<div class="min-h-screen bg-slate-50 text-slate-800 w-full flex font-sans antialiased">
    
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
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                    <!-- ============================================================ -->
                    <!-- 1. PAGE HEADER                                               -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Spatial Intelligence &amp; GIS
                                </span>
                                <span class="text-sm text-slate-300 font-bold">•</span>
                                <span id="headerReportCount" class="text-xs sm:text-sm font-bold text-slate-500"><?php echo count($reports); ?> Mapped Incident Points</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                GIS Incident Mapping &amp; Spatial Operations
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Real-time geographic incident density, heatmaps, purok zoning, and landmark overlays for Barangay Dulong Bayan.
                            </p>
                        </div>

                        <!-- Top Action Buttons -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-extrabold transition border border-slate-200 shadow-xs cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                Print Map
                            </button>
                            <a href="/brgy-waste-app-v3/public/settings/landmarks" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-800 text-xs sm:text-sm font-extrabold transition border border-slate-200 shadow-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                Landmarks
                            </a>
                            <a href="/brgy-waste-app-v3/public/settings/heatmap" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold transition shadow-xs border border-emerald-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/><circle cx="12" cy="10" r="2"/></svg>
                                Heatmap Config
                            </a>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 2. SPATIAL KPI METRICS                                       -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Geo-Tagged</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p id="kpiTotalMapped" class="text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo $total_mapped; ?></p>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Live Pins</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Filtered &amp; verified reports</span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-amber-700 uppercase tracking-wider">Active Hotspots</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p id="kpiActiveHotspots" class="text-3xl font-extrabold text-amber-600 tracking-tight"><?php echo $active_hotspots_count; ?></p>
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">&ge; 3 incidents</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">High density cluster sectors</span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-red-700 uppercase tracking-wider">Highest Concern</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p id="kpiHighestPurok" class="text-2xl font-extrabold text-slate-900 truncate tracking-tight"><?php echo htmlspecialchars($highest_purok); ?></p>
                                <span class="text-xs font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded-md border border-red-200">Priority Zone</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Requires route adjustment</span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Heatmap Radius</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p class="text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo (int)($heatmap_settings['radius_meters'] ?? 40); ?><span class="text-sm font-semibold text-slate-500 ml-1">meters</span></p>
                                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">Kernel Blur</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Density dispersion range</span>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 3. INTERACTIVE SEARCH & CATEGORY FILTER BAR                  -->
                    <!-- ============================================================ -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                        
                        <!-- Top Row: Live Search & Dropdowns -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                            
                            <!-- Search Input -->
                            <div class="relative flex-1 min-w-[220px]">
                                <input type="text" id="gisSearchInput" placeholder="Search by resident name, location keyword, or ID..." 
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>

                            <!-- Purok Selector -->
                            <div class="w-full sm:w-48">
                                <select id="purokFilter" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition cursor-pointer">
                                    <option value="0">All Purok Sectors</option>
                                    <?php foreach ($puroks as $p): ?>
                                        <option value="<?php echo $p['purok_id']; ?>"><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Status Selector -->
                            <div class="w-full sm:w-44">
                                <select id="statusFilter" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition cursor-pointer">
                                    <option value="">All Statuses</option>
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?php echo htmlspecialchars($s['status_name']); ?>"><?php echo htmlspecialchars($s['status_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Reset Filters -->
                            <button id="btnResetFilters" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-extrabold transition border border-slate-200 shrink-0 cursor-pointer">
                                Reset
                            </button>
                        </div>

                        <!-- Bottom Row: Waste Category Filter Chips -->
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-extrabold scrollbar-thin">
                            <span class="text-slate-400 uppercase tracking-wider text-[11px] shrink-0 mr-1">Classification:</span>
                            <button onclick="filterCategory(0)" data-category="0" 
                                    class="category-filter px-3.5 py-1.5 rounded-full bg-[#0B2E22] text-white shadow-2xs transition shrink-0 cursor-pointer border border-emerald-900">
                                All Categories
                            </button>
                            <?php foreach ($categories as $cat): ?>
                                <button onclick="filterCategory(<?php echo $cat['category_id']; ?>)" data-category="<?php echo $cat['category_id']; ?>" 
                                        class="category-filter px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition shrink-0 cursor-pointer">
                                    <?php echo htmlspecialchars($cat['category_name']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 4. MAIN MAP & INTERACTIVE INSPECTOR GRID                     -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        <!-- Left Map Canvas (8 Columns) -->
                        <div id="gisMapContainer" class="lg:col-span-8 flex flex-col bg-white rounded-2xl border border-slate-200 shadow-xs p-4 sm:p-5 relative transition-all">
                            
                            <!-- Floating Layer & Map Control Bar -->
                            <div class="flex flex-wrap items-center justify-between gap-2.5 pb-4 border-b border-slate-100">
                                
                                <!-- Layer Toggles -->
                                <div class="flex flex-wrap items-center gap-1.5 text-xs font-extrabold">
                                    <button id="toggleHeatmap" class="layer-toggle px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-950 border border-emerald-300 transition flex items-center gap-1.5 active" title="Toggle Thermal Heatmap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z"/></svg>
                                        <span>Heatmap</span>
                                    </button>
                                    <button id="toggleMarkers" class="layer-toggle px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-950 border border-emerald-300 transition flex items-center gap-1.5 active" title="Toggle Incident Pin Markers">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>Incident Pins</span>
                                    </button>
                                    <button id="toggleLandmarks" class="layer-toggle px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-950 border border-emerald-300 transition flex items-center gap-1.5 active" title="Toggle Facilities & Landmarks">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
                                        <span>Landmarks</span>
                                    </button>
                                    <button id="togglePuroks" class="layer-toggle px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-950 border border-emerald-300 transition flex items-center gap-1.5 active" title="Toggle Purok Boundaries">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                                        <span>Purok Zones</span>
                                    </button>
                                </div>

                                <!-- Tools & Controls -->
                                <div class="flex items-center gap-2">
                                    <button id="btnResetView" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition border border-slate-200 text-xs font-bold flex items-center gap-1.5" title="Center on Dulong Bayan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="22"/><line x1="2" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="22" y2="12"/></svg>
                                        <span class="hidden sm:inline">Center Map</span>
                                    </button>
                                    <button id="btnFullscreenMap" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition border border-slate-200 text-xs font-bold" title="Fullscreen View">
                                        <svg id="fullscreenIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Map Viewport -->
                            <div class="mt-3 relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner flex-1" style="min-height: 520px;">
                                <div id="gisMap" class="w-full h-full" style="min-height: 520px; height: clamp(420px, 60vh, 680px);"></div>
                                
                                <!-- Live Area Spatial Intelligence HUD (Floating on Map Hover) -->
                                <div id="areaIntelligenceHUD" class="absolute top-4 left-4 z-[400] bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-slate-200/90 text-xs max-w-[320px] transition-all duration-200 pointer-events-none hidden">
                                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2 mb-2">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                            <h4 id="hudAreaName" class="font-extrabold text-slate-900 text-sm truncate">Purok Area</h4>
                                        </div>
                                        <span id="hudDensityBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold shrink-0 bg-emerald-100 text-emerald-900 border border-emerald-300">
                                            Low Density
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2.5">
                                        <!-- Stats Summary Grid -->
                                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                                            <div>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Reports</span>
                                                <span id="hudTotalReports" class="text-lg font-extrabold font-mono text-slate-900">0</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Highest Waste</span>
                                                <span id="hudHighestWaste" class="text-xs font-bold text-emerald-800 truncate block mt-0.5">N/A</span>
                                            </div>
                                        </div>

                                        <!-- Dominant Waste Progress Bar -->
                                        <div id="hudWasteBarContainer" class="space-y-1">
                                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                                <span id="hudWasteBarLabel">Dominant Share</span>
                                                <span id="hudWasteBarPercent" class="font-mono text-emerald-700">0%</span>
                                            </div>
                                            <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                                <div id="hudWasteProgressBar" class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                            </div>
                                        </div>

                                        <!-- Recommended Action Feature -->
                                        <div class="pt-2 border-t border-slate-100">
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                                                <span class="text-[10px] font-black text-slate-800 uppercase tracking-wider">Recommended Action:</span>
                                            </div>
                                            <p id="hudRecommendedAction" class="text-[11px] font-semibold text-slate-700 leading-snug bg-emerald-50/80 border border-emerald-200/80 p-2.5 rounded-xl">
                                                Move mouse over a purok boundary or cluster to analyze area intelligence.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Status Legend -->
                                <div class="absolute bottom-4 left-4 z-[400] bg-white/95 backdrop-blur-md rounded-xl p-3.5 shadow-md border border-slate-200 text-xs max-w-[210px] hidden sm:block">
                                    <div class="flex items-center justify-between pb-1.5 mb-1.5 border-b border-slate-100">
                                        <p class="font-extrabold text-slate-900 text-[11px] uppercase tracking-wider">Report Status Legend</p>
                                    </div>
                                    <div class="space-y-1.5">
                                        <?php foreach ($statusColors as $label => $color): ?>
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-3 h-3 rounded-full border-2 border-white shadow-2xs shrink-0" style="background: <?php echo $color; ?>;"></span>
                                                    <span class="text-slate-700 font-bold text-[11px]"><?php echo $label; ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Coordinates HUD (Bottom Right) -->
                                <div id="coordHUD" class="absolute bottom-4 right-4 z-[400] bg-slate-900/85 backdrop-blur-md text-white px-3 py-1 rounded-lg text-[10px] font-mono shadow-xs border border-slate-800 hidden sm:block">
                                    Lat: 15.558000 | Lng: 120.803000
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Tabs & Inspector (4 Columns) -->
                        <div class="lg:col-span-4 space-y-5">
                            
                            <!-- Tabbed Card Component -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col h-full max-h-[720px]">
                                
                                <!-- Panel Navigation Tabs -->
                                <div class="flex items-center border-b border-slate-200 bg-slate-50/70 p-2 gap-1 text-xs font-extrabold">
                                    <button onclick="switchTab('hotspots')" id="tabBtnHotspots" 
                                            class="tab-btn flex-1 py-2 px-3 rounded-xl bg-white text-slate-900 shadow-xs border border-slate-200 transition text-center">
                                        Hotspot Zones
                                    </button>
                                    <button onclick="switchTab('stream')" id="tabBtnStream" 
                                            class="tab-btn flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition text-center">
                                        Incidents Stream
                                    </button>
                                    <button onclick="switchTab('landmarks')" id="tabBtnLandmarks" 
                                            class="tab-btn flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition text-center">
                                        Facilities
                                    </button>
                                </div>

                                <!-- TAB 1: HOTSPOTS MATRIX -->
                                <div id="tabContentHotspots" class="p-5 overflow-y-auto space-y-3 flex-1 scrollbar-thin">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <h2 class="text-xs font-black text-slate-500 uppercase tracking-wider">Priority Intervention Zones</h2>
                                        <span class="text-[11px] font-bold text-slate-400">Click to fly on map</span>
                                    </div>

                                    <?php if (!empty($active_hotspots)): ?>
                                        <?php $hRank = 1; foreach ($active_hotspots as $hotspot): 
                                            $priority = getPriorityBadge($hotspot['report_count']);
                                        ?>
                                            <div onclick="flyToPurokName('<?php echo htmlspecialchars($hotspot['purok_name'], ENT_QUOTES); ?>')" 
                                                 class="p-3.5 rounded-xl bg-slate-50 hover:bg-emerald-50/60 border border-slate-200 hover:border-emerald-300 transition-all cursor-pointer group shadow-2xs">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="w-5 h-5 rounded-md bg-slate-200 group-hover:bg-emerald-200 text-slate-800 group-hover:text-emerald-900 text-[10px] font-black flex items-center justify-center">
                                                                #<?php echo $hRank++; ?>
                                                            </span>
                                                            <p class="text-sm font-extrabold text-slate-900 group-hover:text-emerald-950"><?php echo htmlspecialchars($hotspot['purok_name']); ?></p>
                                                        </div>
                                                        <p class="text-xs font-semibold text-slate-500 mt-1 pl-7">
                                                            <?php echo $hotspot['report_count']; ?> reports · <?php echo htmlspecialchars($hotspot['dominant_category'] ?? 'Mixed Waste'); ?>
                                                        </p>
                                                    </div>
                                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border <?php echo $priority['bg']; ?>">
                                                        <?php echo $priority['label']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-slate-200">
                                            <p class="text-xs font-bold text-slate-500">No active hotspot clusters detected.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- TAB 2: LIVE INCIDENTS STREAM -->
                                <div id="tabContentStream" class="p-5 overflow-y-auto space-y-3 flex-1 scrollbar-thin hidden">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <h2 class="text-xs font-black text-slate-500 uppercase tracking-wider">Filtered Incidents Feed</h2>
                                        <span id="streamCount" class="text-[11px] font-bold text-emerald-700"><?php echo count($reports); ?> Loaded</span>
                                    </div>

                                    <div id="incidentsStreamList" class="space-y-2.5">
                                        <!-- Dynamically Populated by JS -->
                                    </div>
                                </div>

                                <!-- TAB 3: LANDMARKS & FACILITIES -->
                                <div id="tabContentLandmarks" class="p-5 overflow-y-auto space-y-3 flex-1 scrollbar-thin hidden">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <h2 class="text-xs font-black text-slate-500 uppercase tracking-wider">Barangay Infrastructure</h2>
                                        <a href="/brgy-waste-app-v3/public/settings/landmarks" class="text-[11px] font-bold text-emerald-700 hover:underline">+ Manage</a>
                                    </div>

                                    <?php if (!empty($landmarks)): ?>
                                        <?php foreach ($landmarks as $lm): ?>
                                            <div onclick="flyToCoords(<?php echo (float)$lm['latitude']; ?>, <?php echo (float)$lm['longitude']; ?>, '<?php echo htmlspecialchars($lm['landmark_name'], ENT_QUOTES); ?>')" 
                                                 class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition cursor-pointer flex items-center justify-between group shadow-2xs">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-900 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-extrabold text-slate-900"><?php echo htmlspecialchars($lm['landmark_name']); ?></p>
                                                        <p class="text-[11px] font-semibold text-emerald-700"><?php echo htmlspecialchars($lm['landmark_type'] ?? 'Facility'); ?></p>
                                                    </div>
                                                </div>
                                                <span class="text-xs font-bold text-slate-400 group-hover:text-emerald-700 transition">Fly →</span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-8 text-center bg-slate-50 rounded-xl border border-slate-200">
                                            <p class="text-xs font-bold text-slate-500">No landmarks configured yet.</p>
                                            <a href="/brgy-waste-app-v3/public/settings/landmarks" class="mt-2 inline-block text-xs font-extrabold text-emerald-700 underline">Add landmark pin</a>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- Leaflet Map Logic & Dynamic Filtering Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // -------------------------------------------------------
    // 1. INITIALIZE LEAFLET MAP
    // -------------------------------------------------------
    const defaultCenter = [
        <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>, 
        <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>
    ];
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;
    
    const map = L.map('gisMap', {
        center: defaultCenter,
        zoom: defaultZoom,
        zoomControl: false // Custom placement
    });

    // Add Zoom Control to Top Right
    L.control.zoom({ position: 'topright' }).addTo(map);

    // Tile Layers
    const satelliteTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Esri World Imagery',
        maxZoom: 19
    });
    const satelliteLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: '',
        maxZoom: 19
    });
    const streetTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    });

    // Default: Satellite with street labels
    const satelliteGroup = L.layerGroup([satelliteTile, satelliteLabels]).addTo(map);

    // Layer Switcher Control (Basemaps)
    L.control.layers({
        "Satellite View": satelliteGroup,
        "Clean Street Map": streetTile
    }, null, { position: 'topright' }).addTo(map);

    // Coordinates HUD tracker
    const coordHUD = document.getElementById('coordHUD');
    map.on('mousemove', function(e) {
        if (coordHUD) {
            coordHUD.textContent = `Lat: ${e.latlng.lat.toFixed(6)} | Lng: ${e.latlng.lng.toFixed(6)}`;
        }
    });

    // -------------------------------------------------------
    // 2. BARANGAY BOUNDARY POLYGON (Dynamic from Database)
    // -------------------------------------------------------
    const rawBoundaryData = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
    let boundaryLayer = null;

    if (rawBoundaryData) {
        try {
            const boundaryGeoObj = (typeof rawBoundaryData === 'string') ? JSON.parse(rawBoundaryData) : rawBoundaryData;
            const boundaryStyle = { color: '#10B981', weight: 2.5, fillColor: '#D1FAE5', fillOpacity: 0.12, dashArray: '6, 6' };
            boundaryLayer = L.geoJSON(boundaryGeoObj, { style: boundaryStyle }).addTo(map);
        } catch(e) {
            console.error('Error rendering dynamic barangay boundary in Admin GIS:', e);
        }
    }

    // -------------------------------------------------------
    // 3. PUROK BOUNDARIES LAYER & SPATIAL INTELLIGENCE HOVER
    // -------------------------------------------------------
    const purokPolygons = <?php echo json_encode($purok_polygons ?: []); ?>;
    const puroksLayerGroup = L.layerGroup().addTo(map);
    const purokLayerMap = {};

    // HUD Elements
    const hud = document.getElementById('areaIntelligenceHUD');
    const hudAreaName = document.getElementById('hudAreaName');
    const hudDensityBadge = document.getElementById('hudDensityBadge');
    const hudTotalReports = document.getElementById('hudTotalReports');
    const hudHighestWaste = document.getElementById('hudHighestWaste');
    const hudWasteBarLabel = document.getElementById('hudWasteBarLabel');
    const hudWasteBarPercent = document.getElementById('hudWasteBarPercent');
    const hudWasteProgressBar = document.getElementById('hudWasteProgressBar');
    const hudRecommendedAction = document.getElementById('hudRecommendedAction');

    window.analyzeAreaIntelligence = function(purokName) {
        const areaReports = rawReports.filter(r => r.purok && r.purok.toLowerCase() === purokName.toLowerCase());
        const total = areaReports.length;
        
        // Category Breakdown
        const catCounts = {};
        let dominantCat = 'No Waste Reported';
        let maxCatCount = 0;
        
        areaReports.forEach(r => {
            const cat = r.waste_category || 'Unclassified';
            catCounts[cat] = (catCounts[cat] || 0) + 1;
            if (catCounts[cat] > maxCatCount) {
                maxCatCount = catCounts[cat];
                dominantCat = cat;
            }
        });

        const percent = total > 0 ? Math.round((maxCatCount / total) * 100) : 0;

        // Density Rules:
        // <= 3 reports: Low Density (Pending/Initial)
        // 4 to 8 reports: Medium Density (Verified Clusters)
        // >= 9 reports: High Density (Active Hotspots)
        let densityLabel = 'Low Density (Pending)';
        let densityBadgeClass = 'bg-emerald-100 text-emerald-950 border border-emerald-300';
        let recommendation = '';

        if (total >= 9) {
            densityLabel = 'High Density (Hotspot)';
            densityBadgeClass = 'bg-red-100 text-red-950 border border-red-300';
            recommendation = `Critical Intervention: High incident concentration (${total} reports, ${dominantCat} dominant at ${percent}%). Immediate dispatch of compactor truck & sanitation sweep required within 12h. Conduct purok segregation audit.`;
        } else if (total >= 4) {
            densityLabel = 'Medium Density (Verified)';
            densityBadgeClass = 'bg-amber-100 text-amber-950 border border-amber-300';
            recommendation = `Medium Priority: Active recurring waste accumulation (${total} reports, mostly ${dominantCat}). Schedule dedicated collection run within 24h and inspect purok collection points.`;
        } else if (total > 0) {
            densityLabel = 'Low Density (Pending)';
            densityBadgeClass = 'bg-emerald-100 text-emerald-950 border border-emerald-300';
            recommendation = `Low Activity: ${total} reported incident(s) of ${dominantCat}. Standard routine collection schedule adequate. Verify any pending citizen reports.`;
        } else {
            densityLabel = 'Clean / Monitored';
            densityBadgeClass = 'bg-slate-100 text-slate-700 border border-slate-300';
            recommendation = 'Clean Sector: No active waste incident reports recorded in this purok.';
        }

        return {
            purokName,
            total,
            dominantCat,
            maxCatCount,
            percent,
            densityLabel,
            densityBadgeClass,
            recommendation
        };
    };

    function showAreaIntelligenceHUD(info) {
        if (!hud) return;
        if (hudAreaName) hudAreaName.textContent = info.purokName;
        if (hudDensityBadge) {
            hudDensityBadge.textContent = info.densityLabel;
            hudDensityBadge.className = `px-2.5 py-0.5 rounded-full text-[10px] font-extrabold shrink-0 ${info.densityBadgeClass}`;
        }
        if (hudTotalReports) hudTotalReports.textContent = info.total;
        if (hudHighestWaste) hudHighestWaste.textContent = info.dominantCat;
        if (hudWasteBarLabel) hudWasteBarLabel.textContent = `${info.dominantCat} Proportion`;
        if (hudWasteBarPercent) hudWasteBarPercent.textContent = `${info.percent}%`;
        if (hudWasteProgressBar) hudWasteProgressBar.style.width = `${info.percent}%`;
        if (hudRecommendedAction) hudRecommendedAction.textContent = info.recommendation;

        hud.classList.remove('hidden');
    }

    function hideAreaIntelligenceHUD() {
        if (!hud) return;
        hud.classList.add('hidden');
    }

    purokPolygons.forEach(p => {
        if (p.polygon_geometry) {
            try {
                const geom = JSON.parse(p.polygon_geometry);
                const poly = L.geoJSON(geom, {
                    style: { color: '#059669', weight: 2, fillColor: '#34D399', fillOpacity: 0.15, dashArray: '4, 4' }
                });
                poly.bindTooltip(`<b class="font-bold">${p.purok_name}</b>`, { permanent: false, direction: 'center', className: 'purok-poly-tooltip' });
                
                // Interactive Hover Events on Purok Boundary Polygon
                poly.on('mouseover', function() {
                    poly.setStyle({
                        weight: 3.5,
                        fillOpacity: 0.35,
                        color: '#10B981',
                        dashArray: ''
                    });
                    const info = analyzeAreaIntelligence(p.purok_name);
                    showAreaIntelligenceHUD(info);
                });

                poly.on('mouseout', function() {
                    poly.setStyle({
                        color: '#059669',
                        weight: 2,
                        fillColor: '#34D399',
                        fillOpacity: 0.15,
                        dashArray: '4, 4'
                    });
                    hideAreaIntelligenceHUD();
                });

                poly.on('click', function() {
                    map.fitBounds(poly.getBounds(), { padding: [40, 40] });
                    const info = analyzeAreaIntelligence(p.purok_name);
                    showAreaIntelligenceHUD(info);
                });

                puroksLayerGroup.addLayer(poly);
                purokLayerMap[p.purok_name.toLowerCase()] = poly;
            } catch(e) {}
        }
    });

    // -------------------------------------------------------
    // 4. LANDMARKS LAYER
    // -------------------------------------------------------
    const landmarks = <?php echo json_encode($landmarks ?: []); ?>;
    const landmarksLayerGroup = L.layerGroup().addTo(map);

    landmarks.forEach(lm => {
        const lat = parseFloat(lm.latitude);
        const lng = parseFloat(lm.longitude);
        if (!isNaN(lat) && !isNaN(lng)) {
            const icon = L.divIcon({
                html: `<div style="background:#0B2E22;color:#ffffff;width:24px;height:24px;border-radius:8px;display:flex;align-items:center;justify-content:center;border:2px solid #34D399;box-shadow:0 3px 8px rgba(0,0,0,0.3);"><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='#ffffff' stroke-width='2.5'><path d='M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z'/></svg></div>`,
                className: '', iconSize: [24, 24], iconAnchor: [12, 12]
            });
            const marker = L.marker([lat, lng], { icon });
            marker.bindPopup(`
                <div style="padding:14px; min-width:200px; font-family:'Miranda Sans',sans-serif;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                        <span style="display:flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0B2E22" stroke-width="2.5"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg></span>
                        <h4 style="margin:0; font-size:14px; font-weight:800; color:#0B2E22;">${lm.landmark_name}</h4>
                    </div>
                    <span style="display:inline-block; font-size:10px; font-weight:800; color:#059669; background:#ecfdf5; padding:2px 8px; border-radius:99px; border:1px solid #a7f3d0; margin-bottom:6px;">
                        ${lm.landmark_type || 'Barangay Facility'}
                    </span>
                    ${lm.description ? `<p style="font-size:11px; color:#475569; margin:4px 0 0; line-height:1.4;">${lm.description}</p>` : ''}
                </div>
            `);
            landmarksLayerGroup.addLayer(marker);
        }
    });

    // -------------------------------------------------------
    // 5. DYNAMIC INCIDENT PINS & HEATMAP LAYER
    // -------------------------------------------------------
    let rawReports = <?php echo json_encode($reports ?: []); ?>;
    let markersLayerGroup = L.layerGroup().addTo(map);
    let heatLayer = null;
    let markerIdMap = {};

    const statusBadgeColors = {
        'Pending':     { color: '#F59E0B', bg: '#FEF3C7', text: '#B45309' },
        'Verified':    { color: '#0284C7', bg: '#E0F2FE', text: '#0369A1' },
        'In Progress': { color: '#F97316', bg: '#FFEDD5', text: '#C2410C' },
        'Resolved':    { color: '#059669', bg: '#D1FAE5', text: '#047857' },
        'Rejected':    { color: '#DC2626', bg: '#FEE2E2', text: '#B91C1C' }
    };

    function getPinIcon(status) {
        const cfg = statusBadgeColors[status] || { color: '#64748B' };
        return L.divIcon({
            html: `<div style="background:${cfg.color}; width:15px; height:15px; border-radius:50%; border:2.5px solid #FFFFFF; box-shadow:0 2px 8px rgba(0,0,0,0.35);"></div>`,
            className: '', iconSize: [15, 15], iconAnchor: [7.5, 7.5]
        });
    }

    function buildIncidentPopup(r) {
        const cfg = statusBadgeColors[r.status] || { bg: '#F1F5F9', text: '#475569' };
        const dateStr = r.submission_date ? new Date(r.submission_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '';
        const imgHtml = r.photo_path 
            ? `<div style="width:100%; height:110px; overflow:hidden; background:#0f172a; margin-bottom:10px; border-radius:10px;">
                 <img src="/brgy-waste-app-v3/public/uploads/${r.photo_path}" style="width:100%; height:100%; object-fit:cover;" onerror="this.style.display='none'">
               </div>`
            : '';

        return `
            <div style="font-family:'Miranda Sans',sans-serif; min-width:210px; max-width:250px; padding:14px;">
                ${imgHtml}
                <div style="display:flex; align-items:center; justify-content:space-between; gap:6px; margin-bottom:6px;">
                    <span style="background:${cfg.bg}; color:${cfg.text}; font-size:10px; font-weight:800; padding:2px 8px; border-radius:99px; text-transform:uppercase;">
                        ${r.status}
                    </span>
                    <span style="font-size:11px; font-weight:700; color:#64748b;">#${r.id}</span>
                </div>
                <h4 style="font-size:13px; font-weight:800; color:#0f172a; margin:0 0 3px;">${r.waste_category || 'Unclassified Waste'}</h4>
                <p style="font-size:11px; color:#475569; margin:0 0 4px; font-weight:600;">Purok: <b>${r.purok || 'N/A'}</b></p>
                ${r.description ? `<p style="font-size:11px; color:#64748b; margin:0 0 8px; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">${r.description}</p>` : ''}
                <div style="display:flex; align-items:center; justify-content:space-between; padding-top:8px; border-top:1px solid #f1f5f9; margin-top:6px;">
                    <span style="font-size:10px; color:#94a3b8; font-weight:600;">${dateStr}</span>
                    <a href="/brgy-waste-app-v3/public/admin/viewReport/${r.id}" target="_blank" 
                       style="font-size:11px; font-weight:800; color:#059669; text-decoration:none;">View Full Report &rarr;</a>
                </div>
            </div>
        `;
    }

    function renderIncidentLayer(reportList) {
        markersLayerGroup.clearLayers();
        markerIdMap = {};
        if (heatLayer) {
            map.removeLayer(heatLayer);
            heatLayer = null;
        }

        const heatData = [];
        const streamContainer = document.getElementById('incidentsStreamList');
        if (streamContainer) streamContainer.innerHTML = '';

        // Calculate purok report counts for dynamic weighting
        const purokCountMap = {};
        reportList.forEach(r => {
            if (r.purok) {
                purokCountMap[r.purok.toLowerCase()] = (purokCountMap[r.purok.toLowerCase()] || 0) + 1;
            }
        });

        reportList.forEach(r => {
            const lat = parseFloat(r.latitude);
            const lng = parseFloat(r.longitude);

            if (!isNaN(lat) && !isNaN(lng)) {
                // Pin Marker
                const marker = L.marker([lat, lng], { icon: getPinIcon(r.status) })
                    .bindPopup(buildIncidentPopup(r));

                // Hovering on marker shows area HUD
                marker.on('mouseover', function() {
                    if (r.purok) {
                        const info = analyzeAreaIntelligence(r.purok);
                        showAreaIntelligenceHUD(info);
                    }
                });
                marker.on('mouseout', function() {
                    hideAreaIntelligenceHUD();
                });
                
                markersLayerGroup.addLayer(marker);
                markerIdMap[r.id] = { marker, lat, lng };

                // Heat Density Weighting:
                // <= 3 reports: Low Density (weight 0.35)
                // 4 - 8 reports: Medium Density (weight 0.70)
                // >= 9 reports: High Density (weight 1.00)
                const pCount = r.purok ? (purokCountMap[r.purok.toLowerCase()] || 1) : 1;
                let weight = 0.35;
                if (pCount >= 9) {
                    weight = 1.0;
                } else if (pCount >= 4) {
                    weight = 0.70;
                }
                if (r.status === 'Pending') {
                    weight = Math.min(weight, 0.45);
                }

                heatData.push([lat, lng, weight]);

                // Stream List Item
                if (streamContainer) {
                    const cfg = statusBadgeColors[r.status] || { bg: '#F1F5F9', text: '#475569' };
                    const dateFormatted = r.submission_date ? new Date(r.submission_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '';
                    
                    const item = document.createElement('div');
                    item.className = 'p-3 rounded-xl bg-slate-50 hover:bg-emerald-50/50 border border-slate-200 hover:border-emerald-300 transition cursor-pointer group shadow-2xs';
                    item.innerHTML = `
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-[10px] font-mono font-bold text-slate-400">#${r.id}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold" style="background:${cfg.bg}; color:${cfg.text};">${r.status}</span>
                                </div>
                                <p class="text-xs font-extrabold text-slate-900 truncate">${r.waste_category || 'Waste Report'}</p>
                                <p class="text-[11px] font-semibold text-slate-500 truncate">${r.purok || 'Barangay Area'}</p>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 shrink-0">${dateFormatted}</span>
                        </div>
                    `;
                    item.onclick = function() {
                        map.flyTo([lat, lng], 17, { duration: 1 });
                        setTimeout(() => marker.openPopup(), 1050);
                    };
                    streamContainer.appendChild(item);
                }
            }
        });

        // Update counts
        const headerCount = document.getElementById('headerReportCount');
        const kpiCount = document.getElementById('kpiTotalMapped');
        const streamCount = document.getElementById('streamCount');
        if (headerCount) headerCount.textContent = `${reportList.length} Mapped Incident Points`;
        if (kpiCount) kpiCount.textContent = reportList.length;
        if (streamCount) streamCount.textContent = `${reportList.length} Loaded`;

        // Render Heat Layer using settings colors
        if (heatData.length > 0) {
            const lowC = '<?php echo htmlspecialchars($heatmap_settings['low_density_color'] ?? '#FDE68A'); ?>';
            const medC = '<?php echo htmlspecialchars($heatmap_settings['medium_density_color'] ?? '#F97316'); ?>';
            const highC = '<?php echo htmlspecialchars($heatmap_settings['high_density_color'] ?? '#EF4444'); ?>';

            heatLayer = L.heatLayer(heatData, {
                radius: <?php echo (int)($heatmap_settings['radius_meters'] ?? 40); ?>,
                blur: 18,
                maxZoom: 16,
                gradient: { 0.0: lowC, 0.5: medC, 1.0: highC }
            });
            if (document.getElementById('toggleHeatmap').classList.contains('active')) {
                heatLayer.addTo(map);
            }
        }
    }

    // Initial render
    renderIncidentLayer(rawReports);

    // -------------------------------------------------------
    // 6. FILTERING & SEARCH INTERACTIONS
    // -------------------------------------------------------
    let currentCategoryId = 0;

    window.filterCategory = function(catId) {
        currentCategoryId = catId;
        document.querySelectorAll('.category-filter').forEach(btn => {
            btn.classList.remove('bg-[#0B2E22]', 'text-white', 'border-emerald-900');
            btn.classList.add('bg-slate-100', 'text-slate-700', 'border-slate-200');
        });
        const activeBtn = document.querySelector(`[data-category="${catId}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('bg-slate-100', 'text-slate-700', 'border-slate-200');
            activeBtn.classList.add('bg-[#0B2E22]', 'text-white', 'border-emerald-900');
        }
        applyFilters();
    };

    function applyFilters() {
        const purokVal = document.getElementById('purokFilter').value;
        const statusVal = document.getElementById('statusFilter').value;
        const searchVal = document.getElementById('gisSearchInput').value.trim().toLowerCase();

        let url = '/brgy-waste-app-v3/public/admin/getGisData?';
        if (currentCategoryId > 0) url += `category=${currentCategoryId}&`;
        if (purokVal > 0) url += `purok=${purokVal}&`;
        if (statusVal) url += `status=${encodeURIComponent(statusVal)}&`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.reports)) {
                    let filtered = data.reports;
                    if (searchVal) {
                        filtered = filtered.filter(r => 
                            (r.resident_name && r.resident_name.toLowerCase().includes(searchVal)) ||
                            (r.purok && r.purok.toLowerCase().includes(searchVal)) ||
                            (r.location && r.location.toLowerCase().includes(searchVal)) ||
                            (r.waste_category && r.waste_category.toLowerCase().includes(searchVal)) ||
                            (String(r.id) === searchVal.replace('#', ''))
                        );
                    }
                    renderIncidentLayer(filtered);
                }
            })
            .catch(err => console.error('Filter request error:', err));
    }

    document.getElementById('purokFilter')?.addEventListener('change', applyFilters);
    document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
    
    // Live Search with Debounce
    let searchDebounce = null;
    document.getElementById('gisSearchInput')?.addEventListener('input', function() {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(applyFilters, 250);
    });

    // Reset Filters
    document.getElementById('btnResetFilters')?.addEventListener('click', function() {
        document.getElementById('gisSearchInput').value = '';
        document.getElementById('purokFilter').value = '0';
        document.getElementById('statusFilter').value = '';
        filterCategory(0);
    });

    // -------------------------------------------------------
    // 7. LAYER TOGGLES & MAP UTILITIES
    // -------------------------------------------------------
    
    // Heatmap Toggle
    const btnHeatmap = document.getElementById('toggleHeatmap');
    btnHeatmap?.addEventListener('click', function() {
        this.classList.toggle('active');
        if (this.classList.contains('active')) {
            this.classList.add('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.remove('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (heatLayer && !map.hasLayer(heatLayer)) map.addLayer(heatLayer);
        } else {
            this.classList.remove('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.add('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (heatLayer && map.hasLayer(heatLayer)) map.removeLayer(heatLayer);
        }
    });

    // Markers Toggle
    const btnMarkers = document.getElementById('toggleMarkers');
    btnMarkers?.addEventListener('click', function() {
        this.classList.toggle('active');
        if (this.classList.contains('active')) {
            this.classList.add('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.remove('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (!map.hasLayer(markersLayerGroup)) map.addLayer(markersLayerGroup);
        } else {
            this.classList.remove('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.add('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (map.hasLayer(markersLayerGroup)) map.removeLayer(markersLayerGroup);
        }
    });

    // Landmarks Toggle
    const btnLandmarks = document.getElementById('toggleLandmarks');
    btnLandmarks?.addEventListener('click', function() {
        this.classList.toggle('active');
        if (this.classList.contains('active')) {
            this.classList.add('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.remove('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (!map.hasLayer(landmarksLayerGroup)) map.addLayer(landmarksLayerGroup);
        } else {
            this.classList.remove('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.add('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (map.hasLayer(landmarksLayerGroup)) map.removeLayer(landmarksLayerGroup);
        }
    });

    // Purok Zones Toggle
    const btnPuroks = document.getElementById('togglePuroks');
    btnPuroks?.addEventListener('click', function() {
        this.classList.toggle('active');
        if (this.classList.contains('active')) {
            this.classList.add('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.remove('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (!map.hasLayer(puroksLayerGroup)) map.addLayer(puroksLayerGroup);
        } else {
            this.classList.remove('bg-emerald-100', 'text-emerald-950', 'border-emerald-300');
            this.classList.add('bg-slate-100', 'text-slate-600', 'border-slate-200');
            if (map.hasLayer(puroksLayerGroup)) map.removeLayer(puroksLayerGroup);
        }
    });

    // Reset View
    document.getElementById('btnResetView')?.addEventListener('click', function() {
        try {
            map.fitBounds(boundaryLayer.getBounds(), { padding: [25, 25] });
        } catch(e) {
            map.setView(mapCenter, 15);
        }
    });

    // Fullscreen Toggle
    const mapContainer = document.getElementById('gisMapContainer');
    document.getElementById('btnFullscreenMap')?.addEventListener('click', function() {
        mapContainer.classList.toggle('fullscreen-mode');
        setTimeout(() => map.invalidateSize(), 300);
    });

    // Global Fly Helpers for Panels
    window.flyToPurokName = function(purokName) {
        const poly = purokLayerMap[purokName.toLowerCase()];
        if (poly) {
            map.fitBounds(poly.getBounds(), { padding: [40, 40] });
            poly.openTooltip();
        } else {
            const match = rawReports.find(r => r.purok && r.purok.toLowerCase() === purokName.toLowerCase());
            if (match && match.latitude && match.longitude) {
                map.flyTo([parseFloat(match.latitude), parseFloat(match.longitude)], 16);
            }
        }
    };

    window.flyToCoords = function(lat, lng, name) {
        map.flyTo([lat, lng], 17, { duration: 1.2 });
    };

    // Tab Switching
    window.switchTab = function(tabName) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-slate-900', 'shadow-xs', 'border', 'border-slate-200');
            btn.classList.add('text-slate-500');
        });
        document.getElementById('tabContentHotspots').classList.add('hidden');
        document.getElementById('tabContentStream').classList.add('hidden');
        document.getElementById('tabContentLandmarks').classList.add('hidden');

        if (tabName === 'hotspots') {
            document.getElementById('tabBtnHotspots').classList.add('bg-white', 'text-slate-900', 'shadow-xs', 'border', 'border-slate-200');
            document.getElementById('tabContentHotspots').classList.remove('hidden');
        } else if (tabName === 'stream') {
            document.getElementById('tabBtnStream').classList.add('bg-white', 'text-slate-900', 'shadow-xs', 'border', 'border-slate-200');
            document.getElementById('tabContentStream').classList.remove('hidden');
        } else if (tabName === 'landmarks') {
            document.getElementById('tabBtnLandmarks').classList.add('bg-white', 'text-slate-900', 'shadow-xs', 'border', 'border-slate-200');
            document.getElementById('tabContentLandmarks').classList.remove('hidden');
        }
    };

    // Initial fit boundary
    try {
        map.fitBounds(boundaryLayer.getBounds(), { padding: [20, 20] });
    } catch(e) {}

    setTimeout(() => map.invalidateSize(), 300);
    window.addEventListener('resize', () => map.invalidateSize());
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>