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

            <main class="mx-auto max-w-7xl px-4 py-8 pb-24 sm:px-6 lg:px-8">
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
                            <div class="flex items-center gap-3 mb-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#0B3024]/10 text-[#0B3024]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Duplicate Check</h3>
                                    <p class="text-xs text-slate-600 mt-0.5">Pin a location to scan for nearby reports within 50 m.</p>
                                </div>
                            </div>

                            <!-- Live duplicate result panel -->
                            <div id="dupCheckResult" class="hidden mt-3 rounded-xl border bg-white p-4">
                                <div id="dupCheckContent"></div>
                            </div>
                            <div id="dupCheckIdle" class="mt-3 rounded-xl border border-dashed border-[#10B981]/40 bg-white/60 p-4 text-center text-sm text-slate-500">
                                <svg class="mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                Waiting for location pin…
                            </div>
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
            [15.5699279, 120.8013517],[15.569572, 120.8008898],[15.5686578, 120.8008276],
            [15.5685788, 120.8006126],[15.5678398, 120.8005542],[15.5672858, 120.8001844],
            [15.5668847, 120.8000725],[15.566531, 120.8001665],[15.5663685, 120.7995785],
            [15.5657033, 120.7989717],[15.5658025, 120.7987031],[15.5654243, 120.7984537],
            [15.5652, 120.7980956],[15.5652043, 120.7977553],[15.5652862, 120.7975135],
            [15.5652259, 120.7971285],[15.5648604, 120.7964691],[15.5643821, 120.7961709],
            [15.5643993, 120.795562],[15.5637567, 120.7951681],[15.5632478, 120.7953561],
            [15.562581, 120.7952523],[15.5617529, 120.7950598],[15.5611835, 120.7950416],
            [15.5608471, 120.7945939],[15.5603295, 120.7946431],[15.5596467, 120.7943504],
            [15.5597848, 120.7937415],[15.55916, 120.7930393],[15.5570187, 120.7928646],
            [15.555107, 120.7921781],[15.554853, 120.7912123],[15.5543176, 120.7913399],
            [15.5533236, 120.7915605],[15.5534046, 120.7918092],[15.5478115, 120.8001316],
            [15.5481325, 120.8011058],[15.5484701, 120.8021398],[15.5485113, 120.8027807],
            [15.5489723, 120.8032508],[15.5500426, 120.8030798],[15.5501365, 120.8038043],
            [15.5502517, 120.8044282],[15.550614, 120.8049495],[15.5508445, 120.8058211],
            [15.551569, 120.8062911],[15.5520964, 120.8071584],[15.5520903, 120.8076635],
            [15.5524005, 120.8081181],[15.5523519, 120.8083454],[15.5525708, 120.8085979],
            [15.5528807, 120.8088668],[15.5512389, 120.8118007],[15.550257, 120.8126332],
            [15.5523838, 120.8153176],[15.549628, 120.817434],[15.5518119, 120.8219183],
            [15.5522367, 120.8232918],[15.5516159, 120.8253946],[15.5512188, 120.8260956],
            [15.5526533, 120.8281375],[15.5518644, 120.8298546],[15.5519514, 120.8310955],
            [15.5541358, 120.8335885],[15.5557229, 120.8325752],[15.5574083, 120.8326161],
            [15.5602447, 120.8332704],[15.5650646, 120.8283841],[15.5703491, 120.8236492],
            [15.5689622, 120.82189],[15.5676998, 120.8219651],[15.5645562, 120.8203353],
            [15.5594636, 120.8205697],[15.5617437, 120.8185042],[15.5609879, 120.8149287],
            [15.5623097, 120.8126889],[15.5595308, 120.8092582],[15.5673914, 120.8032464],
            [15.5699463, 120.8014669],[15.5699279, 120.8013517]
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

        // --- Update location + trigger live duplicate check ---
        var dupCheckTimer = null;
        function updateLocation(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            document.getElementById('locStatus').classList.remove('hidden');
            // Trigger live dup-check (debounced 600 ms)
            clearTimeout(dupCheckTimer);
            dupCheckTimer = setTimeout(function() { runDupCheck(lat, lng); }, 600);
        }

        function runDupCheck(lat, lng) {
            var catInput = document.querySelector('input[name="category_id"]:checked');
            var catId = catInput ? catInput.value : 0;
            var idleEl   = document.getElementById('dupCheckIdle');
            var resultEl = document.getElementById('dupCheckResult');
            var contentEl = document.getElementById('dupCheckContent');

            // Show loading state
            if (idleEl)   idleEl.classList.add('hidden');
            if (resultEl) resultEl.classList.remove('hidden');
            if (contentEl) contentEl.innerHTML = '<p class="text-xs text-slate-400 text-center py-2">Checking for nearby reports\u2026</p>';

            fetch('/brgy-waste-app-v3/public/resident/nearby_check?lat=' + lat + '&lng=' + lng + '&cat=' + catId)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var nearby = data.nearby || [];
                    if (nearby.length === 0) {
                        contentEl.innerHTML = '<div class="flex items-center gap-2 text-emerald-700">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>' +
                            '<span class="text-xs font-semibold">No duplicate reports found nearby. You\u2019re good to submit!</span></div>';
                        resultEl.className = 'mt-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4';
                    } else {
                        var html = '<div class="flex items-center gap-2 mb-3">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>' +
                            '<p class="text-xs font-bold text-amber-800">' + nearby.length + ' similar report(s) found within 50\u202fm</p></div>';
                        nearby.forEach(function(r) {
                            var dist = Math.round((r.distance_km || 0) * 1000);
                            var date = r.submission_date ? new Date(r.submission_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : '';
                            html += '<div class="flex items-center justify-between py-2 border-t border-amber-100">' +
                                '<div><p class="text-xs font-semibold text-slate-700">' + (r.category_name || 'Report') + '</p>' +
                                '<p class="text-[10px] text-slate-400">' + dist + '\u202fm away &middot; ' + date + ' &middot; ' + (r.support_count||0) + ' supports</p></div>' +
                                '<a href="/brgy-waste-app-v3/public/resident/view_report/' + r.id + '" target="_blank" class="ml-3 shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-bold text-amber-800 hover:bg-amber-200 transition">View</a></div>';
                        });
                        resultEl.className = 'mt-3 rounded-xl border border-amber-200 bg-amber-50 p-4';
                        contentEl.innerHTML = html;
                    }
                })
                .catch(function() {
                    if (resultEl) resultEl.classList.add('hidden');
                    if (idleEl)   idleEl.classList.remove('hidden');
                });
        }

        // Re-run dup check if category changes
        document.querySelectorAll('input[name="category_id"]').forEach(function(el) {
            el.addEventListener('change', function() {
                var lat = parseFloat(document.getElementById('latitude').value);
                var lng = parseFloat(document.getElementById('longitude').value);
                if (lat && lng) runDupCheck(lat, lng);
            });
        });


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