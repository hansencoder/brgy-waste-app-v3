<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Report · WasteWatch Guest</title>
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
        #reviewMap { height: 200px; border-radius: 0.75rem; overflow: hidden; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

<div class="max-w-2xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="javascript:history.back()" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900 leading-tight">Review Your Report</h1>
            <p class="text-xs text-slate-500">Please confirm the details below before submitting.</p>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="flex items-center gap-2 mb-8">
        <?php $labels = ['Verify', 'Details', 'Review', 'Done']; ?>
        <?php foreach ($labels as $i => $label): ?>
            <div class="flex items-center <?php echo $i < count($labels)-1 ? 'flex-1' : ''; ?>">
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                        <?php echo $i < 2 ? 'bg-emerald-600 text-white' : ($i === 2 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400'); ?>">
                        <?php if ($i < 2): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php else: echo $i + 1; endif; ?>
                    </div>
                    <span class="text-xs font-medium <?php echo $i === 2 ? 'text-slate-900' : 'text-slate-400'; ?> hidden sm:block"><?php echo $label; ?></span>
                </div>
                <?php if ($i < count($labels)-1): ?>
                <div class="flex-1 h-px <?php echo $i < 2 ? 'bg-emerald-300' : 'bg-slate-200'; ?> mx-2"></div>
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

    <?php $report = $data['report']; ?>

    <!-- Review Cards -->
    <div class="space-y-4">

        <!-- Reporter Info -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Reporter</h2>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    This phone number has reported <?php echo (int)($data['report_count'] ?? 0); ?> time<?php echo ($data['report_count'] ?? 0) == 1 ? '' : 's'; ?>
                </span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <div class="text-xs text-slate-400 mb-0.5">Name</div>
                    <div class="font-semibold text-slate-800"><?php echo !empty($report['guest_name']) ? htmlspecialchars($report['guest_name'], ENT_QUOTES, 'UTF-8') : '—'; ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-0.5">Mobile</div>
                    <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['guest_phone'], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>
        </div>

        <!-- Waste Details -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Waste Details</h2>
            <div class="grid grid-cols-3 gap-3 text-sm mb-4">
                <div>
                    <div class="text-xs text-slate-400 mb-0.5">Category</div>
                    <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($data['category_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-0.5">Volume</div>
                    <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($data['quantity_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-0.5">Condition</div>
                    <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($data['condition_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-xs text-slate-400 mb-0.5">Purok / Area</div>
                <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($data['purok_name'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-0.5">Description</div>
                <p class="text-sm text-slate-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($report['description'], ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
        </div>

        <!-- Location Map -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Waste Location</h2>
            <div id="reviewMap" class="border border-slate-200 mb-3"></div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="bg-slate-50 rounded-lg p-2">
                    <span class="text-slate-400">Lat:</span> <span class="font-mono font-semibold text-slate-700"><?php echo $report['latitude']; ?></span>
                </div>
                <div class="bg-slate-50 rounded-lg p-2">
                    <span class="text-slate-400">Lng:</span> <span class="font-mono font-semibold text-slate-700"><?php echo $report['longitude']; ?></span>
                </div>
            </div>
        </div>

        <!-- Photos -->
        <?php if (!empty($report['photos'])): ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Photos (<?php echo count($report['photos']); ?>)</h2>
            <div class="flex gap-2 flex-wrap">
                <?php foreach ($report['photos'] as $photo): ?>
                <img src="/brgy-waste-app-v3/public/uploads/<?php echo htmlspecialchars($photo); ?>" class="h-24 w-24 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Waste photo">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Location Plausibility Warning -->
        <?php if (($report['location_plausibility'] ?? 'plausible') !== 'plausible'): ?>
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <div class="font-semibold">Location Distance Notice</div>
                <div class="text-amber-700/80 mt-0.5">The pinned waste location is far from your current position. Your report may require additional verification.</div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Submit Actions -->
    <div class="mt-6 flex gap-3">
        <a href="/brgy-waste-app-v3/public/index.php?url=guest/reportForm"
            class="flex-1 h-12 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl shadow-sm hover:bg-slate-50 transition flex items-center justify-center gap-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
            Edit Report
        </a>
        <form action="/brgy-waste-app-v3/public/guest/submitReport" method="POST" class="flex-[2]">
            <button type="submit" class="w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm active:scale-[0.99]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Submit Report
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-slate-400 mt-4">By submitting, you confirm the information above is accurate.</p>
</div>

<script>
    const lat = <?php echo json_encode((float)$report['latitude']); ?>;
    const lng = <?php echo json_encode((float)$report['longitude']); ?>;

    const map = L.map('reviewMap', { zoomControl: false, dragging: false, scrollWheelZoom: false }).setView([lat, lng], 17);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);

    const icon = L.divIcon({
        className: '',
        html: `<div style="background:#10B981;width:28px;height:28px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 12px rgba(16,185,129,0.4);"></div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
    });
    L.marker([lat, lng], { icon }).addTo(map);
</script>

</body>
</html>
