<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-rose-100 text-rose-900 border border-rose-300">
                                    Map Landmarks
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Barangay Map Landmarks
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                View barangay purok polygon boundaries and drop landmark pins (Barangay Hall, MRF, Eco Center).
                            </p>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-5 bg-red-50 border-2 border-red-200 text-red-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Content Layout -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'landmarks'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <div class="flex-1 min-w-0 space-y-6">
                            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                                
                                <!-- Left: Interactive Map -->
                                <div class="xl:col-span-2 bg-white rounded-2xl border-2 border-slate-250 p-5 sm:p-6 shadow-xs flex flex-col space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <h2 class="text-sm sm:text-base font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                                            Barangay Map &amp; Landmark Pins
                                        </h2>
                                        
                                        <div class="flex items-center gap-2">
                                            <!-- Basemap Switcher Segmented Control -->
                                            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-extrabold">
                                                <button type="button" id="btnStreetBasemap" onclick="switchLandmarkBasemap('street')" 
                                                        class="px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs border border-slate-200/80 inline-flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                                                    <span>Clean Street</span>
                                                </button>
                                                <button type="button" id="btnSatBasemap" onclick="switchLandmarkBasemap('satellite')" 
                                                        class="px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900 inline-flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                                                    <span>Satellite View</span>
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Map Canvas -->
                                    <div id="landmarkMap" class="h-[520px] w-full rounded-2xl border-2 border-slate-200 overflow-hidden relative shadow-inner"></div>

                                    <!-- Map Legend -->
                                    <div class="flex items-center flex-wrap gap-4 text-xs sm:text-sm font-bold text-slate-700 pt-1">
                                        <span class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-xs border-2 border-emerald-600 bg-emerald-500/30"></span> Purok Boundary</span>
                                        <span class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-full bg-slate-900"></span> Landmark Pin</span>
                                        <span class="flex items-center gap-1.5 text-slate-500"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg> Click any spot on the map to set landmark location.</span>
                                    </div>
                                </div>

                                <!-- Right: Add Form & Existing List -->
                                <div class="space-y-6">
                                    
                                    <!-- Form -->
                                    <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 shadow-xs space-y-4">
                                        <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-3">Add New Landmark</h3>
                                        <form action="/brgy-waste-app-v3/public/settings/landmarks" method="POST" class="space-y-4">
                                            <input type="hidden" name="add_landmark" value="1">
                                            <input type="hidden" name="latitude" id="landmarkLat" value="">
                                            <input type="hidden" name="longitude" id="landmarkLng" value="">

                                            <div>
                                                <label class="block text-sm font-extrabold text-slate-900 mb-2">Landmark Name <span class="text-red-600">*</span></label>
                                                <input type="text" name="landmark_name" id="landmarkName" required placeholder="e.g. Barangay Hall"
                                                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-250 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-[#10B981]">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-extrabold text-slate-900 mb-2">Category Type</label>
                                                <select name="landmark_type" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-250 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-[#10B981]">
                                                    <option value="Barangay Hall">Barangay Hall</option>
                                                    <option value="MRF">Material Recovery Facility</option>
                                                    <option value="Collection Point">Collection Point</option>
                                                    <option value="Eco Center">Eco Center</option>
                                                    <option value="Transfer Station">Transfer Station</option>
                                                    <option value="Other">Other Facility</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-extrabold text-slate-900 mb-2">Description (Optional)</label>
                                                <input type="text" name="description" placeholder="Facility notes or details"
                                                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-250 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-[#10B981]">
                                            </div>

                                            <button type="submit" id="addLandmarkBtn" disabled
                                                    class="w-full mt-2 px-6 py-3.5 bg-[#0B2E22] hover:bg-[#093024] text-white text-base font-extrabold rounded-xl transition shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                                                Save Landmark Pin
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Registered Landmarks List -->
                                    <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 shadow-xs space-y-4">
                                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                            <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider">Registered Landmarks</h3>
                                            <span class="text-sm font-mono font-extrabold text-slate-900 bg-slate-100 px-3 py-1 rounded-full border border-slate-250">
                                                <?php echo count($data['landmarks'] ?? []); ?>
                                            </span>
                                        </div>

                                        <div class="max-h-60 overflow-y-auto space-y-2.5 pr-1">
                                            <?php if (!empty($data['landmarks'])): ?>
                                                <?php foreach ($data['landmarks'] as $lm): ?>
                                                    <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border-2 border-slate-250 hover:bg-slate-100 transition">
                                                        <div class="min-w-0 pr-2">
                                                            <p class="text-sm font-extrabold text-slate-900 truncate"><?php echo htmlspecialchars($lm['landmark_name']); ?></p>
                                                            <p class="text-xs font-bold text-emerald-800 mt-0.5"><?php echo htmlspecialchars($lm['landmark_type'] ?? 'Landmark'); ?></p>
                                                        </div>
                                                        <form method="POST" class="shrink-0" onsubmit="return confirm('Delete this landmark?');">
                                                            <input type="hidden" name="delete_landmark" value="1">
                                                            <input type="hidden" name="landmark_id" value="<?php echo $lm['landmark_id']; ?>">
                                                            <button type="submit" class="h-8 w-8 flex items-center justify-center bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition border border-red-250 cursor-pointer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-sm text-slate-500 font-semibold text-center py-4">No landmarks pinned yet.</p>
                                            <?php endif; ?>
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

<!-- Leaflet Map & Polygon Render Script -->
<script>
let landmarkMapInstance = null;
let currentLandmarkBasemap = 'street';
let streetLayer = null;
let satelliteLayerGroup = null;

function switchLandmarkBasemap(type) {
    if (!landmarkMapInstance || !streetLayer || !satelliteLayerGroup) return;
    currentLandmarkBasemap = type;

    const btnStreet = document.getElementById('btnStreetBasemap');
    const btnSat = document.getElementById('btnSatBasemap');

    if (type === 'satellite') {
        landmarkMapInstance.removeLayer(streetLayer);
        landmarkMapInstance.addLayer(satelliteLayerGroup);
        if (btnSat) {
            btnSat.className = "px-3 py-1.5 rounded-lg transition bg-[#083528] text-white shadow-xs border border-emerald-600";
        }
        if (btnStreet) {
            btnStreet.className = "px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900";
        }
    } else {
        landmarkMapInstance.removeLayer(satelliteLayerGroup);
        landmarkMapInstance.addLayer(streetLayer);
        if (btnStreet) {
            btnStreet.className = "px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs border border-slate-200/80";
        }
        if (btnSat) {
            btnSat.className = "px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900";
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof L === 'undefined') return;

    const defaultCenter = [
        <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>, 
        <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>
    ];
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;

    const map = L.map('landmarkMap', {
        zoomControl: true,
        attributionControl: false
    }).setView(defaultCenter, defaultZoom);
    landmarkMapInstance = map;

    // Basemaps
    streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    const satTiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    });
    const satLabels = L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    });
    satelliteLayerGroup = L.layerGroup([satTiles, satLabels]);

    // Master Barangay Boundary
    const rawBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
    if (rawBrgyBoundary) {
        try {
            const brgyGeo = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
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
            console.error('Error rendering master boundary in landmarks:', e);
        }
    }

    let tempMarker = null;
    const boundsGroup = L.featureGroup().addTo(map);

    const latInput = document.getElementById('landmarkLat');
    const lngInput = document.getElementById('landmarkLng');
    const btn = document.getElementById('addLandmarkBtn');
    const clickInfo = document.getElementById('clickInfo');

    // 1. RENDER BARANGAY PUROK POLYGON BOUNDARIES
    const puroks = <?php echo json_encode($data['puroks'] ?? []); ?>;
    puroks.forEach(p => {
        if (p.polygon_geometry) {
            try {
                const geoObj = (typeof p.polygon_geometry === 'string') ? JSON.parse(p.polygon_geometry) : p.polygon_geometry;
                const polygonLayer = L.geoJSON(geoObj, {
                    style: {
                        color: '#059669',       // Emerald 600 border line
                        weight: 3,
                        fillColor: '#10B981',   // Emerald 500 fill
                        fillOpacity: 0.20,
                        dashArray: '5, 5'
                    }
                });
                polygonLayer.bindTooltip(`<b>${p.purok_name}</b>`, { permanent: false, direction: 'center', className: 'text-sm font-bold' });
                polygonLayer.addTo(boundsGroup);
            } catch(e) {
                console.error('Error parsing purok geometry:', e);
            }
        }
    });

    if (boundsGroup.getLayers().length > 0) {
        map.fitBounds(boundsGroup.getBounds(), { padding: [20, 20] });
    }

    // 2. RENDER EXISTING LANDMARK PINS
    const landmarks = <?php echo json_encode($data['landmarks'] ?? []); ?>;
    landmarks.forEach(lm => {
        if (lm.latitude && lm.longitude) {
            const marker = L.marker([lm.latitude, lm.longitude]).addTo(map);
            marker.bindPopup(`
                <div style="font-family:'Miranda Sans',sans-serif; padding:6px;">
                    <div style="font-size:15px; font-weight:800; color:#0B2E22;">${lm.landmark_name}</div>
                    <div style="font-size:12px; font-weight:700; color:#059669; margin-top:2px;">${lm.landmark_type || 'Landmark'}</div>
                    ${lm.description ? `<div style="font-size:12px; color:#334155; margin-top:4px;">${lm.description}</div>` : ''}
                </div>
            `);
        }
    });

    // 3. MAP CLICK PIN SELECTOR
    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        if (tempMarker) {
            map.removeLayer(tempMarker);
        }

        tempMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
        latInput.value = lat;
        lngInput.value = lng;
        btn.removeAttribute('disabled');

        if (clickInfo) {
            clickInfo.textContent = `Pin: ${lat}, ${lng}`;
            clickInfo.className = "text-xs sm:text-sm font-extrabold text-emerald-950 bg-emerald-100 px-3.5 py-1.5 rounded-full border border-emerald-300 font-mono";
        }

        tempMarker.on('dragend', function(evt) {
            const pos = evt.target.getLatLng();
            const dragLat = pos.lat.toFixed(6);
            const dragLng = pos.lng.toFixed(6);
            latInput.value = dragLat;
            lngInput.value = dragLng;
            if (clickInfo) {
                clickInfo.textContent = `Pin: ${dragLat}, ${dragLng}`;
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>