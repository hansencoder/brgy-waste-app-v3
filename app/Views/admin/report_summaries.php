<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$kpis = $data['kpis'] ?? [];
$trendData = $data['trend_data'] ?? [];
$trendByGranularity = $data['trend_data_by_granularity'] ?? [];
$categoryData = $data['category_data'] ?? [];
$statusData = $data['status_data'] ?? [];
$conditionData = $data['condition_data'] ?? [];
$purokData = $data['purok_data'] ?? [];
$purokStacked = $data['purok_stacked'] ?? ['labels' => [], 'datasets' => []];
$hotspotIntelligence = $data['hotspot_intelligence'] ?? [];
$totalSupports = $data['total_supports'] ?? 0;
$supportToReportRatio = $data['support_to_report_ratio'] ?? 0;
$avgResolutionHours = $data['avg_resolution_hours'] ?? 0;
$avgVerificationHours = $data['avg_verification_hours'] ?? 0;
$fastestResolution = $data['fastest_resolution'] ?? 0;
$longestResolution = $data['longest_resolution'] ?? 0;
$trendComparison = $data['trend_comparison'] ?? [];
$decisionSupport = $data['decision_support'] ?? [];
$filteredReports = $data['filtered_reports'] ?? [];
$dateFrom = $data['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
$dateTo = $data['date_to'] ?? date('Y-m-d');
$categories = $data['categories'] ?? [];
$puroks = $data['puroks'] ?? [];
$statuses = $data['statuses'] ?? [];
$quantities = $data['quantities'] ?? [];
$conditions = $data['conditions'] ?? [];
$selectedCategory = $data['selected_category'] ?? 0;
$selectedPurok = $data['selected_purok'] ?? 0;
$selectedStatus = $data['selected_status'] ?? '';
$selectedQuantity = $data['selected_quantity'] ?? 0;
$selectedCondition = $data['selected_condition'] ?? 0;
$trendGranularity = $data['trend_granularity'] ?? 'monthly';
$exports = $data['exports'] ?? [];

$trendLabels = array_column($trendData, 'month');
$trendValues = array_column($trendData, 'count');
$categoryLabels = array_column($categoryData, 'category_name');
$categoryValues = array_column($categoryData, 'count');
$statusLabels = array_column($statusData, 'status_name');
$statusValues = array_column($statusData, 'count');
$statusColors = array_column($statusData, 'color_code');
$conditionLabels = array_column($conditionData, 'condition_name');
$conditionValues = array_column($conditionData, 'count');
$purokLabels = array_column($purokData, 'purok_name');
$purokValues = array_column($purokData, 'total_reports');

$exportQuery = http_build_query([
    'date_from' => $dateFrom,
    'date_to' => $dateTo,
    'category' => $selectedCategory,
    'purok' => $selectedPurok,
    'status' => $selectedStatus,
    'quantity' => $selectedQuantity,
    'condition' => $selectedCondition,
    'trend_granularity' => $trendGranularity,
]);

function statusBadgeClass($status) {
    $map = [
        'Pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'Verified' => 'bg-sky-50 text-sky-700 border border-sky-200',
        'In Progress' => 'bg-orange-50 text-orange-700 border border-orange-200',
        'Resolved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'Rejected' => 'bg-red-50 text-red-700 border border-red-200',
    ];
    return $map[$status] ?? 'bg-slate-50 text-slate-700 border border-slate-200';
}
?>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .chart-container { position: relative; height: 260px; width: 100%; }
    .chart-container canvas { width: 100% !important; height: 100% !important; }
    .kpi-card { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px -6px rgba(0,0,0,0.08); }
    .donut-container { position: relative; max-width: 170px; max-height: 170px; margin: 0 auto; }
    .donut-container canvas { width: 100% !important; height: 100% !important; }
    .donut-center-text {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        text-align: center; pointer-events: none; line-height: 1.15;
    }
    .donut-center-text .number { font-size: 1.4rem; font-weight: 800; color: #0f172a; }
    .donut-center-text .label { font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; }
</style>

<div class="min-h-screen bg-slate-50 text-slate-900 w-full flex font-sans antialiased">
    <div class="lg:flex lg:min-h-screen w-full">
        <!-- Sidebar Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                    <!-- Page Action Header -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Intelligence &amp; Insights
                                </span>
                                <span class="text-sm text-slate-300 font-bold">•</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-500">Live Analytics</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Waste Analytics &amp; Reports Summary
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Real-time reporting metrics, waste classification breakdown, purok hotspots, and decision support.
                            </p>
                        </div>

                        <!-- Header Action Buttons -->
                        <div class="flex items-center gap-3">
                            <a href="<?php echo app_url('admin/exportAnalyticsPDF?<?php echo $exportQuery; ?>'); ?>"
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 font-extrabold text-xs sm:text-sm border border-red-200 shadow-xs transition active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Print Report View
                            </a>
                            <a href="<?php echo app_url('admin/exportAnalyticsExcel?<?php echo $exportQuery; ?>'); ?>"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white font-extrabold text-xs sm:text-sm shadow-xs transition active:scale-[0.98] border border-emerald-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Export Dataset
                            </a>
                        </div>
                    </div>

                    <!-- Filter Analytics Panel -->
                    <div class="bg-white p-6 sm:p-7 rounded-2xl border-2 border-slate-200 shadow-xs space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                </div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Filter Parameters &amp; Granularity</h2>
                            </div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Multi-Variable Analytics</span>
                        </div>

                        <form method="GET" action="<?php echo app_url('admin/report_summaries'); ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Date From</label>
                                <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Date To</label>
                                <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Waste Category</label>
                                <select name="category" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                                    <option value="0">All Categories</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?php echo $c['category_id']; ?>" <?php echo $selectedCategory == $c['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Purok Area</label>
                                <select name="purok" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                                    <option value="0">All Puroks</option>
                                    <?php foreach ($puroks as $p): ?>
                                        <option value="<?php echo $p['purok_id']; ?>" <?php echo $selectedPurok == $p['purok_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Report Status</label>
                                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                                    <option value="">All Statuses</option>
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?php echo htmlspecialchars($s['status_name']); ?>" <?php echo $selectedStatus === $s['status_name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['status_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Estimated Quantity</label>
                                <select name="quantity" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                                    <option value="0">All Quantities</option>
                                    <?php foreach ($quantities as $q): ?>
                                        <option value="<?php echo $q['quantity_id']; ?>" <?php echo $selectedQuantity == $q['quantity_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($q['quantity_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Waste Condition</label>
                                <select name="condition" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                                    <option value="0">All Conditions</option>
                                    <?php foreach ($conditions as $c): ?>
                                        <option value="<?php echo $c['condition_id']; ?>" <?php echo $selectedCondition == $c['condition_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['condition_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Trend Granularity</label>
                                <select name="trend_granularity" class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-bold text-slate-900 outline-none transition">
                                    <?php foreach (['daily' => 'Daily Intervals', 'weekly' => 'Weekly Intervals', 'monthly' => 'Monthly Intervals', 'yearly' => 'Yearly Intervals'] as $val => $label): ?>
                                        <option value="<?php echo $val; ?>" <?php echo $trendGranularity === $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex items-center gap-3 col-span-full pt-2">
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-[#0B2E22] hover:bg-[#084232] text-white rounded-xl font-extrabold text-xs sm:text-sm shadow-xs transition active:scale-[0.98] cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Apply Filter Criteria
                                </button>
                                <a href="<?php echo app_url('admin/report_summaries'); ?>" class="inline-flex items-center px-5 py-3 border-2 border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl font-extrabold text-xs sm:text-sm transition cursor-pointer">
                                    Reset Filters
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- KPI Cards (6 Cards: Resolution Rate and Avg Resolution removed as requested) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        <!-- Total Reports -->
                        <div class="kpi-card bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-xs text-center flex flex-col justify-between">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Reports</span>
                            <p class="text-3xl font-extrabold text-slate-900 mt-2 mb-1 tracking-tight"><?php echo $kpis['total'] ?? 0; ?></p>
                            <span class="text-[11px] font-bold text-slate-400">All submissions</span>
                        </div>

                        <!-- Pending -->
                        <div class="kpi-card bg-white p-5 rounded-2xl border-2 border-amber-200/80 shadow-xs text-center flex flex-col justify-between">
                            <span class="text-xs font-black text-amber-700 uppercase tracking-wider">
                                Pending
                            </span>
                            <p class="text-3xl font-extrabold text-amber-600 mt-2 mb-1 tracking-tight"><?php echo $kpis['pending'] ?? 0; ?></p>
                            <span class="text-[11px] font-bold text-amber-700/70">Awaiting validation</span>
                        </div>

                        <!-- Verified -->
                        <div class="kpi-card bg-white p-5 rounded-2xl border-2 border-sky-200/80 shadow-xs text-center flex flex-col justify-between">
                            <span class="text-xs font-black text-sky-700 uppercase tracking-wider">
                                Verified
                            </span>
                            <p class="text-3xl font-extrabold text-sky-600 mt-2 mb-1 tracking-tight"><?php echo $kpis['verified'] ?? 0; ?></p>
                            <span class="text-[11px] font-bold text-sky-700/70">Confirmed reports</span>
                        </div>

                        <!-- In Progress -->
                        <div class="kpi-card bg-white p-5 rounded-2xl border-2 border-orange-200/80 shadow-xs text-center flex flex-col justify-between">
                            <span class="text-xs font-black text-orange-700 uppercase tracking-wider">
                                In Progress
                            </span>
                            <p class="text-3xl font-extrabold text-orange-600 mt-2 mb-1 tracking-tight"><?php echo $kpis['in_progress'] ?? 0; ?></p>
                            <span class="text-[11px] font-bold text-orange-700/70">Collection dispatched</span>
                        </div>

                        <!-- Resolved -->
                        <div class="kpi-card bg-white p-5 rounded-2xl border-2 border-emerald-200/80 shadow-xs text-center flex flex-col justify-between">
                            <span class="text-xs font-black text-emerald-700 uppercase tracking-wider">
                                Resolved
                            </span>
                            <p class="text-3xl font-extrabold text-emerald-600 mt-2 mb-1 tracking-tight"><?php echo $kpis['resolved'] ?? 0; ?></p>
                            <span class="text-[11px] font-bold text-emerald-700/70">Cleaned &amp; cleared</span>
                        </div>

                        <!-- Active Hotspots -->
                        <div class="kpi-card bg-white p-5 rounded-2xl border-2 border-red-200/80 shadow-xs text-center flex flex-col justify-between">
                            <span class="text-xs font-black text-red-700 uppercase tracking-wider">
                                Hotspots
                            </span>
                            <p class="text-3xl font-extrabold text-red-600 mt-2 mb-1 tracking-tight"><?php echo $kpis['active_hotspots'] ?? 0; ?></p>
                            <span class="text-[11px] font-bold text-red-700/70">Critical cluster zones</span>
                        </div>
                    </div>

                    <!-- Report Trends Timeline -->
                    <div class="bg-white p-6 sm:p-7 rounded-2xl border-2 border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Report Volume Trends</h2>
                                <p class="text-xs sm:text-sm font-semibold text-slate-500">Chronological incident frequency and volume over time</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-extrabold text-slate-700">
                                <?php echo ucfirst($trendGranularity); ?> View
                            </span>
                        </div>
                        <div class="chart-container">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>

                    <!-- Distribution Charts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- By Category -->
                        <div class="bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-xs flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Waste Classification</h3>
                                <p class="text-xs font-semibold text-slate-500 mb-4">Volume by waste category</p>
                            </div>
                            <div class="chart-container" style="height:210px;">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>

                        <!-- By Status -->
                        <div class="bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-xs flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Status Distribution</h3>
                                <p class="text-xs font-semibold text-slate-500 mb-4">Current stage of reported cases</p>
                            </div>
                            <div class="flex justify-center items-center py-2">
                                <div class="donut-container">
                                    <canvas id="statusChart"></canvas>
                                    <div class="donut-center-text">
                                        <div class="number"><?php echo $kpis['total'] ?? 0; ?></div>
                                        <div class="label">Reports</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- By Waste Condition -->
                        <div class="bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-xs flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Condition Severity</h3>
                                <p class="text-xs font-semibold text-slate-500 mb-4">Scattered, bagged, or hazardous</p>
                            </div>
                            <div class="chart-container" style="height:210px;">
                                <canvas id="conditionChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Purok Spatial Analysis -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white p-6 sm:p-7 rounded-2xl border-2 border-slate-200 shadow-xs">
                            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Reports by Purok Zone</h2>
                            <p class="text-xs font-semibold text-slate-500 mb-4">Incident density ranked across puroks</p>
                            <div class="chart-container" style="height:240px;">
                                <canvas id="purokChart"></canvas>
                            </div>
                        </div>

                        <div class="bg-white p-6 sm:p-7 rounded-2xl border-2 border-slate-200 shadow-xs">
                            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Waste Breakdown per Purok</h2>
                            <p class="text-xs font-semibold text-slate-500 mb-4">Stacked distribution of categories by sector</p>
                            <div class="chart-container" style="height:240px;">
                                <canvas id="purokStackedChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Hotspot Intelligence Table -->
                    <div class="bg-white p-6 sm:p-7 rounded-2xl border-2 border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                                    Hotspot Intelligence &amp; Priority Zones
                                </h2>
                                <p class="text-xs sm:text-sm font-semibold text-slate-500">Clusters identified by high report density requiring immediate intervention</p>
                            </div>
                            <span class="px-3.5 py-1.5 rounded-full bg-red-50 text-red-900 text-xs font-extrabold border border-red-200">
                                Priority Matrix
                            </span>
                        </div>

                        <?php if (!empty($hotspotIntelligence)): ?>
                            <div class="overflow-x-auto rounded-xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-200 text-xs sm:text-sm">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Priority</th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Purok Area</th>
                                            <th class="px-4 py-3 text-center text-xs font-black text-slate-600 uppercase tracking-wider">Total Reports</th>
                                            <th class="px-4 py-3 text-center text-xs font-black text-slate-600 uppercase tracking-wider">Unresolved</th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Dominant Waste</th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Latest Activity</th>
                                            <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Recommended Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php $priority = 1; foreach ($hotspotIntelligence as $spot): ?>
                                            <?php
                                                $action = 'Monitor regular collection';
                                                $actionClass = 'bg-slate-100 text-slate-800 border-slate-200';
                                                if ($spot['report_count'] >= 10) {
                                                    $action = 'Immediate site inspection';
                                                    $actionClass = 'bg-red-100 text-red-900 border-red-300';
                                                } elseif ($spot['report_count'] >= 6) {
                                                    $action = 'Schedule special truck route';
                                                    $actionClass = 'bg-amber-100 text-amber-900 border-amber-300';
                                                } elseif (($spot['unresolved_count'] ?? 0) >= 3) {
                                                    $action = 'Clear pending backlogs';
                                                    $actionClass = 'bg-orange-100 text-orange-900 border-orange-300';
                                                }
                                            ?>
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="px-4 py-3 font-extrabold">
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl <?php echo $priority <= 2 ? 'bg-red-100 text-red-800 border border-red-300' : 'bg-slate-100 text-slate-800 border border-slate-200'; ?> text-xs font-extrabold">
                                                        #<?php echo $priority++; ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 font-extrabold text-slate-900"><?php echo htmlspecialchars($spot['purok_name']); ?></td>
                                                <td class="px-4 py-3 text-center font-extrabold text-slate-900"><?php echo $spot['report_count']; ?></td>
                                                <td class="px-4 py-3 text-center font-extrabold text-red-600"><?php echo $spot['unresolved_count'] ?? 0; ?></td>
                                                <td class="px-4 py-3 font-bold text-slate-700"><?php echo htmlspecialchars($spot['dominant_category'] ?? 'N/A'); ?></td>
                                                <td class="px-4 py-3 font-bold text-slate-500"><?php echo date('M d, Y', strtotime($spot['latest_report'])); ?></td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border <?php echo $actionClass; ?>">
                                                        <?php echo $action; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-8 text-center bg-slate-50 rounded-xl border border-slate-200">
                                <p class="text-sm font-bold text-slate-500">No active hotspot clusters detected for the filtered criteria.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Decision Support & Community Intelligence Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Community Engagement -->
                        <div class="bg-white p-6 rounded-2xl border-2 border-slate-200 shadow-xs flex flex-col justify-between">
                            <h2 class="text-base font-extrabold text-slate-900 mb-3">Community Engagement</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50 border border-slate-200">
                                    <span class="text-xs font-bold text-slate-600">Resident Upvotes &amp; Supports</span>
                                    <span class="text-base font-extrabold text-slate-900"><?php echo $totalSupports; ?></span>
                                </div>
                                <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50 border border-slate-200">
                                    <span class="text-xs font-bold text-slate-600">Support-to-Report Ratio</span>
                                    <span class="text-base font-extrabold text-slate-900"><?php echo $supportToReportRatio; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Decision Support Summary -->
                        <div class="lg:col-span-2 bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent rounded-2xl border-2 border-emerald-200 p-6 shadow-xs flex flex-col justify-between">
                            <div>
                                <h2 class="text-base font-extrabold text-emerald-950 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    Automated Decision Support Summary
                                </h2>
                                <p class="text-xs font-semibold text-emerald-800 mb-4">Strategic recommendations generated from current dataset patterns</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <?php if (!empty($decisionSupport['highest_hotspot'])): ?>
                                    <div class="p-3 bg-white rounded-xl border border-emerald-200 flex items-start gap-2.5 shadow-2xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        <div>
                                            <p class="font-extrabold text-slate-900">Highest Hotspot</p>
                                            <p class="text-slate-600 font-semibold mt-0.5"><?php echo htmlspecialchars($decisionSupport['highest_hotspot']['purok_name']); ?> (<?php echo $decisionSupport['highest_hotspot']['report_count']; ?> incidents)</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="p-3 bg-white rounded-xl border border-emerald-200 flex items-start gap-2.5 shadow-2xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                                    <div>
                                        <p class="font-extrabold text-slate-900">Volume Trend</p>
                                        <p class="text-slate-600 font-semibold mt-0.5">
                                            Reports are <?php echo ($decisionSupport['trend_increasing'] ?? false) ? '<strong class="text-red-600">increasing</strong>' : '<strong class="text-emerald-700">stable or decreasing</strong>'; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtered Report Table -->
                    <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-xs overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border-b border-slate-200">
                            <div>
                                <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Filtered Case Records</h2>
                                <p class="text-xs sm:text-sm font-semibold text-slate-500"><?php echo count($filteredReports); ?> record(s) matching selected parameters</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="<?php echo app_url('admin/exportReportSummaryXLSX?<?php echo $exportQuery; ?>'); ?>"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-extrabold text-xs shadow-xs transition active:scale-[0.98]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    Export CSV
                                </a>
                                <a href="<?php echo app_url('admin/exportReportSummaryPDF?<?php echo $exportQuery; ?>'); ?>"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-extrabold text-xs shadow-xs transition active:scale-[0.98]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>

                        <?php if (empty($filteredReports)): ?>
                            <p class="p-8 text-center text-slate-500 font-bold text-sm">No reports matching current filter parameters.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-xs sm:text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-5 py-3.5 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Report ID</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Resident / Reporter</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Waste Category</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Purok Area</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Exact Location</th>
                                            <th class="px-5 py-3.5 text-center text-xs font-black text-slate-600 uppercase tracking-wider">Status</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Reported Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        <?php foreach ($filteredReports as $report): ?>
                                            <tr class="hover:bg-slate-50/80 transition">
                                                <td class="px-5 py-3.5 font-mono font-bold text-slate-700">#<?php echo $report['id']; ?></td>
                                                <td class="px-5 py-3.5 font-extrabold text-slate-900"><?php echo htmlspecialchars($report['name']); ?></td>
                                                <td class="px-5 py-3.5 font-bold text-slate-700"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></td>
                                                <td class="px-5 py-3.5 font-bold text-slate-700"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></td>
                                                <td class="px-5 py-3.5 font-medium text-slate-600 truncate max-w-xs"><?php echo htmlspecialchars($report['location'] ?? ''); ?></td>
                                                <td class="px-5 py-3.5 text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold <?php echo statusBadgeClass($report['status']); ?>">
                                                        <?php echo htmlspecialchars($report['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3.5 font-bold text-slate-600"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Previous Exports -->
                    <div class="bg-white p-6 sm:p-7 rounded-2xl border-2 border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Recent Export Archives
                            </h2>
                            <span class="text-xs font-bold text-slate-400">Generated Reports History</span>
                        </div>
                        <div class="space-y-2.5">
                            <?php if (!empty($exports)): ?>
                                <?php foreach ($exports as $export): ?>
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                                        <div>
                                            <p class="font-extrabold text-slate-900 text-sm"><?php echo htmlspecialchars($export['filename']); ?></p>
                                            <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                                <?php echo date('M d, Y', strtotime($export['generated_at'])); ?>
                                                · <?php echo strtoupper(htmlspecialchars($export['file_type'] ?? '')); ?>
                                                · <?php echo (int)($export['total_reports'] ?? 0); ?> records
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-slate-500 font-bold text-xs py-4 text-center">No export archives generated yet.</p>
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
    Chart.defaults.font.family = "'Miranda Sans', sans-serif";

    const trendLabels = <?php echo json_encode($trendLabels); ?>;
    const trendValues = <?php echo json_encode(array_map('intval', $trendValues)); ?>;

    const trendCtx = document.getElementById('trendChart');
    if (trendCtx && trendValues.length) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Report Volume',
                    data: trendValues,
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#059669',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
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
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { weight: '600' } }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false }, ticks: { font: { weight: '600' } } }
                }
            }
        });
    }

    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx && <?php echo json_encode(!empty($categoryValues)); ?>) {
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categoryLabels); ?>,
                datasets: [{
                    label: 'Reports',
                    data: <?php echo json_encode(array_map('intval', $categoryValues)); ?>,
                    backgroundColor: '#059669',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' } } }
                }
            }
        });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx && <?php echo json_encode(!empty($statusValues)); ?>) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($statusLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_map('intval', $statusValues)); ?>,
                    backgroundColor: <?php echo json_encode($statusColors); ?>,
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '72%',
                plugins: { legend: { display: false } }
            }
        });
    }

    const conditionCtx = document.getElementById('conditionChart');
    if (conditionCtx && <?php echo json_encode(!empty($conditionValues)); ?>) {
        const colors = ['#059669', '#D97706', '#0284C7', '#DC2626', '#7C3AED', '#DB2777', '#EA580C'];
        new Chart(conditionCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($conditionLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_map('intval', $conditionValues)); ?>,
                    backgroundColor: colors.slice(0, <?php echo count($conditionValues); ?>),
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { boxWidth: 10, font: { size: 10, weight: '600' } } } }
            }
        });
    }

    const purokCtx = document.getElementById('purokChart');
    if (purokCtx && <?php echo json_encode(!empty($purokValues)); ?>) {
        new Chart(purokCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($purokLabels); ?>,
                datasets: [{
                    label: 'Reports',
                    data: <?php echo json_encode(array_map('intval', $purokValues)); ?>,
                    backgroundColor: '#059669',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    y: { grid: { display: false }, ticks: { font: { weight: '600' } } }
                }
            }
        });
    }

    const stackedCtx = document.getElementById('purokStackedChart');
    const stackedData = <?php echo json_encode($purokStacked); ?>;
    if (stackedCtx && stackedData.labels && stackedData.labels.length) {
        new Chart(stackedCtx, {
            type: 'bar',
            data: {
                labels: stackedData.labels,
                datasets: stackedData.datasets
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10, weight: '600' } } } },
                scales: {
                    x: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } },
                    y: { stacked: true, grid: { display: false }, ticks: { font: { weight: '600' } } }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
