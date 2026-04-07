<?php include '../app/Views/layouts/header.php'; ?>
<style>
    /* Sidebar styles */
    .sidebar {
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: hsl(215 60% 20%);
        color: hsl(210 40% 90%);
        border-right: 1px solid hsl(215 40% 30%);
        overflow-y: auto;
        z-index: 50;
    }
    .sidebar-nav {
        padding: 1rem;
    }
    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.75rem;
        border-radius: 0.375rem;
        color: hsl(210 40% 90%);
        text-decoration: none;
        transition: background-color 0.2s;
        margin-bottom: 0.25rem;
    }
    .sidebar-nav a:hover {
        background: hsl(215 50% 30%);
    }
    .sidebar-nav a.active {
        background: hsl(215 50% 30%);
        font-weight: 600;
    }
    .sidebar-nav a svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
    .nav-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: hsl(215 30% 60%);
        padding: 0 0.75rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    /* Main content area */
    .main-content {
        margin-left: 260px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    /* Top header bar */
    .top-header {
        height: 56px;
        background: hsl(0 0% 100%);
        border-bottom: 1px solid hsl(214 20% 88%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        position: sticky;
        top: 0;
        z-index: 40;
    }
    .top-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .top-header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .role-selector {
        padding: 0.375rem 0.75rem;
        border: 1px solid hsl(214 20% 88%);
        border-radius: 0.375rem;
        background: hsl(0 0% 100%);
        font-size: 0.875rem;
        color: hsl(215 60% 15%);
        cursor: pointer;
    }
    .icon-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        border: none;
        background: transparent;
        color: hsl(215 60% 15%);
        cursor: pointer;
        position: relative;
        transition: background-color 0.2s;
    }
    .icon-btn:hover {
        background: hsl(210 20% 93%);
    }
    .icon-btn svg {
        width: 20px;
        height: 20px;
    }
    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 16px;
        height: 16px;
        background: hsl(0 72% 51%);
        color: white;
        font-size: 0.625rem;
        font-weight: 700;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* Form content */
    .form-content {
        flex: 1;
        padding: 2rem;
        background: hsl(210 20% 97%);
    }
    .form-container {
        max-width: 560px;
        margin: 0 auto;
    }
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: hsl(215 60% 15%);
        margin-bottom: 0.25rem;
    }
    .form-subtitle {
        font-size: 0.875rem;
        color: hsl(215 16% 47%);
        margin-bottom: 1.5rem;
    }
    .form-section {
        background: hsl(0 0% 100%);
        border: 1px solid hsl(214 20% 88%);
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: hsl(215 60% 15%);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-title svg {
        width: 16px;
        height: 16px;
    }
    /* Upload area */
    .upload-area {
        border: 2px dashed hsl(214 20% 88%);
        border-radius: 0.5rem;
        padding: 2.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background-color 0.2s;
    }
    .upload-area:hover, .upload-area.dragover {
        border-color: hsl(215 60% 25%);
        background: hsl(210 20% 97%);
    }
    .upload-area svg {
        width: 32px;
        height: 32px;
        color: hsl(215 16% 47%);
        margin-bottom: 0.75rem;
    }
    .upload-text {
        font-size: 0.875rem;
        color: hsl(215 16% 47%);
    }
    .upload-hint {
        font-size: 0.75rem;
        color: hsl(215 16% 47%);
        margin-top: 0.25rem;
    }
    /* Textarea */
    .form-textarea {
        width: 100%;
        min-height: 100px;
        padding: 0.75rem;
        border: 1px solid hsl(214 20% 88%);
        border-radius: 0.375rem;
        font-size: 0.875rem;
        color: hsl(215 60% 15%);
        resize: vertical;
        font-family: inherit;
    }
    .form-textarea:focus {
        outline: none;
        border-color: hsl(215 60% 25%);
        box-shadow: 0 0 0 3px hsl(215 60% 25% / 0.1);
    }
    .form-textarea::placeholder {
        color: hsl(215 16% 47%);
    }
    .char-counter {
        text-align: right;
        font-size: 0.75rem;
        color: hsl(215 16% 47%);
        margin-top: 0.25rem;
    }
    /* Location buttons */
    .location-buttons {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .location-btn {
        flex: 1;
        padding: 0.625rem 1rem;
        border: 1px solid hsl(214 20% 88%);
        border-radius: 0.375rem;
        background: hsl(210 20% 97%);
        font-size: 0.875rem;
        color: hsl(215 60% 15%);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: background-color 0.2s;
    }
    .location-btn:hover {
        background: hsl(210 20% 93%);
    }
    .location-btn svg {
        width: 16px;
        height: 16px;
    }
    /* Map container */
    .map-container {
        width: 100%;
        height: 200px;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    #map {
        width: 100%;
        height: 100%;
    }
    /* Submit button */
    .submit-btn {
        width: 100%;
        padding: 0.875rem 1.5rem;
        background: hsl(215 60% 25%);
        color: hsl(210 40% 98%);
        border: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .submit-btn:hover {
        background: hsl(215 60% 20%);
    }
    /* Logo area */
    .sidebar-logo {
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 1px solid hsl(215 40% 30%);
    }
    .logo-icon {
        width: 36px;
        height: 36px;
        background: hsl(142 71% 45%);
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logo-icon svg {
        width: 20px;
        height: 20px;
        color: white;
    }
    .logo-text {
        display: flex;
        flex-direction: column;
    }
    .logo-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: hsl(210 40% 98%);
        line-height: 1.2;
    }
    .logo-subtitle {
        font-size: 0.75rem;
        color: hsl(215 30% 60%);
    }
</style>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="logo-text">
            <span class="logo-title">WasteWatch</span>
            <span class="logo-subtitle">Resident Portal</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Navigation</div>
        <a href="/brgy-waste-app-v3/public/resident/submit" class="active">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            Submit Report
        </a>
        <a href="/brgy-waste-app-v3/public/resident">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            My Reports
        </a>
        <a href="/brgy-waste-app-v3/public/resident/announcements">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            Announcements
        </a>
    </nav>
</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Header -->
    <header class="top-header">
        <div class="top-header-left">
            <button class="icon-btn" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M15 3v18"/></svg>
            </button>
        </div>
        <div class="top-header-right">
            <select class="role-selector">
                <option value="resident">Resident</option>
            </select>
            <button class="icon-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <span class="notification-badge">2</span>
            </button>
            <a href="/brgy-waste-app-v3/public/auth/logout" class="icon-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            </a>
        </div>
    </header>

    <!-- Form Content -->
    <div class="form-content">
        <div class="form-container">
            <h1 class="form-title">Submit Waste Report</h1>
            <p class="form-subtitle">Report waste issues in Barangay Dulong Bayan</p>

            <form id="reportForm" action="/brgy-waste-app-v3/public/resident/submit" method="POST" enctype="multipart/form-data">
                <!-- Photo Upload Section -->
                <div class="form-section">
                    <div class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        Photo of Waste
                    </div>
                    <div class="upload-area" id="uploadArea">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        <div class="upload-text">Click or drag to upload</div>
                        <div class="upload-hint">JPG, JPEG, PNG (max 5MB)</div>
                    </div>
                    <input type="file" id="photoInput" name="photo" accept=".jpg,.jpeg,.png" style="display: none;">
                    <div id="previewContainer" style="margin-top: 1rem; display: none;">
                        <img id="previewImage" style="max-width: 100%; max-height: 200px; border-radius: 0.375rem;" />
                        <button type="button" id="removePhoto" style="margin-top: 0.5rem; padding: 0.375rem 0.75rem; background: hsl(0 72% 51%); color: white; border: none; border-radius: 0.25rem; font-size: 0.75rem; cursor: pointer;">Remove</button>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="form-section">
                    <div class="section-title">Description</div>
                    <textarea class="form-textarea" id="description" name="description" placeholder="Describe the waste issue in detail (minimum 10 characters)..." maxlength="500" required></textarea>
                    <div class="char-counter"><span id="charCount">0</span>/500</div>
                </div>

                <!-- Location Section -->
                <div class="form-section">
                    <div class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        Location
                    </div>
                    <div class="location-buttons">
                        <button type="button" class="location-btn" id="detectGPS">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                            Detect GPS
                        </button>
                        <button type="button" class="location-btn" id="manualPin">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            Manual Pin
                        </button>
                    </div>
                    <div class="map-container">
                        <div id="map"></div>
                    </div>
                    <input type="hidden" id="latitude" name="latitude" required>
                    <input type="hidden" id="longitude" name="longitude" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">Submit Report</button>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize map
const map = L.map('map').setView([14.5995, 120.9842], 15); // Default to Philippines coordinates

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

let marker = null;

// Upload area functionality
const uploadArea = document.getElementById('uploadArea');
const photoInput = document.getElementById('photoInput');
const previewContainer = document.getElementById('previewContainer');
const previewImage = document.getElementById('previewImage');
const removePhoto = document.getElementById('removePhoto');

uploadArea.addEventListener('click', () => {
    photoInput.click();
});

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
});

photoInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFile(e.target.files[0]);
    }
});

function handleFile(file) {
    if (file.type.match('image.*')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            uploadArea.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

removePhoto.addEventListener('click', () => {
    photoInput.value = '';
    previewContainer.style.display = 'none';
    uploadArea.style.display = 'block';
});

// Character counter
const description = document.getElementById('description');
const charCount = document.getElementById('charCount');

description.addEventListener('input', () => {
    charCount.textContent = description.value.length;
});

// GPS Detection
document.getElementById('detectGPS').addEventListener('click', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            
            map.setView([lat, lng], 16);
            
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng]).addTo(map);
        }, (error) => {
            alert('Unable to detect your location. Please use Manual Pin option.');
        });
    }
});

// Manual Pin
let manualMode = false;
document.getElementById('manualPin').addEventListener('click', () => {
    manualMode = !manualMode;
    if (manualMode) {
        map.getContainer().style.cursor = 'crosshair';
    } else {
        map.getContainer().style.cursor = '';
    }
});

map.on('click', (e) => {
    if (manualMode) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        
        manualMode = false;
        map.getContainer().style.cursor = '';
    }
});

// Sidebar toggle (for mobile)
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
}
</script>

<?php include '../app/Views/layouts/footer.php'; ?>
