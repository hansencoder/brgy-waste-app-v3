<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$report = $data['report'] ?? null;
if (!$report) {
    header('Location: /brgy-waste-app-v3/public/admin/reports');
    exit;
}

function getStatusBadge($status) {
    $map = [
        'Pending'     => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Pending'],
        'Verified'    => ['bg' => '#DCFCE7', 'text' => '#15803D', 'label' => 'Verified'],
        'Resolved'    => ['bg' => '#E0F2FE', 'text' => '#0369A1', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => '#FEE2E2', 'text' => '#B91C1C', 'label' => 'Rejected'],
        'In Progress' => ['bg' => '#FFEDD5', 'text' => '#C2410C', 'label' => 'In Progress'],
    ];
    return $map[$status] ?? ['bg' => '#F3F4F6', 'text' => '#4B5563', 'label' => $status];
}

$badge = getStatusBadge($report['status']);
$reportId = 'WR-' . str_pad($report['id'], 7, '0', STR_PAD_LEFT);
$imgPath = !empty($report['photo_path']) ? '/brgy-waste-app-v3/public/uploads/' . $report['photo_path'] : null;
?>

<div class="min-h-screen bg-[#F1F1F1]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Back Link -->
                    <a href="/brgy-waste-app-v3/public/admin/reports" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        Back to Reports
                    </a>

                    <!-- Two-Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        <!-- Left Column (8/12) -->
                        <div class="lg:col-span-8 space-y-6">

                            <!-- Card 1: Report Metadata -->
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="font-mono text-sm text-slate-500"><?php echo htmlspecialchars($reportId); ?></span>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;"><?php echo $badge['label']; ?></span>
                                </div>
                                <h1 class="text-2xl font-extrabold text-slate-900 mb-6"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></h1>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Waste Category</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Estimated Quantity</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Waste Condition</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['waste_condition'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Purok</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Date Submitted</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Remarks</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['additional_remarks'] ?? '—'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Report Location Map -->
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h2 class="text-sm font-bold text-slate-900 mb-4">Report Location</h2>
                                <div class="bg-emerald-50 rounded-xl border border-emerald-200 overflow-hidden relative">
                                    <div id="reportLocationMap" class="h-64 w-full"></div>
                                    <div class="absolute bottom-3 right-3 bg-white rounded-full px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-md flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Barangay Dulong Bayan
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 mt-3 font-mono"><?php echo $report['latitude'] . ', ' . $report['longitude']; ?></p>
                            </div>
                        </div>

                        <!-- Right Column (4/12) -->
                        <div class="lg:col-span-4 space-y-6">

                            <!-- Card 1: Reporter Information -->
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-sm font-bold text-slate-900">Reporter Information</h2>
                                    <?php if (!empty($report['reporter_type']) && $report['reporter_type'] === 'guest'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                        Guest Report
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                        Resident
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm <?php echo (!empty($report['reporter_type']) && $report['reporter_type'] === 'guest') ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'; ?>">
                                        <?php
                                            $displayName = (!empty($report['reporter_type']) && $report['reporter_type'] === 'guest')
                                                ? ($report['guest_name'] ?? 'Guest')
                                                : ($report['resident_name'] ?? '');
                                            $initials = '';
                                            $parts = explode(' ', $displayName);
                                            foreach ($parts as $part) {
                                                if (!empty($part)) $initials .= strtoupper($part[0]);
                                            }
                                            echo htmlspecialchars($initials ?: '?');
                                        ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($displayName ?: 'N/A'); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <p class="flex items-center gap-2 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <?php
                                            if (!empty($report['reporter_type']) && $report['reporter_type'] === 'guest') {
                                                echo htmlspecialchars($report['guest_phone'] ?? 'N/A');
                                            } else {
                                                echo htmlspecialchars($report['resident_phone'] ?? 'N/A');
                                            }
                                        ?>
                                    </p>
                                    <?php if (empty($report['reporter_type']) || $report['reporter_type'] !== 'guest'): ?>
                                    <p class="flex items-center gap-2 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        <?php echo htmlspecialchars($report['resident_email'] ?? 'N/A'); ?>
                                    </p>
                                    <?php endif; ?>
                                    <?php if (!empty($report['tracking_number'])): ?>
                                    <p class="flex items-center gap-2 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Tracking: <span class="font-mono font-bold text-slate-800"><?php echo htmlspecialchars($report['tracking_number']); ?></span>
                                    </p>
                                    <?php endif; ?>
                                    <p class="flex items-center gap-2 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Total reports: <span class="font-bold text-slate-800"><?php echo $report['total_reports']; ?></span>
                                    </p>
                                    <?php if (!empty($report['reporter_type']) && $report['reporter_type'] === 'guest' && !empty($report['location_plausibility']) && $report['location_plausibility'] !== 'plausible'): ?>
                                    <div class="mt-2 p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-700 text-xs flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        <span>Location plausibility: <strong><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $report['location_plausibility']))); ?></strong></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card 2: Uploaded Evidence -->
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h2 class="text-sm font-bold text-slate-900 mb-4">Uploaded Evidence</h2>
                                <?php if ($imgPath && file_exists($_SERVER['DOCUMENT_ROOT'] . '/brgy-waste-app-v3/public/uploads/' . basename($report['photo_path']))): ?>
                                    <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                                        <img src="<?php echo $imgPath; ?>" alt="Report Photo" class="w-full h-auto object-cover">
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2">Photo attached · <?php echo date('M d, Y', strtotime($report['submission_date'])); ?></p>
                                <?php else: ?>
                                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        <p class="text-sm text-slate-500">No photo attached</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                                        <!-- Card 3: Take Action -->
                                        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                            <h2 class="text-sm font-bold text-slate-900 mb-4">Take Action</h2>
                                            <div class="space-y-3">

                                                <!-- CASE 1: REPORT IS PENDING -->
                                                <?php if ($report['status'] === 'Pending'): ?>
                                                    <!-- Option: Verify Report -->
                                                    <form action="/brgy-waste-app-v3/public/admin/updateReportStatus" method="POST" class="w-full">
                                                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                        <input type="hidden" name="action" value="verify">
                                                        <input type="hidden" name="remark" value="">
                                                        <button type="submit" class="w-full rounded-full bg-[#10B981] hover:bg-emerald-600 text-white font-bold py-3 px-4 shadow-sm transition flex items-center justify-center gap-2 text-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            Verify Report
                                                        </button>
                                                    </form>

                                                    <!-- Option: Reject Report -->
                                                    <form action="/brgy-waste-app-v3/public/admin/updateReportStatus" method="POST" class="w-full">
                                                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                        <input type="hidden" name="action" value="reject">
                                                        <div class="mb-3">
                                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Rejection Reason</label>
                                                            <input type="text" name="remark" placeholder="Enter reason..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-red-300 focus:ring-2 focus:ring-red-100 outline-none">
                                                        </div>
                                                        <button type="submit" class="w-full rounded-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3 px-4 transition flex items-center justify-center gap-2 text-sm border border-red-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                                            Reject Report
                                                        </button>
                                                    </form>

                                                <!-- CASE 2: REPORT IS VERIFIED -->
                                                <?php elseif ($report['status'] === 'Verified'): ?>
                                                    <!-- Option: Mark as In Progress -->
                                                    <form action="/brgy-waste-app-v3/public/admin/updateReportStatus" method="POST" class="w-full">
                                                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                        <input type="hidden" name="action" value="in_progress">
                                                        <input type="hidden" name="remark" value="">
                                                        <button type="submit" class="w-full rounded-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 shadow-sm transition flex items-center justify-center gap-2 text-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                            Start Progress
                                                        </button>
                                                    </form>

                                                    <!-- Option: Reject Report -->
                                                    <form action="/brgy-waste-app-v3/public/admin/updateReportStatus" method="POST" class="w-full">
                                                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                        <input type="hidden" name="action" value="reject">
                                                        <div class="mb-3">
                                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Rejection Reason</label>
                                                            <input type="text" name="remark" placeholder="Enter reason..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-red-300 focus:ring-2 focus:ring-red-100 outline-none">
                                                        </div>
                                                        <button type="submit" class="w-full rounded-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3 px-4 transition flex items-center justify-center gap-2 text-sm border border-red-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                                            Reject Report
                                                        </button>
                                                    </form>

                                                <!-- CASE 3: REPORT IS IN PROGRESS -->
                                                <?php elseif ($report['status'] === 'In Progress'): ?>
                                                    <!-- Option: Resolve Report -->
                                                    <form action="/brgy-waste-app-v3/public/admin/updateReportStatus" method="POST" class="w-full">
                                                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                        <input type="hidden" name="action" value="resolve">
                                                        <input type="hidden" name="remark" value="">
                                                        <button type="submit" class="w-full rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 shadow-sm transition flex items-center justify-center gap-2 text-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            Resolve Report
                                                        </button>
                                                    </form>

                                                    <!-- Option: Reject Report -->
                                                    <form action="/brgy-waste-app-v3/public/admin/updateReportStatus" method="POST" class="w-full">
                                                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                                        <input type="hidden" name="action" value="reject">
                                                        <div class="mb-3">
                                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Rejection Reason</label>
                                                            <input type="text" name="remark" placeholder="Enter reason..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-red-300 focus:ring-2 focus:ring-red-100 outline-none">
                                                        </div>
                                                        <button type="submit" class="w-full rounded-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3 px-4 transition flex items-center justify-center gap-2 text-sm border border-red-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                                            Reject Report
                                                        </button>
                                                    </form>

                                                <!-- CASE 4: REPORT IS RESOLVED OR REJECTED -->
                                                <?php else: ?>
                                                    <p class="text-sm text-slate-500 text-center py-4">
                                                        This report is already <strong class="text-slate-700"><?php echo strtolower($report['status']); ?></strong>.
                                                    </p>
                                                    <?php if ($report['status'] === 'Rejected' && !empty($report['reject_reason'])): ?>
                                                        <div class="rounded-xl bg-red-50 p-3 border border-red-200">
                                                            <p class="text-xs font-semibold text-red-700">Rejection Reason</p>
                                                            <p class="text-sm text-red-800 mt-1"><?php echo htmlspecialchars($report['reject_reason']); ?></p>
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

<!-- Leaflet for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
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
            html: `<div style="background-color: #10B981; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.5);"></div>`,
            className: '',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });

        // Marker pin for report location
        const marker = L.marker([lat, lng], { icon: greenIcon })
            .addTo(map)
            .bindPopup('<strong>Report Location</strong><br><?php echo htmlspecialchars($report['purok'] ?? 'Location'); ?>');

        // 1. Render Barangay Boundary (overall barangay perimeter)
        const barangayBoundary = {
            "type": "Feature",
            "properties": {},
            "geometry": {
                "type": "Polygon",
                "coordinates": [[
                    [120.8013517, 15.5699279], [120.8008898, 15.569572], [120.8008276, 15.5686578],
                    [120.8006126, 15.5685788], [120.8005542, 15.5678398], [120.8001844, 15.5672858],
                    [120.8000725, 15.5668847], [120.8001665, 15.566531], [120.7995785, 15.5663685],
                    [120.7989717, 15.5657033], [120.7987031, 15.5658025], [120.7984537, 15.5654243],
                    [120.7980956, 15.5652], [120.7977553, 15.5652043], [120.7975135, 15.5652862],
                    [120.7971285, 15.5652259], [120.7964691, 15.5648604], [120.7961709, 15.5643821],
                    [120.795562, 15.5643993], [120.7951681, 15.5637567], [120.7953561, 15.5632478],
                    [120.7952523, 15.562581], [120.7950598, 15.5617529], [120.7950416, 15.5611835],
                    [120.7945939, 15.5608471], [120.7946431, 15.5603295], [120.7943504, 15.5596467],
                    [120.7937415, 15.5597848], [120.7930393, 15.55916], [120.7928646, 15.5570187],
                    [120.7921781, 15.555107], [120.7912123, 15.554853], [120.7913399, 15.5543176],
                    [120.7915605, 15.5533236], [120.7918092, 15.5534046], [120.8001316, 15.5478115],
                    [120.8011058, 15.5481325], [120.8021398, 15.5484701], [120.8027807, 15.5485113],
                    [120.8032508, 15.5489723], [120.8030798, 15.5500426], [120.8038043, 15.5501365],
                    [120.8044282, 15.5502517], [120.8049495, 15.550614], [120.8058211, 15.5508445],
                    [120.8062911, 15.551569], [120.8071584, 15.5520964], [120.8076635, 15.5520903],
                    [120.8081181, 15.5524005], [120.8083454, 15.5523519], [120.8085979, 15.5525708],
                    [120.8088668, 15.5528807], [120.8118007, 15.5512389], [120.8126332, 15.550257],
                    [120.8153176, 15.5523838], [120.817434, 15.549628], [120.8219183, 15.5518119],
                    [120.8232918, 15.5522367], [120.8253946, 15.5516159], [120.8260956, 15.5512188],
                    [120.8281375, 15.5526533], [120.8298546, 15.5518644], [120.8310955, 15.5519514],
                    [120.8335885, 15.5541358], [120.8325752, 15.5557229], [120.8326161, 15.5574083],
                    [120.8332704, 15.5602447], [120.8283841, 15.5650646], [120.8236492, 15.5703491],
                    [120.82189, 15.5689622], [120.8219651, 15.5676998], [120.8203353, 15.5645562],
                    [120.8205697, 15.5594636], [120.8185042, 15.5617437], [120.8149287, 15.5609879],
                    [120.8126889, 15.5623097], [120.8092582, 15.5595308], [120.8032464, 15.5673914],
                    [120.8014669, 15.5699463], [120.8013517, 15.5699279]
                ]]
            }
        };

        L.geoJSON(barangayBoundary, {
            style: {
                color: '#3B82F6',
                weight: 2,
                fillColor: '#3B82F6',
                fillOpacity: 0.05,
                dashArray: '5,5'
            }
        }).addTo(map);

        // 2. Render all Purok Boundaries with precise matching for reported zone
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
                                weight: isCurrentPurok ? 3 : 2,
                                fillColor: strokeColor,
                                fillOpacity: isCurrentPurok ? 0.35 : 0.12
                            }
                        }).addTo(map);

                        pLayer.bindPopup('<strong>Purok: ' + pb.purok_name + '</strong>' + (isCurrentPurok ? '<br><span style="color:#059669;font-weight:bold;">Reported Location Zone</span>' : ''));
                        if (isCurrentPurok) {
                            activePurokLayer = pLayer;
                        }
                    }
                } catch(e) {
                    console.warn('Could not parse geometry for purok:', pb.purok_name, e);
                }
            }
        });

        // Smart Map Bounds: focus on reported marker + active purok polygon with optimal zoom
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