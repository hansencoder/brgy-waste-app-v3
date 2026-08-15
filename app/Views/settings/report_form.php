<?php
if (!isset($data) || !is_array($data)) {
    $data = [
        'error' => '',
        'success' => '',
        'settings' => [],
        'categories' => [],
        'quantities' => [],
        'conditions' => []
    ];
}
?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
</style>

<div class="min-h-screen bg-white text-slate-900 w-full flex font-sans antialiased">
    
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

                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-blue-100 text-blue-900 border border-blue-300">
                                    Report Form Rules
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Report Form Configuration
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Configure resident submission rules, file upload limits, and waste category management.
                            </p>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-5 bg-red-50 border-2 border-red-200 text-red-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Content Layout -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'report_form'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <div class="flex-1 min-w-0 space-y-6">
                            
                            <!-- Card 1: Validation Rules -->
                            <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 sm:p-8 shadow-xs space-y-6">
                                <div class="border-b border-slate-200 pb-5">
                                    <h2 class="text-xl font-extrabold text-slate-900">Validation &amp; Limit Rules</h2>
                                    <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">Control photo requirements, file size caps, duplicate detection ranges, and daily limits.</p>
                                </div>

                                <form method="POST" class="space-y-6">
                                    <input type="hidden" name="update_settings" value="1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Photo Requirement</label>
                                            <select name="photo_required" class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                                <option value="1" <?php echo ($data['settings']['photo_required'] ?? 1) ? 'selected' : ''; ?>>Yes (Required)</option>
                                                <option value="0" <?php echo !($data['settings']['photo_required'] ?? 1) ? 'selected' : ''; ?>>No (Optional)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Allowed File Types</label>
                                            <input type="text" name="allowed_file_types" value="<?php echo htmlspecialchars($data['settings']['allowed_file_types'] ?? 'jpg,jpeg,png'); ?>" 
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Max Upload Size (Bytes)</label>
                                            <input type="number" name="max_upload_size" value="<?php echo (int)($data['settings']['max_upload_size'] ?? 5242880); ?>" 
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold font-mono text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                            <p class="text-xs text-slate-500 font-semibold mt-1.5">Default 5242880 bytes (5 MB)</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Duplicate Distance (Meters)</label>
                                            <input type="number" name="duplicate_distance" value="<?php echo (int)($data['settings']['duplicate_distance'] ?? 50); ?>" 
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold font-mono text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Duplicate Time Window (Days)</label>
                                            <input type="number" name="duplicate_time_window" value="<?php echo (int)($data['settings']['duplicate_time_window'] ?? 7); ?>" 
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold font-mono text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Max Reports per Day (per Resident)</label>
                                            <input type="number" name="max_reports_per_day" value="<?php echo (int)($data['settings']['max_reports_per_day'] ?? 10); ?>" 
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold font-mono text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>

                                    </div>

                                    <div class="pt-6 border-t border-slate-200">
                                        <button type="submit" class="px-8 py-4 bg-[#0B2E22] hover:bg-[#093024] text-white text-base font-extrabold rounded-xl shadow-md transition active:scale-[0.98] flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Save Form Rules
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Card 2: Waste Categories Manager -->
                            <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 sm:p-8 shadow-xs space-y-6">
                                <div class="border-b border-slate-200 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <h2 class="text-xl font-extrabold text-slate-900">Manage Waste Categories</h2>
                                        <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">Add, edit, or remove active categories available on the resident report form.</p>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-900 font-mono font-extrabold text-sm border border-slate-250 self-start sm:self-auto">
                                        <?php echo count($data['categories']); ?> Categories
                                    </span>
                                </div>

                                <!-- Add Form -->
                                <form method="POST" class="bg-slate-50 p-5 rounded-2xl border-2 border-slate-250">
                                    <input type="hidden" name="add_category" value="1">
                                    <p class="text-base font-extrabold text-slate-900 mb-3">Add New Category</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                                        <div class="md:col-span-2">
                                            <input type="text" name="category_name" placeholder="Category Name (e.g. Yard Waste)" class="w-full px-4 py-3 bg-white border-2 border-slate-250 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-[#10B981]" required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <input type="text" name="category_description" placeholder="Description (optional)" class="w-full px-4 py-3 bg-white border-2 border-slate-250 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-[#10B981]">
                                        </div>
                                        <div>
                                            <button type="submit" class="w-full px-5 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-sm font-extrabold rounded-xl transition shadow-xs">
                                                + Add Category
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Table -->
                                <div class="overflow-x-auto rounded-2xl border-2 border-slate-250">
                                    <table class="w-full text-left border-collapse text-sm">
                                        <thead class="bg-slate-50 text-slate-500 font-extrabold uppercase tracking-wider text-xs border-b-2 border-slate-250">
                                            <tr>
                                                <th class="px-5 py-4">Category Name</th>
                                                <th class="px-5 py-4">Description</th>
                                                <th class="px-5 py-4 text-center">Status</th>
                                                <th class="px-5 py-4 text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 bg-white">
                                            <?php if (!empty($data['categories'])): ?>
                                                <?php foreach ($data['categories'] as $cat): ?>
                                                <tr class="hover:bg-slate-50 transition">
                                                    <td class="px-5 py-4 font-extrabold text-slate-900 text-base"><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                                    <td class="px-5 py-4 text-slate-600 font-semibold text-sm"><?php echo htmlspecialchars($cat['description'] ?? 'No description'); ?></td>
                                                    <td class="px-5 py-4 text-center">
                                                        <?php if (!empty($cat['is_active'])): ?>
                                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-950 text-xs font-extrabold border border-emerald-300">Active</span>
                                                        <?php else: ?>
                                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-extrabold">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-5 py-4 text-right">
                                                        <form method="POST" class="inline-block" onsubmit="return confirm('Delete this category?');">
                                                            <input type="hidden" name="delete_category" value="1">
                                                            <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                                                            <button type="submit" class="px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl text-xs font-extrabold border border-red-250 transition">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="px-5 py-8 text-center text-slate-500 font-semibold text-base">No waste categories found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>