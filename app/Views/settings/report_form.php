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
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
            <div class="max-w-4xl mx-auto">
                <a href="/brgy-waste-app-v3/public/settings" class="text-sm text-emerald-600 hover:underline mb-4 inline-block">← Back to Settings</a>
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Report Form Settings</h1>

                <?php if (!empty($data['error'])): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg"><?php echo htmlspecialchars($data['success']); ?></div>
                <?php endif; ?>

                <!-- General Settings -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">General Settings</h2>
                    <form method="POST">
                        <input type="hidden" name="update_settings" value="1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Photo Required</label>
                                <select name="photo_required" class="w-full px-3 py-2 border rounded-md">
                                    <option value="1" <?php echo ($data['settings']['photo_required'] ?? 1) ? 'selected' : ''; ?>>Yes</option>
                                    <option value="0" <?php echo !($data['settings']['photo_required'] ?? 1) ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Allowed File Types</label>
                                <input type="text" name="allowed_file_types" value="<?php echo htmlspecialchars($data['settings']['allowed_file_types'] ?? 'jpg,jpeg,png'); ?>" class="w-full px-3 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Max Upload Size (bytes)</label>
                                <input type="number" name="max_upload_size" value="<?php echo (int)($data['settings']['max_upload_size'] ?? 5242880); ?>" class="w-full px-3 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duplicate Distance (meters)</label>
                                <input type="number" name="duplicate_distance" value="<?php echo (int)($data['settings']['duplicate_distance'] ?? 50); ?>" class="w-full px-3 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duplicate Time Window (days)</label>
                                <input type="number" name="duplicate_time_window" value="<?php echo (int)($data['settings']['duplicate_time_window'] ?? 7); ?>" class="w-full px-3 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Max Reports per Day</label>
                                <input type="number" name="max_reports_per_day" value="<?php echo (int)($data['settings']['max_reports_per_day'] ?? 10); ?>" class="w-full px-3 py-2 border rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Enable Remarks</label>
                                <select name="enable_remarks" class="w-full px-3 py-2 border rounded-md">
                                    <option value="1" <?php echo ($data['settings']['enable_remarks'] ?? 1) ? 'selected' : ''; ?>>Yes</option>
                                    <option value="0" <?php echo !($data['settings']['enable_remarks'] ?? 1) ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Remarks Character Limit</label>
                                <input type="number" name="remarks_character_limit" value="<?php echo (int)($data['settings']['remarks_character_limit'] ?? 500); ?>" class="w-full px-3 py-2 border rounded-md">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 transition">Save Settings</button>
                        </div>
                    </form>
                </div>

                <!-- Category Management (simplified) -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Waste Categories</h2>
                    <form method="POST" class="flex gap-2 mb-4">
                        <input type="hidden" name="add_category" value="1">
                        <input type="text" name="category_name" placeholder="Category Name" class="flex-1 px-3 py-2 border rounded-md" required>
                        <input type="text" name="category_description" placeholder="Description" class="flex-1 px-3 py-2 border rounded-md">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
                    </form>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead><tr><th>Name</th><th>Description</th><th>Active</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php if (!empty($data['categories'])): ?>
                                <?php foreach ($data['categories'] as $cat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                                    <td><?php echo htmlspecialchars($cat['description']); ?></td>
                                    <td><?php echo $cat['is_active'] ? '✅' : '❌'; ?></td>
                                    <td>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="delete_category" value="1">
                                            <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this category?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-gray-500 py-4">No categories defined.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Add similar sections for Quantities and Conditions -->
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>