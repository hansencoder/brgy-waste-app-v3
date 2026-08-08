<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$stats = $data['stats'] ?? ['total' => 0, 'Pending' => 0, 'Verified' => 0, 'Resolved' => 0, 'Rejected' => 0];
$resident_stats = $data['resident_stats'] ?? ['total' => 0, 'active' => 0, 'suspended' => 0, 'deactivated' => 0];
$mapped_reports = $data['mapped_reports'] ?? 0;
$active_hotspots = $data['active_hotspots'] ?? 0;
$highest_purok = $data['highest_purok'] ?? 'N/A';
$next_schedule = $data['next_schedule'] ?? null;
$latest_announce = $data['latest_announcement'] ?? null;
$active_announcements = $data['active_announcements'] ?? 0;
$recent_reports = $data['recent_reports'] ?? [];
$admin_name = $_SESSION['user_name'] ?? 'Admin';
$today_date = date('F d, Y');
$unread_count = $data['unread_count'] ?? 0;

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
?>
<style>
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    #mobileSidebarOverlay { opacity: 0; visibility: hidden; }
</style>

<div class="min-h-screen bg-[#F8FAFC]">
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 opacity-0 pointer-events-none lg:hidden"></div>
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <div class="mb-8">
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.35em] text-emerald-700">Administrator Portal</span>
                        <div class="mt-4 max-w-2xl">
                            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900">Good morning, <?php echo htmlspecialchars($admin_name); ?> 👋</h1>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">Barangay Dulong Bayan · <?php echo $today_date; ?></p>
                    </div>

                    <!-- Top Metrics Row -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
                        <!-- Waste Report Overview -->
                        <div class="rounded-xl bg-white border border-slate-200 p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Waste Report Overview</p>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-5 sm:gap-3">
                                <div class="rounded-xl bg-slate-50 p-4 text-center">
                                    <p class="text-sm text-slate-500">Total</p>
                                    <p class="mt-2 text-2xl font-black text-slate-900"><?php echo $stats['total']; ?></p>
                                </div>
                                <div class="rounded-xl bg-amber-50 p-4 text-center">
                                    <p class="text-sm text-amber-700">Pending</p>
                                    <p class="mt-2 text-2xl font-black text-amber-700"><?php echo $stats['Pending']; ?></p>
                                </div>
                                <div class="rounded-xl bg-emerald-50 p-4 text-center">
                                    <p class="text-sm text-emerald-700">Verified</p>
                                    <p class="mt-2 text-2xl font-black text-emerald-700"><?php echo $stats['Verified']; ?></p>
                                </div>
                                <div class="rounded-xl bg-cyan-50 p-4 text-center">
                                    <p class="text-sm text-cyan-700">Resolved</p>
                                    <p class="mt-2 text-2xl font-black text-cyan-700"><?php echo $stats['Resolved']; ?></p>
                                </div>
                                <div class="rounded-xl bg-red-50 p-4 text-center">
                                    <p class="text-sm text-red-700">Rejected</p>
                                    <p class="mt-2 text-2xl font-black text-red-700"><?php echo $stats['Rejected']; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Resident Accounts -->
                        <div class="rounded-xl bg-white border border-slate-200 p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Resident Accounts</p>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 sm:gap-3 text-center">
                                <div class="rounded-xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Total</p>
                                    <p class="mt-2 text-2xl font-black text-slate-900"><?php echo $resident_stats['total']; ?></p>
                                </div>
                                <div class="rounded-xl bg-emerald-50 p-4">
                                    <p class="text-sm text-emerald-700">Active</p>
                                    <p class="mt-2 text-2xl font-black text-emerald-700"><?php echo $resident_stats['active']; ?></p>
                                </div>
                                <div class="rounded-xl bg-amber-50 p-4">
                                    <p class="text-sm text-amber-700">Suspended</p>
                                    <p class="mt-2 text-2xl font-black text-amber-700"><?php echo $resident_stats['suspended']; ?></p>
                                </div>
                                <div class="rounded-xl bg-red-50 p-4">
                                    <p class="text-sm text-red-700">Disabled</p>
                                    <p class="mt-2 text-2xl font-black text-red-700"><?php echo $resident_stats['deactivated']; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- GIS Monitoring -->
                        <div class="rounded-xl bg-white border border-slate-200 p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg bg-orange-50 text-orange-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">GIS Monitoring</p>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                            <div class="space-y-3 text-sm text-slate-600">
                                <div class="flex items-center justify-between">
                                    <span>Mapped Reports</span>
                                    <span class="font-semibold text-slate-900"><?php echo $mapped_reports; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Active Hotspots</span>
                                    <span class="font-semibold text-red-600"><?php echo $active_hotspots; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Highest Concern</span>
                                    <span class="font-semibold text-slate-900"><?php echo htmlspecialchars($highest_purok); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Row -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
                        <!-- Collection Schedule -->
                        <div class="rounded-xl bg-white border border-slate-200 p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Collection Schedule</p>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                            <?php if ($next_schedule): ?>
                            <div class="rounded-xl bg-[#EBF7F2] p-5">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.35em] text-emerald-700">Next Collection</p>
                                <p class="mt-3 text-xl font-black text-slate-900"><?php echo htmlspecialchars($next_schedule['collection_day']); ?> · <?php echo date('g:i A', strtotime($next_schedule['start_time'])); ?></p>
                                <p class="mt-2 text-sm text-slate-700"><?php echo htmlspecialchars($next_schedule['puroks'] ?? 'All Puroks'); ?> — <?php echo htmlspecialchars($next_schedule['waste_type'] ?? 'General'); ?></p>
                            </div>
                            <?php else: ?>
                            <p class="text-sm text-slate-500">No active schedule available.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Announcements -->
                        <div class="rounded-xl bg-white border border-slate-200 p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg bg-violet-50 text-violet-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Announcements</p>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                            <?php if ($latest_announce): ?>
                            <div class="space-y-3">
                                <p class="text-base font-bold text-slate-900"><?php echo htmlspecialchars($latest_announce['title']); ?></p>
                                <p class="text-sm text-slate-500">Published · <?php echo date('M d, Y', strtotime($latest_announce['created_at'])); ?></p>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700">Published</span>
                                    <span class="text-[11px] text-slate-400"><?php echo $active_announcements; ?> active announcements</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <p class="text-sm text-slate-500">No announcements available.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Quick Access (Dark Green) matching screenshot design -->
                        <div class="rounded-2xl bg-[#062419] p-5 sm:p-6 shadow-lg text-white flex flex-col justify-between">
                            <p class="text-xs font-extrabold uppercase tracking-[0.25em] text-[#10B981] mb-4">QUICK ACCESS</p>
                            <div class="grid grid-cols-2 gap-2.5 sm:gap-3 flex-1">
                                <a href="/brgy-waste-app-v3/public/index.php?url=admin/reports" class="flex flex-col items-center justify-center rounded-xl bg-[#0B3326] p-3 sm:p-4 text-center transition hover:bg-[#0E3E2F] hover:scale-[1.02]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    <span class="mt-2 text-xs sm:text-sm font-bold text-white leading-tight">Review<br>Reports</span>
                                </a>
                                <a href="/brgy-waste-app-v3/public/index.php?url=admin/accounts" class="flex flex-col items-center justify-center rounded-xl bg-[#0B3326] p-3 sm:p-4 text-center transition hover:bg-[#0E3E2F] hover:scale-[1.02]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><circle cx="19" cy="11" r="2"/><path d="M19 8v1"/><path d="M19 13v1"/><path d="M16.5 9.5l.87.5"/><path d="M20.63 11.9l.87.5"/></svg>
                                    <span class="mt-2 text-xs sm:text-sm font-bold text-white leading-tight">Manage<br>Users</span>
                                </a>
                                <a href="/brgy-waste-app-v3/public/index.php?url=admin/gis" class="flex flex-col items-center justify-center rounded-xl bg-[#0B3326] p-3 sm:p-4 text-center transition hover:bg-[#0E3E2F] hover:scale-[1.02]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/><circle cx="12" cy="10" r="2"/></svg>
                                    <span class="mt-2 text-xs sm:text-sm font-bold text-white leading-tight">Open GIS<br>Map</span>
                                </a>
                                <a href="/brgy-waste-app-v3/public/index.php?url=admin/announcements" class="flex flex-col items-center justify-center rounded-xl bg-[#0B3326] p-3 sm:p-4 text-center transition hover:bg-[#0E3E2F] hover:scale-[1.02]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                    <span class="mt-2 text-xs sm:text-sm font-bold text-white leading-tight">Make<br>Announcement</span>
                                </a>
                                <a href="/brgy-waste-app-v3/public/index.php?url=admin/schedule" class="flex flex-col items-center justify-center rounded-xl bg-[#0B3326] p-3 sm:p-4 text-center transition hover:bg-[#0E3E2F] hover:scale-[1.02]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                                    <span class="mt-2 text-xs sm:text-sm font-bold text-white leading-tight">Update<br>Schedule</span>
                                </a>
                                <a href="/brgy-waste-app-v3/public/index.php?url=admin/report_summaries" class="flex flex-col items-center justify-center rounded-xl bg-[#0B3326] p-3 sm:p-4 text-center transition hover:bg-[#0E3E2F] hover:scale-[1.02]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4v16h16"/><path d="M9 9h9"/><path d="M9 13h6"/><path d="M9 17h11"/></svg>
                                    <span class="mt-2 text-xs sm:text-sm font-bold text-white leading-tight">View<br>Analytics</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reports Table -->
                    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                        <div class="flex flex-col gap-4 px-6 py-5 border-b border-slate-200 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Recent Reports</h2>
                            </div>
                            <a href="/brgy-waste-app-v3/public/index.php?url=admin/reports" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">View All →</a>
                        </div>

                        <div class="overflow-x-auto hidden md:block">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Report ID</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Resident</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Category</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Purok</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Date</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Status</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php foreach ($recent_reports as $report):
                                        $badge = getStatusBadge($report['status']);
                                        $reportId = 'WR-' . str_pad($report['id'], 7, '0', STR_PAD_LEFT);
                                    ?>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4 font-mono text-slate-900"><?php echo htmlspecialchars($reportId); ?></td>
                                        <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars($report['resident_name'] ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($report['category'] ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-slate-500"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;"><?php echo $badge['label']; ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="/brgy-waste-app-v3/public/index.php?url=admin/reports" class="text-emerald-600 font-semibold hover:text-emerald-700">Review</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($recent_reports)): ?>
                                    <tr><td colspan="7" class="px-6 py-6 text-center text-slate-500">No recent reports.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="md:hidden px-4 py-4 space-y-4">
                            <?php foreach ($recent_reports as $report):
                                $badge = getStatusBadge($report['status']);
                                $reportId = 'WR-' . str_pad($report['id'], 7, '0', STR_PAD_LEFT);
                            ?>
                            <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-400"><?php echo htmlspecialchars($reportId); ?></p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($report['category'] ?? 'N/A'); ?></p>
                                        <p class="mt-1 text-xs text-slate-500"><?php echo htmlspecialchars($report['resident_name'] ?? 'N/A'); ?> · <?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></p>
                                        <p class="text-xs text-slate-400"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></p>
                                    </div>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;"><?php echo $badge['label']; ?></span>
                                </div>
                                <a href="/brgy-waste-app-v3/public/index.php?url=admin/reports" class="mt-4 inline-flex items-center text-sm font-semibold text-emerald-600 hover:text-emerald-700">Review →</a>
                            </div>
                            <?php endforeach; ?>
                            <?php if (empty($recent_reports)): ?>
                            <p class="text-center text-slate-500 py-4">No recent reports.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobileMenuButton');
        const overlay = document.getElementById('mobileSidebarOverlay');
        if (menuButton && overlay) {
            menuButton.addEventListener('click', function() {
                document.body.classList.toggle('mobile-sidebar-open');
            });
            overlay.addEventListener('click', function() {
                document.body.classList.remove('mobile-sidebar-open');
            });
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>