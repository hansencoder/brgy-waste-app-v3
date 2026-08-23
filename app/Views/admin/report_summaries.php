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
$participationData = $data['participation_data'] ?? ['resident_count' => 0, 'guest_count' => 0, 'total_count' => 0, 'resident_pct' => 0, 'guest_pct' => 0];
$residentCount = (int)($participationData['resident_count'] ?? 0);
$guestCount = (int)($participationData['guest_count'] ?? 0);
$residentPct = (float)($participationData['resident_pct'] ?? 0);
$guestPct = (float)($participationData['guest_pct'] ?? 0);

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

// Calculate pattern-based 1-sentence takeaways for key charts
$totalReportsCount = (int)($kpis['total'] ?? count($filteredReports));

// 1. Trend Line Takeaway
$maxTrendVal = !empty($trendValues) ? max($trendValues) : 0;
$maxTrendIdx = !empty($trendValues) ? array_search($maxTrendVal, $trendValues) : 0;
$maxTrendLabel = $trendLabels[$maxTrendIdx] ?? 'Peak Period';
$trendIsUp = !empty($decisionSupport['trend_increasing']);
$trendDirectionText = $trendIsUp ? 'an upward trend' : 'a stable or decreasing trend';
$trendInterpretation = !empty($trendValues) && array_sum($trendValues) > 0 
    ? "Incident volume peaked during {$maxTrendLabel} ({$maxTrendVal} reports), showing {$trendDirectionText} over the period."
    : "No incident reports logged within the selected timeframe.";

// 2. Category Takeaway
$topCatName = !empty($categoryLabels) ? $categoryLabels[0] : 'General Waste';
$topCatCount = !empty($categoryValues) ? (int)$categoryValues[0] : 0;
$totalCatSum = array_sum($categoryValues) ?: 1;
$topCatPct = round(($topCatCount / $totalCatSum) * 100);
$categoryInterpretation = $topCatCount > 0
    ? "{$topCatName} represents the largest proportion ({$topCatCount} reports, {$topCatPct}%), identifying it as the primary waste type requiring collection capacity."
    : "No categorical waste data available for the active filters.";

// 3. Status Takeaway
$resolvedCount = (int)($kpis['resolved'] ?? 0);
$pendingCount = (int)($kpis['pending'] ?? 0);
$resolutionRateVal = (float)($kpis['resolution_rate'] ?? 0);
$statusInterpretation = $totalReportsCount > 0
    ? "{$resolutionRateVal}% of reports have been resolved, with {$pendingCount} case(s) currently awaiting verification."
    : "No active report lifecycle records to evaluate.";

// 4. Participation Takeaway
$participationInterpretation = $totalReportsCount > 0
    ? "Registered residents submitted {$residentPct}% of incidents ({$residentCount} reports), demonstrating strong resident platform adoption over anonymous reports."
    : "No participation records available.";

// 5. Condition Takeaway
$topCondName = !empty($conditionLabels) ? $conditionLabels[0] : 'Standard';
$topCondCount = !empty($conditionValues) ? (int)$conditionValues[0] : 0;
$totalCondSum = array_sum($conditionValues) ?: 1;
$topCondPct = round(($topCondCount / $totalCondSum) * 100);
$conditionInterpretation = $topCondCount > 0
    ? "{$topCondName} is the most common physical state ({$topCondPct}% of reports), highlighting key containment priorities on site."
    : "No waste condition classifications logged.";

// 6. Purok Distribution Takeaway
$topPurokName = !empty($purokLabels) ? $purokLabels[0] : 'Purok 1';
$topPurokCount = !empty($purokValues) ? (int)$purokValues[0] : 0;
$totalPurokSum = array_sum($purokValues) ?: 1;
$topPurokPct = round(($topPurokCount / $totalPurokSum) * 100);
$purokInterpretation = $topPurokCount > 0
    ? "{$topPurokName} recorded the highest incident volume ({$topPurokCount} reports, {$topPurokPct}%), designating it as the prime collection sector."
    : "No localized purok reports recorded.";

// 7. Stacked Purok Takeaway
$stackedInterpretation = $topPurokCount > 0
    ? "{$topCatName} constitutes the dominant waste type across primary sectors including {$topPurokName}."
    : "No sector distribution data available.";

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

if (!function_exists('statusBadgeConfig')) {
    function statusBadgeConfig($status) {
        $map = [
            'Pending' => [
                'class' => 'bg-amber-50 text-amber-800 border-amber-200/80',
                'dot' => 'bg-amber-500'
            ],
            'Verified' => [
                'class' => 'bg-sky-50 text-sky-800 border-sky-200/80',
                'dot' => 'bg-sky-500'
            ],
            'In Progress' => [
                'class' => 'bg-orange-50 text-orange-800 border-orange-200/80',
                'dot' => 'bg-orange-500'
            ],
            'Resolved' => [
                'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                'dot' => 'bg-emerald-500'
            ],
            'Rejected' => [
                'class' => 'bg-rose-50 text-rose-800 border-rose-200/80',
                'dot' => 'bg-rose-500'
            ],
        ];
        return $map[$status] ?? [
            'class' => 'bg-slate-50 text-slate-800 border-slate-200',
            'dot' => 'bg-slate-400'
        ];
    }
}
?>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; }
    .chart-container { position: relative; height: 260px; width: 100%; }
    .chart-container canvas { width: 100% !important; height: 100% !important; }
    .card-hover {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
    }
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 28px -8px rgba(15, 23, 42, 0.08), 0 4px 12px -4px rgba(15, 23, 42, 0.04);
        border-color: #cbd5e1;
    }
    .donut-container { position: relative; max-width: 170px; max-height: 170px; margin: 0 auto; }
    .donut-container canvas { width: 100% !important; height: 100% !important; }
    .donut-center-text {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        text-align: center; pointer-events: none; line-height: 1.15;
    }
    .donut-center-text .number { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
    .donut-center-text .label { font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 9999px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 w-full flex font-sans antialiased">
    <div class="lg:flex lg:min-h-screen w-full">
        <!-- Sidebar Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">

                    <!-- ============================================================== -->
                    <!-- 1. MODERN COMMAND HERO BANNER                                  -->
                    <!-- ============================================================== -->
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#06241a] via-[#0b3b2c] to-[#041d15] text-white p-6 sm:p-8 shadow-xl border border-emerald-800/40">
                        <!-- Ambient Radial Highlights -->
                        <div class="absolute -right-16 -top-16 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute right-1/3 -bottom-20 w-64 h-64 bg-teal-400/10 rounded-full blur-2xl pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            <div class="space-y-2 max-w-3xl">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 backdrop-blur-md">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Real-Time Analytics Suite
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200 border border-white/10 backdrop-blur-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        <?php echo date('M d, Y', strtotime($dateFrom)); ?> &mdash; <?php echo date('M d, Y', strtotime($dateTo)); ?>
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/5 text-emerald-200/80 border border-white/5">
                                        <?php echo ucfirst($trendGranularity); ?> Intervals
                                    </span>
                                </div>

                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                                    Waste Analytics &amp; Reports Summary
                                </h1>

                                <p class="text-sm sm:text-base text-emerald-100/80 font-medium leading-relaxed">
                                    Comprehensive intelligence suite detailing community incident volume, spatial purok clustering, waste classification distribution, and automated operational recommendations.
                                </p>
                            </div>

                            <!-- Header Action Buttons -->
                            <div class="flex flex-wrap items-center gap-3 shrink-0">
                                <a href="<?php echo app_url('admin/exportAnalyticsPDF?' . ($exportQuery)); ?>"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/20 shadow-xs backdrop-blur-md transition-all active:scale-[0.98]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    Print Report View
                                </a>
                                <a href="<?php echo app_url('admin/exportAnalyticsExcel?' . ($exportQuery)); ?>"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-400 hover:bg-emerald-300 text-slate-950 font-extrabold text-xs sm:text-sm shadow-lg shadow-emerald-950/40 transition-all active:scale-[0.98]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    Export Dataset
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 2. STREAMLINED FILTER PARAMETERS & GRANULARITY MATRIX          -->
                    <!-- ============================================================== -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-center font-bold shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Filter Parameters &amp; Granularity</h2>
                                    <p class="text-xs font-semibold text-slate-500">Fine-tune temporal windows, classifications, purok sectors, and chart cadence</p>
                                </div>
                            </div>
                            
                            <!-- Quick Date Presets -->
                            <div class="flex flex-wrap items-center gap-1.5 self-start sm:self-auto">
                                <button type="button" onclick="setFilterPreset('7days')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">7 Days</button>
                                <button type="button" onclick="setFilterPreset('30days')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">30 Days</button>
                                <button type="button" onclick="setFilterPreset('thisMonth')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">This Month</button>
                                <button type="button" onclick="setFilterPreset('ytd')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">Year to Date</button>
                            </div>
                        </div>

                        <form id="analyticsFilterForm" method="GET" action="<?php echo app_url('admin/report_summaries'); ?>" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Date From -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        Date From
                                    </label>
                                    <input type="date" id="inputDateFrom" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                </div>

                                <!-- Date To -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        Date To
                                    </label>
                                    <input type="date" id="inputDateTo" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                </div>

                                <!-- Waste Category -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 6 3 18h12l3-18H3z"/><path d="M19 6V4a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        Waste Category
                                    </label>
                                    <select name="category" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                        <option value="0">All Categories</option>
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?php echo $c['category_id']; ?>" <?php echo $selectedCategory == $c['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Purok Area -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Purok Area
                                    </label>
                                    <select name="purok" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                        <option value="0">All Puroks</option>
                                        <?php foreach ($puroks as $p): ?>
                                            <option value="<?php echo $p['purok_id']; ?>" <?php echo $selectedPurok == $p['purok_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Report Status -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        Report Status
                                    </label>
                                    <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                        <option value="">All Statuses</option>
                                        <?php foreach ($statuses as $s): ?>
                                            <option value="<?php echo htmlspecialchars($s['status_name']); ?>" <?php echo $selectedStatus === $s['status_name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['status_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Estimated Quantity -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                        Estimated Quantity
                                    </label>
                                    <select name="quantity" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                        <option value="0">All Quantities</option>
                                        <?php foreach ($quantities as $q): ?>
                                            <option value="<?php echo $q['quantity_id']; ?>" <?php echo $selectedQuantity == $q['quantity_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($q['quantity_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Waste Condition -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        Waste Condition
                                    </label>
                                    <select name="condition" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                        <option value="0">All Conditions</option>
                                        <?php foreach ($conditions as $c): ?>
                                            <option value="<?php echo $c['condition_id']; ?>" <?php echo $selectedCondition == $c['condition_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['condition_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Trend Granularity -->
                                <div>
                                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                                        Trend Granularity
                                    </label>
                                    <select name="trend_granularity" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-xs sm:text-sm font-semibold text-slate-900 outline-none transition">
                                        <?php foreach (['daily' => 'Daily Intervals', 'weekly' => 'Weekly Intervals', 'monthly' => 'Monthly Intervals', 'yearly' => 'Yearly Intervals'] as $val => $label): ?>
                                            <option value="<?php echo $val; ?>" <?php echo $trendGranularity === $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Submit Buttons -->
                            <div class="flex items-center gap-3 pt-2">
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#0B2E22] hover:bg-[#084232] text-white rounded-xl font-bold text-xs sm:text-sm shadow-md transition active:scale-[0.98] cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Apply Filter Criteria
                                </button>
                                <a href="<?php echo app_url('admin/report_summaries'); ?>" class="inline-flex items-center px-4 py-2.5 border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs sm:text-sm transition cursor-pointer">
                                    Reset Filters
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 3. CORE 6 KPI METRIC CARDS                                     -->
                    <!-- ============================================================== -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
                        
                        <!-- Total Reports -->
                        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Total</span>
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200/60 group-hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                            </div>
                            <div class="my-2.5">
                                <p class="text-2xl sm:text-3xl font-black font-mono text-slate-900 tracking-tight"><?php echo number_format((int)($kpis['total'] ?? 0)); ?></p>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 font-semibold truncate">All submissions</span>
                            </div>
                        </div>

                        <!-- Pending -->
                        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold text-amber-800 uppercase tracking-wider">Pending</span>
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-200/80 group-hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                            </div>
                            <div class="my-2.5">
                                <p class="text-2xl sm:text-3xl font-black font-mono text-amber-700 tracking-tight"><?php echo number_format((int)($kpis['pending'] ?? 0)); ?></p>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                <span class="text-amber-800/80 font-semibold truncate">Awaiting review</span>
                            </div>
                        </div>

                        <!-- Verified -->
                        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold text-blue-800 uppercase tracking-wider">Verified</span>
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-200/80 group-hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                            </div>
                            <div class="my-2.5">
                                <p class="text-2xl sm:text-3xl font-black font-mono text-blue-700 tracking-tight"><?php echo number_format((int)($kpis['verified'] ?? 0)); ?></p>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                <span class="text-blue-800/80 font-semibold truncate">Validated issues</span>
                            </div>
                        </div>

                        <!-- In Progress -->
                        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold text-purple-800 uppercase tracking-wider">In Progress</span>
                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center border border-purple-200/80 group-hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                </div>
                            </div>
                            <div class="my-2.5">
                                <p class="text-2xl sm:text-3xl font-black font-mono text-purple-700 tracking-tight"><?php echo number_format((int)($kpis['in_progress'] ?? 0)); ?></p>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                <span class="text-purple-800/80 font-semibold truncate">Dispatched teams</span>
                            </div>
                        </div>

                        <!-- Resolved -->
                        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold text-emerald-800 uppercase tracking-wider">Resolved</span>
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-200/80 group-hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                            </div>
                            <div class="my-2.5">
                                <p class="text-2xl sm:text-3xl font-black font-mono text-emerald-700 tracking-tight"><?php echo number_format((int)($kpis['resolved'] ?? 0)); ?></p>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                <span class="text-emerald-800/80 font-semibold truncate">Cleaned &amp; cleared</span>
                            </div>
                        </div>

                        <!-- Hotspots -->
                        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-extrabold text-rose-800 uppercase tracking-wider">Hotspots</span>
                                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center border border-rose-200/80 group-hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                                </div>
                            </div>
                            <div class="my-2.5">
                                <p class="text-2xl sm:text-3xl font-black font-mono text-rose-700 tracking-tight"><?php echo number_format((int)($kpis['active_hotspots'] ?? 0)); ?></p>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                <span class="text-rose-800/80 font-semibold truncate">Active clusters</span>
                            </div>
                        </div>

                    </div>

                    <!-- ============================================================== -->
                    <!-- 4. REPORT VOLUME TRENDS LINE TIMELINE CHART                     -->
                    <!-- ============================================================== -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                            <div>
                                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Report Volume Trends</h2>
                                <p class="text-xs sm:text-sm font-medium text-slate-500">Chronological incident frequency and volume evolution over time</p>
                            </div>
                            <div class="flex items-center gap-2 self-start sm:self-auto">
                                <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800">
                                    <?php echo ucfirst($trendGranularity); ?> View
                                </span>
                            </div>
                        </div>
                        <div class="chart-container" style="height:260px;">
                            <canvas id="trendChart"></canvas>
                        </div>
                        <!-- 1-Sentence Interpretation -->
                        <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100 flex items-start gap-2.5 text-xs text-emerald-950">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            <p class="font-semibold leading-relaxed" id="trendSummaryText"><?php echo htmlspecialchars($trendInterpretation); ?></p>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 5. FOUR DISTRIBUTION CHARTS GRID                               -->
                    <!-- ============================================================== -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Waste Classification -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                            <div class="pb-3 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-bold text-slate-900">Waste Classification</h3>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">Category</span>
                                </div>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Volume by waste category</p>
                            </div>
                            <div class="chart-container" style="height:200px;">
                                <canvas id="categoryChart"></canvas>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2 text-[11px] text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p class="font-semibold leading-relaxed" id="categorySummaryText"><?php echo htmlspecialchars($categoryInterpretation); ?></p>
                            </div>
                        </div>

                        <!-- Status Distribution -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                            <div class="pb-3 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-bold text-slate-900">Status Distribution</h3>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">Lifecycle</span>
                                </div>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Current stage of reported cases</p>
                            </div>
                            <div class="flex justify-center items-center py-1">
                                <div class="donut-container">
                                    <canvas id="statusChart"></canvas>
                                    <div class="donut-center-text">
                                        <div class="number"><?php echo (int)($kpis['total'] ?? 0); ?></div>
                                        <div class="label">Reports</div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2 text-[11px] text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p class="font-semibold leading-relaxed" id="statusSummaryText"><?php echo htmlspecialchars($statusInterpretation); ?></p>
                            </div>
                        </div>

                        <!-- Resident vs Guest Participation -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                            <div class="pb-3 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-bold text-slate-900">Participation</h3>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800">Demographics</span>
                                </div>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Verified Residents vs Guests</p>
                            </div>
                            <div class="flex justify-center items-center py-1">
                                <div class="donut-container">
                                    <canvas id="participationChart"></canvas>
                                    <div class="donut-center-text">
                                        <div class="number text-emerald-700"><?php echo $residentPct; ?>%</div>
                                        <div class="label">Residents</div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-slate-600">
                                <span class="flex items-center gap-1.5 text-emerald-800">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    Resident: <?php echo $residentCount; ?> (<?php echo $residentPct; ?>%)
                                </span>
                                <span class="flex items-center gap-1.5 text-slate-600">
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                    Guest: <?php echo $guestCount; ?> (<?php echo $guestPct; ?>%)
                                </span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2 text-[11px] text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p class="font-semibold leading-relaxed" id="participationSummaryText"><?php echo htmlspecialchars($participationInterpretation); ?></p>
                            </div>
                        </div>

                        <!-- Waste Condition Severity -->
                        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                            <div class="pb-3 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-bold text-slate-900">Condition Severity</h3>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">Physical State</span>
                                </div>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Scattered, bagged, or hazardous</p>
                            </div>
                            <div class="chart-container" style="height:200px;">
                                <canvas id="conditionChart"></canvas>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2 text-[11px] text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p class="font-semibold leading-relaxed" id="conditionSummaryText"><?php echo htmlspecialchars($conditionInterpretation); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 6. PUROK SPATIAL ANALYSIS                                      -->
                    <!-- ============================================================== -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                            <div class="pb-3 border-b border-slate-100">
                                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Reports by Purok Zone</h2>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Incident density ranking across community puroks</p>
                            </div>
                            <div class="chart-container" style="height:240px;">
                                <canvas id="purokChart"></canvas>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2 text-xs text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p class="font-semibold leading-relaxed" id="purokSummaryText"><?php echo htmlspecialchars($purokInterpretation); ?></p>
                            </div>
                        </div>

                        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                            <div class="pb-3 border-b border-slate-100">
                                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Waste Breakdown per Purok</h2>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Stacked distribution of categories by sector</p>
                            </div>
                            <div class="chart-container" style="height:240px;">
                                <canvas id="purokStackedChart"></canvas>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2 text-xs text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p class="font-semibold leading-relaxed" id="purokStackedSummaryText"><?php echo htmlspecialchars($stackedInterpretation); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 7. HOTSPOT INTELLIGENCE & PRIORITY ZONES TABLE                 -->
                    <!-- ============================================================== -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                                    Hotspot Intelligence &amp; Priority Zones
                                </h2>
                                <p class="text-xs sm:text-sm font-medium text-slate-500">Clusters identified by high report density requiring immediate route intervention</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-800 text-xs font-extrabold border border-rose-200 self-start sm:self-auto">
                                Priority Matrix
                            </span>
                        </div>

                        <?php if (!empty($hotspotIntelligence)): ?>
                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-200 text-xs sm:text-sm">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Priority</th>
                                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Purok Area</th>
                                            <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Total Reports</th>
                                            <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Unresolved</th>
                                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Dominant Waste</th>
                                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Latest Activity</th>
                                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Recommended Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php $priority = 1; foreach ($hotspotIntelligence as $spot): ?>
                                            <?php
                                                $action = 'Monitor regular collection';
                                                $actionClass = 'bg-slate-50 text-slate-700 border-slate-200';
                                                if ($spot['report_count'] >= 10) {
                                                    $action = 'Immediate site inspection';
                                                    $actionClass = 'bg-rose-50 text-rose-800 border-rose-200';
                                                } elseif ($spot['report_count'] >= 6) {
                                                    $action = 'Schedule special truck route';
                                                    $actionClass = 'bg-amber-50 text-amber-800 border-amber-200';
                                                } elseif (($spot['unresolved_count'] ?? 0) >= 3) {
                                                    $action = 'Clear pending backlogs';
                                                    $actionClass = 'bg-orange-50 text-orange-800 border-orange-200';
                                                }
                                            ?>
                                            <tr class="hover:bg-slate-50/80 transition">
                                                <td class="px-4 py-3.5">
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl <?php echo $priority <= 2 ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-slate-100 text-slate-700 border border-slate-200'; ?> text-xs font-bold font-mono">
                                                        #<?php echo $priority++; ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3.5 font-bold text-slate-900">
                                                    <div class="flex items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                        <?php echo htmlspecialchars($spot['purok_name']); ?>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3.5 text-center font-bold text-slate-900 font-mono"><?php echo $spot['report_count']; ?></td>
                                                <td class="px-4 py-3.5 text-center">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold font-mono <?php echo ($spot['unresolved_count'] ?? 0) > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'; ?>">
                                                        <?php echo $spot['unresolved_count'] ?? 0; ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3.5 font-semibold text-slate-700"><?php echo htmlspecialchars($spot['dominant_category'] ?? 'N/A'); ?></td>
                                                <td class="px-4 py-3.5 font-medium text-slate-500"><?php echo date('M d, Y', strtotime($spot['latest_report'])); ?></td>
                                                <td class="px-4 py-3.5">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?php echo $actionClass; ?>">
                                                        <?php echo $action; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                                <p class="text-sm font-bold text-slate-500">No active hotspot clusters detected for the current filter criteria.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 8. COMMUNITY ENGAGEMENT & AUTOMATED DECISION SUPPORT           -->
                    <!-- ============================================================== -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Community Engagement -->
                        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                            <div class="pb-3 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    Community Engagement
                                </h2>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Resident participation &amp; endorsement ratio</p>
                            </div>
                            <div class="space-y-3.5 my-4">
                                <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    <span class="text-xs font-bold text-slate-600">Resident Upvotes &amp; Supports</span>
                                    <span class="text-base font-extrabold text-slate-900 font-mono"><?php echo $totalSupports; ?></span>
                                </div>
                                <div class="flex justify-between items-center p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    <span class="text-xs font-bold text-slate-600">Support-to-Report Ratio</span>
                                    <span class="text-base font-extrabold text-emerald-700 font-mono"><?php echo $supportToReportRatio; ?></span>
                                </div>
                            </div>
                            <div class="text-[11px] font-semibold text-slate-400 text-center">
                                High ratio indicates strong resident community vigilance
                            </div>
                        </div>

                        <!-- Decision Support Summary -->
                        <div class="lg:col-span-2 bg-gradient-to-br from-emerald-950 via-[#062c20] to-[#041d15] text-white rounded-3xl border border-emerald-800/40 p-6 sm:p-7 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-emerald-800/40 mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-400/30 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-base font-bold text-white leading-tight">Automated Decision Support Summary</h2>
                                            <p class="text-xs text-emerald-200/70 font-medium">Strategic intelligence derived from active dataset parameters</p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[11px] font-bold border border-emerald-400/30">AI Guidance</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                                    <?php if (!empty($decisionSupport['highest_hotspot'])): ?>
                                        <div class="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 flex items-start gap-3">
                                            <div class="w-7 h-7 rounded-lg bg-rose-500/20 border border-rose-400/30 text-rose-300 flex items-center justify-center shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-white">Critical Cluster Focus</p>
                                                <p class="text-emerald-100/80 font-medium mt-0.5"><?php echo htmlspecialchars($decisionSupport['highest_hotspot']['purok_name']); ?> leads with <strong class="text-white"><?php echo $decisionSupport['highest_hotspot']['report_count']; ?> incidents</strong> (Dominant: <?php echo htmlspecialchars($topCatName); ?>).</p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 flex items-start gap-3">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-white">Even Distribution</p>
                                                <p class="text-emerald-100/80 font-medium mt-0.5">No acute spatial clusters identified. Standard patrol routes remain effective.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">Trend Trajectory</p>
                                            <p class="text-emerald-100/80 font-medium mt-0.5">
                                                Incident frequency is <?php echo ($decisionSupport['trend_increasing'] ?? false) ? '<strong class="text-rose-300 font-bold">trending upward</strong> — deploy additional collection frequency.' : '<strong class="text-emerald-300 font-bold">stable or decreasing</strong> — maintain existing schedule.'; ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-amber-500/20 border border-amber-400/30 text-amber-300 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">Resource Allocation</p>
                                            <p class="text-emerald-100/80 font-medium mt-0.5">Prioritize dedicated vehicles for <strong class="text-white"><?php echo htmlspecialchars($topCatName); ?></strong> handling in <strong class="text-white"><?php echo htmlspecialchars($topPurokName); ?></strong>.</p>
                                        </div>
                                    </div>

                                    <div class="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-sky-500/20 border border-sky-400/30 text-sky-300 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">Community Action</p>
                                            <p class="text-emerald-100/80 font-medium mt-0.5"><strong class="text-white"><?php echo $resolutionRateVal; ?>%</strong> resolution rate; resident engagement at <strong class="text-white"><?php echo $residentPct; ?>%</strong>.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 mt-4 border-t border-emerald-800/40 flex items-center justify-between text-[11px] text-emerald-200/60 font-medium">
                                <span>Optimal dispatch scheduled based on active purok priorities</span>
                                <span>Updated real-time</span>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 9. FILTERED CASE RECORDS TABLE (WITH LIVE FILTER & PAGINATION) -->
                    <!-- ============================================================== -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-4 p-6 sm:p-7">
                        <!-- Top Row: Title, Records Count & Export Buttons -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Filtered Case Records</h2>
                                    <span id="caseRecordCountBadge" class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold font-mono">
                                        <?php echo count($filteredReports); ?> records
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">Individual incident logs satisfying the current filter criteria</p>
                            </div>

                            <!-- Export Buttons -->
                            <div class="flex items-center gap-2 self-start sm:self-auto">
                                <a href="<?php echo app_url('admin/exportReportSummaryXLSX?' . ($exportQuery)); ?>"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold text-xs shadow-xs transition active:scale-[0.98]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    CSV
                                </a>
                                <a href="<?php echo app_url('admin/exportReportSummaryPDF?' . ($exportQuery)); ?>"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs shadow-xs transition active:scale-[0.98]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    PDF
                                </a>
                            </div>
                        </div>

                        <!-- Filter Controls Row: Status Filter Pills, Live Search, Per Page -->
                        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 pt-1">
                            <!-- Status Filter Pills -->
                            <div class="flex flex-wrap items-center bg-slate-100 p-1 rounded-xl gap-1">
                                <button type="button" onclick="filterTableRows('all', this)" class="table-filter-btn active px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-slate-900 shadow-xs transition cursor-pointer">All</button>
                                <button type="button" onclick="filterTableRows('Pending', this)" class="table-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition cursor-pointer">Pending</button>
                                <button type="button" onclick="filterTableRows('Verified', this)" class="table-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition cursor-pointer">Verified</button>
                                <button type="button" onclick="filterTableRows('In Progress', this)" class="table-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition cursor-pointer">In Progress</button>
                                <button type="button" onclick="filterTableRows('Resolved', this)" class="table-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-900 transition cursor-pointer">Resolved</button>
                            </div>

                            <!-- Right Controls: Live Search + Per Page -->
                            <div class="flex flex-wrap items-center gap-2.5 justify-start lg:justify-end">
                                <!-- Live Instant Search Input -->
                                <div class="relative w-full sm:w-64">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    </div>
                                    <input type="text" id="caseSearchInput" onkeyup="onCaseSearchChange()" placeholder="Live search reports..." 
                                           class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 outline-none transition">
                                </div>

                                <!-- Per Page Selector -->
                                <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5">
                                    <span class="text-[11px] font-bold text-slate-500">Show:</span>
                                    <select id="casePerPageSelect" onchange="changeCasePerPage(this.value)" class="bg-transparent text-xs font-bold text-slate-800 outline-none cursor-pointer">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="all">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php if (empty($filteredReports)): ?>
                            <div class="p-12 text-center bg-slate-50 rounded-2xl border border-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                <p class="text-sm font-bold text-slate-600">No reports match the current filter parameters.</p>
                                <p class="text-xs text-slate-400 mt-1">Try broadening your date range or selecting all categories.</p>
                            </div>
                        <?php else: ?>
                            <div class="rounded-2xl border border-slate-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200 text-xs sm:text-sm">
                                        <thead class="bg-slate-50/80">
                                            <tr>
                                                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Report ID</th>
                                                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Resident / Reporter</th>
                                                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Waste Category</th>
                                                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Purok Area</th>
                                                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Exact Location</th>
                                                <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">Status</th>
                                                <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Reported Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="caseRecordsTableBody" class="bg-white divide-y divide-slate-100">
                                            <?php foreach ($filteredReports as $report): ?>
                                                <?php 
                                                    $cfg = statusBadgeConfig($report['status']); 
                                                    $reportCode = '#' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);
                                                    $repName = $report['name'] ?? 'Anonymous';
                                                    $catName = $report['waste_category'] ?? 'N/A';
                                                    $pkName = $report['purok'] ?? 'N/A';
                                                    $locText = $report['location'] ?? '—';
                                                    $statText = $report['status'] ?? '';
                                                    $dateFormatted = date('M d, Y', strtotime($report['submission_date']));
                                                    $searchText = strtolower("{$reportCode} {$repName} {$catName} {$pkName} {$locText} {$statText} {$dateFormatted}");
                                                ?>
                                                <tr class="case-record-row hover:bg-slate-50/80 transition" 
                                                    data-status="<?php echo htmlspecialchars($report['status']); ?>"
                                                    data-search-text="<?php echo htmlspecialchars($searchText); ?>">
                                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-700">
                                                        <a href="<?php echo app_url('admin/viewReport/' . ($report['id'])); ?>" class="hover:text-emerald-600 transition">
                                                            <?php echo $reportCode; ?>
                                                        </a>
                                                    </td>
                                                    <td class="px-4 py-3.5 font-bold text-slate-900">
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold flex items-center justify-center shrink-0">
                                                                <?php echo strtoupper(substr($repName, 0, 1)); ?>
                                                            </div>
                                                            <span class="truncate"><?php echo htmlspecialchars($repName); ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3.5 font-semibold text-slate-700"><?php echo htmlspecialchars($catName); ?></td>
                                                    <td class="px-4 py-3.5 font-semibold text-slate-700">
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                            <?php echo htmlspecialchars($pkName); ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3.5 font-normal text-slate-600 truncate max-w-xs" title="<?php echo htmlspecialchars($locText); ?>">
                                                        <?php echo htmlspecialchars($locText); ?>
                                                    </td>
                                                    <td class="px-4 py-3.5 text-center">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border <?php echo $cfg['class']; ?>">
                                                            <span class="w-1.5 h-1.5 rounded-full <?php echo $cfg['dot']; ?>"></span>
                                                            <?php echo htmlspecialchars($report['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3.5 font-medium text-slate-500"><?php echo $dateFormatted; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr id="noCaseRecordsRow" style="display: none;">
                                                <td colspan="7" class="py-12 text-center text-slate-400 font-medium">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                                    No case records found matching your filters.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination Footer Bar (Matching reports.php) -->
                                <div id="casePaginationBar" class="p-4 bg-slate-50/90 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                                    <div class="text-slate-600 font-semibold" id="casePaginationInfo">
                                        Showing <span id="casePageStart" class="font-bold text-slate-900">0</span> to <span id="casePageEnd" class="font-bold text-slate-900">0</span> of <span id="casePageTotal" class="font-bold text-slate-900">0</span> entries
                                    </div>
                                    
                                    <div class="flex items-center gap-1" id="casePaginationControls">
                                        <!-- Dynamic pagination buttons -->
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- ============================================================== -->
                    <!-- 10. RECENT EXPORT ARCHIVES                                     -->
                    <!-- ============================================================== -->
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Recent Export Archives</h2>
                                    <p class="text-xs font-medium text-slate-500">Historical logs of generated analytics summaries</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-400 font-mono"><?php echo count($exports); ?> archives</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php if (!empty($exports)): ?>
                                <?php foreach ($exports as $export): ?>
                                    <div class="flex items-center justify-between p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200 hover:border-slate-300 transition">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-xl <?php echo (strtoupper($export['file_type'] ?? '') === 'PDF') ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'; ?> flex items-center justify-center font-bold text-[10px] shrink-0 font-mono">
                                                <?php echo strtoupper(htmlspecialchars($export['file_type'] ?? 'DOC')); ?>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 text-xs sm:text-sm truncate"><?php echo htmlspecialchars($export['filename']); ?></p>
                                                <p class="text-[11px] font-medium text-slate-500 mt-0.5">
                                                    <?php echo date('M d, Y • h:i A', strtotime($export['generated_at'])); ?> &bull; <?php echo (int)($export['total_reports'] ?? 0); ?> records
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-slate-500 font-bold text-xs py-4 text-center col-span-full">No export archives generated yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- JAVASCRIPT: CHARTS, QUICK PRESETS, & TABLE FILTERING           -->
<!-- ============================================================== -->
<script>
function setFilterPreset(preset) {
    const today = new Date();
    const formatDate = (d) => d.toISOString().split('T')[0];
    
    let fromDate = new Date();
    let toDate = new Date();
    
    if (preset === '7days') {
        fromDate.setDate(today.getDate() - 7);
    } else if (preset === '30days') {
        fromDate.setDate(today.getDate() - 30);
    } else if (preset === 'thisMonth') {
        fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
    } else if (preset === 'ytd') {
        fromDate = new Date(today.getFullYear(), 0, 1);
    }
    
    document.getElementById('inputDateFrom').value = formatDate(fromDate);
    document.getElementById('inputDateTo').value = formatDate(toDate);
    document.getElementById('analyticsFilterForm').submit();
}

// Case Records Table Pagination & Live Filtering (Matching reports.php)
let currentCaseStatusFilter = 'all';
let currentCasePage = 1;
let casePerPage = 10;

function filterTableRows(status, btnElement) {
    currentCaseStatusFilter = status;
    currentCasePage = 1;
    
    const buttons = document.querySelectorAll('.table-filter-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-white', 'text-slate-900', 'shadow-xs');
        btn.classList.add('text-slate-600');
    });
    
    const activeBtn = btnElement || (typeof event !== 'undefined' && event ? event.currentTarget || event.target : null);
    if (activeBtn) {
        activeBtn.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-xs');
        activeBtn.classList.remove('text-slate-600');
    }
    
    applyCasePagination();
}

function changeCasePerPage(val) {
    casePerPage = (val === 'all') ? 999999 : parseInt(val, 10);
    currentCasePage = 1;
    applyCasePagination();
}

function onCaseSearchChange() {
    currentCasePage = 1;
    applyCasePagination();
}

function goToCasePage(page) {
    currentCasePage = page;
    applyCasePagination();
}

function applyCasePagination() {
    const searchInput = (document.getElementById('caseSearchInput')?.value || '').toLowerCase().trim();
    const allRows = Array.from(document.querySelectorAll('.case-record-row'));
    const noRecordsRow = document.getElementById('noCaseRecordsRow');

    const matchingRows = allRows.filter(row => {
        const rowStatus = (row.getAttribute('data-status') || '').trim();
        const rowSearchText = (row.getAttribute('data-search-text') || row.textContent).toLowerCase();
        const matchesStatus = (currentCaseStatusFilter === 'all' || rowStatus.toLowerCase() === currentCaseStatusFilter.toLowerCase());
        const matchesSearch = (!searchInput || rowSearchText.includes(searchInput));
        return matchesStatus && matchesSearch;
    });

    const totalMatching = matchingRows.length;
    const totalPages = Math.max(1, Math.ceil(totalMatching / casePerPage));

    if (currentCasePage > totalPages) currentCasePage = totalPages;
    if (currentCasePage < 1) currentCasePage = 1;

    const startIdx = (currentCasePage - 1) * casePerPage;
    const endIdx = Math.min(startIdx + casePerPage, totalMatching);

    allRows.forEach(row => row.style.display = 'none');
    matchingRows.slice(startIdx, endIdx).forEach(row => { row.style.display = ''; });

    if (noRecordsRow) {
        noRecordsRow.style.display = (totalMatching === 0) ? '' : 'none';
    }

    const countBadge = document.getElementById('caseRecordCountBadge');
    if (countBadge) {
        countBadge.innerText = totalMatching + ' records';
    }

    const pageStartEl = document.getElementById('casePageStart');
    const pageEndEl = document.getElementById('casePageEnd');
    const pageTotalEl = document.getElementById('casePageTotal');

    if (pageStartEl) pageStartEl.textContent = totalMatching > 0 ? (startIdx + 1) : 0;
    if (pageEndEl) pageEndEl.textContent = endIdx;
    if (pageTotalEl) pageTotalEl.textContent = totalMatching;

    renderCasePaginationControls(totalPages);
}

function renderCasePaginationControls(totalPages) {
    const container = document.getElementById('casePaginationControls');
    if (!container) return;

    if (totalPages <= 1 && casePerPage >= 999999) {
        container.innerHTML = '';
        return;
    }

    let html = '';
    const prevDisabled = (currentCasePage <= 1);
    html += `
        <button type="button" onclick="goToCasePage(${currentCasePage - 1})" ${prevDisabled ? 'disabled' : ''} 
                class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${prevDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            <span>Prev</span>
        </button>
    `;

    let startPage = Math.max(1, currentCasePage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }

    if (startPage > 1) {
        html += `<button type="button" onclick="goToCasePage(1)" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">1</button>`;
        if (startPage > 2) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
    }

    for (let p = startPage; p <= endPage; p++) {
        const isActive = (p === currentCasePage);
        html += `
            <button type="button" onclick="goToCasePage(${p})" 
                    class="w-8 h-8 rounded-lg text-xs font-bold transition cursor-pointer ${isActive ? 'bg-[#0B2E22] text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'}">
                ${p}
            </button>
        `;
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
        html += `<button type="button" onclick="goToCasePage(${totalPages})" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">${totalPages}</button>`;
    }

    const nextDisabled = (currentCasePage >= totalPages);
    html += `
        <button type="button" onclick="goToCasePage(${currentCasePage + 1})" ${nextDisabled ? 'disabled' : ''} 
                class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${nextDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
            <span>Next</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
    `;

    container.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Miranda Sans', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Trend Line Chart
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
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, font: { weight: '600' } }, 
                        grid: { color: '#f1f5f9' } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { weight: '600' } } 
                    }
                }
            }
        });
    }

    // 2. Category Bar Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx && <?php echo json_encode(!empty($categoryValues)); ?>) {
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categoryLabels); ?>,
                datasets: [{
                    label: 'Reports',
                    data: <?php echo json_encode(array_map('intval', $categoryValues)); ?>,
                    backgroundColor: '#10b981',
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

    // 3. Status Doughnut Chart
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
                    borderColor: '#ffffff'
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

    // 3.5 Resident vs Guest Participation Doughnut Chart
    const participationCtx = document.getElementById('participationChart');
    if (participationCtx) {
        new Chart(participationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Registered Residents', 'Anonymous Guests'],
                datasets: [{
                    data: [<?php echo (int)$residentCount; ?>, <?php echo (int)$guestCount; ?>],
                    backgroundColor: ['#10b981', '#94a3b8'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
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

    // 4. Waste Condition Severity Pie Chart
    const conditionCtx = document.getElementById('conditionChart');
    if (conditionCtx && <?php echo json_encode(!empty($conditionValues)); ?>) {
        const colors = ['#10b981', '#f59e0b', '#0284c7', '#ef4444', '#8b5cf6', '#ec4899', '#f97316'];
        new Chart(conditionCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($conditionLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_map('intval', $conditionValues)); ?>,
                    backgroundColor: colors.slice(0, <?php echo count($conditionValues); ?>),
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'right', 
                        labels: { boxWidth: 10, font: { size: 10, weight: '600' } } 
                    } 
                }
            }
        });
    }

    // 5. Purok Density Bar Chart
    const purokCtx = document.getElementById('purokChart');
    if (purokCtx && <?php echo json_encode(!empty($purokValues)); ?>) {
        new Chart(purokCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($purokLabels); ?>,
                datasets: [{
                    label: 'Reports',
                    data: <?php echo json_encode(array_map('intval', $purokValues)); ?>,
                    backgroundColor: '#10b981',
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

    // 6. Purok Stacked Category Breakdown Chart
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
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { boxWidth: 10, font: { size: 10, weight: '600' } } 
                    } 
                },
                scales: {
                    x: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } },
                    y: { stacked: true, grid: { display: false }, ticks: { font: { weight: '600' } } }
                }
            }
        });
    }
    
    // Initialize case records table pagination on DOM ready
    applyCasePagination();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

