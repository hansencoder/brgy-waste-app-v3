<?php include '../app/Views/layouts/header.php'; ?>
<nav class="bg-sidebar text-sidebar-foreground shadow-md border-b border-sidebar-border">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-4">
                <span class="font-bold text-xl">Dulong Bayan Reporter</span>
                <a href="/brgy-waste-app-v3/public/resident" class="px-3 py-2 rounded-md hover:bg-sidebar-accent/90">My Reports</a>
                <a href="/brgy-waste-app-v3/public/resident/submit" class="px-3 py-2 rounded-md bg-sidebar-accent">Submit Report</a>
                <a href="/brgy-waste-app-v3/public/resident/announcements" class="px-3 py-2 rounded-md hover:bg-sidebar-accent/90">Announcements</a>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm">Hi, <?php echo $_SESSION['user_name']; ?></span>
                <a href="/brgy-waste-app-v3/public/auth/logout" class="px-3 py-2 bg-destructive text-destructive-foreground hover:opacity-90 rounded-md text-sm font-semibold">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-3xl mx-auto px-4 py-8 flex-grow">
    <h1 class="text-3xl font-bold text-foreground mb-6">Submit Waste Report</h1>

    <div class="bg-card rounded-xl border border-border shadow-lg p-6">
        <?php if (!empty($data['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p><?php echo $data['error']; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><?php echo $data['success']; ?></p>
            </div>
        <?php endif; ?>

        <form action="/brgy-waste-app-v3/public/resident/submit" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">1. Upload Photo (JPG, PNG. Max 5MB)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('photo').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <span class="relative cursor-pointer bg-card rounded-md font-medium text-primary hover:text-primary/80 focus-within:outline-none focus-within:ring-2 focus-within:ring-primary pt-1">
                                Upload a file
                            </span>
                        </div>
                    </div>
                </div>
                <input id="photo" name="photo" type="file" class="hidden" accept="image/jpeg,image/png" required onchange="previewImage(event)">
                <img id="preview" class="mt-4 hidden h-48 w-full object-cover rounded shadow" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="description">2. Description of the Waste Issue</label>
                <textarea id="description" name="description" rows="4" required minlength="10" maxlength="500" placeholder="Describe what you see..."
                    class="w-full px-4 py-3 rounded-lg border border-border focus:ring-2 focus:ring-primary outline-none bg-background"></textarea>
                <p class="text-xs text-gray-500 mt-1">Between 10 and 500 characters.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">3. Location (Pin on Map or Auto-Detect)</label>
                <div class="flex space-x-2 mb-2">
                    <button type="button" onclick="getLocation()" class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm hover:bg-blue-200 transition">
                        📍 Auto-detect GPS
                    </button>
                    <span id="loc-status" class="text-sm text-gray-500 py-1"></span>
                </div>
                <div id="map" class="h-64 rounded border border-gray-300 z-0"></div>
                <input type="hidden" id="latitude" name="latitude" required>
                <input type="hidden" id="longitude" name="longitude" required>
            </div>

            <button type="submit" class="w-full bg-primary text-primary-foreground font-semibold py-3 px-4 rounded-md shadow-md hover:bg-primary/90 transition">
                Submit Report
            </button>
        </form>
    </div>
</div>

<script>
    // Image Preview
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Leaflet.js Map Initialization
    var map = L.map('map').setView([14.6760, 121.0437], 15); // Default to Dulong Bayan approx center
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    var marker = L.marker([14.6760, 121.0437], {draggable: true}).addTo(map);
    
    // Default form value
    document.getElementById('latitude').value = 14.6760;
    document.getElementById('longitude').value = 121.0437;

    // Drag marker updates input
    marker.on('dragend', function(e) {
        var latLng = e.target.getLatLng();
        document.getElementById('latitude').value = latLng.lat;
        document.getElementById('longitude').value = latLng.lng;
    });

    // Auto-detect GPS
    function getLocation() {
        var status = document.getElementById('loc-status');
        if (navigator.geolocation) {
            status.innerHTML = "Locating...";
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                var newLatLng = new L.LatLng(lat, lng);
                marker.setLatLng(newLatLng);
                map.setView(newLatLng, 16);
                status.innerHTML = "Found!";
            }, function() {
                status.innerHTML = "GPS disabled/denied.";
            });
        } else {
            status.innerHTML = "Geolocation not supported.";
        }
    }
</script>
<?php include '../app/Views/layouts/footer.php'; ?>
