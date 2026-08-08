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
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                GIS Map Management
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Map Landmarks</h1>
                            <p class="text-sm text-slate-500 mt-1">Add, edit, or remove official barangay landmarks shown on public maps (Barangay Hall, MRF, Eco Center, etc.).</p>
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

                    <!-- Layout: Settings Category Sub-Sidebar + Content Area -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'landmarks'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Main Content Panels (Map + Form & List Grid) -->
                        <div class="flex-1 min-w-0 space-y-6">
                            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                                <!-- Left: Interactive Map -->
                                <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                                            Interactive Map Pin Selector
                                        </h2>
                                        <span id="clickInfo" class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                            📍 Click map to set pin
                                        </span>
                                    </div>
                                    <div id="landmarkMap" class="h-[480px] w-full rounded-xl border border-slate-200"></div>
                                </div>

                                <!-- Right: Form & Existing List -->
                                <div class="space-y-6">
                                    <!-- Add Landmark Form -->
                                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-3">Add New Landmark</h3>
                                        <form action="/brgy-waste-app-v3/public/settings/landmarks" method="POST" class="space-y-3.5">
                                            <input type="hidden" name="add_landmark" value="1">
                                            <input type="hidden" name="latitude" id="landmarkLat" value="">
                                            <input type="hidden" name="longitude" id="landmarkLng" value="">

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Landmark Name</label>
                                                <input type="text" name="landmark_name" id="landmarkName" required placeholder="e.g. Barangay Hall"
                                                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Type</label>
                                                <select name="landmark_type" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                                    <option value="Barangay Hall">🏛️ Barangay Hall</option>
                                                    <option value="MRF">♻️ Material Recovery Facility</option>
                                                    <option value="Collection Point">🗑️ Collection Point</option>
                                                    <option value="Eco Center">🌿 Eco Center</option>
                                                    <option value="Transfer Station">🚛 Transfer Station</option>
                                                    <option value="Other">📍 Other</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                                                <input type="text" name="description" placeholder="Facility notes or details"
                                                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                            </div>

                                            <button type="submit" id="addLandmarkBtn" disabled
                                                    class="w-full mt-2 px-4 py-2.5 bg-[#07281E] text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-emerald-800 transition shadow-md disabled:opacity-40 disabled:cursor-not-allowed">
                                                Save Landmark
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Existing Landmarks List -->
                                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3 border-b border-slate-100 pb-3">Registered Landmarks</h3>
                                        <div class="max-h-56 overflow-y-auto space-y-2 pr-1">
                                            <?php if (!empty($data['landmarks'])): ?>
                                                <?php foreach ($data['landmarks'] as $lm): ?>
                                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200/80 hover:bg-emerald-50/50 transition">
                                                        <div class="min-w-0 pr-2">
                                                            <p class="text-xs font-bold text-slate-800 truncate"><?php echo htmlspecialchars($lm['landmark_name']); ?></p>
                                                            <p class="text-[10px] font-semibold text-emerald-700"><?php echo htmlspecialchars($lm['landmark_type'] ?? ''); ?></p>
                                                        </div>
                                                        <form method="POST" class="shrink-0" onsubmit="return confirm('Delete this landmark?');">
                                                            <input type="hidden" name="delete_landmark" value="1">
                                                            <input type="hidden" name="landmark_id" value="<?php echo $lm['landmark_id']; ?>">
                                                            <button type="submit" class="h-7 w-7 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs transition">✕</button>
                                                        </form>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-xs text-slate-400 text-center py-4">No landmarks pinned yet.</p>
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

<!-- Leaflet Map JS Engine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const centerLat = 15.558;
    const centerLng = 120.803;

    const map = L.map('landmarkMap').setView([centerLat, centerLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Existing landmarks
    const landmarks = <?php echo json_encode($data['landmarks'] ?? []); ?>;
    let markerLayer = L.layerGroup().addTo(map);

    function loadLandmarks() {
        markerLayer.clearLayers();
        landmarks.forEach(lm => {
            const icon = L.divIcon({
                html: `<div style="background:#10B981;width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>`,
                className: '',
                iconSize: [14,14],
                iconAnchor: [7,7]
            });
            L.marker([parseFloat(lm.latitude), parseFloat(lm.longitude)], { icon: icon })
                .addTo(markerLayer)
                .bindPopup(`<strong>${lm.landmark_name}</strong><br><span style="color:#10B981;font-weight:bold;">${lm.landmark_type || ''}</span><br>${lm.description || ''}`);
        });
    }
    loadLandmarks();

    // Click to add landmark
    let selectedLat = null;
    let selectedLng = null;
    let tempMarker = null;

    map.on('click', function(e) {
        selectedLat = e.latlng.lat;
        selectedLng = e.latlng.lng;

        if (tempMarker) {
            map.removeLayer(tempMarker);
        }
        tempMarker = L.marker([selectedLat, selectedLng], {
            icon: L.divIcon({
                html: `<div style="background:#EF4444;width:16px;height:16px;border-radius:50%;border:2px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.4);"></div>`,
                className: '',
                iconSize: [16,16],
                iconAnchor: [8,8]
            })
        }).addTo(map);

        document.getElementById('landmarkLat').value = selectedLat;
        document.getElementById('landmarkLng').value = selectedLng;
        document.getElementById('clickInfo').textContent = `📍 Selected: ${selectedLat.toFixed(5)}, ${selectedLng.toFixed(5)}`;
        document.getElementById('addLandmarkBtn').disabled = false;
    });

    document.getElementById('addLandmarkBtn').disabled = true;
    setTimeout(() => map.invalidateSize(), 300);
});
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>