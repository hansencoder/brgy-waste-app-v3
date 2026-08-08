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
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-700 text-xs font-bold rounded-full border border-orange-200 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                GIS Configuration
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Heatmap Configuration</h1>
                            <p class="text-sm text-slate-500 mt-1">Configure cluster radius, report density threshold minimums, and hotspot color gradients for GIS monitoring.</p>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm font-semibold shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Layout: Settings Category Sub-Sidebar + Form Content -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'heatmap'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Main Content Card -->
                        <div class="flex-1 min-w-0">
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                                <div class="border-b border-slate-100 pb-5 mb-6">
                                    <h2 class="text-lg font-bold text-slate-900">GIS Heatmap & Hotspot Settings</h2>
                                    <p class="text-xs text-slate-500 mt-1">Changes made here affect GIS map monitoring and hotspot calculations across administrator & supervisor dashboards.</p>
                                </div>

                                <form method="POST" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Cluster Radius (Meters)</label>
                                            <input type="number" name="radius_meters" value="<?php echo (int)($data['settings']['radius_meters'] ?? 50); ?>" required
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                            <p class="text-[10px] text-slate-400 mt-1">Geographic radius distance to group nearby reports together.</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Minimum Reports for Hotspot</label>
                                            <input type="number" name="minimum_reports" value="<?php echo (int)($data['settings']['minimum_reports'] ?? 3); ?>" required
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                            <p class="text-[10px] text-slate-400 mt-1">Minimum report count within radius to trigger an active hotspot alert.</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Low Density Color</label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" name="low_density_color" value="<?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?>"
                                                       class="h-10 w-16 p-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                                                <span class="text-xs font-mono font-bold text-slate-600"><?php echo htmlspecialchars($data['settings']['low_density_color'] ?? '#FDE68A'); ?></span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Medium Density Color</label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" name="medium_density_color" value="<?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?>"
                                                       class="h-10 w-16 p-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                                                <span class="text-xs font-mono font-bold text-slate-600"><?php echo htmlspecialchars($data['settings']['medium_density_color'] ?? '#F97316'); ?></span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">High Density Color</label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" name="high_density_color" value="<?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?>"
                                                       class="h-10 w-16 p-1 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                                                <span class="text-xs font-mono font-bold text-slate-600"><?php echo htmlspecialchars($data['settings']['high_density_color'] ?? '#EF4444'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-slate-100 flex items-center gap-3">
                                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-[#07281E] text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-emerald-800 transition shadow-lg shadow-emerald-950/10">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Save Heatmap Config
                                        </button>
                                        <a href="/brgy-waste-app-v3/public/settings" class="px-5 py-3 bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-200 transition">
                                            Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>