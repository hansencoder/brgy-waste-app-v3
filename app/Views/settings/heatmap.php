<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Leaflet for Live Simulation Preview -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    
    /* Range Slider Styling */
    input[type=range] {
        accent-color: #059669;
    }

    /* Circular Color Picker Styling */
    .color-circle-picker {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 42px;
        height: 42px;
        border-radius: 50% !important;
        overflow: hidden;
        cursor: pointer;
        border: 2.5px solid #cbd5e1;
        padding: 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        transition: transform 0.15s ease, border-color 0.15s ease;
    }
    .color-circle-picker:hover {
        transform: scale(1.06);
        border-color: #10b981;
    }
    .color-circle-picker::-webkit-color-swatch-wrapper {
        padding: 0;
    }
    .color-circle-picker::-webkit-color-swatch {
        border: none;
        border-radius: 50%;
    }
    .color-circle-picker::-moz-color-swatch {
        border: none;
        border-radius: 50%;
    }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 w-full flex font-sans antialiased">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <!-- Layout Wrapper -->
    <div class="lg:flex lg:min-h-screen w-full">
        
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <!-- Main Scrollable Canvas -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- ============================================================ -->
                    <!-- 1. PAGE HEADER                                               -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="<?php echo app_url('settings'); ?>" class="text-xs sm:text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-amber-100 text-amber-900 border border-amber-300">
                                    Heatmap &amp; Spatial Rules
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                GIS Heatmap Configuration
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Configure cluster dispersion radius, report hotspot density thresholds, and custom thermal color gradients.
                            </p>
                        </div>

                        <!-- Header Action Buttons -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <a href="<?php echo app_url('admin/gis'); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs sm:text-sm font-extrabold transition border border-emerald-200 shadow-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                View Live GIS Map
                            </a>
                            <a href="<?php echo app_url('settings'); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-extrabold transition border border-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                Back to Hub
                            </a>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 2. ALERTS                                                    -->
                    <!-- ============================================================ -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-4 sm:p-5 bg-red-50 border border-red-200 text-red-950 rounded-2xl text-sm font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="p-4 sm:p-5 bg-emerald-50 border border-emerald-200 text-emerald-950 rounded-2xl text-sm font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- ============================================================ -->
                    <!-- 3. MAIN CONTENT: SETTINGS SIDEBAR + FORM + PREVIEW           -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col lg:flex-row gap-6 items-start">
                        <?php 
                        $activeTab = 'heatmap'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Main Form & Visualizer Grid -->
                        <div class="flex-1 min-w-0 space-y-6">
                            
                            <form method="POST" id="heatmapForm" class="space-y-6">
                                
                                <!-- FORM CARD: Spatial Parameters -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6">
                                    
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                                        <div>
                                            <h2 class="text-lg font-extrabold text-slate-900">Heatmap Dispersion &amp; Hotspot Thresholds</h2>
                                            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">Parameters control how incident clusters are aggregated on administrative maps.</p>
                                        </div>
                                        
                                        <!-- Quick Presets Dropdown -->
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Presets:</span>
                                            <button type="button" onclick="applyPreset('default')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold transition border border-slate-200">
                                                Standard
                                            </button>
                                            <button type="button" onclick="applyPreset('dense')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold transition border border-slate-200">
                                                Dense Urban
                                            </button>
                                            <button type="button" onclick="applyPreset('wide')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold transition border border-slate-200">
                                                Wide Area
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <!-- 1. Cluster Radius Slider & Input -->
                                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 space-y-3">
                                            <div class="flex items-center justify-between">
                                                <label class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Cluster Radius</label>
                                                <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full border border-emerald-300">
                                                    <span id="radiusValueBadge"><?php echo (int)($data['settings']['radius_meters'] ?? 50); ?></span> meters
                                                </span>
                                            </div>
                                            <input type="range" id="radiusSlider" min="15" max="150" step="5" 
                                                   value="<?php echo (int)($data['settings']['radius_meters'] ?? 50); ?>" 
                                                   class="w-full h-2 bg-slate-200 rounded-lg cursor-pointer transition">
                                            <input type="hidden" name="radius_meters" id="radiusInput" value="<?php echo (int)($data['settings']['radius_meters'] ?? 50); ?>">
                                            <div class="flex justify-between text-[11px] font-bold text-slate-400">
                                                <span>15m (Tight cluster)</span>
                                                <span>50m (Recommended)</span>
                                                <span>150m (Wide range)</span>
                                            </div>
                                            <p class="text-xs text-slate-500 font-semibold pt-1 border-t border-slate-200">
                                                Kernel smoothing radius used to merge adjacent waste reports into single hotspots.
                                            </p>
                                        </div>

                                        <!-- 2. Minimum Reports Threshold -->
                                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 space-y-3">
                                            <div class="flex items-center justify-between">
                                                <label class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Hotspot Minimum Reports</label>
                                                <span class="text-xs font-mono font-bold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-300">
                                                    &ge; <span id="thresholdValueBadge"><?php echo (int)($data['settings']['minimum_reports'] ?? 3); ?></span> reports
                                                </span>
                                            </div>
                                            <input type="range" id="thresholdSlider" min="1" max="10" step="1" 
                                                   value="<?php echo (int)($data['settings']['minimum_reports'] ?? 3); ?>" 
                                                   class="w-full h-2 bg-slate-200 rounded-lg cursor-pointer transition">
                                            <input type="hidden" name="minimum_reports" id="thresholdInput" value="<?php echo (int)($data['settings']['minimum_reports'] ?? 3); ?>">
                                            <div class="flex justify-between text-[11px] font-bold text-slate-400">
                                                <span>1 (Sensitive)</span>
                                                <span>3 (Balanced)</span>
                                                <span>10 (Strict)</span>
                                            </div>
                                            <p class="text-xs text-slate-500 font-semibold pt-1 border-t border-slate-200">
                                                Minimum incident reports inside cluster radius required before marking a zone as an Active Hotspot.
                                            </p>
                                        </div>

                                    </div>

                                    <!-- 3. Thermal Color Gradient Ramp Config -->
                                    <div class="space-y-4 pt-2">
                                        <div>
                                            <label class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Thermal Density Color Gradient</label>
                                            <p class="text-xs text-slate-500 font-semibold mt-0.5">Defines the visual heat spectrum from isolated incidents (Low) to severe cluster zones (High).</p>
                                        </div>

                                        <!-- Live Gradient Ramp Bar -->
                                        <div class="p-3 bg-slate-900 rounded-xl space-y-2 text-white border border-slate-800 shadow-2xs">
                                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400">
                                                <span>Low (0.0 Intensity)</span>
                                                <span>Medium (0.5 Intensity)</span>
                                                <span>High (1.0 Maximum Heat)</span>
                                            </div>
                                            <div id="gradientRampBar" class="h-6 rounded-lg shadow-inner border border-white/20 transition-all"
                                                 style="background: linear-gradient(to right, <?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>, <?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>, <?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>);">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                                            
                                            <!-- Low Density Color Picker -->
                                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                                                <span class="text-xs font-black text-slate-700 uppercase tracking-wider block">1. Low Density</span>
                                                <div class="flex items-center gap-3">
                                                    <input type="color" id="lowColor" name="low_density_color" 
                                                           value="<?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>"
                                                           class="color-circle-picker shrink-0">
                                                    <input type="text" id="lowColorHex" value="<?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>"
                                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 uppercase focus:border-emerald-500 outline-none">
                                                </div>
                                                <span class="text-[11px] font-semibold text-slate-500">Occasional scattered reports</span>
                                            </div>

                                            <!-- Medium Density Color Picker -->
                                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                                                <span class="text-xs font-black text-slate-700 uppercase tracking-wider block">2. Medium Density</span>
                                                <div class="flex items-center gap-3">
                                                    <input type="color" id="mediumColor" name="medium_density_color" 
                                                           value="<?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>"
                                                           class="color-circle-picker shrink-0">
                                                    <input type="text" id="mediumColorHex" value="<?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>"
                                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 uppercase focus:border-emerald-500 outline-none">
                                                </div>
                                                <span class="text-[11px] font-semibold text-slate-500">Moderate recurring clusters</span>
                                            </div>

                                            <!-- High Density Color Picker -->
                                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                                                <span class="text-xs font-black text-slate-700 uppercase tracking-wider block">3. High Density</span>
                                                <div class="flex items-center gap-3">
                                                    <input type="color" id="highColor" name="high_density_color" 
                                                           value="<?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>"
                                                           class="color-circle-picker shrink-0">
                                                    <input type="text" id="highColorHex" value="<?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>"
                                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 uppercase focus:border-emerald-500 outline-none">
                                                </div>
                                                <span class="text-[11px] font-semibold text-slate-500">Severe active hotspot zones</span>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <!-- LIVE REAL-TIME MAP SIMULATOR -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-4">
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                        <div>
                                            <h2 class="text-base font-extrabold text-slate-900">Real-Time Heatmap Simulation Preview</h2>
                                            <p class="text-xs text-slate-500 font-semibold">Live demonstration of how your radius and gradient values render on the map.</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                            Interactive Preview
                                        </span>
                                    </div>

                                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100" style="height: 320px;">
                                        <div id="simMap" class="w-full h-full"></div>
                                        
                                        <!-- Overlay info -->
                                        <div class="absolute bottom-3 left-3 z-[400] bg-slate-900/85 backdrop-blur-md text-white px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-800 shadow-md">
                                            <span>Simulating cluster samples at Dulong Bayan</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex items-center gap-3 pt-2">
                                    <button type="submit" class="inline-flex items-center gap-2 px-7 py-3.5 bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold rounded-xl shadow-xs transition active:scale-[0.98] border border-emerald-900 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Save Heatmap Settings
                                    </button>
                                    <a href="<?php echo app_url('settings'); ?>" class="inline-flex items-center px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-extrabold rounded-xl transition border border-slate-200 cursor-pointer">
                                        Cancel
                                    </a>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- Live Heatmap Visualizer Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Elements
    const radiusSlider = document.getElementById('radiusSlider');
    const radiusInput = document.getElementById('radiusInput');
    const radiusBadge = document.getElementById('radiusValueBadge');
    
    const thresholdSlider = document.getElementById('thresholdSlider');
    const thresholdInput = document.getElementById('thresholdInput');
    const thresholdBadge = document.getElementById('thresholdValueBadge');

    const lowColor = document.getElementById('lowColor');
    const lowHex = document.getElementById('lowColorHex');
    const mediumColor = document.getElementById('mediumColor');
    const mediumHex = document.getElementById('mediumColorHex');
    const highColor = document.getElementById('highColor');
    const highHex = document.getElementById('highColorHex');
    const gradientRamp = document.getElementById('gradientRampBar');

    // 1. Initialize Simulation Map
    const defaultCenter = [
        <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>, 
        <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>
    ];
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;

    const map = L.map('simMap', {
        center: defaultCenter,
        zoom: defaultZoom,
        zoomControl: false,
        attributionControl: false
    });

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    }).addTo(map);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    }).addTo(map);

    // Master Barangay Boundary
    const rawBrgyBoundary = <?php echo json_encode($data['barangay_boundary'] ?? null); ?>;
    if (rawBrgyBoundary) {
        try {
            const brgyGeo = (typeof rawBrgyBoundary === 'string') ? JSON.parse(rawBrgyBoundary) : rawBrgyBoundary;
            L.geoJSON(brgyGeo, {
                style: {
                    color: '#059669',
                    weight: 2.5,
                    fillColor: '#D1FAE5',
                    fillOpacity: 0.05,
                    dashArray: '6, 6'
                }
            }).addTo(map);
        } catch(e) {
            console.error('Error rendering master boundary in heatmap:', e);
        }
    }

    // Sample Incident Clusters
    const samplePoints = [
        [15.5582, 120.8031, 0.9],
        [15.5584, 120.8033, 0.8],
        [15.5580, 120.8028, 0.7],
        [15.5586, 120.8035, 1.0],
        [15.5578, 120.8039, 0.6],
        [15.5610, 120.8015, 0.5],
        [15.5612, 120.8018, 0.8],
        [15.5614, 120.8013, 0.9],
        [15.5550, 120.8060, 0.4],
        [15.5552, 120.8062, 0.5]
    ];

    let simHeatLayer = null;

    function updateSimulation() {
        const radius = parseInt(radiusInput.value) || 50;
        const low = lowColor.value;
        const med = mediumColor.value;
        const high = highColor.value;

        // Update ramp
        gradientRamp.style.background = `linear-gradient(to right, ${low}, ${med}, ${high})`;

        // Update leaflet heat
        if (simHeatLayer) {
            map.removeLayer(simHeatLayer);
        }

        const gradientConfig = {
            0.0: low,
            0.5: med,
            1.0: high
        };

        simHeatLayer = L.heatLayer(samplePoints, {
            radius: radius,
            blur: Math.round(radius * 0.45),
            maxZoom: 17,
            gradient: gradientConfig
        }).addTo(map);
    }

    // Sliders event listeners
    radiusSlider?.addEventListener('input', function() {
        radiusInput.value = this.value;
        radiusBadge.textContent = this.value;
        updateSimulation();
    });

    thresholdSlider?.addEventListener('input', function() {
        thresholdInput.value = this.value;
        thresholdBadge.textContent = this.value;
    });

    // Color Pickers sync with Hex Inputs
    function bindColorSync(colorEl, hexEl) {
        colorEl.addEventListener('input', function() {
            hexEl.value = this.value.toUpperCase();
            updateSimulation();
        });
        hexEl.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                colorEl.value = this.value;
                updateSimulation();
            }
        });
    }

    bindColorSync(lowColor, lowHex);
    bindColorSync(mediumColor, mediumHex);
    bindColorSync(highColor, highHex);

    // Presets
    window.applyPreset = function(presetName) {
        if (presetName === 'default') {
            radiusSlider.value = 50;
            thresholdSlider.value = 3;
            lowColor.value = '#FDE68A';
            mediumColor.value = '#F97316';
            highColor.value = '#EF4444';
        } else if (presetName === 'dense') {
            radiusSlider.value = 30;
            thresholdSlider.value = 2;
            lowColor.value = '#FEF08A';
            mediumColor.value = '#EA580C';
            highColor.value = '#DC2626';
        } else if (presetName === 'wide') {
            radiusSlider.value = 85;
            thresholdSlider.value = 5;
            lowColor.value = '#BAE6FD';
            mediumColor.value = '#F59E0B';
            highColor.value = '#B91C1C';
        }

        radiusInput.value = radiusSlider.value;
        radiusBadge.textContent = radiusSlider.value;
        thresholdInput.value = thresholdSlider.value;
        thresholdBadge.textContent = thresholdSlider.value;
        
        lowHex.value = lowColor.value.toUpperCase();
        mediumHex.value = mediumColor.value.toUpperCase();
        highHex.value = highColor.value.toUpperCase();

        updateSimulation();
    };

    // Initial run
    updateSimulation();
    setTimeout(() => map.invalidateSize(), 300);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>