<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$data   = $data['data'] ?? [];
$nearby = $data['nearby'] ?? [];
$lat    = $data['lat'] ?? 15.558;
$lng    = $data['lng'] ?? 120.803;
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap');
    *:not(.material-icons) { font-family: 'Nunito Sans', sans-serif !important; }

    /* Pulsing animation for the user's location dot */
    @keyframes pulse-dot {
        0%   { box-shadow: 0 0 0 0   rgba(239,68,68,0.5); }
        70%  { box-shadow: 0 0 0 10px rgba(239,68,68,0);   }
        100% { box-shadow: 0 0 0 0   rgba(239,68,68,0);   }
    }
    .pulse-dot { animation: pulse-dot 1.8s infinite; }

    .leaflet-popup-content-wrapper {
        border-radius: 12px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
        padding: 0 !important;
        overflow: hidden;
    }
    .leaflet-popup-content { margin: 12px !important; }
    .dup-popup { width: 200px; }
    .dup-popup-cat  { font-size: 13px; font-weight: 800; color: #1e293b; margin: 0 0 3px; }
    .dup-popup-meta { font-size: 11px; color: #64748b; margin: 0 0 4px; line-height: 1.5; }
    .dup-popup-badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 700; letter-spacing: .04em; }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-100 to-[#E6F4EA] flex items-start sm:items-center justify-center p-3 sm:p-6">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-lg relative overflow-hidden">

        <!-- Header bar -->
        <div class="bg-gradient-to-r from-[#07281E] to-[#0B3024] px-5 py-5 sm:px-6 sm:py-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-emerald-300 mb-1">Duplicate Detection</p>
                    <h2 class="text-xl sm:text-2xl font-black text-white leading-tight">⚠️ Nearby Reports Found</h2>
                    <p class="mt-1.5 text-sm text-emerald-100/80">
                        <?php echo count($nearby); ?> report<?php echo count($nearby) !== 1 ? 's' : ''; ?> within <strong class="text-white">50 m</strong> of your location.
                        You can support one instead of creating a duplicate.
                    </p>
                </div>
                <a href="/brgy-waste-app-v3/public/resident/submit"
                   class="shrink-0 flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-5 space-y-4">

            <!-- Mini Map -->
            <div id="duplicateMap" class="h-44 sm:h-56 rounded-xl overflow-hidden border border-slate-200 bg-slate-100"></div>

            <!-- Legend row -->
            <div class="flex items-center gap-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[0.2em] px-1">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-500 pulse-dot shrink-0"></span> Your Location
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-amber-400 shrink-0"></span> Nearby Report
                </span>
            </div>

            <!-- Nearby Report Cards -->
            <?php if (!empty($nearby)): ?>
            <div class="space-y-2 max-h-52 overflow-y-auto pr-1 -mr-1">
                <?php foreach ($nearby as $report):
                    $distM   = (int)round(($report['distance_km'] ?? 0) * 1000);
                    $dateStr = !empty($report['submission_date']) ? date('M d, Y', strtotime($report['submission_date'])) : '';
                    $status  = strtolower($report['status'] ?? 'pending');
                    $statusLabel = ['pending'=>'Pending','verified'=>'Verified','resolved'=>'Resolved','rejected'=>'Rejected'][$status] ?? ucfirst($status);
                    $badgeBg = ['pending'=>'#fef3c7','verified'=>'#dbeafe','resolved'=>'#d1fae5','rejected'=>'#fee2e2'][$status] ?? '#f3f4f6';
                    $badgeTx = ['pending'=>'#92400e','verified'=>'#1e40af','resolved'=>'#065f46','rejected'=>'#991b1b'][$status] ?? '#374151';
                ?>
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 hover:bg-[#E6F4EA]/60 transition">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-bold text-slate-800 truncate"><?php echo htmlspecialchars($report['category_name'] ?? 'Report'); ?></p>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold"
                                  style="background:<?php echo $badgeBg; ?>;color:<?php echo $badgeTx; ?>"><?php echo $statusLabel; ?></span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            <?php echo $distM; ?>m away
                            <?php if ($dateStr): ?> · <?php echo $dateStr; ?><?php endif; ?>
                            · <?php echo (int)($report['support_count'] ?? 0); ?> support<?php echo (int)($report['support_count'] ?? 0) !== 1 ? 's' : ''; ?>
                        </p>
                    </div>
                    <form action="/brgy-waste-app-v3/public/resident/support_report" method="POST" class="shrink-0">
                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                        <button type="submit"
                                class="rounded-full bg-[#10B981] px-3 py-1.5 text-[11px] font-bold text-white hover:bg-emerald-600 transition shadow-sm shadow-emerald-200">
                            Support
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center text-sm text-slate-500">
                No nearby reports found.
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-1">
                <form action="/brgy-waste-app-v3/public/resident/continue_report" method="POST" class="flex-1">
                    <button type="submit"
                            class="w-full rounded-xl border-2 border-[#07281E] bg-white py-2.5 text-sm font-bold text-[#07281E] hover:bg-[#E6F4EA] transition">
                        Continue New Report
                    </button>
                </form>
                <a href="/brgy-waste-app-v3/public/resident/submit"
                   class="flex-1 rounded-xl bg-slate-100 py-2.5 text-center text-sm font-bold text-slate-700 hover:bg-slate-200 transition">
                    Cancel
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Leaflet for mini map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var userLat = <?php echo (float)$lat; ?>;
    var userLng = <?php echo (float)$lng; ?>;

    var map = L.map('duplicateMap', {
        zoomControl: false,
        scrollWheelZoom: false,
        dragging: false,
        doubleClickZoom: false
    }).setView([userLat, userLng], 17);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);

    // Precise barangay boundary
    var boundary = [
        [15.5699279, 120.8013517],[15.569572, 120.8008898],[15.5686578, 120.8008276],
        [15.5685788, 120.8006126],[15.5678398, 120.8005542],[15.5672858, 120.8001844],
        [15.5668847, 120.8000725],[15.566531, 120.8001665],[15.5663685, 120.7995785],
        [15.5657033, 120.7989717],[15.5658025, 120.7987031],[15.5654243, 120.7984537],
        [15.5652, 120.7980956],[15.5652043, 120.7977553],[15.5652862, 120.7975135],
        [15.5652259, 120.7971285],[15.5648604, 120.7964691],[15.5643821, 120.7961709],
        [15.5643993, 120.795562],[15.5637567, 120.7951681],[15.5632478, 120.7953561],
        [15.562581, 120.7952523],[15.5617529, 120.7950598],[15.5611835, 120.7950416],
        [15.5608471, 120.7945939],[15.5603295, 120.7946431],[15.5596467, 120.7943504],
        [15.5597848, 120.7937415],[15.55916, 120.7930393],[15.5570187, 120.7928646],
        [15.555107, 120.7921781],[15.554853, 120.7912123],[15.5543176, 120.7913399],
        [15.5533236, 120.7915605],[15.5534046, 120.7918092],[15.5478115, 120.8001316],
        [15.5481325, 120.8011058],[15.5484701, 120.8021398],[15.5485113, 120.8027807],
        [15.5489723, 120.8032508],[15.5500426, 120.8030798],[15.5501365, 120.8038043],
        [15.5502517, 120.8044282],[15.550614, 120.8049495],[15.5508445, 120.8058211],
        [15.551569, 120.8062911],[15.5520964, 120.8071584],[15.5520903, 120.8076635],
        [15.5524005, 120.8081181],[15.5523519, 120.8083454],[15.5525708, 120.8085979],
        [15.5528807, 120.8088668],[15.5512389, 120.8118007],[15.550257, 120.8126332],
        [15.5523838, 120.8153176],[15.549628, 120.817434],[15.5518119, 120.8219183],
        [15.5522367, 120.8232918],[15.5516159, 120.8253946],[15.5512188, 120.8260956],
        [15.5526533, 120.8281375],[15.5518644, 120.8298546],[15.5519514, 120.8310955],
        [15.5541358, 120.8335885],[15.5557229, 120.8325752],[15.5574083, 120.8326161],
        [15.5602447, 120.8332704],[15.5650646, 120.8283841],[15.5703491, 120.8236492],
        [15.5689622, 120.82189],[15.5676998, 120.8219651],[15.5645562, 120.8203353],
        [15.5594636, 120.8205697],[15.5617437, 120.8185042],[15.5609879, 120.8149287],
        [15.5623097, 120.8126889],[15.5595308, 120.8092582],[15.5673914, 120.8032464],
        [15.5699463, 120.8014669],[15.5699279, 120.8013517]
    ];
    L.polygon(boundary, {
        color: '#10b981', weight: 2, fillColor: '#d1fae5', fillOpacity: 0.12, dashArray: '6 5'
    }).addTo(map);

    // Pulsing user location marker
    var userIcon = L.divIcon({
        html: '<div class="pulse-dot" style="width:14px;height:14px;border-radius:50%;background:#ef4444;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>',
        className: '', iconSize: [14,14], iconAnchor: [7,7]
    });
    L.marker([userLat, userLng], { icon: userIcon })
        .addTo(map)
        .bindPopup('<b style="font-size:12px">Your pinned location</b>', { closeButton: false });

    // Nearby report markers with rich popups
    var statusLabel = { pending: 'Pending', verified: 'Verified', resolved: 'Resolved', rejected: 'Rejected' };
    var badgeBg = { pending: '#fef3c7', verified: '#dbeafe', resolved: '#d1fae5', rejected: '#fee2e2' };
    var badgeTx = { pending: '#92400e', verified: '#1e40af', resolved: '#065f46', rejected: '#991b1b' };

    <?php foreach ($nearby as $report): ?>
    (function() {
        var rLat  = <?php echo (float)$report['latitude']; ?>;
        var rLng  = <?php echo (float)$report['longitude']; ?>;
        var distM = <?php echo (int)round(($report['distance_km'] ?? 0) * 1000); ?>;
        var cat   = <?php echo json_encode($report['category_name'] ?? 'Report'); ?>;
        var st    = <?php echo json_encode(strtolower($report['status'] ?? 'pending')); ?>;
        var date  = <?php echo json_encode(!empty($report['submission_date']) ? date('M d, Y', strtotime($report['submission_date'])) : ''); ?>;
        var sup   = <?php echo (int)($report['support_count'] ?? 0); ?>;
        var id    = <?php echo (int)$report['id']; ?>;

        var nearIcon = L.divIcon({
            html: '<div style="width:12px;height:12px;border-radius:50%;background:#f59e0b;border:2.5px solid white;box-shadow:0 2px 5px rgba(0,0,0,0.25);"></div>',
            className: '', iconSize: [12,12], iconAnchor: [6,6]
        });

        var bg  = badgeBg[st]    || '#f3f4f6';
        var tx  = badgeTx[st]    || '#374151';
        var lbl = statusLabel[st] || st.charAt(0).toUpperCase() + st.slice(1);

        var popup = '<div class="dup-popup">' +
            '<span class="dup-popup-badge" style="background:' + bg + ';color:' + tx + '">' + lbl + '</span>' +
            '<p class="dup-popup-cat">' + cat + '</p>' +
            '<p class="dup-popup-meta">' + distM + ' m away' +
                (date ? ' &middot; ' + date : '') +
                ' &middot; ' + sup + ' support' + (sup !== 1 ? 's' : '') +
            '</p>' +
            '<a href="/brgy-waste-app-v3/public/resident/view_report/' + id + '" target="_blank" ' +
               'style="display:block;text-align:center;background:#07281E;color:#fff;border-radius:7px;padding:5px;font-size:11px;font-weight:700;text-decoration:none;margin-top:6px">' +
               'View Details &rarr;</a></div>';

        L.marker([rLat, rLng], { icon: nearIcon })
            .addTo(map)
            .bindPopup(popup, { maxWidth: 220, minWidth: 200 });
    })();
    <?php endforeach; ?>

    setTimeout(function() { map.invalidateSize(); }, 200);
});
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>