<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$schedule = $data['schedule'] ?? null;
$puroks = $data['puroks'] ?? [];
$selected_puroks = $data['selected_puroks'] ?? [];

if (!$schedule) {
    header('Location: /brgy-waste-app-v3/public/admin/schedule');
    exit;
}
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Back Link -->
                    <a href="/brgy-waste-app-v3/public/admin/schedule" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        Back to Schedule
                    </a>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <h1 class="text-2xl font-extrabold text-slate-900 mb-6">Edit Schedule</h1>

                        <form action="/brgy-waste-app-v3/public/admin/updateSchedule" method="POST" class="space-y-4">
                            <input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id']; ?>">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Collection Day</label>
                                    <select name="collection_day" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
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
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waste Type</label>
                                    <select name="waste_type" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                        <option value="Biodegradable" <?php echo $schedule['waste_type'] == 'Biodegradable' ? 'selected' : ''; ?>>Biodegradable</option>
                                        <option value="Non-Biodegradable" <?php echo $schedule['waste_type'] == 'Non-Biodegradable' ? 'selected' : ''; ?>>Non-Biodegradable</option>
                                        <option value="Residual Waste" <?php echo $schedule['waste_type'] == 'Residual Waste' ? 'selected' : ''; ?>>Residual Waste</option>
                                        <option value="Special / Hazardous" <?php echo $schedule['waste_type'] == 'Special / Hazardous' ? 'selected' : ''; ?>>Special / Hazardous</option>
                                        <option value="General" <?php echo $schedule['waste_type'] == 'General' ? 'selected' : ''; ?>>General</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Start Time</label>
                                    <input type="time" name="start_time" value="<?php echo date('H:i', strtotime($schedule['start_time'])); ?>" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">End Time</label>
                                    <input type="time" name="end_time" value="<?php echo date('H:i', strtotime($schedule['end_time'])); ?>" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Covered Puroks</label>
                                <select name="purok_ids[]" multiple class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition min-h-[100px]">
                                    <?php foreach ($puroks as $purok): ?>
                                        <option value="<?php echo $purok['purok_id']; ?>" <?php echo in_array($purok['purok_id'], $selected_puroks) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($purok['purok_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-xs text-slate-400 mt-1">Hold Ctrl/Cmd to select multiple puroks</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                                <select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                    <option value="active" <?php echo $schedule['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $schedule['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="special" <?php echo $schedule['status'] == 'special' ? 'selected' : ''; ?>>Special</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Special Notes</label>
                                <textarea name="special_notes" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition" placeholder="e.g., Holiday schedule adjustment..."><?php echo htmlspecialchars($schedule['special_notes'] ?? ''); ?></textarea>
                            </div>

                            <div class="flex gap-3 pt-4">
                                <a href="/brgy-waste-app-v3/public/admin/schedule" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-semibold text-sm text-center hover:bg-slate-50 transition">Cancel</a>
                                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#10B981] text-white rounded-xl font-semibold text-sm hover:bg-emerald-600 transition">Update Schedule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>