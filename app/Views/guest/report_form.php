<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Waste · WasteWatch Guest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
        #mapContainer { height: 260px; border-radius: 0.75rem; overflow: hidden; }
        .leaflet-control-zoom { border: none !important; box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important; border-radius: 12px !important; }
        .leaflet-control-zoom a { background: white !important; color: #1e293b !important; font-weight: 600 !important; border: none !important; width: 34px !important; height: 34px !important; line-height: 34px !important; }
        .leaflet-control-zoom a:first-child { border-radius: 12px 12px 0 0 !important; }
        .leaflet-control-zoom a:last-child { border-radius: 0 0 12px 12px !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

<div class="max-w-2xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Report a Waste Issue</h1>
            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                <span>Reporting as <strong class="text-emerald-700"><?php echo htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8'); ?></strong><?php if (!empty($data['name'])): ?> (<?php echo htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'); ?>)<?php endif; ?></span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Reported <?php echo (int)($data['report_count'] ?? 0); ?> time<?php echo ($data['report_count'] ?? 0) == 1 ? '' : 's'; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="flex items-center gap-2 mb-8">
        <?php $labels = ['Verify', 'Details', 'Review', 'Done']; ?>
        <?php foreach ($labels as $i => $label): ?>
            <div class="flex items-center <?php echo $i < count($labels)-1 ? 'flex-1' : ''; ?>">
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                        <?php echo $i < 1 ? 'bg-emerald-600 text-white' : ($i === 1 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400'); ?>">
                        <?php if ($i < 1): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php else: echo $i + 1; endif; ?>
                    </div>
                    <span class="text-xs font-medium <?php echo $i === 1 ? 'text-slate-900' : 'text-slate-400'; ?> hidden sm:block"><?php echo $label; ?></span>
                </div>
                <?php if ($i < count($labels)-1): ?>
                <div class="flex-1 h-px <?php echo $i < 1 ? 'bg-emerald-300' : 'bg-slate-200'; ?> mx-2"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($data['error'])): ?>
    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <?php endif; ?>

    <!-- Anti-Abuse Warning Notice -->
    <div class="mb-5 p-3.5 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-600 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div>
            <div class="font-semibold text-amber-700">Submission & Anti-Abuse Policy</div>
            <div class="text-amber-700/80 mt-0.5 leading-relaxed">Maximum 3 reports per hour per mobile number. Pinned locations are verified against your GPS position. Submitting fake or duplicate reports is prohibited.</div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="/brgy-waste-app-v3/public/guest/review" method="POST" enctype="multipart/form-data" onsubmit="return validateReportForm()" class="space-y-5">

        <!-- Description -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-slate-800">Waste Details</h2>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Describe the issue <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" required rows="3" maxlength="500" placeholder="Describe the type and condition of waste, and any safety hazards..."
                    class="w-full px-3.5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 resize-none"
                    oninput="updateCharCount(this)"></textarea>
                <div class="flex justify-between mt-1">
                    <p id="desc-error" class="text-red-500 text-xs font-medium hidden">Please describe the waste issue.</p>
                    <span id="desc-count" class="text-xs text-slate-400 ml-auto">0/500</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" required class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10">
                        <option value="">Select</option>
                        <?php foreach ($data['categories'] as $cat): ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Volume <span class="text-red-500">*</span></label>
                    <select id="quantity_id" name="quantity_id" required class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10">
                        <option value="">Select</option>
                        <?php foreach ($data['quantities'] as $q): ?>
                        <option value="<?php echo $q['quantity_id']; ?>"><?php echo htmlspecialchars($q['quantity_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Condition -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Condition <span class="text-red-500">*</span></label>
                    <select id="condition_id" name="condition_id" required class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10">
                        <option value="">Select</option>
                        <?php foreach ($data['conditions'] as $c): ?>
                        <option value="<?php echo $c['condition_id']; ?>"><?php echo htmlspecialchars($c['condition_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Purok -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Purok / Area</label>
                <select name="purok_id" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10">
                    <option value="">Not sure / Unknown</option>
                    <?php foreach ($data['puroks'] as $p): ?>
                    <option value="<?php echo $p['purok_id']; ?>"><?php echo htmlspecialchars($p['purok_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Location -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800">Waste Location <span class="text-red-500">*</span></h2>
                <button type="button" onclick="getGpsLocation()" id="gpsBtn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold hover:bg-emerald-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48 2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83"/></svg>
                    Use My Location
                </button>
            </div>
            <p class="text-xs text-slate-500">Click on the map or use GPS to mark where the waste is located.</p>

            <div id="mapContainer" class="h-64 w-full rounded-xl border border-slate-200 overflow-hidden relative"></div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Latitude</label>
                    <input type="text" id="latitude" name="latitude" readonly placeholder="Click map to set"
                        class="w-full h-9 px-3 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 text-xs font-mono outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Longitude</label>
                    <input type="text" id="longitude" name="longitude" readonly placeholder="Click map to set"
                        class="w-full h-9 px-3 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 text-xs font-mono outline-none">
                </div>
            </div>
            <p id="location-error" class="text-red-500 text-xs font-medium hidden">Please pin the waste location on the map.</p>
            <input type="hidden" id="location" name="location" value="">

            <!-- Reporter GPS (hidden, captured silently) -->
            <input type="hidden" id="reporter_latitude" name="reporter_latitude" value="">
            <input type="hidden" id="reporter_longitude" name="reporter_longitude" value="">
        </div>

        <!-- Photo Upload -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-3">
            <h2 class="text-sm font-bold text-slate-800">Photos <span class="text-slate-400 font-normal text-xs">(optional, max 3)</span></h2>
            <label for="photos" class="flex flex-col items-center justify-center gap-2 h-28 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <span class="text-xs text-slate-500">Click to upload photos</span>
                <span class="text-xs text-slate-400">JPG, PNG, WEBP · Max 5MB each</span>
            </label>
            <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple class="hidden" onchange="previewPhotos(this)">
            <div id="photo-preview" class="flex gap-2 flex-wrap"></div>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm active:scale-[0.99]">
            Review My Report
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
    </form>
</div>

<script>
    // Initialize map centered on dynamic barangay center with Satellite Imagery
    const defaultCenter = [
        <?php echo (float)($data['map_center']['lat'] ?? 15.5600); ?>, 
        <?php echo (float)($data['map_center']['lng'] ?? 120.8048); ?>
    ];
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 16); ?>;
    const map = L.map('mapContainer').setView(defaultCenter, defaultZoom);
    
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics',
        maxZoom: 19
    });
    const labelsLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: '',
        maxZoom: 19
    });
    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors', maxZoom: 19
    });

    satelliteLayer.addTo(map);
    labelsLayer.addTo(map);

    L.control.layers({
        "Satellite (Homes & Buildings)": L.layerGroup([satelliteLayer, labelsLayer]),
        "Street Map": streetLayer
    }, null, { position: 'topright' }).addTo(map);

    // Render Official Barangay Boundary
    const rawBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
    if (rawBrgyBoundary) {
        try {
            const brgyGeoObj = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            L.geoJSON(brgyGeoObj, {
                style: {
                    color: '#10b981',
                    weight: 2,
                    fillColor: '#d1fae5',
                    fillOpacity: 0.08,
                    dashArray: '5, 5'
                }
            }).addTo(map);
        } catch(e) {
            console.error('Error rendering dynamic barangay boundary:', e);
        }
    }

    let marker = null;

    const wasteIcon = L.divIcon({
        className: '',
        html: `<div style="background:#10B981;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 12px rgba(16,185,129,0.4);"></div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 30],
    });

    function placeMarker(lat, lng, label) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], { icon: wasteIcon }).addTo(map);
        document.getElementById('latitude').value  = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        document.getElementById('location').value  = label || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        document.getElementById('location-error').classList.add('hidden');
    }

    map.on('click', function(e) {
        placeMarker(e.latlng.lat, e.latlng.lng);
    });

    function getGpsLocation() {
        if (!navigator.geolocation) return;
        const btn = document.getElementById('gpsBtn');
        btn.textContent = 'Locating…';
        btn.disabled = true;
        navigator.geolocation.getCurrentPosition(
            pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                map.setView([lat, lng], 17);
                placeMarker(lat, lng, 'GPS Location');
                // Store reporter location for plausibility check
                document.getElementById('reporter_latitude').value  = lat.toFixed(8);
                document.getElementById('reporter_longitude').value = lng.toFixed(8);
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Location Set';
                btn.disabled = false;
            },
            err => {
                btn.textContent = 'Use My Location';
                btn.disabled = false;
                alert('Could not get your location. Please pin the waste location on the map.');
            },
            { timeout: 10000 }
        );
    }

    // Also silently capture reporter GPS on page load (for plausibility check)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            document.getElementById('reporter_latitude').value  = pos.coords.latitude.toFixed(8);
            document.getElementById('reporter_longitude').value = pos.coords.longitude.toFixed(8);
        }, () => {});
    }

    function updateCharCount(el) {
        document.getElementById('desc-count').textContent = el.value.length + '/500';
    }

    function previewPhotos(input) {
        const preview = document.getElementById('photo-preview');
        preview.innerHTML = '';
        const files = Array.from(input.files).slice(0, 3);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'h-20 w-20 object-cover rounded-xl border border-slate-200';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    function validateReportForm() {
        let valid = true;

        const desc = document.getElementById('description');
        if (!desc.value.trim()) {
            desc.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            document.getElementById('desc-error').classList.remove('hidden');
            valid = false;
        }

        ['category_id','quantity_id','condition_id'].forEach(id => {
            const el = document.getElementById(id);
            if (!el.value) {
                el.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
                valid = false;
            }
        });

        if (!document.getElementById('latitude').value) {
            document.getElementById('location-error').classList.remove('hidden');
            valid = false;
        }

        return valid;
    }
</script>

</body>
</html>
