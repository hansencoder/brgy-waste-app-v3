<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$reports = $data['reports'] ?? [];
$statuses = $data['statuses'] ?? [];
$categories = $data['categories'] ?? [];
$puroks = $data['puroks'] ?? [];
$hotspots = $data['hotspots'] ?? [];
$total_mapped = (int)($data['total_mapped'] ?? count($reports));
$active_hotspots_count = (int)($data['active_hotspots_count'] ?? count($hotspots));
$highest_purok = $data['highest_purok'] ?? 'N/A';
$heatmap_settings = $data['heatmap_settings'] ?? null;
$current_view = $data['current_view'] ?? 'map';

// Filter parameters
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = (int)($_GET['category'] ?? 0);
$purokFilter = (int)($_GET['purok'] ?? 0);
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

// Prepare JS report data
$reportData = [];
foreach ($reports as $r) {
    $reportData[] = [
        'id' => $r['id'],
        'lat' => (float)$r['latitude'],
        'lng' => (float)$r['longitude'],
        'status' => $r['status'] ?? 'Pending',
        'status_color' => $r['status_color'] ?? '#10B981',
        'category' => $r['waste_category'] ?? 'General',
        'purok' => $r['purok'] ?? 'Barangay Area',
        'reporter' => $r['resident_name'] ?? 'Reporter',
        'date' => date('M d, Y', strtotime($r['submission_date'])),
        'submission_date' => $r['submission_date']
    ];
}
?>

<div class="min-h-screen bg-[#F8FAFC] flex">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../layouts/supervisor_topbar.php'; ?>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto w-full">

            <!-- Header & Mode Switcher -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">GIS Operational Heatmap &amp; Map</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Real-time geospatial visualization of waste accumulation and incident reports</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="?view=map<?php echo http_build_query(array_filter(['status' => $statusFilter, 'category' => $categoryFilter, 'purok' => $purokFilter, 'date_from' => $dateFrom, 'date_to' => $dateTo])); ?>" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition <?php echo $current_view !== 'heatmap' ? 'bg-[#0B2E22] text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Pin Marker View</span>
                    </a>

                    <a href="?view=heatmap<?php echo http_build_query(array_filter(['status' => $statusFilter, 'category' => $categoryFilter, 'purok' => $purokFilter, 'date_from' => $dateFrom, 'date_to' => $dateTo])); ?>" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition <?php echo $current_view === 'heatmap' ? 'bg-[#0B2E22] text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2v4M12 22v-4M2 12h4M22 12h-4"/></svg>
                        <span>Heatmap Density</span>
                    </a>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-2xs">
                <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <input type="hidden" name="url" value="supervisor/gis">
                    <input type="hidden" name="view" value="<?php echo htmlspecialchars($current_view); ?>">

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?php echo $s['status_name']; ?>" <?php echo $statusFilter === $s['status_name'] ? 'selected' : ''; ?>><?php echo $s['status_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Category</label>
                        <select name="category" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                            <option value="0">All Categories</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo $c['category_id']; ?>" <?php echo $categoryFilter === (int)$c['category_id'] ? 'selected' : ''; ?>><?php echo $c['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Purok Area</label>
                        <select name="purok" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                            <option value="0">All Puroks</option>
                            <?php foreach ($puroks as $p): ?>
                                <option value="<?php echo $p['purok_id']; ?>" <?php echo $purokFilter === (int)$p['purok_id'] ? 'selected' : ''; ?>><?php echo $p['purok_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Date Range</label>
                        <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 h-10 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition">
                            Apply
                        </button>
                        <a href="<?php echo app_url('supervisor/gis?view=' . ($current_view)); ?>" class="h-10 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold transition flex items-center justify-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- GIS Workspace Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Main Map Frame (8 cols) -->
                <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs relative">
                    <div id="gisMap" class="w-full h-[540px] rounded-xl overflow-hidden bg-slate-100 border border-slate-200"></div>

                    <!-- Custom Map Legend Overlay -->
                    <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-md rounded-xl p-3 shadow-lg border border-slate-200/80 text-[11px] space-y-1.5 pointer-events-none z-[400]">
                        <p class="font-bold text-slate-800">Map Legend</p>
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> <span>Pending Review</span></div>
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> <span>Verified Report</span></div>
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> <span>Dispatched Crew</span></div>
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> <span>Resolved Incident</span></div>
                    </div>
                </div>

                <!-- Right Statistics & Hotspot Side Panel (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Map Summary KPI Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3.5">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Geospatial Summary</span>
                        
                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                                <span class="text-slate-500">Total Incidents Mapped</span>
                                <span class="font-bold text-slate-900 font-mono text-sm"><?php echo number_format($total_mapped); ?></span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-xl bg-red-50/70 border border-red-100">
                                <span class="text-red-800 font-medium">Critical Hotspot Zones</span>
                                <span class="font-bold text-red-600 font-mono text-sm"><?php echo number_format($active_hotspots_count); ?></span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                                <span class="text-slate-500">Highest Incident Purok</span>
                                <span class="font-bold text-slate-800 truncate"><?php echo htmlspecialchars($highest_purok); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Active Hotspots List -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Priority Zones</span>
                            <span class="text-[10px] text-slate-400 font-mono"><?php echo count($hotspots); ?> zones</span>
                        </div>

                        <div class="space-y-2.5 max-h-80 overflow-y-auto pr-1 custom-scrollbar">
                            <?php if (!empty($hotspots)): ?>
                                <?php foreach ($hotspots as $spot):
                                    $count = (int)($spot['report_count'] ?? 0);
                                    $severity = $count >= 10 ? 'Critical' : ($count >= 5 ? 'High' : 'Moderate');
                                    $sevBg = $count >= 10 ? 'bg-red-50 text-red-700 border-red-200' : ($count >= 5 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                                ?>
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-slate-800 truncate"><?php echo htmlspecialchars($spot['purok_name'] ?? 'Purok Area'); ?></span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold border <?php echo $sevBg; ?>"><?php echo $severity; ?></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 flex items-center justify-between">
                                        <span><?php echo htmlspecialchars($spot['dominant_category'] ?? 'Mixed Waste'); ?></span>
                                        <strong class="font-mono text-slate-700"><?php echo $count; ?> reports</strong>
                                    </p>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="py-6 text-center text-slate-400 text-xs">
                                    No active density clusters detected.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>

        </main>

    </div>
</div>

<!-- Leaflet & Heatmap Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reports = <?php echo json_encode($reportData); ?>;
    const currentView = '<?php echo $current_view; ?>';

    const centerLat = <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>;
    const centerLng = <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>;
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;

    const map = L.map('gisMap', {
        center: [centerLat, centerLng],
        zoom: defaultZoom,
        zoomControl: true
    });

    const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri',
        maxZoom: 19
    });
    const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: '',
        maxZoom: 19
    });
    const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19
    });

    satelliteMap.addTo(map);
    labelsMap.addTo(map);

    L.control.layers({
        "Satellite Imagery": L.layerGroup([satelliteMap, labelsMap]),
        "Street Map": streetMap
    }, null, { position: 'topright' }).addTo(map);

    // Dynamic Barangay Boundary
    const rawBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
    if (rawBrgyBoundary) {
        try {
            const brgyGeoObj = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            L.geoJSON(brgyGeoObj, {
                style: {
                    color: '#3B82F6',
                    weight: 2,
                    fillColor: '#3B82F6',
                    fillOpacity: 0.05,
                    dashArray: '5,5'
                }
            }).addTo(map);
        } catch(e) {}
    }

    // Dynamic Purok Boundaries
    const purokBoundariesData = <?php echo json_encode($data['purok_boundaries'] ?? []); ?>;
    purokBoundariesData.forEach(pb => {
        if (pb.polygon_geometry) {
            try {
                let geojson = (typeof pb.polygon_geometry === 'string') ? JSON.parse(pb.polygon_geometry) : pb.polygon_geometry;
                if (geojson) {
                    L.geoJSON(geojson, {
                        style: {
                            color: '#10B981',
                            weight: 1.5,
                            fillColor: '#10B981',
                            fillOpacity: 0.08
                        }
                    }).bindPopup('<strong>' + pb.purok_name + '</strong>').addTo(map);
                }
            } catch(e) {}
        }
    });

    if (currentView === 'heatmap') {
        const heatPoints = reports.map(r => [r.lat, r.lng, 0.8]);
        if (heatPoints.length > 0) {
            L.heatLayer(heatPoints, {
                radius: 35,
                blur: 20,
                maxZoom: 18,
                gradient: { 0.2: '#3B82F6', 0.4: '#10B981', 0.6: '#F59E0B', 0.8: '#EF4444' }
            }).addTo(map);
        }
    } else {
        const markerGroup = L.featureGroup();
        reports.forEach(r => {
            const statusColor = r.status === 'Resolved' ? '#10B981' : (r.status === 'Verified' ? '#3B82F6' : (r.status === 'In Progress' ? '#8B5CF6' : '#F59E0B'));
            const customIcon = L.divIcon({
                html: `<div style="background-color: ${statusColor}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.4);"></div>`,
                className: '',
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });

            const marker = L.marker([r.lat, r.lng], { icon: customIcon })
                .bindPopup(`
                    <div style="font-family: 'Miranda Sans', sans-serif; min-width: 160px;">
                        <span style="font-size: 10px; font-weight: bold; color: ${statusColor}; text-transform: uppercase;">${r.status}</span>
                        <h4 style="font-size: 12px; font-weight: bold; margin: 2px 0;">${r.category}</h4>
                        <p style="font-size: 11px; color: #64748B; margin: 0 0 6px 0; display: flex; align-items: center; gap: 4px;"><svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill="none" stroke='#64748B' stroke-width='2.5' fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg> ${r.purok}</p>
                        <a href="<?php echo app_url('supervisor/view_report/${r.id}'); ?>" style="display: inline-block; font-size: 11px; font-weight: bold; color: #10B981; text-decoration: none;">View Report Details →</a>
                    </div>
                `);
            markerGroup.addLayer(marker);
        });
        markerGroup.addTo(map);
    }

    setTimeout(() => map.invalidateSize(), 300);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>