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
    
    #mapContainer {
        height: 340px;
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
                    <a href="<?php echo app_url('resident/my_report'); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-xs self-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <span>View My Reports</span>
                    </a>
                </div>

                <!-- Alert Messages -->
                <?php if (!empty($error)): ?>
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-xs sm:text-sm font-bold text-red-700 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm font-bold text-emerald-800 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Main Form Grid -->
                <form id="reportForm" action="<?php echo app_url('resident/submit'); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6" onsubmit="return false;">
                    
                    <!-- Left Column (2 cols): Details & Attachments -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- SECTION 4: Multi-Photo Upload (1 to 3 pictures) -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                                        <span>Evidence Photos</span>
                                        <span id="photoCountHeader" class="text-xs font-bold text-slate-400 font-mono">(0/3)</span>
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Attach clear photos of the waste issue (At least 1 photo required, up to 3 pictures).</p>
                                </div>
                                <span id="photoCountBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-300 transition-all">
                                    Required · 0 / 3
                                </span>
                            </div>

                            <!-- Drop & Click Zone -->
                            <div id="photoDropArea" onclick="triggerPhotoBrowse()" class="flex flex-col items-center justify-center p-6 sm:p-8 rounded-2xl border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50/80 hover:bg-emerald-50/40 transition cursor-pointer text-center group">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-xs text-slate-400 group-hover:text-emerald-600 flex items-center justify-center border border-slate-200 group-hover:border-emerald-300 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-emerald-950 mt-2.5">Click to browse or drag &amp; drop photos here</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Supports JPG, PNG, WEBP · Max 5MB each (1 to 3 pictures)</p>
                            </div>

                            <!-- Hidden Multiple File Input -->
                            <input id="photoInput" name="photos[]" type="file" class="hidden" accept="image/jpeg,image/jpg,image/png,image/webp" multiple onchange="handlePhotoSelect(this)">
                            <input type="hidden" name="photo_uploaded" id="photoUploaded" value="0">

                            <!-- Dynamic Photo Preview Grid (1 to 3 items) -->
                            <div id="photoPreviewGrid" class="hidden grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-1">
                                <!-- Cards injected via JS -->
                            </div>

                            <div id="photoError" class="text-xs font-bold text-red-500 hidden">Please upload at least 1 valid evidence photo.</div>
                        </div>

                        <!-- SECTION 1: Location & Map Picker -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900">Incident Location</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Pin location on map or use GPS auto-detect.</p>
                                </div>
                                <button type="button" onclick="detectGPS()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                    <span>Auto-Detect GPS</span>
                                </button>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-2 sm:p-3 space-y-2">
                                <div id="mapContainer" class="map-box border border-slate-200"></div>
                                <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500 pt-1 px-1">
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600 border border-white inline-block"></span> Selected Incident Pin</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 border border-white inline-block"></span> Existing Reports (Reference)</span>
                                </div>
                            </div>

                            <input type="hidden" id="latitude" name="latitude" required>
                            <input type="hidden" id="longitude" name="longitude" required>

                            <!-- Out-of-jurisdiction warning banner -->
                            <div id="jurisdictionWarning" class="hidden p-3 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-start gap-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                <div>
                                    <p class="font-bold">Outside Barangay Dulong Bayan Boundary</p>
                                    <p class="text-[11px] text-amber-800 mt-0.5">This location is outside the official barangay boundary. Municipal waste services can only be dispatched within barangay boundaries.</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-1">
                                <span id="locStatus" class="font-bold text-emerald-700 hidden flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
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
                                        <input type="radio" name="category_id" value="<?php echo htmlspecialchars($cat['category_id']); ?>" data-name="<?php echo htmlspecialchars($cat['category_name']); ?>" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
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
                                            <input type="radio" name="quantity_id" value="<?php echo htmlspecialchars($qty['quantity_id']); ?>" data-name="<?php echo htmlspecialchars($qty['quantity_name']); ?>" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
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
                                            <input type="radio" name="condition_id" value="<?php echo htmlspecialchars($cond['condition_id']); ?>" data-name="<?php echo htmlspecialchars($cond['condition_name']); ?>" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                                            <span class="font-bold text-slate-800"><?php echo htmlspecialchars($cond['condition_name']); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

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

                        <!-- Submit Review Trigger Button -->
                        <div class="pt-2">
                            <button type="button" onclick="openReviewModal()" id="reviewBtn" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-[#0B2E22] hover:bg-[#083528] text-white font-extrabold text-sm shadow-md transition active:scale-[0.98] cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h20"/><path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6"/><path d="m4 8 8-5 8 5"/></svg>
                                <span>Review &amp; Submit Report</span>
                            </button>
                            <p class="text-center text-[11px] text-slate-400 mt-2 font-medium">You can review all details and photo evidence before final submission.</p>
                        </div>

                    </div>

                    <!-- Right Column (1 col): Process & Information -->
                    <div class="space-y-6">

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
// State for multiple selected photos (up to 3)
let selectedPhotosList = [];

function triggerPhotoBrowse() {
    if (selectedPhotosList.length >= 3) {
        showModalAlert('You have already reached the maximum limit of 3 evidence photos. Remove a photo to add a different one.', 'Maximum Photos Reached', 'info');
        return;
    }
    document.getElementById('photoInput').click();
}

function handlePhotoSelect(input) {
    const files = Array.from(input.files || []);
    if (!files.length) return;

    let addedCount = 0;
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    for (let file of files) {
        if (selectedPhotosList.length >= 3) {
            showToast('Maximum of 3 evidence photos reached.', 'warning');
            break;
        }

        if (!allowedTypes.includes(file.type.toLowerCase())) {
            showModalAlert(`"${file.name}" is not a supported image format. Please upload JPG, PNG, or WEBP images.`, 'Invalid Format', 'warning');
            continue;
        }

        if (file.size > 5 * 1024 * 1024) {
            showModalAlert(`"${file.name}" exceeds the 5MB file size limit. Please upload a smaller image.`, 'File Too Large', 'warning');
            continue;
        }

        // Avoid duplicate files by name & size
        const isDuplicate = selectedPhotosList.some(p => p.name === file.name && p.size === file.size);
        if (!isDuplicate) {
            selectedPhotosList.push(file);
            addedCount++;
        }
    }

    syncPhotoInputAndRender();
}

function removePhotoByIndex(index, event) {
    if (event) {
        event.stopPropagation();
    }
    if (index >= 0 && index < selectedPhotosList.length) {
        selectedPhotosList.splice(index, 1);
        syncPhotoInputAndRender();
        showToast('Photo removed.', 'info');
    }
}

function syncPhotoInputAndRender() {
    const input = document.getElementById('photoInput');
    const photoUploaded = document.getElementById('photoUploaded');
    const badge = document.getElementById('photoCountBadge');
    const headerCount = document.getElementById('photoCountHeader');
    const grid = document.getElementById('photoPreviewGrid');
    const dropArea = document.getElementById('photoDropArea');
    const photoError = document.getElementById('photoError');

    // Sync HTML5 FileList using DataTransfer
    const dt = new DataTransfer();
    selectedPhotosList.forEach(file => dt.items.add(file));
    input.files = dt.files;

    const count = selectedPhotosList.length;
    photoUploaded.value = count > 0 ? count.toString() : '0';
    if (headerCount) headerCount.textContent = `(${count}/3)`;

    if (count > 0) {
        photoError.classList.add('hidden');
        if (count >= 3) {
            badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300';
            badge.textContent = `3 / 3 Photos Attached`;
            dropArea.classList.add('hidden');
        } else {
            badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200';
            badge.textContent = `${count} / 3 Photos Attached`;
            dropArea.classList.remove('hidden');
        }
    } else {
        badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-300';
        badge.textContent = `Required · 0 / 3`;
        dropArea.classList.remove('hidden');
    }

    // Render Preview Cards Grid
    if (count === 0) {
        grid.classList.add('hidden');
        grid.innerHTML = '';
        return;
    }

    grid.classList.remove('hidden');
    grid.innerHTML = '';

    selectedPhotosList.forEach((file, idx) => {
        const card = document.createElement('div');
        card.className = 'relative group rounded-xl border border-slate-200 bg-white p-2.5 shadow-xs flex flex-col items-center gap-2';

        const imgContainer = document.createElement('div');
        imgContainer.className = 'relative w-full h-36 rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200 cursor-pointer';
        imgContainer.title = 'Click to preview full size';

        const img = document.createElement('img');
        img.className = 'w-full h-full object-cover group-hover:scale-105 transition duration-200';
        img.alt = `Evidence Photo ${idx + 1}`;

        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target.result;
            imgContainer.onclick = (ev) => {
                ev.stopPropagation();
                openImageLightbox(e.target.result, `Evidence Photo ${idx + 1} of ${count}`);
            };
        };
        reader.readAsDataURL(file);

        // Zoom Overlay Icon
        const zoomOverlay = document.createElement('div');
        zoomOverlay.className = 'absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white';
        zoomOverlay.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 drop-shadow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>`;

        imgContainer.appendChild(img);
        imgContainer.appendChild(zoomOverlay);

        // Bottom Info Bar
        const infoBar = document.createElement('div');
        infoBar.className = 'w-full flex items-center justify-between text-xs pt-1 px-1';

        const badgeLabel = document.createElement('span');
        badgeLabel.className = idx === 0 
            ? 'px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200' 
            : 'px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600';
        badgeLabel.textContent = idx === 0 ? `Photo 1 (Primary)` : `Photo ${idx + 1}`;

        const sizeLabel = document.createElement('span');
        sizeLabel.className = 'text-[10px] font-mono text-slate-400 font-bold';
        sizeLabel.textContent = (file.size / (1024 * 1024)).toFixed(1) + ' MB';

        infoBar.appendChild(badgeLabel);
        infoBar.appendChild(sizeLabel);

        // Remove Button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'absolute top-1.5 right-1.5 w-7 h-7 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center shadow-md transition cursor-pointer z-10';
        removeBtn.title = 'Remove this photo';
        removeBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
        removeBtn.onclick = (ev) => removePhotoByIndex(idx, ev);

        card.appendChild(removeBtn);
        card.appendChild(imgContainer);
        card.appendChild(infoBar);

        grid.appendChild(card);
    });

    // If 1 or 2 photos uploaded, show an "Add Photo" card
    if (count < 3) {
        const addCard = document.createElement('div');
        addCard.onclick = triggerPhotoBrowse;
        addCard.className = 'rounded-xl border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50/60 hover:bg-emerald-50/40 p-4 flex flex-col items-center justify-center gap-2 cursor-pointer transition min-h-[180px] group';
        addCard.innerHTML = `
            <div class="w-10 h-10 rounded-xl bg-white shadow-xs text-slate-400 group-hover:text-emerald-600 flex items-center justify-center border border-slate-200 group-hover:border-emerald-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <span class="text-xs font-bold text-slate-700 group-hover:text-emerald-900">Add Another Photo</span>
            <span class="text-[10px] text-slate-400">(${3 - count} slot${3 - count === 1 ? '' : 's'} remaining)</span>
        `;
        grid.appendChild(addCard);
    }
}

// Lightbox Controls
function openImageLightbox(src, title = 'Photo Evidence Preview') {
    const modal = document.getElementById('imageLightboxModal');
    const img = document.getElementById('lightboxImage');
    const titleEl = document.getElementById('lightboxTitle');

    if (modal && img) {
        img.src = src;
        if (titleEl) titleEl.textContent = title;
        modal.classList.remove('hidden');
    }
}

function closeImageLightbox() {
    const modal = document.getElementById('imageLightboxModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Map Initialization
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

    // Render Existing Community Reports on Map Picker
    const existingPins = <?php echo json_encode($data['existing_pins'] ?? []); ?>;
    existingPins.forEach(ep => {
        if (!ep.latitude || !ep.longitude) return;
        const pinIcon = L.divIcon({
            html: `<div style="background:#f59e0b;width:12px;height:12px;border-radius:50%;border:2px solid white;box-shadow:0 1px 4px rgba(0,0,0,0.35);"></div>`,
            className: '', iconSize: [12,12], iconAnchor: [6,6]
        });
        const dateFormatted = ep.submission_date ? new Date(ep.submission_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '';
        const popup = `<div style="font-size:11px;font-family:'Miranda Sans',sans-serif;width:165px;">
            <div style="font-weight:800;color:#0f172a;margin-bottom:2px;">${ep.category_name || 'Existing Report'}</div>
            <div style="color:#64748b;font-size:10px;">Status: <strong style="color:#0f172a;">${ep.status_name || 'Active'}</strong></div>
            <div style="color:#94a3b8;font-size:10px;margin-top:2px;">Logged: ${dateFormatted}</div>
        </div>`;
        L.marker([parseFloat(ep.latitude), parseFloat(ep.longitude)], { icon: pinIcon }).addTo(map).bindPopup(popup);
    });

    let marker = null;

    function isInsideBoundary(lat, lng) {
        if (!rawBrgyBoundary) return true;
        try {
            const geo = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            const pt = [lng, lat];
            let coords = null;
            if (geo.type === 'Feature' && geo.geometry) coords = geo.geometry.coordinates;
            else if (geo.type === 'Polygon' || geo.type === 'MultiPolygon') coords = geo.coordinates;
            if (!coords) return true;

            function insidePoly(point, poly) {
                let inside = false;
                for (let i = 0, j = poly.length - 1; i < poly.length; j = i++) {
                    const xi = poly[i][0], yi = poly[i][1];
                    const xj = poly[j][0], yj = poly[j][1];
                    const intersect = ((yi > point[1]) !== (yj > point[1])) && (point[0] < (xj - xi) * (point[1] - yi) / (yj - yi) + xi);
                    if (intersect) inside = !inside;
                }
                return inside;
            }

            if (geo.type === 'MultiPolygon' || (geo.geometry && geo.geometry.type === 'MultiPolygon')) {
                for (let poly of coords) {
                    if (insidePoly(pt, poly[0])) return true;
                }
                return false;
            } else {
                return insidePoly(pt, coords[0]);
            }
        } catch(e) {
            return true;
        }
    }

    function setPin(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            const customIcon = L.divIcon({
                className: '',
                html: '<div class="custom-pin-marker"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });
            marker = L.marker([lat, lng], { icon: customIcon, draggable: true }).addTo(map);
            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                setPin(pos.lat, pos.lng);
            });
        }

        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
        document.getElementById('coordsDisplay').textContent = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        document.getElementById('locStatus').classList.remove('hidden');

        const inBounds = isInsideBoundary(lat, lng);
        const warn = document.getElementById('jurisdictionWarning');
        const submitBtn = document.getElementById('reviewBtn');
        if (!inBounds) {
            warn.classList.remove('hidden');
            if (submitBtn) {
                submitBtn.classList.add('opacity-50', 'pointer-events-none');
            }
        } else {
            warn.classList.add('hidden');
            if (submitBtn) {
                submitBtn.classList.remove('opacity-50', 'pointer-events-none');
            }
        }

        checkDuplicate(lat.toFixed(7), lng.toFixed(7));
    }

    map.on('click', function(e) {
        setPin(e.latlng.lat, e.latlng.lng);
    });

    window.detectGPS = function() {
        if (!navigator.geolocation) {
            showModalAlert('Geolocation is not supported by your device or browser.', 'GPS Unavailable', 'warning');
            return;
        }
        navigator.geolocation.getCurrentPosition(function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            map.setView([lat, lng], 17);
            setPin(lat, lng);
        }, function() {
            showModalAlert('Unable to retrieve your current location. Please ensure location permissions are enabled, or tap your location directly on the map.', 'Location Access', 'warning');
        }, { enableHighAccuracy: true });
    };

    // Duplicate Check AJAX
    function checkDuplicate(lat, lng) {
        const resultBox = document.getElementById('dupCheckResult');
        const content = document.getElementById('dupCheckContent');
        const idle = document.getElementById('dupCheckIdle');

        fetch('<?php echo app_url('resident/check_duplicate'); ?>?lat=' + lat + '&lng=' + lng)
            .then(res => res.json())
            .then(data => {
                idle.classList.add('hidden');
                resultBox.classList.remove('hidden');
                if (data.has_duplicates && data.duplicates.length > 0) {
                    resultBox.className = 'p-3.5 rounded-xl border border-amber-300 bg-amber-50 text-xs space-y-2';
                    content.innerHTML = `
                        <p class="font-bold text-amber-800 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            <span>Similar report found nearby</span>
                        </p>
                        <p class="text-slate-600 text-[11px]">There is already a waste report logged within 50 meters of your pin.</p>
                    `;
                } else {
                    resultBox.className = 'p-3.5 rounded-xl border border-emerald-300 bg-emerald-50 text-xs space-y-1';
                    content.innerHTML = `
                        <p class="font-bold text-emerald-800 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Location Clear</span>
                        </p>
                        <p class="text-emerald-700 text-[11px]">No duplicate reports detected in this immediate area.</p>
                    `;
                }
            })
            .catch(() => {
                idle.classList.remove('hidden');
                resultBox.classList.add('hidden');
            });
    }

    // Drag & Drop Handling for Upload Zone
    const dropArea = document.getElementById('photoDropArea');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('border-emerald-500', 'bg-emerald-50/50'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('border-emerald-500', 'bg-emerald-50/50'), false);
    });

    dropArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            handlePhotoSelect({ files });
        }
    }, false);

    // Character Counter
    const desc = document.getElementById('description');
    const charCounter = document.getElementById('descCharCount');
    function updateDescCounter() {
        const len = desc.value.length;
        charCounter.textContent = `${len}/500`;
        if (len > 500) {
            charCounter.className = 'text-xs font-mono font-bold text-red-600 animate-pulse';
        } else if (len >= 450) {
            charCounter.className = 'text-xs font-mono font-bold text-amber-600';
        } else {
            charCounter.className = 'text-xs font-mono font-bold text-slate-400';
        }
    }
    desc.addEventListener('input', updateDescCounter);
    updateDescCounter();

    setTimeout(() => map.invalidateSize(), 250);
});

// Modal Open & Form Validation
function openReviewModal() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    const desc = document.getElementById('description').value.trim();
    const photoUploaded = document.getElementById('photoUploaded').value;
    const catChecked = document.querySelector('input[name="category_id"]:checked');
    const qtyChecked = document.querySelector('input[name="quantity_id"]:checked');
    const condChecked = document.querySelector('input[name="condition_id"]:checked');

    if (!photoUploaded || photoUploaded === '0' || selectedPhotosList.length === 0) {
        showModalAlert('Please upload at least 1 clear evidence photo (up to 3 photos) of the waste issue.', 'Evidence Photo Required', 'warning');
        return;
    }
    if (!lat || !lng) {
        showModalAlert('Please pin the exact location of the waste issue on the interactive map.', 'Location Required', 'warning');
        return;
    }
    if (!catChecked) {
        showModalAlert('Please select the type / category of waste.', 'Category Required', 'warning');
        return;
    }
    if (!qtyChecked) {
        showModalAlert('Please select an estimated waste volume or quantity.', 'Volume Required', 'warning');
        return;
    }
    if (!condChecked) {
        showModalAlert('Please select the current waste condition.', 'Condition Required', 'warning');
        return;
    }
    if (desc.length < 10) {
        showModalAlert('Please provide a brief description with at least 10 characters detailing the waste issue.', 'Description Required', 'warning');
        document.getElementById('description').focus();
        return;
    }
    if (desc.length > 500) {
        showModalAlert(`Your description exceeds the 500-character maximum (${desc.length}/500). Please shorten it before submitting.`, 'Description Too Long', 'warning');
        document.getElementById('description').focus();
        return;
    }

    // Populate review modal details
    document.getElementById('revCategory').textContent = catChecked.getAttribute('data-name') || 'Waste Category';
    document.getElementById('revQuantity').textContent = qtyChecked.getAttribute('data-name') || 'Volume';
    document.getElementById('revCondition').textContent = condChecked.getAttribute('data-name') || 'Condition';
    document.getElementById('revCoords').textContent = `${parseFloat(lat).toFixed(5)}, ${parseFloat(lng).toFixed(5)}`;
    document.getElementById('revDescription').textContent = desc;

    // Render review photos gallery
    const revPhotosContainer = document.getElementById('revPhotosGallery');
    revPhotosContainer.innerHTML = '';
    
    selectedPhotosList.forEach((file, idx) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 h-24 cursor-pointer group shadow-xs';
        wrapper.title = 'Click to zoom in';

        const img = document.createElement('img');
        img.className = 'w-full h-full object-cover group-hover:scale-105 transition';

        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target.result;
            wrapper.onclick = () => openImageLightbox(e.target.result, `Review Photo ${idx + 1} of ${selectedPhotosList.length}`);
        };
        reader.readAsDataURL(file);

        const badge = document.createElement('div');
        badge.className = 'absolute bottom-1 left-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs';
        badge.textContent = idx === 0 ? 'Primary' : `Photo ${idx + 1}`;

        wrapper.appendChild(img);
        wrapper.appendChild(badge);
        revPhotosContainer.appendChild(wrapper);
    });

    document.getElementById('revPhotoCount').textContent = `${selectedPhotosList.length} photo${selectedPhotosList.length > 1 ? 's' : ''} attached`;

    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
}

function confirmSubmit() {
    const btn = document.getElementById('confirmSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Submitting...
    `;
    document.getElementById('reportForm').submit();
}
</script>

<!-- REVIEW DETAILS MODAL (High Z-Index so Leaflet map never bleeds over) -->
<div id="reviewModal" class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-200">
        <div class="bg-[#0B2E22] px-6 py-4 flex items-center justify-between text-white border-b border-emerald-900">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center border border-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h20"/><path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6"/><path d="m4 8 8-5 8 5"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-white">Review Report Details</h3>
                    <p class="text-[11px] text-emerald-200/70">Verify your submission before sending to Barangay</p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="text-emerald-300 hover:text-white cursor-pointer transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <!-- Multi-Photo Gallery Preview -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Evidence Photo Proof</span>
                    <span id="revPhotoCount" class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">1 photo attached</span>
                </div>
                <div id="revPhotosGallery" class="grid grid-cols-3 gap-2">
                    <!-- Photo items injected via JS -->
                </div>
                <p class="text-[10px] text-slate-400 italic">Click any photo to view full size</p>
            </div>

            <!-- Details Table -->
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Category</span>
                    <span id="revCategory" class="font-bold text-slate-900 mt-0.5 block">General</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Volume</span>
                    <span id="revQuantity" class="font-bold text-slate-900 mt-0.5 block">Small</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Condition</span>
                    <span id="revCondition" class="font-bold text-slate-900 mt-0.5 block">Scattered</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Coordinates</span>
                    <span id="revCoords" class="font-mono font-bold text-emerald-800 mt-0.5 block">0, 0</span>
                </div>
            </div>

            <!-- Description -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Description &amp; Landmarks</span>
                <p id="revDescription" class="text-xs text-slate-700 font-medium leading-relaxed whitespace-pre-line"></p>
            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3">
            <button type="button" onclick="closeReviewModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition cursor-pointer">
                Back to Edit
            </button>
            <button type="button" id="confirmSubmitBtn" onclick="confirmSubmit()" class="px-6 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#083528] text-white font-bold text-xs shadow-md transition flex items-center gap-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Confirm &amp; Submit Report</span>
            </button>
        </div>
    </div>
</div>

<!-- FULL-SCREEN PHOTO LIGHTBOX MODAL (Z-Index 99999) -->
<div id="imageLightboxModal" style="z-index: 99999 !important;" class="fixed inset-0 bg-slate-950/85 backdrop-blur-md hidden flex items-center justify-center p-4 sm:p-8" onclick="closeImageLightbox()">
    <div class="relative max-w-4xl w-full max-h-[90vh] bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-700/60 flex flex-col" onclick="event.stopPropagation()">
        <!-- Lightbox Header -->
        <div class="px-5 py-3.5 bg-slate-900/95 border-b border-slate-800 flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <span id="lightboxTitle" class="text-xs font-bold text-slate-200">Evidence Photo Preview</span>
            </div>
            <button type="button" onclick="closeImageLightbox()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <!-- Lightbox Image Viewport -->
        <div class="p-3 sm:p-6 flex items-center justify-center bg-slate-950/60 overflow-auto max-h-[80vh]">
            <img id="lightboxImage" src="" alt="Full Evidence Preview" class="max-h-[75vh] w-auto max-w-full object-contain rounded-lg shadow-2xl">
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>