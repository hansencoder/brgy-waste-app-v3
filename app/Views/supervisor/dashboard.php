<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
// User info
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Supervisor';
$fullName = $_SESSION['user_name'] ?? 'Supervisor User';
$unreadCount = $data['unread_count'] ?? 0;

// KPI data
$totalReports = $data['total_reports'] ?? 0;
$pending = $data['pending'] ?? 0;
$inProgress = $data['in_progress'] ?? 0;
$resolved = $data['resolved'] ?? 0;
$todayReports = $data['today_reports'] ?? 0;
$activeHotspots = $data['active_hotspots'] ?? 0;

// Chart data
$statusDistribution = $data['status_distribution'] ?? [];
$monthlyTrends = $data['monthly_trends'] ?? [];
$recentReports = $data['recent_reports'] ?? [];
$hotspots = $data['hotspots'] ?? [];

// Prepare chart data
$statusLabels = [];
$statusCounts = [];
$statusColors = [];
foreach ($statusDistribution as $item) {
    $statusLabels[] = $item['status_name'];
    $statusCounts[] = (int)$item['count'];
    $statusColors[] = $item['color_code'] ?? '#6B7280';
}

$trendLabels = [];
$trendValues = [];
foreach ($monthlyTrends as $item) {
    $trendLabels[] = substr($item['month'], 0, 3);
    $trendValues[] = (int)$item['count'];
}

// Helper for status badge
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
    .pulse-dot { animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    .chart-container { position: relative; height: 180px; width: 100%; }
    .chart-container canvas { width: 100% !important; height: 100% !important; }
    .donut-container { position: relative; max-width: 128px; max-height: 128px; margin: 0 auto; }
    .donut-container canvas { width: 100% !important; height: 100% !important; }
    .donut-center-text {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        text-align: center; pointer-events: none; line-height: 1.15;
    }
    .donut-center-text .number { font-size: 1.3rem; font-weight: 700; color: #0f172a; }
    .donut-center-text .label { font-size: 0.58rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
    .pressure-track { height: 4px; border-radius: 2px; background: #f1f5f9; overflow: hidden; }
    .pressure-fill { height: 100%; border-radius: 2px; }
</style>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight truncate">Dashboard</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">Barangay Dulong Bayan · <?php echo date('F d, Y'); ?></p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse-dot"></span>
                        Live monitoring
                    </span>
                    <button onclick="openNotificationPanel()" class="relative p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        <?php if ($unreadCount > 0): ?>
                            <span class="absolute -top-1 -right-1 min-w-[16px] h-[16px] rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center px-1 border-2 border-white font-mono"><?php echo min($unreadCount, 99); ?></span>
                        <?php endif; ?>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Hero Banner -->
                    <div class="relative overflow-hidden bg-[#07281E] rounded-2xl p-4 sm:p-5 md:p-6 shadow-[0_24px_60px_-30px_rgba(7,40,30,0.6)] mb-8">
                        <svg class="absolute right-0 top-0 h-full w-1/2 pointer-events-none opacity-[0.08]" viewBox="0 0 400 160" preserveAspectRatio="none" fill="none">
                            <path d="M0,80 L60,80 L78,30 L96,130 L114,80 L150,80 L165,55 L180,105 L195,80 L400,80" stroke="white" stroke-width="2" fill="none"/>
                        </svg>
                        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="inline-flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-[0.3em] text-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    Supervisor Portal
                                </span>
                                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mt-1 tracking-tight truncate">Good morning, <?php echo htmlspecialchars($firstName); ?> 👋</h1>
                                <p class="text-emerald-200/65 text-xs sm:text-sm mt-1 font-medium truncate">Monitoring Barangay Dulong Bayan waste management operations</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                                <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/reports" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white/10 text-white text-xs font-semibold rounded-xl border border-white/10 hover:bg-white/20 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    Reports
                                </a>
                                <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/gis" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#1F8A5F] text-white text-xs font-semibold rounded-xl hover:bg-[#19754F] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    GIS Map
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- KPI Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-sm text-slate-500">Total</p>
                            <p class="text-2xl font-black text-slate-900"><?php echo $totalReports; ?></p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-sm text-amber-600">Pending</p>
                            <p class="text-2xl font-black text-amber-600"><?php echo $pending; ?></p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-sm text-emerald-600">Resolved</p>
                            <p class="text-2xl font-black text-emerald-600"><?php echo $resolved; ?></p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-sm text-slate-500">In Progress</p>
                            <p class="text-2xl font-black text-slate-900"><?php echo $inProgress; ?></p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-sm text-red-600">Hotspots</p>
                            <p class="text-2xl font-black text-red-600"><?php echo $activeHotspots; ?></p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-center">
                            <p class="text-sm text-slate-500">Today</p>
                            <p class="text-2xl font-black text-slate-900"><?php echo $todayReports; ?></p>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Monthly Trends -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                            <h3 class="text-sm font-bold text-slate-900 mb-2">Monthly Trends</h3>
                            <div class="chart-container">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>
                        <!-- Status Distribution -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                            <h3 class="text-sm font-bold text-slate-900 mb-2">Status Distribution</h3>
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <div class="donut-container">
                                    <canvas id="statusChart"></canvas>
                                    <div class="donut-center-text">
                                        <div class="number"><?php echo $totalReports; ?></div>
                                        <div class="label">Total</div>
                                    </div>
                                </div>
                                <div class="flex-1 grid grid-cols-2 gap-x-2 gap-y-1 text-xs w-full">
                                    <?php foreach ($statusDistribution as $item): ?>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background: <?php echo $item['color_code'] ?? '#6B7280'; ?>"></span>
                                            <span class="font-medium text-slate-600 truncate"><?php echo $item['status_name']; ?></span>
                                            <span class="font-mono font-semibold text-slate-800 ml-auto"><?php echo $item['count']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reports & Hotspots -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Recent Reports -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-bold text-slate-900">Recent Reports</h3>
                                <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/reports" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700">View all →</a>
                            </div>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                <?php foreach ($recentReports as $report): ?>
                                    <?php $badge = getStatusBadge($report['status']); ?>
                                    <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/view_report/<?php echo $report['id']; ?>" class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 gap-2">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs sm:text-sm font-semibold text-slate-800 truncate"><?php echo htmlspecialchars($report['category'] ?? 'Reported Issue'); ?></p>
                                            <p class="text-[10px] text-slate-500 truncate"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?> · <span class="font-mono">WR-<?php echo str_pad($report['id'], 4, '0', STR_PAD_LEFT); ?></span></p>
                                        </div>
                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-bold flex-shrink-0" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background: <?php echo $badge['text']; ?>;"></span>
                                            <?php echo $badge['label']; ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                                <?php if (empty($recentReports)): ?>
                                    <p class="text-center text-slate-400 text-sm py-4">No recent reports</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Active Hotspots -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-bold text-slate-900">Active Hotspots</h3>
                                <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/gis" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700">View map →</a>
                            </div>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                <?php foreach ($hotspots as $spot): ?>
                                    <?php
                                        $spotCount = (int)($spot['report_count'] ?? 0);
                                        $severity = $spotCount >= 10 ? 'High' : ($spotCount >= 5 ? 'Medium' : 'Low');
                                        $severityColor = $spotCount >= 10 ? '#DC2626' : ($spotCount >= 5 ? '#D97706' : '#10B981');
                                    ?>
                                    <div class="p-2.5 rounded-lg bg-slate-50/70 border border-slate-100">
                                        <div class="flex items-center justify-between gap-2 mb-1.5">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <span class="font-bold text-slate-800 text-xs sm:text-sm truncate"><?php echo htmlspecialchars($spot['purok_name'] ?? 'Unknown'); ?></span>
                                                <span class="inline-flex rounded-full px-1.5 py-0.5 text-[8px] font-bold flex-shrink-0" style="background: <?php echo $spotCount >= 10 ? '#FEE2E2' : '#FEF3C7'; ?>; color: <?php echo $severityColor; ?>;"><?php echo $severity; ?></span>
                                            </div>
                                            <span class="text-[10px] text-slate-500 font-mono flex-shrink-0"><?php echo $spotCount; ?> reports</span>
                                        </div>
                                        <p class="text-[10px] text-slate-500 mb-1.5 truncate"><?php echo htmlspecialchars($spot['dominant_category'] ?? 'Various'); ?></p>
                                        <div class="pressure-track"><div class="pressure-fill" style="width: <?php echo min($spotCount * 10, 100); ?>%; background: <?php echo $severityColor; ?>;"></div></div>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (empty($hotspots)): ?>
                                    <p class="text-center text-slate-400 text-sm py-4">No active hotspots detected</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Access -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/reports" class="bg-white rounded-2xl border border-slate-200 p-3 text-center hover:border-slate-300 hover:shadow-md transition-all shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 mx-auto flex items-center justify-center text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-700 mt-1 leading-tight">Reports Monitoring</p>
                        </a>
                        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/gis" class="bg-white rounded-2xl border border-slate-200 p-3 text-center hover:border-slate-300 hover:shadow-md transition-all shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 mx-auto flex items-center justify-center text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-700 mt-1 leading-tight">GIS Map</p>
                        </a>
                        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/analytics" class="bg-white rounded-2xl border border-slate-200 p-3 text-center hover:border-slate-300 hover:shadow-md transition-all shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 mx-auto flex items-center justify-center text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-700 mt-1 leading-tight">Analytics</p>
                        </a>
                        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/schedule" class="bg-white rounded-2xl border border-slate-200 p-3 text-center hover:border-slate-300 hover:shadow-md transition-all shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 mx-auto flex items-center justify-center text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                            </div>
                            <p class="text-[10px] font-bold text-slate-700 mt-1 leading-tight">Collection Schedule</p>
                        </a>
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
                    tension: 0.4,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5
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

    // Status Donut Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx && <?php echo json_encode(!empty($statusCounts)); ?>) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($statusLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($statusCounts); ?>,
                    backgroundColor: <?php echo json_encode($statusColors); ?>,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '68%',
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
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>