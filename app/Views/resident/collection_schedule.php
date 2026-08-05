<?php include __DIR__ . '/../layouts/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap');
  /* Apply Nunito Sans to everything EXCEPT material-icons */
  *:not(.material-icons) {
    font-family: 'Lato', sans-serif !important;
    }
  /* Ensure Material Icons render correctly */
    .material-icons {
    font-family: 'Material Icons' !important;
    font-weight: normal;
    font-style: normal;
    font-size: 24px;  /* Preferred icon size */
    display: inline-block;
    line-height: 1;
    text-transform: none;
    letter-spacing: normal;
    word-wrap: normal;
    white-space: nowrap;
    direction: ltr;
    vertical-align: middle;
    }
</style>


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

// Map waste types to colors and icons (now using Material Icons names)
$wasteTypeMap = [
    'Biodegradable'      => ['bg' => 'emerald-50', 'text' => 'emerald-700', 'border' => 'border-emerald-500', 'icon' => 'eco'],
    'Non-Biodegradable'  => ['bg' => 'sky-50', 'text' => 'sky-700', 'border' => 'border-sky-500', 'icon' => 'recycling'],
    'Residual Waste'     => ['bg' => 'amber-50', 'text' => 'amber-700', 'border' => 'border-amber-500', 'icon' => 'delete_sweep'],
    'Special / Hazardous' => ['bg' => 'violet-50', 'text' => 'violet-700', 'border' => 'border-violet-500', 'icon' => 'warning'],
];
$defaultWasteType = ['bg' => 'slate-50', 'text' => 'slate-700', 'border' => 'border-slate-300', 'icon' => 'help_outline'];
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

            <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

                <!-- View Switcher -->
                <div class="flex items-center justify-between mb-8">
                    <div class="inline-flex rounded-lg border border-slate-200 bg-white p-1 shadow-sm">
                        <a href="?view=cards<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>" 
                           class="rounded-md px-5 py-2 text-sm font-semibold transition flex items-center gap-2 <?php echo $view === 'cards' ? 'bg-[#0D9488] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'; ?>">
                            <span class="material-icons text-[1.1rem]">grid_view</span>
                            Cards
                        </a>
                        <a href="?view=calendar<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>" 
                           class="rounded-md px-5 py-2 text-sm font-semibold transition flex items-center gap-2 <?php echo $view === 'calendar' ? 'bg-[#0D9488] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'; ?>">
                            <span class="material-icons text-[1.1rem]">calendar_today</span>
                            Calendar
                        </a>
                    </div>
                </div>

                <?php if ($view !== 'calendar'): ?>
                    <!-- Schedules Cards -->
                    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                        <?php if (!empty($schedules)): ?>
                            <?php foreach ($schedules as $schedule): ?>
                                <?php
                                    $wasteType = $schedule['waste_type'] ?? 'General';
                                    $style = $wasteTypeMap[$wasteType] ?? $defaultWasteType;
                                    $purokList = $schedule['puroks'] ?? 'All Puroks';
                                    $start = date('g:i A', strtotime($schedule['start_time']));
                                    $end = date('g:i A', strtotime($schedule['end_time']));
                                ?>
                                <article class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl relative overflow-hidden">
                                    <!-- Left accent border -->
                                    <div class="absolute left-0 top-0 bottom-0 w-1 <?php echo $style['border']; ?> rounded-l-2xl"></div>
                                    
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl <?php echo 'bg-'.$style['bg']; ?> text-<?php echo str_replace('text-', '', $style['text']); ?>">
                                            <span class="material-icons text-2xl"><?php echo $style['icon']; ?></span>
                                        </div>
                                        <span class="rounded-full <?php echo 'bg-'.$style['bg']; ?> px-3 py-1 text-[11px] font-bold <?php echo $style['text']; ?>">
                                            <?php echo htmlspecialchars($wasteType); ?>
                                        </span>
                                    </div>
                                    <h2 class="mt-5 text-2xl font-black text-slate-900"><?php echo htmlspecialchars($schedule['collection_day']); ?></h2>
                                    <p class="mt-2 text-sm font-semibold text-slate-600 flex items-center gap-1.5">
                                        <span class="material-icons text-sm">schedule</span>
                                        <?php echo $start; ?> – <?php echo $end; ?>
                                    </p>
                                    <div class="mt-3 flex items-center gap-2 text-sm text-slate-500">
                                        <span class="material-icons text-sm">location_on</span>
                                        <?php echo htmlspecialchars($purokList); ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full text-center py-12 text-slate-500 bg-white rounded-2xl border border-slate-200">No collection schedules available at this time.</div>
                        <?php endif; ?>
                    </section>

                    <!-- Special Notice -->
                    <?php if ($special_notice): ?>
                        <section class="mt-8 rounded-2xl border border-amber-200 bg-amber-50/80 p-5 sm:p-6">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                    <span class="material-icons text-3xl">announcement</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-amber-800"><?php echo htmlspecialchars($special_notice['title']); ?></h3>
                                    <p class="mt-1 text-sm leading-7 text-amber-700"><?php echo nl2br(htmlspecialchars($special_notice['content'])); ?></p>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- ============================================================ -->
                <!-- CALENDAR VIEW -->
                <!-- ============================================================ -->
                <?php if ($view === 'calendar'): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mt-8">
                        
                        <!-- Calendar Header -->
                        <div class="flex flex-wrap items-center justify-between pb-5 border-b border-slate-100 gap-4">
                            <div class="flex items-center gap-2">
                                <a href="?view=calendar&month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" 
                                   class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                                    <span class="material-icons">chevron_left</span>
                                </a>
                                <h2 class="text-xl font-bold text-slate-900 min-w-[140px] text-center"><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>
                                <a href="?view=calendar&month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" 
                                   class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
                                    <span class="material-icons">chevron_right</span>
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
                                        <span class="w-3 h-3 rounded-full" style="background: <?php echo $color; ?>"></span>
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
                                        <div class="bg-white min-h-[90px] p-2 border-t border-slate-100"></div>
                                    <?php else: ?>
                                        <div class="bg-white min-h-[90px] p-2 flex flex-col justify-between border-t border-slate-100 hover:bg-slate-50 transition">
                                            <span class="text-sm font-bold <?php echo $dayData['is_today'] ? 'text-white bg-[#0D9488] w-7 h-7 rounded-full flex items-center justify-center' : 'text-slate-700'; ?>">
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

                <?php if ($view !== 'calendar'): ?>
                    <!-- Detailed Schedule Table -->
                    <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-black text-slate-900">Detailed Schedule</h3>
                        </div>

                        <div class="overflow-hidden rounded-xl border border-slate-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-800 text-white">
                                        <tr>
                                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.25em]">Collection Day</th>
                                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.25em]">Collection Time</th>
                                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.25em]">Covered Purok(s)</th>
                                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.25em]">Waste Type</th>
                                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-[0.25em]">Special Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php if (!empty($schedules)): ?>
                                            <?php foreach ($schedules as $index => $schedule): ?>
                                                <tr class="<?php echo $index % 2 === 0 ? 'bg-white' : 'bg-slate-50'; ?> hover:bg-slate-100 transition">
                                                    <td class="px-5 py-3.5 font-semibold text-slate-800"><?php echo htmlspecialchars($schedule['collection_day']); ?></td>
                                                    <td class="px-5 py-3.5 text-slate-600">
                                                        <?php echo date('g:i A', strtotime($schedule['start_time'])); ?> – <?php echo date('g:i A', strtotime($schedule['end_time'])); ?>
                                                    </td>
                                                    <td class="px-5 py-3.5 text-slate-600"><?php echo htmlspecialchars($schedule['puroks'] ?? 'All Puroks'); ?></td>
                                                    <td class="px-5 py-3.5 text-slate-600"><?php echo htmlspecialchars($schedule['waste_type'] ?? 'General'); ?></td>
                                                    <td class="px-5 py-3.5 text-slate-500"><?php echo htmlspecialchars($schedule['special_notes'] ?? '—'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="px-5 py-6 text-center text-slate-500">No schedule records found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <p class="mt-8 text-center text-sm text-slate-500">
                    Schedule last updated: <?php echo htmlspecialchars($last_updated); ?> · For concerns: (02) 8-123-4567
                </p>
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation (icons replaced) -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 px-2 py-3 backdrop-blur md:hidden">
        <div class="mx-auto flex max-w-md items-center justify-between gap-1">
            <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <span class="material-icons text-xl block mx-auto mb-1">home</span>
                Home
            </a>
            <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <span class="material-icons text-xl block mx-auto mb-1">description</span>
                Reports
            </a>
            <a href="/brgy-waste-app-v3/public/resident/submit" class="flex-1 rounded-full bg-[#10B981] px-3 py-2.5 text-center text-[10px] font-black text-white shadow-lg shadow-emerald-500/20">
                <span class="material-icons text-xl block mx-auto mb-1">add_circle</span>
                Report
            </a>
            <a href="/brgy-waste-app-v3/public/resident/collection_schedule" class="flex-1 rounded-2xl bg-[#E6F4EA] px-2 py-2 text-center text-[10px] font-semibold text-slate-900">
                <span class="material-icons text-xl block mx-auto mb-1 text-[#10B981]">calendar_month</span>
                Schedule
            </a>
            <a href="/brgy-waste-app-v3/public/resident/profile" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <span class="material-icons text-xl block mx-auto mb-1">person</span>
                Profile
            </a>
        </div>
    </nav>
</div>