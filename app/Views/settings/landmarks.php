<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
            <div class="max-w-6xl mx-auto">
                <a href="/brgy-waste-app-v3/public/settings" class="text-sm text-emerald-600 hover:underline mb-4 inline-block">← Back to Settings</a>
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Map Landmarks</h1>
                <p class="text-sm text-gray-500 mb-6">Add, edit, or remove official landmarks shown on the public map (Barangay Hall, MRF, Collection Points, etc.).</p>

                <?php if (!empty($data['error'])): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg"><?php echo htmlspecialchars($data['success']); ?></div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: Map -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Click on the map to add a landmark</h2>
                        <div id="landmarkMap" class="h-[500px] rounded-lg border border-gray-200"></div>
                    </div>

                    <!-- Right: Form & List -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Add Form -->
                        <div class="bg-white rounded-lg shadow p-4">
                            <h3 class="text-md font-semibold text-gray-800 mb-3">Add New Landmark</h3>
                            <form action="/brgy-waste-app-v3/public/settings/landmarks" method="POST">
                                <input type="hidden" name="add_landmark" value="1">
                                <input type="hidden" name="latitude" id="landmarkLat" value="">
                                <input type="hidden" name="longitude" id="landmarkLng" value="">
                                
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Landmark Name</label>
                                    <input type="text" name="landmark_name" id="landmarkName" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="e.g., Barangay Hall">
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Type</label>
                                    <select name="landmark_type" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                        <option value="Barangay Hall">🏛️ Barangay Hall</option>
                                        <option value="MRF">♻️ Material Recovery Facility</option>
                                        <option value="Collection Point">🗑️ Collection Point</option>
                                        <option value="Eco Center">🌿 Eco Center</option>
                                        <option value="Transfer Station">🚛 Transfer Station</option>
                                        <option value="Other">📍 Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Description (optional)</label>
                                    <input type="text" name="description" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Additional details">
                                </div>
                                <div id="clickInfo" class="text-xs text-gray-400 mb-3">Click on the map to set location</div>
                                <button type="submit" id="addLandmarkBtn" disabled class="w-full px-4 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    Add Landmark
                                </button>
                            </form>
                        </div>

                        <!-- List -->
                        <div class="bg-white rounded-lg shadow p-4">
                            <h3 class="text-md font-semibold text-gray-800 mb-3">Existing Landmarks</h3>
                            <div class="max-h-64 overflow-y-auto space-y-2">
                                <?php if (!empty($data['landmarks'])): ?>
                                    <?php foreach ($data['landmarks'] as $lm): ?>
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded border border-gray-200 hover:bg-gray-100 transition">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($lm['landmark_name']); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($lm['landmark_type'] ?? ''); ?></p>
                                            </div>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="delete_landmark" value="1">
                                                <input type="hidden" name="landmark_id" value="<?php echo $lm['landmark_id']; ?>">
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Delete this landmark?')">✕</button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-400 text-center py-4">No landmarks added yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Center on barangay (Dulong Bayan)
    const centerLat = 15.558;
    const centerLng = 120.803;

    const map = L.map('landmarkMap').setView([centerLat, centerLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OSM'
    }).addTo(map);

    // Existing landmarks
    const landmarks = <?php echo json_encode($data['landmarks'] ?? []); ?>;
    let markerLayer = L.layerGroup().addTo(map);

    function loadLandmarks() {
        markerLayer.clearLayers();
        landmarks.forEach(lm => {
            const icon = L.divIcon({
                html: `<div style="background:#10B981;width:12px;height:12px;border-radius:50%;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>`,
                className: '',
                iconSize: [12,12],
                iconAnchor: [6,6]
            });
            L.marker([parseFloat(lm.latitude), parseFloat(lm.longitude)], { icon: icon })
                .addTo(markerLayer)
                .bindPopup(`<strong>${lm.landmark_name}</strong><br>${lm.landmark_type || ''}<br>${lm.description || ''}`);
        });
    }
    loadLandmarks();

    // Click to add new landmark
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
                html: `<div style="background:#EF4444;width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.4);"></div>`,
                className: '',
                iconSize: [14,14],
                iconAnchor: [7,7]
            })
        }).addTo(map);

        document.getElementById('landmarkLat').value = selectedLat;
        document.getElementById('landmarkLng').value = selectedLng;
        document.getElementById('clickInfo').textContent = `📍 Selected: ${selectedLat.toFixed(6)}, ${selectedLng.toFixed(6)}`;
        document.getElementById('addLandmarkBtn').disabled = false;
    });

    // Disable button if no location selected
    document.getElementById('addLandmarkBtn').disabled = true;

    // Resize map
    setTimeout(() => map.invalidateSize(), 300);
});
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>