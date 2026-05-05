<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
// Retrieve user info from session if available
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';
?>

<div class="min-h-screen bg-[#f9fafb] w-full font-sans antialiased text-slate-800">

    <!-- Top Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[68px]">
                <!-- Left: Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#2A523D] flex items-center justify-center text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <span class="font-extrabold text-black text-lg tracking-tight">WasteWatch</span>
                </div>

                <!-- Center: Nav Links -->
                <div class="hidden md:flex items-center justify-center gap-1.5 flex-1">
                    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex items-center gap-2 bg-[#2A523D] text-white px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] shadow-sm shadow-[#118B50]/20 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/></svg>
                        Home
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        Reports
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Submit Report
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        News
                    </a>
                </div>

                <!-- Right: Profile -->
                <div class="flex items-center gap-3 md:gap-5">
                    <button onclick="openNotificationPanel()" class="relative p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </button>

                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>


                    <a href="/brgy-waste-app-v3/public/resident/profile" class="text-[13px] font-bold text-white hidden sm:block hover:text-[#118B50] bg-[#2A523D] px-4 py-2.5 rounded-[12px] transition-colors">Resident <?php echo htmlspecialchars($firstName); ?></a>


                    <a href="/brgy-waste-app-v3/public/auth/logout" class="flex items-center gap-2.5 px-3 py-1 rounded-full hover:bg-red-50 transition-colors ">


                        <div class="w-[34px] h-[34px] rounded-full border border-red-200 flex items-center justify-center bg-gray-50 text-slate-500 shadow-sm group-hover:border-red-200 group-hover:bg-red-50 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            
                        </div>
                        
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10 space-y-8 md:space-y-10 pb-24 md:pb-12">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-5 bg-[#2A523D] px-12 py-6 rounded-[12px]">
            <div>
                <h1 class="text-[32px] font-extrabold text-[#F0F0F0] tracking-tight leading-tight mb-0.5">Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
                <p class="text-[15px] text-slate-300 font-medium">Here's your report summary</p>
            </div>
            <a href="/brgy-waste-app-v3/public/resident/submit" class="inline-flex flex-shrink-0 items-center justify-center gap-2 bg-[#0e7442] hover:bg-[#1e3c2c] text-white px-5 py-[11px] rounded-[10px] font-bold text-[14px] transition-colors shadow-sm shadow-[#2A523D]/20 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Report Waste
            </a>
        </div>

        <!-- Summary Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            <!-- Total Reports -->
            <div class="bg-white rounded-[16px] p-5 shadow-[0_2px_12px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col gap-2 relative overflow-hidden group hover:border-[#118B50]/30 transition-colors">
                <div class="flex items-center gap-4 w-full">
                    <div class="w-[42px] h-[42px] rounded-[10px] bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                    </div>
                    <div class="flex flex-col pt-0.5">
                        <div class="text-[26px] font-extrabold text-slate-800 leading-none tracking-tight"><?php echo $data['stats']['total'] ?? 0; ?></div>
                        <div class="text-[13px] font-semibold text-slate-400 mt-1 uppercase tracking-wide">Total Reports</div>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-[16px] p-5 shadow-[0_2px_12px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col gap-2 relative overflow-hidden group hover:border-amber-200 transition-colors">
                <div class="flex items-center gap-4 w-full">
                    <div class="w-[42px] h-[42px] rounded-[10px] bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="flex flex-col pt-0.5">
                        <div class="text-[26px] font-extrabold text-slate-800 leading-none tracking-tight"><?php echo $data['stats']['pending'] ?? 0; ?></div>
                        <div class="text-[13px] font-semibold text-slate-400 mt-1 uppercase tracking-wide">Pending</div>
                    </div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="bg-white rounded-[16px] p-5 shadow-[0_2px_12px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col gap-2 relative overflow-hidden group hover:border-emerald-200 transition-colors">
                <div class="flex items-center gap-4 w-full">
                    <div class="w-[42px] h-[42px] rounded-[10px] bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500 shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="flex flex-col pt-0.5">
                        <div class="text-[26px] font-extrabold text-slate-800 leading-none tracking-tight"><?php echo $data['stats']['resolved'] ?? 0; ?></div>
                        <div class="text-[13px] font-semibold text-slate-400 mt-1 uppercase tracking-wide">Resolved</div>
                    </div>
                </div>
            </div>

            <!-- Verified -->
            <div class="bg-white rounded-[16px] p-5 shadow-[0_2px_12px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col gap-2 relative overflow-hidden group hover:border-blue-200 transition-colors">
                <div class="flex items-center gap-4 w-full">
                    <div class="w-[42px] h-[42px] rounded-[10px] bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 shrink-0 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="flex flex-col pt-0.5">
                        <div class="text-[26px] font-extrabold text-slate-800 leading-none tracking-tight"><?php echo $data['stats']['verified'] ?? 0; ?></div>
                        <div class="text-[13px] font-semibold text-slate-400 mt-1 uppercase tracking-wide">Verified</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports Section -->
        <div class="pt-2">
            <div class="flex justify-between items-end mb-4 md:mb-5">
                <h2 class="text-[19px] font-extrabold text-[#111827]">Recent Reports</h2>
                <a href="/brgy-waste-app-v3/public/resident/my_report" class="text-[#118B50] font-bold text-[13.5px] hover:underline">View all</a>
            </div>
            
            <div class="space-y-4">
                <?php if(!empty($data['reports'])): ?>
                    <?php $recent = array_slice($data['reports'], 0, 3); // Get top 3 ?>
                    <?php foreach($recent as $report): ?>
                        <?php 
                            $statusColors = [
                                'pending' => ['bg' => 'amber-50', 'text' => 'amber-600', 'dot' => 'amber-500'],
                                'verified' => ['bg' => 'blue-50', 'text' => 'blue-600', 'dot' => 'blue-500'],
                                'resolved' => ['bg' => 'emerald-50', 'text' => 'emerald-600', 'dot' => 'emerald-500']
                            ];
                            $color = $statusColors[$report['status']] ?? $statusColors['pending'];
                            $imgPath = !empty($report['photo_path']) ? '/brgy-waste-app-v3/public/uploads/' . $report['photo_path'] : 'https://placehold.co/150x150?text=No+Image';
                        ?>
                        <a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>" class="block">
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 bg-white border border-gray-200/80 rounded-[18px] p-4 md:p-5 shadow-sm hover:shadow-md transition-shadow relative">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-[90px] md:h-[90px] rounded-[14px] overflow-hidden shrink-0 border border-gray-100 bg-gray-50 flex items-center justify-center">
                                    <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Thumbnail" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1 gap-2">
                                        <span class="text-[12.5px] font-mono text-slate-400 tracking-tight font-medium">RPT-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                        <div class="absolute top-4 right-4 sm:static sm:top-auto sm:right-auto">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-<?php echo $color['bg']; ?> text-<?php echo $color['text']; ?> rounded-full text-[11.5px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-<?php echo $color['dot']; ?>"></span>
                                                <?php echo ucfirst($report['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <h3 class="text-[15px] font-semibold text-slate-800 leading-snug truncate sm:whitespace-normal mb-1 sm:mb-2 max-w-4xl"><?php echo htmlspecialchars($report['description']); ?></h3>
                                    <div class="text-[12px] text-slate-400 font-medium"><?php echo date('M j, Y', strtotime($report['created_at'])); ?></div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-slate-500 py-4 text-[14px]">You have no recent reports.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Waste Map Section -->
        <div class="pt-2">
            <div class="flex justify-between items-end mb-4 md:mb-5">
                <h2 class="text-[19px] font-extrabold text-[#111827] flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#118B50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Waste Reports Map
                </h2>
            </div>
            
            <div class="w-full h-[320px] md:h-[400px] lg:h-[450px] rounded-[20px] md:rounded-[24px] border border-gray-200 shadow-sm overflow-hidden relative bg-gray-50 flex flex-col ">
                <div id="dashboardMap" class="w-full h-full flex-1 z-0 relative z-0 outline-none"></div>
            </div>
            
        </div>

    </main>
</div>

<!-- Mobile Bottom Navigation (only visible < md screens) -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/95 backdrop-blur-md border-t border-gray-200/60 pt-2.5 pb-6 px-1 z-50 flex justify-between items-end shadow-[0_-10px_20px_rgba(0,0,0,0.03)]">
    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2A523D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
        <span class="text-[10.5px] font-extrabold text-[#2A523D] tracking-wide">Home</span>
    </a>
    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Reports</span>
    </a>
    <div class="flex-1 flex justify-center sticky z-50">
        <a href="/brgy-waste-app-v3/public/resident/submit" class="flex flex-col items-center relative -top-[22px] group transform active:scale-95 transition-all">
            <div class="w-[58px] h-[58px] rounded-full bg-[#2A523D] flex items-center justify-center border-[5px] border-[#f9fafb] shadow-md text-white mb-1 group-hover:bg-[#1e3c2c]">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <span class="text-[10.5px] font-extrabold tracking-wide text-[#2A523D]">Report</span>
        </a>
    </div>
    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">News</span>
    </a>
    <a href="/brgy-waste-app-v3/public/resident/profile" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Profile</span>
    </a>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Leaflet.js Map Initialization with boundaries from user config
    const mapCenter = [15.558, 120.803]; 
    
    // Default Map Initialization
    const map = L.map('dashboardMap', {
        zoomControl: window.innerWidth >= 768, 
        dragging: window.innerWidth >= 768, 
        scrollWheelZoom: true,
        doubleClickZoom: false,
        touchZoom: window.innerWidth >= 768
    }).setView(mapCenter, 15);

    // Using a clean minimal basemap like CartoDB Positron equivalent or standard OSM with grayscale filter via CSS
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OS',
        className: 'map-tiles'
    }).addTo(map);

    // Dynamic clean map style injection
    var mapStyle = document.createElement('style');
    mapStyle.innerHTML = `
        .map-tiles { filter: grayscale(1) opacity(0.7); }
    `;
    document.head.appendChild(mapStyle);

    // Exact geo boundary configured
    var barangayGeoJSON = {
        "type": "FeatureCollection",
        "features": [
            {
            "type": "Feature",
            "properties": {},
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                [
                    [120.8013517, 15.5699279],
                    [120.8008898, 15.569572],
                    [120.8008276, 15.5686578],
                    [120.8006126, 15.5685788],
                    [120.8005542, 15.5678398],
                    [120.8001844, 15.5672858],
                    [120.8000725, 15.5668847],
                    [120.8001665, 15.566531],
                    [120.7995785, 15.5663685],
                    [120.7989717, 15.5657033],
                    [120.7987031, 15.5658025],
                    [120.7984537, 15.5654243],
                    [120.7980956, 15.5652],
                    [120.7977553, 15.5652043],
                    [120.7975135, 15.5652862],
                    [120.7971285, 15.5652259],
                    [120.7964691, 15.5648604],
                    [120.7961709, 15.5643821],
                    [120.795562, 15.5643993],
                    [120.7951681, 15.5637567],
                    [120.7953561, 15.5632478],
                    [120.7952523, 15.562581],
                    [120.7950598, 15.5617529],
                    [120.7950416, 15.5611835],
                    [120.7945939, 15.5608471],
                    [120.7946431, 15.5603295],
                    [120.7943504, 15.5596467],
                    [120.7937415, 15.5597848],
                    [120.7930393, 15.55916],
                    [120.7928646, 15.5570187],
                    [120.7921781, 15.555107],
                    [120.7912123, 15.554853],
                    [120.7913399, 15.5543176],
                    [120.7915605, 15.5533236],
                    [120.7918092, 15.5534046],
                    [120.8001316, 15.5478115],
                    [120.8011058, 15.5481325],
                    [120.8021398, 15.5484701],
                    [120.8027807, 15.5485113],
                    [120.8032508, 15.5489723],
                    [120.8030798, 15.5500426],
                    [120.8038043, 15.5501365],
                    [120.8044282, 15.5502517],
                    [120.8049495, 15.550614],
                    [120.8058211, 15.5508445],
                    [120.8062911, 15.551569],
                    [120.8071584, 15.5520964],
                    [120.8076635, 15.5520903],
                    [120.8081181, 15.5524005],
                    [120.8083454, 15.5523519],
                    [120.8085979, 15.5525708],
                    [120.8088668, 15.5528807],
                    [120.8118007, 15.5512389],
                    [120.8126332, 15.550257],
                    [120.8153176, 15.5523838],
                    [120.817434, 15.549628],
                    [120.8219183, 15.5518119],
                    [120.8232918, 15.5522367],
                    [120.8253946, 15.5516159],
                    [120.8260956, 15.5512188],
                    [120.8281375, 15.5526533],
                    [120.8298546, 15.5518644],
                    [120.8310955, 15.5519514],
                    [120.8335885, 15.5541358],
                    [120.8325752, 15.5557229],
                    [120.8326161, 15.5574083],
                    [120.8332704, 15.5602447],
                    [120.8283841, 15.5650646],
                    [120.8236492, 15.5703491],
                    [120.82189, 15.5689622],
                    [120.8219651, 15.5676998],
                    [120.8203353, 15.5645562],
                    [120.8205697, 15.5594636],
                    [120.8185042, 15.5617437],
                    [120.8149287, 15.5609879],
                    [120.8126889, 15.5623097],
                    [120.8092582, 15.5595308],
                    [120.8032464, 15.5673914],
                    [120.8014669, 15.5699463],
                    [120.8013468, 15.5699463],
                    [120.8013468, 15.5699463]
                ]
            ]
            }
        }
        ]
    };
    
    L.geoJSON(barangayGeoJSON, {
        style: {
            color: '#22c55e',     // Bright green dash 
            weight: 2.5,
            fillColor: '#ecfdf5', // Extremely light green fill
            fillOpacity: 0.15,    
            dashArray: '6, 6'
        }
    }).addTo(map);

    // Marker Pins populated from database
    const mapPins = <?php echo json_encode($data['map_pins'] ?? []); ?>;
    
    mapPins.forEach(pin => {
        let color = '#f59e0b'; // pending amber
        if (pin.status === 'verified') color = '#3b82f6'; // blue
        if (pin.status === 'resolved') color = '#10b981'; // green

        const markerHtml = `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`;
        const icon = L.divIcon({
            html: markerHtml,
            className: '',
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });
        L.marker([pin.latitude, pin.longitude], { icon: icon }).addTo(map);
    });

    // Ensure map tiles load perfectly on viewport resizes
    setTimeout(function() { map.invalidateSize(); }, 100);
    window.addEventListener("resize", function() { map.invalidateSize(); });
});
</script>

<?php include __DIR__ . '/../layouts/notification-panel.php'; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
