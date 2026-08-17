<?php
if (!isset($data) || !is_array($data)) {
    $data = [
        'error' => '',
        'success' => '',
        'puroks' => []
    ];
}
?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Leaflet Draw CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    
    /* Active Purok Selection */
    .purok-btn.active {
        background-color: #0B2E22 !important;
        color: white !important;
        border-color: #10B981 !important;
        box-shadow: 0 4px 12px rgba(11, 46, 34, 0.15);
    }
    .purok-btn.active span { color: white !important; }
    .purok-btn.active .badge-saved {
        background-color: #10B981 !important;
        color: white !important;
        border-color: #059669 !important;
    }

    /* Custom Leaflet Tooltip Styling */
    .purok-tooltip {
        background: #0B2E22 !important;
        color: #FFFFFF !important;
        border: 1px solid #10B981 !important;
        border-radius: 8px !important;
        font-family: 'Miranda Sans', sans-serif !important;
        font-optical-sizing: auto;
        font-weight: 800 !important;
        font-size: 13px !important;
        padding: 4px 10px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
</style>

<div class="min-h-screen bg-white text-slate-900 w-full flex font-sans antialiased">
    
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-teal-100 text-teal-900 border border-teal-300">
                                    Spatial Boundaries
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Purok Boundary Editor
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Draw, edit, and save spatial GeoJSON polygon boundaries for automated Purok detection.
                            </p>
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
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'purok_boundaries'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <div class="flex-1 min-w-0 space-y-6">
                            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                                
                                <!-- Left: Purok Selector List -->
                                <div class="xl:col-span-4 bg-white rounded-2xl border-2 border-slate-250 p-6 shadow-xs flex flex-col justify-between space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
                                            <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                                Select Purok Zone
                                            </h3>
                                            <span class="text-xs font-mono font-extrabold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-250">
                                                <?php echo count($data['puroks']); ?> Zones
                                            </span>
                                        </div>

                                        <div class="space-y-2.5 max-h-[480px] overflow-y-auto pr-1">
                                            <?php if (!empty($data['puroks'])): ?>
                                                <?php foreach ($data['puroks'] as $p): ?>
                                                    <button type="button" 
                                                            onclick="selectPurok(<?php echo $p['purok_id']; ?>, '<?php echo addslashes($p['purok_name']); ?>', <?php echo htmlspecialchars(json_encode($p['polygon_geometry'] ?? null)); ?>)" 
                                                            class="purok-btn w-full text-left px-4 py-3.5 rounded-xl border-2 border-slate-250 hover:bg-slate-50 transition flex items-center justify-between text-sm font-bold"
                                                            data-purok-id="<?php echo $p['purok_id']; ?>">
                                                        <span class="font-extrabold text-slate-900 text-base"><?php echo htmlspecialchars($p['purok_name']); ?></span>
                                                        <?php if (!empty($p['polygon_geometry'])): ?>
                                                            <span class="badge-saved inline-flex items-center gap-1 text-xs font-extrabold px-3 py-1 rounded-full bg-emerald-100 text-emerald-950 border border-emerald-300">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                                <span>Saved</span>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-xs text-slate-500 font-extrabold">No boundary</span>
                                                        <?php endif; ?>
                                                    </button>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-sm text-slate-500 font-semibold text-center py-6">No puroks available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-slate-200 flex items-center justify-between text-xs text-slate-700 font-extrabold">
                                        <span class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-full bg-emerald-600"></span> Active Zone</span>
                                        <span class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-full bg-slate-400"></span> Other Boundaries</span>
                                    </div>
                                </div>

                                <!-- Right: Map Editor Canvas -->
                                <div class="xl:col-span-8 bg-white rounded-2xl border-2 border-slate-250 p-6 shadow-xs flex flex-col justify-between space-y-4">
                                    <div>
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-slate-200 pb-4">
                                            <div>
                                                <h3 id="selectedPurokName" class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                    Select a Purok from the left list to draw or edit
                                                </h3>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button id="saveBoundaryBtn" type="button" onclick="saveBoundary()" disabled 
                                                        class="px-6 py-3 bg-[#0B2E22] text-white text-base font-extrabold rounded-xl hover:bg-[#093024] transition shadow-xs disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                                    Save Boundary
                                                </button>
                                                <button type="button" onclick="clearDrawing()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 text-base font-extrabold rounded-xl transition border border-slate-250">
                                                    Clear
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Map Container -->
                                        <div id="purokMap" class="h-[540px] w-full rounded-2xl border-2 border-slate-250 relative overflow-hidden"></div>
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

<!-- Hidden Form -->
<form id="boundarySaveForm" action="/brgy-waste-app-v3/public/settings/purok_boundaries" method="POST" class="hidden">
    <input type="hidden" name="save_boundary" value="1">
    <input type="hidden" name="purok_id" id="postPurokId" value="">
    <input type="hidden" name="polygon_geojson" id="postGeoJson" value="">
</form>

<script>
let map = null;
let drawnItems = null;
let staticBoundariesGroup = null;
let selectedPurokId = null;
let currentGeoJson = null;

// All puroks data from PHP backend
const purokData = <?php echo json_encode($data['puroks'] ?? []); ?>;
const masterBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
const defaultCenter = [
    <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>, 
    <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>
];
const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;

document.addEventListener('DOMContentLoaded', function() {
    if (typeof L === 'undefined') return;

    // Initialize map
    map = L.map('purokMap').setView(defaultCenter, defaultZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Render Master Barangay Outer Boundary as reference
    if (masterBrgyBoundary) {
        try {
            const brgyGeo = (typeof masterBrgyBoundary === 'string') ? JSON.parse(masterBrgyBoundary) : masterBrgyBoundary;
            L.geoJSON(brgyGeo, {
                style: {
                    color: '#059669',
                    weight: 2.5,
                    fillColor: '#D1FAE5',
                    fillOpacity: 0.05,
                    dashArray: '6, 6'
                }
            }).addTo(map);
        } catch(e) {
            console.error('Error rendering master boundary in purok editor:', e);
        }
    }

    // FeatureGroup to hold all static background purok boundaries
    staticBoundariesGroup = L.featureGroup().addTo(map);

    // FeatureGroup for currently edited polygon
    drawnItems = new L.FeatureGroup().addTo(map);

    // 1. RENDER ALL EXISTING BARANGAY PUROK BOUNDARIES ON MAP LOAD
    renderAllPurokBoundaries();

    // 2. SETUP LEAFLET DRAW CONTROLS
    if (typeof L.Control.Draw !== 'undefined') {
        const drawControl = new L.Control.Draw({
            edit: { featureGroup: drawnItems },
            draw: {
                polygon: {
                    allowIntersection: false,
                    drawError: { color: '#ef4444', message: '<strong>Error:</strong> Polygon edges cannot cross!' },
                    shapeOptions: { color: '#10B981', fillColor: '#10B981', fillOpacity: 0.35, weight: 3 }
                },
                polyline: false,
                rectangle: {
                    shapeOptions: { color: '#10B981', fillColor: '#10B981', fillOpacity: 0.35, weight: 3 }
                },
                circle: false,
                marker: false,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        map.on(L.Draw.Event.CREATED, function(event) {
            drawnItems.clearLayers();
            const layer = event.layer;
            drawnItems.addLayer(layer);
            currentGeoJson = JSON.stringify(layer.toGeoJSON());
            document.getElementById('saveBoundaryBtn').removeAttribute('disabled');
        });

        map.on(L.Draw.Event.EDITED, function() {
            const layers = drawnItems.getLayers();
            if (layers.length > 0) {
                currentGeoJson = JSON.stringify(layers[0].toGeoJSON());
                document.getElementById('saveBoundaryBtn').removeAttribute('disabled');
            }
        });

        map.on(L.Draw.Event.DELETED, function() {
            currentGeoJson = null;
            document.getElementById('saveBoundaryBtn').setAttribute('disabled', 'true');
        });
    }
});

// Render all barangay purok boundaries on map
function renderAllPurokBoundaries(activePurokId = null) {
    staticBoundariesGroup.clearLayers();
    const bounds = [];

    purokData.forEach(p => {
        if (p.polygon_geometry && p.purok_id != activePurokId) {
            try {
                const geoObj = (typeof p.polygon_geometry === 'string') ? JSON.parse(p.polygon_geometry) : p.polygon_geometry;
                const layer = L.geoJSON(geoObj, {
                    style: {
                        color: '#64748B',        // Slate border
                        weight: 2,
                        fillColor: '#94A3B8',    // Light slate fill
                        fillOpacity: 0.15,
                        dashArray: '4, 4'
                    }
                });
                layer.bindTooltip(`<b>${p.purok_name}</b>`, { permanent: false, direction: 'center', className: 'purok-tooltip' });
                staticBoundariesGroup.addLayer(layer);
                bounds.push(layer.getBounds());
            } catch(e) {
                console.error('Error rendering purok geometry:', e);
            }
        }
    });

    // Auto-fit map bounds if boundaries exist and no specific purok is focused
    if (!activePurokId && staticBoundariesGroup.getLayers().length > 0) {
        map.fitBounds(staticBoundariesGroup.getBounds(), { padding: [30, 30] });
    }
}

// Select a Purok to view or edit its spatial boundary
function selectPurok(id, name, geometry) {
    selectedPurokId = id;
    
    // Highlight button state in list
    document.querySelectorAll('.purok-btn').forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.querySelector(`.purok-btn[data-purok-id="${id}"]`);
    if (activeBtn) activeBtn.classList.add('active');

    // Update Header text
    document.getElementById('selectedPurokName').innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
        Editing Boundary: <span class="text-emerald-700 font-extrabold ml-1.5 text-xl">${name}</span>
    `;

    // Re-render other purok boundaries as background
    renderAllPurokBoundaries(id);

    // Clear active editing layer
    drawnItems.clearLayers();
    currentGeoJson = null;

    if (geometry) {
        try {
            const geoObj = (typeof geometry === 'string') ? JSON.parse(geometry) : geometry;
            const geoLayer = L.geoJSON(geoObj, {
                style: {
                    color: '#10B981',       // Emerald border
                    weight: 3.5,
                    fillColor: '#10B981',   // Emerald fill
                    fillOpacity: 0.35
                }
            });
            
            geoLayer.eachLayer(l => drawnItems.addLayer(l));
            map.fitBounds(drawnItems.getBounds(), { padding: [40, 40] });
            currentGeoJson = JSON.stringify(geoObj);
            document.getElementById('saveBoundaryBtn').removeAttribute('disabled');
        } catch(e) {
            console.error('Invalid GeoJSON geometry:', e);
        }
    } else {
        document.getElementById('saveBoundaryBtn').setAttribute('disabled', 'true');
    }
}

function clearDrawing() {
    drawnItems.clearLayers();
    currentGeoJson = null;
    document.getElementById('saveBoundaryBtn').setAttribute('disabled', 'true');
}

function saveBoundary() {
    if (!selectedPurokId || !currentGeoJson) {
        alert('Please select a purok and draw a polygon first.');
        return;
    }

    document.getElementById('postPurokId').value = selectedPurokId;
    document.getElementById('postGeoJson').value = currentGeoJson;
    document.getElementById('boundarySaveForm').submit();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>