<?php include '../app/Views/layouts/header.php'; ?>
<?php
$stats         = $data['stats']          ?? ['total'=>0,'pending'=>0,'verified'=>0,'resolved'=>0];
$today_count   = $data['today_count']    ?? 0;
$pending_count = $data['pending_count']  ?? 0;
$active_res    = $data['active_residents'] ?? 0;
$res_rate      = $data['resolution_rate'] ?? 0;
$recent_rpts   = $data['recent_reports'] ?? [];
$recent_act    = $data['recent_activity'] ?? [];
$heatmap       = $data['heatmap']        ?? [];

// Status colour map
$statusDot = [
    'pending'  => 'bg-amber-500',
    'verified' => 'bg-blue-500',
    'resolved' => 'bg-emerald-500',
];
$statusLabel = [
    'pending'  => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600">Pending</span>',
    'verified' => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600">Verified</span>',
    'resolved' => '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600">Resolved</span>',
];
?>

<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <!-- Sidebar -->
    <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">

        <!-- Top Nav -->
        <?php include '../app/Views/layouts/admin_topbar.php'; ?>

        <!-- Scrollable Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <!-- ── Header ── -->
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-1">Secretary Dashboard</h1>
                        <p class="text-gray-500 text-sm">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Secretary'); ?>! Here's what's happening in your barangay.</p>
                    </div>
                    <div class="flex space-x-3 text-sm">
                        <a href="/brgy-waste-app-v3/public/admin/announcements"
                           class="bg-[#35664b] hover:bg-[#15281f] text-white px-4 py-2 rounded-md font-medium flex items-center shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Post Announcement
                        </a>
                        <a href="/brgy-waste-app-v3/public/admin/export?format=csv"
                           class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md font-medium flex items-center shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export CSV
                        </a>
                    </div>
                </div>

                <!-- ── 4 Stat Cards ── -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

                    <!-- New Reports Today -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium mb-1">New Reports Today</h3>
                                <p class="text-4xl font-bold text-gray-900"><?php echo $today_count; ?></p>
                            </div>
                            <div class="bg-blue-50 p-2 rounded-lg text-blue-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3"><?php echo $stats['total']; ?> total all-time</p>
                    </div>

                    <!-- Pending Approvals -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium mb-1">Pending Reports</h3>
                                <p class="text-4xl font-bold text-gray-900"><?php echo $pending_count; ?></p>
                            </div>
                            <div class="bg-yellow-50 p-2 rounded-lg text-yellow-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3"><?php echo $stats['verified'] ?? 0; ?> verified, awaiting resolution</p>
                    </div>

                    <!-- Active Residents -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium mb-1">Active Residents</h3>
                                <p class="text-4xl font-bold text-gray-900"><?php echo $active_res; ?></p>
                            </div>
                            <div class="bg-green-50 p-2 rounded-lg text-green-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3">Registered & approved accounts</p>
                    </div>

                    <!-- Resolution Rate -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-500 text-sm font-medium mb-1">Resolution Rate</h3>
                                <p class="text-4xl font-bold text-gray-900"><?php echo $res_rate; ?>%</p>
                            </div>
                            <div class="bg-purple-50 p-2 rounded-lg text-purple-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3"><?php echo $stats['resolved'] ?? 0; ?> of <?php echo $stats['total']; ?> reports resolved</p>
                    </div>

                </div>

                <!-- ── Main Content Grid ── -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Left Column (Reports Overview + Map) -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Reports Overview Card -->
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                                <h2 class="text-lg font-bold text-gray-900">Reports Overview</h2>
                                <a href="/brgy-waste-app-v3/public/admin/reports" class="text-green-600 text-sm font-medium hover:underline">View all →</a>
                            </div>
                            <div class="p-6">
                                <!-- Stats Mini Bar -->
                                <div class="flex justify-between text-center mb-8 px-4">
                                    <div>
                                        <p class="text-2xl font-bold text-amber-500"><?php echo $stats['pending'] ?? 0; ?></p>
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Pending</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-blue-500"><?php echo $stats['verified'] ?? 0; ?></p>
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Verified</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-emerald-500"><?php echo $stats['resolved'] ?? 0; ?></p>
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Resolved</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-700"><?php echo $stats['total'] ?? 0; ?></p>
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Total</p>
                                    </div>
                                </div>

                                <!-- Recent Reports List -->
                                <?php if (!empty($recent_rpts)): ?>
                                <div class="space-y-4">
                                    <?php foreach ($recent_rpts as $rpt): ?>
                                    <?php
                                        $dotClass = $statusDot[$rpt['status']] ?? 'bg-gray-400';
                                    ?>
                                    <a href="/brgy-waste-app-v3/public/admin/reports" class="flex items-start justify-between group hover:bg-gray-50 -mx-2 px-2 py-2 rounded-lg transition-colors">
                                        <div class="flex items-start">
                                            <div class="w-2 h-2 rounded-full <?php echo $dotClass; ?> mt-2 mr-3 flex-shrink-0"></div>
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-green-700 transition-colors">
                                                    RPT-<?php echo str_pad($rpt['id'], 5, '0', STR_PAD_LEFT); ?>
                                                    &nbsp;<?php echo $statusLabel[$rpt['status']] ?? ''; ?>
                                                </h4>
                                                <p class="text-xs text-gray-500 mt-0.5 truncate max-w-sm"><?php echo htmlspecialchars($rpt['description']); ?></p>
                                                <p class="text-xs text-gray-400 mt-0.5">by <?php echo htmlspecialchars($rpt['resident_name']); ?></p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400 whitespace-nowrap ml-4 mt-1"><?php echo date('M j, Y', strtotime($rpt['submission_date'])); ?></span>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No reports yet</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Map Section -->
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 overflow-hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-bold text-gray-900">Barangay Map — Reports Heatmap</h2>
                                <a href="/brgy-waste-app-v3/public/admin/reports" class="text-green-600 text-sm font-medium hover:underline">Manage reports →</a>
                            </div>
                            <div class="w-full h-[320px] rounded-lg overflow-hidden border border-gray-200">
                                <div id="map" class="w-full h-full z-0"></div>
                            </div>
                            <!-- Map Legend -->
                            <div class="flex items-center gap-5 mt-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>Pending</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>Verified</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>Resolved</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Recent Activity) -->
                    <div class="space-y-8">
                        <!-- Recent Activity -->
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                                <h2 class="text-lg font-bold text-gray-900">Recent Activity</h2>
                                <a href="/brgy-waste-app-v3/public/admin/auditLogs" class="text-green-600 text-sm font-medium hover:underline">View logs →</a>
                            </div>
                            <div class="flex flex-col divide-y divide-gray-50">
                                <?php if (!empty($recent_act)): ?>
                                    <?php foreach ($recent_act as $act): ?>
                                    <?php
                                        // Pick icon/colour based on action keyword
                                        $actBg  = 'bg-blue-50 text-blue-500';
                                        $actIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';

                                        if (stripos($act['action'], 'Verified') !== false || stripos($act['action'], 'Resolved') !== false) {
                                            $actBg   = 'bg-emerald-50 text-emerald-500';
                                            $actIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                                        } elseif (stripos($act['action'], 'Approved') !== false) {
                                            $actBg   = 'bg-green-50 text-green-500';
                                            $actIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
                                        } elseif (stripos($act['action'], 'Deleted') !== false || stripos($act['action'], 'Rejected') !== false) {
                                            $actBg   = 'bg-red-50 text-red-500';
                                            $actIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
                                        } elseif (stripos($act['action'], 'Announcement') !== false) {
                                            $actBg   = 'bg-purple-50 text-purple-500';
                                            $actIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>';
                                        }
                                    ?>
                                    <div class="p-5 hover:bg-gray-50 transition-colors flex items-start gap-4">
                                        <div class="<?php echo $actBg; ?> p-2 rounded-lg flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <?php echo $actIcon; ?>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($act['action']); ?></h4>
                                            <p class="text-xs text-gray-500 mt-0.5 truncate"><?php echo htmlspecialchars($act['details']); ?></p>
                                            <p class="text-xs text-gray-400 mt-0.5"><?php echo date('M j, Y g:i A', strtotime($act['created_at'])); ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-8 text-center text-gray-400">
                                        <p class="text-sm">No recent activity yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>                    
                    </div>
                </div>

            </div><!-- /max-w-7xl -->
        </main>
    </div>
</div>

<!-- Leaflet Heat Plugin -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var map = L.map('map').setView([15.558, 120.803], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        className: 'map-grayscale'
    }).addTo(map);

    // Subtle grayscale filter
    var s = document.createElement('style');
    s.innerHTML = '.map-grayscale { filter: grayscale(40%) opacity(0.85); }';
    document.head.appendChild(s);

    // Barangay boundary
    var boundary = {"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[120.8013517,15.5699279],[120.8008898,15.569572],[120.8008276,15.5686578],[120.8006126,15.5685788],[120.8005542,15.5678398],[120.8001844,15.5672858],[120.8000725,15.5668847],[120.8001665,15.566531],[120.7995785,15.5663685],[120.7989717,15.5657033],[120.7987031,15.5658025],[120.7984537,15.5654243],[120.7980956,15.5652],[120.7977553,15.5652043],[120.7975135,15.5652862],[120.7971285,15.5652259],[120.7964691,15.5648604],[120.7961709,15.5643821],[120.795562,15.5643993],[120.7951681,15.5637567],[120.7953561,15.5632478],[120.7952523,15.562581],[120.7950598,15.5617529],[120.7950416,15.5611835],[120.7945939,15.5608471],[120.7946431,15.5603295],[120.7943504,15.5596467],[120.7937415,15.5597848],[120.7930393,15.55916],[120.7928646,15.5570187],[120.7921781,15.555107],[120.7912123,15.554853],[120.7913399,15.5543176],[120.7915605,15.5533236],[120.7918092,15.5534046],[120.8001316,15.5478115],[120.8011058,15.5481325],[120.8021398,15.5484701],[120.8027807,15.5485113],[120.8032508,15.5489723],[120.8030798,15.5500426],[120.8038043,15.5501365],[120.8044282,15.5502517],[120.8049495,15.550614],[120.8058211,15.5508445],[120.8062911,15.551569],[120.8071584,15.5520964],[120.8076635,15.5520903],[120.8081181,15.5524005],[120.8083454,15.5523519],[120.8085979,15.5525708],[120.8088668,15.5528807],[120.8118007,15.5512389],[120.8126332,15.550257],[120.8153176,15.5523838],[120.817434,15.549628],[120.8219183,15.5518119],[120.8232918,15.5522367],[120.8253946,15.5516159],[120.8260956,15.5512188],[120.8281375,15.5526533],[120.8298546,15.5518644],[120.8310955,15.5519514],[120.8335885,15.5541358],[120.8325752,15.5557229],[120.8326161,15.5574083],[120.8332704,15.5602447],[120.8283841,15.5650646],[120.8236492,15.5703491],[120.82189,15.5689622],[120.8219651,15.5676998],[120.8203353,15.5645562],[120.8205697,15.5594636],[120.8185042,15.5617437],[120.8149287,15.5609879],[120.8126889,15.5623097],[120.8092582,15.5595308],[120.8032464,15.5673914],[120.8014669,15.5699463],[120.8013468,15.5699463]]]}}]};
    L.geoJSON(boundary, { style: { color:'#16a34a', weight:2, fillColor:'#22c55e', fillOpacity:0.08, dashArray:'6,5' } }).addTo(map);

    // Real report pins from DB
    var reports = <?php
        $db2 = new Database();
        $db2->query("SELECT latitude, longitude, status FROM reports");
        $pins = $db2->resultSet();
        echo json_encode($pins ?: []);
    ?>;

    var pinColors = { pending: '#f59e0b', verified: '#3b82f6', resolved: '#10b981' };
    reports.forEach(function(pin) {
        var color = pinColors[pin.status] || '#9ca3af';
        var icon = L.divIcon({
            html: '<div style="background:' + color + ';width:12px;height:12px;border-radius:50%;border:2.5px solid white;box-shadow:0 2px 4px rgba(0,0,0,.3)"></div>',
            className: '', iconSize: [12,12], iconAnchor: [6,6]
        });
        L.marker([parseFloat(pin.latitude), parseFloat(pin.longitude)], { icon: icon }).addTo(map);
    });

    // Heatmap overlay
    var heatData = <?php echo json_encode(array_map(fn($p) => [(float)$p['latitude'], (float)$p['longitude'], 0.6], $heatmap)); ?>;
    if (heatData.length > 0) {
        L.heatLayer(heatData, { radius:30, blur:20, maxZoom:16 }).addTo(map);
    }

    setTimeout(function(){ map.invalidateSize(); }, 150);
});
</script>

<?php include '../app/Views/layouts/footer.php'; ?>
