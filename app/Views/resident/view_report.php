<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php
$data = $data ?? [];
$report = $data['report'] ?? [];
$timeline = $data['timeline'] ?? [];
$photos = $data['photos'] ?? [];

if (empty($report)) {
    echo '<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
        <div class="max-w-md w-full rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-md">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 border border-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h1 class="text-xl font-bold text-slate-900">Report Not Found</h1>
            <p class="mt-2 text-xs text-slate-500">The waste report you are looking for does not exist or has been removed.</p>
            <a href="' . htmlspecialchars(app_url('resident/my_report')) . '" class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#0B2E22] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#083528]">Back to My Reports</a>
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
$primaryImg = !empty($report['photo_path']) ? format_asset_url($report['photo_path']) : '';

$allPhotos = [];
if (!empty($photos) && is_array($photos)) {
    foreach ($photos as $p) {
        $allPhotos[] = [
            'path' => format_asset_url($p['photo_path']),
            'is_primary' => (int)($p['is_primary'] ?? 0)
        ];
    }
} elseif (!empty($primaryImg)) {
    $allPhotos[] = ['path' => $primaryImg, 'is_primary' => 1];
}

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
        $title = 'Status: ' . ucfirst(str_replace('_', ' ', $st));
        $desc = $t['remarks'] ?? 'Status updated by barangay personnel.';
        
        if ($st === 'verified') $title = 'Report Verified';
        elseif ($st === 'in_progress') $title = 'Dispatched to Team';
        elseif ($st === 'resolved') $title = 'Resolved & Cleaned';
        elseif ($st === 'rejected') $title = 'Report Rejected';

        $events[] = [
            'status' => $st,
            'title' => $title,
            'desc' => $desc,
            'date' => date('M d, Y • h:i A', strtotime($t['created_at'] ?? 'now')),
        ];
    }
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    
    #viewReportMap {
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

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/resident_topbar.php'; ?>

        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500 flex-wrap">
                            <a href="<?php echo app_url('resident/my_report'); ?>" class="text-emerald-700 hover:underline">My Reports</a>
                            <span>/</span>
                            <span class="font-mono text-slate-700 font-extrabold"><?php echo htmlspecialchars($reportId); ?></span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-extrabold text-[10px] border <?php echo $cfg['bg']; ?>">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: <?php echo $cfg['dot']; ?>;"></span>
                                <span><?php echo htmlspecialchars($cfg['label']); ?></span>
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Report Forensic Summary</h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">Logged on <?php echo date('F d, Y \a\t h:i A', strtotime($report['submission_date'])); ?> · Purok <?php echo htmlspecialchars($report['purok'] ?? 'Not specified'); ?></p>
                    </div>
                    <?php
                    $isOwner = $data['is_owner'] ?? false;
                    $hasSupported = $data['has_supported'] ?? false;
                    $supportCount = (int)($report['support_count'] ?? 0);
                    ?>
                    <div class="flex items-center gap-2.5 self-start sm:self-auto flex-wrap">
                        <?php if ($rawStatus === 'pending'): ?>
                            <?php if ($isOwner): ?>
                                <button type="button" onclick="openEditModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 font-bold text-xs shadow-xs transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    <span>Edit Details</span>
                                </button>
                            <?php else: ?>
                                <?php if ($hasSupported): ?>
                                    <div class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-teal-50 text-teal-800 border border-teal-200 font-extrabold text-xs shadow-2xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>You Supported This Report</span>
                                        <span class="px-2 py-0.5 rounded-full bg-teal-100 text-teal-900 font-mono text-[10px]"><?php echo $supportCount; ?></span>
                                    </div>
                                <?php else: ?>
                                    <form action="<?php echo app_url('resident/support_report'); ?>" method="POST" class="inline-flex m-0">
                                        <input type="hidden" name="report_id" value="<?php echo (int)$report['id']; ?>">
                                        <input type="hidden" name="redirect_to" value="view_report">
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white font-extrabold text-xs shadow-xs transition cursor-pointer active:scale-95">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/></svg>
                                            <span>Support Report (+1 Upvote)</span>
                                            <?php if ($supportCount > 0): ?>
                                                <span class="px-1.5 py-0.2 rounded-md bg-teal-800 text-[10px] font-mono text-teal-200"><?php echo $supportCount; ?></span>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm font-bold text-emerald-800 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        <span><?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['flash_warning'])): ?>
                    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs sm:text-sm font-bold text-amber-800 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?php echo htmlspecialchars($_SESSION['flash_warning']); unset($_SESSION['flash_warning']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($rawStatus !== 'rejected'): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Resolution Progress</p>
                    <div class="grid grid-cols-4 gap-2 relative">
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 1 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400'; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-900">Submitted</p>
                        </div>
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 2 ? 'bg-emerald-600 text-white' : ($currentStep === 1 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-400'); ?>">
                                <?php if ($currentStep >= 2): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <?php else: echo '2'; endif; ?>
                            </div>
                            <p class="text-xs font-bold text-slate-900">Verified</p>
                        </div>
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 3 ? 'bg-emerald-600 text-white' : ($currentStep === 2 ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-400'); ?>">
                                <?php if ($currentStep >= 3): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <?php else: echo '3'; endif; ?>
                            </div>
                            <p class="text-xs font-bold text-slate-900">Dispatched</p>
                        </div>
                        <div class="text-center space-y-1.5">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs <?php echo $currentStep >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400'; ?>">
                                <?php if ($currentStep >= 4): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <?php else: echo '4'; endif; ?>
                            </div>
                            <p class="text-xs font-bold text-slate-900">Resolved</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="lg:col-span-2 space-y-6">

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-extrabold text-slate-900">Evidence Photo Attachments</h3>
                                    <span class="text-xs font-bold font-mono px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <?php echo count($allPhotos); ?> Photo<?php echo count($allPhotos) === 1 ? '' : 's'; ?> (1/3 Max)
                                    </span>
                                </div>
                                <span class="text-xs text-slate-400 font-semibold">Original Upload</span>
                            </div>

                            <?php if (!empty($allPhotos)): ?>
                                <div class="p-4 sm:p-6 bg-slate-900/5 space-y-4">
                                    <div class="relative flex items-center justify-center bg-white rounded-2xl p-2 border border-slate-200 shadow-xs group cursor-pointer" onclick="openReportLightbox(currentPhotoSrc, 'Evidence Photo')">
                                        <img id="mainActivePhoto" src="<?php echo htmlspecialchars($allPhotos[0]['path']); ?>" alt="Report Evidence" class="max-h-96 w-auto rounded-xl object-contain shadow-sm">
                                        
                                        <div class="absolute inset-0 bg-slate-950/20 opacity-0 group-hover:opacity-100 transition rounded-2xl flex items-center justify-center text-white">
                                            <div class="px-3 py-1.5 rounded-xl bg-slate-900/80 backdrop-blur-xs text-xs font-bold flex items-center gap-1.5 shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                                <span>Click to Zoom Full Screen</span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (count($allPhotos) > 1): ?>
                                        <div class="grid grid-cols-3 gap-3 pt-1">
                                            <?php foreach ($allPhotos as $idx => $p): ?>
                                                <button type="button" onclick="switchActivePhoto('<?php echo htmlspecialchars($p['path']); ?>', <?php echo $idx; ?>, this)" 
                                                        class="photo-thumb-btn relative rounded-xl overflow-hidden border-2 <?php echo $idx === 0 ? 'border-emerald-600 ring-2 ring-emerald-500/20' : 'border-slate-200'; ?> bg-white p-1 h-20 transition hover:border-emerald-400 cursor-pointer">
                                                    <img src="<?php echo htmlspecialchars($p['path']); ?>" alt="Thumbnail <?php echo $idx + 1; ?>" class="w-full h-full object-cover rounded-lg">
                                                    <span class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                                        <?php echo $idx === 0 ? 'Primary' : 'Photo ' . ($idx + 1); ?>
                                                    </span>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="py-16 text-center text-slate-400 text-xs flex items-center justify-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                    <span>No photo attached to this report.</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-3">
                            <h3 class="text-sm font-extrabold text-slate-900 pb-2 border-b border-slate-100">Incident Description</h3>
                            <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium">
                                <?php echo htmlspecialchars($report['description'] ?: 'No additional description provided.'); ?>
                            </p>
                        </div>

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

                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Incident Coordinates</h3>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">GIS Located</span>
                            </div>
                            <div id="viewReportMap" class="h-64 w-full border-b border-slate-100"></div>
                            <div class="p-4 bg-slate-50 text-xs space-y-1">
                                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Pinned Coordinates</p>
                                <p id="gpsCoords" class="font-mono font-bold text-slate-800"><?php echo htmlspecialchars($report['latitude'] . ', ' . $report['longitude']); ?></p>
                            </div>
                        </div>

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
                            <div class="flex justify-between items-center py-1 border-b border-slate-50">
                                <span class="text-slate-500">Community Support</span>
                                <span class="inline-flex items-center gap-1.5 font-extrabold text-teal-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/></svg>
                                    <span><?php echo (int)($report['support_count'] ?? 0); ?> Support<?php echo ((int)($report['support_count'] ?? 0) === 1) ? '' : 's'; ?></span>
                                </span>
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
let currentPhotoSrc = '<?php echo !empty($allPhotos) ? htmlspecialchars($allPhotos[0]['path']) : ''; ?>';

function switchActivePhoto(src, idx, btnEl) {
    currentPhotoSrc = src;
    const mainImg = document.getElementById('mainActivePhoto');
    if (mainImg) {
        mainImg.src = src;
    }

    document.querySelectorAll('.photo-thumb-btn').forEach(btn => {
        btn.classList.remove('border-emerald-600', 'ring-2', 'ring-emerald-500/20');
        btn.classList.add('border-slate-200');
    });

    if (btnEl) {
        btnEl.classList.remove('border-slate-200');
        btnEl.classList.add('border-emerald-600', 'ring-2', 'ring-emerald-500/20');
    }
}

function openReportLightbox(src, title) {
    const modal = document.getElementById('imageLightboxModal');
    const img = document.getElementById('lightboxImage');
    const titleEl = document.getElementById('lightboxTitle');

    if (modal && img) {
        img.src = src || currentPhotoSrc;
        if (titleEl) titleEl.textContent = title || 'Evidence Photo Preview';
        modal.classList.remove('hidden');
    }
}

function closeReportLightbox() {
    const modal = document.getElementById('imageLightboxModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

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

    const customIcon = L.divIcon({
        html: '<div style="background-color:#10b981;width:18px;height:18px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.35);"></div>',
        className: '',
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });

    L.marker([lat, lng], { icon: customIcon }).addTo(map)
        .bindPopup('<b>Report Location</b><br><?php echo htmlspecialchars($report['waste_category'] ?? 'Waste Incident'); ?>')
        .openPopup();

    setTimeout(() => map.invalidateSize(), 250);
});

function openEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) modal.classList.remove('hidden');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) modal.classList.add('hidden');
}
</script>

<div id="imageLightboxModal" style="z-index: 99999 !important;" class="fixed inset-0 bg-slate-950/85 backdrop-blur-md hidden flex items-center justify-center p-4 sm:p-8" onclick="closeReportLightbox()">
    <div class="relative max-w-4xl w-full max-h-[90vh] bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-700/60 flex flex-col" onclick="event.stopPropagation()">
        <div class="px-5 py-3.5 bg-slate-900/95 border-b border-slate-800 flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <span id="lightboxTitle" class="text-xs font-bold text-slate-200">Evidence Photo Preview</span>
            </div>
            <button type="button" onclick="closeReportLightbox()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-3 sm:p-6 flex items-center justify-center bg-slate-950/60 overflow-auto max-h-[80vh]">
            <img id="lightboxImage" src="" alt="Full Evidence Preview" class="max-h-[75vh] w-auto max-w-full object-contain rounded-lg shadow-2xl">
        </div>
    </div>
</div>

<?php if ($rawStatus === 'pending'): ?>
<div id="editModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-200">
        <form action="<?php echo app_url('resident/edit_report/' . $report['id']); ?>" method="POST">
            <div class="bg-[#0B2E22] px-6 py-4 flex items-center justify-between text-white border-b border-emerald-900">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    <h3 class="text-sm font-extrabold text-white">Edit Report Details</h3>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-emerald-300 hover:text-white cursor-pointer transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Waste Category</label>
                    <select name="category_id" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition" required>
                        <?php foreach (($data['categories'] ?? []) as $cat): ?>
                            <option value="<?php echo (int)$cat['category_id']; ?>" <?php echo ((int)$cat['category_id'] === (int)$report['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estimated Volume</label>
                        <select name="quantity_id" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition" required>
                            <?php foreach (($data['quantities'] ?? []) as $qty): ?>
                                <option value="<?php echo (int)$qty['quantity_id']; ?>" <?php echo ((int)$qty['quantity_id'] === (int)$report['quantity_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($qty['quantity_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Waste Condition</label>
                        <select name="condition_id" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition" required>
                            <?php foreach (($data['conditions'] ?? []) as $cnd): ?>
                                <option value="<?php echo (int)$cnd['condition_id']; ?>" <?php echo ((int)$cnd['condition_id'] === (int)$report['condition_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cnd['condition_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description &amp; Landmarks</label>
                    <textarea name="description" rows="3" maxlength="500" class="w-full p-3 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition resize-none leading-relaxed" required><?php echo htmlspecialchars($report['description']); ?></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
