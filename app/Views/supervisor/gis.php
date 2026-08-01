<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$reports = $data['reports'] ?? [];
$statuses = $data['statuses'] ?? [];
$categories = $data['categories'] ?? [];
$puroks = $data['puroks'] ?? [];
$hotspots = $data['hotspots'] ?? [];
$total_mapped = $data['total_mapped'] ?? 0;
$active_hotspots_count = $data['active_hotspots_count'] ?? 0;
$highest_purok = $data['highest_purok'] ?? 'N/A';
$heatmap_settings = $data['heatmap_settings'] ?? null;
$current_view = $data['current_view'] ?? 'map';

// Get filter values from GET
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$purokFilter = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Prepare report data for JavaScript
$reportData = [];
foreach ($reports as $r) {
    $reportData[] = [
        'id' => $r['id'],
        'lat' => (float)$r['latitude'],
        'lng' => (float)$r['longitude'],
        'status' => $r['status'],
        'status_color' => $r['status_color'] ?? '#6B7280',
        'category' => $r['waste_category'] ?? 'N/A',
        'purok' => $r['purok'] ?? 'N/A',
        'reporter' => $r['resident_name'] ?? 'N/A',
        'date' => date('M d, Y', strtotime($r['submission_date'])),
        'submission_date' => $r['submission_date']
    ];
}

function getStatusBadge($status) {
    $map = [
        'Pending'     => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Pending'],
        'Verified'    => ['bg' => '#DCFCE7', 'text' => '#15803D', 'label' => 'Verified'],
        'Resolved'    => ['bg' => '#E0F2FE', 'text' => '#0369A1', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => '#FEE2E2', 'text' => '#B91C1C', 'label' => 'Rejected'],
        'In Progress' => ['bg' => '#FFEDD5', 'text' => '#C2410C', 'label' => 'In Progress'],
    ];
    return $map[$status] ?? ['bg' => '#F3F4F6', 'text' => '#4B5563', 'label' => $status];
}
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight truncate">GIS Monitoring</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">Interactive waste report map · Barangay Dulong Bayan</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-slate-500 font-medium"><?php echo count($reports); ?> reports</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Filter Bar -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
                        <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                            <input type="hidden" name="view" value="<?php echo $current_view; ?>">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                                <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                    <option value="">All Status</option>
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?php echo $s['status_name']; ?>" <?php echo $statusFilter == $s['status_name'] ? 'selected' : ''; ?>><?php echo $s['status_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Category</label>
                                <select name="category" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                    <option value="0">All Categories</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?php echo $c['category_id']; ?>" <?php echo $categoryFilter == $c['category_id'] ? 'selected' : ''; ?>><?php echo $c['category_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Purok</label>
                                <select name="purok" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                    <option value="0">All Puroks</option>
                                    <?php foreach ($puroks as $p): ?>
                                        <option value="<?php echo $p['purok_id']; ?>" <?php echo $purokFilter == $p['purok_id'] ? 'selected' : ''; ?>><?php echo $p['purok_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Date From</label>
                                <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Date To</label>
                                <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                            </div>
                            <div class="flex items-end gap-2 col-span-full sm:col-span-1">
                                <button type="submit" class="w-full rounded-xl bg-[#10B981] hover:bg-emerald-600 text-white font-semibold px-4 py-2 text-sm transition">Apply</button>
                                <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/gis&view=<?php echo $current_view; ?>" class="w-full rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold px-4 py-2 text-sm text-center transition">Clear</a>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Switcher -->
                    <div class="flex items-center gap-2 mb-4">
                        <a href="?view=map<?php echo http_build_query(array_filter(['status' => $statusFilter, 'category' => $categoryFilter, 'purok' => $purokFilter, 'date_from' => $dateFrom, 'date_to' => $dateTo])); ?>" 
                           class="rounded-full px-4 py-2 text-sm font-semibold transition <?php echo $current_view == 'map' ? 'bg-[#10B981] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            Report Map
                        </a>
                        <a href="?view=heatmap<?php echo http_build_query(array_filter(['status' => $statusFilter, 'category' => $categoryFilter, 'purok' => $purokFilter, 'date_from' => $dateFrom, 'date_to' => $dateTo])); ?>" 
                           class="rounded-full px-4 py-2 text-sm font-semibold transition <?php echo $current_view == 'heatmap' ? 'bg-[#10B981] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2v4M12 22v-4M2 12h4M22 12h-4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            Heatmap
                        </a>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        <!-- Map Container (8/12) -->
                        <div class="lg:col-span-8">
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 relative min-h-[520px]">
                                <div id="gisMap" class="w-full h-[520px] rounded-xl bg-emerald-50/40 border border-emerald-100 overflow-hidden"></div>
                                <!-- Map Legend -->
                                <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-sm rounded-xl p-3 shadow-md border border-slate-100 text-xs">
                                    <p class="font-bold text-slate-700 mb-1.5">Legend</p>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Pending</div>
                                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Verified</div>
                                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-cyan-500"></span> Resolved</div>
                                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Rejected</div>
                                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-orange-500"></span> In Progress</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel (4/12) -->
                        <div class="lg:col-span-4 space-y-6">

                            <!-- Map Summary -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                                <h3 class="text-sm font-bold text-slate-900 mb-3">Map Summary</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between items-center py-1 border-b border-slate-100">
                                        <span class="text-slate-500">Total Mapped</span>
                                        <span class="font-bold text-slate-900"><?php echo $total_mapped; ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-1 border-b border-slate-100">
                                        <span class="text-slate-500">Active Hotspots</span>
                                        <span class="font-bold text-red-600"><?php echo $active_hotspots_count; ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-1">
                                        <span class="text-slate-500">Highest Concern</span>
                                        <span class="font-bold text-slate-900"><?php echo htmlspecialchars($highest_purok); ?></span>
                                    </div>
                                    <?php if ($heatmap_settings): ?>
                                    <div class="flex justify-between items-center py-1 border-t border-slate-100 mt-2 pt-2 text-xs text-slate-400">
                                        <span>Heatmap Radius</span>
                                        <span><?php echo $heatmap_settings['radius_meters'] ?? 50; ?> m</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Active Hotspots -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                                <h3 class="text-sm font-bold text-slate-900 mb-3">Active Hotspots</h3>
                                <?php if (!empty($hotspots)): ?>
                                    <div class="space-y-3">
                                        <?php foreach ($hotspots as $idx => $spot): 
                                            $severity = $spot['report_count'] >= 10 ? 'HIGH' : ($spot['report_count'] >= 5 ? 'MEDIUM' : 'LOW');
                                            $severityBg = $severity == 'HIGH' ? '#FEE2E2' : ($severity == 'MEDIUM' ? '#FEF3C7' : '#DCFCE7');
                                            $severityText = $severity == 'HIGH' ? '#DC2626' : ($severity == 'MEDIUM' ? '#D97706' : '#15803D');
                                            $dotColor = $severity == 'HIGH' ? '#DC2626' : ($severity == 'MEDIUM' ? '#D97706' : '#10B981');
                                        ?>
                                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/70 border border-slate-100">
                                            <span class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0" style="background: <?php echo $dotColor; ?>"></span>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="font-bold text-slate-800 text-sm truncate"><?php echo htmlspecialchars($spot['purok_name']); ?></span>
                                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold flex-shrink-0" style="background: <?php echo $severityBg; ?>; color: <?php echo $severityText; ?>;"><?php echo $severity; ?></span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-0.5"><?php echo $spot['report_count']; ?> reports · <?php echo htmlspecialchars($spot['dominant_category'] ?? 'Various'); ?></p>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-sm text-slate-500 text-center py-4">No active hotspots detected</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- Leaflet & Heatmap Libraries -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Report data passed from PHP
    const reports = <?php echo json_encode($reportData); ?>;
    const currentView = '<?php echo $current_view; ?>';

    // Center map on barangay
    const centerLat = 15.558;
    const centerLng = 120.803;

    // Initialize map
    const map = L.map('gisMap', {
        center: [centerLat, centerLng],
        zoom: 15,
        zoomControl: true
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OSM',
        className: 'map-tiles'
    }).addTo(map);

    // Add grayscale filter for cleaner look
    document.head.insertAdjacentHTML('beforeend', '<style>.map-tiles { filter: grayscale(0.3) opacity(0.9); }</style>');

    // Status colors for markers
    const statusColors = {
        'Pending': '#F59E0B',
        'Verified': '#10B981',
        'Resolved': '#06B6D4',
        'Rejected': '#EF4444',
        'In Progress': '#F97316'
    };

    // --- Function to clear layers ---
    let markerLayer = L.layerGroup().addTo(map);
    let heatLayer = null;

    function updateMap(view) {
        // Clear existing layers
        markerLayer.clearLayers();
        if (heatLayer) {
            map.removeLayer(heatLayer);
            heatLayer = null;
        }

        if (view === 'map') {
            // Add markers
            reports.forEach(r => {
                const color = statusColors[r.status] || '#9CA3AF';
                const icon = L.divIcon({
                    html: `<div style="background: ${color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                    className: '',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });
                const marker = L.marker([r.lat, r.lng], { icon: icon })
                    .bindPopup(`
                        <strong>Report #${r.id}</strong><br>
                        Category: ${r.category}<br>
                        Purok: ${r.purok}<br>
                        Status: ${r.status}<br>
                        Date: ${r.date}
                    `);
                markerLayer.addLayer(marker);
            });
        } else if (view === 'heatmap') {
            // Prepare heat data: [lat, lng, intensity]
            const heatData = reports.map(r => [r.lat, r.lng, 0.6]);
            if (heatData.length > 0) {
                heatLayer = L.heatLayer(heatData, {
                    radius: <?php echo $heatmap_settings['radius_meters'] ?? 50; ?>,
                    blur: 20,
                    maxZoom: 17,
                    gradient: {
                        0.0: '<?php echo $heatmap_settings['low_density_color'] ?? '#FDE68A'; ?>',
                        0.5: '<?php echo $heatmap_settings['medium_density_color'] ?? '#F97316'; ?>',
                        1.0: '<?php echo $heatmap_settings['high_density_color'] ?? '#EF4444'; ?>'
                    }
                }).addTo(map);
            }
        }

        // Refresh map size after changes
        setTimeout(() => map.invalidateSize(), 100);
    }

    // Initial render
    updateMap(currentView);

    // Handle tab switching via URL (already done by PHP, but we can listen for clicks if needed)
    // For now, we rely on full page reloads when tabs are clicked.



    // Resize map on window resize
    window.addEventListener('resize', function() {
        map.invalidateSize();
    });

    // Fix map sizing after sidebar toggle (mobile)
    document.addEventListener('click', function() {
        setTimeout(() => map.invalidateSize(), 300);
    });
});

    // --- HOTSPOT HOVER & CLICK (ADD THIS BLOCK) ---
    let hotspotLayer = null;
    let hotspotTooltip = null;

    // Function to load hotspot data
    function loadHotspots() {
        // Clear existing hotspot layer
        if (hotspotLayer) {
            map.removeLayer(hotspotLayer);
        }
        
        // Fetch hotspot data with current filters
        const params = new URLSearchParams(window.location.search);
        fetch('/brgy-waste-app-v3/public/supervisor/getHotspots?' + params.toString())
            .then(response => response.json())
            .then(data => {
                if (data.hotspots && data.hotspots.length > 0) {
                    // Create GeoJSON layer for hotspots
                    const geojson = {
                        type: 'FeatureCollection',
                        features: data.hotspots.map(h => ({
                            type: 'Feature',
                            properties: {
                                purok: h.purok_name,
                                count: h.report_count,
                                category: h.dominant_category,
                                severity: h.severity || 'medium'
                            },
                            geometry: {
                                type: 'Point',
                                coordinates: [h.lng || 120.803, h.lat || 15.558]
                            }
                        }))
                    };
                    
                    hotspotLayer = L.geoJSON(geojson, {
                        pointToLayer: function(feature, latlng) {
                            const severity = feature.properties.severity;
                            const radius = severity === 'high' ? 20 : severity === 'medium' ? 14 : 10;
                            const color = severity === 'high' ? '#EF4444' : severity === 'medium' ? '#F97316' : '#10B981';
                            
                            return L.circleMarker(latlng, {
                                radius: radius,
                                fillColor: color,
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.7
                            });
                        }
                    }).addTo(map);
                    
                    // Hover event
                    hotspotLayer.on('mouseover', function(e) {
                        const props = e.layer.feature.properties;
                        const popupContent = `
                            <div class="p-2 min-w-[180px]">
                                <p class="font-bold text-sm">📍 ${props.purok}</p>
                                <p class="text-xs text-slate-600">${props.count} reports</p>
                                <p class="text-xs text-slate-500">${props.category || 'Various'}</p>
                                <p class="text-xs font-semibold mt-1 ${props.severity === 'high' ? 'text-red-600' : props.severity === 'medium' ? 'text-orange-600' : 'text-emerald-600'}">
                                    Priority: ${props.severity.toUpperCase()}
                                </p>
                                <p class="text-xs text-slate-400 mt-1">💡 ${getSuggestedAction(props)}</p>
                            </div>
                        `;
                        
                        if (hotspotTooltip) {
                            map.closePopup(hotspotTooltip);
                        }
                        hotspotTooltip = L.popup({
                            closeButton: true,
                            className: 'hotspot-tooltip',
                            offset: [0, -20]
                        })
                        .setLatLng(e.latlng)
                        .setContent(popupContent)
                        .openOn(map);
                        
                        // Highlight the marker
                        e.layer.setStyle({
                            fillOpacity: 1,
                            radius: e.layer.options.radius * 1.3
                        });
                    });
                    
                    hotspotLayer.on('mouseout', function(e) {
                        if (hotspotTooltip) {
                            map.closePopup(hotspotTooltip);
                            hotspotTooltip = null;
                        }
                        e.layer.setStyle({
                            fillOpacity: 0.7,
                            radius: e.layer.options.radius
                        });
                    });
                    
                    // Click event - open details panel
                    hotspotLayer.on('click', function(e) {
                        const props = e.layer.feature.properties;
                        showHotspotDetails(props);
                    });
                }
            })
            .catch(error => console.error('Error loading hotspots:', error));
    }

    // Function to get suggested action
    function getSuggestedAction(props) {
        const count = props.count || 0;
        const category = props.category || '';
        
        if (category.includes('Illegal Dumping')) {
            return 'Conduct site inspection and investigate';
        } else if (category.includes('Overflowing') || category.includes('Garbage Bin')) {
            return 'Increase collection frequency';
        } else if (category.includes('Blocking Drainage')) {
            return 'Coordinate immediate clearing';
        } else if (count >= 10) {
            return 'Schedule immediate collection review';
        } else if (count >= 5) {
            return 'Monitor area closely';
        } else {
            return 'Continue regular monitoring';
        }
    }

    // Show hotspot details panel
    function showHotspotDetails(props) {
        // Create or show a details panel
        let panel = document.getElementById('hotspotDetails');
        if (!panel) {
            panel = document.createElement('div');
            panel.id = 'hotspotDetails';
            panel.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            panel.innerHTML = `
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
                        <h2 class="text-xl font-bold text-slate-900">Hotspot Details</h2>
                        <button onclick="this.closest('#hotspotDetails').remove()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <div id="hotspotContent" class="p-6">
                        Loading...
                    </div>
                </div>
            `;
            document.body.appendChild(panel);
        }
        
        // Load detailed data
        const content = document.getElementById('hotspotContent');
        content.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
            </div>
        `;
        
        fetch(`/brgy-waste-app-v3/public/supervisor/getHotspotDetails?purok=${encodeURIComponent(props.purok)}`)
            .then(response => response.json())
            .then(data => {
                content.innerHTML = `
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <p class="text-3xl font-black text-slate-900">${data.total_reports || 0}</p>
                            <p class="text-xs text-slate-500">Total Reports</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <p class="text-3xl font-black text-emerald-600">${data.resolved || 0}</p>
                            <p class="text-xs text-slate-500">Resolved</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <p class="text-3xl font-black text-amber-600">${data.pending || 0}</p>
                            <p class="text-xs text-slate-500">Pending</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                            <p class="text-3xl font-black text-purple-600">${data.total_supports || 0}</p>
                            <p class="text-xs text-slate-500">Total Supports</p>
                        </div>
                    </div>
                    
                    <h4 class="font-bold text-slate-800 mb-2">Category Distribution</h4>
                    <div class="space-y-2 mb-4">
                        ${Object.entries(data.categories || {}).map(([name, count]) => `
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">${name}</span>
                                <span class="font-bold text-slate-800">${count}</span>
                            </div>
                        `).join('') || '<p class="text-sm text-slate-400">No category data</p>'}
                    </div>
                    
                    <h4 class="font-bold text-slate-800 mb-2">Suggested Actions</h4>
                    <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                        <p class="text-sm text-emerald-800">${data.suggested_action || 'Continue regular monitoring'}</p>
                    </div>
                `;
            });
    }

    // Load hotspots on page load (call after map initialization)
    loadHotspots();

    // Also reload when filters change
    document.querySelectorAll('select, input[type="date"]').forEach(el => {
        el.addEventListener('change', function() {
            loadHotspots();
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>