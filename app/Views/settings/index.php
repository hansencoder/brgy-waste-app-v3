<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">System Settings</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="/brgy-waste-app-v3/public/settings/barangay" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-emerald-500">
                        <h2 class="text-xl font-semibold text-gray-800">🏛️ Barangay Info</h2>
                        <p class="text-sm text-gray-500 mt-2">Update barangay details, address, contact.</p>
                    </a>
                    <a href="/brgy-waste-app-v3/public/settings/report_form" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-blue-500">
                        <h2 class="text-xl font-semibold text-gray-800">📋 Report Form Settings</h2>
                        <p class="text-sm text-gray-500 mt-2">Manage categories, quantities, conditions, validation rules.</p>
                    </a>
                    <a href="/brgy-waste-app-v3/public/settings/heatmap" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-orange-500">
                        <h2 class="text-xl font-semibold text-gray-800">🔥 Heatmap Configuration</h2>
                        <p class="text-sm text-gray-500 mt-2">Adjust radius, thresholds, colors.</p>
                    </a>
                    <a href="/brgy-waste-app-v3/public/settings/report_generation" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-purple-500">
                        <h2 class="text-xl font-semibold text-gray-800">📄 Report Generation</h2>
                        <p class="text-sm text-gray-500 mt-2">Header, footer, signatory settings.</p>
                    </a>
                    <a href="/brgy-waste-app-v3/public/settings/landmarks" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-rose-500">
                        <h2 class="text-xl font-semibold text-gray-800">🗺️ Map Landmarks</h2>
                        <p class="text-sm text-gray-500 mt-2">Add/edit barangay landmarks (hall, MRF, etc.).</p>
                    </a>
                    <a href="/brgy-waste-app-v3/public/settings/purok_boundaries" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-l-4 border-teal-500">
                        <h2 class="text-xl font-semibold text-gray-800">📐 Purok Boundaries</h2>
                        <p class="text-sm text-gray-500 mt-2">Draw/edit purok polygons for accurate detection.</p>
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>