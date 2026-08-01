<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$kpis = $data['kpis'] ?? [];
$trendData = $data['trend_data'] ?? [];
$categoryData = $data['category_data'] ?? [];
$statusData = $data['status_data'] ?? [];
$conditionData = $data['condition_data'] ?? [];
$purokData = $data['purok_data'] ?? [];
$hotspotIntelligence = $data['hotspot_intelligence'] ?? [];
$totalSupports = $data['total_supports'] ?? 0;
$supportToReportRatio = $data['support_to_report_ratio'] ?? 0;
$avgResolutionHours = $data['avg_resolution_hours'] ?? 0;
$fastestResolution = $data['fastest_resolution'] ?? 0;
$longestResolution = $data['longest_resolution'] ?? 0;
$trendComparison = $data['trend_comparison'] ?? [];
$decisionSupport = $data['decision_support'] ?? [];
$dateFrom = $data['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
$dateTo = $data['date_to'] ?? date('Y-m-d');
$categories = $data['categories'] ?? [];
$puroks = $data['puroks'] ?? [];
$statuses = $data['statuses'] ?? [];
$selectedCategory = $data['selected_category'] ?? 0;
$selectedPurok = $data['selected_purok'] ?? 0;
$selectedStatus = $data['selected_status'] ?? '';

// Prepare chart data
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
?>

<style>
    .chart-container { position: relative; height: 250px; width: 100%; }
    .chart-container canvas { width: 100% !important; height: 100% !important; }
    .kpi-card { transition: all 0.2s ease; }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }
    .donut-container { position: relative; max-width: 140px; max-height: 140px; margin: 0 auto; }
    .donut-container canvas { width: 100% !important; height: 100% !important; }
    .donut-center-text {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        text-align: center; pointer-events: none; line-height: 1.15;
    }
    .donut-center-text .number { font-size: 1.2rem; font-weight: 700; color: #0f172a; }
    .donut-center-text .label { font-size: 0.5rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
    .trend-up { color: #10B981; }
    .trend-down { color: #EF4444; }
</style>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight truncate">Analytics & Insights</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">Waste reporting statistics and decision support</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="/brgy-waste-app-v3/public/supervisor/exportAnalyticsPDF?<?php echo http_build_query($_GET); ?>" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-100 transition" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Open Print View
                    </a>
                    <a href="/brgy-waste-app-v3/public/supervisor/exportAnalyticsExcel?<?php echo http_build_query($_GET); ?>" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg hover:bg-emerald-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Filter Bar -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
                        <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Date From</label>
                                <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Date To</label>
                                <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Category</label>
                                <select name="category" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                    <option value="0">All Categories</option>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?php echo $c['category_id']; ?>" <?php echo $selectedCategory == $c['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Purok</label>
                                <select name="purok" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                    <option value="0">All Puroks</option>
                                    <?php foreach ($puroks as $p): ?>
                                        <option value="<?php echo $p['purok_id']; ?>" <?php echo $selectedPurok == $p['purok_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                                <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                    <option value="">All Status</option>
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?php echo $s['status_name']; ?>" <?php echo $selectedStatus == $s['status_name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['status_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex items-end gap-2 col-span-full sm:col-span-1">
                                <button type="submit" class="w-full rounded-xl bg-[#10B981] hover:bg-emerald-600 text-white font-semibold px-4 py-2 text-sm transition">Apply</button>
                                <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/analytics" class="w-full rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold px-4 py-2 text-sm text-center transition">Clear</a>
                            </div>
                        </form>
                    </div>

                    <!-- KPI Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-6">
                        <div class="kpi-card bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-xs text-slate-500 font-medium">Total Reports</p>
                            <p class="text-2xl font-black text-slate-900 mt-1"><?php echo $kpis['total'] ?? 0; ?></p>
                        </div>
                        <div class="kpi-card bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-xs text-amber-600 font-medium">Pending</p>
                            <p class="text-2xl font-black text-amber-600 mt-1"><?php echo $kpis['pending'] ?? 0; ?></p>
                        </div>
                        <div class="kpi-card bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-xs text-emerald-600 font-medium">Resolved</p>
                            <p class="text-2xl font-black text-emerald-600 mt-1"><?php echo $kpis['resolved'] ?? 0; ?></p>
                        </div>
                        <div class="kpi-card bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-xs text-slate-500 font-medium">Resolution Rate</p>
                            <p class="text-2xl font-black text-slate-900 mt-1"><?php echo ($kpis['resolution_rate'] ?? 0); ?>%</p>
                        </div>
                        <div class="kpi-card bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-xs text-red-600 font-medium">Active Hotspots</p>
                            <p class="text-2xl font-black text-red-600 mt-1"><?php echo $kpis['active_hotspots'] ?? 0; ?></p>
                        </div>
                    </div>

                    <!-- Report Trends -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
                        <h3 class="text-sm font-bold text-slate-900 mb-4">Report Trends</h3>
                        <div class="chart-container">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>

                    <!-- Distribution Charts (3 columns) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Category Distribution -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                            <h3 class="text-sm font-bold text-slate-900 mb-3">By Category</h3>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                        <!-- Status Distribution -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                            <h3 class="text-sm font-bold text-slate-900 mb-3">By Status</h3>
                            <div class="flex justify-center">
                                <div class="donut-container">
                                    <canvas id="statusChart"></canvas>
                                    <div class="donut-center-text">
                                        <div class="number"><?php echo $kpis['total'] ?? 0; ?></div>
                                        <div class="label">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Condition Distribution -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                            <h3 class="text-sm font-bold text-slate-900 mb-3">By Waste Condition</h3>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="conditionChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Purok Analysis -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
                        <h3 class="text-sm font-bold text-slate-900 mb-4">Purok Analysis</h3>
                        <div class="chart-container" style="height: 220px;">
                            <canvas id="purokChart"></canvas>
                        </div>
                    </div>

                    <!-- Hotspot Intelligence -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
                        <h3 class="text-sm font-bold text-slate-900 mb-4">Hotspot Intelligence</h3>
                        <?php if (!empty($hotspotIntelligence)): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Priority</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Purok</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Reports</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dominant Category</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Latest Report</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Suggested Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php $priority = 1; ?>
                                        <?php foreach ($hotspotIntelligence as $spot): ?>
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-2 font-bold">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full <?php echo $priority <= 2 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'; ?> text-xs font-bold">
                                                        <?php echo $priority++; ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 font-semibold text-slate-800"><?php echo htmlspecialchars($spot['purok_name']); ?></td>
                                                <td class="px-4 py-2 text-slate-600"><?php echo $spot['report_count']; ?></td>
                                                <td class="px-4 py-2 text-slate-600"><?php echo htmlspecialchars($spot['dominant_category'] ?? 'N/A'); ?></td>
                                                <td class="px-4 py-2 text-slate-500"><?php echo date('M d, Y', strtotime($spot['latest_report'])); ?></td>
                                                <td class="px-4 py-2 text-xs">
                                                    <?php
                                                        $action = 'Monitor area';
                                                        if ($spot['report_count'] >= 10) $action = 'Immediate site inspection';
                                                        elseif ($spot['report_count'] >= 6) $action = 'Schedule collection review';
                                                        echo '<span class="inline-flex rounded-full px-2 py-1 bg-emerald-50 text-emerald-700 font-semibold">' . $action . '</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-slate-500 text-center py-4">No hotspot data available for the selected period.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Community & Operational Performance -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                            <h3 class="text-sm font-bold text-slate-900 mb-4">Community Participation</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                    <span class="text-slate-600">Total Supports</span>
                                    <span class="font-bold text-slate-900"><?php echo $totalSupports; ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                    <span class="text-slate-600">Support-to-Report Ratio</span>
                                    <span class="font-bold text-slate-900"><?php echo $supportToReportRatio; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                            <h3 class="text-sm font-bold text-slate-900 mb-4">Operational Performance</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                    <span class="text-slate-600">Average Resolution Time</span>
                                    <span class="font-bold text-slate-900"><?php echo $avgResolutionHours; ?> hrs</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                    <span class="text-slate-600">Fastest Resolution</span>
                                    <span class="font-bold text-emerald-600"><?php echo $fastestResolution; ?> hrs</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-slate-600">Longest Resolution</span>
                                    <span class="font-bold text-red-600"><?php echo $longestResolution; ?> hrs</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trend Comparison -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
                        <h3 class="text-sm font-bold text-slate-900 mb-4">Trend Comparison</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <?php
                                $totalChange = $trendComparison['total_reports']['change'] ?? 0;
                                $resolutionChange = $trendComparison['resolution_rate']['change'] ?? 0;
                            ?>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs text-slate-500">Total Reports</p>
                                <p class="text-lg font-bold text-slate-900 mt-1"><?php echo $trendComparison['total_reports']['current'] ?? 0; ?></p>
                                <p class="text-xs <?php echo $totalChange >= 0 ? 'text-emerald-600' : 'text-red-600'; ?>">
                                    <?php echo $totalChange >= 0 ? '↑' : '↓'; ?> <?php echo abs($totalChange); ?>% vs previous
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs text-slate-500">Resolution Rate</p>
                                <p class="text-lg font-bold text-slate-900 mt-1"><?php echo $trendComparison['resolution_rate']['current'] ?? 0; ?>%</p>
                                <p class="text-xs <?php echo $resolutionChange >= 0 ? 'text-emerald-600' : 'text-red-600'; ?>">
                                    <?php echo $resolutionChange >= 0 ? '↑' : '↓'; ?> <?php echo abs($resolutionChange); ?>% vs previous
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs text-slate-500">Active Hotspots</p>
                                <p class="text-lg font-bold text-slate-900 mt-1"><?php echo $kpis['active_hotspots'] ?? 0; ?></p>
                                <p class="text-xs text-slate-400">Current period</p>
                            </div>
                        </div>
                    </div>

                    <!-- Decision Support -->
                    <div class="bg-gradient-to-r from-emerald-50 to-emerald-100/40 rounded-2xl border border-emerald-200 p-6">
                        <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                            Decision Support Summary
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <?php if (!empty($decisionSupport['highest_hotspot'])): ?>
                                <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                                    <span class="text-red-600 mt-0.5">⚠️</span>
                                    <div>
                                        <p class="font-semibold text-slate-800">Highest‑priority hotspot</p>
                                        <p class="text-slate-600"><?php echo htmlspecialchars($decisionSupport['highest_hotspot']['purok_name']); ?> – <?php echo $decisionSupport['highest_hotspot']['report_count']; ?> reports</p>
                                        <p class="text-xs text-slate-500 mt-1">Immediate action recommended</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($decisionSupport['emerging_hotspot'])): ?>
                                <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                                    <span class="text-amber-600 mt-0.5">🆕</span>
                                    <div>
                                        <p class="font-semibold text-slate-800">Emerging hotspot</p>
                                        <p class="text-slate-600"><?php echo htmlspecialchars($decisionSupport['emerging_hotspot']['purok_name']); ?> – <?php echo $decisionSupport['emerging_hotspot']['report_count']; ?> reports</p>
                                        <p class="text-xs text-slate-500 mt-1">Monitor closely</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                                <span class="text-blue-600 mt-0.5">📈</span>
                                <div>
                                    <p class="font-semibold text-slate-800">Trend analysis</p>
                                    <p class="text-slate-600">
                                        <?php if ($decisionSupport['trend_increasing'] ?? false): ?>
                                            Reports are <span class="text-red-600 font-semibold">increasing</span> month over month
                                        <?php else: ?>
                                            Reports are <span class="text-emerald-600 font-semibold">stable or decreasing</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                                <span class="text-purple-600 mt-0.5">💡</span>
                                <div>
                                    <p class="font-semibold text-slate-800">Recommended interventions</p>
                                    <p class="text-slate-600 text-xs">
                                        <?php
                                            if ($totalSupports > 0 && $avgResolutionHours > 48) {
                                                echo 'Prioritize collection scheduling in high-density areas.';
                                            } elseif ($avgResolutionHours > 24) {
                                                echo 'Improve response time – consider additional collection routes.';
                                            } else {
                                                echo 'Continue current operations – performance is satisfactory.';
                                            }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Trend Chart (Line)
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx && <?php echo json_encode(!empty($trendValues)); ?>) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($trendLabels); ?>,
                datasets: [{
                    label: 'Reports',
                    data: <?php echo json_encode($trendValues); ?>,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                }
            }
        });
    }

    // Category Chart (Bar)
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx && <?php echo json_encode(!empty($categoryValues)); ?>) {
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categoryLabels); ?>,
                datasets: [{
                    label: 'Reports',
                    data: <?php echo json_encode($categoryValues); ?>,
                    backgroundColor: '#10B981',
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 8 } } }
                }
            }
        });
    }

    // Status Chart (Donut)
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx && <?php echo json_encode(!empty($statusValues)); ?>) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($statusLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($statusValues); ?>,
                    backgroundColor: <?php echo json_encode($statusColors); ?>,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Condition Chart (Pie)
    const conditionCtx = document.getElementById('conditionChart');
    if (conditionCtx && <?php echo json_encode(!empty($conditionValues)); ?>) {
        const colors = ['#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#8B5CF6', '#EC4899', '#F97316'];
        new Chart(conditionCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($conditionLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($conditionValues); ?>,
                    backgroundColor: colors.slice(0, <?php echo count($conditionValues); ?>),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 10, font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Purok Chart (Horizontal Bar)
    const purokCtx = document.getElementById('purokChart');
    if (purokCtx && <?php echo json_encode(!empty($purokValues)); ?>) {
        new Chart(purokCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($purokLabels); ?>,
                datasets: [{
                    label: 'Reports',
                    data: <?php echo json_encode($purokValues); ?>,
                    backgroundColor: '#10B981',
                    borderColor: '#059669',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#f1f5f9' } },
                    y: { grid: { display: false }, ticks: { font: { size: 9 } } }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>