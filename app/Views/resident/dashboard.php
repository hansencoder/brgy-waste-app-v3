<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php
$fullName        = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName       = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';
$purok           = $_SESSION['user_purok'] ?? 'Purok 1';
$unreadCount     = $data['unread_count'] ?? 0;

$stats           = $data['stats'] ?? [];
$total           = (int)($stats['total']       ?? 0);
$pending         = (int)($stats['pending']     ?? $stats['Pending']     ?? 0);
$verified        = (int)($stats['verified']    ?? $stats['Verified']    ?? 0);
$inProgress      = (int)($stats['in_progress'] ?? $stats['In Progress'] ?? 0);
$resolved        = (int)($stats['resolved']    ?? $stats['Resolved']    ?? 0);
$resolutionRate  = $total > 0 ? round(($resolved / $total) * 100) : 0;
$supported_count = $data['supported_count'] ?? 0;

function getResidentReportBadge($status) {
    $map = [
        'pending'     => ['bg' => 'bg-amber-50 text-amber-900 border-amber-200', 'label' => 'Pending'],
        'verified'    => ['bg' => 'bg-blue-50 text-blue-900 border-blue-200', 'label' => 'Verified'],
        'in_progress' => ['bg' => 'bg-purple-50 text-purple-900 border-purple-200', 'label' => 'In Progress'],
        'resolved'    => ['bg' => 'bg-emerald-50 text-emerald-900 border-emerald-200', 'label' => 'Resolved'],
        'rejected'    => ['bg' => 'bg-red-50 text-red-900 border-red-200', 'label' => 'Rejected'],
    ];
    return $map[strtolower($status)] ?? ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'label' => ucfirst($status)];
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .dashboard-map { height: 350px; border-radius: 12px; overflow: hidden; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <!-- Resident Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <!-- Resident Topbar -->
        <?php include __DIR__ . '/../layouts/resident_topbar.php'; ?>

        <!-- Scrollable Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                <!-- 1. Hero Welcome Banner -->
                <div class="relative overflow-hidden rounded-2xl bg-[#0B2E22] p-6 sm:p-8 text-white shadow-sm border border-emerald-950">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-2 max-w-xl">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    Resident Dashboard
                                </span>
                                <span class="text-xs font-medium text-emerald-200/70">• <?php echo htmlspecialchars($purok); ?></span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-emerald-200 border border-white/15 text-xs font-mono">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span id="liveClockResident"><?php echo date('h:i:s A'); ?></span>
                                    <span class="text-white/40">•</span>
                                    <span><?php echo date('l, M d, Y'); ?></span>
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                                Welcome back, <?php echo htmlspecialchars($fullName); ?>
                            </h1>
                            <p class="text-xs sm:text-sm text-emerald-100/80 font-medium leading-relaxed">
                                Monitor your waste collection schedules, submit local waste incidents, and stay updated with official barangay announcements.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3 shrink-0">
                            <a href="<?php echo app_url('resident/submit'); ?>"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#10B981] hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-md transition active:scale-[0.98] cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                <span>Report Waste Issue</span>
                            </a>
                            <a href="<?php echo app_url('resident/collection_schedule'); ?>"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/15 transition backdrop-blur-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span>Collection Schedule</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. KPI Metrics Summary Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                    <!-- Total Submitted -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Reports</p>
                        <p class="text-2xl font-black text-slate-900 mt-1 font-mono"><?php echo $total; ?></p>
                        <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Submitted by you</p>
                    </div>

                    <!-- Pending Verification -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Pending</p>
                        <p class="text-2xl font-black text-amber-600 mt-1 font-mono"><?php echo $pending; ?></p>
                        <p class="text-[11px] font-semibold text-amber-700/80 mt-0.5">Awaiting review</p>
                    </div>

                    <!-- Verified -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-700">Verified</p>
                        <p class="text-2xl font-black text-blue-600 mt-1 font-mono"><?php echo $verified; ?></p>
                        <p class="text-[11px] font-semibold text-blue-700/80 mt-0.5">Confirmed</p>
                    </div>

                    <!-- In Progress -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-purple-700">In Progress</p>
                        <p class="text-2xl font-black text-purple-600 mt-1 font-mono"><?php echo $inProgress; ?></p>
                        <p class="text-[11px] font-semibold text-purple-700/80 mt-0.5">Dispatched</p>
                    </div>

                    <!-- Resolved -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-800">Resolved</p>
                        <p class="text-2xl font-black text-emerald-800 mt-1 font-mono"><?php echo $resolved; ?></p>
                        <p class="text-[11px] font-semibold text-emerald-700 mt-0.5">Cleaned</p>
                    </div>

                    <!-- Supported Reports Count -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-teal-700">Supported (+1)</p>
                        <p class="text-2xl font-black text-teal-700 mt-1 font-mono"><?php echo $supported_count; ?></p>
                        <p class="text-[11px] font-semibold text-teal-600 mt-0.5">Community votes</p>
                    </div>
                </div>

                <!-- 3. Two-Column Dashboard Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Section (2 cols): Recent Reports & Waste Map -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Recent Report Activity Table Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Recent Report Activity</h2>
                                    <p class="text-xs font-semibold text-slate-400 mt-0.5">Your latest waste incident submissions</p>
                                </div>
                                <a href="<?php echo app_url('resident/my_report'); ?>" class="text-xs font-extrabold text-emerald-700 hover:text-emerald-900 transition">
                                    View All Reports →
                                </a>
                            </div>

                            <!-- Desktop Table -->
                            <div class="hidden sm:block overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            <th class="py-3 px-6">Report ID</th>
                                            <th class="py-3 px-6">Category</th>
                                            <th class="py-3 px-6">Date Submitted</th>
                                            <th class="py-3 px-6">Status</th>
                                            <th class="py-3 px-6 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php if (!empty($data['reports'])): ?>
                                            <?php foreach (array_slice($data['reports'], 0, 5) as $report):
                                                $badge = getResidentReportBadge($report['status'] ?? 'pending');
                                                $reportId = 'WR-' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);
                                            ?>
                                            <tr class="hover:bg-slate-50/60 transition">
                                                <td class="py-3.5 px-6 font-mono font-bold text-slate-900">
                                                    <?php echo $reportId; ?>
                                                </td>
                                                <td class="py-3.5 px-6 font-semibold text-slate-800">
                                                    <?php echo htmlspecialchars($report['waste_category'] ?? 'General Waste'); ?>
                                                </td>
                                                <td class="py-3.5 px-6 text-slate-500 font-mono">
                                                    <?php echo date('M d, Y', strtotime($report['submission_date'])); ?>
                                                </td>
                                                <td class="py-3.5 px-6">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border <?php echo $badge['bg']; ?>">
                                                        <?php echo $badge['label']; ?>
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-6 text-right">
                                                    <a href="<?php echo app_url('resident/view_report/' . ($report['id']) . '?from=dashboard'); ?>"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition">
                                                        <span>View</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="py-8 text-center text-slate-400 font-medium">
                                                    No waste reports submitted yet. Click "Report Waste Issue" to submit your first report.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile list view -->
                            <div class="sm:hidden divide-y divide-slate-100 text-xs">
                                <?php if (!empty($data['reports'])): ?>
                                    <?php foreach (array_slice($data['reports'], 0, 5) as $report):
                                        $badge = getResidentReportBadge($report['status'] ?? 'pending');
                                        $reportId = 'WR-' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);
                                    ?>
                                    <div class="p-4 flex items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-mono font-bold text-slate-900"><?php echo $reportId; ?></span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border <?php echo $badge['bg']; ?>">
                                                    <?php echo $badge['label']; ?>
                                                </span>
                                            </div>
                                            <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['waste_category'] ?? 'General Waste'); ?></p>
                                            <p class="text-[10px] text-slate-400 font-mono mt-0.5"><?php echo date('M d, Y - h:i A', strtotime($report['submission_date'])); ?></p>
                                        </div>
                                        <a href="<?php echo app_url('resident/view_report/' . ($report['id']) . '?from=dashboard'); ?>" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 font-bold text-xs border border-emerald-200 shrink-0">
                                            View
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-6 text-center text-slate-400 font-medium">No waste reports yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Barangay Reports Map Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                                        <span>Barangay Reports Map</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Live GIS</span>
                                    </h2>
                                    <p class="text-xs font-semibold text-slate-400 mt-0.5">Nearby waste reports &amp; barangay collection jurisdiction</p>
                                </div>
                                <div class="flex items-center gap-3 text-xs font-semibold text-slate-600 flex-wrap">
                                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span>Pending</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span>Verified</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Resolved</span>
                                </div>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div id="dashboardMap" class="dashboard-map border border-slate-200"></div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Section (1 col): Announcements, Tips & Schedule -->
                    <div class="space-y-6">

                        <!-- Quick Guide / Reporting Tips Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">Waste Reporting Tips</h3>
                                    <p class="text-[11px] font-semibold text-slate-400">Best practices for fast response</p>
                                </div>
                            </div>

                            <div class="space-y-3 text-xs">
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">1</span>
                                    <p class="text-slate-600 font-medium leading-relaxed"><strong>Snap clear photos</strong> of the waste pile to help collectors gauge truck size.</p>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">2</span>
                                    <p class="text-slate-600 font-medium leading-relaxed"><strong>Pin exact location</strong> using the interactive map to avoid route delays.</p>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">3</span>
                                    <p class="text-slate-600 font-medium leading-relaxed"><strong>Categorize correctly</strong> (Hazardous, Bulky, Biodegradable, Recyclable).</p>
                                </div>
                            </div>
                        </div>

                        <!-- Eco Tip Card -->
                        <div class="bg-gradient-to-br from-[#0B2E22] to-[#083528] rounded-2xl p-5 text-white shadow-sm border border-emerald-900 space-y-3">
                            <div class="flex items-center gap-2 text-emerald-300 text-xs font-bold uppercase tracking-wider">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12 12"/></svg>
                                <span>Eco Reminder</span>
                            </div>
                            <h4 class="text-sm font-extrabold text-white leading-snug">Segregate Waste at the Source</h4>
                            <p class="text-xs text-emerald-100/80 leading-relaxed font-medium">
                                Proper sorting into Biodegradable, Recyclable, and Special Waste prevents landfill overflow and keeps our barangay drainage flood-free.
                            </p>
                            <div class="pt-2 border-t border-emerald-800/60 flex items-center justify-between text-xs">
                                <span class="text-emerald-300 font-bold">Barangay Solid Waste Act</span>
                                <span class="text-[10px] text-emerald-400 font-mono">RA 9003</span>
                            </div>
                        </div>

                        <!-- Collection Schedule Shortcut -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h3 class="text-sm font-extrabold text-slate-900">Collection Schedules</h3>
                                <a href="<?php echo app_url('resident/collection_schedule'); ?>" class="text-xs font-bold text-emerald-700 hover:text-emerald-900">
                                    Full Schedule →
                                </a>
                            </div>
                            <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                Standard waste trucks operate regular routes every morning starting at 6:00 AM. Please ensure your bins are placed out prior to collection.
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultCenter = [
        <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>, 
        <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>
    ];
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;

    const map = L.map('dashboardMap', {
        zoomControl: true,
        scrollWheelZoom: false
    }).setView(defaultCenter, defaultZoom);

    const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri',
        maxZoom: 19
    });
    const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    });
    const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19
    });

    satelliteMap.addTo(map);
    labelsMap.addTo(map);

    L.control.layers({
        "Satellite Imagery": L.layerGroup([satelliteMap, labelsMap]),
        "Street Map": streetMap
    }, null, { position: 'topright' }).addTo(map);

    // Dynamic Barangay boundary
    const rawBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
    if (rawBrgyBoundary) {
        try {
            const brgyGeoObj = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            L.geoJSON(brgyGeoObj, {
                style: {
                    color: '#10b981',
                    weight: 2.5,
                    fillColor: '#d1fae5',
                    fillOpacity: 0.12,
                    dashArray: '6 5'
                }
            }).addTo(map);
        } catch(e) {
            console.error('Error rendering dynamic barangay boundary in resident dashboard:', e);
        }
    }

    const statusColors = {
        1: { color: '#f59e0b', bg: '#fef3c7', txt: '#92400e', label: 'Pending' },
        2: { color: '#3b82f6', bg: '#dbeafe', txt: '#1e40af', label: 'Verified' },
        3: { color: '#8b5cf6', bg: '#ede9fe', txt: '#4c1d95', label: 'In Progress' },
        4: { color: '#10b981', bg: '#d1fae5', txt: '#065f46', label: 'Resolved' },
        5: { color: '#ef4444', bg: '#fee2e2', txt: '#991b1b', label: 'Rejected' }
    };

    const mapPins = <?php echo json_encode($data['map_pins'] ?? []); ?>;
    const pinGroup = L.featureGroup();

    mapPins.forEach(pin => {
        if (!pin.latitude || !pin.longitude) return;
        const cfg = statusColors[pin.status_id] || { color: '#9ca3af', bg: '#f3f4f6', txt: '#4b5563', label: 'Unknown' };
        const icon = L.divIcon({
            html: `<div style="background:${cfg.color};width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>`,
            className: '', iconSize: [14,14], iconAnchor: [7,7]
        });
        const viewUrl = '<?php echo app_url('resident/view_report/'); ?>' + pin.id + '?from=dashboard';
        const supportBadge = (pin.support_count > 0) ? `<span style="display:inline-block;padding:2px 6px;border-radius:6px;font-size:9px;font-weight:700;background:#ccfbf1;color:#0f766e;margin-left:4px;">👍 ${pin.support_count}</span>` : '';
        const rawDesc = pin.description || 'No description provided';
        const shortDesc = rawDesc.length > 55 ? rawDesc.substring(0, 55) + '...' : rawDesc;
        const popup = `<div style="font-family:'Miranda Sans',sans-serif;font-size:12px;width:190px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <span style="display:inline-block;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:700;background:${cfg.bg};color:${cfg.txt};">${cfg.label}</span>
                ${supportBadge}
            </div>
            <p style="font-weight:800;color:#0f172a;margin:0 0 2px;">${pin.category_name||'Waste Report'}</p>
            <p style="color:#64748b;font-size:11px;margin:0 0 6px;">${shortDesc}</p>
            <a href="${viewUrl}" style="display:block;text-align:center;background:#0B2E22;color:white;padding:5px 0;border-radius:8px;font-weight:700;text-decoration:none;font-size:11px;">View Report →</a>
        </div>`;
        const marker = L.marker([parseFloat(pin.latitude), parseFloat(pin.longitude)], { icon }).bindPopup(popup);
        marker.addTo(map);
        pinGroup.addLayer(marker);
    });

    try {
        if (pinGroup.getLayers().length > 0) {
            map.fitBounds(pinGroup.getBounds().pad(0.1));
        } else if (rawBrgyBoundary) {
            const brgyGeoObj = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            const bounds = L.geoJSON(brgyGeoObj).getBounds();
            map.fitBounds(bounds, { padding: [16, 16] });
        } else {
            map.setView(defaultCenter, defaultZoom);
        }
    } catch(e) { 
        map.setView(defaultCenter, defaultZoom); 
    }

    setTimeout(() => map.invalidateSize(), 200);

    // Live Clock Updater
    function updateLiveClockResident() {
        const el = document.getElementById('liveClockResident');
        if (el) {
            const now = new Date();
            el.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
        }
    }
    setInterval(updateLiveClockResident, 1000);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>