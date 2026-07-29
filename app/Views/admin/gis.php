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

// Status colors for legend
$statusColors = [
    'Pending' => '#F59E0B',
    'Verified' => '#10B981',
    'Resolved' => '#06B6D4',
    'Rejected' => '#EF4444',
    'In Progress' => '#F97316'
];

// Helper for priority badge
function getPriorityBadge($count) {
    if ($count >= 25) {
        return ['label' => 'HIGH', 'bg' => '#FEE2E2', 'text' => '#DC2626'];
    } elseif ($count >= 15) {
        return ['label' => 'MEDIUM', 'bg' => '#FEF3C7', 'text' => '#D97706'];
    }
    return ['label' => 'LOW', 'bg' => '#DCFCE7', 'text' => '#15803D'];
}
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Page Header -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-extrabold text-slate-900">GIS Monitoring</h1>
                        <p class="text-sm text-slate-500">Interactive waste report map · Barangay Dulong Bayan</p>
                    </div>

                    <!-- Filter Controls -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <button onclick="filterCategory(0)" data-category="0" class="category-filter rounded-full px-4 py-2 text-sm font-semibold bg-[#10B981] text-white shadow-sm hover:bg-emerald-600 transition">All Categories</button>
                            <?php foreach ($categories as $cat): ?>
                                <button onclick="filterCategory(<?php echo $cat['category_id']; ?>)" data-category="<?php echo $cat['category_id']; ?>" class="category-filter rounded-full px-4 py-2 text-sm font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition"><?php echo htmlspecialchars($cat['category_name']); ?></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <select id="purokFilter" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                <option value="0">All Purok</option>
                                <?php foreach ($puroks as $p): ?>
                                    <option value="<?php echo $p['purok_id']; ?>"><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="statusFilter" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                <option value="">All Status</option>
                                <?php foreach ($statuses as $s): ?>
                                    <option value="<?php echo htmlspecialchars($s['status_name']); ?>"><?php echo htmlspecialchars($s['status_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Main Map + Hotspots Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        <!-- Left: Map -->
                        <div class="lg:col-span-8">
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 relative">
                                <div class="bg-emerald-50/60 rounded-xl overflow-hidden relative border border-emerald-100" style="min-height: 520px;">
                                    <div id="gisMap" class="w-full h-[520px]"></div>
                                    
                                    <!-- Legend Overlay (Bottom Left) -->
                                    <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm rounded-xl p-3 shadow-md border border-slate-100 text-xs">
                                        <p class="font-bold text-slate-800 mb-1.5">Legend</p>
                                        <div class="space-y-1">
                                            <?php foreach ($statusColors as $label => $color): ?>
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2.5 h-2.5 rounded-full" style="background: <?php echo $color; ?>;"></span>
                                                    <span class="text-slate-600"><?php echo $label; ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Hotspots & Summary -->
                        <div class="lg:col-span-4 space-y-6">

                            <!-- Card 1: Active Hotspots -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                                <h2 class="text-sm font-bold text-slate-900 mb-4">Active Hotspots</h2>
                                <div class="space-y-4">
                                    <?php if (!empty($active_hotspots)): ?>
                                        <?php foreach ($active_hotspots as $hotspot): 
                                            $priority = getPriorityBadge($hotspot['report_count']);
                                            $dotColor = $hotspot['report_count'] >= 25 ? '#EF4444' : ($hotspot['report_count'] >= 15 ? '#F97316' : '#10B981');
                                        ?>
                                        <div class="flex items-start gap-3">
                                            <span class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0" style="background: <?php echo $dotColor; ?>;"></span>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($hotspot['purok_name']); ?></p>
                                                <p class="text-xs text-slate-500"><?php echo $hotspot['report_count']; ?> reports · <?php echo htmlspecialchars($hotspot['dominant_category'] ?? 'Various'); ?></p>
                                            </div>
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold" style="background: <?php echo $priority['bg']; ?>; color: <?php echo $priority['text']; ?>;"><?php echo $priority['label']; ?></span>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-sm text-slate-500 text-center py-4">No active hotspots detected.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card 2: Map Summary -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                                <h2 class="text-sm font-bold text-slate-900 mb-4">Map Summary</h2>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">Total Mapped</span>
                                        <span class="font-bold text-slate-900"><?php echo $total_mapped; ?></span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">Active Hotspots</span>
                                        <span class="font-bold text-slate-900"><?php echo $active_hotspots_count; ?></span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">Highest Concern</span>
                                        <span class="font-bold text-slate-900"><?php echo htmlspecialchars($highest_purok); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-500">Heatmap Radius</span>
                                        <span class="font-bold text-slate-900"><?php echo $heatmap_settings['radius_meters'] ?? 40; ?> m</span>
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

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Initialize Map ---
    const map = L.map('gisMap', {
        center: [15.558, 120.803],
        zoom: 15,
        zoomControl: true
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        className: 'map-tiles'
    }).addTo(map);

    // --- Barangay Boundary (from your data) ---
    const boundary = {
        "type": "FeatureCollection",
        "features": [{
            "type": "Feature",
            "properties": {},
            "geometry": {
                "type": "Polygon",
                "coordinates": [[
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
                    [120.8014669, 15.5699463], [120.8013468, 15.5699463]
                ]]
            }
        }]
    };

    L.geoJSON(boundary, {
        style: {
            color: '#10B981',
            weight: 2.5,
            fillColor: '#D1FAE5',
            fillOpacity: 0.15,
            dashArray: '6, 6'
        }
    }).addTo(map);

    // --- Report Data from PHP ---
    const reports = <?php echo json_encode($reports ?: []); ?>;

    // Status color mapping
    const statusColors = {
        'Pending': '#F59E0B',
        'Verified': '#10B981',
        'Resolved': '#06B6D4',
        'Rejected': '#EF4444',
        'In Progress': '#F97316'
    };

    // Create custom marker icons
    function getMarkerIcon(status) {
        const color = statusColors[status] || '#6B7280';
        return L.divIcon({
            html: `<div style="background: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>`,
            className: '',
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });
    }

    // Add markers and prepare heatmap data
    const heatData = [];
    reports.forEach(report => {
        const lat = parseFloat(report.latitude);
        const lng = parseFloat(report.longitude);
        if (!isNaN(lat) && !isNaN(lng)) {
            // Add marker
            const icon = getMarkerIcon(report.status);
            const marker = L.marker([lat, lng], { icon: icon }).addTo(map);
            marker.bindPopup(`
                <strong>${report.id}</strong><br>
                ${report.waste_category || 'N/A'}<br>
                Status: ${report.status}<br>
                Purok: ${report.purok || 'N/A'}<br>
                <a href="/brgy-waste-app-v3/public/admin/viewReport/${report.id}" target="_blank">View Details</a>
            `);

            // Add to heat data (with intensity 0.6)
            heatData.push([lat, lng, 0.6]);
        }
    });

    // Add heatmap layer
    if (heatData.length > 0) {
        const heat = L.heatLayer(heatData, {
            radius: <?php echo $heatmap_settings['radius_meters'] ?? 40; ?>,
            blur: 20,
            maxZoom: 16,
            gradient: {
                0.0: '#FDE68A',
                0.3: '#F97316',
                0.6: '#EF4444',
                1.0: '#7F1D1D'
            }
        }).addTo(map);
    }

    // --- Filter Functions ---
    window.filterCategory = function(categoryId) {
        // Update button styles
        document.querySelectorAll('.category-filter').forEach(btn => {
            btn.classList.remove('bg-[#10B981]', 'text-white');
            btn.classList.add('bg-slate-100', 'text-slate-700');
        });
        document.querySelector(`[data-category="${categoryId}"]`)?.classList.add('bg-[#10B981]', 'text-white');
        
        applyFilters();
    };

    function applyFilters() {
        const category = document.querySelector('.category-filter.bg-\\[\\#10B981\\]')?.dataset?.category || 0;
        const purok = document.getElementById('purokFilter').value;
        const status = document.getElementById('statusFilter').value;

        // Build URL with filters
        let url = '/brgy-waste-app-v3/public/admin/getGisData?';
        if (category > 0) url += `category=${category}&`;
        if (purok > 0) url += `purok=${purok}&`;
        if (status) url += `status=${encodeURIComponent(status)}&`;

        // Fetch filtered data and update map
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear existing markers and heatmap
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.Marker || layer instanceof L.HeatLayer) {
                            map.removeLayer(layer);
                        }
                    });
                    
                    // Re-add boundary
                    L.geoJSON(boundary, {
                        style: {
                            color: '#10B981',
                            weight: 2.5,
                            fillColor: '#D1FAE5',
                            fillOpacity: 0.15,
                            dashArray: '6, 6'
                        }
                    }).addTo(map);

                    // Add new markers and heat data
                    const newHeatData = [];
                    data.reports.forEach(report => {
                        const lat = parseFloat(report.latitude);
                        const lng = parseFloat(report.longitude);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            const icon = getMarkerIcon(report.status);
                            const marker = L.marker([lat, lng], { icon: icon }).addTo(map);
                            marker.bindPopup(`
                                <strong>${report.id}</strong><br>
                                ${report.waste_category || 'N/A'}<br>
                                Status: ${report.status}<br>
                                Purok: ${report.purok || 'N/A'}<br>
                                <a href="/brgy-waste-app-v3/public/admin/viewReport/${report.id}" target="_blank">View Details</a>
                            `);
                            newHeatData.push([lat, lng, 0.6]);
                        }
                    });

                    if (newHeatData.length > 0) {
                        L.heatLayer(newHeatData, {
                            radius: <?php echo $heatmap_settings['radius_meters'] ?? 40; ?>,
                            blur: 20,
                            maxZoom: 16,
                            gradient: {
                                0.0: '#FDE68A',
                                0.3: '#F97316',
                                0.6: '#EF4444',
                                1.0: '#7F1D1D'
                            }
                        }).addTo(map);
                    }
                }
            })
            .catch(error => console.error('Error fetching filtered data:', error));
    }

    // Event listeners for filter changes
    document.getElementById('purokFilter')?.addEventListener('change', applyFilters);
    document.getElementById('statusFilter')?.addEventListener('change', applyFilters);

    // Resize map after loading
    setTimeout(() => map.invalidateSize(), 300);
});
</script>

<style>
    .map-tiles {
        filter: grayscale(40%) opacity(0.85);
    }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>