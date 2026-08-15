<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php
$data = $data ?? [];
$report = $data['report'] ?? [];
$timeline = $data['timeline'] ?? [];

if (empty($report)) {
    echo '<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
        <div class="max-w-md w-full rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-md">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 border border-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h1 class="text-xl font-bold text-slate-900">Report Not Found</h1>
            <p class="mt-2 text-xs text-slate-500">The waste report you are looking for does not exist or has been removed.</p>
            <a href="/brgy-waste-app-v3/public/resident/my_report" class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#0B2E22] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#083528]">Back to My Reports</a>
        </div>
    </div>';
    include __DIR__ . '/../layouts/footer.php';
    exit;
}

$rawStatus = strtolower($report['status'] ?? 'pending');
$statusConfig = [
    'pending'     => ['bg' => 'bg-amber-50 text-amber-900 border-amber-200', 'dot' => '#f59e0b', 'label' => 'Pending Verification', 'step' => 1],
    'verified'    => ['bg' => 'bg-blue-50 text-blue-900 border-blue-200', 'dot' => '#3b82f6', 'label' => 'Verified by Admin', 'step' => 2],
    'in_progress' => ['bg' => 'bg-purple-50 text-purple-900 border-purple-200', 'dot' => '#8b5cf6', 'label' => 'Collection In Progress', 'step' => 3],
    'resolved'    => ['bg' => 'bg-emerald-50 text-emerald-900 border-emerald-200', 'dot' => '#10b981', 'label' => 'Resolved & Cleaned', 'step' => 4],
    'rejected'    => ['bg' => 'bg-red-50 text-red-900 border-red-200', 'dot' => '#ef4444', 'label' => 'Rejected', 'step' => 0],
];
$cfg = $statusConfig[$rawStatus] ?? $statusConfig['pending'];
$currentStep = $cfg['step'];

$reportId = 'WR-' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);
$imgPath = !empty($report['photo_path']) ? '/brgy-waste-app-v3/public/uploads/' . $report['photo_path'] : '';

// Build timeline events
$events = [];
$events[] = [
    'status' => 'pending',
    'title' => 'Report Submitted',
    'desc' => 'Incident reported with evidence photos and GPS coordinates.',
    'date' => date('M d, Y • h:i A', strtotime($report['submission_date'])),
];

if (!empty($timeline)) {
    foreach ($timeline as $t) {
        $st = strtolower($t['new_status'] ?? 'pending');
        $title = 'Status changed to ' . ucfirst($st);
        if ($st === 'verified') $title = 'Report Verified by Staff';
        elseif ($st === 'in_progress') $title = 'Collection Dispatched';
        elseif ($st === 'resolved') $title = 'Waste Cleaned & Completed';
        elseif ($st === 'rejected') $title = 'Report Rejected';

        $events[] = [
            'status' => $st,
            'title' => $title,
            'desc' => !empty($t['remark']) ? $t['remark'] : 'System status transition recorded.',
            'date' => date('M d, Y • h:i A', strtotime($t['changed_at'])),
        ];
    }
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
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

                <!-- Header Title Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <a href="/brgy-waste-app-v3/public/resident/my_report" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition" title="Back to Reports">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        </a>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xl sm:text-2xl font-black text-slate-900"><?php echo $reportId; ?></span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?php echo $cfg['bg']; ?>">
                                    <?php echo $cfg['label']; ?>
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Submitted on <?php echo date('F d, Y \a\t h:i A', strtotime($report['submission_date'])); ?></p>
                        </div>
                    </div>
                    
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0B2E22] hover:bg-[#083528] text-white font-bold text-xs shadow-xs self-start sm:self-auto transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        <span>New Report</span>
                    </a>
                </div>

                <!-- Visual Progress Stepper (if not rejected) -->
                <?php if ($rawStatus !== 'rejected'): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Resolution Progress</p>
                    <div class="grid grid-cols-4 gap-2 relative">
                        <!-- Step 1: Submitted -->
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 1 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400'; ?>">
                                ✓
                            </div>
                            <p class="text-xs font-bold text-slate-900">Submitted</p>
                            <p class="text-[10px] text-slate-400">Incident logged</p>
                        </div>
                        <!-- Step 2: Verified -->
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 2 ? 'bg-emerald-600 text-white' : ($currentStep === 1 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-400'); ?>">
                                <?php echo $currentStep >= 2 ? '✓' : '2'; ?>
                            </div>
                            <p class="text-xs font-bold text-slate-900">Verified</p>
                            <p class="text-[10px] text-slate-400">Admin confirmed</p>
                        </div>
                        <!-- Step 3: In Progress -->
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 3 ? 'bg-emerald-600 text-white' : ($currentStep === 2 ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-400'); ?>">
                                <?php echo $currentStep >= 3 ? '✓' : '3'; ?>
                            </div>
                            <p class="text-xs font-bold text-slate-900">Dispatched</p>
                            <p class="text-[10px] text-slate-400">Truck en route</p>
                        </div>
                        <!-- Step 4: Resolved -->
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400'; ?>">
                                <?php echo $currentStep >= 4 ? '✓' : '4'; ?>
                            </div>
                            <p class="text-xs font-bold text-slate-900">Resolved</p>
                            <p class="text-[10px] text-slate-400">Site cleaned</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Two-Column Report Details Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Section (2 cols): Evidence Photos, Description, Timeline -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Evidence Photo Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-sm font-extrabold text-slate-900">Evidence Photo Attachment</h3>
                                <span class="text-xs text-slate-400 font-semibold">Original Upload</span>
                            </div>
                            <div class="p-4 sm:p-6 bg-slate-900/5 flex items-center justify-center">
                                <?php if (!empty($imgPath)): ?>
                                    <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Report Evidence" class="max-h-96 w-auto rounded-xl object-contain shadow-md bg-white">
                                <?php else: ?>
                                    <div class="py-16 text-center text-slate-400 text-xs">
                                        📷 No photo attached to this report.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Incident Description -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-3">
                            <h3 class="text-sm font-extrabold text-slate-900 pb-2 border-b border-slate-100">Incident Description</h3>
                            <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium">
                                <?php echo htmlspecialchars($report['description'] ?: 'No additional description provided.'); ?>
                            </p>
                        </div>

                        <!-- Status History Timeline -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
                            <h3 class="text-sm font-extrabold text-slate-900 pb-2 border-b border-slate-100">Status History</h3>
                            <div class="relative pl-6 space-y-6 border-l-2 border-slate-200 ml-2">
                                <?php foreach ($events as $ev): ?>
                                    <div class="relative">
                                        <div class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full bg-emerald-600 border-2 border-white ring-2 ring-emerald-100"></div>
                                        <div>
                                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                                <h4 class="text-xs font-extrabold text-slate-900"><?php echo htmlspecialchars($ev['title']); ?></h4>
                                                <span class="text-[10px] font-mono text-slate-400"><?php echo htmlspecialchars($ev['date']); ?></span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-1"><?php echo htmlspecialchars($ev['desc']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>

                    <!-- Right Section (1 col): GIS Location & Details -->
                    <div class="space-y-6">

                        <!-- GIS Location Map Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-sm font-extrabold text-slate-900">Incident Location</h3>
                                <button type="button" onclick="copyCoordinates()" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 cursor-pointer">
                                    Copy GPS
                                </button>
                            </div>
                            <div id="viewReportMap" class="h-64 w-full border-b border-slate-100"></div>
                            <div class="p-4 bg-slate-50 text-xs space-y-1">
                                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Pinned Coordinates</p>
                                <p id="gpsCoords" class="font-mono font-bold text-slate-800"><?php echo htmlspecialchars($report['latitude'] . ', ' . $report['longitude']); ?></p>
                            </div>
                        </div>

                        <!-- Report Summary Key-Values -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5 text-xs">
                            <h3 class="text-sm font-extrabold text-slate-900 pb-2 border-b border-slate-100">Report Details</h3>

                            <div class="flex justify-between items-center py-1 border-b border-slate-50">
                                <span class="text-slate-500">Waste Category</span>
                                <span class="font-bold text-slate-900"><?php echo htmlspecialchars($report['waste_category'] ?? 'General Waste'); ?></span>
                            </div>

                            <div class="flex justify-between items-center py-1 border-b border-slate-50">
                                <span class="text-slate-500">Estimated Volume</span>
                                <span class="font-bold text-slate-900"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="flex justify-between items-center py-1 border-b border-slate-50">
                                <span class="text-slate-500">Waste Condition</span>
                                <span class="font-bold text-slate-900"><?php echo htmlspecialchars($report['waste_condition'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="flex justify-between items-center py-1">
                                <span class="text-slate-500">Jurisdiction</span>
                                <span class="font-bold text-slate-900">Barangay Dulong Bayan</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = <?php echo (float)$report['latitude']; ?>;
    const lng = <?php echo (float)$report['longitude']; ?>;

    const map = L.map('viewReportMap', { zoomControl: true }).setView([lat, lng], 16);

    const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri', maxZoom: 19
    });
    const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    });
    satelliteMap.addTo(map);
    labelsMap.addTo(map);

    // Render Official Barangay Boundary
    const rawBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
    if (rawBrgyBoundary) {
        try {
            const brgyGeoObj = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            L.geoJSON(brgyGeoObj, {
                style: {
                    color: '#3B82F6',
                    weight: 2,
                    fillColor: '#3B82F6',
                    fillOpacity: 0.05,
                    dashArray: '5,5'
                }
            }).addTo(map);
        } catch(e) {
            console.error('Error rendering dynamic barangay boundary:', e);
        }
    }

    const customIcon = L.divIcon({
        html: '<div style="background-color:#10b981;width:18px;height:18px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.35);"></div>',
        className: '',
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });

    L.marker([lat, lng], { icon: customIcon }).addTo(map);
    setTimeout(() => map.invalidateSize(), 200);
});

function copyCoordinates() {
    const coords = document.getElementById('gpsCoords').innerText;
    navigator.clipboard.writeText(coords).then(() => {
        alert('Coordinates copied to clipboard: ' + coords);
    });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
