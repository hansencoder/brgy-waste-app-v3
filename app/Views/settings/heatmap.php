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
                            <a href="<?php echo app_url('admin/gis'); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs sm:text-sm font-extrabold transition border border-emerald-200 shadow-xs">
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
                            
                            <form method="POST" id="heatmapForm" action="<?php echo app_url('settings/heatmap'); ?>" class="space-y-6">
                                
                                <!-- ============================================================ -->
                                <!-- CARD 1: CLUSTER RADIUS & PRESETS                             -->
                                <!-- ============================================================ -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6">
                                    
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Spatial Dispersion &amp; Cluster Radius</h2>
                                            </div>
                                            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">Controls the geographic smoothing distance (kernel radius) used to group neighboring reports.</p>
                                        </div>
                                        
                                        <!-- Active Badge -->
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Radius:</span>
                                            <span class="text-xs sm:text-sm font-mono font-extrabold text-emerald-900 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-300 shadow-2xs">
                                                <span id="radiusValueBadge"><?php echo (int)($data['settings']['radius_meters'] ?? 50); ?></span> meters
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                                        <!-- Slider Column (7 cols) -->
                                        <div class="lg:col-span-7 bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <label for="radiusSlider" class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Adjust Radius Distance</label>
                                                <span class="text-[11px] font-bold text-slate-400">15m – 150m</span>
                                            </div>

                                            <div class="space-y-2">
                                                <input type="range" id="radiusSlider" min="15" max="150" step="5" 
                                                       value="<?php echo (int)($data['settings']['radius_meters'] ?? 50); ?>" 
                                                       class="w-full h-2.5 bg-slate-200 rounded-lg cursor-pointer transition">
                                                <input type="hidden" name="radius_meters" id="radiusInput" value="<?php echo (int)($data['settings']['radius_meters'] ?? 50); ?>">
                                                
                                                <div class="flex justify-between text-[11px] font-bold text-slate-500">
                                                    <span class="flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> 15m (Tight / Localized)
                                                    </span>
                                                    <span class="flex items-center gap-1 text-emerald-700 font-extrabold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> 50m (Recommended)
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> 150m (Wide Area)
                                                    </span>
                                                </div>
                                            </div>

                                            <p class="text-xs text-slate-500 font-medium pt-2 border-t border-slate-200/80">
                                                Waste reports within this circular distance will blend together into a single continuous thermal gradient.
                                            </p>
                                        </div>

                                        <!-- Presets Column (5 cols) -->
                                        <div class="lg:col-span-5 space-y-2.5">
                                            <label class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block">Quick Presets</label>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-2">
                                                <button type="button" onclick="applyPreset('default')" 
                                                        class="p-2.5 px-3.5 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-left transition flex items-center justify-between group cursor-pointer">
                                                    <div>
                                                        <p class="text-xs font-extrabold text-slate-900 group-hover:text-emerald-950">Standard Balanced</p>
                                                        <p class="text-[10px] font-semibold text-slate-500">50m radius · 3–5 / 6–10 / 11+</p>
                                                    </div>
                                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 group-hover:border-emerald-300">50m</span>
                                                </button>

                                                <button type="button" onclick="applyPreset('dense')" 
                                                        class="p-2.5 px-3.5 rounded-xl bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-300 text-left transition flex items-center justify-between group cursor-pointer">
                                                    <div>
                                                        <p class="text-xs font-extrabold text-slate-900 group-hover:text-amber-950">Dense Urban</p>
                                                        <p class="text-[10px] font-semibold text-slate-500">30m radius · 2–4 / 5–8 / 9+</p>
                                                    </div>
                                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 group-hover:border-amber-300">30m</span>
                                                </button>

                                                <button type="button" onclick="applyPreset('wide')" 
                                                        class="p-2.5 px-3.5 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 text-left transition flex items-center justify-between group cursor-pointer">
                                                    <div>
                                                        <p class="text-xs font-extrabold text-slate-900 group-hover:text-blue-950">Wide Rural / Macro</p>
                                                        <p class="text-[10px] font-semibold text-slate-500">85m radius · 5–8 / 9–15 / 16+</p>
                                                    </div>
                                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 group-hover:border-blue-300">85m</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- ============================================================ -->
                                <!-- CARD 2: HOTSPOT DENSITY TIERS & THERMAL PALETTE             -->
                                <!-- ============================================================ -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-6">
                                    
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Hotspot Density Intervals &amp; Thermal Colors</h2>
                                            </div>
                                            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">Define incident report volume intervals and custom thermal gradient colors for each intensity tier.</p>
                                        </div>

                                        <span class="text-xs font-mono font-bold text-amber-900 bg-amber-100 px-3 py-1 rounded-full border border-amber-300 self-start sm:self-auto">
                                            3 Intensity Tiers
                                        </span>
                                    </div>
                                    <input type="hidden" name="minimum_reports" id="thresholdInput" value="<?php echo (int)($data['settings']['low_min'] ?? $data['settings']['minimum_reports'] ?? 3); ?>">

                                    <!-- Live Gradient Ramp Bar -->
                                    <div class="p-4 bg-slate-900 rounded-2xl space-y-2.5 text-white border border-slate-800 shadow-sm">
                                        <div class="flex items-center justify-between text-xs font-mono text-slate-400">
                                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full" style="background-color: <?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>;"></span> Tier 1: Low Heat (0.2)</span>
                                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full" style="background-color: <?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>;"></span> Tier 2: Moderate Heat (0.6)</span>
                                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full" style="background-color: <?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>;"></span> Tier 3: Critical Heat (1.0)</span>
                                        </div>
                                        <div id="gradientRampBar" class="h-6 rounded-xl shadow-inner border border-white/20 transition-all"
                                             style="background: linear-gradient(to right, <?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>, <?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>, <?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>);">
                                        </div>
                                    </div>

                                    <!-- 3 Spacious Tier Cards Grid -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        
                                        <!-- TIER 1: LOW DENSITY -->
                                        <div class="bg-slate-50 hover:bg-white p-5 rounded-2xl border border-slate-200 hover:border-amber-300 transition-all space-y-4 shadow-2xs">
                                            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-3 h-3 rounded-full border border-slate-300 shrink-0" style="background-color: <?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>"></span>
                                                    <h3 class="font-extrabold text-slate-900 text-sm">1. Low Density</h3>
                                                </div>
                                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-900 border border-yellow-300">
                                                    Initial Tier
                                                </span>
                                            </div>
                                            
                                            <!-- Interval Range Inputs -->
                                            <div class="space-y-1.5">
                                                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Incident Count Range</label>
                                                <div class="flex items-center gap-2">
                                                    <div class="relative flex-1">
                                                        <input type="number" min="1" max="50" name="low_min" id="inputLowMin" 
                                                               value="<?php echo (int)($data['settings']['low_min'] ?? 3); ?>" 
                                                               class="w-full px-3 py-2 text-center bg-white border border-slate-300 rounded-xl text-sm font-extrabold text-slate-800 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition">
                                                        <span class="text-[10px] font-bold text-slate-400 absolute left-2 top-0.5">Min</span>
                                                    </div>
                                                    <span class="text-slate-400 font-bold text-sm">to</span>
                                                    <div class="relative flex-1">
                                                        <input type="number" min="1" max="50" name="low_max" id="inputLowMax" 
                                                               value="<?php echo (int)($data['settings']['low_max'] ?? 5); ?>" 
                                                               class="w-full px-3 py-2 text-center bg-white border border-slate-300 rounded-xl text-sm font-extrabold text-slate-800 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition">
                                                        <span class="text-[10px] font-bold text-slate-400 absolute left-2 top-0.5">Max</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Color Picker & HEX Input -->
                                            <div class="pt-3 border-t border-slate-200 space-y-1.5">
                                                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Assigned Color</label>
                                                <div class="flex items-center gap-3">
                                                    <input type="color" id="lowColor" name="low_density_color" 
                                                           value="<?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>"
                                                           class="color-circle-picker shrink-0">
                                                    <div class="relative flex-1">
                                                        <span class="absolute left-3 top-2 text-slate-400 text-xs font-mono font-bold">#</span>
                                                        <input type="text" id="lowColorHex" value="<?php echo htmlspecialchars(ltrim($data['settings']['low_density_color'] ?? 'FDE68A', '#')); ?>"
                                                               class="w-full pl-7 pr-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-mono font-extrabold text-slate-800 uppercase focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TIER 2: MODERATE DENSITY -->
                                        <div class="bg-slate-50 hover:bg-white p-5 rounded-2xl border border-slate-200 hover:border-orange-300 transition-all space-y-4 shadow-2xs">
                                            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-3 h-3 rounded-full border border-slate-300 shrink-0" style="background-color: <?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>"></span>
                                                    <h3 class="font-extrabold text-slate-900 text-sm">2. Moderate Density</h3>
                                                </div>
                                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                                                    Active Cluster
                                                </span>
                                            </div>

                                            <!-- Interval Range Inputs -->
                                            <div class="space-y-1.5">
                                                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Incident Count Range</label>
                                                <div class="flex items-center gap-2">
                                                    <div class="relative flex-1">
                                                        <input type="number" min="2" max="100" name="moderate_min" id="inputModMin" 
                                                               value="<?php echo (int)($data['settings']['moderate_min'] ?? 6); ?>" 
                                                               class="w-full px-3 py-2 text-center bg-white border border-slate-300 rounded-xl text-sm font-extrabold text-slate-800 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition">
                                                        <span class="text-[10px] font-bold text-slate-400 absolute left-2 top-0.5">Min</span>
                                                    </div>
                                                    <span class="text-slate-400 font-bold text-sm">to</span>
                                                    <div class="relative flex-1">
                                                        <input type="number" min="2" max="100" name="moderate_max" id="inputModMax" 
                                                               value="<?php echo (int)($data['settings']['moderate_max'] ?? 10); ?>" 
                                                               class="w-full px-3 py-2 text-center bg-white border border-slate-300 rounded-xl text-sm font-extrabold text-slate-800 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition">
                                                        <span class="text-[10px] font-bold text-slate-400 absolute left-2 top-0.5">Max</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Color Picker & HEX Input -->
                                            <div class="pt-3 border-t border-slate-200 space-y-1.5">
                                                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Assigned Color</label>
                                                <div class="flex items-center gap-3">
                                                    <input type="color" id="mediumColor" name="medium_density_color" 
                                                           value="<?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>"
                                                           class="color-circle-picker shrink-0">
                                                    <div class="relative flex-1">
                                                        <span class="absolute left-3 top-2 text-slate-400 text-xs font-mono font-bold">#</span>
                                                        <input type="text" id="mediumColorHex" value="<?php echo htmlspecialchars(ltrim($data['settings']['medium_density_color'] ?? 'F97316', '#')); ?>"
                                                               class="w-full pl-7 pr-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-mono font-extrabold text-slate-800 uppercase focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TIER 3: CRITICAL / SEVERE -->
                                        <div class="bg-slate-50 hover:bg-white p-5 rounded-2xl border border-slate-200 hover:border-red-300 transition-all space-y-4 shadow-2xs">
                                            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-3 h-3 rounded-full border border-slate-300 shrink-0" style="background-color: <?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>"></span>
                                                    <h3 class="font-extrabold text-slate-900 text-sm">3. Critical / Severe</h3>
                                                </div>
                                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-red-100 text-red-900 border border-red-300">
                                                    Severe Hotspot
                                                </span>
                                            </div>

                                            <!-- Interval Range Inputs -->
                                            <div class="space-y-1.5">
                                                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Minimum Incident Threshold</label>
                                                <div class="relative">
                                                    <div class="flex items-center">
                                                        <span class="px-3 py-2 bg-slate-200 border border-r-0 border-slate-300 rounded-l-xl text-xs font-extrabold text-slate-700">&ge;</span>
                                                        <input type="number" min="3" max="200" name="severe_min" id="inputSevMin" 
                                                               value="<?php echo (int)($data['settings']['severe_min'] ?? 11); ?>" 
                                                               class="w-full px-3 py-2 text-center bg-white border border-slate-300 rounded-r-xl text-sm font-extrabold text-slate-800 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition">
                                                    </div>
                                                </div>
                                                <span class="text-[10px] text-slate-500 font-semibold block text-right">Any cluster with &ge; this count</span>
                                            </div>

                                            <!-- Color Picker & HEX Input -->
                                            <div class="pt-3 border-t border-slate-200 space-y-1.5">
                                                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block">Assigned Color</label>
                                                <div class="flex items-center gap-3">
                                                    <input type="color" id="highColor" name="high_density_color" 
                                                           value="<?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>"
                                                           class="color-circle-picker shrink-0">
                                                    <div class="relative flex-1">
                                                        <span class="absolute left-3 top-2 text-slate-400 text-xs font-mono font-bold">#</span>
                                                        <input type="text" id="highColorHex" value="<?php echo htmlspecialchars(ltrim($data['settings']['high_density_color'] ?? 'EF4444', '#')); ?>"
                                                               class="w-full pl-7 pr-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-mono font-extrabold text-slate-800 uppercase focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Sub-Threshold Alert Banner -->
                                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-950 flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-xl bg-emerald-200/80 text-emerald-900 flex items-center justify-center shrink-0 border border-emerald-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                        </div>
                                        <div class="leading-relaxed">
                                            <strong class="font-extrabold text-emerald-950 block">Spatial Sub-Threshold Suppression Rule:</strong>
                                            Areas with fewer reports than <span class="font-mono font-bold underline">Low Min (<span id="alertMinText"><?php echo (int)($data['settings']['low_min'] ?? 3); ?></span>)</span> will display incident pins only without generating thermal heatmap blobs. This ensures isolated citizen reports do not trigger false alarm hotspot alerts.
                                        </div>
                                    </div>

                                </div>

                                <!-- ============================================================ -->
                                <!-- CARD 3: LIVE REAL-TIME MAP SIMULATOR                         -->
                                <!-- ============================================================ -->
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Real-Time Heatmap Simulation Preview</h2>
                                            </div>
                                            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">Live demonstration of how your radius and gradient values render on the map.</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-extrabold bg-blue-50 text-blue-900 border border-blue-200">
                                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                                Live Sync
                                            </span>
                                        </div>
                                    </div>

                                    <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner" style="height: 380px;">
                                        <div id="simMap" class="w-full h-full"></div>
                                        
                                        <!-- Overlay info -->
                                        <div class="absolute bottom-4 left-4 z-[400] bg-slate-900/90 backdrop-blur-md text-white px-3.5 py-2 rounded-xl text-xs font-bold border border-slate-800 shadow-md flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                            <span>Simulating cluster samples at Dulong Bayan</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex flex-wrap items-center gap-3 pt-2">
                                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold rounded-xl shadow-md hover:shadow-lg transition active:scale-[0.98] border border-emerald-900 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Save Heatmap Settings
                                    </button>
                                    <button type="button" onclick="applyPreset('default')" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white hover:bg-slate-100 text-slate-800 text-xs sm:text-sm font-extrabold rounded-xl transition border border-slate-300 shadow-2xs cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        Reset to Standard
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
    
    const thresholdInput = document.getElementById('thresholdInput');
    const alertMinText = document.getElementById('alertMinText');

    const lowColor = document.getElementById('lowColor');
    const lowHex = document.getElementById('lowColorHex');
    const mediumColor = document.getElementById('mediumColor');
    const mediumHex = document.getElementById('mediumColorHex');
    const highColor = document.getElementById('highColor');
    const highHex = document.getElementById('highColorHex');
    const gradientRamp = document.getElementById('gradientRampBar');

    const inputLowMin = document.getElementById('inputLowMin');
    const inputLowMax = document.getElementById('inputLowMax');
    const inputModMin = document.getElementById('inputModMin');
    const inputModMax = document.getElementById('inputModMax');
    const inputSevMin = document.getElementById('inputSevMin');

    // 1. Initialize Simulation Map
    const defaultCenter = [
        <?php echo (float)($data['map_center']['lat'] ?? 15.558); ?>, 
        <?php echo (float)($data['map_center']['lng'] ?? 120.803); ?>
    ];
    const defaultZoom = <?php echo (int)($data['map_center']['zoom'] ?? 15); ?>;

    const map = L.map('simMap', {
        center: defaultCenter,
        zoom: defaultZoom,
        zoomControl: true,
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
            0.2: low,
            0.6: med,
            1.0: high
        };

        simHeatLayer = L.heatLayer(samplePoints, {
            radius: radius,
            blur: Math.round(radius * 0.45),
            maxZoom: 17,
            minOpacity: 0.35,
            gradient: gradientConfig
        }).addTo(map);
    }

    // Slider event listener
    radiusSlider?.addEventListener('input', function() {
        radiusInput.value = this.value;
        radiusBadge.textContent = this.value;
        updateSimulation();
    });

    // Min input sync
    inputLowMin?.addEventListener('input', function() {
        if (thresholdInput) thresholdInput.value = this.value;
        if (alertMinText) alertMinText.textContent = this.value;
    });

    // Color Pickers sync with Hex Inputs
    function bindColorSync(colorEl, hexEl) {
        colorEl.addEventListener('input', function() {
            hexEl.value = this.value.replace('#', '').toUpperCase();
            updateSimulation();
        });
        hexEl.addEventListener('input', function() {
            let val = this.value.trim();
            if (!val.startsWith('#')) val = '#' + val;
            if (/^#[0-9A-F]{6}$/i.test(val)) {
                colorEl.value = val;
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
            lowColor.value = '#FDE68A';
            mediumColor.value = '#F97316';
            highColor.value = '#EF4444';
            if (inputLowMin) inputLowMin.value = 3;
            if (inputLowMax) inputLowMax.value = 5;
            if (inputModMin) inputModMin.value = 6;
            if (inputModMax) inputModMax.value = 10;
            if (inputSevMin) inputSevMin.value = 11;
        } else if (presetName === 'dense') {
            radiusSlider.value = 30;
            lowColor.value = '#FEF08A';
            mediumColor.value = '#EA580C';
            highColor.value = '#DC2626';
            if (inputLowMin) inputLowMin.value = 2;
            if (inputLowMax) inputLowMax.value = 4;
            if (inputModMin) inputModMin.value = 5;
            if (inputModMax) inputModMax.value = 8;
            if (inputSevMin) inputSevMin.value = 9;
        } else if (presetName === 'wide') {
            radiusSlider.value = 85;
            lowColor.value = '#BAE6FD';
            mediumColor.value = '#F59E0B';
            highColor.value = '#B91C1C';
            if (inputLowMin) inputLowMin.value = 5;
            if (inputLowMax) inputLowMax.value = 8;
            if (inputModMin) inputModMin.value = 9;
            if (inputModMax) inputModMax.value = 15;
            if (inputSevMin) inputSevMin.value = 16;
        }

        radiusInput.value = radiusSlider.value;
        radiusBadge.textContent = radiusSlider.value;
        if (thresholdInput) thresholdInput.value = inputLowMin?.value || 3;
        if (alertMinText) alertMinText.textContent = inputLowMin?.value || 3;
        
        lowHex.value = lowColor.value.replace('#', '').toUpperCase();
        mediumHex.value = mediumColor.value.replace('#', '').toUpperCase();
        highHex.value = highColor.value.replace('#', '').toUpperCase();

        updateSimulation();
    };

    // Initial run
    updateSimulation();
    setTimeout(() => map.invalidateSize(), 300);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>