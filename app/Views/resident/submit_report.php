<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';
?>

<div class="min-h-screen bg-[#f9fafb] w-full font-sans antialiased text-slate-800 flex flex-col">

    <!-- Top Navbar (Matching Dashboard) -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm shrink-0">
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
                    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/></svg>
                        Home
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        Reports
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="flex items-center gap-2 bg-[#2A523D] text-white px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] shadow-sm shadow-[#118B50]/20 transition-all">
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 flex-1 w-full flex flex-col mb-24 md:mb-0">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-[32px] font-extrabold text-[#111827] tracking-tight leading-tight mb-1">Submit Waste Report</h1>
            <p class="text-[15px] text-slate-500 font-medium">Report waste issues in Barangay Dulong Bayan.</p>
        </div>

        <?php if (!empty($data['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl shadow-sm mb-6 flex gap-3 text-[14px] font-medium items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm mb-6 flex gap-3 text-[14px] font-medium items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <?php echo $data['success']; ?>
            </div>
        <?php endif; ?>

        <!-- Location Out of Bounds Error Overlay -->
        <div id="locationError" class="fixed top-4 left-1/2 -translate-x-1/2 max-w-lg w-[calc(100%-2rem)] bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-xl shadow-lg flex gap-3 text-[14px] font-medium items-start hidden z-[9998] transition-all duration-300 animate-in fade-in slide-in-from-top-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <div class="flex-1">
                <p class="font-bold">Location Out of Bounds</p>
                <p class="text-[13px] font-normal text-amber-700">This location is outside Barangay Dulong Bayan. Please select a location within the green boundary.</p>
            </div>
            <button type="button" onclick="closeLocationError()" class="shrink-0 text-amber-600 hover:text-amber-800 transition-colors p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- GPS Permission Error Overlay -->
        <div id="gpsError" class="fixed top-4 left-1/2 -translate-x-1/2 max-w-lg w-[calc(100%-2rem)] bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl shadow-lg flex gap-3 text-[14px] font-medium items-start hidden z-[9998] transition-all duration-300 animate-in fade-in slide-in-from-top-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <div class="flex-1">
                <p class="font-bold">GPS Access Denied</p>
                <p class="text-[13px] font-normal text-red-500">Camera or location permission denied. Please click on the map to pin your location manually.</p>
            </div>
            <button type="button" onclick="closeGpsError()" class="shrink-0 text-red-600 hover:text-red-800 transition-colors p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Geolocation Not Supported Error Overlay -->
        <div id="geoNotSupportedError" class="fixed top-4 left-1/2 -translate-x-1/2 max-w-lg w-[calc(100%-2rem)] bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl shadow-lg flex gap-3 text-[14px] font-medium items-start hidden z-[9998] transition-all duration-300 animate-in fade-in slide-in-from-top-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <div class="flex-1">
                <p class="font-bold">Geolocation Not Supported</p>
                <p class="text-[13px] font-normal text-red-500">Your browser doesn't support geolocation. Please click on the map to pin your location manually.</p>
            </div>
            <button type="button" onclick="closeGeoNotSupportedError()" class="shrink-0 text-red-600 hover:text-red-800 transition-colors p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Camera Permission Error Overlay -->
        <div id="cameraError" class="fixed top-4 left-1/2 -translate-x-1/2 max-w-lg w-[calc(100%-2rem)] bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl shadow-lg flex gap-3 text-[14px] font-medium items-start hidden z-[9998] transition-all duration-300 animate-in fade-in slide-in-from-top-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <div class="flex-1">
                <p class="font-bold">Camera Access Denied</p>
                <p class="text-[13px] font-normal text-red-500">Camera permission was denied. Please use the Browse Files option to upload a photo instead.</p>
            </div>
            <button type="button" onclick="closeCameraError()" class="shrink-0 text-red-600 hover:text-red-800 transition-colors p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Location Not Selected Error Overlay -->
        <div id="noLocationError" class="fixed top-4 left-1/2 -translate-x-1/2 max-w-lg w-[calc(100%-2rem)] bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl shadow-lg flex gap-3 text-[14px] font-medium items-start hidden z-[9998] transition-all duration-300 animate-in fade-in slide-in-from-top-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <div class="flex-1">
                <p class="font-bold">Location Required</p>
                <p class="text-[13px] font-normal text-red-500">Please click on the map or use Detect GPS to pin your waste location.</p>
            </div>
            <button type="button" onclick="closeNoLocationError()" class="shrink-0 text-red-600 hover:text-red-800 transition-colors p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Form Layout -->
        <form id="reportForm" action="/brgy-waste-app-v3/public/resident/submit" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                <!-- LEFT COLUMN -->
                <div class="flex flex-col gap-6">
                    
                    <!-- A. Photo Upload Card -->
                    <div class="bg-white border border-gray-200/80 rounded-[20px] p-5 shadow-sm flex flex-col">
                        <label class="block text-[15px] font-bold text-slate-800 mb-4">Photo of Waste</label>
                        
                        <div id="drop-area" class="relative flex-1 min-h-[220px] rounded-[16px] border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 hover:border-[#118B50]/50 transition-all cursor-pointer flex flex-col items-center justify-center p-6 text-center group overflow-hidden" onclick="document.getElementById('photoInput').click()">
                            
                            <div id="upload-content" class="flex flex-col items-center gap-2">
                                <div class="w-14 h-14 rounded-full bg-white shadow-sm flex items-center justify-center text-[#118B50] group-hover:scale-110 transition-transform mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-slate-700">Click or drag image to upload</p>
                                    <p class="text-[12px] text-slate-400 font-medium mt-1">PNG, JPG up to 5MB</p>
                                </div>
                                <div class="flex gap-2 mt-2 relative z-20" onclick="event.stopPropagation()">
                                    <button type="button" onclick="openWebcam()" class="flex items-center gap-1.5 bg-[#2A523D] text-white px-3 py-1.5 rounded-lg text-[12px] font-bold hover:bg-[#1e3c2c] transition-colors focus:outline-none shadow-sm shadow-[#2A523D]/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                        Use Camera
                                    </button>
                                    <button type="button" onclick="document.getElementById('photoInput').click()" class="flex items-center gap-1.5 bg-white border border-gray-200 text-slate-700 px-3 py-1.5 rounded-lg text-[12px] font-bold hover:bg-gray-50 transition-colors focus:outline-none shadow-sm">
                                        Browse Files
                                    </button>
                                </div>
                            </div>

                            <img id="imagePreview" class="absolute inset-0 w-full h-full object-cover hidden z-10" alt="Preview">
                            <button type="button" id="removeImageBtn" class="absolute top-3 right-3 z-20 w-8 h-8 rounde-half bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg hidden " title="Remove image">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                        
                        <input id="photoInput" name="photo" type="file" class="hidden" accept="image/jpeg,image/png">
                        <div id="photoError" class="text-red-500 text-[12px] font-bold mt-2 hidden">Please select a valid image under 5MB.</div>
                    </div>

                    <!-- B. Description Card -->
                    <div class="bg-white border border-gray-200/80 rounded-[20px] p-5 shadow-sm flex flex-col pt-5">
                        <label class="block text-[15px] font-bold text-slate-800 mb-3" for="description">Issue Description</label>
                        <textarea id="description" name="description" rows="5" minlength="10" maxlength="500" 
                                placeholder="Describe the waste issue in detail... e.g., location specifics, type of waste, foul odor..."
                                class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-[14px] text-[14px] text-slate-700 placeholder:text-slate-400 outline-none focus:bg-white focus:border-[#118B50] focus:ring-4 focus:ring-[#118B50]/10 transition-all resize-none"></textarea>
                        
                        <div class="flex justify-between items-center mt-2 px-1">
                            <span id="descError" class="text-red-500 text-[12px] font-bold hidden">Minimum 10 characters required.</span>
                            <span id="charCount" class="text-[12px] font-bold text-slate-400 ml-auto transition-colors">0/500</span>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN -->
                <div class="flex flex-col h-full gap-5 bg-white border border-gray-200/80 rounded-[20px] p-5 shadow-sm">


                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-1">
                        <label class="text-[15px] font-bold text-slate-800">Pin Location</label>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="button" onclick="detectGPS()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-[#10b981]/10 hover:bg-[#10b981]/20 text-[#10b981] px-3.5 py-2 rounded-xl text-[12.5px] font-bold transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                Detect GPS
                            </button>
                            <div class="hidden sm:block w-px h-6 bg-gray-200"></div>
                            <span class="text-[12px] font-semibold text-slate-400 hidden sm:block">or click map to pin</span>
                        </div>
                    </div>
                    
                    <div id="locStatus" class="text-[12px] font-bold text-[#118B50] hidden">Location updated!</div>

                    <!-- MAP -->
                    <div class="w-full h-[300px] lg:h-full min-h-[350px] bg-slate-100 rounded-[14px] border border-gray-200 relative overflow-hidden flex flex-col shrink-0">
                        <div id="mapContainer" class="w-full h-full z-0 relative flex-1 outline-none"></div>
                    </div>

                    <input type="hidden" id="latitude" name="latitude" required>
                    <input type="hidden" id="longitude" name="longitude" required>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="mt-2 w-full pb-4">
                <button type="submit" id="submitBtn" class="w-full bg-[#2A523D] hover:bg-[#1e3c2c] active:scale-[0.99] text-white font-bold py-4 rounded-[14px] shadow-[0_4px_14px_rgba(42,82,61,0.3)] transition-all flex justify-center items-center gap-2 text-[15px]">
                    <span id="btnText">Submit Report</span>
                    <svg id="btnSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <div id="formErrorGlobal" class="text-center text-red-500 font-bold text-[13px] mt-4 hidden">Please fix the errors above before submitting.</div>
            </div>

        </form>
    </main>
</div>

<!-- Mobile Bottom Navigation (only visible < md screens) -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/95 backdrop-blur-md border-t border-gray-200/60 pt-2.5 pb-6 px-1 z-50 flex justify-between items-end shadow-[0_-10px_20px_rgba(0,0,0,0.03)]">
    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Home</span>
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
            <span class="text-[10.5px] font-extrabold tracking-wide text-[#2A523D]">Submit</span>
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

<!-- Unsaved Changes Modal -->
<div id="unsavedModal" class="fixed inset-0 z-[9999] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-[20px] shadow-2xl max-w-sm w-full overflow-hidden transform transition-all">
            <!-- Header -->
            <div class="p-6 pb-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[18px] font-bold text-slate-800 mb-1">Unsaved Report</h3>
                        <p class="text-[14px] text-slate-500 font-medium leading-relaxed">You have a report that hasn't been submitted. Are you sure you want to leave? Your progress will be lost.</p>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="px-6 pb-6 flex gap-3">
                <button onclick="closeModal()" class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold rounded-[12px] transition-colors text-[14px]">
                    Cancel
                </button>
                <button id="confirmLeave" class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-[12px] transition-colors text-[14px] shadow-lg shadow-red-600/20">
                    Discard
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Webcam Modal -->
<div id="webcamModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-[14px] p-6 max-w-md w-full shadow-2xl flex flex-col items-center">
        <h3 class="text-lg font-bold text-[#118B50] mb-4 w-full flex justify-between items-center">
            Take a Photo
            <button type="button" onclick="closeWebcam()" class="text-gray-400 hover:text-[#118B50] focus:outline-none bg-gray-50 hover:bg-green-50 rounded-full p-1.5 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </h3>
        
        <div class="relative w-full aspect-video bg-black rounded-lg overflow-hidden mb-6 shadow-inner">
            <video id="webcamVideo" autoplay playsinline class="w-full h-full object-cover"></video>
            <canvas id="webcamCanvas" class="hidden"></canvas>
        </div>
        
        <button type="button" id="captureBtn" class="w-16 h-16 bg-white border-4 border-[#118B50] rounded-full shadow-lg flex items-center justify-center hover:bg-green-50 transition-colors focus:outline-none mb-2" onclick="captureWebcam()">
            <div class="w-12 h-12 bg-[#118B50] rounded-full"></div>
        </button>
        <p class="text-[13px] text-gray-500 font-medium text-center">Ensure the waste issue is clearly visible.</p>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-[24px] shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-[20px] font-extrabold text-slate-800 tracking-tight">Review Your Report</h3>
                <p class="text-[13px] text-slate-500 font-medium">Please verify all details before final submission.</p>
            </div>
            <button type="button" onclick="closePreview()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-[#118B50] hover:bg-green-50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left: Photo -->
                <div class="flex flex-col gap-3">
                    <span class="text-[12px] font-bold text-slate-400 uppercase tracking-wider">ATTACHED PHOTO</span>
                    <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 border border-gray-100 shadow-sm relative">
                        <img id="previewImage" class="w-full h-full object-cover" src="" alt="Waste Preview">
                    </div>
                </div>

                <!-- Right: Map -->
                <div class="flex flex-col gap-3">
                    <span class="text-[12px] font-bold text-slate-400 uppercase tracking-wider">PINNED LOCATION</span>
                    <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 border border-gray-100 shadow-sm relative">
                        <div id="miniMap" class="w-full h-full z-0"></div>
                        <div class="absolute bottom-3 left-3 z-[1000] bg-white/90 backdrop-blur px-2 py-1 rounded-lg border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-600" id="previewCoords">15.560, 120.801</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-8 flex flex-col gap-3">
                <span class="text-[12px] font-bold text-slate-400 uppercase tracking-wider">ISSUE DESCRIPTION</span>
                <div class="p-4 bg-slate-50 rounded-2xl border border-gray-100 italic text-slate-700 text-[14.5px] leading-relaxed" id="previewDesc">
                    No description provided.
                </div>
            </div>

            <!-- Disclaimer -->
            <div class="mt-8 p-4 bg-green-50/50 rounded-2xl border border-green-100 flex gap-4">
                <div class="shrink-0 pt-0.5">
                    <input type="checkbox" id="confirmAccuracy" class="w-5 h-5 rounded border-green-300 text-[#118B50] focus:ring-[#118B50] cursor-pointer">
                </div>
                <label for="confirmAccuracy" class="text-[13.5px] text-slate-600 font-medium cursor-pointer leading-tight">
                    I confirm that this report is accurate and reflects a genuine waste management issue within Barangay Dulong Bayan.
                </label>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-slate-50/80 border-t border-gray-100 flex flex-col sm:flex-row gap-3 shrink-0">
            <button type="button" onclick="closePreview()" class="flex-1 px-6 py-3.5 bg-white border border-gray-200 text-slate-700 font-bold rounded-xl hover:bg-gray-50 transition-all text-[15px]">
                Back to Edit
            </button>
            <button type="button" id="finalSubmitBtn" onclick="handleFinalSubmit()" class="flex-1 px-6 py-3.5 bg-[#2A523D] text-white font-bold rounded-xl shadow-lg shadow-[#2A523D]/20 hover:bg-[#1e3c2c] active:scale-[0.98] transition-all text-[15px] flex items-center justify-center gap-2">
                <span>Confirm & Submit</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</div>

<!-- Scripts for interactive UI -->
<script>
    // --- UI Elements ---
    const dropArea = document.getElementById('drop-area');
    const photoInput = document.getElementById('photoInput');
    const imagePreview = document.getElementById('imagePreview');
    const uploadContent = document.getElementById('upload-content');
    const photoError = document.getElementById('photoError');
    const removeImageBtn = document.getElementById('removeImageBtn');
    
    const descInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const descError = document.getElementById('descError');
    
    const locStatus = document.getElementById('locStatus');
    const form = document.getElementById('reportForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const formErrorGlobal = document.getElementById('formErrorGlobal');

    let fileIsValid = false;
    let hasChanges = false;
    let pendingNavigation = null;

    // --- Track form changes ---
    function markAsChanged() {
        hasChanges = true;
    }

    // Track all form inputs
    descInput.addEventListener('input', markAsChanged);
    photoInput.addEventListener('change', markAsChanged);
    document.getElementById('latitude').addEventListener('input', markLocationChanged);
    document.getElementById('longitude').addEventListener('input', markLocationChanged);

    // --- Modal Functions ---
    function closeModal() {
        document.getElementById('unsavedModal').classList.add('hidden');
        pendingNavigation = null;
    }

    function showModal(callback) {
        pendingNavigation = callback;
        document.getElementById('unsavedModal').classList.remove('hidden');
    }

    // Confirm leave button
    document.getElementById('confirmLeave').addEventListener('click', function() {
        if (pendingNavigation) {
            closeModal();
            window.location.href = pendingNavigation;
        }
    });

    // --- Intercept Navigation Links ---
    function setupNavigationWarning() {
        // Get all nav links (both desktop and mobile)
        const navLinks = document.querySelectorAll('nav a[href*="/resident/"]');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // Don't intercept the current page link
                if (href === '/brgy-waste-app-v3/public/resident/submit') {
                    return;
                }

                // Only warn if there are changes
                if (hasChanges) {
                    e.preventDefault();
                    showModal(() => {
                        window.location.href = href;
                    });
                }
            });
        });
    }

    // --- Browser Back/Forward Button Warning ---
    function handleBeforeUnload(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    }
    
    window.addEventListener('beforeunload', handleBeforeUnload);

    // Setup on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        setupNavigationWarning();
    });

    // Mark as changed when map location is updated
    let isInitializing = true;
    function markLocationChanged() {
        if (!isInitializing) {
            markAsChanged();
        }
    }

    // --- Photo Upload Logic ---
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('bg-gray-100', 'border-[#118B50]');
    });
    
    dropArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropArea.classList.remove('bg-gray-100', 'border-[#118B50]');
    });
    
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('bg-gray-100', 'border-[#118B50]');
        if (e.dataTransfer.files.length) {
            photoInput.files = e.dataTransfer.files;
            handleFileSelection();
        }
    });

    photoInput.addEventListener('change', handleFileSelection);

    function handleFileSelection() {
        if (!photoInput.files || !photoInput.files.length) return;
        
        const file = photoInput.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        
        // Validation
        if (!validTypes.includes(file.type)) {
            showPhotoError("Must be JPG or PNG.");
            return;
        }
        if (file.size > 5 * 1024 * 1024) { // 5MB
            showPhotoError("File size exceeds 5MB.");
            return;
        }

        // Success Preview
        photoError.classList.add('hidden');
        dropArea.classList.remove('border-red-400');
        fileIsValid = true;

        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            uploadContent.classList.add('opacity-0'); // Hide upload layout text but keep clickable
            removeImageBtn.classList.remove('hidden');
            removeImageBtn.classList.add('flex');
        }
        reader.readAsDataURL(file);
    }

    function showPhotoError(msg) {
        fileIsValid = false;
        imagePreview.classList.add('hidden');
        uploadContent.classList.remove('opacity-0');
        removeImageBtn.classList.add('hidden');
        removeImageBtn.classList.remove('flex');
        photoError.textContent = msg;
        photoError.classList.remove('hidden');
        dropArea.classList.add('border-red-400');
        photoInput.value = ""; // clear
    }

    // --- Remove Image Button ---
    removeImageBtn.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent triggering dropArea click
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
        uploadContent.classList.remove('opacity-0');
        removeImageBtn.classList.add('hidden');
        removeImageBtn.classList.remove('flex');
        photoInput.value = '';
        fileIsValid = false;
        photoError.classList.add('hidden');
        dropArea.classList.remove('border-red-400');
        markAsChanged();
    });

    // --- Webcam Capture Logic ---
    let webcamStream = null;

    async function openWebcam() {
        const modal = document.getElementById('webcamModal');
        const video = document.getElementById('webcamVideo');
        
        try {
            webcamStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = webcamStream;
            modal.classList.remove('hidden');
        } catch (err) {
            console.error("Camera error:", err);
            showCameraError();
        }
    }

    function closeWebcam() {
        const modal = document.getElementById('webcamModal');
        const video = document.getElementById('webcamVideo');
        
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
            webcamStream = null;
        }
        video.srcObject = null;
        modal.classList.add('hidden');
    }

    function captureWebcam() {
        const video = document.getElementById('webcamVideo');
        const canvas = document.getElementById('webcamCanvas');
        const ctx = canvas.getContext('2d');
        
        // Match the canvas size to the video stream size
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Draw the current video frame to the canvas
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert canvas image to a File object
        canvas.toBlob((blob) => {
            if(!blob) return;
            const file = new File([blob], "waste_camera_capture.jpg", { type: "image/jpeg" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            
            // Assign new file to the hidden input
            photoInput.files = dataTransfer.files;
            
            // Trigger file handling logic
            handleFileSelection();
            
            closeWebcam();
        }, 'image/jpeg', 0.9);
    }

    // --- Description Counter ---
    descInput.addEventListener('input', function() {
        const len = this.value.length;
        charCount.textContent = `${len}/500`;
        if (len >= 500) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
        }
        if (len >= 10) {
            descError.classList.add('hidden');
            descInput.classList.remove('border-red-400');
        }
    });

    // --- Map Implementation ---
    // Wait for DOM to be fully ready
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Leaflet is loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded!');
            document.getElementById('mapContainer').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500 font-semibold">Map unavailable - Please click "Detect GPS" or refresh the page.</div>';
            return;
        }

        // Delay map initialization to ensure container is rendered
        setTimeout(function() {
            try {
                const defaultCenter = [15.560, 120.801]; // Dulong bayan approx map center
                
                // Initialize map only if not already initialized
                if (!window.mapInstance) {
                    window.mapInstance = L.map('mapContainer', {
                        center: defaultCenter,
                        zoom: 15,
                        zoomControl: true
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(window.mapInstance);

                    // Draggable Marker representing the user's report location
                    window.mapMarker = L.marker(defaultCenter, { draggable: true }).addTo(window.mapInstance);

                    // Custom red pin
                    const customIcon = L.divIcon({
                        html: `<div style="background-color: #ef4444; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.4), 0 0 0 2px rgba(239,68,68,0.2); animation: pulse 2s infinite;"></div>`,
                        className: '', 
                        iconSize: [16, 16], 
                        iconAnchor: [8, 8]
                    });
                    window.mapMarker.setIcon(customIcon);

                    // Attach map clicks to easily move marker
                    window.mapInstance.on('click', function(e) {
                        // Check if clicked location is within barangay boundary
                        if (!isInsideBarangay(e.latlng.lat, e.latlng.lng)) {
                            showLocationError();
                            return;
                        }
                        window.mapMarker.setLatLng(e.latlng);
                        updateLatLng(e.latlng.lat, e.latlng.lng);
                        showLocationSuccess();
                    });

                    window.mapMarker.on('dragend', function(e) {
                        const ll = window.mapMarker.getLatLng();
                        updateLatLng(ll.lat, ll.lng);
                    });

                    // Add Barangay Boundary
                    var barangayGeoJSON = {
                        "type": "FeatureCollection",
                        "features": [{
                            "type": "Feature", "properties": {"name": "Barangay Dulong Bayan"}, "geometry": {
                                "type": "Polygon",
                                "coordinates": [[
                                    [120.80135, 15.56992],[120.80018, 15.56728],[120.79897, 15.56570],[120.79751, 15.56528],[120.79516, 15.56375],[120.79464, 15.56032],[120.79121, 15.55485],[120.80013, 15.54781],[120.80494, 15.55061],[120.80886, 15.55288],[120.81743, 15.54962],[120.82609, 15.55121],[120.83358, 15.55413],[120.83261, 15.55740],[120.82838, 15.56506],[120.82364, 15.57034],[120.82033, 15.56455],[120.81492, 15.56098],[120.80324, 15.56739],[120.80135, 15.56992]
                                ]]
                            }
                        }]
                    };
                    var boundaryLayer = L.geoJSON(barangayGeoJSON, {
                        style: { color: '#22c55e', weight: 3, fillColor: '#10b981', fillOpacity: 0.08, dashArray: '8, 6' }
                    }).addTo(window.mapInstance);
                    
                    // Add boundary label
                    boundaryLayer.bindPopup("<b>Barangay Dulong Bayan</b><br><span style='font-size: 11px; color: #64748b;'>Reports can only be submitted within this area</span>");

                    // Ensure map renders correctly after container is visible
                    setTimeout(() => window.mapInstance.invalidateSize(), 200);
                }

                // Initial populate (don't trigger change detection)
                updateLatLng(defaultCenter[0], defaultCenter[1]);
                // Reset initialization flag after initial setup
                setTimeout(() => { isInitializing = false; }, 200);
            } catch (error) {
                console.error('Error initializing map:', error);
                document.getElementById('mapContainer').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500 font-semibold">Error loading map - Please use GPS detection or refresh.</div>';
            }
        }, 100);
    });

    // Hidden inputs population
    function updateLatLng(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        markLocationChanged();
    }

    // initial populate is done in DOMContentLoaded

    // --- Barangay Dulong Bayan Boundary Check ---
    // Polygon coordinates defining the barangay boundary
    const barangayBoundary = [
        [15.56992, 120.80135], [15.56728, 120.80018], [15.56570, 120.79897],
        [15.56528, 120.79751], [15.56375, 120.79516], [15.56032, 120.79464],
        [15.55485, 120.79121], [15.54781, 120.80013], [15.55061, 120.80494],
        [15.55288, 120.80886], [15.54962, 120.81743], [15.55121, 120.82609],
        [15.55413, 120.83358], [15.55740, 120.83261], [15.56506, 120.82838],
        [15.57034, 120.82364], [15.56455, 120.82033], [15.56098, 120.81492],
        [15.56739, 120.80324], [15.56992, 120.80135]
    ];

    // Point-in-polygon algorithm to check if location is within barangay
    function isInsideBarangay(lat, lng) {
        let inside = false;
        const x = lat, y = lng;
        
        for (let i = 0, j = barangayBoundary.length - 1; i < barangayBoundary.length; j = i++) {
            const xi = barangayBoundary[i][0], yi = barangayBoundary[i][1];
            const xj = barangayBoundary[j][0], yj = barangayBoundary[j][1];
            
            const intersect = ((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
        
        return inside;
    }

    function showLocationError() {
        closeGpsError();
        closeGeoNotSupportedError();
        closeCameraError();
        closeNoLocationError();
        const locErrorDiv = document.getElementById('locationError');
        locErrorDiv.classList.remove('hidden');
    }

    function closeLocationError() {
        document.getElementById('locationError').classList.add('hidden');
    }

    function showGpsError() {
        closeLocationError();
        closeGeoNotSupportedError();
        closeCameraError();
        closeNoLocationError();
        const gpsErrorDiv = document.getElementById('gpsError');
        gpsErrorDiv.classList.remove('hidden');
    }

    function closeGpsError() {
        document.getElementById('gpsError').classList.add('hidden');
    }

    function showGeoNotSupportedError() {
        closeLocationError();
        closeGpsError();
        closeCameraError();
        closeNoLocationError();
        const geoErrorDiv = document.getElementById('geoNotSupportedError');
        geoErrorDiv.classList.remove('hidden');
    }

    function closeGeoNotSupportedError() {
        document.getElementById('geoNotSupportedError').classList.add('hidden');
    }

    function showCameraError() {
        closeLocationError();
        closeGpsError();
        closeGeoNotSupportedError();
        closeNoLocationError();
        const cameraErrorDiv = document.getElementById('cameraError');
        cameraErrorDiv.classList.remove('hidden');
    }

    function closeCameraError() {
        document.getElementById('cameraError').classList.add('hidden');
    }

    function showNoLocationError() {
        closeLocationError();
        closeGpsError();
        closeGeoNotSupportedError();
        closeCameraError();
        const noLocErrorDiv = document.getElementById('noLocationError');
        noLocErrorDiv.classList.remove('hidden');
    }

    function closeNoLocationError() {
        document.getElementById('noLocationError').classList.add('hidden');
    }

    function detectGPS() {
        const btn = event.currentTarget;
        const ogContent = btn.innerHTML;
        btn.innerHTML = 'Locating...';

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                
                // Check if location is within barangay boundary
                if (!isInsideBarangay(lat, lng)) {
                    showLocationError();
                    btn.innerHTML = ogContent;
                    return;
                }
                
                const userLL = new L.LatLng(lat, lng);

                window.mapInstance.flyTo(userLL, 16, { duration: 1.5 });
                window.mapMarker.setLatLng(userLL);
                updateLatLng(lat, lng);

                btn.innerHTML = ogContent;
                showLocationSuccess();

            }, err => {
                showGpsError();
                btn.innerHTML = ogContent;
            });
        } else {
            showGeoNotSupportedError();
            btn.innerHTML = ogContent;
        }
    }

    function showLocationSuccess() {
        locStatus.classList.remove('hidden');
        setTimeout(() => locStatus.classList.add('hidden'), 3000);
    }

    window.addEventListener("resize", () => { 
        setTimeout(() => window.mapInstance && window.mapInstance.invalidateSize(), 150); 
    });

    // --- Validation & Submit Logic ---
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let valid = true;
        let locationError = null;

        // Check image
        if (!fileIsValid) {
            showPhotoError("Image is required.");
            valid = false;
        }

        // Check generic description
        if (descInput.value.length < 10) {
            descError.classList.remove('hidden');
            descInput.classList.add('border-red-400');
            valid = false;
        }

        // Check Location
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        if(!lat) {
            showNoLocationError();
            valid = false;
        } else {
            // Check if location is within barangay boundary
            if (!isInsideBarangay(parseFloat(lat), parseFloat(lng))) {
                showLocationError();
                valid = false;
            }
        }

        if (!valid) {
            formErrorGlobal.classList.remove('hidden');
            return;
        }

        formErrorGlobal.classList.add('hidden');
        showPreview();
    });

    // --- Preview Logic ---
    let miniMapInstance = null;
    let miniMapMarker = null;

    function showPreview() {
        const previewModal = document.getElementById('previewModal');
        const previewImage = document.getElementById('previewImage');
        const previewDesc = document.getElementById('previewDesc');
        const previewCoords = document.getElementById('previewCoords');
        
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        // Populate Data
        previewImage.src = imagePreview.src;
        previewDesc.textContent = descInput.value;
        previewCoords.textContent = `${lat}, ${lng}`;
        
        // Show Modal
        previewModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Initialize/Update Mini Map
        setTimeout(() => {
            if (!miniMapInstance) {
                miniMapInstance = L.map('miniMap', {
                    dragging: false,
                    scrollWheelZoom: false,
                    zoomControl: false,
                    attributionControl: false
                }).setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(miniMapInstance);
                
                const customIcon = L.divIcon({
                    html: `<div style="background-color: #ef4444; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                    className: '', 
                    iconSize: [12, 12], 
                    iconAnchor: [6, 6]
                });
                
                miniMapMarker = L.marker([lat, lng], {icon: customIcon}).addTo(miniMapInstance);
            } else {
                miniMapInstance.setView([lat, lng], 17);
                miniMapMarker.setLatLng([lat, lng]);
            }
            miniMapInstance.invalidateSize();
        }, 100);
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function handleFinalSubmit() {
        const checkbox = document.getElementById('confirmAccuracy');
        const finalBtn = document.getElementById('finalSubmitBtn');
        
        if (!checkbox.checked) {
            checkbox.classList.add('ring-2', 'ring-red-400');
            setTimeout(() => checkbox.classList.remove('ring-2', 'ring-red-400'), 1500);
            return;
        }

        // Disable UI
        finalBtn.setAttribute('disabled', 'true');
        finalBtn.innerHTML = `
            <span>Submitting...</span>
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;

        // Disable beforeunload warning
        window.removeEventListener('beforeunload', handleBeforeUnload);
        hasChanges = false;

        // Final Native Submit
        form.submit();
    }

</script>

<style>
/* Safe animation for marker pinging */
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

/* Map container styling */
#mapContainer {
    width: 100%;
    height: 100%;
    min-height: 350px;
    z-index: 1;
}

/* Ensure Leaflet map tiles are visible */
.leaflet-container {
    width: 100%;
    height: 100%;
    background: #ddd;
    font-family: inherit;
}

/* Grayscale map filter for cleaner look */
.leaflet-tile-pane {
    filter: grayscale(20%) opacity(0.9);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>

<?php include __DIR__ . '/../layouts/notification-panel.php'; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
