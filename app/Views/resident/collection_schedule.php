<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$schedules      = $data['schedules'] ?? [];
$special_notice = $data['special_notice'] ?? null;
$last_updated   = $data['last_updated'] ?? date('F j, Y');

$view = $data['view'] ?? 'cards';
$month = $data['month'] ?? date('n');
$year = $data['year'] ?? date('Y');
$calendar_days = $data['calendar_days'] ?? [];

$wasteTypeMap = [
    'Biodegradable'       => ['bg' => 'bg-emerald-50 text-emerald-900 border-emerald-300', 'badge' => 'bg-emerald-600 text-white'],
    'Non-Biodegradable'   => ['bg' => 'bg-blue-50 text-blue-900 border-blue-300', 'badge' => 'bg-blue-600 text-white'],
    'Residual Waste'      => ['bg' => 'bg-amber-50 text-amber-900 border-amber-300', 'badge' => 'bg-amber-600 text-white'],
    'Special / Hazardous' => ['bg' => 'bg-purple-50 text-purple-900 border-purple-300', 'badge' => 'bg-purple-600 text-white'],
];
$defaultWasteType = ['bg' => 'bg-slate-50 text-slate-700 border-slate-300', 'badge' => 'bg-slate-700 text-white'];
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <!-- Resident Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <!-- Resident Topbar -->
        <?php include __DIR__ . '/../layouts/resident_topbar.php'; ?>

        <!-- Scrollable Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                <!-- Header Banner & View Mode Switcher -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-slate-200">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">
                            <span>Resident Portal</span>
                            <span>•</span>
                            <span>Barangay Route Timetable</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Collection Schedule</h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Official waste truck collection timetable and coverage routes for Barangay Dulong Bayan.</p>
                    </div>

                    <!-- View Switcher -->
                    <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 shadow-xs self-start">
                        <a href="?view=cards<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold transition <?php echo $view === 'cards' ? 'bg-[#0B2E22] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                            <span>Card Grid</span>
                        </a>
                        <a href="?view=calendar<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold transition <?php echo $view === 'calendar' ? 'bg-[#0B2E22] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <span>Calendar</span>
                        </a>
                    </div>
                </div>

                <!-- Special Advisory Banner -->
                <?php if ($special_notice): ?>
                    <div class="rounded-2xl border border-amber-300 bg-amber-50 p-5 shadow-xs flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-200 text-amber-900 mb-1">
                                Special Collection Advisory
                            </span>
                            <h3 class="text-base font-extrabold text-amber-950"><?php echo htmlspecialchars($special_notice['title']); ?></h3>
                            <p class="text-xs text-amber-900/80 mt-1 leading-relaxed font-medium"><?php echo nl2br(htmlspecialchars($special_notice['content'])); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- CARD VIEW -->
                <?php if ($view !== 'calendar'): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                        <?php if (!empty($schedules)): ?>
                            <?php foreach ($schedules as $s):
                                $wasteType = $s['waste_type'] ?? 'General';
                                $style = $wasteTypeMap[$wasteType] ?? $defaultWasteType;
                                $purokList = $s['puroks'] ?? 'All Puroks';
                                $start = date('h:i A', strtotime($s['start_time']));
                                $end = date('h:i A', strtotime($s['end_time']));
                                $isToday = (strtolower(date('l')) === strtolower($s['collection_day']));
                            ?>
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 hover:shadow-md transition relative flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?php echo $style['bg']; ?>">
                                            <?php echo htmlspecialchars($wasteType); ?>
                                        </span>
                                        <?php if ($isToday): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white animate-pulse">
                                                Today's Route
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="text-xl font-extrabold text-slate-900"><?php echo htmlspecialchars($s['collection_day']); ?></h3>
                                    
                                    <div class="mt-2 space-y-1.5 text-xs text-slate-600">
                                        <div class="flex items-center gap-1.5 font-semibold text-emerald-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <span><?php echo $start; ?> – <?php echo $end; ?></span>
                                        </div>
                                        <div class="flex items-start gap-1.5 text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            <span class="leading-tight"><?php echo htmlspecialchars($purokList); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                                    <span>Status: Active</span>
                                    <span class="font-semibold text-slate-600">Truck Route</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-sm">
                                No active collection schedules available at this time.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Detailed Schedule Table -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <h3 class="text-base font-extrabold text-slate-900">Complete Weekly Schedule Table</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Summary of collection times, waste categories, and assigned purok zones</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        <th class="py-3 px-6">Day</th>
                                        <th class="py-3 px-6">Time Window</th>
                                        <th class="py-3 px-6">Waste Type</th>
                                        <th class="py-3 px-6">Covered Purok(s)</th>
                                        <th class="py-3 px-6">Special Instructions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php if (!empty($schedules)): ?>
                                        <?php foreach ($schedules as $s):
                                            $wasteType = $s['waste_type'] ?? 'General';
                                            $style = $wasteTypeMap[$wasteType] ?? $defaultWasteType;
                                            $start = date('h:i A', strtotime($s['start_time']));
                                            $end = date('h:i A', strtotime($s['end_time']));
                                        ?>
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="py-3.5 px-6 font-bold text-slate-900"><?php echo htmlspecialchars($s['collection_day']); ?></td>
                                            <td class="py-3.5 px-6 font-mono font-semibold text-slate-700"><?php echo $start; ?> – <?php echo $end; ?></td>
                                            <td class="py-3.5 px-6">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?php echo $style['bg']; ?>">
                                                    <?php echo htmlspecialchars($wasteType); ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-6 font-semibold text-slate-800"><?php echo htmlspecialchars($s['puroks'] ?? 'All Puroks'); ?></td>
                                            <td class="py-3.5 px-6 text-slate-500"><?php echo htmlspecialchars($s['special_notes'] ?? 'Place bins outside before 6:00 AM'); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- CALENDAR VIEW -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
                        
                        <!-- Month Navigation Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <a href="?view=calendar&month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>"
                                   class="p-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                </a>
                                <h2 class="text-xl font-extrabold text-slate-900 min-w-[160px] text-center font-mono">
                                    <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?>
                                </h2>
                                <a href="?view=calendar&month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>"
                                   class="p-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            </div>

                            <!-- Legend -->
                            <div class="flex items-center gap-3 flex-wrap text-xs font-semibold text-slate-600">
                                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>Biodegradable</span>
                                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>Non-Bio</span>
                                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>Residual</span>
                                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>Special/Hazardous</span>
                            </div>
                        </div>

                        <!-- Calendar Days Grid -->
                        <div>
                            <div class="grid grid-cols-7 text-center py-2.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 bg-slate-50 rounded-t-xl">
                                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                            </div>
                            <div class="grid grid-cols-7 gap-px bg-slate-200 rounded-b-xl overflow-hidden border border-slate-200">
                                <?php foreach ($calendar_days as $dayData): ?>
                                    <?php if ($dayData === null): ?>
                                        <div class="bg-slate-50 min-h-[90px] sm:min-h-[105px] p-2"></div>
                                    <?php else: ?>
                                        <div class="bg-white min-h-[90px] sm:min-h-[105px] p-2 flex flex-col justify-between hover:bg-slate-50/80 transition">
                                            <div class="flex justify-between items-start">
                                                <span class="text-xs font-bold <?php echo $dayData['is_today'] ? 'w-6 h-6 rounded-full bg-[#0B2E22] text-white flex items-center justify-center' : 'text-slate-800'; ?>">
                                                    <?php echo $dayData['day']; ?>
                                                </span>
                                            </div>
                                            <div class="space-y-1 mt-1">
                                                <?php if (!empty($dayData['schedules'])): ?>
                                                    <?php foreach ($dayData['schedules'] as $sch):
                                                        $w = $sch['waste_type'] ?? 'General';
                                                        $badgeBg = strpos($w, 'Bio') !== false && strpos($w, 'Non') === false ? 'bg-emerald-600' :
                                                                  (strpos($w, 'Non') !== false ? 'bg-blue-600' :
                                                                  (strpos($w, 'Residual') !== false ? 'bg-amber-600' : 'bg-purple-600'));
                                                    ?>
                                                        <div class="text-[9px] font-bold text-white <?php echo $badgeBg; ?> px-1.5 py-0.5 rounded-md truncate" title="<?php echo htmlspecialchars($w . ' - ' . ($sch['puroks'] ?? 'All')); ?>">
                                                            <?php echo htmlspecialchars($w); ?>
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

                <p class="text-center text-xs text-slate-400 pt-2 font-medium">
                    Schedule Last Updated: <?php echo htmlspecialchars($last_updated); ?> • For waste collection inquiries, contact Barangay Dulong Bayan Office.
                </p>

            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>