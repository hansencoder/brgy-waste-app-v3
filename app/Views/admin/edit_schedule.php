<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$schedule = $data['schedule'] ?? null;
$puroks = $data['puroks'] ?? [];
$selected_puroks = $data['selected_puroks'] ?? [];

if (!$schedule) {
    header('Location: ' . app_url('admin/schedule'));
    exit;
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
</style>

<div class="min-h-screen bg-slate-50 text-slate-900 w-full flex font-sans antialiased">
    <div class="lg:flex lg:min-h-screen w-full">
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                    <!-- Header with Breadcrumb & Back -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="<?php echo app_url('admin/schedule'); ?>" class="text-xs sm:text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Schedule Management</a>
                                <span class="text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Edit Schedule #<?php echo $schedule['schedule_id']; ?>
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Edit Collection Schedule
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Modify day of collection, waste class, operational timeframes, and designated purok coverage.
                            </p>
                        </div>

                        <a href="<?php echo app_url('admin/schedule'); ?>" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-extrabold transition self-start sm:self-auto border border-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Back to Schedules
                        </a>
                    </div>

                    <!-- Main Edit Card -->
                    <div class="bg-white rounded-2xl border-2 border-slate-200 p-6 sm:p-8 shadow-xs">
                        <form action="<?php echo app_url('admin/updateSchedule'); ?>" method="POST" class="space-y-6">
                            <input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id']; ?>">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Collection Day <span class="text-red-600">*</span></label>
                                    <select name="collection_day" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3.5 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                                        <option value="Monday" <?php echo $schedule['collection_day'] == 'Monday' ? 'selected' : ''; ?>>Monday</option>
                                        <option value="Tuesday" <?php echo $schedule['collection_day'] == 'Tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                                        <option value="Wednesday" <?php echo $schedule['collection_day'] == 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                                        <option value="Thursday" <?php echo $schedule['collection_day'] == 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                                        <option value="Friday" <?php echo $schedule['collection_day'] == 'Friday' ? 'selected' : ''; ?>>Friday</option>
                                        <option value="Saturday" <?php echo $schedule['collection_day'] == 'Saturday' ? 'selected' : ''; ?>>Saturday</option>
                                        <option value="Sunday" <?php echo $schedule['collection_day'] == 'Sunday' ? 'selected' : ''; ?>>Sunday</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Waste Classification <span class="text-red-600">*</span></label>
                                    <select name="waste_type" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3.5 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                                        <option value="Biodegradable" <?php echo $schedule['waste_type'] == 'Biodegradable' ? 'selected' : ''; ?>>Biodegradable (Organic / Compostable)</option>
                                        <option value="Non-Biodegradable" <?php echo $schedule['waste_type'] == 'Non-Biodegradable' ? 'selected' : ''; ?>>Non-Biodegradable (Recyclables / Plastics)</option>
                                        <option value="Residual Waste" <?php echo $schedule['waste_type'] == 'Residual Waste' ? 'selected' : ''; ?>>Residual Waste</option>
                                        <option value="Special / Hazardous" <?php echo $schedule['waste_type'] == 'Special / Hazardous' ? 'selected' : ''; ?>>Special / Hazardous (E-Waste / Medical)</option>
                                        <option value="General" <?php echo $schedule['waste_type'] == 'General' ? 'selected' : ''; ?>>General Waste</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Start Time <span class="text-red-600">*</span></label>
                                    <input type="time" name="start_time" value="<?php echo date('H:i', strtotime($schedule['start_time'])); ?>" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3.5 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">End Time <span class="text-red-600">*</span></label>
                                    <input type="time" name="end_time" value="<?php echo date('H:i', strtotime($schedule['end_time'])); ?>" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3.5 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                                </div>
                            </div>

                            <!-- Covered Puroks Selection -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider">Covered Puroks</label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="selectAllPuroks('editPuroks')" class="text-xs font-extrabold text-emerald-700 hover:text-emerald-800">Select All</button>
                                        <span class="text-slate-300">•</span>
                                        <button type="button" onclick="deselectAllPuroks('editPuroks')" class="text-xs font-extrabold text-slate-500 hover:text-slate-700">Clear</button>
                                    </div>
                                </div>
                                <div class="p-4 bg-slate-50 border-2 border-slate-200 rounded-xl max-h-48 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-2" id="editPuroks">
                                    <?php foreach ($puroks as $purok): ?>
                                        <?php $isChecked = in_array($purok['purok_id'], $selected_puroks); ?>
                                        <label class="flex items-center gap-2.5 p-2.5 rounded-lg hover:bg-white transition cursor-pointer font-bold text-xs text-slate-800 border <?php echo $isChecked ? 'border-emerald-300 bg-emerald-50/50' : 'border-transparent hover:border-slate-200'; ?>">
                                            <input type="checkbox" name="purok_ids[]" value="<?php echo $purok['purok_id']; ?>" <?php echo $isChecked ? 'checked' : ''; ?> class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                                            <span><?php echo htmlspecialchars($purok['purok_name']); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Schedule Status</label>
                                    <select name="status" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3.5 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                                        <option value="active" <?php echo $schedule['status'] == 'active' ? 'selected' : ''; ?>>Active (Ongoing regular schedule)</option>
                                        <option value="inactive" <?php echo $schedule['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive (Paused)</option>
                                        <option value="special" <?php echo $schedule['status'] == 'special' ? 'selected' : ''; ?>>Special Collection</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Special Notes (Optional)</label>
                                    <input type="text" name="special_notes" value="<?php echo htmlspecialchars($schedule['special_notes'] ?? ''); ?>" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3.5 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white" placeholder="e.g. Holiday schedule adjustment...">
                                </div>
                            </div>

                            <!-- Footer Action Buttons -->
                            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                                <a href="<?php echo app_url('admin/schedule'); ?>" class="px-6 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-sm transition border border-slate-200 cursor-pointer">
                                    Cancel
                                </a>
                                <button type="submit" class="px-8 py-3.5 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white font-extrabold text-sm shadow-sm transition active:scale-[0.98] cursor-pointer flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>Update Schedule</span>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<script>
    function selectAllPuroks(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
    }

    function deselectAllPuroks(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>