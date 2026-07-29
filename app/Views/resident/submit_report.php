<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Retrieve data passed from controller
$categories  = $data['categories'] ?? [];
$quantities  = $data['quantities'] ?? [];
$conditions  = $data['conditions'] ?? [];
$error       = $data['error'] ?? '';
$success     = $data['success'] ?? '';
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
                        <a href="/brgy-waste-app-v3/public/resident/profile" class="inline-flex items-center gap-2 self-start rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#10B981] text-sm font-bold text-white">M</span>
                            Resident <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>
                        </a>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <?php if (!empty($error)): ?>
                    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>

                <form action="/brgy-waste-app-v3/public/resident/submit" method="POST" enctype="multipart/form-data" class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]" onsubmit="return validateForm()">
                    <div class="space-y-6">
                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Report Location</h2>
                                    <p class="text-sm text-slate-500">Pin the waste location or use GPS to detect it automatically.</p>
                                </div>
                                <button type="button" onclick="detectGPS()" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-emerald-200 bg-[#E6F4EA] px-4 py-2.5 text-sm font-semibold text-[#0B3024] transition hover:bg-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                    Allow GPS Access
                                </button>
                            </div>

                            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                <div class="mb-3 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                    <span class="rounded-full bg-white px-3 py-1 font-medium shadow-sm">Or manually pin on the map</span>
                                    <span class="rounded-full bg-[#E6F4EA] px-3 py-1 font-semibold text-[#0B3024]">Drop pin</span>
                                </div>
                                <div class="h-[310px] overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                    <div id="mapContainer" class="h-full w-full"></div>
                                </div>
                            </div>

                            <input type="hidden" id="latitude" name="latitude" required>
                            <input type="hidden" id="longitude" name="longitude" required>
                            <div id="locStatus" class="mt-3 hidden text-sm font-semibold text-[#10B981]">Location updated successfully.</div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Waste Category</h2>
                                    <p class="text-sm text-slate-500">Choose the most relevant waste type.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>
                            <div class="mt-5 grid gap-3 md:grid-cols-2">
                                <?php foreach ($categories as $cat): ?>
                                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                        <input type="radio" name="category_id" value="<?php echo htmlspecialchars($cat['category_id']); ?>" class="mt-1 h-4 w-4 border-slate-300 text-[#10B981] focus:ring-[#10B981]" required>
                                        <span>
                                            <span class="block font-semibold text-slate-800"><?php echo htmlspecialchars($cat['category_name']); ?></span>
                                            <span class="text-sm text-slate-500">Select this category</span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Estimated Quantity</h2>
                                    <p class="text-sm text-slate-500">Select the approximate volume of the waste.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>
                            <div class="mt-5 grid gap-3 md:grid-cols-2">
                                <?php foreach ($quantities as $qty): ?>
                                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                        <input type="radio" name="quantity_id" value="<?php echo htmlspecialchars($qty['quantity_id']); ?>" class="mt-1 h-4 w-4 border-slate-300 text-[#10B981] focus:ring-[#10B981]" required>
                                        <span>
                                            <span class="block font-semibold text-slate-800"><?php echo htmlspecialchars($qty['quantity_name']); ?></span>
                                            <span class="text-sm text-slate-500"><?php echo htmlspecialchars($qty['description']); ?></span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Waste Condition</h2>
                                    <p class="text-sm text-slate-500">Describe how the waste currently looks.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>
                            <div class="mt-5 grid gap-3 md:grid-cols-2">
                                <?php foreach ($conditions as $cond): ?>
                                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                        <input type="radio" name="condition_id" value="<?php echo htmlspecialchars($cond['condition_id']); ?>" class="mt-1 h-4 w-4 border-slate-300 text-[#10B981] focus:ring-[#10B981]" required>
                                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($cond['condition_name']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Photo Attachment</h2>
                                    <p class="text-sm text-slate-500">Upload a clear photo of the waste issue.</p>
                                </div>
                                <span class="rounded-full bg-[#E6F4EA] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#0B3024]">Required</span>
                            </div>

                            <div id="drop-area" onclick="document.getElementById('photoInput').click()" class="mt-5 flex cursor-pointer flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center transition hover:border-[#10B981] hover:bg-[#E6F4EA]">
                                <div id="upload-content" class="flex flex-col items-center gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-sm text-[#0B3024]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Click or drag to upload photo</p>
                                        <p class="mt-1 text-sm text-slate-500">PNG or JPG up to 5MB</p>
                                    </div>
                                    <button type="button" class="rounded-2xl bg-[#0B3024] px-4 py-2 text-sm font-semibold text-white">Browse Files</button>
                                </div>
                                <img id="imagePreview" class="hidden h-56 w-full rounded-2xl object-contain bg-white p-2" alt="Preview">
                                <button type="button" id="removeImageBtn" class="hidden mt-3 rounded-full bg-red-500 p-2 text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            <input id="photoInput" name="photo" type="file" class="hidden" accept="image/jpeg,image/png">
                            <div id="photoError" class="mt-2 hidden text-sm font-semibold text-red-500">Please select a valid image under 5MB.</div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Additional Remarks</h2>
                                    <p class="text-sm text-slate-500">Optional details that help the barangay team.</p>
                                </div>
                                <span id="charCount" class="text-sm font-semibold text-slate-400">0/300</span>
                            </div>
                            <textarea name="remarks" id="remarks" rows="4" class="mt-4 w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#10B981] focus:ring-2 focus:ring-[#10B981]/20" placeholder="e.g. Beside the basketball court, behind the blue gate, or near the drainage canal."></textarea>
                        </section>

                        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)] sm:p-8">
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#0B3024] px-5 py-3.5 text-base font-bold text-white transition hover:bg-[#07281E]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                Submit Report
                            </button>
                            <p class="mt-3 text-center text-sm text-slate-500">The system will check for nearby duplicate reports before saving your submission.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)]">
                            <div class="border-b border-slate-200 bg-[#E6F4EA] px-5 py-4">
                                <h3 class="text-lg font-bold text-slate-900">Live Map Preview</h3>
                                <p class="text-sm text-slate-600">Green routes and your pinned location are shown here.</p>
                            </div>
                            <div class="h-[560px]">
                                <div id="mapPreview" class="h-full w-full"></div>
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-[#E6F4EA] p-6 shadow-[0_18px_50px_-24px_rgba(7,40,30,0.35)]">
                            <h3 class="text-lg font-bold text-slate-900">Duplicate Check</h3>
                            <p class="mt-2 text-sm text-slate-600">The system will compare your report with nearby submissions to reduce duplicates before it is saved.</p>
                        </section>
                    </div>
                </form>
            </main>

            <footer class="border-t border-[#0f4d37] bg-[#07281E] px-4 py-6 text-sm text-[#dcefe2] sm:px-6 lg:px-8">
                <div class="mx-auto flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-2 font-semibold">
                        <span class="text-white">Wastewatch</span>
                        <span class="text-[#8fe0ae]">SMART WASTE SOLUTIONS</span>
                    </div>
                    <div class="flex flex-wrap gap-4 text-[#b9d8c5]">
                        <a href="#" class="transition hover:text-white">Privacy</a>
                        <a href="#" class="transition hover:text-white">Terms</a>
                        <a href="#" class="transition hover:text-white">Contact</a>
                        <a href="/brgy-waste-app-v3/public/" class="transition hover:text-white">← Back to site</a>
                    </div>
                    <p class="text-[#b9d8c5]">© <?php echo date('Y'); ?> Wastewatch - Barangay Dulong Bayan Waste Reporting System</p>
                </div>
            </footer>
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

        var defaultCenter = [15.558, 120.803];
        var map = L.map('mapContainer', { center: defaultCenter, zoom: 15, zoomControl: true });
        var previewMap = L.map('mapPreview', { center: defaultCenter, zoom: 14, zoomControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(previewMap);

        var routeCoords = [[15.5562, 120.8008], [15.5575, 120.8024], [15.5588, 120.8041], [15.5601, 120.8052]];
        L.polyline(routeCoords, { color: '#10B981', weight: 4, dashArray: '6,7', lineCap: 'round' }).addTo(map);
        L.polyline(routeCoords, { color: '#10B981', weight: 4, dashArray: '6,7', lineCap: 'round' }).addTo(previewMap);

        var customIcon = L.divIcon({
            html: '<div style="background-color: #ef4444; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.4);"></div>',
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });

        var marker = L.marker(defaultCenter, { draggable: true, icon: customIcon }).addTo(map);
        var previewMarker = L.marker(defaultCenter, { icon: customIcon }).addTo(previewMap);

        function updateLocation(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            document.getElementById('locStatus').classList.remove('hidden');
        }

        marker.on('dragend', function() {
            var pos = marker.getLatLng();
            previewMarker.setLatLng(pos);
            updateLocation(pos.lat, pos.lng);
        });

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

        updateLocation(defaultCenter[0], defaultCenter[1]);

        window.detectGPS = function() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    var lat = pos.coords.latitude;
                    var lng = pos.coords.longitude;
                    var newLatLng = L.latLng(lat, lng);
                    marker.setLatLng(newLatLng);
                    previewMarker.setLatLng(newLatLng);
                    map.flyTo(newLatLng, 16);
                    previewMap.flyTo(newLatLng, 15);
                    updateLocation(lat, lng);
                }, function() {
                    alert('Unable to detect location. Please pin manually on the map.');
                });
            } else {
                alert('Geolocation is not supported by your browser. Please pin manually.');
            }
        };

        setTimeout(function() { map.invalidateSize(); previewMap.invalidateSize(); }, 200);
        window.addEventListener('resize', function() { map.invalidateSize(); previewMap.invalidateSize(); });
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

    if (photoInput) {
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const validTypes = ['image/jpeg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    photoError.textContent = 'Only JPG and PNG allowed.';
                    photoError.classList.remove('hidden');
                    this.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    photoError.textContent = 'File size exceeds 5MB.';
                    photoError.classList.remove('hidden');
                    this.value = '';
                    return;
                }
                photoError.classList.add('hidden');
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
        const category = document.querySelector('input[name="category_id"]:checked');
        const quantity = document.querySelector('input[name="quantity_id"]:checked');
        const condition = document.querySelector('input[name="condition_id"]:checked');
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        const photo = document.getElementById('photoInput').files.length;

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
        if (photo === 0) {
            alert('Please upload a photo of the waste.');
            return false;
        }
        return true;
    }
</script>

