<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
            <div class="max-w-3xl mx-auto">
                <a href="/brgy-waste-app-v3/public/settings" class="text-sm text-emerald-600 hover:underline mb-4 inline-block">← Back to Settings</a>
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Barangay Information</h1>

                <?php if (!empty($data['error'])): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo $data['error']; ?></div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg"><?php echo $data['success']; ?></div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow p-6">
                    <form action="/brgy-waste-app-v3/public/settings/barangay" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Barangay Name</label>
                                <input type="text" name="barangay_name" value="<?php echo htmlspecialchars($data['barangay']['barangay_name'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Municipality</label>
                                <input type="text" name="municipality" value="<?php echo htmlspecialchars($data['barangay']['municipality'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Province</label>
                                <input type="text" name="province" value="<?php echo htmlspecialchars($data['barangay']['province'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Region</label>
                                <input type="text" name="region" value="<?php echo htmlspecialchars($data['barangay']['region'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Official Address</label>
                                <input type="text" name="official_address" value="<?php echo htmlspecialchars($data['barangay']['official_address'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                <input type="text" name="contact_number" value="<?php echo htmlspecialchars($data['barangay']['contact_number'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Official Email</label>
                                <input type="email" name="official_email" value="<?php echo htmlspecialchars($data['barangay']['official_email'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 transition">Save Changes</button>
                            <a href="/brgy-waste-app-v3/public/settings" class="ml-3 px-6 py-2 bg-gray-300 text-gray-700 font-bold rounded-md hover:bg-gray-400 transition">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>