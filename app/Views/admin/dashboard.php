<?php include '../app/Views/layouts/header.php'; ?>

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
    <!-- Header Section -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Secretary Dashboard</h1>
            <p class="text-gray-500 text-sm">Welcome back, Capt. Roberto Gomez! Here is what's happening in your barangay.</p>
        </div>
        <div class="flex space-x-3 text-sm">
            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                Post Announcement
            </button>
            <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md font-medium flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Data
            </button>
        </div>
    </div>

    <!-- 4 Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between items-stretch">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">New Reports Today</h3>
                    <p class="text-4xl font-bold text-gray-900">0</p>
                </div>
                <div class="bg-blue-50 p-2 rounded-lg text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-blue-500 text-sm font-medium hover:underline">View all reports →</a>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between items-stretch">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Pending Approvals</h3>
                    <p class="text-4xl font-bold text-gray-900">0</p>
                </div>
                <div class="bg-yellow-50 p-2 rounded-lg text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-yellow-600 text-sm font-medium hover:underline">Review registrations →</a>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between items-stretch">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Active Residents</h3>
                    <p class="text-4xl font-bold text-gray-900">2</p>
                </div>
                <div class="bg-green-50 p-2 rounded-lg text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-green-500 text-sm font-medium hover:underline">Manage residents →</a>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between items-stretch">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium mb-1">Resolution Rate</h3>
                    <p class="text-4xl font-bold text-gray-900">33%</p>
                </div>
                <div class="bg-purple-50 p-2 rounded-lg text-purple-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="#" class="text-purple-500 text-sm font-medium hover:underline">View analytics →</a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column (Reports Overview + Map) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Reports Overview</h2>
                    <a href="#" class="text-green-600 text-sm font-medium hover:underline">View all</a>
                </div>
                <div class="p-6">
                    <!-- Stats / Tabs -->
                    <div class="flex justify-between text-center mb-8 px-4">
                        <div>
                            <p class="text-2xl font-bold text-orange-400">1</p>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Pending</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-500">1</p>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">In Progress</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-500">1</p>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Resolved</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-red-500">0</p>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mt-1">Rejected</p>
                        </div>
                    </div>
                    
                    <!-- Report List -->
                    <div class="space-y-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-2 h-2 rounded-full bg-blue-500 mt-2 mr-3 flex-shrink-0"></div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">Report #r1</h4>
                                    <p class="text-xs text-gray-500 mt-1 truncate max-w-sm">Accumulated garbage along Mabini St. near the inte...</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">3/10/2024</span>
                        </div>
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-2 h-2 rounded-full bg-orange-400 mt-2 mr-3 flex-shrink-0"></div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">Report #r2</h4>
                                    <p class="text-xs text-gray-500 mt-1 truncate max-w-sm">Illegal dumping site at the vacant lot near the ch...</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">3/14/2024</span>
                        </div>
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-2 h-2 rounded-full bg-green-500 mt-2 mr-3 flex-shrink-0"></div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">Report #r3</h4>
                                    <p class="text-xs text-gray-500 mt-1 truncate max-w-sm">Clogged drainage causing flooding. Waste materials...</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">3/5/2024</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 overflow-hidden">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Barangay Map (Markers + Heat)</h2>
                    <a href="#" class="text-green-600 text-sm font-medium hover:underline">Open full map →</a>
                </div>
                <div class="w-full h-[300px] rounded-lg overflow-hidden border border-gray-200">
                    <div id="map" class="w-full h-full z-0"></div>
                </div>
            </div>
        </div>

        <!-- Right Column (Quick Actions + Recent Activity) -->
        <div class="space-y-8">
            <!-- Recent Activity -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <h2 class="text-lg font-bold text-gray-900 p-6 border-b border-gray-100">Recent Activity</h2>
                <div class="flex flex-col">
                    <div class="p-6 border-b border-gray-50 hover:bg-gray-50 transition-colors flex items-start">
                        <div class="bg-blue-50 text-blue-500 p-2 rounded-lg mr-4 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">New Report Submitted</h4>
                            <p class="text-xs text-gray-500 mt-1">Report #r2 from 2</p>
                            <p class="text-xs text-gray-400 mt-1">3/14/2024, 8:00:00 AM</p>
                        </div>
                    </div>
                    <div class="p-6 border-b border-gray-50 hover:bg-gray-50 transition-colors flex items-start">
                        <div class="bg-blue-50 text-blue-500 p-2 rounded-lg mr-4 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">New Report Submitted</h4>
                            <p class="text-xs text-gray-500 mt-1">Report #r1 from 1</p>
                            <p class="text-xs text-gray-400 mt-1">3/10/2024, 8:00:00 AM</p>
                        </div>
                    </div>
                    <div class="p-6 hover:bg-gray-50 transition-colors flex items-start">
                        <div class="bg-blue-50 text-blue-500 p-2 rounded-lg mr-4 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">New Report Submitted</h4>
                            <p class="text-xs text-gray-500 mt-1">Report #r3 from 1</p>
                            <p class="text-xs text-gray-400 mt-1">3/5/2024, 8:00:00 AM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Init Leaflet Map
        var map = L.map('map').setView([14.6060, 120.9837], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Heatmap Data Injection
        var heatData = [];
        <?php if(isset($data['heatmap'])): foreach($data['heatmap'] as $point): ?>
        heatData.push([<?php echo $point['latitude']; ?>, <?php echo $point['longitude']; ?>, 0.5]);
        <?php endforeach; endif; ?>
        
        if (heatData.length > 0) {
            L.heatLayer(heatData, {radius: 25, blur: 15}).addTo(map);
        } else {
            L.heatLayer([
                [14.606, 120.983, 0.5],
                [14.616, 120.993, 0.7]
            ], {radius: 25, blur: 15}).addTo(map);
        }
    });
</script>
            </div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
