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
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
            <div class="max-w-6xl mx-auto">
                <a href="/brgy-waste-app-v3/public/settings" class="text-sm text-emerald-600 hover:underline mb-4 inline-block">← Back to Settings</a>
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Purok Boundaries</h1>
                <p class="text-sm text-gray-500 mb-6">Draw or update purok polygons on the map. This enables accurate automatic purok detection when residents submit reports.</p>

                <?php if (!empty($data['error'])): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg"><?php echo htmlspecialchars($data['success']); ?></div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Left: Purok Selector -->
                    <div class="lg:col-span-1 bg-white rounded-lg shadow p-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Select Purok</h3>
                        <div class="space-y-2">
                            <?php if (!empty($data['puroks'])): ?>
                                <?php foreach ($data['puroks'] as $p): ?>
                                    <button onclick="selectPurok(<?php echo $p['purok_id']; ?>, '<?php echo addslashes($p['purok_name']); ?>', '<?php echo addslashes($p['polygon_geometry'] ?? ''); ?>')" 
                                            class="purok-btn w-full text-left px-3 py-2 rounded border border-gray-200 hover:bg-emerald-50 hover:border-emerald-300 transition text-sm font-medium <?php echo $p['polygon_geometry'] ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200'; ?>"
                                            data-purok-id="<?php echo $p['purok_id']; ?>">
                                        <?php echo htmlspecialchars($p['purok_name']); ?>
                                        <?php if ($p['polygon_geometry']): ?>
                                            <span class="text-xs text-emerald-600 float-right">✓</span>
                                        <?php endif; ?>
                                    </button>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-sm text-gray-400 text-center py-4">No puroks found. Please add puroks first.</p>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4 text-xs text-gray-400">
                            <span class="inline-block w-3 h-3 bg-emerald-50 border border-emerald-300 rounded mr-1"></span> Has boundary
                            <span class="inline-block w-3 h-3 bg-white border border-gray-200 rounded ml-2 mr-1"></span> No boundary
                        </div>
                    </div>

                    <!-- Right: Map -->
                    <div class="lg:col-span-3 bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 id="selectedPurokName" class="text-lg font-semibold text-gray-800">Select a purok to edit</h3>
                            <div>
                                <button id="saveBoundaryBtn" onclick="saveBoundary()" disabled class="px-4 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    💾 Save Boundary
                                </button>
                                <button onclick="clearDrawing()" class="ml-2 px-4 py-2 bg-red-500 text-white font-bold rounded-md hover:bg-red-600 transition">
                                    🗑️ Clear
                                </button>
                            </div>
                        </div>
                        <div id="purokMap" class="h-[550px] rounded-lg border border-gray-200"></div>
                        <form id="boundaryForm" action="/brgy-waste-app-v3/public/settings/purok_boundaries" method="POST">
                            <input type="hidden" name="save_boundary" value="1">
                            <input type="hidden" name="purok_id" id="boundaryPurokId" value="">
                            <input type="hidden" name="polygon_geojson" id="polygonGeojson" value="">
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet Draw -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

<script>
let map;
let drawnItems;
let currentPurokId = null;
let currentPurokName = '';

document.addEventListener('DOMContentLoaded', function() {
    const centerLat = 15.558;
    const centerLng = 120.803;

    map = L.map('purokMap').setView([centerLat, centerLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OSM'
    }).addTo(map);

    // Draw controls
    drawnItems = L.featureGroup().addTo(map);

    const drawControl = new L.Control.Draw({
        position: 'topright',
        draw: {
            polygon: {
                allowIntersection: false,
                showArea: true,
                shapeOptions: {
                    color: '#10B981',
                    weight: 3,
                    opacity: 0.8,
                    fillColor: '#10B981',
                    fillOpacity: 0.2
                }
            },
            rectangle: false,
            circle: false,
            circlemarker: false,
            marker: false,
            polyline: false,
        },
        edit: {
            featureGroup: drawnItems,
            remove: true,
        }
    });
    map.addControl(drawControl);

    // Event: when drawing is complete
    map.on('draw:created', function(e) {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        document.getElementById('saveBoundaryBtn').disabled = false;
    });

    map.on('draw:edited', function(e) {
        document.getElementById('saveBoundaryBtn').disabled = false;
    });

    map.on('draw:deleted', function() {
        document.getElementById('saveBoundaryBtn').disabled = true;
        document.getElementById('polygonGeojson').value = '';
    });

    // Barangay boundary (for reference)
    const barangayBoundary = {
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
    };

    L.geoJSON(barangayBoundary, {
        style: {
            color: '#6B7280',
            weight: 2,
            fillColor: 'transparent',
            fillOpacity: 0,
            dashArray: '4,4'
        }
    }).addTo(map);

    setTimeout(() => map.invalidateSize(), 300);
});

// Select a purok to edit
function selectPurok(id, name, geometry) {
    currentPurokId = id;
    currentPurokName = name;

    document.getElementById('selectedPurokName').textContent = '✏️ Editing: ' + name;
    document.getElementById('boundaryPurokId').value = id;

    // Clear existing drawing
    drawnItems.clearLayers();

    if (geometry) {
        try {
            let geojson;
            if (typeof geometry === 'string' && geometry.startsWith('POLYGON')) {
                // WKT format – skip for now
                console.warn('WKT format not fully supported in editor yet.');
            } else {
                try {
                    geojson = JSON.parse(geometry);
                } catch(e) {}
            }

            if (geojson && geojson.type === 'Polygon') {
                const layer = L.geoJSON(geojson);
                layer.eachLayer(function(l) {
                    drawnItems.addLayer(l);
                });
                map.fitBounds(drawnItems.getBounds());
                document.getElementById('saveBoundaryBtn').disabled = true;
            }
        } catch(e) {
            console.warn('Could not parse existing boundary:', e);
        }
    }

    // Highlight selected button
    document.querySelectorAll('.purok-btn').forEach(btn => {
        btn.classList.remove('border-emerald-500', 'bg-emerald-100');
        btn.classList.add('border-gray-200');
        if (parseInt(btn.dataset.purokId) === id) {
            btn.classList.add('border-emerald-500', 'bg-emerald-100');
            btn.classList.remove('border-gray-200');
        }
    });

    document.getElementById('saveBoundaryBtn').disabled = true;
}

// Save the drawn boundary
function saveBoundary() {
    if (!currentPurokId) {
        alert('Please select a purok first.');
        return;
    }

    if (drawnItems.getLayers().length === 0) {
        alert('Please draw a polygon on the map first.');
        return;
    }

    // Get GeoJSON from drawn items
    const geojson = drawnItems.toGeoJSON();
    if (!geojson || geojson.features.length === 0) {
        alert('No valid polygon found.');
        return;
    }

    // Extract the polygon coordinates
    const feature = geojson.features[0];
    if (feature.geometry.type !== 'Polygon') {
        alert('Please draw a polygon (not a point or line).');
        return;
    }

    // Store as GeoJSON string
    document.getElementById('polygonGeojson').value = JSON.stringify(feature.geometry);

    // Submit form
    document.getElementById('boundaryForm').submit();
}

// Clear drawing
function clearDrawing() {
    drawnItems.clearLayers();
    document.getElementById('saveBoundaryBtn').disabled = true;
    document.getElementById('polygonGeojson').value = '';
}

// Keyboard shortcut: Enter to save
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'SELECT') {
        saveBoundary();
    }
});
</script>

<style>
.leaflet-draw-toolbar a {
    background-color: white !important;
}
.leaflet-draw-toolbar a:hover {
    background-color: #f0fdf4 !important;
}
.purok-btn {
    transition: all 0.2s ease;
}
.purok-btn:hover {
    transform: translateX(4px);
}
</style>
<?php include __DIR__ . '/../layouts/footer.php'; ?>