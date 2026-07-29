<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$schedules = $data['schedules'] ?? [];
$view = $data['view'] ?? 'cards';
$calendarDays = $data['calendar_days'] ?? [];
$month = $data['month'] ?? date('n');
$year = $data['year'] ?? date('Y');
$monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));

// Waste type mapping for colors
function getWasteTypeStyle($type) {
    $map = [
        'Biodegradable' => [
            'bg' => 'emerald-100',
            'text' => 'emerald-600',
            'badge_bg' => '#DCFCE7',
            'badge_text' => '#15803D',
            'badge_label' => 'Biodegradable'
        ],
        'Non-Biodegradable' => [
            'bg' => 'sky-100',
            'text' => 'sky-600',
            'badge_bg' => '#E0F2FE',
            'badge_text' => '#0284C7',
            'badge_label' => 'Non-Biodegradable'
        ],
        'Residual Waste' => [
            'bg' => 'amber-100',
            'text' => 'amber-600',
            'badge_bg' => '#FFEDD5',
            'badge_text' => '#C2410C',
            'badge_label' => 'Residual Waste'
        ],
        'Special / Hazardous' => [
            'bg' => 'purple-100',
            'text' => 'purple-600',
            'badge_bg' => '#F3E8FF',
            'badge_text' => '#7E22CE',
            'badge_label' => 'Special / Hazardous'
        ],
        'General' => [
            'bg' => 'gray-100',
            'text' => 'gray-600',
            'badge_bg' => '#F3F4F6',
            'badge_text' => '#4B5563',
            'badge_label' => 'General'
        ]
    ];
    return $map[$type] ?? $map['General'];
}

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
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight truncate">Collection Schedule</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">Official waste collection schedule · Barangay Dulong Bayan</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-slate-500 font-medium"><?php echo count($schedules); ?> schedules</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- View Toggle -->
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
                        <div class="text-xs text-slate-400">
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> View-only
                            </span>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- CARDS VIEW -->
                    <!-- ============================================================ -->
                    <?php if ($view === 'cards'): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php if (!empty($schedules)): ?>
                                <?php foreach ($schedules as $schedule):
                                    $style = getWasteTypeStyle($schedule['waste_type']);
                                    $purokList = $schedule['puroks'] ?? 'All Puroks';
                                    $start = date('g:i A', strtotime($schedule['start_time']));
                                    $end = date('g:i A', strtotime($schedule['end_time']));
                                ?>
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-xl bg-<?php echo $style['bg']; ?> text-<?php echo $style['text']; ?> flex items-center justify-center flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                                    <path d="M16 2v4M8 2v4M3 10h18"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-900"><?php echo htmlspecialchars($schedule['collection_day']); ?></h3>
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold" style="background: <?php echo $style['badge_bg']; ?>; color: <?php echo $style['badge_text']; ?>;">
                                                    <?php echo htmlspecialchars($style['badge_label']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 space-y-1 text-sm">
                                        <p class="text-slate-600"><?php echo $start; ?> – <?php echo $end; ?></p>
                                        <p class="text-slate-500 text-xs">📌 <?php echo htmlspecialchars($purokList); ?></p>
                                        <?php if (!empty($schedule['special_notes'])): ?>
                                            <p class="text-xs text-amber-600">📢 <?php echo htmlspecialchars($schedule['special_notes']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-span-full bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                    <p class="text-slate-500 font-medium">No collection schedules found.</p>
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
                                    <?php foreach ($calendarDays as $dayData): ?>
                                        <?php if ($dayData === null): ?>
                                            <div class="bg-white min-h-[90px] p-2 border-t border-slate-100"></div>
                                        <?php else: ?>
                                            <div class="bg-white min-h-[90px] p-2 flex flex-col justify-between border-t border-slate-100 relative">
                                                <!-- Date Number -->
                                                <div class="flex justify-between items-start">
                                                    <span class="text-sm font-semibold <?php echo $dayData['is_today'] ? 'text-white bg-[#10B981] w-7 h-7 rounded-full flex items-center justify-center' : 'text-slate-700'; ?>">
                                                        <?php echo $dayData['day']; ?>
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

                    <!-- Info Note -->
                    <div class="mt-6 text-center text-xs text-slate-400">
                        <p>Schedule last updated: <?php echo date('F j, Y'); ?> · For concerns, contact the barangay office.</p>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>