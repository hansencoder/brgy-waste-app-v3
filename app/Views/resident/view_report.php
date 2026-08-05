<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$data = $data ?? [];
$report = $data['report'] ?? [];
$timeline = $data['timeline'] ?? [];

if (empty($report)) {
    echo '<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
        <div class="max-w-md w-full rounded-[28px] border border-slate-200 bg-white p-8 text-center shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)]">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h1 class="text-2xl font-black text-slate-900">Report not found</h1>
            <p class="mt-3 text-sm text-slate-600">The report you are trying to view is missing or unavailable.</p>
            <a href="/brgy-waste-app-v3/public/resident/my_report" class="mt-6 inline-flex items-center justify-center rounded-full bg-[#0D9488] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f766e]">Back to Reports</a>
        </div>
    </div>';
    include __DIR__ . '/../layouts/footer.php';
    exit;
}

$statusColors = [
    'pending' => ['bg' => 'amber-50', 'text' => 'amber-700', 'dot' => 'amber-500', 'label' => 'Pending'],
    'verified' => ['bg' => 'sky-50', 'text' => 'sky-700', 'dot' => 'sky-500', 'label' => 'Verified'],
    'resolved' => ['bg' => 'emerald-50', 'text' => 'emerald-700', 'dot' => 'emerald-500', 'label' => 'Resolved'],
    'rejected' => ['bg' => 'red-50', 'text' => 'red-700', 'dot' => 'red-500', 'label' => 'Rejected'],
];
$color = $statusColors[strtolower($report['status'] ?? 'pending')] ?? $statusColors['pending'];
$imgPath = !empty($report['photo_path']) ? '/brgy-waste-app-v3/public/uploads/' . $report['photo_path'] : 'https://placehold.co/800x400?text=No+Image';

$events = [];
$events[] = [
    'status' => 'pending',
    'title' => 'Report submitted',
    'date' => date('M j, Y, h:i A', strtotime($report['submission_date'])),
    'color' => $statusColors['pending']
];

if (!empty($timeline)) {
    foreach ($timeline as $t) {
        $newStatus = strtolower($t['new_status'] ?? 'pending');

        if ($newStatus === 'verified') {
            $title = 'Report verified by ' . ($t['changed_by_name'] ?? 'secretary');
        } elseif ($newStatus === 'resolved') {
            $title = 'Cleanup completed';
        } elseif ($newStatus === 'rejected') {
            $title = 'Report rejected';
        } else {
            $title = 'Status updated to ' . ucfirst($newStatus);
        }

        $tColor = $statusColors[$newStatus] ?? $statusColors['pending'];
        $event = [
            'status' => $newStatus,
            'title' => $title,
            'date' => date('M j, Y, h:i A', strtotime($t['changed_at'])),
            'color' => $tColor,
        ];

        if ($newStatus === 'rejected' && !empty($t['remark'])) {
            $event['reason'] = $t['remark'];
        }

        $events[] = $event;
    }
} else {
    if (in_array(strtolower($report['status'] ?? ''), ['verified', 'resolved', 'rejected'], true)) {
        $currentStatus = strtolower($report['status']);

        if ($currentStatus === 'verified') {
            $events[] = [
                'status' => 'verified',
                'title' => 'Report verified by secretary',
                'date' => date('M j, Y, h:i A', strtotime($report['updated_at'] ?? $report['submission_date'])),
                'color' => $statusColors['verified']
            ];
        }

        if ($currentStatus === 'resolved') {
            $events[] = [
                'status' => 'resolved',
                'title' => 'Cleanup completed',
                'date' => date('M j, Y, h:i A', strtotime($report['updated_at'] ?? $report['submission_date'])),
                'color' => $statusColors['resolved']
            ];
        }

        if ($currentStatus === 'rejected') {
            $event = [
                'status' => 'rejected',
                'title' => 'Report rejected',
                'date' => date('M j, Y, h:i A', isset($data['flag_date']) ? strtotime($data['flag_date']) : strtotime($report['updated_at'] ?? $report['submission_date'])),
                'color' => $statusColors['rejected'],
            ];
            if (!empty($data['flag_reason'])) {
                $event['reason'] = $data['flag_reason'];
            }
            $events[] = $event;
        }
    }
}
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800">
    <div class="lg:flex">
        <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8 lg:py-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-[#0D9488]">Resident Portal</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Report Details</h1>
                        <p class="mt-1 text-sm text-slate-500">Track the status and location of this submitted waste report.</p>
                    </div>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                        Back to Reports
                    </a>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <div class="mb-6 flex flex-col gap-4 rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)] sm:p-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#E6F4EA] text-[#0D9488]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-slate-400">Report ID</p>
                            <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">RPT-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></h2>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full bg-<?php echo $color['bg']; ?> px-3 py-2 text-xs font-bold uppercase tracking-[0.18em] text-<?php echo $color['text']; ?>">
                            <span class="h-2.5 w-2.5 rounded-full bg-<?php echo $color['dot']; ?>"></span>
                            <?php echo ucfirst($color['label']); ?>
                        </span>

                        <?php if (strtolower($report['status'] ?? 'pending') !== 'rejected'): ?>
                            <button type="button" onclick="showDeleteConfirm()" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3.5 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                Delete
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="deleteModal" class="fixed inset-0 z-[90] hidden">
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteConfirm()"></div>
                    <div class="absolute inset-0 flex items-center justify-center p-4">
                        <div class="w-full max-w-md rounded-[28px] border border-slate-200 bg-white p-6 shadow-2xl">
                            <div class="mb-5 flex items-start justify-between">
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-red-500">Warning</p>
                                    <h3 class="mt-2 text-xl font-black text-slate-900">Delete this report?</h3>
                                </div>
                                <button type="button" onclick="hideDeleteConfirm()" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>

                            <p class="text-sm text-slate-600">This action cannot be undone. The report <span class="font-bold text-slate-800">RPT-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></span> will be permanently removed.</p>

                            <div class="mt-6 flex gap-3">
                                <button type="button" onclick="hideDeleteConfirm()" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                                <form action="/brgy-waste-app-v3/public/resident/delete_report/<?php echo $report['id']; ?>" method="POST" class="flex-1">
                                    <button type="submit" class="w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Delete Report</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                    <div class="space-y-6">
                        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)]">
                            <div class="aspect-[16/9] w-full overflow-hidden bg-slate-100">
                                <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Waste Report Photo" class="h-full w-full object-cover" />
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)]">
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.28em] text-slate-400">Description</h3>
                            <p class="mt-3 text-base leading-relaxed text-slate-700"><?php echo nl2br(htmlspecialchars($report['description'])); ?></p>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)]">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-[11px] font-bold uppercase tracking-[0.28em] text-slate-400">Status Timeline</h3>
                                    <p class="mt-2 text-sm text-slate-500">Latest updates and resolution history for this report.</p>
                                </div>
                            </div>

                            <div class="relative mt-6 pl-3">
                                <div class="absolute left-[10px] top-1 h-[calc(100%-10px)] w-px bg-slate-200"></div>
                                <div class="space-y-5">
                                    <?php foreach ($events as $event): ?>
                                        <div class="relative flex gap-4">
                                            <div class="relative z-10 flex h-5 w-5 items-center justify-center rounded-full bg-white ring-4 ring-white">
                                                <span class="h-2.5 w-2.5 rounded-full bg-<?php echo $event['color']['dot']; ?>"></span>
                                            </div>
                                            <div class="flex-1 pb-2">
                                                <span class="inline-flex rounded-full bg-<?php echo $event['color']['bg']; ?> px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-<?php echo $event['color']['text']; ?>"><?php echo htmlspecialchars($event['status']); ?></span>
                                                <h4 class="mt-2 text-sm font-bold text-slate-900"><?php echo htmlspecialchars($event['title']); ?></h4>
                                                <p class="mt-1 text-xs text-slate-400"><?php echo htmlspecialchars($event['date']); ?></p>
                                                <?php if (!empty($event['reason'])): ?>
                                                    <div class="mt-3 rounded-2xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                                                        <p class="mb-1 text-[10px] font-bold uppercase tracking-[0.2em] text-red-500">Rejection reason</p>
                                                        <?php echo htmlspecialchars($event['reason']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="space-y-6">
                        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)]">
                            <div class="border-b border-slate-200 bg-[#E6F4EA] px-5 py-4">
                                <h3 class="text-lg font-black text-slate-900">Location Summary</h3>
                            </div>
                            <div id="reportMap" class="h-[260px] w-full bg-slate-100"></div>
                            <div class="space-y-4 p-5">
                                <div class="flex items-start gap-3 rounded-2xl bg-slate-50 p-3">
                                    <div class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-[#E6F4EA] text-[#0D9488]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Location</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-700" id="locationName"><?php echo htmlspecialchars($report['location_name'] ?? 'Unknown location'); ?></p>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">Coordinates</p>
                                        <button type="button" onclick="copyCoords()" id="copyBtn" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 transition hover:bg-slate-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                            Copy
                                        </button>
                                    </div>
                                    <p id="coordsText" class="mt-3 font-mono text-sm font-semibold text-slate-700"><?php echo htmlspecialchars($report['latitude'] . ', ' . $report['longitude']); ?></p>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)]">
                            <h3 class="text-[11px] font-bold uppercase tracking-[0.28em] text-slate-400">Report Summary</h3>
                            <dl class="mt-4 space-y-4 text-sm">
                                <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                                    <dt class="text-slate-500">Category</dt>
                                    <dd class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                                    <dt class="text-slate-500">Estimated Quantity</dt>
                                    <dd class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                                    <dt class="text-slate-500">Waste Condition</dt>
                                    <dd class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['waste_condition'] ?? 'N/A'); ?></dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Submission Date</dt>
                                    <dd class="font-semibold text-slate-800"><?php echo date('M j, Y', strtotime($report['submission_date'])); ?></dd>
                                </div>
                            </dl>
                        </section>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof L === 'undefined') return;

        const lat = <?php echo htmlspecialchars($report['latitude']); ?>;
        const lng = <?php echo htmlspecialchars($report['longitude']); ?>;
        const map = L.map('reportMap', {
            center: [lat, lng],
            zoom: 16,
            zoomControl: false,
            dragging: true,
            scrollWheelZoom: true,
            attributionControl: true,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);

        const pinColor = '<?php echo $color['dot']; ?>' === 'amber-500' ? '#f59e0b' : '<?php echo $color['dot']; ?>' === 'sky-500' ? '#3b82f6' : '<?php echo $color['dot']; ?>' === 'red-500' ? '#ef4444' : '#10b981';
        const customIcon = L.divIcon({
            html: '<div style="background-color:' + pinColor + '; width:16px; height:16px; border-radius:50%; border:3px solid white; box-shadow:0 4px 12px rgba(0,0,0,0.25);"></div>',
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8],
        });

        L.marker([lat, lng], { icon: customIcon }).addTo(map);
        map.panTo([lat, lng]);
    });

    function copyCoords() {
        const coords = document.getElementById('coordsText').innerText;
        navigator.clipboard.writeText(coords).then(() => {
            const btn = document.getElementById('copyBtn');
            const original = btn.innerHTML;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Copied';
            btn.classList.add('bg-[#E6F4EA]', 'text-[#0D9488]', 'border-[#A7F3D0]');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.remove('bg-[#E6F4EA]', 'text-[#0D9488]', 'border-[#A7F3D0]');
            }, 1500);
        });
    }

    function showDeleteConfirm() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideDeleteConfirm() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
