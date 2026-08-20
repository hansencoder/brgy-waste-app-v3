<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$report = $data['report'] ?? null;
if (!$report) {
    header('Location: ' . app_url('admin/reports'));
    exit;
}

function getStatusBadge($status) {
    $map = [
        'Pending'     => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'label' => 'Pending'],
        'Verified'    => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'label' => 'Verified'],
        'Resolved'    => ['bg' => 'bg-sky-50 text-sky-800 border-sky-200', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200', 'label' => 'Rejected'],
        'In Progress' => ['bg' => 'bg-orange-50 text-orange-800 border-orange-200', 'label' => 'In Progress'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'label' => $status];
}

$badge = getStatusBadge($report['status']);
$reportId = 'WR-' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);

// Collect all report photos
$reportPhotos = !empty($report['photos']) ? $report['photos'] : [];
if (empty($reportPhotos) && !empty($report['photo_path'])) {
    $reportPhotos = [['photo_path' => $report['photo_path'], 'is_primary' => 1]];
}

$primaryPhoto = !empty($reportPhotos[0]['photo_path']) ? format_asset_url($reportPhotos[0]['photo_path']) : null;
$isGuest = !empty($report['reporter_type']) && $report['reporter_type'] === 'guest';
$reporterName = $isGuest ? ($report['guest_name'] ?: 'Guest Citizen') : ($report['resident_name'] ?: 'Barangay Resident');
$reporterContact = $isGuest ? ($report['guest_phone'] ?: 'N/A') : ($report['resident_phone'] ?: ($report['resident_email'] ?: 'N/A'));
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- Header & Navigation Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
                        <div class="flex items-center gap-3">
                            <a href="<?php echo app_url('admin/reports'); ?>" 
                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-slate-900 shadow-2xs transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                            </a>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-slate-500"><?php echo htmlspecialchars($reportId); ?></span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border <?php echo $badge['bg']; ?>">
                                        <?php echo $badge['label']; ?>
                                    </span>
                                    <?php if ($isGuest): ?>
                                        <span class="px-2 py-0.5 rounded-md bg-amber-100/80 text-amber-800 text-[10px] font-bold uppercase tracking-wider border border-amber-200">Guest</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-100/80 text-emerald-800 text-[10px] font-bold uppercase tracking-wider border border-emerald-200">Resident</span>
                                    <?php endif; ?>
                                </div>
                                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                                    <?php echo htmlspecialchars($report['waste_category'] ?? 'Waste Incident'); ?>
                                </h1>
                            </div>
                        </div>

                        <!-- Quick Meta Badges -->
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 shadow-2xs font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <?php echo date('M d, Y · g:i A', strtotime($report['submission_date'])); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Main Grid Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        <!-- Left Column (7/12): Incident Details, Location & Timeline -->
                        <div class="lg:col-span-7 space-y-6">

                            <!-- Incident Overview Card -->
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-5">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Incident Details</h2>
                                    <span class="text-xs text-slate-500 font-semibold font-mono">ID: #<?php echo $report['id']; ?></span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                                    <div class="space-y-1">
                                        <span class="text-slate-400 font-medium block">Waste Category</span>
                                        <span class="font-bold text-slate-900 text-sm block"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-slate-400 font-medium block">Estimated Quantity</span>
                                        <span class="font-bold text-slate-900 text-sm block"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-slate-400 font-medium block">Waste Condition</span>
                                        <span class="font-bold text-slate-900 text-sm block"><?php echo htmlspecialchars($report['waste_condition'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-slate-400 font-medium block">Purok / Zone</span>
                                        <span class="font-bold text-slate-900 text-sm block flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            <?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?>
                                        </span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-slate-400 font-medium block">Submitted Date</span>
                                        <span class="font-bold text-slate-900 text-sm block"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-slate-400 font-medium block">Reference Code</span>
                                        <span class="font-mono font-bold text-emerald-700 text-xs block truncate"><?php echo htmlspecialchars($report['tracking_number'] ?? $reportId); ?></span>
                                    </div>
                                </div>

                                <!-- Remarks / Description Box -->
                                <?php if (!empty($report['additional_remarks'])): ?>
                                <div class="pt-3 border-t border-slate-100 space-y-1.5">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Reporter Remarks</span>
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                                        <?php echo nl2br(htmlspecialchars($report['additional_remarks'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Map Location Card -->
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        </div>
                                        <h2 class="text-sm font-bold text-slate-900">Geographic Location</h2>
                                    </div>
                                    <span class="font-mono text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                        <?php echo number_format((float)$report['latitude'], 5) . ', ' . number_format((float)$report['longitude'], 5); ?>
                                    </span>
                                </div>

                                <div class="rounded-xl overflow-hidden border border-slate-200 relative">
                                    <div id="reportLocationMap" class="h-64 sm:h-72 w-full"></div>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>Purok: <strong class="text-slate-800"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></strong></span>
                                    <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                                        Inside Official Barangay Dulong Bayan Perimeter
                                    </span>
                                </div>
                            </div>

                            <!-- Timeline Stepper Card -->
                            <?php
                            $timeline = $report['timeline'] ?? [];
                            // Ensure initial report submission is always represented
                            if (empty($timeline)) {
                                $timeline = [
                                    [
                                        'status_name' => 'Report Submitted',
                                        'changed_at' => $report['submission_date'],
                                        'changed_by_name' => $reporterName,
                                        'remark' => 'Initial submission received with status: ' . ($report['status'] ?? 'Pending')
                                    ]
                                ];
                            } else {
                                $hasInitial = false;
                                foreach ($timeline as $t) {
                                    $st = strtolower($t['new_status'] ?? ($t['status_name'] ?? ''));
                                    if ($st === 'pending' || $st === 'report submitted') {
                                        $hasInitial = true;
                                        break;
                                    }
                                }
                                if (!$hasInitial) {
                                    array_unshift($timeline, [
                                        'status_name' => 'Report Submitted',
                                        'changed_at' => $report['submission_date'],
                                        'changed_by_name' => $reporterName,
                                        'remark' => 'Initial report submitted with status: Pending'
                                    ]);
                                }
                            }
                            ?>
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Activity &amp; Status Timeline</h2>
                                    <span class="text-xs text-slate-500 font-semibold font-mono"><?php echo count($timeline); ?> Event<?php echo count($timeline) !== 1 ? 's' : ''; ?></span>
                                </div>
                                <div class="space-y-4 pl-2 border-l-2 border-slate-100">
                                    <?php foreach ($timeline as $tItem): 
                                        $statusTitle = $tItem['new_status_name'] ?? $tItem['status_name'] ?? (!empty($tItem['new_status']) ? ucfirst(str_replace('_', ' ', $tItem['new_status'])) : 'Status Update');
                                        $timeStr = !empty($tItem['changed_at']) ? $tItem['changed_at'] : (!empty($tItem['created_at']) ? $tItem['created_at'] : (!empty($tItem['timestamp']) ? $tItem['timestamp'] : $report['submission_date']));
                                        $remarkText = $tItem['remark'] ?? $tItem['remarks'] ?? $tItem['comment'] ?? '';
                                        $actor = $tItem['changed_by_name'] ?? '';
                                    ?>
                                    <div class="relative pl-4 space-y-1">
                                        <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-white"></div>
                                        <div class="flex flex-wrap items-center justify-between gap-1 text-xs">
                                            <span class="font-bold text-slate-900"><?php echo htmlspecialchars($statusTitle); ?></span>
                                            <span class="text-slate-400 font-mono text-[11px]"><?php echo date('M d, Y · g:i A', strtotime($timeStr)); ?></span>
                                        </div>
                                        <?php if (!empty($actor)): ?>
                                            <p class="text-[11px] text-slate-500 font-medium">By: <strong class="text-slate-700"><?php echo htmlspecialchars($actor); ?></strong></p>
                                        <?php endif; ?>
                                        <?php if (!empty($remarkText)): ?>
                                        <p class="text-xs text-slate-600 bg-slate-50 rounded-lg p-2.5 border border-slate-100 leading-relaxed"><?php echo htmlspecialchars($remarkText); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>

                        <!-- Right Column (5/12): Multi-Photo Evidence Gallery, Reporter Card & Actions -->
                        <div class="lg:col-span-5 space-y-6">

                            <!-- Evidence Photos Gallery (Compact Layout & Multi-Image Visibility) -->
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        </div>
                                        <h2 class="text-sm font-bold text-slate-900">Uploaded Evidence</h2>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold font-mono">
                                        <?php echo count($reportPhotos); ?> Photo<?php echo count($reportPhotos) !== 1 ? 's' : ''; ?>
                                    </span>
                                </div>

                                <?php if (!empty($reportPhotos)): ?>
                                    <!-- Primary Compact Photo Display -->
                                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 group cursor-pointer aspect-4/3 max-h-60" 
                                         onclick="openLightbox(0)">
                                        <img id="mainEvidenceImg" 
                                             src="<?php echo htmlspecialchars(format_asset_url($reportPhotos[0]['photo_path'])); ?>" 
                                             alt="Primary Evidence Photo" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/f1f5f9/94a3b8?text=Image+Unavailable';">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-3">
                                            <span class="text-white text-xs font-bold flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                                Click to enlarge high-res view
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Multi-Image Thumbnails Grid (Visible thumbnails) -->
                                    <?php if (count($reportPhotos) > 1): ?>
                                    <div class="space-y-1.5 pt-1">
                                        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block">All Attached Photos</span>
                                        <div class="grid grid-cols-3 gap-2">
                                            <?php foreach ($reportPhotos as $idx => $p): 
                                                $thumbUrl = format_asset_url($p['photo_path']);
                                            ?>
                                            <div class="relative aspect-square rounded-xl overflow-hidden border-2 <?php echo $idx === 0 ? 'border-emerald-500' : 'border-slate-200'; ?> bg-slate-100 cursor-pointer group hover:border-emerald-400 transition"
                                                 onclick="switchPrimaryImage('<?php echo htmlspecialchars($thumbUrl); ?>', <?php echo $idx; ?>)">
                                                <img src="<?php echo htmlspecialchars($thumbUrl); ?>" 
                                                     alt="Evidence #<?php echo $idx + 1; ?>" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-200"
                                                     onerror="this.onerror=null; this.src='https://placehold.co/200x200/f1f5f9/94a3b8?text=N/A';">
                                                <div class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-black/70 text-white text-[9px] font-mono">
                                                    #<?php echo $idx + 1; ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <div class="rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-8 text-center space-y-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300 mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        <p class="text-xs text-slate-500 font-medium">No photos attached to this report.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Reporter Profile Card -->
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Reporter Information</h2>
                                    <?php if ($isGuest): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-bold">
                                        Guest Reporter
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold">
                                        Official Resident
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm text-white shadow-2xs <?php echo $isGuest ? 'bg-amber-600' : 'bg-emerald-700'; ?>">
                                        <?php
                                            $initials = '';
                                            $parts = explode(' ', $reporterName);
                                            foreach ($parts as $part) {
                                                if (!empty($part)) $initials .= strtoupper($part[0]);
                                            }
                                            echo htmlspecialchars(substr($initials, 0, 2) ?: '?');
                                        ?>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-900 truncate"><?php echo htmlspecialchars($reporterName); ?></p>
                                        <p class="text-xs text-slate-500 truncate"><?php echo htmlspecialchars($report['purok'] ?? 'Barangay Dulong Bayan'); ?></p>
                                    </div>
                                </div>

                                <div class="space-y-2 pt-2 border-t border-slate-100 text-xs">
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span class="flex items-center gap-1.5 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-4.69-4.69 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                            Contact
                                        </span>
                                        <span class="font-bold text-slate-900 font-mono"><?php echo htmlspecialchars($reporterContact); ?></span>
                                    </div>

                                    <?php if (!$isGuest && !empty($report['resident_email'])): ?>
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span class="flex items-center gap-1.5 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                            Email
                                        </span>
                                        <span class="font-medium text-slate-800 truncate max-w-[180px]"><?php echo htmlspecialchars($report['resident_email']); ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <div class="flex items-center justify-between text-slate-600">
                                        <span class="flex items-center gap-1.5 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            Total Submissions
                                        </span>
                                        <span class="font-bold text-slate-900 font-mono"><?php echo (int)($report['total_reports'] ?? 1); ?></span>
                                    </div>

                                    <?php if ($isGuest && !empty($report['location_plausibility']) && $report['location_plausibility'] !== 'plausible'): ?>
                                    <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] flex items-start gap-2 mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-amber-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        <div>
                                            <span class="font-bold block">Plausibility Check</span>
                                            <span><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $report['location_plausibility']))); ?></span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Take Action Card -->
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs space-y-4">
                                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Take Action</h2>

                                <div class="space-y-3">
                                    <!-- CASE 1: PENDING -->
                                    <?php if ($report['status'] === 'Pending'): ?>
                                        <form action="<?php echo app_url('admin/updateReportStatus'); ?>" method="POST" class="w-full">
                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                            <input type="hidden" name="action" value="verify">
                                            <input type="hidden" name="remark" value="">
                                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-xs transition flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                Verify &amp; Accept Report
                                            </button>
                                        </form>

                                        <form action="<?php echo app_url('admin/updateReportStatus'); ?>" method="POST" class="w-full space-y-2 pt-2 border-t border-slate-100">
                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <div>
                                                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Rejection Reason</label>
                                                <input type="text" name="remark" placeholder="e.g. Duplicate or already cleared" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-rose-300 focus:ring-2 focus:ring-rose-100 outline-none">
                                            </div>
                                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition flex items-center justify-center gap-2 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                                Decline / Reject
                                            </button>
                                        </form>

                                    <!-- CASE 2: VERIFIED -->
                                    <?php elseif ($report['status'] === 'Verified'): ?>
                                        <form action="<?php echo app_url('admin/updateReportStatus'); ?>" method="POST" class="w-full">
                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                            <input type="hidden" name="action" value="in_progress">
                                            <input type="hidden" name="remark" value="">
                                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs sm:text-sm shadow-xs transition flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                Start Collection Progress
                                            </button>
                                        </form>

                                        <form action="<?php echo app_url('admin/updateReportStatus'); ?>" method="POST" class="w-full space-y-2 pt-2 border-t border-slate-100">
                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <div>
                                                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Rejection Reason</label>
                                                <input type="text" name="remark" placeholder="Enter reason..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-rose-300 focus:ring-2 focus:ring-rose-100 outline-none">
                                            </div>
                                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition flex items-center justify-center gap-2 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                                Reject Report
                                            </button>
                                        </form>

                                    <!-- CASE 3: IN PROGRESS -->
                                    <?php elseif ($report['status'] === 'In Progress'): ?>
                                        <form action="<?php echo app_url('admin/updateReportStatus'); ?>" method="POST" class="w-full">
                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                            <input type="hidden" name="action" value="resolve">
                                            <input type="hidden" name="remark" value="">
                                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs sm:text-sm shadow-xs transition flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                Mark as Cleaned / Resolved
                                            </button>
                                        </form>

                                    <!-- CASE 4: RESOLVED OR REJECTED -->
                                    <?php else: ?>
                                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-600 space-y-1">
                                            <p class="font-bold text-slate-800">Report is <?php echo htmlspecialchars($report['status']); ?></p>
                                            <p class="text-slate-500">No further status actions are required.</p>
                                        </div>
                                        <?php if ($report['status'] === 'Rejected' && !empty($report['reject_reason'])): ?>
                                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs space-y-1">
                                            <span class="font-bold text-rose-900 block">Rejection Reason:</span>
                                            <p class="text-rose-800"><?php echo htmlspecialchars($report['reject_reason']); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- High-Resolution Lightbox Modal for Evidence Photos -->
<div id="photoLightboxModal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-slate-300 p-2 rounded-full bg-white/10 hover:bg-white/20 transition cursor-pointer z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    <?php if (count($reportPhotos) > 1): ?>
    <button onclick="navigateLightbox(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-slate-300 p-3 rounded-full bg-white/10 hover:bg-white/20 transition cursor-pointer z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button onclick="navigateLightbox(1)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-slate-300 p-3 rounded-full bg-white/10 hover:bg-white/20 transition cursor-pointer z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <?php endif; ?>

    <div class="max-w-4xl max-h-[85vh] flex flex-col items-center">
        <img id="lightboxImg" src="" alt="Enlarged Evidence" class="max-w-full max-h-[80vh] rounded-xl object-contain shadow-2xl">
        <div class="text-white text-xs font-mono mt-3" id="lightboxCounter">Photo 1 of <?php echo count($reportPhotos); ?></div>
    </div>
</div>

<!-- Leaflet for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const galleryPhotos = <?php echo json_encode(array_map(function($p) { return format_asset_url($p['photo_path']); }, $reportPhotos)); ?>;
let currentLightboxIdx = 0;

function switchPrimaryImage(url, idx) {
    const mainImg = document.getElementById('mainEvidenceImg');
    if (mainImg) {
        mainImg.src = url;
    }
    currentLightboxIdx = idx;
}

function openLightbox(idx) {
    if (!galleryPhotos || !galleryPhotos.length) return;
    currentLightboxIdx = idx;
    document.getElementById('lightboxImg').src = galleryPhotos[currentLightboxIdx];
    document.getElementById('lightboxCounter').textContent = `Photo ${currentLightboxIdx + 1} of ${galleryPhotos.length}`;
    document.getElementById('photoLightboxModal').classList.remove('hidden');
}

function closeLightbox() {
    document.getElementById('photoLightboxModal').classList.add('hidden');
}

function navigateLightbox(dir) {
    if (!galleryPhotos || !galleryPhotos.length) return;
    currentLightboxIdx = (currentLightboxIdx + dir + galleryPhotos.length) % galleryPhotos.length;
    document.getElementById('lightboxImg').src = galleryPhotos[currentLightboxIdx];
    document.getElementById('lightboxCounter').textContent = `Photo ${currentLightboxIdx + 1} of ${galleryPhotos.length}`;
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigateLightbox(-1);
    if (e.key === 'ArrowRight') navigateLightbox(1);
});

// Map Initialization
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
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics',
            maxZoom: 19
        });
        const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '',
            maxZoom: 19
        });
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
        });

        satelliteMap.addTo(map);
        labelsMap.addTo(map);

        L.control.layers({
            "Satellite (Homes & Buildings)": L.layerGroup([satelliteMap, labelsMap]),
            "Street Map": streetMap
        }, null, { position: 'topright' }).addTo(map);

        const greenIcon = L.divIcon({
            html: `<div style="background-color: #10B981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.5);"></div>`,
            className: '',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        const marker = L.marker([lat, lng], { icon: greenIcon })
            .addTo(map)
            .bindPopup('<strong>Report Location</strong><br><?php echo htmlspecialchars($report['purok'] ?? 'Location'); ?>');

        // Dynamic Barangay Boundary
        const rawBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
        if (rawBrgyBoundary) {
            try {
                const brgyGeoObj = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
                L.geoJSON(brgyGeoObj, {
                    style: {
                        color: '#10B981',
                        weight: 2.5,
                        fillColor: '#D1FAE5',
                        fillOpacity: 0.1,
                        dashArray: '5,5'
                    }
                }).addTo(map);
            } catch(e) {
                console.error('Error rendering barangay boundary in view report:', e);
            }
        }

        // Purok Boundaries
        const purokBoundariesData = <?php echo json_encode($data['purok_boundaries'] ?? []); ?>;
        const colorPalette = ['#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#3B82F6', '#06B6D4', '#14B8A6'];
        const reportPurok = <?php echo json_encode(trim(strtolower($report['purok'] ?? ''))); ?>;
        
        let colorIdx = 0;
        let activePurokLayer = null;

        purokBoundariesData.forEach(pb => {
            if (pb.polygon_geometry) {
                try {
                    let geojson = (typeof pb.polygon_geometry === 'string') ? JSON.parse(pb.polygon_geometry) : pb.polygon_geometry;
                    if (geojson) {
                        const nameTrim = (pb.purok_name || '').trim().toLowerCase();
                        const isCurrentPurok = reportPurok && (nameTrim === reportPurok || nameTrim.includes(reportPurok) || reportPurok.includes(nameTrim));
                        const strokeColor = isCurrentPurok ? '#059669' : colorPalette[colorIdx % colorPalette.length];
                        colorIdx++;

                        const pLayer = L.geoJSON(geojson, {
                            style: {
                                color: strokeColor,
                                weight: isCurrentPurok ? 3 : 1.5,
                                fillColor: strokeColor,
                                fillOpacity: isCurrentPurok ? 0.35 : 0.08
                            }
                        }).addTo(map);

                        pLayer.bindPopup('<strong>Purok: ' + pb.purok_name + '</strong>' + (isCurrentPurok ? '<br><span style="color:#059669;font-weight:bold;">Reported Location Zone</span>' : ''));
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

        setTimeout(() => map.invalidateSize(), 250);
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>