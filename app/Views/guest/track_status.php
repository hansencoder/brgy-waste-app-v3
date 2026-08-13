<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Status · WasteWatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        #statusMap { height: 200px; border-radius: 0.75rem; overflow: hidden; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen">

<div class="max-w-2xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-7">
        <a href="/brgy-waste-app-v3/public/index.php?url=guest/track" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Report Status</h1>
            <p class="text-xs font-mono text-emerald-700 font-semibold"><?php echo htmlspecialchars($data['report']['tracking_number'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>

    <?php
    $report = $data['report'];
    $rawStatus = $report['status'] ?? 'Pending';
    $statusKey = str_replace([' ', '-'], '_', strtolower(trim($rawStatus)));
    $status    = $statusKey;

    $statusConfig = [
        'pending'     => ['label' => 'Pending',     'color' => 'amber',   'icon' => '🕐', 'desc' => 'Your report has been received and is waiting for review.'],
        'verified'    => ['label' => 'Verified',     'color' => 'blue',    'icon' => '✅', 'desc' => 'Your report has been verified by our team and will be actioned.'],
        'in_progress' => ['label' => 'In Progress',  'color' => 'violet',  'icon' => '🚛', 'desc' => 'Our team is actively addressing the waste issue.'],
        'resolved'    => ['label' => 'Resolved',     'color' => 'emerald', 'icon' => '🎉', 'desc' => 'The waste issue has been successfully resolved. Thank you!'],
        'rejected'    => ['label' => 'Rejected',     'color' => 'red',     'icon' => '❌', 'desc' => 'Your report could not be processed. Please contact us for more information.'],
    ];
    $sc = $statusConfig[$statusKey] ?? $statusConfig['pending'];

    $colorMap = [
        'amber'   => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-700',   'badge' => 'bg-amber-100 text-amber-800'],
        'blue'    => ['bg' => 'bg-blue-50',    'border' => 'border-blue-200',    'text' => 'text-blue-700',    'badge' => 'bg-blue-100 text-blue-800'],
        'violet'  => ['bg' => 'bg-violet-50',  'border' => 'border-violet-200',  'text' => 'text-violet-700',  'badge' => 'bg-violet-100 text-violet-800'],
        'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-800'],
        'red'     => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'text' => 'text-red-700',     'badge' => 'bg-red-100 text-red-800'],
    ];
    $c = $colorMap[$sc['color']];

    $allStatuses = ['pending', 'verified', 'in_progress', 'resolved'];
    $currentIdx = array_search($statusKey, $allStatuses);
    if ($currentIdx === false) $currentIdx = -1;
    ?>

    <!-- Status Banner -->
    <div class="<?php echo $c['bg']; ?> border <?php echo $c['border']; ?> rounded-2xl p-5 mb-5 flex items-center gap-4">
        <div class="text-3xl"><?php echo $sc['icon']; ?></div>
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-sm font-bold <?php echo $c['text']; ?>"><?php echo $sc['label']; ?></span>
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?php echo $c['badge']; ?>"><?php echo htmlspecialchars($sc['label']); ?></span>
            </div>
            <p class="text-xs <?php echo $c['text']; ?>/80 leading-relaxed"><?php echo $sc['desc']; ?></p>
        </div>
    </div>

    <!-- Timeline -->
    <?php if ($status !== 'rejected'): ?>
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm mb-5">
        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Progress</h2>
        <div class="flex items-center">
            <?php foreach ($allStatuses as $i => $step):
                $stepLabel = ucwords(str_replace('_', ' ', $step));
                $done = $currentIdx >= $i;
                $active = $currentIdx === $i;
            ?>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all
                        <?php echo $done ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-400'; ?>
                        <?php echo $active ? 'ring-4 ring-emerald-100' : ''; ?>">
                        <?php if ($done): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php else: echo $i + 1; endif; ?>
                    </div>
                    <span class="text-[10px] font-medium mt-1.5 text-center <?php echo $done ? 'text-emerald-700' : 'text-slate-400'; ?> leading-tight max-w-[60px]"><?php echo $stepLabel; ?></span>
                </div>
                <?php if ($i < count($allStatuses)-1): ?>
                <div class="flex-1 h-0.5 <?php echo $currentIdx > $i ? 'bg-emerald-400' : 'bg-slate-200'; ?> -mt-5"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Report Details -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm mb-5">
        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Report Details</h2>

        <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm mb-4">
            <div>
                <div class="text-xs text-slate-400 mb-0.5">Submitted</div>
                <div class="font-semibold text-slate-800"><?php echo date('M j, Y g:i A', strtotime($report['submission_date'])); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-0.5">Category</div>
                <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-0.5">Volume</div>
                <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-0.5">Condition</div>
                <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['waste_condition'] ?? 'N/A'); ?></div>
            </div>
            <?php if (!empty($report['purok'])): ?>
            <div>
                <div class="text-xs text-slate-400 mb-0.5">Purok</div>
                <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['purok']); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <div class="text-xs text-slate-400 mb-1">Description</div>
            <p class="text-sm text-slate-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($report['description'], ENT_QUOTES, 'UTF-8')); ?></p>
        </div>

        <!-- Map -->
        <div>
            <div class="text-xs text-slate-400 mb-2">Location</div>
            <div id="statusMap" class="border border-slate-200"></div>
        </div>
    </div>

    <!-- Photo -->
    <?php if (!empty($report['photo_path'])): ?>
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm mb-5">
        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Photo</h2>
        <img src="/brgy-waste-app-v3/public/uploads/<?php echo htmlspecialchars($report['photo_path']); ?>"
            class="w-full max-h-48 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Waste photo">
    </div>
    <?php endif; ?>

    <!-- Duplicate Notice -->
    <?php if (!empty($report['is_duplicate'])): ?>
    <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div>
            <div class="font-semibold">Possible Duplicate</div>
            <div class="text-amber-700/80 mt-0.5">A similar waste report was submitted nearby recently. Your report has been flagged and will be reviewed together.</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="flex gap-3">
        <a href="/brgy-waste-app-v3/public/index.php?url=guest/track"
            class="flex-1 h-11 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl shadow-sm hover:bg-slate-50 transition flex items-center justify-center gap-2 text-sm">
            Check Another
        </a>
        <a href="/brgy-waste-app-v3/public/index.php?url=guest"
            class="flex-1 h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2 text-sm">
            Submit New Report
        </a>
    </div>
</div>

<script>
    const lat = <?php echo json_encode((float)$report['latitude']); ?>;
    const lng = <?php echo json_encode((float)$report['longitude']); ?>;

    const map = L.map('statusMap', { zoomControl: false, dragging: false, scrollWheelZoom: false }).setView([lat, lng], 17);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
    const icon = L.divIcon({
        className: '',
        html: `<div style="background:#10B981;width:28px;height:28px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 12px rgba(16,185,129,0.4);"></div>`,
        iconSize: [28, 28], iconAnchor: [14, 28],
    });
    L.marker([lat, lng], { icon }).addTo(map);
</script>
</body>
</html>
