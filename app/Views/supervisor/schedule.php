<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$schedules = $data['schedules'] ?? [];
$view = $data['view'] ?? 'cards';
$calendarDays = $data['calendar_days'] ?? [];
$month = $data['month'] ?? date('n');
$year = $data['year'] ?? date('Y');
$monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));

$wasteTypeColors = [
    'Biodegradable' => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'bar' => 'bg-emerald-500'],
    'Recyclable' => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'bar' => 'bg-blue-500'],
    'Non-Biodegradable' => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200', 'bar' => 'bg-blue-500'],
    'Residual' => ['bg' => 'bg-amber-50 text-amber-900 border-amber-200', 'bar' => 'bg-amber-500'],
    'Residual Waste' => ['bg' => 'bg-amber-50 text-amber-900 border-amber-200', 'bar' => 'bg-amber-500'],
    'Special / Hazardous' => ['bg' => 'bg-purple-50 text-purple-900 border-purple-200', 'bar' => 'bg-purple-500'],
    'General' => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-500']
];

$todayDayName = date('l');
?>

<div class="min-h-screen bg-[#F8FAFC] flex">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../layouts/supervisor_topbar.php'; ?>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto w-full">

            <!-- Header with View Toggle -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Barangay Collection Schedule</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Route timetables, waste categorization, and collection days across all puroks</p>
                </div>

                <!-- Dual View Toggle -->
                <div class="flex items-center gap-1 p-1 rounded-xl bg-slate-200/80 border border-slate-200">
                    <a href="?view=cards" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition <?php echo $view !== 'calendar' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                        <span>Card Grid</span>
                    </a>
                    <a href="?view=calendar" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition <?php echo $view === 'calendar' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <span>Monthly Calendar</span>
                    </a>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- CARD GRID VIEW                                               -->
            <!-- ============================================================ -->
            <?php if ($view !== 'calendar'): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <?php if (!empty($schedules)): ?>
                        <?php foreach ($schedules as $sched):
                            $type = $sched['waste_type'] ?? 'General';
                            $st = $wasteTypeColors[$type] ?? $wasteTypeColors['General'];
                            $isToday = (strtolower($sched['collection_day']) === strtolower($todayDayName));
                            $start = date('g:i A', strtotime($sched['start_time']));
                            $end = date('g:i A', strtotime($sched['end_time']));
                        ?>
                        <div class="bg-white rounded-2xl border <?php echo $isToday ? 'border-emerald-500 ring-2 ring-emerald-500/10' : 'border-slate-200'; ?> p-5 shadow-2xs space-y-4 flex flex-col justify-between relative overflow-hidden">
                            <?php if ($isToday): ?>
                            <span class="absolute top-3 right-3 px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-wider">
                                Today's Route
                            </span>
                            <?php endif; ?>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-2.5 h-2.5 rounded-full <?php echo $st['bar']; ?>"></span>
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500"><?php echo htmlspecialchars($sched['collection_day']); ?></span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900"><?php echo htmlspecialchars($type); ?></h3>
                                
                                <div class="mt-3 space-y-1.5 text-xs text-slate-600">
                                    <p class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span class="font-mono font-bold text-slate-800"><?php echo $start; ?> – <?php echo $end; ?></span>
                                    </p>
                                    <p class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span><?php echo htmlspecialchars($sched['puroks'] ?? 'All Puroks'); ?></span>
                                    </p>
                                </div>
                            </div>

                            <?php if (!empty($sched['special_notes'])): ?>
                            <div class="pt-3 border-t border-slate-100 text-[11px] text-amber-800 bg-amber-50/70 p-2.5 rounded-xl flex items-start gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                <span><?php echo htmlspecialchars($sched['special_notes']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200 text-xs">
                            No collection schedule registered.
                        </div>
                    <?php endif; ?>
                </div>

            <!-- ============================================================ -->
            <!-- MONTHLY CALENDAR VIEW                                        -->
            <!-- ============================================================ -->
            <?php else: ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-2xs space-y-6">
                    
                    <!-- Month Header Navigation -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <a href="?view=calendar&month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" 
                               class="p-2 rounded-xl hover:bg-slate-100 text-slate-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                            </a>
                            <h2 class="text-base font-bold text-slate-900"><?php echo $monthName; ?></h2>
                            <a href="?view=calendar&month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" 
                               class="p-2 rounded-xl hover:bg-slate-100 text-slate-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        </div>

                        <!-- Legend -->
                        <div class="flex flex-wrap items-center gap-3 text-xs">
                            <span class="flex items-center gap-1.5 text-emerald-800"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Biodegradable</span>
                            <span class="flex items-center gap-1.5 text-blue-800"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Recyclable</span>
                            <span class="flex items-center gap-1.5 text-amber-800"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Residual</span>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div>
                        <div class="grid grid-cols-7 text-center py-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                        </div>

                        <div class="grid grid-cols-7 gap-px bg-slate-200 border-b border-slate-200">
                            <?php foreach ($calendarDays as $dayData): ?>
                                <?php if ($dayData === null): ?>
                                    <div class="bg-slate-50 min-h-[90px] p-2"></div>
                                <?php else: ?>
                                    <div class="bg-white min-h-[90px] p-2 flex flex-col justify-between">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-bold <?php echo $dayData['is_today'] ? 'w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center' : 'text-slate-800'; ?>">
                                                <?php echo $dayData['day']; ?>
                                            </span>
                                        </div>

                                        <div class="space-y-1 mt-1">
                                            <?php if (!empty($dayData['schedules'])): ?>
                                                <?php foreach ($dayData['schedules'] as $sch): 
                                                    $tp = $sch['waste_type'] ?? 'General';
                                                    $badgeStyle = (strpos($tp, 'Bio') !== false && strpos($tp, 'Non') === false) ? 'bg-emerald-100 text-emerald-800' : ((strpos($tp, 'Non') !== false || strpos($tp, 'Recycle') !== false) ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800');
                                                ?>
                                                <div class="text-[10px] font-semibold px-2 py-0.5 rounded truncate <?php echo $badgeStyle; ?>" title="<?php echo htmlspecialchars($tp); ?>">
                                                    <?php echo htmlspecialchars($tp); ?>
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

            <!-- Weekly Reference Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Official Route Schedule Master Reference</h3>
                    <p class="text-xs text-slate-500">Full weekly timetable by waste category and assigned puroks</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-500 uppercase text-[10px] font-semibold tracking-wider">
                                <th class="py-3 px-4">Day</th>
                                <th class="py-3 px-4">Waste Category</th>
                                <th class="py-3 px-4">Collection Window</th>
                                <th class="py-3 px-4">Assigned Puroks</th>
                                <th class="py-3 px-4">Special Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($schedules)): ?>
                                <?php foreach ($schedules as $sched):
                                    $st = $wasteTypeColors[$sched['waste_type'] ?? 'General'] ?? $wasteTypeColors['General'];
                                ?>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3 px-4 font-bold text-slate-900"><?php echo htmlspecialchars($sched['collection_day']); ?></td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-semibold border <?php echo $st['bg']; ?>">
                                            <?php echo htmlspecialchars($sched['waste_type'] ?? 'General'); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-mono font-bold text-slate-800">
                                        <?php echo date('g:i A', strtotime($sched['start_time'])); ?> – <?php echo date('g:i A', strtotime($sched['end_time'])); ?>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700">
                                        <span class="inline-flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            <span><?php echo htmlspecialchars($sched['puroks'] ?? 'All Puroks'); ?></span>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-500"><?php echo htmlspecialchars($sched['special_notes'] ?? '—'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs">No active routes registered.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>