<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
        <div class="flex-1 flex flex-col min-w-0">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
            
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto space-y-6">
                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                System Administration
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">System Settings</h1>
                            <p class="text-sm text-slate-500 mt-1">Configure barangay details, report validation, heatmap, exports & GIS boundaries.</p>
                        </div>
                    </div>

                    <!-- Layout: Sub-Sidebar + Category Grid Content -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'barangay'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Category Overview Cards -->
                        <div class="flex-1 min-w-0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <a href="/brgy-waste-app-v3/public/settings/barangay" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M3 7v14"/><path d="M21 7v14"/><path d="M6 18h12"/><path d="M6 14h12"/><path d="M6 10h12"/><path d="M12 3L2 7h20L12 3z"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-emerald-600 group-hover:translate-x-1 transition-transform">Configure →</span>
                                        </div>
                                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">🏛️ Barangay Info</h2>
                                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Update official barangay name, municipality, region, contact numbers & email address.</p>
                                    </div>
                                </a>

                                <a href="/brgy-waste-app-v3/public/settings/report_form" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-blue-600 group-hover:translate-x-1 transition-transform">Configure →</span>
                                        </div>
                                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-blue-700 transition-colors">📋 Report Form Settings</h2>
                                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Manage waste categories, photo upload requirements, file sizes, and duplicate checks.</p>
                                    </div>
                                </a>

                                <a href="/brgy-waste-app-v3/public/settings/heatmap" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-orange-600 group-hover:translate-x-1 transition-transform">Configure →</span>
                                        </div>
                                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-orange-700 transition-colors">🔥 Heatmap Configuration</h2>
                                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Adjust GIS heatmap cluster radius, report density minimums, and color intensity thresholds.</p>
                                    </div>
                                </a>

                                <a href="/brgy-waste-app-v3/public/settings/report_generation" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-purple-600 group-hover:translate-x-1 transition-transform">Configure →</span>
                                        </div>
                                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-purple-700 transition-colors">📄 Report Generation</h2>
                                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Set printable headers, footers, official signatory name, position and legal disclaimer.</p>
                                    </div>
                                </a>

                                <a href="/brgy-waste-app-v3/public/settings/landmarks" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-rose-600 group-hover:translate-x-1 transition-transform">Configure →</span>
                                        </div>
                                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-rose-700 transition-colors">🗺️ Map Landmarks</h2>
                                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Add and pin key barangay landmarks (Barangay Hall, MRF facility, Eco Center) on GIS maps.</p>
                                    </div>
                                </a>

                                <a href="/brgy-waste-app-v3/public/settings/purok_boundaries" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-500/50 transition-all duration-200 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-teal-600 group-hover:translate-x-1 transition-transform">Configure →</span>
                                        </div>
                                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-teal-700 transition-colors">📐 Purok Boundaries</h2>
                                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Draw and update interactive map polygon boundaries for each purok zone in the barangay.</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>