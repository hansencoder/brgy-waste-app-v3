<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Waste · WasteWatch Guest</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0B2E22',
                        'primary-dark': '#062018',
                        'emerald-brand': '#10B981',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
        #mapContainer { 
            height: 260px; 
            border-radius: 0.75rem; 
            overflow: hidden; 
            position: relative !important;
            z-index: 1 !important;
            isolation: isolate !important;
        }
        .leaflet-pane {
            z-index: 2 !important;
        }
        .leaflet-top, .leaflet-bottom {
            z-index: 5 !important;
        }
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
        <a href="<?php echo app_url('index.php?url=guest'); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Report a Waste Issue</h1>
            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                <span>Reporting as <strong class="text-emerald-700"><?php echo htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8'); ?></strong><?php if (!empty($data['name'])): ?> (<?php echo htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'); ?>)<?php endif; ?></span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
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
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div>
            <div class="font-semibold text-amber-700">Submission & Anti-Abuse Policy</div>
            <div class="text-amber-700/80 mt-0.5 leading-relaxed">Maximum 3 reports per hour per mobile number. Pinned locations are verified against your GPS position. Submitting fake or duplicate reports is prohibited.</div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="<?php echo app_url('index.php?url=guest/review'); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateReportForm()" class="space-y-5">

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

            <?php 
            $verifiedContact = $data['phone'] ?? ($data['contact'] ?? '');
            $isEmailContact = filter_var($verifiedContact, FILTER_VALIDATE_EMAIL);
            $defaultEmail = $isEmailContact ? $verifiedContact : '';
            ?>
            <!-- Notification Email Address -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700">
                        Email Address for Status Updates <?php if (!$isEmailContact): ?><span class="text-slate-400 font-normal">(Optional)</span><?php endif; ?>
                    </label>
                    <?php if ($isEmailContact): ?>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Verified Email
                    </span>
                    <?php endif; ?>
                </div>
                <div class="relative">
                    <input type="email" id="guest_email" name="guest_email" 
                        value="<?php echo htmlspecialchars($defaultEmail, ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="you@example.com"
                        <?php echo $isEmailContact ? 'readonly' : ''; ?>
                        class="w-full h-11 px-3.5 rounded-xl border border-slate-200 <?php echo $isEmailContact ? 'bg-slate-50 font-medium text-slate-700' : 'bg-white text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10'; ?> text-sm outline-none transition">
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Receive official email alerts when your report is verified, dispatched, or resolved.</p>
            </div>
        </div>

        <!-- Photo Upload -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Evidence Photos <span class="text-red-500">*</span></h2>
                    <p class="text-xs text-slate-400 mt-0.5">Upload clear photos of the waste issue (At least 1 photo required, max 3).</p>
                </div>
                <span id="photoCountBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-300">
                    Required · 0 / 3
                </span>
            </div>

            <!-- Drop & Click Area -->
            <div id="photoDropArea" onclick="document.getElementById('photos').click()" 
                 class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50/70 hover:bg-emerald-50/30 rounded-2xl cursor-pointer transition text-center group">
                <div class="w-11 h-11 rounded-2xl bg-white shadow-xs border border-slate-200 group-hover:border-emerald-300 text-slate-400 group-hover:text-emerald-600 flex items-center justify-center transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                </div>
                <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-emerald-950 mt-2">Click or drag photos here to upload</span>
                <span class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, WEBP · Max 5MB per file</span>
            </div>

            <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple class="hidden" onchange="handlePhotoSelect(this)">

            <!-- Dynamic Photo Preview Grid -->
            <div id="photo-preview-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3 hidden"></div>

            <p id="photo-error" class="text-red-500 text-xs font-medium hidden">Please upload at least 1 evidence photo of the waste issue.</p>
        </div>

        <!-- Location -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800">Waste Location <span class="text-red-500">*</span></h2>
                <button type="button" onclick="getGpsLocation()" id="gpsBtn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold hover:bg-emerald-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48 2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83"/></svg>
                    Use My Location
                </button>
            </div>
            <p class="text-xs text-slate-500">Click on the map or use GPS to mark where the waste is located.</p>

            <div id="mapContainer" class="h-64 w-full rounded-xl border border-slate-200 overflow-hidden relative"></div>

            <!-- Auto-Detected Purok Card with Change Option -->
            <div id="detectedPurokCard" class="hidden p-3.5 rounded-xl bg-white border border-emerald-200 text-slate-800 text-xs shadow-xs space-y-2.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Assigned Sector / Area</span>
                            <span id="detectedPurokName" class="font-extrabold text-slate-900 text-sm">Purok 1</span>
                        </div>
                    </div>
                    <span id="purokBadge" class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Auto-Detected
                    </span>
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                    <span class="text-slate-500 text-[11px]">Incorrect area? Change:</span>
                    <select id="purok_id_select" onchange="manualPurokChange(this)" class="h-8 px-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-xs font-semibold outline-none focus:border-emerald-600 cursor-pointer">
                        <?php foreach ($data['puroks'] as $p): ?>
                        <option value="<?php echo $p['purok_id']; ?>" data-name="<?php echo htmlspecialchars($p['purok_name']); ?>"><?php echo htmlspecialchars($p['purok_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <input type="hidden" id="purok_id" name="purok_id" value="">

            <!-- Warning Alerts -->
            <div id="guestJurisdictionWarning" class="hidden p-3 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <div>
                    <p class="font-bold">Outside Barangay Dulong Bayan</p>
                    <p class="text-[11px] text-amber-800 mt-0.5">The selected incident is outside official barangay limits.</p>
                </div>
            </div>

            <div id="distanceWarning" class="hidden p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <p class="font-bold">Distance Notice</p>
                    <p id="distanceWarningText" class="text-[11px] text-blue-800 mt-0.5">You are submitting a report for a location away from your current device GPS position.</p>
                </div>
            </div>

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

        <!-- Submit -->
        <button type="submit" class="w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm active:scale-[0.99]">
            Review My Report
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
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

    // Render Purok Boundaries on Map
    const rawPuroksData = <?php echo json_encode($data['puroks'] ?? []); ?>;
    const purokBorderColors = ['#059669', '#2563EB', '#7C3AED', '#D97706', '#DB2777', '#0891B2', '#65A30D'];
    
    if (rawPuroksData && rawPuroksData.length) {
        rawPuroksData.forEach((p, idx) => {
            if (!p.polygon_geometry) return;
            try {
                const geo = (typeof p.polygon_geometry === 'string') ? JSON.parse(p.polygon_geometry) : p.polygon_geometry;
                const col = purokBorderColors[idx % purokBorderColors.length];
                L.geoJSON(geo, {
                    style: {
                        color: col,
                        weight: 1.5,
                        fillColor: col,
                        fillOpacity: 0.06,
                        dashArray: '4, 4'
                    }
                }).bindTooltip(p.purok_name, { permanent: false, direction: 'center', className: 'text-[11px] font-bold text-slate-800' }).addTo(map);
            } catch(e) {}
        });
    }

    let marker = null;
    let userGpsMarker = null;
    let userLat = null;
    let userLng = null;

    const wasteIcon = L.divIcon({
        className: '',
        html: `<div style="background:#10B981;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 12px rgba(16,185,129,0.4);"></div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 30],
    });

    const userGpsIcon = L.divIcon({
        className: '',
        html: `<div style="background:#3b82f6;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 0 0 4px rgba(59,130,246,0.35);"></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });

    function calcDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // meters
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function isInsideBoundary(lat, lng) {
        if (!rawBrgyBoundary) return true;
        try {
            const geo = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            const poly = geo.coordinates ? geo.coordinates[0] : null;
            if (!poly || !poly.length) return true;

            let inside = false;
            for (let i = 0, j = poly.length - 1; i < poly.length; j = i++) {
                const xi = poly[i][0], yi = poly[i][1];
                const xj = poly[j][0], yj = poly[j][1];
                const intersect = ((yi > lat) !== (yj > lat)) && (lng < (xj - xi) * (lat - yi) / (yj - yi) + xi);
                if (intersect) inside = !inside;
            }
            return inside;
        } catch(e) {
            return true;
        }
    }

    function pointToSegmentDist(px, py, x1, y1, x2, y2) {
        const dx = x2 - x1;
        const dy = y2 - y1;
        if (dx === 0 && dy === 0) {
            return Math.hypot(px - x1, py - y1);
        }
        const t = Math.max(0, Math.min(1, ((px - x1) * dx + (py - y1) * dy) / (dx * dx + dy * dy)));
        const projX = x1 + t * dx;
        const projY = y1 + t * dy;
        return Math.hypot(px - projX, py - projY);
    }

    function pointToPolyDist(lng, lat, polyCoords) {
        let minDist = Infinity;
        const n = polyCoords.length;
        for (let i = 0, j = n - 1; i < n; j = i++) {
            const x1 = polyCoords[j][0], y1 = polyCoords[j][1];
            const x2 = polyCoords[i][0], y2 = polyCoords[i][1];
            const d = pointToSegmentDist(lng, lat, x1, y1, x2, y2);
            if (d < minDist) minDist = d;
        }
        return minDist;
    }

    function detectPurokClient(lat, lng) {
        if (!rawPuroksData || !rawPuroksData.length) return null;
        
        let closestPurok = null;
        let minDistance = Infinity;

        for (const p of rawPuroksData) {
            if (!p.polygon_geometry) continue;
            try {
                const geo = (typeof p.polygon_geometry === 'string') ? JSON.parse(p.polygon_geometry) : p.polygon_geometry;
                const poly = geo.coordinates ? geo.coordinates[0] : null;
                if (!poly || !poly.length) continue;
                
                // 1. Strict point in polygon check
                let inside = false;
                for (let i = 0, j = poly.length - 1; i < poly.length; j = i++) {
                    const xi = poly[i][0], yi = poly[i][1];
                    const xj = poly[j][0], yj = poly[j][1];
                    const intersect = ((yi > lat) !== (yj > lat)) && (lng < (xj - xi) * (lat - yi) / (yj - yi) + xi);
                    if (intersect) inside = !inside;
                }
                if (inside) {
                    return p;
                }

                // 2. Compute minimum distance to boundary for gap/border points
                const dist = pointToPolyDist(lng, lat, poly);
                if (dist < minDistance) {
                    minDistance = dist;
                    closestPurok = p;
                }
            } catch(e) {}
        }
        return closestPurok;
    }

    function manualPurokChange(sel) {
        const selectedOpt = sel.options[sel.selectedIndex];
        const purokName = selectedOpt.getAttribute('data-name') || selectedOpt.text;
        const purokId = sel.value;

        document.getElementById('purok_id').value = purokId;
        document.getElementById('detectedPurokName').textContent = purokName;

        const badge = document.getElementById('purokBadge');
        if (badge) {
            badge.className = "inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200";
            badge.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg> Custom Selected`;
        }
    }

    function placeMarker(lat, lng, label) {
        const isInside = isInsideBoundary(lat, lng);
        const jWarn = document.getElementById('guestJurisdictionWarning');
        if (jWarn) {
            if (!isInside) jWarn.classList.remove('hidden');
            else jWarn.classList.add('hidden');
        }

        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], { icon: wasteIcon }).addTo(map);
        document.getElementById('latitude').value  = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);
        document.getElementById('location').value  = label || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        document.getElementById('location-error').classList.add('hidden');

        // Auto-detect and display Purok with highest precision
        const detected = detectPurokClient(lat, lng);
        const pCard = document.getElementById('detectedPurokCard');
        const pName = document.getElementById('detectedPurokName');
        const pInput = document.getElementById('purok_id');
        const pSelect = document.getElementById('purok_id_select');
        const badge = document.getElementById('purokBadge');

        if (detected) {
            if (pCard) pCard.classList.remove('hidden');
            if (pName) pName.textContent = detected.purok_name;
            if (pInput) pInput.value = detected.purok_id;
            if (pSelect) pSelect.value = detected.purok_id;
            if (badge) {
                badge.className = "inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200";
                badge.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Auto-Detected`;
            }
        }

        // Distance check vs User GPS
        const dWarn = document.getElementById('distanceWarning');
        const dText = document.getElementById('distanceWarningText');
        if (userLat && userLng && dWarn && dText) {
            const dist = calcDistance(userLat, userLng, lat, lng);
            if (dist > 250) {
                dWarn.classList.remove('hidden');
                dText.textContent = `You are submitting a report for a location ~${Math.round(dist)}m away from your current device GPS position.`;
            } else {
                dWarn.classList.add('hidden');
            }
        }

        return true;
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
                userLat = lat;
                userLng = lng;

                if (userGpsMarker) map.removeLayer(userGpsMarker);
                userGpsMarker = L.marker([lat, lng], { icon: userGpsIcon }).addTo(map).bindPopup('Your Current Device Location');

                map.setView([lat, lng], 17);
                placeMarker(lat, lng, 'GPS Location');
                document.getElementById('reporter_latitude').value  = lat.toFixed(8);
                document.getElementById('reporter_longitude').value = lng.toFixed(8);
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Location Set';
                btn.disabled = false;
            },
            err => {
                btn.textContent = 'Use My Location';
                btn.disabled = false;
                showModalAlert('Could not retrieve your GPS location. Please tap or pin the waste location directly on the interactive map.', 'Location Access', 'warning');
            },
            { timeout: 10000 }
        );
    }

    // Capture reporter GPS automatically on page load
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            userLat = lat;
            userLng = lng;
            document.getElementById('reporter_latitude').value  = lat.toFixed(8);
            document.getElementById('reporter_longitude').value = lng.toFixed(8);

            if (!userGpsMarker) {
                userGpsMarker = L.marker([lat, lng], { icon: userGpsIcon }).addTo(map).bindPopup('Your Current Device Location');
            }
        }, () => {});
    }

    function updateCharCount(el) {
        document.getElementById('desc-count').textContent = el.value.length + '/500';
    }

    // Interactive Photo Upload Handling
    let selectedPhotoFiles = [];

    function handlePhotoSelect(input) {
        const newFiles = Array.from(input.files);
        if (!newFiles.length) return;

        for (let file of newFiles) {
            if (selectedPhotoFiles.length < 3) {
                if (file.size > 5 * 1024 * 1024) {
                    showModalAlert(`File "${file.name}" exceeds the 5MB size limit. Please upload a smaller image file.`, 'File Size Limit', 'warning');
                    continue;
                }
                selectedPhotoFiles.push(file);
            }
        }

        syncPhotoInputAndPreview();
    }

    function removePhoto(index) {
        selectedPhotoFiles.splice(index, 1);
        syncPhotoInputAndPreview();
    }

    function syncPhotoInputAndPreview() {
        const input = document.getElementById('photos');
        const dt = new DataTransfer();
        selectedPhotoFiles.forEach(f => dt.items.add(f));
        input.files = dt.files;

        const countBadge = document.getElementById('photoCountBadge');
        if (countBadge) {
            countBadge.textContent = selectedPhotoFiles.length > 0 ? `${selectedPhotoFiles.length} / 3 Added` : 'Required · 0 / 3';
            countBadge.className = selectedPhotoFiles.length > 0
                ? 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300'
                : 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-300';
        }

        const dropArea = document.getElementById('photoDropArea');
        const previewGrid = document.getElementById('photo-preview-grid');
        const photoErr = document.getElementById('photo-error');

        if (selectedPhotoFiles.length > 0) {
            if (photoErr) photoErr.classList.add('hidden');
            if (dropArea) dropArea.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }

        if (selectedPhotoFiles.length >= 3) {
            if (dropArea) dropArea.classList.add('hidden');
        } else {
            if (dropArea) dropArea.classList.remove('hidden');
        }

        if (selectedPhotoFiles.length === 0) {
            if (previewGrid) {
                previewGrid.innerHTML = '';
                previewGrid.classList.add('hidden');
            }
            return;
        }

        if (previewGrid) {
            previewGrid.innerHTML = '';
            previewGrid.classList.remove('hidden');

            selectedPhotoFiles.forEach((file, idx) => {
                const card = document.createElement('div');
                card.className = 'relative aspect-4/3 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs group';
                
                const img = document.createElement('img');
                img.className = 'w-full h-full object-cover';
                img.alt = file.name;
                
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; };
                reader.readAsDataURL(file);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.title = 'Remove photo';
                removeBtn.className = 'absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-xs font-black shadow-xs transition cursor-pointer active:scale-90';
                removeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
                removeBtn.onclick = (e) => {
                    e.stopPropagation();
                    removePhoto(idx);
                };

                const tag = document.createElement('div');
                tag.className = 'absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-xs text-white text-[10px] font-mono truncate max-w-[80%]';
                tag.textContent = `Photo ${idx + 1}`;

                card.appendChild(img);
                card.appendChild(removeBtn);
                card.appendChild(tag);
                previewGrid.appendChild(card);
            });
        }
    }

    // Drag and Drop listeners
    const dropArea = document.getElementById('photoDropArea');
    if (dropArea) {
        ['dragenter', 'dragover'].forEach(name => {
            dropArea.addEventListener(name, (e) => {
                e.preventDefault();
                dropArea.classList.add('border-emerald-500', 'bg-emerald-50/50');
            });
        });
        ['dragleave', 'drop'].forEach(name => {
            dropArea.addEventListener(name, (e) => {
                e.preventDefault();
                dropArea.classList.remove('border-emerald-500', 'bg-emerald-50/50');
            });
        });
        dropArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            if (dt && dt.files && dt.files.length) {
                handlePhotoSelect({ files: dt.files });
            }
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

        // Photo Upload validation (At least 1 photo required)
        if (selectedPhotoFiles.length === 0 && (!document.getElementById('photos').files || document.getElementById('photos').files.length === 0)) {
            document.getElementById('photo-error')?.classList.remove('hidden');
            document.getElementById('photoDropArea')?.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            valid = false;
        }

        if (!document.getElementById('latitude').value) {
            document.getElementById('location-error').classList.remove('hidden');
            valid = false;
        }

        if (!valid) {
            const firstError = document.querySelector('.border-red-500') || document.getElementById('photo-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return valid;
    }
</script>

<?php include __DIR__ . '/../layouts/popup_system.php'; ?>
</body>
</html>
