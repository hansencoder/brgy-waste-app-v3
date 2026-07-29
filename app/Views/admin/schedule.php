<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$schedules = $data['schedules'] ?? [];
$puroks = $data['puroks'] ?? [];
$view = $data['view'] ?? 'cards';
$calendarDays = $data['calendar_days'] ?? [];
$month = $data['month'] ?? date('n');
$year = $data['year'] ?? date('Y');
$monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));

// Waste type to icon/color mapping (for cards view)
function getWasteTypeStyle($type) {
    $map = [
        'Biodegradable' => [
            'icon' => '<path d="M12 3v18M18 8c-2 0-3 2-3 4s1 4 3 4M6 8c2 0 3 2 3 4s-1 4-3 4"/>',
            'bg' => 'emerald-100',
            'text' => 'emerald-600',
            'badge_bg' => '#DCFCE7',
            'badge_text' => '#15803D',
            'badge_label' => 'Biodegradable'
        ],
        'Non-Biodegradable' => [
            'icon' => '<path d="M7 7h10M7 17h10M4 4h16v16H4z"/>',
            'bg' => 'sky-100',
            'text' => 'sky-600',
            'badge_bg' => '#E0F2FE',
            'badge_text' => '#0284C7',
            'badge_label' => 'Non-Biodegradable'
        ],
        'Residual Waste' => [
            'icon' => '<path d="M3 12h18M6 16c1.2-1.2 2.4-2.4 3.6-3.6M15 16c-1.2-1.2-2.4-2.4-3.6-3.6M8 8c1.5-1.5 2.5-2.5 4-4M16 8c-1.5-1.5-2.5-2.5-4-4"/>',
            'bg' => 'amber-100',
            'text' => 'amber-600',
            'badge_bg' => '#FFEDD5',
            'badge_text' => '#C2410C',
            'badge_label' => 'Residual Waste'
        ],
        'Special / Hazardous' => [
            'icon' => '<path d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z"/><path d="M12 8v4"/><path d="M12 16h.01"/>',
            'bg' => 'purple-100',
            'text' => 'purple-600',
            'badge_bg' => '#F3E8FF',
            'badge_text' => '#7E22CE',
            'badge_label' => 'Special / Hazardous'
        ],
        'General' => [
            'icon' => '<path d="M4 4h16v16H4z"/><path d="M8 8h8M8 12h6M8 16h4"/>',
            'bg' => 'gray-100',
            'text' => 'gray-600',
            'badge_bg' => '#F3F4F6',
            'badge_text' => '#4B5563',
            'badge_label' => 'General'
        ]
    ];
    return $map[$type] ?? $map['General'];
}

// Calendar waste type colors
function getCalendarWasteColor($type) {
    $map = [
        'Biodegradable' => '#10B981',
        'Non-Biodegradable' => '#0284C7',
        'Residual Waste' => '#EA580C',
        'Special / Hazardous' => '#8B5CF6',
        'General' => '#6B7280'
    ];
    return $map[$type] ?? '#6B7280';
}
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Flash Messages -->
                    <?php if (isset($_SESSION['flash_success'])): ?>
                        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php echo htmlspecialchars($_SESSION['flash_success']); ?>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>

                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-extrabold text-slate-900">Collection Schedule Management</h1>
                            <p class="text-sm text-slate-500">Create, edit, and publish official collection schedules</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- View Switcher Toggle -->
                            <div class="inline-flex rounded-full bg-slate-100 p-1 shadow-sm">
                                <a href="?view=cards<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>" 
                                   class="rounded-full px-4 py-2 text-sm font-semibold transition flex items-center gap-2 <?php echo $view === 'cards' ? 'bg-[#10B981] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200'; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                                    Cards
                                </a>
                                <a href="?view=calendar<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>" 
                                   class="rounded-full px-4 py-2 text-sm font-semibold transition flex items-center gap-2 <?php echo $view === 'calendar' ? 'bg-[#10B981] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200'; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                    Calendar
                                </a>
                            </div>
                            <!-- Add Schedule Button -->
                            <button onclick="openAddModal()" class="rounded-xl bg-[#10B981] hover:bg-emerald-600 text-white font-bold px-5 py-2.5 shadow-sm transition flex items-center gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Add Schedule
                            </button>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- CARDS VIEW -->
                    <!-- ============================================================ -->
                    <?php if ($view === 'cards'): ?>
                        <div class="space-y-4">
                            <?php if (!empty($schedules)): ?>
                                <?php foreach ($schedules as $schedule):
                                    $style = getWasteTypeStyle($schedule['waste_type']);
                                    $purokList = $schedule['puroks'] ?? 'All Puroks';
                                    $start = date('g:i A', strtotime($schedule['start_time']));
                                    $end = date('g:i A', strtotime($schedule['end_time']));
                                ?>
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center justify-between hover:shadow-md transition group">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="w-12 h-12 rounded-xl bg-<?php echo $style['bg']; ?> text-<?php echo $style['text']; ?> flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <?php echo $style['icon']; ?>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <h3 class="text-lg font-bold text-slate-900"><?php echo htmlspecialchars($schedule['collection_day']); ?></h3>
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background: <?php echo $style['badge_bg']; ?>; color: <?php echo $style['badge_text']; ?>;">
                                                    <?php echo htmlspecialchars($style['badge_label']); ?>
                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-500 mt-1"><?php echo $start; ?> – <?php echo $end; ?> · <?php echo htmlspecialchars($purokList); ?></p>
                                            <?php if (!empty($schedule['special_notes'])): ?>
                                                <p class="text-xs text-amber-600 mt-1">📌 <?php echo htmlspecialchars($schedule['special_notes']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button onclick="openEditModal(<?php echo $schedule['schedule_id']; ?>)" class="w-10 h-10 rounded-xl bg-[#F0FDF4] hover:bg-emerald-100 text-slate-600 hover:text-emerald-700 transition flex items-center justify-center" title="Edit Schedule">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                        </button>
                                        <button onclick="confirmDelete(<?php echo $schedule['schedule_id']; ?>, '<?php echo htmlspecialchars($schedule['collection_day']); ?>')" class="w-10 h-10 rounded-xl bg-[#FEF2F2] hover:bg-red-100 text-slate-600 hover:text-red-600 transition flex items-center justify-center" title="Delete Schedule">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                    <p class="text-slate-500 font-medium">No collection schedules found.</p>
                                    <p class="text-sm text-slate-400 mt-1">Click "Add Schedule" to create one.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                    <!-- ============================================================ -->
                    <!-- CALENDAR VIEW -->
                    <!-- ============================================================ -->
                    <?php else: ?>
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                            
                            <!-- Calendar Header -->
                            <div class="flex flex-wrap items-center justify-between pb-6 border-b border-slate-100 gap-4">
                                <!-- Month Navigation -->
                                <div class="flex items-center gap-3">
                                    <a href="?view=calendar&month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" 
                                       class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                    </a>
                                    <h2 class="text-xl font-bold text-slate-900 min-w-[140px] text-center"><?php echo $monthName; ?></h2>
                                    <a href="?view=calendar&month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" 
                                       class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                    </a>
                                </div>

                                <!-- Legend -->
                                <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-600">
                                    <?php
                                        $legendColors = [
                                            'Biodegradable' => '#10B981',
                                            'Non-Biodegradable' => '#0284C7',
                                            'Residual Waste' => '#EA580C',
                                            'Special / Hazardous' => '#8B5CF6'
                                        ];
                                    ?>
                                    <?php foreach ($legendColors as $label => $color): ?>
                                        <span class="flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full" style="background: <?php echo $color; ?>"></span>
                                            <?php echo $label; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Calendar Grid -->
                            <div class="mt-4">
                                <!-- Days of Week Header -->
                                <div class="grid grid-cols-7 text-center py-3 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                    <div>Sun</div>
                                    <div>Mon</div>
                                    <div>Tue</div>
                                    <div>Wed</div>
                                    <div>Thu</div>
                                    <div>Fri</div>
                                    <div>Sat</div>
                                </div>

                                <!-- Date Grid -->
                                <div class="grid grid-cols-7 gap-px bg-slate-100 rounded-b-xl overflow-hidden">
                                    <?php $dayIndex = 0; ?>
                                    <?php foreach ($calendarDays as $dayData): ?>
                                        <?php if ($dayData === null): ?>
                                            <div class="bg-white min-h-[90px] p-2 border-t border-slate-100"></div>
                                        <?php else: ?>
                                            <div class="bg-white min-h-[90px] p-2 flex flex-col justify-between border-t border-slate-100 relative">
                                                <!-- Date Number -->
                                                <div class="flex justify-between items-start">
                                                    <span class="text-sm font-semibold <?php echo $dayData['is_today'] ? 'text-white bg-[#10B981] w-7 h-7 rounded-full flex items-center justify-center' : 'text-slate-700'; ?>">
                                                        <?php if ($dayData['is_today']): ?>
                                                            <?php echo $dayData['day']; ?>
                                                        <?php else: ?>
                                                            <?php echo $dayData['day']; ?>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>

                                                <!-- Schedule Pills -->
                                                <div class="flex flex-col gap-1 mt-1">
                                                    <?php if (!empty($dayData['schedules'])): ?>
                                                        <?php foreach ($dayData['schedules'] as $schedule): ?>
                                                            <div class="text-[10px] font-bold text-white rounded-full px-2 py-0.5 truncate" 
                                                                 style="background: <?php echo getCalendarWasteColor($schedule['waste_type']); ?>;"
                                                                 title="<?php echo htmlspecialchars($schedule['waste_type']); ?>">
                                                                <?php 
                                                                    $shortLabel = $schedule['waste_type'];
                                                                    if (strpos($shortLabel, 'Biodegradable') !== false) $shortLabel = 'Bio';
                                                                    elseif (strpos($shortLabel, 'Non-Biodegradable') !== false) $shortLabel = 'Non-Bio';
                                                                    elseif (strpos($shortLabel, 'Residual') !== false) $shortLabel = 'Residual';
                                                                    elseif (strpos($shortLabel, 'Special') !== false) $shortLabel = 'Special';
                                                                    echo $shortLabel;
                                                                ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Special Collection Notice Banner -->
                    <div class="mt-8 bg-amber-50/80 rounded-2xl border border-amber-200 p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-amber-100 text-amber-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-amber-800">Special Collection Notice</h3>
                                <p class="text-sm text-amber-700">Publish an update for collection postponements or reschedules.</p>
                            </div>
                        </div>
                        <a href="/brgy-waste-app-v3/public/admin/announcements" class="rounded-xl bg-[#D97706] hover:bg-amber-600 text-white font-bold px-5 py-2.5 shadow-sm transition text-sm whitespace-nowrap">
                            + Publish Notice
                        </a>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- ADD SCHEDULE MODAL -->
<!-- ============================================================ -->
<div id="addModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-slate-900">Add New Schedule</h2>
            <button onclick="closeAddModal()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="/brgy-waste-app-v3/public/admin/addSchedule" method="POST" class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Collection Day</label>
                    <select name="collection_day" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                        <option value="">Select day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waste Type</label>
                    <select name="waste_type" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                        <option value="">Select type</option>
                        <option value="Biodegradable">Biodegradable</option>
                        <option value="Non-Biodegradable">Non-Biodegradable</option>
                        <option value="Residual Waste">Residual Waste</option>
                        <option value="Special / Hazardous">Special / Hazardous</option>
                        <option value="General">General</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Start Time</label>
                    <input type="time" name="start_time" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">End Time</label>
                    <input type="time" name="end_time" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Covered Puroks</label>
                <select name="purok_ids[]" multiple class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition min-h-[100px]">
                    <?php foreach ($puroks as $purok): ?>
                        <option value="<?php echo $purok['purok_id']; ?>"><?php echo htmlspecialchars($purok['purok_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-slate-400 mt-1">Hold Ctrl/Cmd to select multiple puroks</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="special">Special</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Special Notes (Optional)</label>
                <textarea name="special_notes" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition" placeholder="e.g., Holiday schedule adjustment..."></textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeAddModal()" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-50 transition">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#10B981] text-white rounded-xl font-semibold text-sm hover:bg-emerald-600 transition">Add Schedule</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- DELETE CONFIRMATION MODAL -->
<!-- ============================================================ -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Delete Schedule</h2>
            <button onclick="closeDeleteModal()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="px-6 py-6">
            <p class="text-slate-600">Are you sure you want to delete the schedule for <strong id="deleteDay" class="text-slate-900"></strong>? This action cannot be undone.</p>
        </div>
        <div class="px-6 py-4 bg-slate-50 rounded-b-2xl flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-100 transition">Cancel</button>
            <form action="/brgy-waste-app-v3/public/admin/deleteSchedule" method="POST" class="flex-1">
                <input type="hidden" id="deleteScheduleId" name="schedule_id" value="">
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 transition">Delete Schedule</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Add Modal
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Delete Modal
    let deleteDay = '';

    function confirmDelete(id, day) {
        deleteDay = day;
        document.getElementById('deleteScheduleId').value = id;
        document.getElementById('deleteDay').textContent = day;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Edit Modal (redirects to edit page)
    function openEditModal(id) {
        window.location.href = '/brgy-waste-app-v3/public/admin/editSchedule/' + id;
    }

    // Close modals on overlay click
    document.getElementById('addModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });

    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeDeleteModal();
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>