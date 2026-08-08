<?php
// Safety: Ensure $data is defined
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
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-200 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Form Configuration
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Report Form Settings</h1>
                            <p class="text-sm text-slate-500 mt-1">Configure resident submission rules, file upload limits, and waste category management.</p>
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
                        $activeTab = 'report_form'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Main Content Panels -->
                        <div class="flex-1 min-w-0 space-y-6">
                            <!-- General Settings Card -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                                <div class="border-b border-slate-100 pb-5 mb-6">
                                    <h2 class="text-lg font-bold text-slate-900">Validation & Limit Rules</h2>
                                    <p class="text-xs text-slate-500 mt-1">Control photo requirements, file size caps, duplicate detection ranges, and daily limits.</p>
                                </div>

                                <form method="POST" class="space-y-6">
                                    <input type="hidden" name="update_settings" value="1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Photo Required</label>
                                            <select name="photo_required" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                                <option value="1" <?php echo ($data['settings']['photo_required'] ?? 1) ? 'selected' : ''; ?>>Yes (Required)</option>
                                                <option value="0" <?php echo !($data['settings']['photo_required'] ?? 1) ? 'selected' : ''; ?>>No (Optional)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Allowed File Types</label>
                                            <input type="text" name="allowed_file_types" value="<?php echo htmlspecialchars($data['settings']['allowed_file_types'] ?? 'jpg,jpeg,png'); ?>" 
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Max Upload Size (Bytes)</label>
                                            <input type="number" name="max_upload_size" value="<?php echo (int)($data['settings']['max_upload_size'] ?? 5242880); ?>" 
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                            <p class="text-[10px] text-slate-400 mt-1">Default 5242880 bytes (5 MB)</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Duplicate Distance (Meters)</label>
                                            <input type="number" name="duplicate_distance" value="<?php echo (int)($data['settings']['duplicate_distance'] ?? 50); ?>" 
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Duplicate Time Window (Days)</label>
                                            <input type="number" name="duplicate_time_window" value="<?php echo (int)($data['settings']['duplicate_time_window'] ?? 7); ?>" 
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Max Reports per Day (per Resident)</label>
                                            <input type="number" name="max_reports_per_day" value="<?php echo (int)($data['settings']['max_reports_per_day'] ?? 10); ?>" 
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Enable Remarks Field</label>
                                            <select name="enable_remarks" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                                <option value="1" <?php echo ($data['settings']['enable_remarks'] ?? 1) ? 'selected' : ''; ?>>Yes</option>
                                                <option value="0" <?php echo !($data['settings']['enable_remarks'] ?? 1) ? 'selected' : ''; ?>>No</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Remarks Character Limit</label>
                                            <input type="number" name="remarks_character_limit" value="<?php echo (int)($data['settings']['remarks_character_limit'] ?? 500); ?>" 
                                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-slate-100">
                                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-[#07281E] text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-emerald-800 transition shadow-lg shadow-emerald-950/10">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Save Form Rules
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Waste Category Management Card -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                                <div class="border-b border-slate-100 pb-5 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <h2 class="text-lg font-bold text-slate-900">Manage Waste Categories</h2>
                                        <p class="text-xs text-slate-500 mt-1">Add, edit, or toggle active categories available on the resident report form.</p>
                                    </div>
                                </div>

                                <!-- Add Category Form -->
                                <form method="POST" class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
                                    <input type="hidden" name="add_category" value="1">
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Add New Category</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                                        <div class="md:col-span-2">
                                            <input type="text" name="category_name" placeholder="Category Name (e.g. Yard Waste)" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 outline-none focus:border-emerald-500" required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <input type="text" name="category_description" placeholder="Description (optional)" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 outline-none focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <button type="submit" class="w-full px-4 py-2 bg-[#10B981] text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-emerald-600 transition shadow-sm">
                                                + Add
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Categories Table -->
                                <div class="overflow-x-auto rounded-xl border border-slate-200">
                                    <table class="min-w-full divide-y divide-slate-200 text-xs">
                                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                                            <tr>
                                                <th class="px-4 py-3 text-left">Category Name</th>
                                                <th class="px-4 py-3 text-left">Description</th>
                                                <th class="px-4 py-3 text-center">Status</th>
                                                <th class="px-4 py-3 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white font-medium text-slate-700">
                                            <?php if (!empty($data['categories'])): ?>
                                                <?php foreach ($data['categories'] as $cat): ?>
                                                <tr class="hover:bg-slate-50/80 transition">
                                                    <td class="px-4 py-3 font-bold text-slate-900"><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                                    <td class="px-4 py-3 text-slate-500"><?php echo htmlspecialchars($cat['description'] ?? 'No description'); ?></td>
                                                    <td class="px-4 py-3 text-center">
                                                        <?php if (!empty($cat['is_active'])): ?>
                                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">Active</span>
                                                        <?php else: ?>
                                                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        <form method="POST" class="inline-block" onsubmit="return confirm('Delete this category?');">
                                                            <input type="hidden" name="delete_category" value="1">
                                                            <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                                                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-[11px] font-bold transition">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">No waste categories found.</td>
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