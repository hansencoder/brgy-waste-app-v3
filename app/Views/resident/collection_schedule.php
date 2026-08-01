<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';

// Data passed from ResidentController@schedule
$schedules      = $data['schedules'] ?? [];
$special_notice = $data['special_notice'] ?? null;
$last_updated   = $data['last_updated'] ?? date('F j, Y');

// View mode and calendar data
$view = $data['view'] ?? 'cards';
$month = $data['month'] ?? date('n');
$year = $data['year'] ?? date('Y');
$calendar_days = $data['calendar_days'] ?? [];

// Map waste types to colors and icons
$wasteTypeMap = [
    'Biodegradable'      => ['bg' => 'emerald-50', 'text' => 'emerald-700', 'icon' => 'M12 3v18M18 8c-2 0-3 2-3 4s1 4 3 4M6 8c2 0 3 2 3 4s-1 4-3 4'],
    'Non-Biodegradable'  => ['bg' => 'sky-50', 'text' => 'sky-700', 'icon' => 'M7 7h10M7 17h10M4 4h16v16H4z'],
    'Residual Waste'     => ['bg' => 'amber-50', 'text' => 'amber-700', 'icon' => 'M3 12h18M6 16c1.2-1.2 2.4-2.4 3.6-3.6M15 16c-1.2-1.2-2.4-2.4-3.6-3.6M8 8c1.5-1.5 2.5-2.5 4-4M16 8c-1.5-1.5-2.5-2.5-4-4'],
    'Special / Hazardous' => ['bg' => 'violet-50', 'text' => 'violet-700', 'icon' => 'M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z'],
];
$defaultWasteType = ['bg' => 'slate-50', 'text' => 'slate-700', 'icon' => 'M12 3v18'];
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800">
    <div class="lg:flex">
        <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8 lg:py-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-[#0D9488]">Resident Portal</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Collection Schedule</h1>
                        <p class="mt-1 text-sm text-slate-500">Official waste collection schedule for Barangay Dulong Bayan.</p>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 lg:py-8">

                <!-- View Switcher -->
            <div class="flex items-center justify-between mb-6">
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
            </div>

                <!-- Schedules Cards -->
                <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <?php if (!empty($schedules)): ?>
                        <?php foreach ($schedules as $schedule): ?>
                            <?php
                                $wasteType = $schedule['waste_type'] ?? 'General';
                                $style = $wasteTypeMap[$wasteType] ?? $defaultWasteType;
                                $purokList = $schedule['puroks'] ?? 'All Puroks';
                                $start = date('g:i A', strtotime($schedule['start_time']));
                                $end = date('g:i A', strtotime($schedule['end_time']));
                            ?>
                            <article class="rounded-[24px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)]">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-<?php echo $style['bg']; ?> text-<?php echo str_replace('text-', '', $style['text']); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="<?php echo $style['icon']; ?>"/>
                                        </svg>
                                    </div>
                                    <span class="rounded-full bg-<?php echo $style['bg']; ?> px-3 py-1 text-[11px] font-semibold <?php echo $style['text']; ?>">
                                        <?php echo htmlspecialchars($wasteType); ?>
                                    </span>
                                </div>
                                <h2 class="mt-4 text-xl font-black text-slate-900"><?php echo htmlspecialchars($schedule['collection_day']); ?></h2>
                                <p class="mt-2 text-sm font-semibold text-slate-600"><?php echo $start; ?> – <?php echo $end; ?></p>
                                <div class="mt-3 flex items-center gap-2 text-sm text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <?php echo htmlspecialchars($purokList); ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-8 text-slate-500">No collection schedules available at this time.</div>
                    <?php endif; ?>
                </section>

                <!-- Special Notice -->
                <?php if ($special_notice): ?>
                    <section class="mt-5 rounded-[24px] border border-amber-200 bg-amber-50/80 p-4 sm:p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 15H4L12 3z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-amber-800"><?php echo htmlspecialchars($special_notice['title']); ?></h3>
                                <p class="mt-1 text-sm leading-7 text-amber-700"><?php echo nl2br(htmlspecialchars($special_notice['content'])); ?></p>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- ============================================================ -->
                <!-- CALENDAR VIEW -->
                <!-- ============================================================ -->
                <?php if ($view === 'calendar'): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mt-6">
                        
                        <!-- Calendar Header -->
                        <div class="flex flex-wrap items-center justify-between pb-6 border-b border-slate-100 gap-4">
                            <div class="flex items-center gap-3">
                                <a href="?view=calendar&month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" 
                                class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                </a>
                                <h2 class="text-xl font-bold text-slate-900 min-w-[140px] text-center"><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
                                <a href="?view=calendar&month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" 
                                class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            </div>
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
                            <div class="grid grid-cols-7 text-center py-3 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                            </div>
                            <div class="grid grid-cols-7 gap-px bg-slate-100 rounded-b-xl overflow-hidden">
                                <?php foreach ($calendar_days as $dayData): ?>
                                    <?php if ($dayData === null): ?>
                                        <div class="bg-white min-h-[80px] p-2 border-t border-slate-100"></div>
                                    <?php else: ?>
                                        <div class="bg-white min-h-[80px] p-2 flex flex-col justify-between border-t border-slate-100 relative">
                                            <span class="text-sm font-semibold <?php echo $dayData['is_today'] ? 'text-white bg-[#10B981] w-7 h-7 rounded-full flex items-center justify-center' : 'text-slate-700'; ?>">
                                                <?php echo $dayData['day']; ?>
                                            </span>
                                            <div class="flex flex-col gap-1 mt-1">
                                                <?php if (!empty($dayData['schedules'])): ?>
                                                    <?php foreach ($dayData['schedules'] as $schedule): ?>
                                                        <div class="text-[9px] font-bold text-white rounded-full px-2 py-0.5 truncate" 
                                                            style="background: <?php echo $legendColors[$schedule['waste_type']] ?? '#6B7280'; ?>;"
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

                <!-- Detailed Schedule Table -->
                <section class="mt-5 rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)] sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-900">Detailed Schedule</h3>
                    </div>

                    <div class="mt-4 overflow-hidden rounded-[22px] border border-slate-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Collection Day</th>
                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Collection Time</th>
                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Covered Purok(s)</th>
                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Waste Type</th>
                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Special Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (!empty($schedules)): ?>
                                        <?php foreach ($schedules as $schedule): ?>
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-3 font-semibold text-slate-800"><?php echo htmlspecialchars($schedule['collection_day']); ?></td>
                                                <td class="px-4 py-3 text-slate-600">
                                                    <?php echo date('g:i A', strtotime($schedule['start_time'])); ?> – <?php echo date('g:i A', strtotime($schedule['end_time'])); ?>
                                                </td>
                                                <td class="px-4 py-3 text-slate-600"><?php echo htmlspecialchars($schedule['puroks'] ?? 'All Puroks'); ?></td>
                                                <td class="px-4 py-3 text-slate-600"><?php echo htmlspecialchars($schedule['waste_type'] ?? 'General'); ?></td>
                                                <td class="px-4 py-3 text-slate-500"><?php echo htmlspecialchars($schedule['special_notes'] ?? '—'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No schedule records found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <p class="mt-6 text-center text-sm text-slate-500">
                    Schedule last updated: <?php echo htmlspecialchars($last_updated); ?> · For concerns: (02) 8-123-4567
                </p>
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 px-2 py-3 backdrop-blur md:hidden">
        <div class="mx-auto flex max-w-md items-center justify-between gap-1">
            <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                Home
            </a>
            <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                Reports
            </a>
            <a href="/brgy-waste-app-v3/public/resident/submit" class="flex-1 rounded-full bg-[#10B981] px-3 py-2.5 text-center text-[10px] font-black text-white shadow-lg shadow-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Report
            </a>
            <a href="/brgy-waste-app-v3/public/resident/collection_schedule" class="flex-1 rounded-2xl bg-[#E6F4EA] px-2 py-2 text-center text-[10px] font-semibold text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                Schedule
            </a>
            <a href="/brgy-waste-app-v3/public/resident/profile" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
                Profile
            </a>
        </div>
    </nav>
</div>
