<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap');
  /* Apply Nunito Sans to everything EXCEPT material-icons */
  *:not(.material-icons) {
    font-family: 'Lato', sans-serif !important;
    }
  /* Ensure Material Icons render correctly */
    .material-icons {
    font-family: 'Material Icons' !important;
    font-weight: normal;
    font-style: normal;
    font-size: 24px;  /* Preferred icon size */
    display: inline-block;
    line-height: 1;
    text-transform: none;
    letter-spacing: normal;
    word-wrap: normal;
    white-space: nowrap;
    direction: ltr;
    vertical-align: middle;
    }

    .main {
        background-color: #d4fff3;
    }

    /* Map container improvements */
    #mapContainer, #mapPreview {
        border-radius: 0.75rem; /* rounded-xl */
        overflow: hidden;
        background: #f0f4f8;
    }
    .leaflet-control-zoom {
        border: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        border-radius: 12px !important;
    }
    .leaflet-control-zoom a {
        background: white !important;
        color: #1e293b !important;
        font-weight: 600 !important;
        border: none !important;
        border-radius: 0 !important;
        width: 34px !important;
        height: 34px !important;
        line-height: 34px !important;
    }
    .leaflet-control-zoom a:first-child {
        border-radius: 12px 12px 0 0 !important;
    }
    .leaflet-control-zoom a:last-child {
        border-radius: 0 0 12px 12px !important;
    }
    .leaflet-control-zoom a:hover {
        background: #f1f5f9 !important;
    }
    .leaflet-bar {
        border: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }
    .custom-marker {
        background: #10B981;
        width: 32px;
        height: 32px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-marker::after {
        content: '';
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        transform: rotate(45deg);
    }
    .leaflet-popup-content-wrapper {
        border-radius: 12px !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1) !important;
    }
    .leaflet-popup-tip {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1) !important;
    }
    .locate-btn {
        background: white !important;
        border: none !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        border-radius: 12px !important;
        width: 40px !important;
        height: 40px !important;
        line-height: 40px !important;
        text-align: center !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        font-size: 20px !important;
        color: #1e293b !important;
    }
    .locate-btn:hover {
        background: #f1f5f9 !important;
        transform: scale(1.05);
    }
    .locate-btn.pulsing {
        animation: pulse-locate 1.5s infinite;
    }
    @keyframes pulse-locate {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 12px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .map-legend {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(4px);
        padding: 8px 12px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        font-size: 11px;
        font-weight: 500;
        border: 1px solid rgba(255,255,255,0.3);
        color: #1e293b;
    }
    .map-legend span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .map-legend .color-dot {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 4px;
        background: rgba(16, 185, 129, 0.2);
        border: 2px dashed #10B981;
        margin-right: 4px;
    }
</style>

<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Retrieve data passed from controller
$categories  = $data['categories'] ?? [];
$quantities  = $data['quantities'] ?? [];
$conditions  = $data['conditions'] ?? [];
$error       = $data['error'] ?? '';
$success     = $data['success'] ?? '';
$resume_data = $data['resume_data'] ?? null;
$resume_description = $resume_data['description'] ?? '';
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800">
    <div class="min-h-screen lg:flex">
        <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <span class="inline-flex items-center rounded-full bg-[#E6F4EA] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.35em] text-[#0B3024]">Resident Portal</span>
                            <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-900">Submit Waste Report</h1>
                            <p class="mt-2 max-w-2xl text-sm text-slate-500">Report waste issues in Barangay Dulong Bayan. Your report goes directly to the barangay team.</p>
                        </div>
                        <a href="/brgy-waste-app-v3/public/resident/profile" class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#10B981] text-sm font-bold text-white">M</span>
                            Resident <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>
                        </a>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <?php if (!empty($error)): ?>
                    <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>

                <form action="/brgy-waste-app-v3/public/resident/submit" method="POST" enctype="multipart/form-data" class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]" onsubmit="return validateForm()">
                    <div class="space-y-6">
                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Report Location</h2>
                                    <p class="text-sm text-slate-500">Pin the waste location or use GPS to detect it automatically.</p>
                                </div>
                                <button type="button" onclick="detectGPS()" class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-[#E6F4EA] px-4 py-2.5 text-sm font-semibold text-[#0B3024] transition hover:bg-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                    Allow GPS Access
                                </button>
                            </div>

                            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                <div class="mb-3 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                    <span class="rounded-full bg-white px-3 py-1 font-medium shadow-sm">Or manually pin on the map</span>
                                    <span class="rounded-full bg-[#E6F4EA] px-3 py-1 font-semibold text-[#0B3024]">Drop pin</span>
                                </div>
                                <div class="h-[310px] overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                    <div id="mapContainer" class="h-full w-full"></div>
                                </div>
                            </div>

                            <input type="hidden" id="latitude" name="latitude" required>
                            <input type="hidden" id="longitude" name="longitude" required>
                            <div id="locStatus" class="mt-3 hidden text-sm font-semibold text-[#10B981]">Location updated successfully.</div>
                        </section>

                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Waste Category</h2>
                                    <p class="text-sm text-slate-500">Choose the most relevant waste type.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>
                            <div class="mt-5 grid gap-3 md:grid-cols-2">
                                <?php foreach ($categories as $cat): ?>
                                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                        <input type="radio" name="category_id" value="<?php echo htmlspecialchars($cat['category_id']); ?>" class="mt-1 h-4 w-4 border-slate-300 text-[#10B981] focus:ring-[#10B981]" required>
                                        <span>
                                            <span class="block font-semibold text-slate-800"><?php echo htmlspecialchars($cat['category_name']); ?></span>
                                            <span class="text-sm text-slate-500">Select this category</span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Estimated Quantity</h2>
                                    <p class="text-sm text-slate-500">Select the approximate volume of the waste.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>
                            <div class="mt-5 grid gap-3 md:grid-cols-2">
                                <?php foreach ($quantities as $qty): ?>
                                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                        <input type="radio" name="quantity_id" value="<?php echo htmlspecialchars($qty['quantity_id']); ?>" class="mt-1 h-4 w-4 border-slate-300 text-[#10B981] focus:ring-[#10B981]" required>
                                        <span>
                                            <span class="block font-semibold text-slate-800"><?php echo htmlspecialchars($qty['quantity_name']); ?></span>
                                            <span class="text-sm text-slate-500"><?php echo htmlspecialchars($qty['description']); ?></span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Waste Condition</h2>
                                    <p class="text-sm text-slate-500">Describe how the waste currently looks.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>
                            <div class="mt-5 grid gap-3 md:grid-cols-2">
                                <?php foreach ($conditions as $cond): ?>
                                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                        <input type="radio" name="condition_id" value="<?php echo htmlspecialchars($cond['condition_id']); ?>" class="mt-1 h-4 w-4 border-slate-300 text-[#10B981] focus:ring-[#10B981]" required>
                                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($cond['condition_name']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Photo Attachment</h2>
                                    <p class="text-sm text-slate-500">Upload a clear photo of the waste issue.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>

                            <div id="drop-area" onclick="document.getElementById('photoInput').click()" class="mt-5 flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                <div id="upload-content" class="flex flex-col items-center gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-sm text-[#0B3024]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Click or drag to upload photo</p>
                                        <p class="mt-1 text-sm text-slate-500">PNG or JPG up to 5MB</p>
                                    </div>
                                    <button type="button" class="rounded-xl bg-[#0B3024] px-4 py-2 text-sm font-semibold text-white">Browse Files</button>
                                </div>
                                <img id="imagePreview" class="hidden h-56 w-full rounded-xl object-contain bg-white p-2" alt="Preview">
                                <button type="button" id="removeImageBtn" class="hidden mt-3 rounded-full bg-red-500 p-2 text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            <input id="photoInput" name="photo" type="file" class="hidden" accept="image/jpeg,image/png">
                            <input type="hidden" name="photo_uploaded" id="photoUploaded" value="0">
                            <div id="photoError" class="mt-2 hidden text-sm font-semibold text-red-500">Please select a valid image under 5MB.</div>
                        </section>

                        <!-- ============================================================ -->
                        <!-- DESCRIPTION SECTION (REQUIRED)                               -->
                        <!-- ============================================================ -->
                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Description</h2>
                                    <p class="text-sm text-slate-500">Describe the waste issue in detail.</p>
                                </div>
                                <span id="descCharCount" class="text-sm font-semibold text-slate-400">0/500</span>
                            </div>
                            <textarea name="description" id="description" rows="4" 
                            class="mt-4 w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#10B981] focus:ring-2 focus:ring-[#10B981]/20"
                            placeholder="Describe the waste issue in detail..." required><?php echo htmlspecialchars($resume_description); ?></textarea>
                            <p class="mt-2 text-xs text-slate-400">Minimum 10 characters. Maximum 500 characters.</p>
                        </section>

                        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0B3024] px-5 py-3.5 text-base font-bold text-white transition hover:bg-[#07281E]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                Submit Report
                            </button>
                            <p class="mt-3 text-center text-sm text-slate-500">The system will check for nearby duplicate reports before saving your submission.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)]">
                            <div class="border-b border-slate-200 bg-[#E6F4EA] px-5 py-4">
                                <h3 class="text-lg font-bold text-slate-900">Live Map Preview</h3>
                                <p class="text-sm text-slate-600">Green routes and your pinned location are shown here.</p>
                            </div>
                            <div class="h-[560px]">
                                <div id="mapPreview" class="h-full w-full"></div>
                            </div>
                        </section>

                        <section class="rounded-xl border border-slate-200 bg-[#E6F4EA] p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)]">
                            <h3 class="text-lg font-bold text-slate-900">Duplicate Check</h3>
                            <p class="mt-2 text-sm text-slate-600">The system will compare your report with nearby submissions to reduce duplicates before it is saved.</p>
                        </section>
                    </div>
                </form>
            </main>

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mapContainer = document.getElementById('mapContainer');
        var mapPreview = document.getElementById('mapPreview');

        if (typeof L === 'undefined') {
            if (mapContainer) mapContainer.innerHTML = '<div class="flex h-full items-center justify-center text-gray-500 font-semibold">Map unavailable - please refresh.</div>';
            if (mapPreview) mapPreview.innerHTML = '<div class="flex h-full items-center justify-center text-gray-500 font-semibold">Map unavailable - please refresh.</div>';
            return;
        }

        // --- Center on Barangay ---
        var defaultCenter = [15.558, 120.803];
        var defaultZoom = 15;

        // --- Create Maps ---
        var map = L.map('mapContainer', { 
            center: defaultCenter, 
            zoom: defaultZoom, 
            zoomControl: false,
            attributionControl: true
        });
        var previewMap = L.map('mapPreview', { 
            center: defaultCenter, 
            zoom: 14, 
            zoomControl: false, 
            dragging: false, 
            scrollWheelZoom: false, 
            doubleClickZoom: false,
            attributionControl: false
        });

        // --- Tile Layers (Clean, modern look) ---
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; CartoDB',
            maxZoom: 19
        }).addTo(map);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; CartoDB',
            maxZoom: 19
        }).addTo(previewMap);

        // --- Barangay Boundary (Improved styling) ---
        var barangayBoundary = [
            [15.56992, 120.80135], [15.56728, 120.80018], [15.56570, 120.79897],
            [15.56528, 120.79751], [15.56375, 120.79516], [15.56032, 120.79464],
            [15.55485, 120.79121], [15.54781, 120.80013], [15.55061, 120.80494],
            [15.55288, 120.80886], [15.54962, 120.81743], [15.55121, 120.82609],
            [15.55413, 120.83358], [15.55740, 120.83261], [15.56506, 120.82838],
            [15.57034, 120.82364], [15.56455, 120.82033], [15.56098, 120.81492],
            [15.56739, 120.80324], [15.56992, 120.80135]
        ];

        var polygonStyle = {
            color: '#10B981',
            weight: 3,
            opacity: 0.8,
            fillColor: '#A7F3D0',
            fillOpacity: 0.2,
            dashArray: '6, 6',
            smoothFactor: 1,
            lineCap: 'round',
            lineJoin: 'round'
        };

        L.polygon(barangayBoundary, polygonStyle).addTo(map);
        L.polygon(barangayBoundary, polygonStyle).addTo(previewMap);

        // --- Add a subtle inner glow using a second polygon ---
        var glowStyle = {
            color: 'transparent',
            weight: 0,
            fillColor: '#10B981',
            fillOpacity: 0.05
        };
        L.polygon(barangayBoundary, glowStyle).addTo(map);
        L.polygon(barangayBoundary, glowStyle).addTo(previewMap);

        // --- Scale Bar (only on main map) ---
        L.control.scale({ position: 'bottomright', imperial: false, metric: true }).addTo(map);

        // --- Custom Marker (modern pin) ---
        var customIcon = L.divIcon({
            html: '<div class="custom-marker"></div>',
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // --- Initial Marker ---
        var marker = L.marker(defaultCenter, { draggable: true, icon: customIcon }).addTo(map);
        var previewMarker = L.marker(defaultCenter, { icon: customIcon }).addTo(previewMap);

        // --- Legend (add to main map) ---
        var legend = L.control({ position: 'bottomleft' });
        legend.onAdd = function() {
            var div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML = '<span><span class="color-dot"></span> Barangay Boundary</span>';
            return div;
        };
        legend.addTo(map);

        // --- Locate Control (custom button) ---
        var locateControl = L.control({ position: 'topleft' });
        locateControl.onAdd = function() {
            var btn = L.DomUtil.create('button', 'locate-btn');
            btn.innerHTML = '📍';
            btn.title = 'Use my current location';
            btn.onclick = function() {
                detectGPS();
            };
            return btn;
        };
        locateControl.addTo(map);

        // Also add to preview map (but disabled)
        var locateControlPreview = L.control({ position: 'topleft' });
        locateControlPreview.onAdd = function() {
            var btn = L.DomUtil.create('button', 'locate-btn');
            btn.innerHTML = '📍';
            btn.title = 'Location locked';
            btn.style.opacity = '0.5';
            btn.style.cursor = 'default';
            return btn;
        };
        locateControlPreview.addTo(previewMap);

        // --- Update location ---
        function updateLocation(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            document.getElementById('locStatus').classList.remove('hidden');
        }

        // --- Marker drag events ---
        marker.on('dragend', function() {
            var pos = marker.getLatLng();
            previewMarker.setLatLng(pos);
            updateLocation(pos.lat, pos.lng);
        });

        // --- Map clicks ---
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            previewMarker.setLatLng(e.latlng);
            updateLocation(e.latlng.lat, e.latlng.lng);
        });

        previewMap.on('click', function(e) {
            marker.setLatLng(e.latlng);
            previewMarker.setLatLng(e.latlng);
            updateLocation(e.latlng.lat, e.latlng.lng);
        });

        // --- Fit map to boundary on load ---
        var bounds = L.latLngBounds(barangayBoundary);
        map.fitBounds(bounds, { padding: [20, 20] });
        previewMap.fitBounds(bounds, { padding: [20, 20] });

        // --- GPS Detection ---
        window.detectGPS = function() {
            if ("geolocation" in navigator) {
                // Add pulsing effect to locate button
                var locateBtn = document.querySelector('.locate-btn');
                if (locateBtn) locateBtn.classList.add('pulsing');

                navigator.geolocation.getCurrentPosition(function(pos) {
                    var lat = pos.coords.latitude;
                    var lng = pos.coords.longitude;
                    var newLatLng = L.latLng(lat, lng);
                    marker.setLatLng(newLatLng);
                    previewMarker.setLatLng(newLatLng);
                    map.flyTo(newLatLng, 17);
                    previewMap.flyTo(newLatLng, 16);
                    updateLocation(lat, lng);
                    if (locateBtn) locateBtn.classList.remove('pulsing');
                }, function() {
                    alert('Unable to detect location. Please pin manually on the map.');
                    if (locateBtn) locateBtn.classList.remove('pulsing');
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                alert('Geolocation is not supported by your browser. Please pin manually.');
            }
        };

        // --- Resize map after load ---
        setTimeout(function() { 
            map.invalidateSize(); 
            previewMap.invalidateSize(); 
        }, 300);
        
        window.addEventListener('resize', function() { 
            map.invalidateSize(); 
            previewMap.invalidateSize(); 
        });
    });

    const photoInput = document.getElementById('photoInput');
    const dropArea = document.getElementById('drop-area');
    const uploadContent = document.getElementById('upload-content');
    const imagePreview = document.getElementById('imagePreview');
    const removeBtn = document.getElementById('removeImageBtn');
    const photoError = document.getElementById('photoError');
    const remarks = document.getElementById('remarks');
    const charCount = document.getElementById('charCount');

    if (remarks && charCount) {
        remarks.addEventListener('input', function() {
            charCount.textContent = this.value.length + '/300';
        });
    }

    // Description character counter
    const description = document.getElementById('description');
    const descCharCount = document.getElementById('descCharCount');

    if (description && descCharCount) {
        description.addEventListener('input', function() {
            const count = this.value.length;
            descCharCount.textContent = count + '/500';
            // Change color if approaching limit
            if (count > 450) {
                descCharCount.classList.add('text-red-500');
                descCharCount.classList.remove('text-slate-400');
            } else {
                descCharCount.classList.remove('text-red-500');
                descCharCount.classList.add('text-slate-400');
            }
        });
    }

    if (photoInput) {
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const validTypes = ['image/jpeg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    photoError.textContent = 'Only JPG and PNG allowed.';
                    photoError.classList.remove('hidden');
                    this.value = '';
                    document.getElementById('photoUploaded').value = '0';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    photoError.textContent = 'File size exceeds 5MB.';
                    photoError.classList.remove('hidden');
                    this.value = '';
                    document.getElementById('photoUploaded').value = '0';
                    return;
                }
                photoError.classList.add('hidden');
                document.getElementById('photoUploaded').value = '1'; // Mark as uploaded
                const reader = new FileReader();
                reader.onload = function(ev) {
                    imagePreview.src = ev.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadContent.style.display = 'none';
                    removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            photoInput.value = '';
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            uploadContent.style.display = 'flex';
            removeBtn.classList.add('hidden');
            photoError.classList.add('hidden');
            document.getElementById('photoUploaded').value = '0'; // Reset flag
        });
    }

    if (dropArea) {
        dropArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-[#10B981]', 'bg-[#E6F4EA]');
        });
        dropArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-[#10B981]', 'bg-[#E6F4EA]');
        });
        dropArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-[#10B981]', 'bg-[#E6F4EA]');
            if (e.dataTransfer.files.length) {
                photoInput.files = e.dataTransfer.files;
                photoInput.dispatchEvent(new Event('change'));
            }
        });
    }

    function validateForm() {

    const description = document.getElementById('description');
    if (!description || description.value.trim().length < 10) {
        alert('Please provide a description with at least 10 characters.');
        description?.focus();
        return false;
    }
    if (description.value.trim().length > 500) {
        alert('Description cannot exceed 500 characters.');
        description?.focus();
        return false;
    }


    const category = document.querySelector('input[name="category_id"]:checked');
    const quantity = document.querySelector('input[name="quantity_id"]:checked');
    const condition = document.querySelector('input[name="condition_id"]:checked');
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;

    
    // Check if photo is uploaded - use the file input's files property directly
    const photoInput = document.getElementById('photoInput');
    const hasPhoto = photoInput && photoInput.files && photoInput.files.length > 0;
    
    // Also check if there's an image preview (user uploaded then removed? but preview exists)
    const hasPreview = document.getElementById('imagePreview') && 
                        !document.getElementById('imagePreview').classList.contains('hidden');
    
    const photoUploaded = document.getElementById('photoUploaded').value;

    if (!category) {
        alert('Please select a waste category.');
        return false;
    }
    if (!quantity) {
        alert('Please select an estimated quantity.');
        return false;
    }
    if (!condition) {
        alert('Please select a waste condition.');
        return false;
    }
    if (!lat || !lng) {
        alert('Please pin your location on the map.');
        return false;
    }
    if (!hasPhoto && !hasPreview) {
        alert('Please upload a photo of the waste.');
        return false;
    }
    if (!photoUploaded && photoUploaded !== '1') {
        alert('Please upload a photo of the waste.');
        return false;
    }
    return true;
    
}
</script>