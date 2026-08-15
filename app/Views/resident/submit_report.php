<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php
// Retrieve data passed from controller
$categories         = $data['categories'] ?? [];
$quantities         = $data['quantities'] ?? [];
$conditions         = $data['conditions'] ?? [];
$error              = $data['error'] ?? '';
$success            = $data['success'] ?? '';
$resume_data        = $data['resume_data'] ?? null;
$resume_description = $resume_data['description'] ?? '';
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    
    .map-box {
        height: 340px;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .custom-pin-marker {
        background: #10B981;
        width: 30px;
        height: 30px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-pin-marker::after {
        content: '';
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        transform: rotate(45deg);
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <!-- Resident Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <!-- Resident Topbar -->
        <?php include __DIR__ . '/../layouts/resident_topbar.php'; ?>

        <!-- Scrollable Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                <!-- Header Title Banner -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">
                            <span>Resident Portal</span>
                            <span>•</span>
                            <span>New Incident</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Submit Waste Report</h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Report uncollected waste, illegal dumps, or hazardous materials for prompt barangay response.</p>
                    </div>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-xs self-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <span>View My Reports</span>
                    </a>
                </div>

                <!-- Alert Messages -->
                <?php if (!empty($error)): ?>
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-xs sm:text-sm font-bold text-red-700 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm font-bold text-emerald-800 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Main Form Grid -->
                <form id="reportForm" action="/brgy-waste-app-v3/public/resident/submit" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6" onsubmit="return validateForm()">
                    
                    <!-- Left Column (2 cols): Details & Attachments -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- SECTION 1: Location & Map Picker -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900">Incident Location</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Pin location on map or use GPS auto-detect.</p>
                                </div>
                                <button type="button" onclick="detectGPS()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                    <span>Auto-Detect GPS</span>
                                </button>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-2 sm:p-3">
                                <div id="mapContainer" class="map-box border border-slate-200"></div>
                            </div>

                            <input type="hidden" id="latitude" name="latitude" required>
                            <input type="hidden" id="longitude" name="longitude" required>

                            <div class="flex items-center justify-between text-xs pt-1">
                                <span id="locStatus" class="font-bold text-emerald-700 hidden flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>Location pinned</span>
                                </span>
                                <span class="text-slate-400 font-mono text-[11px]" id="coordsDisplay">Coordinates: Not set</span>
                            </div>
                        </div>

                        <!-- SECTION 2: Waste Category -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900">Waste Category</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Select the primary waste classification.</p>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Required</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <?php foreach ($categories as $cat): ?>
                                    <label class="relative flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 cursor-pointer transition has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/80">
                                        <input type="radio" name="category_id" value="<?php echo htmlspecialchars($cat['category_id']); ?>" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                                        <div class="min-w-0">
                                            <span class="block text-xs font-bold text-slate-800"><?php echo htmlspecialchars($cat['category_name']); ?></span>
                                            <span class="text-[11px] text-slate-500 block truncate">Official Waste Type</span>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- SECTION 3: Volume & Condition -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            
                            <!-- Quantity -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3">
                                <div class="pb-2 border-b border-slate-100">
                                    <h3 class="text-sm font-extrabold text-slate-900">Estimated Volume</h3>
                                    <p class="text-[11px] text-slate-400">Approximate size of waste</p>
                                </div>
                                <div class="space-y-2">
                                    <?php foreach ($quantities as $qty): ?>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 cursor-pointer transition text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/80">
                                            <input type="radio" name="quantity_id" value="<?php echo htmlspecialchars($qty['quantity_id']); ?>" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                                            <div>
                                                <span class="font-bold text-slate-800 block"><?php echo htmlspecialchars($qty['quantity_name']); ?></span>
                                                <span class="text-[10px] text-slate-500"><?php echo htmlspecialchars($qty['description']); ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Condition -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3">
                                <div class="pb-2 border-b border-slate-100">
                                    <h3 class="text-sm font-extrabold text-slate-900">Waste Condition</h3>
                                    <p class="text-[11px] text-slate-400">Current state of the pile</p>
                                </div>
                                <div class="space-y-2">
                                    <?php foreach ($conditions as $cond): ?>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 cursor-pointer transition text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/80">
                                            <input type="radio" name="condition_id" value="<?php echo htmlspecialchars($cond['condition_id']); ?>" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                                            <span class="font-bold text-slate-800"><?php echo htmlspecialchars($cond['condition_name']); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>

                        <!-- SECTION 4: Photo Upload -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900">Evidence Photo</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Upload a clear photo showing the waste issue.</p>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Required</span>
                            </div>

                            <div id="drop-area" onclick="document.getElementById('photoInput').click()" class="flex flex-col items-center justify-center p-8 rounded-2xl border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50 hover:bg-emerald-50/30 transition cursor-pointer text-center">
                                <div id="upload-content" class="flex flex-col items-center gap-2">
                                    <div class="w-12 h-12 rounded-2xl bg-white shadow-xs text-emerald-600 flex items-center justify-center border border-slate-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 mt-1">Click or drag image here</p>
                                    <p class="text-[11px] text-slate-400">Supports JPG, PNG (Max 5MB)</p>
                                </div>
                                <img id="imagePreview" class="hidden max-h-60 w-auto rounded-xl object-contain bg-white p-2 border border-slate-200" alt="Preview">
                                <button type="button" id="removeImageBtn" class="hidden mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-xs transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    <span>Remove Photo</span>
                                </button>
                            </div>

                            <input id="photoInput" name="photo" type="file" class="hidden" accept="image/jpeg,image/png">
                            <input type="hidden" name="photo_uploaded" id="photoUploaded" value="0">
                            <div id="photoError" class="text-xs font-bold text-red-500 hidden">Please upload a valid image file.</div>
                        </div>

                        <!-- SECTION 5: Description -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900">Incident Description</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Provide landmarks or specific instructions for collectors.</p>
                                </div>
                                <span id="descCharCount" class="text-xs font-mono font-bold text-slate-400">0/500</span>
                            </div>

                            <textarea name="description" id="description" rows="4" maxlength="500"
                                      class="w-full p-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition resize-none leading-relaxed"
                                      placeholder="e.g. Near Purok 3 basketball court, behind the chapel. Pile includes garden waste and broken furniture." required><?php echo htmlspecialchars($resume_description); ?></textarea>
                            <p class="text-[11px] text-slate-400">Minimum 10 characters required.</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" id="submitBtn" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-[#0B2E22] hover:bg-[#083528] text-white font-extrabold text-sm shadow-md transition active:scale-[0.98] cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                <span>Submit Waste Report</span>
                            </button>
                            <p class="text-center text-[11px] text-slate-400 mt-2 font-medium">Your report will be automatically checked for nearby duplicates before logging.</p>
                        </div>

                    </div>

                    <!-- Right Column (1 col): Duplicate Scanner & Information -->
                    <div class="space-y-6">

                        <!-- Duplicate Check Box -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3">
                            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-sm border border-emerald-100">
                                    🔍
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">Duplicate Scanner</h3>
                                    <p class="text-[11px] font-semibold text-slate-400">Scans 50m radius around pin</p>
                                </div>
                            </div>

                            <div id="dupCheckResult" class="hidden p-3.5 rounded-xl border bg-slate-50 text-xs">
                                <div id="dupCheckContent"></div>
                            </div>

                            <div id="dupCheckIdle" class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl text-slate-400 text-xs space-y-1">
                                <p class="font-bold">📍 Waiting for Location Pin</p>
                                <p class="text-[11px] text-slate-400">Pin a location on the map to automatically scan for nearby existing reports.</p>
                            </div>
                        </div>

                        <!-- Response Process Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5">
                            <h3 class="text-sm font-extrabold text-slate-900 pb-2 border-b border-slate-100">What Happens Next?</h3>
                            
                            <div class="space-y-3 text-xs">
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">1</span>
                                    <div>
                                        <p class="font-bold text-slate-800">Verification</p>
                                        <p class="text-[11px] text-slate-500">Barangay officials review the report details.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">2</span>
                                    <div>
                                        <p class="font-bold text-slate-800">Truck Dispatch</p>
                                        <p class="text-[11px] text-slate-500">A collection team is assigned to the coordinates.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">3</span>
                                    <div>
                                        <p class="font-bold text-slate-800">Resolution Photo</p>
                                        <p class="text-[11px] text-slate-500">Collector uploads cleaned site proof.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultCenter = [
        <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>, 
        <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>
    ];
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;
    const map = L.map('mapContainer', { zoomControl: true }).setView(defaultCenter, defaultZoom);

    const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri', maxZoom: 19
    });
    const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    });
    const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19
    });

    satelliteMap.addTo(map);
    labelsMap.addTo(map);

    L.control.layers({
        "Satellite": L.layerGroup([satelliteMap, labelsMap]),
        "Street Map": streetMap
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

    function setPin(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            const icon = L.divIcon({
                html: '<div class="custom-pin-marker"></div>',
                className: '',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });
            marker = L.marker([lat, lng], { icon: icon, draggable: true }).addTo(map);
            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                updateCoords(pos.lat, pos.lng);
            });
        }
        updateCoords(lat, lng);
    }

    function updateCoords(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        document.getElementById('locStatus').classList.remove('hidden');
        document.getElementById('coordsDisplay').textContent = `Coordinates: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        checkDuplicate(lat, lng);
    }

    map.on('click', function(e) {
        setPin(e.latlng.lat, e.latlng.lng);
    });

    window.detectGPS = function() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        navigator.geolocation.getCurrentPosition(function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            map.setView([lat, lng], 17);
            setPin(lat, lng);
        }, function() {
            alert('Unable to retrieve your location. Please check location permissions.');
        }, { enableHighAccuracy: true });
    };

    // Duplicate Check AJAX
    function checkDuplicate(lat, lng) {
        const resultBox = document.getElementById('dupCheckResult');
        const content = document.getElementById('dupCheckContent');
        const idle = document.getElementById('dupCheckIdle');

        fetch(`/brgy-waste-app-v3/public/resident/check_duplicate?lat=${lat}&lng=${lng}`)
            .then(res => res.json())
            .then(data => {
                idle.classList.add('hidden');
                resultBox.classList.remove('hidden');
                if (data.has_duplicates && data.duplicates.length > 0) {
                    resultBox.className = 'p-3.5 rounded-xl border border-amber-300 bg-amber-50 text-xs space-y-2';
                    content.innerHTML = `
                        <p class="font-bold text-amber-800 flex items-center gap-1.5">
                            <span>⚠️ Similar report found nearby</span>
                        </p>
                        <p class="text-slate-600 text-[11px]">There is already a waste report logged within 50 meters of your pin.</p>
                    `;
                } else {
                    resultBox.className = 'p-3.5 rounded-xl border border-emerald-300 bg-emerald-50 text-xs space-y-1';
                    content.innerHTML = `
                        <p class="font-bold text-emerald-800">✓ Location Clear</p>
                        <p class="text-emerald-700 text-[11px]">No duplicate reports detected in this immediate area.</p>
                    `;
                }
            })
            .catch(() => {
                idle.classList.remove('hidden');
                resultBox.classList.add('hidden');
            });
    }

    // Photo Preview & Drop
    const photoInput = document.getElementById('photoInput');
    const imagePreview = document.getElementById('imagePreview');
    const uploadContent = document.getElementById('upload-content');
    const removeBtn = document.getElementById('removeImageBtn');
    const photoError = document.getElementById('photoError');

    photoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (!['image/jpeg', 'image/png'].includes(file.type)) {
                photoError.textContent = 'Only JPG and PNG files are allowed.';
                photoError.classList.remove('hidden');
                this.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                photoError.textContent = 'File size exceeds 5MB limit.';
                photoError.classList.remove('hidden');
                this.value = '';
                return;
            }
            photoError.classList.add('hidden');
            document.getElementById('photoUploaded').value = '1';
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadContent.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        photoInput.value = '';
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
        uploadContent.classList.remove('hidden');
        removeBtn.classList.add('hidden');
        document.getElementById('photoUploaded').value = '0';
    });

    // Character Counter
    const desc = document.getElementById('description');
    const charCounter = document.getElementById('descCharCount');
    desc.addEventListener('input', function() {
        charCounter.textContent = `${this.value.length}/500`;
    });
    charCounter.textContent = `${desc.value.length}/500`;

    setTimeout(() => map.invalidateSize(), 200);
});

function validateForm() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    const desc = document.getElementById('description').value.trim();
    const photoUploaded = document.getElementById('photoUploaded').value;

    if (!lat || !lng) {
        alert('Please pin the location of the waste issue on the map.');
        return false;
    }
    if (desc.length < 10) {
        alert('Please provide a description with at least 10 characters.');
        document.getElementById('description').focus();
        return false;
    }
    if (photoUploaded !== '1') {
        alert('Please upload an evidence photo of the waste issue.');
        return false;
    }
    return true;
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>