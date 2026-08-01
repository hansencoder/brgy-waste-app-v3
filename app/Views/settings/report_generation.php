<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
            <div class="max-w-3xl mx-auto">
                <a href="/brgy-waste-app-v3/public/settings" class="text-sm text-emerald-600 hover:underline mb-4 inline-block">← Back to Settings</a>
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Report Generation Settings</h1>

                <?php if (!empty($data['error'])): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg"><?php echo htmlspecialchars($data['success']); ?></div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500 mb-6">These settings affect the header, footer, and signatory information on generated PDF and Excel reports.</p>
                    
                    <form action="/brgy-waste-app-v3/public/settings/report_generation" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Report Header</label>
                                <input type="text" name="report_header" value="<?php echo htmlspecialchars($data['settings']['report_header'] ?? 'Barangay Dulong Bayan Waste Management Report'); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                <p class="text-xs text-gray-400 mt-1">Appears at the top of every exported report</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Report Footer</label>
                                <input type="text" name="report_footer" value="<?php echo htmlspecialchars($data['settings']['report_footer'] ?? 'This report is for official use only.'); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                <p class="text-xs text-gray-400 mt-1">Appears at the bottom of every exported report</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Signatory Name</label>
                                    <input type="text" name="signatory_name" value="<?php echo htmlspecialchars($data['settings']['signatory_name'] ?? ''); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <p class="text-xs text-gray-400 mt-1">Name of the authorized signatory</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Signatory Position</label>
                                    <input type="text" name="signatory_position" value="<?php echo htmlspecialchars($data['settings']['signatory_position'] ?? 'Barangay Secretary'); ?>" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <p class="text-xs text-gray-400 mt-1">Official position of the signatory</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Disclaimer</label>
                                <textarea name="disclaimer" rows="3" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none"><?php echo htmlspecialchars($data['settings']['disclaimer'] ?? ''); ?></textarea>
                                <p class="text-xs text-gray-400 mt-1">Optional legal or policy disclaimer</p>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 transition">Save Settings</button>
                            <a href="/brgy-waste-app-v3/public/settings" class="px-6 py-2 bg-gray-300 text-gray-700 font-bold rounded-md hover:bg-gray-400 transition">Cancel</a>
                        </div>
                    </form>

                    <!-- Preview Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Preview</h3>
                        <div class="bg-gray-50 p-4 rounded border border-gray-200 text-sm">
                            <div class="text-center font-bold text-gray-800 border-b border-gray-300 pb-2">
                                <?php echo htmlspecialchars($data['settings']['report_header'] ?? 'Barangay Dulong Bayan Waste Management Report'); ?>
                            </div>
                            <div class="py-4 text-gray-500 text-center">
                                <p>[Report content appears here]</p>
                            </div>
                            <div class="text-center text-xs text-gray-400 border-t border-gray-300 pt-2">
                                <?php echo htmlspecialchars($data['settings']['report_footer'] ?? 'This report is for official use only.'); ?>
                            </div>
                            <?php if (!empty($data['settings']['signatory_name'])): ?>
                            <div class="text-right text-xs text-gray-500 mt-3 pt-2 border-t border-gray-200">
                                <p><strong>Signed:</strong> <?php echo htmlspecialchars($data['settings']['signatory_name']); ?></p>
                                <p><?php echo htmlspecialchars($data['settings']['signatory_position'] ?? ''); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($data['settings']['disclaimer'])): ?>
                            <div class="text-xs text-gray-400 mt-2 italic">
                                <?php echo nl2br(htmlspecialchars($data['settings']['disclaimer'])); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>