<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<?php
$data   = $data['data'] ?? [];
$nearby = $data['nearby'] ?? [];
$lat    = $data['lat'] ?? 15.558;
$lng    = $data['lng'] ?? 120.803;
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
</style>

<div class="min-h-screen bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-200">

        <!-- Header -->
        <div class="bg-[#0B2E22] px-6 py-5 flex items-center justify-between text-white border-b border-emerald-900">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center border border-amber-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-white">Nearby Reports Detected</h2>
                    <p class="text-xs text-emerald-200/80 mt-0.5"><?php echo count($nearby); ?> existing report(s) found within 50 meters</p>
                </div>
            </div>
            <a href="<?php echo app_url('resident/submit'); ?>" class="text-emerald-300 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
        </div>

        <div class="p-6 space-y-5">
            <!-- Mini Map -->
            <div id="duplicateMap" class="h-44 rounded-xl overflow-hidden border border-slate-200 bg-slate-100"></div>

            <!-- Legend -->
            <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 justify-center">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Your Location</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Existing Report</span>
            </div>

            <!-- Nearby Report Cards List -->
            <?php if (!empty($nearby)): ?>
                <div class="space-y-2.5 max-h-52 overflow-y-auto pr-1">
                    <?php foreach ($nearby as $rep):
                        $distM = (int)round(($rep['distance_km'] ?? 0) * 1000);
                        $dateStr = !empty($rep['submission_date']) ? date('M d, Y', strtotime($rep['submission_date'])) : '';
                        $status = strtolower($rep['status'] ?? 'pending');
                        $badgeBg = 'bg-amber-50 text-amber-900 border-amber-200';
                        if ($status === 'verified') $badgeBg = 'bg-blue-50 text-blue-900 border-blue-200';
                        elseif ($status === 'resolved') $badgeBg = 'bg-emerald-50 text-emerald-900 border-emerald-200';
                    ?>
                    <div class="flex items-center justify-between gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-emerald-50/50 transition">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-bold text-slate-900 truncate"><?php echo htmlspecialchars($rep['category_name'] ?? 'Waste Incident'); ?></span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border <?php echo $badgeBg; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span><?php echo $distM; ?>m away <?php if ($dateStr): ?>• <?php echo $dateStr; ?><?php endif; ?></span>
                            </p>
                        </div>
                        <form action="<?php echo app_url('resident/support_report'); ?>" method="POST" class="shrink-0">
                            <input type="hidden" name="report_id" value="<?php echo (int)$rep['id']; ?>">
                            <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition cursor-pointer">
                                Support This
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                <a href="<?php echo app_url('resident/submit'); ?>" class="py-2.5 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition">
                    Cancel
                </a>
                <form action="<?php echo app_url('resident/continue_report'); ?>" method="POST">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0B2E22] hover:bg-[#083528] text-white font-bold text-xs transition cursor-pointer">
                        Continue Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userLat = <?php echo (float)$lat; ?>;
    const userLng = <?php echo (float)$lng; ?>;

    const map = L.map('duplicateMap', {
        zoomControl: false,
        scrollWheelZoom: false,
        dragging: false
    }).setView([userLat, userLng], 17);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '', maxZoom: 19
    }).addTo(map);

    // User pin
    const userIcon = L.divIcon({
        html: '<div style="background:#ef4444;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 0 10px rgba(239,68,68,0.8);"></div>',
        className: '', iconSize: [16,16], iconAnchor: [8,8]
    });
    L.marker([userLat, userLng], { icon: userIcon }).addTo(map);

    // Nearby pins
    const nearby = <?php echo json_encode($nearby); ?>;
    nearby.forEach(r => {
        const pin = L.divIcon({
            html: '<div style="background:#f59e0b;width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>',
            className: '', iconSize: [14,14], iconAnchor: [7,7]
        });
        L.marker([r.latitude, r.longitude], { icon: pin }).addTo(map);
    });

    setTimeout(() => map.invalidateSize(), 200);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>