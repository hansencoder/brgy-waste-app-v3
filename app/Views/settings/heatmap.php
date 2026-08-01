<?php include __DIR__ . '/../layouts/header.php'; ?>
<!-- similar layout -->
<div class="max-w-3xl mx-auto">
    <a href="/brgy-waste-app-v3/public/settings" class="text-sm text-emerald-600 hover:underline mb-4 inline-block">← Back to Settings</a>
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Heatmap Configuration</h1>
    <?php if (!empty($data['success'])): ?><div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg"><?php echo $data['success']; ?></div><?php endif; ?>
    <?php if (!empty($data['error'])): ?><div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo $data['error']; ?></div><?php endif; ?>
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Radius (meters)</label>
                    <input type="number" name="radius_meters" value="<?php echo $data['settings']['radius_meters'] ?? 50; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Minimum Reports</label>
                    <input type="number" name="minimum_reports" value="<?php echo $data['settings']['minimum_reports'] ?? 3; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Low Density Color</label>
                    <input type="color" name="low_density_color" value="<?php echo $data['settings']['low_density_color'] ?? '#FDE68A'; ?>" class="w-full h-10 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Medium Density Color</label>
                    <input type="color" name="medium_density_color" value="<?php echo $data['settings']['medium_density_color'] ?? '#F97316'; ?>" class="w-full h-10 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">High Density Color</label>
                    <input type="color" name="high_density_color" value="<?php echo $data['settings']['high_density_color'] ?? '#EF4444'; ?>" class="w-full h-10 border rounded-md">
                </div>
            </div>
            <button type="submit" class="mt-4 px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700">Save</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>