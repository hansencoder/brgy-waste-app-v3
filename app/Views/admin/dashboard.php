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

$admin_name = $_SESSION['user_name'] ?? 'Secretary';
$today_date = date('l, F d, Y');

// Calculate active/actioned (Verified + In Progress)
$activeActioned = ($stats['Verified'] ?? 0) + ($stats['In Progress'] ?? 0);
$resolvedPct = ($stats['total'] > 0) ? round((($stats['Resolved'] ?? 0) / $stats['total']) * 100) : 0;

function getStatusBadgeProps($status) {
    $map = [
        'Pending'     => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
        'Verified'    => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'dot' => 'bg-blue-500', 'label' => 'Verified'],
        'In Progress' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200', 'dot' => 'bg-purple-500', 'label' => 'In Progress'],
        'Resolved'    => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Rejected'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400', 'label' => $status];
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    
    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
    }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 w-full flex font-sans antialiased">
    
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
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- ============================================================ -->
                    <!-- 1. DASHBOARD WELCOME & COMMAND BANNER                        -->
                    <!-- ============================================================ -->
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#06241a] via-[#0b3b2c] to-[#041d15] p-6 sm:p-8 text-white border border-emerald-800/40 shadow-sm">
                        <!-- Ambient background pattern glow -->
                        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            <!-- Left: Welcome text & operational meta -->
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 backdrop-blur-xs">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Live Operations Command
                                    </span>
                                    <span class="text-xs text-emerald-200/70 font-medium">
                                        <?php echo $today_date; ?>
                                    </span>
                                </div>
                                
                                <div>
                                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                                        Welcome back, <?php echo htmlspecialchars($firstName ?? $admin_name); ?>
                                    </h1>
                                    <p class="text-xs sm:text-sm text-emerald-100/80 font-normal mt-1 max-w-2xl leading-relaxed">
                                        Here is the real-time summary of waste management operations, GIS reports, and community dispatch status.
                                    </p>
                                </div>
                            </div>

                            <!-- Right: Quick Stat Pill & Fast Action Shortcuts -->
                            <div class="flex flex-row lg:flex-col sm:flex-row items-stretch sm:items-center lg:items-end gap-3 flex-shrink-0">
                                <!-- Submissions Today Badge -->
                                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl px-5 py-3 text-center sm:text-right shadow-xs">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-200/80 block">Submissions Today</span>
                                    <div class="flex items-center justify-center sm:justify-end gap-1.5 mt-0.5">
                                        <span class="text-2xl font-extrabold font-mono text-emerald-300">+<?php echo $today_count; ?></span>
                                        <span class="text-xs font-medium text-emerald-100/90">new</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo app_url('admin/gis'); ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-bold transition shadow-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                        <span>GIS Map</span>
                                    </a>
                                    <a href="<?php echo app_url('admin/reports'); ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-white text-xs font-bold backdrop-blur-xs transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        <span>Reports</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hotspot Alert Notification Banner (If active hotspots exist) -->
                    <?php if ($active_hotspots > 0): ?>
                    <div class="rounded-2xl bg-amber-50/90 border border-amber-200/90 p-4 text-xs sm:text-sm font-medium text-amber-950 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-2xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </span>
                            <div>
                                <span class="font-bold text-amber-950">GIS Hotspot Warning:</span> 
                                <span><?php echo $active_hotspots; ?> Purok sector(s) detected with high report density (Highest Concern: <strong class="underline font-bold"><?php echo htmlspecialchars($highest_purok); ?></strong>).</span>
                            </div>
                        </div>
                        <a href="<?php echo app_url('admin/gis'); ?>" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shrink-0 transition self-start sm:self-auto shadow-2xs">
                            <span>Open GIS Map</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                    <?php endif; ?>

                    <!-- ============================================================ -->
                    <!-- 2. CORE KPI METRICS ROW (4 Cards Grid)                       -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        
                        <!-- KPI Card 1: Total Reports -->
                        <div class="group relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs card-hover flex flex-col justify-between overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Submissions</span>
                                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100 group-hover:scale-105 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex items-baseline justify-between gap-2">
                                        <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-mono tracking-tight"><?php echo number_format($stats['total']); ?></span>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                            +<?php echo $today_count; ?> today
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 font-normal mt-1.5">All-time logged waste issues</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span>Coverage Area</span>
                                <span class="font-semibold text-slate-700"><?php echo count($purok_chart_data); ?> Puroks</span>
                            </div>
                        </div>

                        <!-- KPI Card 2: Pending Review -->
                        <div class="group relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs card-hover flex flex-col justify-between overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-400"></div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pending Review</span>
                                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100 group-hover:scale-105 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex items-baseline justify-between gap-2">
                                        <span class="text-3xl sm:text-4xl font-extrabold text-amber-600 font-mono tracking-tight"><?php echo number_format($stats['Pending']); ?></span>
                                        <?php if ($stats['Pending'] > 0): ?>
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Needs Action
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs font-semibold text-slate-400">Queue Empty</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs text-slate-500 font-normal mt-1.5">Awaiting initial verification</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span>Verification Status</span>
                                <a href="<?php echo app_url('admin/reports'); ?>" class="font-bold text-amber-700 hover:text-amber-800">Review Queue →</a>
                            </div>
                        </div>

                        <!-- KPI Card 3: Active / Actioned -->
                        <div class="group relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs card-hover flex flex-col justify-between overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Active / Actioned</span>
                                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-100 group-hover:scale-105 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex items-baseline justify-between gap-2">
                                        <span class="text-3xl sm:text-4xl font-extrabold text-blue-600 font-mono tracking-tight"><?php echo number_format($activeActioned); ?></span>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">
                                            <?php echo (int)($stats['Verified'] ?? 0); ?> Verified
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 font-normal mt-1.5">Assigned to collection teams</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span>Operations</span>
                                <span class="font-semibold text-blue-700"><?php echo (int)($stats['In Progress'] ?? 0); ?> In Progress</span>
                            </div>
                        </div>

                        <!-- KPI Card 4: Resolved Rate -->
                        <div class="group relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs card-hover flex flex-col justify-between overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 to-emerald-400"></div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Resolved Rate</span>
                                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center border border-teal-100 group-hover:scale-105 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex items-baseline justify-between gap-2">
                                        <span class="text-3xl sm:text-4xl font-extrabold text-teal-800 font-mono tracking-tight"><?php echo number_format($stats['Resolved'] ?? 0); ?></span>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-teal-900 bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-200">
                                            <?php echo $resolvedPct; ?>% Cleared
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 font-normal mt-1.5">Successfully collected &amp; closed</p>
                                </div>
                            </div>

                            <!-- Micro Progress Bar -->
                            <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5">
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-teal-500 rounded-full transition-all duration-500" style="width: <?php echo min(100, $resolvedPct); ?>%;"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 3. TRENDS & PUROK DISTRIBUTION CHARTS SECTION                -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Left Panel: Submission Volume Trends Chart (2 Cols) -->
                        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                                            </div>
                                            <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                                                Waste Report Submission Trends
                                            </h2>
                                        </div>
                                        <p class="text-xs text-slate-500 font-normal">Monthly report volume &amp; operational reporting velocity</p>
                                    </div>
                                    
                                    <!-- Period indicator -->
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-xs font-semibold text-slate-600 border border-slate-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Past 6 Months Continuous</span>
                                    </div>
                                </div>

                                <!-- Key Statistics Row in Chart Header -->
                                <div class="grid grid-cols-3 gap-3 mb-6 p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Total Volume</span>
                                        <span class="text-base font-extrabold font-mono text-slate-900"><?php echo number_format($stats['total']); ?></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Top Zone</span>
                                        <span class="text-base font-extrabold text-emerald-700 truncate block"><?php echo htmlspecialchars($highest_purok); ?></span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Active Sectors</span>
                                        <span class="text-base font-extrabold font-mono text-slate-900"><?php echo count($purok_chart_data); ?></span>
                                    </div>
                                </div>

                                <!-- Line Chart Canvas -->
                                <div class="h-64 sm:h-72 w-full relative">
                                    <canvas id="trendsChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Purok Distribution Breakdown (1 Col) -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            </div>
                                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Purok Distribution</h2>
                                        </div>
                                        <p class="text-xs text-slate-500 font-normal">Reports logged per zone</p>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-100 font-mono">
                                        <?php echo count($purok_chart_data); ?> Zones
                                    </span>
                                </div>

                                <!-- Doughnut Chart Container with Absolute Centered Stat -->
                                <div class="h-44 w-full relative flex items-center justify-center my-3">
                                    <canvas id="purokChart"></canvas>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                                        <span class="text-xl font-extrabold font-mono text-slate-900"><?php echo number_format($stats['total']); ?></span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Reports</span>
                                    </div>
                                </div>

                                <!-- List of Purok Breakdown -->
                                <div class="space-y-2.5 pt-3 border-t border-slate-100 max-h-48 overflow-y-auto pr-1">
                                    <?php 
                                    $chartColors = ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#64748B'];
                                    $totalCount = max(1, $stats['total']);
                                    foreach ($purok_chart_data as $idx => $p): 
                                        $dotCol = $chartColors[$idx % count($chartColors)];
                                        $purokPct = round(((int)$p['count'] / $totalCount) * 100);
                                    ?>
                                    <div class="flex items-center justify-between text-xs py-0.5">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?php echo $dotCol; ?>;"></span>
                                            <span class="text-slate-700 font-semibold truncate"><?php echo htmlspecialchars($p['purok_name']); ?></span>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="text-[11px] text-slate-400 font-medium"><?php echo $purokPct; ?>%</span>
                                            <span class="font-bold font-mono text-slate-800 bg-slate-100 px-2 py-0.5 rounded-md text-[11px]">
                                                <?php echo $p['count']; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 4. SYSTEM OPERATIONAL READINESS CARDS (3 Equal-Height Cards) -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Widget 1: Next Waste Collection Schedule -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center border border-sky-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900">Next Collection</h3>
                                            <p class="text-[11px] text-slate-400 font-medium">Scheduled Route</p>
                                        </div>
                                    </div>
                                    <a href="<?php echo app_url('admin/schedule'); ?>" class="text-xs font-bold text-sky-700 hover:text-sky-800">Manage →</a>
                                </div>

                                <?php if ($next_schedule): ?>
                                <div class="p-4 rounded-2xl bg-sky-50/70 border border-sky-100 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-extrabold uppercase tracking-wider text-sky-900"><?php echo htmlspecialchars($next_schedule['collection_day']); ?></span>
                                        <span class="text-xs font-bold font-mono text-sky-800 bg-sky-100 px-2 py-0.5 rounded-lg"><?php echo date('g:i A', strtotime($next_schedule['start_time'])); ?></span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900 line-clamp-2"><?php echo htmlspecialchars($next_schedule['puroks'] ?: 'All Puroks'); ?></p>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-600">
                                        <span class="text-slate-400">Waste Type:</span>
                                        <span class="font-bold text-sky-900"><?php echo htmlspecialchars($next_schedule['waste_type'] ?? 'General Waste'); ?></span>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center text-xs text-slate-400">
                                    No active schedule configured.
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-400">Collection Status</span>
                                <span class="font-semibold text-emerald-700 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active Routing
                                </span>
                            </div>
                        </div>

                        <!-- Widget 2: Resident & User Portal Summary -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center border border-purple-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900">Resident Portal</h3>
                                            <p class="text-[11px] text-slate-400 font-medium">User Directory</p>
                                        </div>
                                    </div>
                                    <a href="<?php echo app_url('admin/accounts'); ?>" class="text-xs font-bold text-purple-700 hover:text-purple-800">Accounts →</a>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-center">
                                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Users</p>
                                        <p class="text-2xl font-extrabold text-slate-900 font-mono mt-0.5"><?php echo number_format($resident_stats['total']); ?></p>
                                    </div>
                                    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-100">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Active</p>
                                        <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-0.5"><?php echo number_format($resident_stats['active']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-400">Account Health</span>
                                <span class="font-semibold text-emerald-700">100% In Good Standing</span>
                            </div>
                        </div>

                        <!-- Widget 3: Announcements & Public Bulletins -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900">Announcements</h3>
                                            <p class="text-[11px] text-slate-400 font-medium">Public Bulletins</p>
                                        </div>
                                    </div>
                                    <a href="<?php echo app_url('admin/announcements'); ?>" class="text-xs font-bold text-amber-700 hover:text-amber-800">Announce →</a>
                                </div>

                                <?php if ($latest_announce): ?>
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                                        <span>Latest Notice</span>
                                        <span><?php echo date('M d, Y', strtotime($latest_announce['created_at'])); ?></span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900 line-clamp-2"><?php echo htmlspecialchars($latest_announce['title']); ?></p>
                                </div>
                                <?php else: ?>
                                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center text-xs text-slate-400">
                                    No announcements published yet.
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-400">Active Notices</span>
                                <span class="font-semibold text-slate-700"><?php echo $active_announcements; ?> Published</span>
                            </div>
                        </div>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 5. RECENT REPORTS TABLE & LIVE AUDIT TRAIL                   -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Left Column: Recent Reports Table (2 Cols) -->
                        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between">
                            <div>
                                <!-- Header & Filter Bar -->
                                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            </div>
                                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Recent Waste Reports</h2>
                                        </div>
                                        <p class="text-xs text-slate-500">Latest resident &amp; guest incident submissions</p>
                                    </div>

                                    <!-- Status Filter Tabs -->
                                    <div class="flex flex-wrap items-center gap-1.5 text-xs font-semibold">
                                        <button type="button" onclick="filterRecentTable('all')" id="btn-filter-all" class="report-filter-btn px-3 py-1.5 rounded-xl bg-slate-900 text-white shadow-2xs transition cursor-pointer">
                                            All (<?php echo count($recent_reports); ?>)
                                        </button>
                                        <button type="button" onclick="filterRecentTable('Pending')" id="btn-filter-pending" class="report-filter-btn px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer">
                                            Pending
                                        </button>
                                        <button type="button" onclick="filterRecentTable('Verified')" id="btn-filter-verified" class="report-filter-btn px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer">
                                            Verified
                                        </button>
                                        <a href="<?php echo app_url('admin/reports'); ?>" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 ml-2 hidden sm:inline">
                                            View All →
                                        </a>
                                    </div>
                                </div>

                                <!-- Desktop Table View -->
                                <div class="overflow-x-auto hidden md:block">
                                    <table class="w-full text-left border-collapse" id="recentReportsTable">
                                        <thead>
                                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500">
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
                                                $reporterName = $report['resident_name'] ?? 'Guest';
                                                $initials = strtoupper(substr($reporterName, 0, 2));
                                            ?>
                                            <tr class="hover:bg-slate-50/80 transition report-row" data-status="<?php echo htmlspecialchars($report['status']); ?>">
                                                <!-- Tracking ID -->
                                                <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                                    <span class="bg-slate-100 text-slate-800 px-2 py-1 rounded-md border border-slate-200/80">
                                                        <?php echo htmlspecialchars($reportId); ?>
                                                    </span>
                                                </td>

                                                <!-- Reporter -->
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-[10px] shrink-0">
                                                            <?php echo $initials; ?>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="font-bold text-slate-800 truncate"><?php echo htmlspecialchars($reporterName); ?></p>
                                                            <?php if ($isGuest): ?>
                                                                <span class="inline-block text-[9px] font-extrabold uppercase px-1.5 py-0.2 rounded bg-purple-50 text-purple-700 border border-purple-200">Guest</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Category -->
                                                <td class="py-4 px-6 font-medium text-slate-700">
                                                    <?php echo htmlspecialchars($report['category'] ?? 'General'); ?>
                                                </td>

                                                <!-- Purok -->
                                                <td class="py-4 px-6 font-medium text-slate-700">
                                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md text-[11px]">
                                                        <?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?>
                                                    </span>
                                                </td>

                                                <!-- Date -->
                                                <td class="py-4 px-6 text-slate-500 font-mono text-[11px]">
                                                    <?php echo date('M d, Y', strtotime($report['submission_date'])); ?>
                                                </td>

                                                <!-- Status Badge -->
                                                <td class="py-4 px-6">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold border <?php echo $badge['bg']; ?>">
                                                        <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                                        <?php echo $badge['label']; ?>
                                                    </span>
                                                </td>

                                                <!-- Action Review -->
                                                <td class="py-4 px-6 text-right">
                                                    <a href="<?php echo app_url('admin/viewReport/' . ($report['id'])); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition shadow-2xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                        <span>Review</span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($recent_reports)): ?>
                                            <tr>
                                                <td colspan="7" class="py-12 text-center text-slate-400 font-medium">No waste reports recorded yet.</td>
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
                                        $reporterName = $report['resident_name'] ?? 'Guest';
                                    ?>
                                    <div class="p-4 space-y-3 report-row" data-status="<?php echo htmlspecialchars($report['status']); ?>">
                                        <div class="flex items-center justify-between">
                                            <span class="font-mono font-bold text-xs text-slate-900 bg-slate-100 px-2 py-0.5 rounded"><?php echo htmlspecialchars($reportId); ?></span>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?php echo $badge['bg']; ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                                <?php echo $badge['label']; ?>
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-end text-xs">
                                            <div class="space-y-0.5">
                                                <p class="font-bold text-slate-800"><?php echo htmlspecialchars($report['category'] ?? 'General'); ?></p>
                                                <p class="text-slate-500"><?php echo htmlspecialchars($reporterName); ?> · <?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></p>
                                                <p class="text-[10px] text-slate-400 font-mono"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></p>
                                            </div>
                                            <a href="<?php echo app_url('admin/viewReport/' . ($report['id'])); ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 font-bold text-xs border border-emerald-200 hover:bg-emerald-100 transition shadow-2xs">Review →</a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500">
                                <span>Showing 10 most recent submissions</span>
                                <a href="<?php echo app_url('admin/reports'); ?>" class="font-bold text-emerald-700 hover:underline">Full Database →</a>
                            </div>
                        </div>

                        <!-- Right Column: Live Audit Trail Activity Feed (1 Col) -->
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-5">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            </div>
                                            <h2 class="text-base font-bold text-slate-900">Recent Activity</h2>
                                        </div>
                                        <p class="text-xs text-slate-500">Live system audit logs</p>
                                    </div>
                                    <a href="<?php echo app_url('admin/audit_logs'); ?>" class="text-xs font-bold text-slate-500 hover:text-slate-900">All Logs →</a>
                                </div>

                                <!-- Activity Timeline -->
                                <div class="space-y-4 my-2 relative pl-4 border-l-2 border-slate-100">
                                    <?php foreach ($recent_activity as $act): 
                                        $actName = $act['action'] ?? '';
                                        $dotColor = 'bg-emerald-500';
                                        if (stripos($actName, 'login') !== false) $dotColor = 'bg-blue-500';
                                        elseif (stripos($actName, 'archive') !== false || stripos($actName, 'delete') !== false) $dotColor = 'bg-amber-500';
                                        elseif (stripos($actName, 'logout') !== false) $dotColor = 'bg-slate-400';
                                    ?>
                                    <div class="relative pl-3 space-y-0.5">
                                        <span class="absolute -left-[23px] top-1.5 w-3 h-3 rounded-full <?php echo $dotColor; ?> ring-4 ring-white shadow-2xs"></span>
                                        <p class="text-xs font-bold text-slate-800 leading-snug"><?php echo htmlspecialchars($act['action']); ?></p>
                                        <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed"><?php echo htmlspecialchars($act['details']); ?></p>
                                        <p class="text-[10px] text-slate-400 font-mono pt-0.5">
                                            <?php echo date('M d, g:i A', strtotime($act['created_at'])); ?> · <span class="font-semibold text-slate-600"><?php echo htmlspecialchars($act['user_name'] ?? 'System'); ?></span>
                                        </p>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php if (empty($recent_activity)): ?>
                                    <p class="text-xs text-slate-400 italic text-center py-6">No recent activity logged.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span>Security Level</span>
                                <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    2FA Protected
                                </span>
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
        const chartCtx = ctxTrends.getContext('2d');
        const gradient = chartCtx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

        new Chart(ctxTrends, {
            type: 'line',
            data: {
                labels: trendLabels.length ? trendLabels : ['Mar 2026', 'Apr 2026', 'May 2026', 'Jun 2026', 'Jul 2026', 'Aug 2026'],
                datasets: [{
                    label: 'Report Submissions',
                    data: trendCounts.length ? trendCounts : [0, 0, 0, 0, 0, 30],
                    borderColor: '#10B981',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.38,
                    borderWidth: 3,
                    pointBackgroundColor: '#07281e',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2.5,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Miranda Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Miranda Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Reports Logged';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Miranda Sans', size: 11, weight: '500' }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { font: { family: 'Miranda Sans', size: 11, weight: '500' }, color: '#64748B', precision: 0 }
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
                labels: purokLabels.length ? purokLabels : ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'],
                datasets: [{
                    data: purokCounts.length ? purokCounts : [6, 6, 6, 6, 6],
                    backgroundColor: colors,
                    borderWidth: 3,
                    borderColor: '#FFFFFF',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Miranda Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Miranda Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + ' reports';
                            }
                        }
                    }
                }
            }
        });
    }
});

// 4. Client-side Filter for Recent Reports Table
function filterRecentTable(status) {
    // Update button states
    document.querySelectorAll('.report-filter-btn').forEach(btn => {
        btn.classList.remove('bg-slate-900', 'text-white', 'shadow-2xs');
        btn.classList.add('bg-slate-100', 'text-slate-600');
    });

    const activeBtn = document.getElementById('btn-filter-' + status.toLowerCase());
    if (activeBtn) {
        activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
        activeBtn.classList.add('bg-slate-900', 'text-white', 'shadow-2xs');
    }

    // Filter table rows
    const rows = document.querySelectorAll('.report-row');
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        if (status === 'all' || rowStatus === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>