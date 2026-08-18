<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$kpis = $data['kpis'] ?? [];
$trendData = $data['trend_data'] ?? [];
$categoryData = $data['category_data'] ?? [];
$statusData = $data['status_data'] ?? [];
$conditionData = $data['condition_data'] ?? [];
$purokData = $data['purok_data'] ?? [];
$hotspotIntelligence = $data['hotspot_intelligence'] ?? [];
$totalSupports = (int)($data['total_supports'] ?? 0);
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
$selectedCategory = (int)($data['selected_category'] ?? 0);
$selectedPurok = (int)($data['selected_purok'] ?? 0);
$selectedStatus = $data['selected_status'] ?? '';

// Chart Arrays
$trendLabels = array_column($trendData, 'month');
$trendValues = array_map('intval', array_column($trendData, 'count'));
$categoryLabels = array_column($categoryData, 'category_name');
$categoryValues = array_map('intval', array_column($categoryData, 'count'));
$statusLabels = array_column($statusData, 'status_name');
$statusValues = array_map('intval', array_column($statusData, 'count'));
$statusColors = array_column($statusData, 'color_code');
$conditionLabels = array_column($conditionData, 'condition_name');
$conditionValues = array_map('intval', array_column($conditionData, 'count'));
$purokLabels = array_column($purokData, 'purok_name');
$purokValues = array_map('intval', array_column($purokData, 'total_reports'));
?>

<div class="min-h-screen bg-[#F8FAFC] flex">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../layouts/supervisor_topbar.php'; ?>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto w-full">

            <!-- Header & Export Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Analytics &amp; Operational Insights</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Statistical metrics, response benchmarks, and decision support</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="<?php echo app_url('supervisor/exportAnalyticsPDF?' . (http_build_query($_GET))); ?>" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
                        <span>Print Official Report</span>
                    </a>
                </div>
            </div>

            <!-- Filter Panel -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-2xs">
                <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                    <input type="hidden" name="url" value="supervisor/analytics">

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Date From</label>
                        <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Date To</label>
                        <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Category</label>
                        <select name="category" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                            <option value="0">All Categories</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo $c['category_id']; ?>" <?php echo $selectedCategory === (int)$c['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Purok Area</label>
                        <select name="purok" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                            <option value="0">All Puroks</option>
                            <?php foreach ($puroks as $p): ?>
                                <option value="<?php echo $p['purok_id']; ?>" <?php echo $selectedPurok === (int)$p['purok_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['purok_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?php echo $s['status_name']; ?>" <?php echo $selectedStatus === $s['status_name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['status_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 h-10 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition">
                            Apply
                        </button>
                        <a href="<?php echo app_url('supervisor/analytics'); ?>" class="h-10 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold transition flex items-center justify-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- KPI Metric Summary Row -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Reports</span>
                    <p class="text-2xl font-bold text-slate-900 font-mono mt-1"><?php echo number_format($kpis['total'] ?? 0); ?></p>
                    <span class="text-[10px] text-slate-400 mt-1 block">In selected period</span>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                    <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider block">Pending</span>
                    <p class="text-2xl font-bold text-amber-600 font-mono mt-1"><?php echo number_format($kpis['pending'] ?? 0); ?></p>
                    <span class="text-[10px] text-amber-600/80 mt-1 block">Awaiting review</span>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                    <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">Resolved</span>
                    <p class="text-2xl font-bold text-emerald-600 font-mono mt-1"><?php echo number_format($kpis['resolved'] ?? 0); ?></p>
                    <span class="text-[10px] text-emerald-600/80 mt-1 block">Successfully closed</span>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Resolution Rate</span>
                    <p class="text-2xl font-bold text-slate-900 font-mono mt-1"><?php echo number_format($kpis['resolution_rate'] ?? 0); ?>%</p>
                    <span class="text-[10px] text-slate-400 mt-1 block">Completion efficiency</span>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                    <span class="text-[11px] font-semibold text-red-600 uppercase tracking-wider block">Active Hotspots</span>
                    <p class="text-2xl font-bold text-red-600 font-mono mt-1"><?php echo number_format($kpis['active_hotspots'] ?? 0); ?></p>
                    <span class="text-[10px] text-red-600/80 mt-1 block">Priority purok clusters</span>
                </div>
            </div>

            <!-- Trend Chart Frame -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Incident Volume Trend</h2>
                        <p class="text-xs text-slate-500">Monthly submissions and seasonal reporting patterns</p>
                    </div>
                </div>
                <div class="h-64 w-full">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- 3 Category & Status Donut Charts -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                
                <!-- Category Chart -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">By Waste Category</h3>
                    <div class="h-48 w-full">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Status Doughnut -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider text-center">Status Breakdown</h3>
                    <div class="relative w-44 h-44 mx-auto flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Condition Chart -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">By Waste Condition</h3>
                    <div class="h-48 w-full">
                        <canvas id="conditionChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- Purok Analysis Bar Chart -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Incident Volume by Purok</h2>
                    <p class="text-xs text-slate-500">Comparative workload distribution across barangay zones</p>
                </div>
                <div class="h-60 w-full">
                    <canvas id="purokChart"></canvas>
                </div>
            </div>

            <!-- Hotspot Intelligence Table -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Hotspot Intelligence &amp; Action Plan</h2>
                    <p class="text-xs text-slate-500">Algorithmic risk evaluation and suggested municipal dispatch actions</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-500 uppercase text-[10px] font-semibold tracking-wider">
                                <th class="py-3 px-4">Priority</th>
                                <th class="py-3 px-4">Purok</th>
                                <th class="py-3 px-4">Reports</th>
                                <th class="py-3 px-4">Dominant Waste</th>
                                <th class="py-3 px-4">Latest Incident</th>
                                <th class="py-3 px-4">Suggested Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($hotspotIntelligence)): ?>
                                <?php $rank = 1; foreach ($hotspotIntelligence as $spot): 
                                    $cnt = (int)($spot['report_count'] ?? 0);
                                    $action = $cnt >= 10 ? 'Immediate Truck Dispatch' : ($cnt >= 5 ? 'Inspect & Schedule Route' : 'Routine Monitoring');
                                    $actBg = $cnt >= 10 ? 'bg-red-50 text-red-700 border-red-200' : ($cnt >= 5 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                                ?>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-700">#<?php echo $rank++; ?></td>
                                    <td class="py-3 px-4 font-bold text-slate-900"><?php echo htmlspecialchars($spot['purok_name'] ?? 'Purok'); ?></td>
                                    <td class="py-3 px-4 font-bold text-emerald-800 font-mono"><?php echo $cnt; ?></td>
                                    <td class="py-3 px-4 text-slate-600"><?php echo htmlspecialchars($spot['dominant_category'] ?? 'Mixed Waste'); ?></td>
                                    <td class="py-3 px-4 text-slate-500"><?php echo date('M d, Y', strtotime($spot['latest_report'])); ?></td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold border <?php echo $actBg; ?>">
                                            <?php echo $action; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                        No priority hotspot anomalies detected for the chosen timeframe.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Miranda Sans', sans-serif";

    // Trend Chart
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
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#64748B', font: { size: 10 } }, grid: { color: '#F1F5F9' } },
                    x: { grid: { display: false }, ticks: { color: '#64748B', font: { size: 10 } } }
                }
            }
        });
    }

    // Category Bar Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx && <?php echo json_encode(!empty($categoryValues)); ?>) {
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categoryLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($categoryValues); ?>,
                    backgroundColor: '#10B981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#F1F5F9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                }
            }
        });
    }

    // Status Donut
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
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // Condition Chart
    const conditionCtx = document.getElementById('conditionChart');
    if (conditionCtx && <?php echo json_encode(!empty($conditionValues)); ?>) {
        new Chart(conditionCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($conditionLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($conditionValues); ?>,
                    backgroundColor: '#3B82F6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#F1F5F9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                }
            }
        });
    }

    // Purok Horizontal Bar Chart
    const purokCtx = document.getElementById('purokChart');
    if (purokCtx && <?php echo json_encode(!empty($purokValues)); ?>) {
        new Chart(purokCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($purokLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($purokValues); ?>,
                    backgroundColor: '#8B5CF6',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#F1F5F9' } },
                    y: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>