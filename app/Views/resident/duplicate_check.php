<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$data = $data['data'] ?? [];
$nearby = $data['nearby'] ?? [];
$lat = $data['lat'] ?? 15.558;
$lng = $data['lng'] ?? 120.803;
?>
<div class="min-h-screen bg-[#F8FAFC] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 relative">
        <!-- Close button (go back to submit) -->
        <a href="/brgy-waste-app-v3/public/resident/submit" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </a>

        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">⚠️ Nearby Reports Found</h2>
        <p class="text-sm text-slate-500 mb-4">We found similar reports within <strong>50 meters</strong> of your location. You may support one of them instead of creating a duplicate.</p>

        <!-- Mini Map -->
        <div id="duplicateMap" class="h-48 rounded-xl border border-slate-200 bg-slate-100 mb-4"></div>

        <!-- List of Nearby Reports -->
        <div class="space-y-3 max-h-60 overflow-y-auto mb-4">
            <?php if (empty($nearby)): ?>
                <p class="text-slate-500 text-sm">No nearby reports found.</p>
            <?php else: ?>
                <?php foreach ($nearby as $report): ?>
                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:bg-slate-50">
                        <div>
                            <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($report['category_name'] ?? 'Report'); ?></p>
                            <p class="text-xs text-slate-500"><?php echo round($report['distance_km'] * 1000, 0); ?>m away · <?php echo date('M d, Y', strtotime($report['submission_date'])); ?></p>
                            <p class="text-xs text-slate-400">Status: <?php echo $report['status']; ?> · <?php echo $report['support_count']; ?> supports</p>
                        </div>
                        <form action="/brgy-waste-app-v3/public/resident/support_report" method="POST">
                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                            <button type="submit" class="px-4 py-2 bg-[#10B981] text-white text-sm font-bold rounded-full hover:bg-emerald-600 transition">
                                Support
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <form action="/brgy-waste-app-v3/public/resident/continue_report" method="POST" class="flex-1">
                <button type="submit" class="w-full py-2.5 border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition">
                    Continue New Report
                </button>
            </form>
            <a href="/brgy-waste-app-v3/public/resident/submit" class="flex-1 py-2.5 bg-slate-200 text-slate-700 font-bold rounded-xl text-center hover:bg-slate-300 transition">
                Cancel
            </a>
        </div>
    </div>
</div>

<!-- Leaflet for mini map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = <?php echo $lat; ?>;
    var lng = <?php echo $lng; ?>;
    var map = L.map('duplicateMap').setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OSM'
    }).addTo(map);
    // User pin
    L.marker([lat, lng], {
        icon: L.divIcon({ html: '<div style="background:#EF4444;width:12px;height:12px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3);"></div>', className: '', iconSize: [12,12], iconAnchor: [6,6] })
    }).addTo(map).bindPopup('Your location');

    // Nearby pins
    <?php foreach ($nearby as $report): ?>
        L.marker([<?php echo $report['latitude']; ?>, <?php echo $report['longitude']; ?>], {
            icon: L.divIcon({ html: '<div style="background:#F59E0B;width:10px;height:10px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3);"></div>', className: '', iconSize: [10,10], iconAnchor: [5,5] })
        }).addTo(map).bindPopup('Report #<?php echo $report['id']; ?>');
    <?php endforeach; ?>
    setTimeout(function() { map.invalidateSize(); }, 200);
});
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>