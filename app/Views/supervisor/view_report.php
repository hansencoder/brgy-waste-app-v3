<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$report = $data['report'] ?? null;
if (!$report) {
    header('Location: ' . app_url('supervisor/reports'));
    exit;
}

function getSupervisorDetailBadge($status) {
    $map = [
        'Pending'     => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/60', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
        'Verified'    => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200/60', 'dot' => 'bg-blue-500', 'label' => 'Verified'],
        'In Progress' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200/60', 'dot' => 'bg-purple-500', 'label' => 'In Progress'],
        'Resolved'    => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200/60', 'dot' => 'bg-emerald-500', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200/60', 'dot' => 'bg-rose-500', 'label' => 'Rejected'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-500', 'label' => $status];
}

$badge = getSupervisorDetailBadge($report['status'] ?? 'Pending');
$reportId = 'WR-' . str_pad($report['id'], 5, '0', STR_PAD_LEFT);
$imgPath = !empty($report['photo_path']) ? format_asset_url($report['photo_path']) : null;

// Progress Stepper calculations
$statusName = $report['status'] ?? 'Pending';
$stepMap = [
    'Pending' => 1,
    'Verified' => 2,
    'In Progress' => 3,
    'Resolved' => 4,
    'Rejected' => -1
];
$currentStep = $stepMap[$statusName] ?? 1;
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    #reportLocationMap {
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
</style>

<div class="min-h-screen bg-[#F8FAFC] flex">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../layouts/supervisor_topbar.php'; ?>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto w-full">

            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="<?php echo app_url('supervisor/reports'); ?>" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-2xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-sm font-bold text-emerald-800"><?php echo htmlspecialchars($reportId); ?></span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border <?php echo $badge['bg']; ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                <?php echo $badge['label']; ?>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Submitted on <?php echo date('F j, Y \a\t g:i A', strtotime($report['submission_date'])); ?></p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="<?php echo app_url('supervisor/reports'); ?>" class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                        Back to Reports
                    </a>
                </div>
            </div>

            <!-- 4-Step Visual Progress Stepper -->
            <?php if ($currentStep > 0): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-4">Resolution Progress</p>
                <div class="grid grid-cols-4 gap-2 relative">
                    <?php
                    $steps = [
                        1 => ['title' => 'Submitted', 'desc' => 'Incident reported'],
                        2 => ['title' => 'Verified', 'desc' => 'Validated by admin'],
                        3 => ['title' => 'Dispatched', 'desc' => 'Crew on field'],
                        4 => ['title' => 'Resolved', 'desc' => 'Waste collected']
                    ];
                    foreach ($steps as $sNum => $sData):
                        $isCompleted = $currentStep >= $sNum;
                        $isCurrent = $currentStep === $sNum;
                    ?>
                    <div class="relative flex flex-col items-center text-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all <?php echo $isCompleted ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-400'; ?>">
                            <?php if ($isCompleted && !$isCurrent): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php else: ?>
                                <?php echo $sNum; ?>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs font-bold mt-2 <?php echo $isCompleted ? 'text-slate-900' : 'text-slate-400'; ?>"><?php echo $sData['title']; ?></span>
                        <span class="text-[10px] text-slate-400 hidden sm:block"><?php echo $sData['desc']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Main Content 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column (8 cols): Incident Details & Map -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Waste Details Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-5">
                        <div class="border-b border-slate-100 pb-4">
                            <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider block mb-1">Waste Details</span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900"><?php echo htmlspecialchars($report['waste_category'] ?? 'General Waste'); ?></h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Estimated Quantity</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5 block"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'Not specified'); ?></span>
                            </div>

                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Waste Condition</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5 block"><?php echo htmlspecialchars($report['waste_condition'] ?? 'Standard'); ?></span>
                            </div>

                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Purok Area</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span><?php echo htmlspecialchars($report['purok'] ?? 'Barangay Wide'); ?></span>
                                </span>
                            </div>

                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Community Support</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 mt-0.5 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h3"/><path d="M7 10 12 2a2 2 0 0 1 3 3.88Z"/></svg>
                                    <span><?php echo (int)($report['support_count'] ?? 0); ?> citizen upvotes</span>
                                </span>
                            </div>
                        </div>

                        <?php if (!empty($report['additional_remarks'])): ?>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Reporter Notes / Remarks</span>
                            <p class="text-slate-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($report['additional_remarks'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Interactive Geolocation Map -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Incident Coordinates</h3>
                                <p class="text-xs text-slate-500">Exact GPS pinpoint on satellite and street layer</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-mono text-xs font-semibold border border-emerald-100">
                                <?php echo number_format($report['latitude'], 5) . ', ' . number_format($report['longitude'], 5); ?>
                            </span>
                        </div>

                        <?php 
                        $gisPurok = $data['gis_detected_purok'] ?? null;
                        $gisPurokName = '';
                        if (is_array($gisPurok)) {
                            $gisPurokName = $gisPurok['purok_name'] ?? ('Purok ' . ($gisPurok['purok_id'] ?? ''));
                        } elseif (is_numeric($gisPurok) && (int)$gisPurok > 0) {
                            $gisPurokName = 'Purok ' . (int)$gisPurok;
                        } elseif (is_string($gisPurok)) {
                            $gisPurokName = $gisPurok;
                        }

                        $assignedPurok = trim($report['purok'] ?? '');
                        $isMismatch = (!empty($gisPurokName) && !empty($assignedPurok) && strcasecmp($assignedPurok, $gisPurokName) !== 0);
                        ?>
                        <?php if ($isMismatch): ?>
                        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-start gap-3 shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            <div>
                                <p class="font-bold text-amber-900">Spatial Mismatch Alert</p>
                                <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                                    Reporter labeled this incident as <span class="font-bold underline"><?php echo htmlspecialchars($assignedPurok); ?></span>, but official GIS spatial polygon detection confirms this pin is inside <span class="font-bold text-emerald-800 underline"><?php echo htmlspecialchars($gisPurokName); ?></span>.
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="rounded-xl overflow-hidden border border-slate-200 relative h-72">
                            <div id="reportLocationMap" class="h-full w-full"></div>
                        </div>
                    </div>

                    <!-- Status History Timeline -->
                    <?php if (!empty($report['timeline'])): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
                        <h3 class="text-sm font-bold text-slate-900">Status History Timeline</h3>
                        <div class="relative pl-4 space-y-4 border-l-2 border-slate-100">
                            <?php foreach ($report['timeline'] as $event): ?>
                            <div class="relative pl-3">
                                <span class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-white"></span>
                                <div class="text-xs">
                                    <p class="font-bold text-slate-800">
                                        Status changed to <span class="text-emerald-700 uppercase"><?php echo htmlspecialchars($event['new_status']); ?></span>
                                        <span class="font-normal text-slate-500">by <?php echo htmlspecialchars($event['changed_by_name'] ?? 'Authorized Officer'); ?></span>
                                    </p>
                                    <p class="text-[10px] text-slate-400 mt-0.5"><?php echo date('M j, Y \a\t g:i A', strtotime($event['changed_at'])); ?></p>
                                    <?php if (!empty($event['remark'])): ?>
                                        <p class="text-xs text-slate-600 mt-1 p-2 rounded-lg bg-slate-50 border border-slate-100"><?php echo htmlspecialchars($event['remark']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>

                <!-- Right Column (4 cols): Reporter & Photo Proof -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Reporter Profile Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
                        <?php
                        $isGuest = !empty($report['reporter_type']) && $report['reporter_type'] === 'guest';
                        $repName = $isGuest ? ($report['guest_name'] ?: 'Guest Citizen') : ($report['resident_name'] ?: 'Barangay Resident');
                        $repPhone = $isGuest ? ($report['guest_phone'] ?: '') : ($report['resident_phone'] ?: '');
                        $repEmail = $isGuest ? ($report['guest_email'] ?: (filter_var($report['guest_phone'] ?? '', FILTER_VALIDATE_EMAIL) ? $report['guest_phone'] : '')) : ($report['resident_email'] ?? '');
                        ?>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Reporter Profile</span>
                            <?php if ($isGuest): ?>
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-bold uppercase tracking-wider">Guest</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider">Resident</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full <?php echo $isGuest ? 'bg-amber-600' : 'bg-[#0B2E22]'; ?> text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                <?php echo strtoupper(substr($repName, 0, 1)); ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate"><?php echo htmlspecialchars($repName); ?></p>
                                <p class="text-xs text-emerald-700 font-medium flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span><?php echo htmlspecialchars($report['purok'] ?? 'Resident'); ?></span>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                            <?php if (!empty($repPhone)): ?>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-4.69-4.69 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <span><?php echo htmlspecialchars($repPhone); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($repEmail)): ?>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                <span class="truncate"><?php echo htmlspecialchars($repEmail); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Photo Evidence Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Uploaded Photo Evidence</span>
                        
                        <?php if (!empty($imgPath)): ?>
                            <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-50 group relative cursor-pointer" onclick="openPhotoModal('<?php echo htmlspecialchars($imgPath); ?>')">
                                <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Waste Evidence Photo" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                    <span>Expand Photo</span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-slate-400 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                No photo attached with report.
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

            </div>

        </main>

    </div>
</div>

<!-- Photo Modal Lightbox -->
<div id="photoModal" style="z-index: 99999 !important;" class="fixed inset-0 bg-black/80 backdrop-blur-xs hidden flex items-center justify-center p-4" onclick="closePhotoModal()">
    <div class="relative max-w-3xl max-h-[90vh] bg-white rounded-2xl overflow-hidden shadow-2xl p-2" onclick="event.stopPropagation()">
        <button onclick="closePhotoModal()" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
        <img id="modalImg" src="" class="max-h-[80vh] w-auto mx-auto rounded-xl object-contain">
    </div>
</div>

<script>
function openPhotoModal(src) {
    document.getElementById('modalImg').src = src;
    document.getElementById('photoModal').classList.remove('hidden');
}
function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof L !== 'undefined') {
        const lat = <?php echo (float)($report['latitude'] ?? 0); ?>;
        const lng = <?php echo (float)($report['longitude'] ?? 0); ?>;
        const map = L.map('reportLocationMap', {
            center: [lat, lng],
            zoom: 16,
            zoomControl: true,
            dragging: true,
            scrollWheelZoom: true
        });

        const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 19
        });
        const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '',
            maxZoom: 19
        });
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        });

        satelliteMap.addTo(map);
        labelsMap.addTo(map);

        L.control.layers({
            "Satellite": L.layerGroup([satelliteMap, labelsMap]),
            "Street Map": streetMap
        }, null, { position: 'topright' }).addTo(map);

        const greenIcon = L.divIcon({
            html: `<div style="background-color: #10B981; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.5);"></div>`,
            className: '',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });

        const marker = L.marker([lat, lng], { icon: greenIcon })
            .addTo(map)
            .bindPopup('<strong>Report Location</strong><br><?php echo htmlspecialchars($report['purok'] ?? 'Reported Location'); ?>');

        // Reporter Device GPS vs Pin comparison
        const repLat = <?php echo !empty($report['reporter_latitude']) ? (float)$report['reporter_latitude'] : 'null'; ?>;
        const repLng = <?php echo !empty($report['reporter_longitude']) ? (float)$report['reporter_longitude'] : 'null'; ?>;
        if (repLat && repLng && (Math.abs(repLat - lat) > 0.0001 || Math.abs(repLng - lng) > 0.0001)) {
            const blueIcon = L.divIcon({
                html: `<div style="background-color: #3B82F6; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 3px rgba(59,130,246,0.4);"></div>`,
                className: '',
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });
            L.marker([repLat, repLng], { icon: blueIcon }).addTo(map).bindPopup('<strong>Reporter Device GPS Position</strong>');
            L.polyline([[repLat, repLng], [lat, lng]], { color: '#3B82F6', weight: 2, dashArray: '4,6' }).addTo(map);
        }

        // Render Barangay Boundary if exists
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
            } catch(e) {}
        }

        // Render Purok Boundaries
        const purokBoundariesData = <?php echo json_encode($data['purok_boundaries'] ?? []); ?>;
        const reportPurok = <?php echo json_encode(trim(strtolower($report['purok'] ?? ''))); ?>;
        let activePurokLayer = null;

        purokBoundariesData.forEach(pb => {
            if (pb.polygon_geometry) {
                try {
                    let geojson = (typeof pb.polygon_geometry === 'string') ? JSON.parse(pb.polygon_geometry) : pb.polygon_geometry;
                    if (geojson) {
                        const nameTrim = (pb.purok_name || '').trim().toLowerCase();
                        const isCurrentPurok = reportPurok && (nameTrim === reportPurok || nameTrim.includes(reportPurok) || reportPurok.includes(nameTrim));
                        const strokeColor = isCurrentPurok ? '#059669' : '#94A3B8';

                        const pLayer = L.geoJSON(geojson, {
                            style: {
                                color: strokeColor,
                                weight: isCurrentPurok ? 3 : 1.5,
                                fillColor: strokeColor,
                                fillOpacity: isCurrentPurok ? 0.25 : 0.05
                            }
                        }).addTo(map);

                        if (isCurrentPurok) {
                            activePurokLayer = pLayer;
                        }
                    }
                } catch(e) {}
            }
        });

        if (activePurokLayer) {
            try {
                const bounds = L.featureGroup([marker, activePurokLayer]).getBounds();
                map.fitBounds(bounds.pad(0.2));
            } catch(e) {
                map.setView([lat, lng], 16);
            }
        } else {
            map.setView([lat, lng], 16);
        }

        setTimeout(() => map.invalidateSize(), 300);
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>