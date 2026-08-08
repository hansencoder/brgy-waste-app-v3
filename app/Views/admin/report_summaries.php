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
        'Pending' => 'bg-amber-50 text-amber-600',
        'Verified' => 'bg-blue-50 text-blue-600',
        'In Progress' => 'bg-orange-50 text-orange-600',
        'Resolved' => 'bg-emerald-50 text-emerald-600',
        'Rejected' => 'bg-red-50 text-red-600',
    ];
    return $map[$status] ?? 'bg-gray-50 text-gray-600';
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

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
</style>

<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <!-- Page Header -->
                <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Statistics &amp; Analytics</h1>
                        <p class="mt-1 text-sm text-gray-600">Waste reporting statistics, trends, and decision support</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="/brgy-waste-app-v3/public/admin/exportAnalyticsPDF?<?php echo $exportQuery; ?>"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg font-semibold text-sm hover:bg-red-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Open Print View
                        </a>
                        <a href="/brgy-waste-app-v3/public/admin/exportAnalyticsExcel?<?php echo $exportQuery; ?>"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-sm hover:bg-emerald-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export Excel
                        </a>
                    </div>
                </div>

                <!-- Filter Panel -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
                    <div class="flex items-center gap-2 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-700"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        <h2 class="text-lg font-bold text-gray-900">Filter Analytics</h2>
                    </div>
                    <form method="GET" action="/brgy-waste-app-v3/public/admin/report_summaries" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                            <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                            <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <option value="0">All Categories</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo $c['category_id']; ?>" <?php echo $selectedCategory == $c['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purok</label>
                            <select name="purok" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <option value="0">All Puroks</option>
                                <?php foreach ($puroks as $p): ?>
                                    <option value="<?php echo $p['purok_id']; ?>" <?php echo $selectedPurok == $p['purok_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <option value="">All Status</option>
                                <?php foreach ($statuses as $s): ?>
                                    <option value="<?php echo htmlspecialchars($s['status_name']); ?>" <?php echo $selectedStatus === $s['status_name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['status_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Quantity</label>
                            <select name="quantity" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <option value="0">All Quantities</option>
                                <?php foreach ($quantities as $q): ?>
                                    <option value="<?php echo $q['quantity_id']; ?>" <?php echo $selectedQuantity == $q['quantity_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($q['quantity_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waste Condition</label>
                            <select name="condition" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <option value="0">All Conditions</option>
                                <?php foreach ($conditions as $c): ?>
                                    <option value="<?php echo $c['condition_id']; ?>" <?php echo $selectedCondition == $c['condition_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['condition_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trend Granularity</label>
                            <select name="trend_granularity" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <?php foreach (['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $val => $label): ?>
                                    <option value="<?php echo $val; ?>" <?php echo $trendGranularity === $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-end gap-2 col-span-full sm:col-span-2 lg:col-span-4">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#118B50] hover:bg-[#15281f] text-white rounded-md font-medium text-sm shadow-sm transition-colors">Apply Filters</button>
                            <a href="/brgy-waste-app-v3/public/admin/report_summaries" class="inline-flex items-center px-6 py-2.5 border border-gray-300 text-gray-700 rounded-md font-medium text-sm hover:bg-gray-50 transition-colors">Clear</a>
                        </div>
                    </form>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-8 gap-4 mb-8">
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-500 font-medium">Total</p>
                        <p class="text-2xl font-black text-gray-900 mt-1"><?php echo $kpis['total'] ?? 0; ?></p>
                    </div>
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-amber-600 font-medium">Pending</p>
                        <p class="text-2xl font-black text-amber-600 mt-1"><?php echo $kpis['pending'] ?? 0; ?></p>
                    </div>
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-blue-600 font-medium">Verified</p>
                        <p class="text-2xl font-black text-blue-600 mt-1"><?php echo $kpis['verified'] ?? 0; ?></p>
                    </div>
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-orange-600 font-medium">In Progress</p>
                        <p class="text-2xl font-black text-orange-600 mt-1"><?php echo $kpis['in_progress'] ?? 0; ?></p>
                    </div>
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-emerald-600 font-medium">Resolved</p>
                        <p class="text-2xl font-black text-emerald-600 mt-1"><?php echo $kpis['resolved'] ?? 0; ?></p>
                    </div>
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-500 font-medium">Resolution Rate</p>
                        <p class="text-2xl font-black text-gray-900 mt-1"><?php echo $kpis['resolution_rate'] ?? 0; ?>%</p>
                    </div>
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-500 font-medium">Avg Resolution</p>
                        <p class="text-2xl font-black text-gray-900 mt-1"><?php echo $avgResolutionHours; ?><span class="text-sm font-normal"> hrs</span></p>
                    </div>
                    <div class="kpi-card bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-red-600 font-medium">Hotspots</p>
                        <p class="text-2xl font-black text-red-600 mt-1"><?php echo $kpis['active_hotspots'] ?? 0; ?></p>
                    </div>
                </div>

                <!-- Report Trends -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Report Trends</h2>
                    <div class="chart-container">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Distribution Charts -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">By Category</h3>
                        <div class="chart-container" style="height:200px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">By Status</h3>
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
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">By Waste Condition</h3>
                        <div class="chart-container" style="height:200px;">
                            <canvas id="conditionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Purok Analysis -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Purok Analysis</h2>
                        <div class="chart-container" style="height:220px;">
                            <canvas id="purokChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Category by Purok</h2>
                        <div class="chart-container" style="height:220px;">
                            <canvas id="purokStackedChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Hotspot Intelligence -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Hotspot Intelligence</h2>
                    <?php if (!empty($hotspotIntelligence)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purok</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reports</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unresolved</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dominant Category</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Latest Report</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Suggested Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php $priority = 1; foreach ($hotspotIntelligence as $spot): ?>
                                        <?php
                                            $action = 'Monitor area';
                                            if ($spot['report_count'] >= 10) $action = 'Immediate site inspection';
                                            elseif ($spot['report_count'] >= 6) $action = 'Schedule collection review';
                                            elseif (($spot['unresolved_count'] ?? 0) >= 3) $action = 'Prioritize unresolved reports';
                                        ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full <?php echo $priority <= 2 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'; ?> text-xs font-bold"><?php echo $priority++; ?></span>
                                            </td>
                                            <td class="px-4 py-3 font-semibold text-gray-900"><?php echo htmlspecialchars($spot['purok_name']); ?></td>
                                            <td class="px-4 py-3 text-gray-600"><?php echo $spot['report_count']; ?></td>
                                            <td class="px-4 py-3 text-gray-600"><?php echo $spot['unresolved_count'] ?? 0; ?></td>
                                            <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($spot['dominant_category'] ?? 'N/A'); ?></td>
                                            <td class="px-4 py-3 text-gray-500"><?php echo date('M d, Y', strtotime($spot['latest_report'])); ?></td>
                                            <td class="px-4 py-3"><span class="inline-flex rounded-full px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold"><?php echo $action; ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 text-center py-6">No hotspot data for the selected period (requires 3+ reports per purok).</p>
                    <?php endif; ?>
                </div>

                <!-- Community & Operational Performance -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Community Participation</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Total Supports</span>
                                <span class="font-bold text-gray-900"><?php echo $totalSupports; ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Support-to-Report Ratio</span>
                                <span class="font-bold text-gray-900"><?php echo $supportToReportRatio; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Operational Performance</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Avg Verification Time</span>
                                <span class="font-bold text-gray-900"><?php echo $avgVerificationHours; ?> hrs</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Avg Resolution Time</span>
                                <span class="font-bold text-gray-900"><?php echo $avgResolutionHours; ?> hrs</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Fastest Resolution</span>
                                <span class="font-bold text-emerald-600"><?php echo $fastestResolution; ?> hrs</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Longest Resolution</span>
                                <span class="font-bold text-red-600"><?php echo $longestResolution; ?> hrs</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trend Comparison -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Trend Comparison</h2>
                    <?php
                        $totalChange = $trendComparison['total_reports']['change'] ?? 0;
                        $resolutionChange = $trendComparison['resolution_rate']['change'] ?? 0;
                    ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-xl bg-gray-50">
                            <p class="text-xs text-gray-500">Total Reports</p>
                            <p class="text-lg font-bold text-gray-900 mt-1"><?php echo $trendComparison['total_reports']['current'] ?? 0; ?></p>
                            <p class="text-xs <?php echo $totalChange >= 0 ? 'text-emerald-600' : 'text-red-600'; ?>">
                                <?php echo $totalChange >= 0 ? '↑' : '↓'; ?> <?php echo abs($totalChange); ?>% vs previous period
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50">
                            <p class="text-xs text-gray-500">Resolution Rate</p>
                            <p class="text-lg font-bold text-gray-900 mt-1"><?php echo $trendComparison['resolution_rate']['current'] ?? 0; ?>%</p>
                            <p class="text-xs <?php echo $resolutionChange >= 0 ? 'text-emerald-600' : 'text-red-600'; ?>">
                                <?php echo $resolutionChange >= 0 ? '↑' : '↓'; ?> <?php echo abs($resolutionChange); ?>% vs previous period
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50">
                            <p class="text-xs text-gray-500">Active Hotspots</p>
                            <p class="text-lg font-bold text-gray-900 mt-1"><?php echo $kpis['active_hotspots'] ?? 0; ?></p>
                            <p class="text-xs text-gray-400">Current period</p>
                        </div>
                    </div>
                </div>

                <!-- Decision Support -->
                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100/40 rounded-xl border border-emerald-200 p-6 mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Decision Support Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <?php if (!empty($decisionSupport['highest_hotspot'])): ?>
                            <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                                <span class="text-red-600 mt-0.5">⚠</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Highest-priority hotspot</p>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($decisionSupport['highest_hotspot']['purok_name']); ?> — <?php echo $decisionSupport['highest_hotspot']['report_count']; ?> reports</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($decisionSupport['emerging_hotspot'])): ?>
                            <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                                <span class="text-amber-600 mt-0.5">●</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Emerging hotspot</p>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($decisionSupport['emerging_hotspot']['purok_name']); ?> — <?php echo $decisionSupport['emerging_hotspot']['report_count']; ?> reports</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                            <span class="text-blue-600 mt-0.5">↗</span>
                            <div>
                                <p class="font-semibold text-gray-800">Trend analysis</p>
                                <p class="text-gray-600">
                                    Reports are <?php echo ($decisionSupport['trend_increasing'] ?? false) ? '<span class="text-red-600 font-semibold">increasing</span>' : '<span class="text-emerald-600 font-semibold">stable or decreasing</span>'; ?> over time
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 bg-white/80 rounded-xl p-3">
                            <span class="text-purple-600 mt-0.5">●</span>
                            <div>
                                <p class="font-semibold text-gray-800">Recommended interventions</p>
                                <p class="text-gray-600 text-xs">
                                    <?php
                                        if ($totalSupports > 0 && $avgResolutionHours > 48) {
                                            echo 'Prioritize collection scheduling in high-density areas.';
                                        } elseif ($avgResolutionHours > 24) {
                                            echo 'Improve response time — consider additional collection routes.';
                                        } else {
                                            echo 'Continue current operations — performance is satisfactory.';
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtered Report Table -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-8">
                    <div class="flex flex-wrap items-center justify-between gap-3 p-6 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Filtered Reports</h2>
                            <p class="text-sm text-gray-500"><?php echo count($filteredReports); ?> report(s) matching filters</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="/brgy-waste-app-v3/public/admin/exportReportSummaryXLSX?<?php echo $exportQuery; ?>"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-semibold text-sm transition-colors">
                                Export CSV
                            </a>
                            <a href="/brgy-waste-app-v3/public/admin/exportReportSummaryPDF?<?php echo $exportQuery; ?>"
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold text-sm transition-colors">
                                Export PDF
                            </a>
                        </div>
                    </div>
                    <?php if (empty($filteredReports)): ?>
                        <p class="p-8 text-center text-gray-500 text-sm">No reports found for selected filters.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Resident</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purok</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($filteredReports as $report): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm font-mono text-gray-700"><?php echo $report['id']; ?></td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($report['name']); ?></td>
                                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($report['location'] ?? ''); ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold <?php echo statusBadgeClass($report['status']); ?>"><?php echo htmlspecialchars($report['status']); ?></span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Previous Exports -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Previous Exports
                    </h2>
                    <div class="space-y-3">
                        <?php if (!empty($exports)): ?>
                            <?php foreach ($exports as $export): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div>
                                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($export['filename']); ?></p>
                                        <p class="text-sm text-gray-600">
                                            <?php echo date('M d, Y', strtotime($export['generated_at'])); ?>
                                            · <?php echo strtoupper(htmlspecialchars($export['file_type'] ?? '')); ?>
                                            · <?php echo (int)($export['total_reports'] ?? 0); ?> reports
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm py-4">No previous exports yet</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";

    const trendLabels = <?php echo json_encode($trendLabels); ?>;
    const trendValues = <?php echo json_encode(array_map('intval', $trendValues)); ?>;

    const trendCtx = document.getElementById('trendChart');
    if (trendCtx && trendValues.length) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Reports',
                    data: trendValues,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
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
                    backgroundColor: '#10B981',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } }
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
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }

    const conditionCtx = document.getElementById('conditionChart');
    if (conditionCtx && <?php echo json_encode(!empty($conditionValues)); ?>) {
        const colors = ['#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#8B5CF6', '#EC4899', '#F97316'];
        new Chart(conditionCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($conditionLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_map('intval', $conditionValues)); ?>,
                    backgroundColor: colors.slice(0, <?php echo count($conditionValues); ?>),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { boxWidth: 10, font: { size: 9 } } } }
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
                    backgroundColor: '#10B981',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    y: { grid: { display: false } }
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
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } } },
                scales: {
                    x: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } },
                    y: { stacked: true, grid: { display: false } }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
