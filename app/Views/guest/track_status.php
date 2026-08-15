<?php
$barangay = $data['barangay'] ?? [];
$systemName = $barangay['system_name'] ?? 'Barangay Waste Management System';
$shortName  = $barangay['system_short_name'] ?? 'WasteWatch';
$brgyName   = $barangay['barangay_name'] ?? 'Dulong Bayan';
$sysLogo    = $barangay['system_logo'] ?? '';
$brgyLogo   = $barangay['barangay_logo'] ?? '';
$activeLogo = !empty($sysLogo) ? $sysLogo : (!empty($brgyLogo) ? $brgyLogo : '');

$report = $data['report'] ?? [];
$rawStatus = $report['status'] ?? 'Pending';
$statusKey = str_replace([' ', '-'], '_', strtolower(trim($rawStatus)));
$status    = $statusKey;

$statusConfig = [
    'pending'     => ['label' => 'Pending Review', 'color' => 'amber',   'icon' => '🕐', 'desc' => 'Your report has been received and is queued for verification by the barangay desk.'],
    'verified'    => ['label' => 'Verified',       'color' => 'blue',    'icon' => '✅', 'desc' => 'Your report has been verified and assigned for collection routing.'],
    'in_progress' => ['label' => 'In Progress',    'color' => 'violet',  'icon' => '🚛', 'desc' => 'Waste management personnel and collection units are actively addressing the area.'],
    'resolved'    => ['label' => 'Resolved',       'color' => 'emerald', 'icon' => '🎉', 'desc' => 'The waste site has been successfully cleared and resolved. Thank you for your vigilance!'],
    'rejected'    => ['label' => 'Rejected',       'color' => 'red',     'icon' => '❌', 'desc' => 'Your report could not be processed. Please contact the barangay hall for details.'],
];
$sc = $statusConfig[$statusKey] ?? $statusConfig['pending'];

$colorMap = [
    'amber'   => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-800',   'badge' => 'bg-amber-100 text-amber-900 border-amber-300'],
    'blue'    => ['bg' => 'bg-blue-50',    'border' => 'border-blue-200',    'text' => 'text-blue-800',    'badge' => 'bg-blue-100 text-blue-900 border-blue-300'],
    'violet'  => ['bg' => 'bg-purple-50',  'border' => 'border-purple-200',  'text' => 'text-purple-800',  'badge' => 'bg-purple-100 text-purple-900 border-purple-300'],
    'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300'],
    'red'     => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'text' => 'text-red-800',     'badge' => 'bg-red-100 text-red-900 border-red-300'],
];
$c = $colorMap[$sc['color']];

$allStatuses = ['pending', 'verified', 'in_progress', 'resolved'];
$currentIdx = array_search($statusKey, $allStatuses);
if ($currentIdx === false) $currentIdx = -1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Status #<?php echo htmlspecialchars($report['tracking_number'] ?? ''); ?> · <?php echo htmlspecialchars($shortName); ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- Google Fonts Miranda Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
        #statusMap { height: 210px; border-radius: 1rem; overflow: hidden; }
        .status-card {
            background: #ffffff;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen py-8 sm:py-12 px-4 selection:bg-emerald-500 selection:text-white">

<div class="max-w-2xl mx-auto space-y-6">

    <!-- Top Bar Navigation & Branding -->
    <div class="flex items-center justify-between gap-4">
        <a href="/brgy-waste-app-v3/public/index.php?url=guest/track" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-extrabold text-slate-700 hover:text-slate-950 hover:bg-slate-100 transition shadow-2xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
            Track Another
        </a>

        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-emerald-700 p-0.5 shadow-xs overflow-hidden flex items-center justify-center border border-slate-200 shrink-0">
                <?php if (!empty($activeLogo)): ?>
                    <img src="<?php echo htmlspecialchars($activeLogo); ?>" alt="Logo" class="w-full h-full rounded-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full rounded-full bg-[#0B2E22] flex items-center justify-center text-white text-xs">🏛️</div>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <span class="text-xs font-extrabold text-slate-900 block leading-tight"><?php echo htmlspecialchars($shortName); ?></span>
                <span class="text-[10px] font-semibold text-slate-500 block">Brgy. <?php echo htmlspecialchars($brgyName); ?></span>
            </div>
        </div>
    </div>

    <!-- Header Title -->
    <div class="status-card rounded-3xl p-6 sm:p-7 border border-slate-200/80 space-y-5">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Tracking Number</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">
                    <?php echo htmlspecialchars($report['tracking_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                </h1>
            </div>
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-black border <?php echo $c['badge']; ?> self-start sm:self-auto uppercase tracking-wide">
                <?php echo htmlspecialchars($sc['label']); ?>
            </span>
        </div>

        <!-- Status Banner -->
        <div class="<?php echo $c['bg']; ?> border <?php echo $c['border']; ?> rounded-2xl p-5 flex items-start sm:items-center gap-4 shadow-2xs">
            <div class="text-3xl shrink-0"><?php echo $sc['icon']; ?></div>
            <div class="space-y-0.5">
                <h3 class="text-sm font-extrabold <?php echo $c['text']; ?>">
                    Status: <?php echo $sc['label']; ?>
                </h3>
                <p class="text-xs font-semibold <?php echo $c['text']; ?>/90 leading-relaxed">
                    <?php echo $sc['desc']; ?>
                </p>
            </div>
        </div>

        <!-- Progress Stepper / Timeline -->
        <?php if ($status !== 'rejected'): ?>
        <div class="pt-2">
            <div class="flex items-center justify-between text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4">
                <span>Incident Lifecycle</span>
                <span class="text-emerald-700 font-bold">Step <?php echo max(1, $currentIdx + 1); ?> of 4</span>
            </div>

            <div class="relative flex items-center justify-between">
                <!-- Connecting Line -->
                <div class="absolute left-6 right-6 top-4 h-1 bg-slate-100 -z-0">
                    <div class="h-full bg-emerald-500 transition-all duration-500" 
                         style="width: <?php echo max(0, min(100, ($currentIdx / 3) * 100)); ?>%;"></div>
                </div>

                <?php foreach ($allStatuses as $i => $step):
                    $stepLabel = ucwords(str_replace('_', ' ', $step));
                    $done = $currentIdx >= $i;
                    $active = $currentIdx === $i;
                ?>
                    <div class="flex flex-col items-center relative z-10">
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-black transition-all
                            <?php echo $done ? 'bg-emerald-600 border-emerald-600 text-white shadow-xs' : 'bg-white border-slate-300 text-slate-400'; ?>
                            <?php echo $active ? 'ring-4 ring-emerald-100 ring-offset-1 scale-110' : ''; ?>">
                            <?php if ($done): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php else: echo $i + 1; endif; ?>
                        </div>
                        <span class="text-[10px] font-extrabold mt-2 text-center <?php echo $done ? 'text-slate-900' : 'text-slate-400'; ?> leading-tight max-w-[65px]">
                            <?php echo $stepLabel; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Report Information & Map Canvas -->
    <div class="status-card rounded-3xl p-6 sm:p-7 border border-slate-200/80 space-y-5">
        <h2 class="text-xs font-black text-slate-400 uppercase tracking-wider">Report Details &amp; Coordinates</h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Submitted</span>
                <span class="font-extrabold text-slate-900 mt-0.5 block"><?php echo date('M j, Y', strtotime($report['submission_date'])); ?></span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Category</span>
                <span class="font-extrabold text-slate-900 mt-0.5 block"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Volume</span>
                <span class="font-extrabold text-slate-900 mt-0.5 block"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Purok Area</span>
                <span class="font-extrabold text-slate-900 mt-0.5 block"><?php echo htmlspecialchars($report['purok'] ?? 'Barangay'); ?></span>
            </div>
        </div>

        <?php if (!empty($report['description'])): ?>
        <div class="space-y-1.5">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Incident Remarks:</span>
            <p class="text-xs sm:text-sm text-slate-700 bg-slate-50 p-3.5 rounded-xl border border-slate-200 leading-relaxed font-semibold">
                <?php echo nl2br(htmlspecialchars($report['description'], ENT_QUOTES, 'UTF-8')); ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Map Viewport -->
        <div class="space-y-2">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Geo-Location Pin:</span>
            <div id="statusMap" class="border border-slate-200 shadow-inner"></div>
        </div>

        <!-- Uploaded Photo if Available -->
        <?php if (!empty($report['photo_path'])): ?>
        <div class="space-y-2 pt-2 border-t border-slate-100">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Submitted Photo Evidence:</span>
            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 max-h-60 flex items-center justify-center">
                <img src="/brgy-waste-app-v3/public/uploads/<?php echo htmlspecialchars($report['photo_path']); ?>"
                     class="w-full h-full object-cover" alt="Waste Evidence Photo">
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="/brgy-waste-app-v3/public/index.php?url=guest/track"
           class="flex-1 h-12 bg-white border border-slate-200 text-slate-800 font-extrabold rounded-2xl shadow-2xs hover:bg-slate-100 transition flex items-center justify-center gap-2 text-xs sm:text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            Track Another Incident
        </a>
        <a href="/brgy-waste-app-v3/public/index.php?url=guest"
           class="flex-1 h-12 bg-[#0B2E22] hover:bg-[#084232] text-white font-extrabold rounded-2xl shadow-xs transition flex items-center justify-center gap-2 text-xs sm:text-sm border border-emerald-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            Submit New Waste Report
        </a>
    </div>

</div>

<script>
    const lat = <?php echo json_encode((float)($report['latitude'] ?? 15.558)); ?>;
    const lng = <?php echo json_encode((float)($report['longitude'] ?? 120.803)); ?>;

    const map = L.map('statusMap', { zoomControl: false, dragging: true, scrollWheelZoom: false }).setView([lat, lng], 17);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
    
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

    const icon = L.divIcon({
        className: '',
        html: `<div style="background:#059669; width:26px; height:26px; border-radius:50% 50% 50% 0; transform:rotate(-45deg); border:3px solid #ffffff; box-shadow:0 4px 12px rgba(0,0,0,0.35);"></div>`,
        iconSize: [26, 26], iconAnchor: [13, 26],
    });
    L.marker([lat, lng], { icon }).addTo(map);
</script>
</body>
</html>
