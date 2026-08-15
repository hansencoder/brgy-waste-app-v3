<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
$stats = $data['stats'] ?? ['total' => 0, 'Pending' => 0, 'Verified' => 0, 'In Progress' => 0, 'Resolved' => 0, 'Rejected' => 0];
$resident_stats = $data['resident_stats'] ?? ['total' => 0, 'active' => 0, 'suspended' => 0, 'deactivated' => 0];
$mapped_reports = $data['mapped_reports'] ?? 0;
$active_hotspots = $data['active_hotspots'] ?? 0;
$highest_purok = $data['highest_purok'] ?? 'N/A';
$next_schedule = $data['next_schedule'] ?? null;
$latest_announce = $data['latest_announcement'] ?? null;
$active_announcements = $data['active_announcements'] ?? 0;
$recent_reports = $data['recent_reports'] ?? [];
$recent_activity = $data['recent_activity'] ?? [];
$today_count = $data['today_count'] ?? 0;
$purok_chart_data = $data['purok_chart_data'] ?? [];
$category_chart_data = $data['category_chart_data'] ?? [];
$monthly_trend_data = $data['monthly_trend_data'] ?? [];

$admin_name = $_SESSION['user_name'] ?? 'Admin';
$today_date = date('F d, Y');

function getStatusBadgeProps($status) {
    $map = [
        'Pending'     => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
        'Verified'    => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'Verified'],
        'In Progress' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'In Progress'],
        'Resolved'    => ['bg' => 'bg-teal-50 text-teal-800 border-teal-200', 'dot' => 'bg-teal-500', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => 'bg-red-50 text-red-800 border-red-200', 'dot' => 'bg-red-500', 'label' => 'Rejected'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-50 text-slate-700 border-slate-250', 'dot' => 'bg-slate-400', 'label' => $status];
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
</style>

<div class="min-h-screen bg-white text-slate-800 w-full flex font-sans antialiased">
    
    <!-- Overlay for Mobile Sidebar -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <!-- Layout Wrapper -->
    <div class="lg:flex lg:min-h-screen w-full">
        
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <!-- Main Scrollable Dashboard Canvas -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- ============================================================ -->
                    <!-- 1. DASHBOARD HEADER & SYSTEM STATUS BANNER                   -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-250 shadow-xs">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Welcome back, <?php echo htmlspecialchars($firstName ?? $admin_name); ?> 👋
                            </h1>
                            <p class="text-sm text-slate-500 font-medium mt-0.5">
                                Here is the real-time summary of waste management operations for today, <?php echo $today_date; ?>.
                            </p>
                        </div>

                        <!-- Date & Quick Stats Badge -->
                        <div class="flex items-center gap-3 self-start md:self-auto">
                            <div class="px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-250 text-right">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Submissions Today</p>
                                <p class="text-lg font-extrabold text-emerald-600 font-mono">+<?php echo $today_count; ?> new</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hotspot Alert Notification Banner (If active hotspots exist) -->
                    <?php if ($active_hotspots > 0): ?>
                    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm font-medium text-amber-900 flex items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-3">
                            <span class="p-2 rounded-lg bg-amber-100 text-amber-700 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </span>
                            <div>
                                <span class="font-bold text-amber-900">GIS Alert:</span> 
                                <?php echo $active_hotspots; ?> Purok hotspot(s) detected with high report density (Top: <strong class="underline"><?php echo htmlspecialchars($highest_purok); ?></strong>).
                            </div>
                        </div>
                        <a href="/brgy-waste-app-v3/public/admin/gis" class="px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shrink-0 transition">
                            Open GIS Map →
                        </a>
                    </div>
                    <?php endif; ?>

                    <!-- ============================================================ -->
                    <!-- 2. KPI METRICS ROW (Responsive 1 -> 2 -> 4 Columns)          -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Card 1: Total Reports -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-250 shadow-20px hover:border-slate-400 transition">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Reports</span>
                                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-3xl font-extrabold text-slate-900 font-mono"><?php echo number_format($stats['total']); ?></span>
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">+<?php echo $today_count; ?> today</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">All time reported waste issues</p>
                        </div>

                        <!-- Card 2: Pending Verification -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-250 shadow-xs hover:border-slate-400 transition">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Review</span>
                                <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-3xl font-extrabold text-amber-600 font-mono"><?php echo number_format($stats['Pending']); ?></span>
                                <?php if ($stats['Pending'] > 0): ?>
                                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">Needs Action</span>
                                <?php else: ?>
                                    <span class="text-xs font-semibold text-slate-400">All Clear</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Awaiting admin verification</p>
                        </div>

                        <!-- Card 3: Active / In Progress -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-250 shadow-xs hover:border-slate-400 transition">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active / Actioned</span>
                                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-3xl font-extrabold text-blue-600 font-mono"><?php echo number_format(($stats['Verified'] ?? 0) + ($stats['In Progress'] ?? 0)); ?></span>
                                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200"><?php echo $stats['Verified']; ?> Verified</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Currently being processed</p>
                        </div>

                        <!-- Card 4: Resolved Reports -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-250 shadow-xs hover:border-slate-400 transition">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Resolved Rate</span>
                                <span class="p-2 rounded-xl bg-teal-50 text-teal-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                            </div>
                            <?php 
                            $resolvedPct = $stats['total'] > 0 ? round(($stats['Resolved'] / $stats['total']) * 100) : 0;
                            ?>
                            <div class="flex items-baseline justify-between">
                                <span class="text-3xl font-extrabold text-teal-700 font-mono"><?php echo number_format($stats['Resolved']); ?></span>
                                <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200"><?php echo $resolvedPct; ?>% Rate</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Successfully collected &amp; cleared</p>
                        </div>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 3. TRENDS & PUROK DISTRIBUTION CHARTS SECTION                -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Left Panel: Submission Volume Trends Chart (2 Cols) -->
                        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-250 p-6 shadow-xs flex flex-col justify-between">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                                        Waste Report Submission Trends
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Monthly report volume over recent periods</p>
                                </div>
                                <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-bold text-slate-600 self-start sm:self-auto">
                                    <button class="px-3 py-1 bg-white text-slate-800 rounded-lg shadow-xs">6 Months</button>
                                    <button class="px-3 py-1 hover:text-slate-900 transition">Yearly</button>
                                </div>
                            </div>
                            <div class="h-64 sm:h-72 w-full relative">
                                <canvas id="trendsChart"></canvas>
                            </div>
                        </div>

                        <!-- Right Panel: Purok Distribution Breakdown (1 Col) -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Purok Distribution
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Reports per barangay zone</p>
                                </div>
                                <span class="text-xs font-bold text-slate-500 font-mono"><?php echo count($purok_chart_data); ?> Zones</span>
                            </div>

                            <!-- Doughnut Chart Container -->
                            <div class="h-44 w-full relative flex items-center justify-center my-2">
                                <canvas id="purokChart"></canvas>
                            </div>

                            <!-- List of Top Puroks -->
                            <div class="space-y-2 pt-3 border-t border-slate-100">
                                <?php 
                                $topPuroks = array_slice($purok_chart_data, 0, 4);
                                foreach ($topPuroks as $p): 
                                ?>
                                <div class="flex items-center justify-between text-xs font-medium">
                                    <span class="text-slate-700 font-semibold"><?php echo htmlspecialchars($p['purok_name']); ?></span>
                                    <span class="font-bold font-mono bg-slate-100 text-slate-800 px-2 py-0.5 rounded-full"><?php echo $p['count']; ?> reports</span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 4. SYSTEM OPERATIONAL READINESS WIDGETS (3 Columns)          -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Widget 1: Next Waste Collection Schedule -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="p-2 rounded-xl bg-sky-50 text-sky-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Next Collection</h3>
                                        <p class="text-[11px] text-slate-400 font-medium">Scheduled Route</p>
                                    </div>
                                </div>
                                <a href="/brgy-waste-app-v3/public/admin/schedule" class="text-xs font-bold text-sky-600 hover:text-sky-700">Manage →</a>
                            </div>

                            <?php if ($next_schedule): ?>
                            <div class="p-4 rounded-xl bg-sky-50/70 border border-sky-100 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold uppercase tracking-wider text-sky-800"><?php echo htmlspecialchars($next_schedule['collection_day']); ?></span>
                                    <span class="text-xs font-bold font-mono text-sky-700 bg-sky-100 px-2 py-0.5 rounded-md"><?php echo date('g:i A', strtotime($next_schedule['start_time'])); ?></span>
                                </div>
                                <p class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($next_schedule['puroks'] ?? 'All Puroks'); ?></p>
                                <p class="text-xs text-slate-600">Waste Type: <strong><?php echo htmlspecialchars($next_schedule['waste_type'] ?? 'General Waste'); ?></strong></p>
                            </div>
                            <?php else: ?>
                            <p class="text-xs text-slate-500 py-4 text-center">No active schedule configured.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Widget 2: Resident & User Portal Summary -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="p-2 rounded-xl bg-purple-50 text-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Resident Portal</h3>
                                        <p class="text-[11px] text-slate-400 font-medium">User Accounts</p>
                                    </div>
                                </div>
                                <a href="/brgy-waste-app-v3/public/admin/accounts" class="text-xs font-bold text-purple-600 hover:text-purple-700">Accounts →</a>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-center">
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Users</p>
                                    <p class="text-xl font-extrabold text-slate-900 font-mono mt-1"><?php echo number_format($resident_stats['total']); ?></p>
                                </div>
                                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Active</p>
                                    <p class="text-xl font-extrabold text-emerald-700 font-mono mt-1"><?php echo number_format($resident_stats['active']); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Widget 3: Active Announcements & GIS Coverage -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Announcements</h3>
                                        <p class="text-[11px] text-slate-400 font-medium">Public Bulletins</p>
                                    </div>
                                </div>
                                <a href="/brgy-waste-app-v3/public/admin/announcements" class="text-xs font-bold text-amber-600 hover:text-amber-700">Announce →</a>
                            </div>

                            <?php if ($latest_announce): ?>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                                <p class="text-xs font-bold text-slate-900 truncate"><?php echo htmlspecialchars($latest_announce['title']); ?></p>
                                <p class="text-[11px] text-slate-500">Published · <?php echo date('M d, Y', strtotime($latest_announce['created_at'])); ?></p>
                            </div>
                            <?php else: ?>
                            <p class="text-xs text-slate-500 py-3 text-center">No announcements published yet.</p>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 5. RECENT REPORTS TABLE & LIVE ACTIVITY FEED                -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Left Column: Recent Reports Table (2 Cols) -->
                        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-250 shadow-xs overflow-hidden flex flex-col justify-between">
                            <div>
                                <!-- Header & Controls -->
                                <div class="p-6 border-b border-slate-250 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <h2 class="text-base font-bold text-slate-900">Recent Waste Reports</h2>
                                        <p class="text-xs text-slate-500">Latest resident &amp; guest report submissions</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="/brgy-waste-app-v3/public/admin/reports" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">View All Reports →</a>
                                    </div>
                                </div>

                                <!-- Desktop Table -->
                                <div class="overflow-x-auto hidden md:block">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                                <th class="py-3.5 px-6">Tracking ID</th>
                                                <th class="py-3.5 px-6">Reporter</th>
                                                <th class="py-3.5 px-6">Category</th>
                                                <th class="py-3.5 px-6">Purok</th>
                                                <th class="py-3.5 px-6">Date</th>
                                                <th class="py-3.5 px-6">Status</th>
                                                <th class="py-3.5 px-6 text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-xs">
                                            <?php foreach ($recent_reports as $report): 
                                                $badge = getStatusBadgeProps($report['status']);
                                                $reportId = 'WR-' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);
                                                $isGuest = isset($report['reporter_type']) && $report['reporter_type'] === 'guest';
                                            ?>
                                            <tr class="hover:bg-slate-50/60 transition">
                                                <td class="py-4 px-6 font-mono font-bold text-slate-900"><?php echo htmlspecialchars($reportId); ?></td>
                                                <td class="py-4 px-6">
                                                    <div class="font-bold text-slate-800"><?php echo htmlspecialchars($report['resident_name'] ?? 'Guest'); ?></div>
                                                    <?php if ($isGuest): ?>
                                                        <span class="inline-block mt-0.5 text-[9px] font-extrabold uppercase px-1.5 py-0.2 rounded bg-purple-50 text-purple-700 border border-purple-200">Guest</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-4 px-6 text-slate-600 font-medium"><?php echo htmlspecialchars($report['category'] ?? 'General'); ?></td>
                                                <td class="py-4 px-6 text-slate-600 font-medium"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></td>
                                                <td class="py-4 px-6 text-slate-500 font-mono"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></td>
                                                <td class="py-4 px-6">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold border <?php echo $badge['bg']; ?>">
                                                        <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                                        <?php echo $badge['label']; ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6 text-right">
                                                    <a href="/brgy-waste-app-v3/public/admin/viewReport/<?php echo $report['id']; ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                        Review
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($recent_reports)): ?>
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-slate-400 font-medium">No waste reports recorded yet.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mobile Card Stack View -->
                                <div class="md:hidden divide-y divide-slate-100">
                                    <?php foreach ($recent_reports as $report): 
                                        $badge = getStatusBadgeProps($report['status']);
                                        $reportId = 'WR-' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);
                                        $isGuest = isset($report['reporter_type']) && $report['reporter_type'] === 'guest';
                                    ?>
                                    <div class="p-4 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-mono font-bold text-xs text-slate-900"><?php echo htmlspecialchars($reportId); ?></span>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border <?php echo $badge['bg']; ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                                <?php echo $badge['label']; ?>
                                            </span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <div>
                                                <p class="font-bold text-slate-800"><?php echo htmlspecialchars($report['category'] ?? 'General'); ?></p>
                                                <p class="text-slate-500"><?php echo htmlspecialchars($report['resident_name'] ?? 'Guest'); ?> · <?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></p>
                                            </div>
                                            <a href="/brgy-waste-app-v3/public/admin/viewReport/<?php echo $report['id']; ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200 hover:bg-emerald-100 transition self-center">Review →</a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: System Audit Log & Activity Feed (1 Col) -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            Recent Activity
                                        </h2>
                                        <p class="text-xs text-slate-500">Live admin &amp; system audit log</p>
                                    </div>
                                    <a href="/brgy-waste-app-v3/public/admin/audit_logs" class="text-xs font-bold text-slate-500 hover:text-slate-800">All Logs →</a>
                                </div>

                                <div class="space-y-4 my-3 relative pl-3 border-l-2 border-slate-100">
                                    <?php foreach ($recent_activity as $act): ?>
                                    <div class="relative pl-3">
                                        <span class="absolute -left-[19px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-white"></span>
                                        <p class="text-xs font-bold text-slate-800"><?php echo htmlspecialchars($act['action']); ?></p>
                                        <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5"><?php echo htmlspecialchars($act['details']); ?></p>
                                        <p class="text-[10px] text-slate-400 font-mono mt-1"><?php echo date('M d, g:i A', strtotime($act['created_at'])); ?> · <?php echo htmlspecialchars($act['user_name'] ?? 'System'); ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php if (empty($recent_activity)): ?>
                                    <p class="text-xs text-slate-400 italic text-center py-4">No recent activity logged.</p>
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

<!-- ============================================================ -->
<!-- CHARTS & INTERACTIVE DASHBOARD SCRIPT                         -->
<!-- ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Mobile Sidebar Toggle
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

    // 2. Render Trends Line Chart
    const monthlyTrendData = <?php echo json_encode($monthly_trend_data); ?>;
    const trendLabels = monthlyTrendData.map(item => item.period);
    const trendCounts = monthlyTrendData.map(item => parseInt(item.count));

    const ctxTrends = document.getElementById('trendsChart');
    if (ctxTrends) {
        new Chart(ctxTrends, {
            type: 'line',
            data: {
                labels: trendLabels.length ? trendLabels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Report Submissions',
                    data: trendCounts.length ? trendCounts : [12, 19, 15, 25, 22, 30],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#0B2E22',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Miranda Sans', size: 11 }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { font: { family: 'Miranda Sans', size: 11 }, color: '#64748B', stepSize: 5 }
                    }
                }
            }
        });
    }

    // 3. Render Purok Distribution Doughnut Chart
    const purokChartData = <?php echo json_encode($purok_chart_data); ?>;
    const purokLabels = purokChartData.map(item => item.purok_name);
    const purokCounts = purokChartData.map(item => parseInt(item.count));
    const colors = ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#64748B'];

    const ctxPurok = document.getElementById('purokChart');
    if (ctxPurok) {
        new Chart(ctxPurok, {
            type: 'doughnut',
            data: {
                labels: purokLabels.length ? purokLabels : ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4'],
                datasets: [{
                    data: purokCounts.length ? purokCounts : [10, 15, 8, 12],
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>