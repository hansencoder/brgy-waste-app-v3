<?php
// Safety: Ensure $data is defined
if (!isset($data) || !is_array($data)) {
    $data = [
        'error' => '',
        'success' => '',
        'puroks' => []
    ];
}
?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
        <div class="flex-1 flex flex-col min-w-0">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
            
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto space-y-6">
                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-full border border-teal-200 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                                GIS Spatial Editor
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Purok Boundaries</h1>
                            <p class="text-sm text-slate-500 mt-1">Draw, edit, and persist spatial GeoJSON polygon boundaries for automated Purok detection.</p>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm font-semibold shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Layout: Settings Category Sub-Sidebar + Editor Content -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'purok_boundaries'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Main Content Panels (Purok Selector List + Map Polygon Editor Grid) -->
                        <div class="flex-1 min-w-0 space-y-6">
                            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                                <!-- Left: Purok Selector List -->
                                <div class="xl:col-span-4 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                            Select Purok Zone
                                        </h3>
                                        <div class="space-y-2 max-h-[460px] overflow-y-auto pr-1">
                                            <?php if (!empty($data['puroks'])): ?>
                                                <?php foreach ($data['puroks'] as $p): ?>
                                                    <button type="button" 
                                                            onclick="selectPurok(<?php echo $p['purok_id']; ?>, '<?php echo addslashes($p['purok_name']); ?>', <?php echo htmlspecialchars(json_encode($p['polygon_geometry'] ?? null)); ?>)" 
                                                            class="purok-btn w-full text-left px-3.5 py-3 rounded-xl border border-slate-200 hover:bg-emerald-50/70 hover:border-emerald-300 transition-all flex items-center justify-between text-xs font-medium"
                                                            data-purok-id="<?php echo $p['purok_id']; ?>">
                                                        <span class="font-bold text-slate-800"><?php echo htmlspecialchars($p['purok_name']); ?></span>
                                                        <?php if (!empty($p['polygon_geometry'])): ?>
                                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">
                                                                ✓ Boundary
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-[10px] text-slate-400 font-semibold">No boundary</span>
                                                        <?php endif; ?>
                                                    </button>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-xs text-slate-400 text-center py-6">No puroks available.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Saved Polygon</span>
                                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span> Empty</span>
                                    </div>
                                </div>

                                <!-- Right: Map Spatial Editor Container -->
                                <div class="xl:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                                    <div>
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-slate-100 pb-3">
                                            <div>
                                                <h3 id="selectedPurokName" class="text-base font-bold text-slate-900 flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    Select a purok from left to edit
                                                </h3>
                                                <p class="text-xs text-slate-500 mt-0.5">Use drawing tools on top right of the map to draw spatial polygons.</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button id="saveBoundaryBtn" type="button" onclick="saveBoundary()" disabled 
                                                        class="px-4 py-2 bg-[#07281E] text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-emerald-800 transition shadow-sm disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                                    Save Boundary
                                                </button>
                                                <button type="button" onclick="clearDrawing()" class="px-3 py-2 bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-200 transition">
                                                    Clear
                                                </button>
                                            </div>
                                        </div>

                                        <div id="purokMap" class="h-[480px] w-full rounded-xl border border-slate-200 relative overflow-hidden"></div>
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

<!-- Form submission hidden container -->
<form id="boundarySaveForm" action="/brgy-waste-app-v3/public/settings/purok_boundaries" method="POST" class="hidden">
    <input type="hidden" name="save_boundary" value="1">
    <input type="hidden" name="purok_id" id="postPurokId" value="">
    <input type="hidden" name="polygon_geojson" id="postGeoJson" value="">
</form>

<!-- Leaflet CSS & Leaflet Draw Plugins -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
let map, drawnItems, drawControl;
let currentPurokId = null;
let currentPurokName = '';
let currentPolygonLayer = null;

document.addEventListener('DOMContentLoaded', function() {
    const centerLat = 15.558;
    const centerLng = 120.803;

    map = L.map('purokMap').setView([centerLat, centerLng], 15);

    const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics',
        maxZoom: 19
    });
    const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: '',
        maxZoom: 19
    });
    const streetMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; CartoDB &copy; OpenStreetMap', maxZoom: 19
    });

    satelliteMap.addTo(map);
    labelsMap.addTo(map);

    L.control.layers({
        "Satellite (Homes & Buildings)": L.layerGroup([satelliteMap, labelsMap]),
        "Street Map": streetMap
    }, null, { position: 'topright' }).addTo(map);

    // Outer Barangay boundary polygon (Reference guide for purok boundary drawing)
    const barangayBoundary = [
        [15.5699279,120.8013517],[15.569572,120.8008898],[15.5686578,120.8008276],
        [15.5685788,120.8006126],[15.5678398,120.8005542],[15.5672858,120.8001844],
        [15.5668847,120.8000725],[15.566531,120.8001665],[15.5663685,120.7995785],
        [15.5657033,120.7989717],[15.5658025,120.7987031],[15.5654243,120.7984537],
        [15.5652,120.7980956],[15.5652043,120.7977553],[15.5652862,120.7975135],
        [15.5652259,120.7971285],[15.5648604,120.7964691],[15.5643821,120.7961709],
        [15.5643993,120.795562],[15.5637567,120.7951681],[15.5632478,120.7953561],
        [15.562581,120.7952523],[15.5617529,120.7950598],[15.5611835,120.7950416],
        [15.5608471,120.7945939],[15.5603295,120.7946431],[15.5596467,120.7943504],
        [15.5597848,120.7937415],[15.55916,120.7930393],[15.5570187,120.7928646],
        [15.555107,120.7921781],[15.554853,120.7912123],[15.5543176,120.7913399],
        [15.5533236,120.7915605],[15.5534046,120.7918092],[15.5478115,120.8001316],
        [15.5481325,120.8011058],[15.5484701,120.8021398],[15.5485113,120.8027807],
        [15.5489723,120.8032508],[15.5500426,120.8030798],[15.5501365,120.8038043],
        [15.5502517,120.8044282],[15.550614,120.8049495],[15.5508445,120.8058211],
        [15.551569,120.8062911],[15.5520964,120.8071584],[15.5520903,120.8076635],
        [15.5524005,120.8081181],[15.5523519,120.8083454],[15.5525708,120.8085979],
        [15.5528807,120.8088668],[15.5512389,120.8118007],[15.550257,120.8126332],
        [15.5523838,120.8153176],[15.549628,120.817434],[15.5518119,120.8219183],
        [15.5522367,120.8232918],[15.5516159,120.8253946],[15.5512188,120.8260956],
        [15.5526533,120.8281375],[15.5518644,120.8298546],[15.5519514,120.8310955],
        [15.5541358,120.8335885],[15.5557229,120.8325752],[15.5574083,120.8326161],
        [15.5602447,120.8332704],[15.5650646,120.8283841],[15.5703491,120.8236492],
        [15.5689622,120.82189],[15.5676998,120.8219651],[15.5645562,120.8203353],
        [15.5594636,120.8205697],[15.5617437,120.8185042],[15.5609879,120.8149287],
        [15.5623097,120.8126889],[15.5595308,120.8092582],[15.5673914,120.8032464],
        [15.5699463,120.8014669],[15.5699279,120.8013517]
    ];
    L.polygon(barangayBoundary, {
        color: '#10b981', weight: 2, fillColor: '#d1fae5', fillOpacity: 0.1, dashArray: '6 5'
    }).addTo(map);

    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    drawControl = new L.Control.Draw({
        draw: {
            polyline: false,
            rectangle: false,
            circle: false,
            marker: false,
            circlemarker: false,
            polygon: {
                allowIntersection: false,
                drawError: { color: '#ef4444', message: 'Polygon edges cannot intersect!' },
                shapeOptions: { color: '#10B981', fillColor: '#10B981', fillOpacity: 0.2, weight: 2 }
            }
        },
        edit: { featureGroup: drawnItems, remove: true }
    });
    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, function (event) {
        if (!currentPurokId) {
            alert('Please select a Purok from the list first before drawing!');
            return;
        }
        drawnItems.clearLayers();
        const layer = event.layer;
        drawnItems.addLayer(layer);
        currentPolygonLayer = layer;
        document.getElementById('saveBoundaryBtn').disabled = false;
    });

    map.on(L.Draw.Event.EDITED, function (event) {
        document.getElementById('saveBoundaryBtn').disabled = false;
    });

    map.on(L.Draw.Event.DELETED, function (event) {
        currentPolygonLayer = null;
        document.getElementById('saveBoundaryBtn').disabled = true;
    });

    setTimeout(() => map.invalidateSize(), 300);
});

function selectPurok(id, name, geojsonStr) {
    currentPurokId = id;
    currentPurokName = name;

    document.querySelectorAll('.purok-btn').forEach(btn => {
        btn.classList.remove('bg-emerald-50', 'border-emerald-500', 'ring-2', 'ring-emerald-500/20');
        if (parseInt(btn.getAttribute('data-purok-id')) === id) {
            btn.classList.add('bg-emerald-50', 'border-emerald-500', 'ring-2', 'ring-emerald-500/20');
        }
    });

    document.getElementById('selectedPurokName').innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Editing: <span class="text-emerald-700">${name}</span>
    `;

    drawnItems.clearLayers();
    document.getElementById('saveBoundaryBtn').disabled = true;

    if (geojsonStr) {
        try {
            const geojsonObj = typeof geojsonStr === 'string' ? JSON.parse(geojsonStr) : geojsonStr;
            const geoLayer = L.geoJSON(geojsonObj, {
                style: { color: '#10B981', fillColor: '#10B981', fillOpacity: 0.25, weight: 2.5 }
            });
            geoLayer.eachLayer(function(layer) {
                drawnItems.addLayer(layer);
                currentPolygonLayer = layer;
            });
            map.fitBounds(geoLayer.getBounds(), { padding: [20, 20] });
            document.getElementById('saveBoundaryBtn').disabled = false;
        } catch (e) {
            console.error('Error parsing GeoJSON:', e);
        }
    }
}

function clearDrawing() {
    drawnItems.clearLayers();
    currentPolygonLayer = null;
    document.getElementById('saveBoundaryBtn').disabled = true;
}

function saveBoundary() {
    if (!currentPurokId) {
        alert('Please select a purok first.');
        return;
    }
    if (drawnItems.getLayers().length === 0) {
        alert('Please draw a polygon boundary on the map first.');
        return;
    }

    const layers = drawnItems.getLayers();
    const lastLayer = layers[layers.length - 1];
    const geojson = lastLayer.toGeoJSON();

    document.getElementById('postPurokId').value = currentPurokId;
    document.getElementById('postGeoJson').value = JSON.stringify(geojson.geometry);
    document.getElementById('boundarySaveForm').submit();
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>