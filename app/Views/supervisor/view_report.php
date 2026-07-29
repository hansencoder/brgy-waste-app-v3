<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$report = $data['report'] ?? null;
if (!$report) {
    header('Location: /brgy-waste-app-v3/public/index.php?url=supervisor/reports');
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

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0 flex items-center gap-3">
                    <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/reports" class="text-slate-400 hover:text-slate-600 transition p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </a>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900">Report Details</h1>
                        <p class="text-xs text-slate-500 font-mono"><?php echo htmlspecialchars($reportId); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;"><?php echo $badge['label']; ?></span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        <!-- Left Column (8/12) -->
                        <div class="lg:col-span-8 space-y-6">

                            <!-- Metadata -->
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h1 class="text-2xl font-extrabold text-slate-900 mb-6"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></h1>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Waste Category</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Estimated Quantity</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Waste Condition</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['waste_condition'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Purok</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Date Submitted</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Remarks</p>
                                        <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($report['additional_remarks'] ?? '—'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
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

                            <!-- Status Timeline -->
                            <?php if (!empty($report['timeline'])): ?>
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h2 class="text-sm font-bold text-slate-900 mb-4">Status Timeline</h2>
                                <div class="relative pl-4 space-y-4">
                                    <div class="absolute left-1.5 top-2 bottom-2 w-[2px] bg-slate-200"></div>
                                    <?php foreach ($report['timeline'] as $event): ?>
                                        <div class="relative flex items-start gap-3">
                                            <div class="absolute left-[-6px] top-1.5 w-3 h-3 rounded-full bg-<?php echo $event['new_status'] == 'verified' ? 'blue' : ($event['new_status'] == 'resolved' ? 'emerald' : ($event['new_status'] == 'rejected' ? 'red' : 'amber')); ?>-500 border-2 border-white shadow-sm"></div>
                                            <div class="ml-4 flex-1">
                                                <p class="text-xs font-semibold text-slate-800">
                                                    <?php
                                                        if ($event['new_status'] == 'verified') echo 'Verified';
                                                        elseif ($event['new_status'] == 'resolved') echo 'Resolved';
                                                        elseif ($event['new_status'] == 'rejected') echo 'Rejected';
                                                        else echo ucfirst($event['new_status']);
                                                    ?>
                                                    <span class="font-normal text-slate-400">by <?php echo htmlspecialchars($event['changed_by_name'] ?? 'System'); ?></span>
                                                </p>
                                                <p class="text-[10px] text-slate-400"><?php echo date('M j, Y g:i A', strtotime($event['changed_at'])); ?></p>
                                                <?php if (!empty($event['remark'])): ?>
                                                    <p class="text-xs text-slate-500 mt-0.5"><?php echo htmlspecialchars($event['remark']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right Column (4/12) -->
                        <div class="lg:col-span-4 space-y-6">

                            <!-- Reporter Information -->
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h2 class="text-sm font-bold text-slate-900 mb-4">Reporter Information</h2>
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">
                                        <?php 
                                            $initials = '';
                                            $name = $report['resident_name'] ?? '';
                                            $parts = explode(' ', $name);
                                            foreach ($parts as $part) {
                                                if (!empty($part)) $initials .= strtoupper($part[0]);
                                            }
                                            echo htmlspecialchars($initials ?: '?');
                                        ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($report['resident_name'] ?? 'N/A'); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <p class="flex items-center gap-2 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <?php echo htmlspecialchars($report['resident_phone'] ?? 'N/A'); ?>
                                    </p>
                                    <p class="flex items-center gap-2 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        <?php echo htmlspecialchars($report['resident_email'] ?? 'N/A'); ?>
                                    </p>
                                    <p class="flex items-center gap-2 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Total reports: <span class="font-bold text-slate-800"><?php echo $report['total_reports'] ?? 0; ?></span>
                                    </p>
                                </div>
                            </div>

                            <!-- Uploaded Evidence -->
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

                            <!-- Info Box (Read-only) -->
                            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4 text-center">
                                <p class="text-xs text-slate-500">This report is in <strong class="text-slate-700"><?php echo strtolower($badge['label']); ?></strong> status.</p>
                                <p class="text-[10px] text-slate-400 mt-1">View-only mode – no actions available</p>
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
        const lat = <?php echo $report['latitude']; ?>;
        const lng = <?php echo $report['longitude']; ?>;
        const map = L.map('reportLocationMap', {
            center: [lat, lng],
            zoom: 16,
            zoomControl: false,
            dragging: true,
            scrollWheelZoom: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OSM',
            className: 'map-tiles'
        }).addTo(map);

        const greenIcon = L.divIcon({
            html: `<div style="background-color: #10B981; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.4);"></div>`,
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });

        L.marker([lat, lng], { icon: greenIcon })
            .addTo(map)
            .bindPopup('<strong><?php echo htmlspecialchars($report['purok'] ?? 'Location'); ?></strong>');

        setTimeout(() => map.invalidateSize(), 200);
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>