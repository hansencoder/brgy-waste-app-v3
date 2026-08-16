<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
// User info
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Supervisor';
$fullName = $_SESSION['user_name'] ?? 'Supervisor User';
$unreadCount = $data['unread_count'] ?? 0;

// KPI data
$totalReports = (int)($data['total_reports'] ?? 0);
$pending = (int)($data['pending'] ?? 0);
$verified = (int)($data['verified'] ?? 0);
$inProgress = (int)($data['in_progress'] ?? 0);
$resolved = (int)($data['resolved'] ?? 0);
$todayReports = (int)($data['today_reports'] ?? 0);
$activeHotspots = (int)($data['active_hotspots'] ?? 0);
$totalSupports = (int)($data['total_supports'] ?? 0);

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
    $statusColors[] = $item['color_code'] ?? '#10B981';
}

$trendLabels = [];
$trendValues = [];
foreach ($monthlyTrends as $item) {
    $trendLabels[] = substr($item['month'], 0, 3);
    $trendValues[] = (int)$item['count'];
}

// Resolution Rate
$resolutionRate = $totalReports > 0 ? round(($resolved / $totalReports) * 100) : 0;

// Status Badge Helper
function getSupervisorStatusBadge($status) {
    $map = [
        'Pending'     => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/60', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
        'Verified'    => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200/60', 'dot' => 'bg-blue-500', 'label' => 'Verified'],
        'In Progress' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200/60', 'dot' => 'bg-purple-500', 'label' => 'In Progress'],
        'Resolved'    => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200/60', 'dot' => 'bg-emerald-500', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200/60', 'dot' => 'bg-rose-500', 'label' => 'Rejected'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-500', 'label' => $status];
}
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

            <!-- Hero Welcome Card -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#07281E] via-[#0B2E22] to-[#041a14] rounded-2xl p-6 text-white shadow-sm border border-emerald-950">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-300 text-[11px] font-medium border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Live Operational Monitoring</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                            Welcome back, <?php echo htmlspecialchars($firstName); ?> 👋
                        </h1>
                        <p class="text-xs sm:text-sm text-emerald-100/70 max-w-xl leading-relaxed">
                            Overview of field incidents, verification queues, and resolution progress across Barangay <?php echo htmlspecialchars($sideBrgyName); ?>.
                        </p>
                    </div>

                    <!-- Action Shortcuts -->
                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                        <a href="/brgy-waste-app-v3/public/supervisor/reports" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-xs transition border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            <span>Manage Reports</span>
                        </a>
                        <a href="/brgy-waste-app-v3/public/supervisor/gis" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold shadow-xs transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                            <span>GIS Heatmap</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 6 KPI Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                
                <!-- Total Reports -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total</span>
                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-slate-900 font-mono"><?php echo number_format($totalReports); ?></p>
                    <span class="text-[10px] text-slate-400 mt-1">All-time logs</span>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider">Pending</span>
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-amber-600 font-mono"><?php echo number_format($pending); ?></p>
                    <span class="text-[10px] text-amber-600/80 mt-1">Needs verification</span>
                </div>

                <!-- Verified -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[11px] font-semibold text-blue-600 uppercase tracking-wider">Verified</span>
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 font-mono"><?php echo number_format($verified); ?></p>
                    <span class="text-[10px] text-blue-600/80 mt-1">Ready for dispatch</span>
                </div>

                <!-- In Progress -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[11px] font-semibold text-purple-600 uppercase tracking-wider">Dispatched</span>
                        <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="16" height="13" x="4" y="5" rx="2"/><path d="M16 2v3"/><path d="M8 2v3"/></svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-purple-600 font-mono"><?php echo number_format($inProgress); ?></p>
                    <span class="text-[10px] text-purple-600/80 mt-1">In progress</span>
                </div>

                <!-- Resolved -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider">Resolved</span>
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-emerald-600 font-mono"><?php echo number_format($resolved); ?></p>
                    <span class="text-[10px] text-emerald-600/80 mt-1"><?php echo $resolutionRate; ?>% rate</span>
                </div>

                <!-- Hotspots -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[11px] font-semibold text-red-600 uppercase tracking-wider">Hotspots</span>
                        <div class="w-7 h-7 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-red-600 font-mono"><?php echo number_format($activeHotspots); ?></p>
                    <span class="text-[10px] text-red-600/80 mt-1">High-density zones</span>
                </div>

            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                
                <!-- Monthly Trend Line Chart (7 cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Incident Volume Trend</h2>
                            <p class="text-xs text-slate-500">Monthly report submissions timeline</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-medium font-mono">
                            <?php echo date('Y'); ?>
                        </span>
                    </div>
                    <div class="h-60 w-full">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Status Distribution Doughnut (5 cols) -->
                <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Status Distribution</h2>
                        <p class="text-xs text-slate-500">Current workflow breakdown</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 pt-1">
                        <div class="relative w-40 h-40 shrink-0">
                            <canvas id="statusChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-xl font-bold text-slate-900 font-mono"><?php echo $totalReports; ?></span>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Total</span>
                            </div>
                        </div>

                        <div class="flex-1 space-y-1.5 w-full text-xs">
                            <?php foreach ($statusDistribution as $item): ?>
                            <div class="flex items-center justify-between p-1.5 rounded-lg hover:bg-slate-50 transition">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: <?php echo $item['color_code'] ?? '#10B981'; ?>"></span>
                                    <span class="font-medium text-slate-700"><?php echo htmlspecialchars($item['status_name']); ?></span>
                                </div>
                                <span class="font-bold text-slate-900 font-mono"><?php echo number_format($item['count']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recent Reports & Active Hotspots -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                
                <!-- Recent Reports (7 cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Recent Incident Reports</h2>
                            <p class="text-xs text-slate-500">Latest field reports submitted by residents and guests</p>
                        </div>
                        <a href="/brgy-waste-app-v3/public/supervisor/reports" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition">
                            View all →
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto pr-1 custom-scrollbar">
                        <?php if (!empty($recentReports)): ?>
                            <?php foreach ($recentReports as $report):
                                $badge = getSupervisorStatusBadge($report['status'] ?? 'Pending');
                            ?>
                            <a href="/brgy-waste-app-v3/public/supervisor/view_report/<?php echo $report['id']; ?>" class="flex items-center justify-between py-3 hover:bg-slate-50 px-2 rounded-xl transition gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-semibold text-emerald-800">WR-<?php echo str_pad($report['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                        <span class="text-xs font-bold text-slate-800 truncate"><?php echo htmlspecialchars($report['category'] ?? 'General Issue'); ?></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5 truncate">
                                        📍 <?php echo htmlspecialchars($report['purok'] ?? 'Barangay Wide'); ?>
                                        <?php if (!empty($report['submission_date'])): ?>
                                            · <?php echo date('M j, g:i A', strtotime($report['submission_date'])); ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border <?php echo $badge['bg']; ?> shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                    <?php echo $badge['label']; ?>
                                </span>
                            </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="py-8 text-center text-slate-400 text-xs">
                                No incident reports logged yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Active Hotspots (5 cols) -->
                <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Purok Density &amp; Hotspots</h2>
                            <p class="text-xs text-slate-500">Priority areas requiring truck dispatch</p>
                        </div>
                        <a href="/brgy-waste-app-v3/public/supervisor/gis" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition">
                            Open Map →
                        </a>
                    </div>

                    <div class="space-y-3 max-h-80 overflow-y-auto pr-1 custom-scrollbar">
                        <?php if (!empty($hotspots)): ?>
                            <?php foreach ($hotspots as $spot):
                                $count = (int)($spot['report_count'] ?? 0);
                                $sev = $count >= 10 ? 'Critical' : ($count >= 5 ? 'Elevated' : 'Moderate');
                                $sevBg = $count >= 10 ? 'bg-red-50 text-red-800 border-red-200' : ($count >= 5 ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200');
                                $barColor = $count >= 10 ? 'bg-red-500' : ($count >= 5 ? 'bg-amber-500' : 'bg-emerald-500');
                                $pct = min($count * 10, 100);
                            ?>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800"><?php echo htmlspecialchars($spot['purok_name'] ?? 'Purok Area'); ?></span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold border <?php echo $sevBg; ?>"><?php echo $sev; ?></span>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 font-mono"><?php echo $count; ?> reports</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="<?php echo $barColor; ?> h-1.5 rounded-full transition-all duration-500" style="width: <?php echo $pct; ?>%;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="py-8 text-center text-slate-400 text-xs">
                                No active density hotspots at this time.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<!-- Chart.js Setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Miranda Sans', sans-serif";

    // Trend Area Chart
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
                        backgroundColor: '#07281E',
                        padding: 10,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#64748B', font: { size: 10 } },
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748B', font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Status Doughnut Chart
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
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#07281E',
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                return ' ' + context.label + ': ' + context.parsed + ' (' + pct + '%)';
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