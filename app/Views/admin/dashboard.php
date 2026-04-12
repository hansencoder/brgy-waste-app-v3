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
        var map = L.map('map').setView([15.5656, 120.8010], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Add a pin for the Barangay
        L.marker([15.5656, 120.8010]).addTo(map).bindPopup("<b>Brgy. Dulong Bayan</b>").openPopup();

        // Add exact boundaries (GeoJSON)
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
                color: '#16a34a',     // Green outline
                weight: 3,
                fillColor: '#22c55e', // Green fill
                fillOpacity: 0.1      // Light transparent fill
            }
        }).addTo(map);

        // Heatmap Data Injection
        var heatData = [];
        <?php if(isset($data['heatmap'])): foreach($data['heatmap'] as $point): ?>
        heatData.push([<?php echo $point['latitude']; ?>, <?php echo $point['longitude']; ?>, 0.5]);
        <?php endforeach; endif; ?>
        
        if (heatData.length > 0) {
            L.heatLayer(heatData, {radius: 25, blur: 15}).addTo(map);
        } else {
            // Fake data for demonstration if no reports
            L.heatLayer([
                [15.5656, 120.8010, 0.5],
                [15.556, 120.813, 0.7]
            ], {radius: 25, blur: 15}).addTo(map);
        }
    });
</script>
            </div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
