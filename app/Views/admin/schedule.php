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
            'icon' => '<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
            'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'icon_bg' => 'bg-emerald-100 text-emerald-700',
            'badge_bg' => 'bg-emerald-100',
            'badge_text' => 'text-emerald-800',
            'badge_border' => 'border-emerald-300',
            'badge_label' => 'Biodegradable'
        ],
        'Non-Biodegradable' => [
            'icon' => '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 9 6 6m0-6-6 6"/>',
            'bg' => 'bg-sky-50 text-sky-700 border-sky-200',
            'icon_bg' => 'bg-sky-100 text-sky-700',
            'badge_bg' => 'bg-sky-100',
            'badge_text' => 'text-sky-800',
            'badge_border' => 'border-sky-300',
            'badge_label' => 'Non-Biodegradable'
        ],
        'Residual Waste' => [
            'icon' => '<path d="M4 4h16v16H4z"/><path d="M8 8h8M8 12h6M8 16h4"/>',
            'bg' => 'bg-amber-50 text-amber-700 border-amber-200',
            'icon_bg' => 'bg-amber-100 text-amber-700',
            'badge_bg' => 'bg-amber-100',
            'badge_text' => 'text-amber-800',
            'badge_border' => 'border-amber-300',
            'badge_label' => 'Residual Waste'
        ],
        'Special / Hazardous' => [
            'icon' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
            'bg' => 'bg-purple-50 text-purple-700 border-purple-200',
            'icon_bg' => 'bg-purple-100 text-purple-700',
            'badge_bg' => 'bg-purple-100',
            'badge_text' => 'text-purple-800',
            'badge_border' => 'border-purple-300',
            'badge_label' => 'Special / Hazardous'
        ],
        'General' => [
            'icon' => '<path d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10"/>',
            'bg' => 'bg-slate-50 text-slate-700 border-slate-200',
            'icon_bg' => 'bg-slate-100 text-slate-700',
            'badge_bg' => 'bg-slate-100',
            'badge_text' => 'text-slate-800',
            'badge_border' => 'border-slate-300',
            'badge_label' => 'General'
        ]
    ];
    return $map[$type] ?? $map['General'];
}

// Calendar waste type colors
function getCalendarWasteColor($type) {
    $map = [
        'Biodegradable' => '#059669',
        'Non-Biodegradable' => '#0284C7',
        'Residual Waste' => '#D97706',
        'Special / Hazardous' => '#7C3AED',
        'General' => '#475569'
    ];
    return $map[$type] ?? '#475569';
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
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                    <!-- Flash Messages -->
                    <?php if (isset($_SESSION['flash_success'])): ?>
                        <div class="p-4 sm:p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-950 rounded-2xl text-base font-bold flex items-center justify-between shadow-xs animate-fadeIn">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <span><?php echo htmlspecialchars($_SESSION['flash_success']); ?></span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 p-1.5 rounded-lg hover:bg-emerald-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>

                    <!-- Page Action Header -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Waste Logistics &amp; Operations
                                </span>
                                <span class="text-sm text-slate-300 font-bold">•</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-500"><?php echo count($schedules); ?> Active Schedules</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Collection Schedule Management
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Create, postpone, edit, and organize regular barangay waste collection schedules by day and waste type.
                            </p>
                        </div>

                        <!-- Main Actions Header Bar -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- View Switcher Toggle -->
                            <div class="inline-flex rounded-xl bg-slate-100 p-1.5 border border-slate-200 shadow-xs">
                                <a href="?view=cards<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>" 
                                   class="rounded-lg px-4 py-2 text-xs sm:text-sm font-extrabold transition-all flex items-center gap-2 <?php echo $view === 'cards' ? 'bg-[#0B2E22] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                                    Cards View
                                </a>
                                <a href="?view=calendar<?php echo isset($_GET['month']) ? '&month='.$_GET['month'].'&year='.$_GET['year'] : ''; ?>" 
                                   class="rounded-lg px-4 py-2 text-xs sm:text-sm font-extrabold transition-all flex items-center gap-2 <?php echo $view === 'calendar' ? 'bg-[#0B2E22] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                    Calendar View
                                </a>
                            </div>

                            <!-- Add Schedule Primary Action Button -->
                            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold shadow-sm transition active:scale-[0.98] border border-emerald-900 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                <span>Add Schedule</span>
                            </button>
                        </div>
                    </div>

                    <!-- Quick Filter Bar (Cards View) -->
                    <?php if ($view === 'cards'): ?>
                    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 w-full sm:w-auto">
                            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mr-1">Filter Day:</span>
                            <button onclick="filterScheduleDay('all')" class="schedule-day-btn active px-3 py-1.5 rounded-lg text-xs font-extrabold bg-[#0B2E22] text-white transition cursor-pointer" data-day="all">All Days</button>
                            <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $d): ?>
                                <button onclick="filterScheduleDay('<?php echo $d; ?>')" class="schedule-day-btn px-3 py-1.5 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer" data-day="<?php echo $d; ?>"><?php echo $d; ?></button>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                            <span>Showing: <strong id="visibleScheduleCount" class="text-slate-900"><?php echo count($schedules); ?></strong> schedules</span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ============================================================ -->
                    <!-- CARDS VIEW -->
                    <!-- ============================================================ -->
                    <?php if ($view === 'cards'): ?>
                        <div class="space-y-4" id="schedulesCardContainer">
                            <?php if (!empty($schedules)): ?>
                                <?php foreach ($schedules as $schedule):
                                    $style = getWasteTypeStyle($schedule['waste_type']);
                                    $purokList = $schedule['puroks'] ?? 'All Puroks';
                                    $start = date('g:i A', strtotime($schedule['start_time']));
                                    $end = date('g:i A', strtotime($schedule['end_time']));
                                    $status = strtolower($schedule['status'] ?? 'active');
                                ?>
                                <div class="schedule-item-card bg-white rounded-2xl border-2 border-slate-200 hover:border-emerald-500/40 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col md:flex-row md:items-center justify-between gap-5 group"
                                     data-day="<?php echo htmlspecialchars($schedule['collection_day']); ?>"
                                     data-waste="<?php echo htmlspecialchars($schedule['waste_type']); ?>">
                                    
                                    <!-- Left Details -->
                                    <div class="flex items-start sm:items-center gap-4 flex-1 min-w-0">
                                        <!-- Waste Type Avatar Badge -->
                                        <div class="w-13 h-13 rounded-2xl <?php echo $style['icon_bg']; ?> flex items-center justify-center shrink-0 border border-slate-200/60 shadow-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <?php echo $style['icon']; ?>
                                            </svg>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2.5 flex-wrap mb-1.5">
                                                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                                                    <?php echo htmlspecialchars($schedule['collection_day']); ?>
                                                </h3>

                                                <!-- Waste Type Tag -->
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold <?php echo $style['badge_bg']; ?> <?php echo $style['badge_text']; ?> border <?php echo $style['badge_border']; ?>">
                                                    <?php echo htmlspecialchars($style['badge_label']); ?>
                                                </span>

                                                <!-- Status Badge -->
                                                <?php if ($status === 'active'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                        Active
                                                    </span>
                                                <?php elseif ($status === 'inactive'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                                        Inactive
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-purple-50 text-purple-800 border border-purple-200">
                                                        Special
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Metadata: Time & Puroks -->
                                            <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs sm:text-sm font-bold text-slate-600">
                                                <div class="flex items-center gap-1.5 text-slate-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                    <span><?php echo $start; ?> – <?php echo $end; ?></span>
                                                </div>
                                                <span class="text-slate-300 hidden sm:inline">•</span>
                                                <div class="flex items-center gap-1.5 text-slate-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                    <span class="truncate max-w-xs sm:max-w-md"><?php echo htmlspecialchars($purokList); ?></span>
                                                </div>
                                            </div>

                                            <?php if (!empty($schedule['special_notes'])): ?>
                                                <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                                                    <span><?php echo htmlspecialchars($schedule['special_notes']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Right Action Buttons Toolbar -->
                                    <div class="flex items-center gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 justify-end shrink-0">
                                        <!-- Postpone Action -->
                                        <button onclick="openPostponeModal(<?php echo $schedule['schedule_id']; ?>, '<?php echo htmlspecialchars($schedule['collection_day']); ?>', '<?php echo htmlspecialchars($schedule['waste_type']); ?>', '<?php echo $start; ?> - <?php echo $end; ?>')" 
                                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/80 hover:border-amber-300 text-xs font-extrabold transition active:scale-[0.97] cursor-pointer shadow-2xs" 
                                                title="Postpone or Reschedule this collection">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <span>Postpone</span>
                                        </button>

                                        <!-- Edit Action -->
                                        <a href="/brgy-waste-app-v3/public/admin/editSchedule/<?php echo $schedule['schedule_id']; ?>" 
                                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 border border-slate-200 hover:border-emerald-300 text-xs font-extrabold transition active:scale-[0.97] cursor-pointer shadow-2xs" 
                                           title="Edit Schedule Details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500 group-hover:text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Delete Action -->
                                        <button onclick="confirmDelete(<?php echo $schedule['schedule_id']; ?>, '<?php echo htmlspecialchars($schedule['collection_day']); ?> (<?php echo htmlspecialchars($schedule['waste_type']); ?>)')" 
                                                class="inline-flex items-center justify-center p-2 rounded-xl bg-slate-100 hover:bg-red-50 text-slate-500 hover:text-red-700 border border-slate-200 hover:border-red-200 transition active:scale-[0.97] cursor-pointer shadow-2xs" 
                                                title="Delete Schedule">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="bg-white rounded-2xl border-2 border-dashed border-slate-250 p-12 sm:p-16 text-center">
                                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                    </div>
                                    <h3 class="text-xl font-extrabold text-slate-900">No collection schedules found</h3>
                                    <p class="text-sm font-semibold text-slate-500 mt-1 max-w-sm mx-auto">Create weekly collection schedules to inform residents and collectors about designated waste pickups.</p>
                                    <button onclick="openAddModal()" class="mt-5 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white text-sm font-extrabold transition shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Create First Schedule
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                    <!-- ============================================================ -->
                    <!-- CALENDAR VIEW -->
                    <!-- ============================================================ -->
                    <?php else: ?>
                        <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-xs p-5 sm:p-7 space-y-6">
                            
                            <!-- Calendar Navigation Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-200 gap-4">
                                <!-- Month Switcher -->
                                <div class="flex items-center gap-2">
                                    <a href="?view=calendar&month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" 
                                       class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition border border-slate-200" title="Previous Month">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                                    </a>
                                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 px-3 tracking-tight"><?php echo $monthName; ?></h2>
                                    <a href="?view=calendar&month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" 
                                       class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition border border-slate-200" title="Next Month">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                    </a>
                                    <a href="?view=calendar&month=<?php echo date('n'); ?>&year=<?php echo date('Y'); ?>" 
                                       class="ml-2 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-extrabold transition border border-slate-200">
                                        Current Month
                                    </a>
                                </div>

                                <!-- Waste Type Legend Badges -->
                                <div class="flex flex-wrap items-center gap-2.5 text-xs font-extrabold text-slate-700">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-900 border border-emerald-300">
                                        Biodegradable
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-sky-100 text-sky-900 border border-sky-300">
                                        Non-Biodegradable
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                                        Residual
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-purple-100 text-purple-900 border border-purple-300">
                                        Hazardous
                                    </span>
                                </div>
                            </div>

                            <!-- Calendar Grid -->
                            <div>
                                <!-- Day Headers -->
                                <div class="grid grid-cols-7 text-center py-3 text-xs font-black text-slate-500 uppercase tracking-widest bg-slate-100 rounded-t-xl border border-slate-200">
                                    <div>Sun</div>
                                    <div>Mon</div>
                                    <div>Tue</div>
                                    <div>Wed</div>
                                    <div>Thu</div>
                                    <div>Fri</div>
                                    <div>Sat</div>
                                </div>

                                <!-- Days Matrix -->
                                <div class="grid grid-cols-7 gap-px bg-slate-200 border-x border-b border-slate-200 rounded-b-xl overflow-hidden shadow-xs">
                                    <?php foreach ($calendarDays as $dayData): ?>
                                        <?php if ($dayData === null): ?>
                                            <div class="bg-slate-50 min-h-[110px] p-2"></div>
                                        <?php else: ?>
                                            <div class="bg-white min-h-[110px] p-2.5 flex flex-col justify-between transition hover:bg-emerald-50/30 group relative">
                                                <!-- Date Indicator -->
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs sm:text-sm font-extrabold <?php echo $dayData['is_today'] ? 'text-white bg-[#0B2E22] w-7 h-7 rounded-full flex items-center justify-center shadow-xs' : 'text-slate-800'; ?>">
                                                        <?php echo $dayData['day']; ?>
                                                    </span>
                                                    <?php if ($dayData['is_today']): ?>
                                                        <span class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-wider">Today</span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Schedules Assigned to this Day -->
                                                <div class="flex flex-col gap-1.5 mt-1">
                                                    <?php if (!empty($dayData['schedules'])): ?>
                                                        <?php foreach ($dayData['schedules'] as $schedule): ?>
                                                            <?php 
                                                                $wType = $schedule['waste_type'];
                                                                $wStyle = getWasteTypeStyle($wType);
                                                                $timeStr = date('g:i A', strtotime($schedule['start_time']));
                                                            ?>
                                                            <div class="px-2 py-1 rounded-lg text-[10px] sm:text-xs font-extrabold <?php echo $wStyle['badge_bg']; ?> <?php echo $wStyle['badge_text']; ?> border <?php echo $wStyle['badge_border']; ?> truncate flex items-center justify-between gap-1 shadow-2xs"
                                                                 title="<?php echo htmlspecialchars($wType); ?> (<?php echo $timeStr; ?>)">
                                                                <span class="truncate"><?php echo htmlspecialchars($wType); ?></span>
                                                                <span class="text-[9px] opacity-75 font-mono shrink-0"><?php echo $timeStr; ?></span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div class="h-4"></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Special Collection Notice CTA Banner -->
                    <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent rounded-2xl border-2 border-amber-200 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 shadow-xs">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 border border-amber-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-extrabold text-amber-950">Special Collection Announcement</h3>
                                <p class="text-xs sm:text-sm text-amber-800 font-semibold mt-0.5">Need to postpone collection or announce holiday rescheduling? Broadcast a notice to all registered residents.</p>
                            </div>
                        </div>
                        <a href="/brgy-waste-app-v3/public/admin/announcements" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-extrabold text-xs sm:text-sm shadow-xs transition active:scale-[0.98] whitespace-nowrap self-stretch sm:self-auto justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                            Publish Public Notice
                        </a>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- 1. ADD SCHEDULE MODAL -->
<!-- ============================================================ -->
<div id="addModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 my-8 overflow-hidden animate-fadeIn">
        <!-- Modal Header -->
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-extrabold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Create Collection Schedule</h2>
                    <p class="text-xs font-semibold text-slate-500">Define weekly schedule timings, waste class, and coverage</p>
                </div>
            </div>
            <button onclick="closeAddModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Form -->
        <form action="/brgy-waste-app-v3/public/admin/addSchedule" method="POST" class="p-6 sm:p-7 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Collection Day <span class="text-red-600">*</span></label>
                    <select name="collection_day" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                        <option value="">-- Select Day of Week --</option>
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
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Waste Type <span class="text-red-600">*</span></label>
                    <select name="waste_type" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                        <option value="">-- Select Waste Classification --</option>
                        <option value="Biodegradable">Biodegradable (Organic / Compostable)</option>
                        <option value="Non-Biodegradable">Non-Biodegradable (Recyclables / Plastics)</option>
                        <option value="Residual Waste">Residual Waste</option>
                        <option value="Special / Hazardous">Special / Hazardous (E-Waste / Medical)</option>
                        <option value="General">General Waste</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Start Time <span class="text-red-600">*</span></label>
                    <input type="time" name="start_time" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">End Time <span class="text-red-600">*</span></label>
                    <input type="time" name="end_time" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                </div>
            </div>

            <!-- Covered Puroks Selection -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider">Covered Puroks</label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="selectAllPuroks('addPuroks')" class="text-xs font-extrabold text-emerald-700 hover:text-emerald-800">Select All</button>
                        <span class="text-slate-300">•</span>
                        <button type="button" onclick="deselectAllPuroks('addPuroks')" class="text-xs font-extrabold text-slate-500 hover:text-slate-700">Clear</button>
                    </div>
                </div>
                <div class="p-3 bg-slate-50 border-2 border-slate-200 rounded-xl max-h-40 overflow-y-auto space-y-1.5" id="addPuroks">
                    <?php foreach ($puroks as $purok): ?>
                        <label class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-white transition cursor-pointer font-bold text-xs text-slate-800 border border-transparent hover:border-slate-200">
                            <input type="checkbox" name="purok_ids[]" value="<?php echo $purok['purok_id']; ?>" class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                            <span><?php echo htmlspecialchars($purok['purok_name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Schedule Status</label>
                    <select name="status" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white">
                        <option value="active">Active (Ongoing regular schedule)</option>
                        <option value="inactive">Inactive (Paused)</option>
                        <option value="special">Special Collection</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Special Notes (Optional)</label>
                    <input type="text" name="special_notes" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition bg-slate-50 focus:bg-white" placeholder="e.g. Bring recyclables to plaza...">
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200">
                <button type="button" onclick="closeAddModal()" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-sm transition border border-slate-200 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-7 py-3 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white font-extrabold text-sm shadow-sm transition active:scale-[0.98] cursor-pointer flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Save Schedule</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- 2. POSTPONE SCHEDULE MODAL (MOVED OUTSIDE FOREACH) -->
<!-- ============================================================ -->
<div id="postponeModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-slate-200 my-8 overflow-hidden animate-fadeIn">
        <div class="px-6 py-5 bg-amber-50 border-b border-amber-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-extrabold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-amber-950 tracking-tight">Postpone Collection</h2>
                    <p class="text-xs font-bold text-amber-700">Reschedule pickup date &amp; notify barangay</p>
                </div>
            </div>
            <button onclick="closePostponeModal()" class="p-2 rounded-xl text-amber-700 hover:text-amber-950 hover:bg-amber-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form action="/brgy-waste-app-v3/public/admin/postpone_schedule" method="POST" class="p-6 sm:p-7 space-y-5">
            <input type="hidden" name="schedule_id" id="postponeScheduleId">

            <!-- Target Schedule Info Box -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 flex items-center justify-between">
                <div>
                    <span class="text-slate-400 uppercase text-[10px] font-black">Target Schedule:</span>
                    <p id="postponeScheduleSummary" class="text-sm font-extrabold text-slate-900 mt-0.5">Monday (Biodegradable)</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 font-extrabold text-[11px] border border-amber-300">
                    To Reschedule
                </span>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">New Target Collection Date <span class="text-red-600">*</span></label>
                <input type="date" name="new_date" id="postponeNewDate" min="<?php echo date('Y-m-d'); ?>" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition bg-slate-50 focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Reason for Postponement <span class="text-red-600">*</span></label>
                <textarea name="reason" id="postponeReason" rows="3" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition bg-slate-50 focus:bg-white" placeholder="e.g., Heavy monsoon rains, national holiday, garbage compactor truck maintenance..."></textarea>
                
                <!-- Quick Preset Reason Chips -->
                <div class="flex items-center gap-1.5 flex-wrap mt-2">
                    <span class="text-[11px] font-extrabold text-slate-400">Quick reasons:</span>
                    <button type="button" onclick="setPostponeReason('Due to official declared public holiday.')" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold">Official Holiday</button>
                    <button type="button" onclick="setPostponeReason('Suspended due to severe weather & heavy rains.')" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold">Severe Weather</button>
                    <button type="button" onclick="setPostponeReason('Collection vehicle maintenance and repair.')" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold">Truck Repair</button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200">
                <button type="button" onclick="closePostponeModal()" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-sm transition border border-slate-200 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-7 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm shadow-sm transition active:scale-[0.98] cursor-pointer flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Postpone &amp; Broadcast Notice</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- 3. DELETE CONFIRMATION MODAL -->
<!-- ============================================================ -->
<div id="deleteModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden animate-fadeIn">
        <div class="p-6 sm:p-7 text-center">
            <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-700 flex items-center justify-center mx-auto mb-4 border border-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900">Delete Collection Schedule?</h2>
            <p class="text-sm font-semibold text-slate-600 mt-2">
                Are you sure you want to remove the schedule for <strong id="deleteDay" class="text-slate-950 font-extrabold"></strong>?
            </p>
            <p class="text-xs text-red-600 font-bold mt-2 bg-red-50 p-2.5 rounded-xl border border-red-200 flex items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>This permanent action cannot be undone.</span>
            </p>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 border border-slate-200 text-slate-700 rounded-xl font-extrabold text-sm hover:bg-slate-100 transition cursor-pointer">
                Cancel
            </button>
            <form action="/brgy-waste-app-v3/public/admin/deleteSchedule" method="POST" class="flex-1">
                <input type="hidden" id="deleteScheduleId" name="schedule_id" value="">
                <button type="submit" class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-extrabold text-sm transition shadow-sm active:scale-[0.98] cursor-pointer">
                    Delete Schedule
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // --- 1. Add Modal Handlers ---
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

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

    // --- 2. Postpone Modal Handlers ---
    function openPostponeModal(id, day, wasteType, timeRange) {
        document.getElementById('postponeScheduleId').value = id;
        document.getElementById('postponeScheduleSummary').textContent = `${day} (${wasteType}) - ${timeRange}`;
        document.getElementById('postponeModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePostponeModal() {
        document.getElementById('postponeModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function setPostponeReason(text) {
        const textarea = document.getElementById('postponeReason');
        if (textarea) {
            textarea.value = text;
            textarea.focus();
        }
    }

    // --- 3. Delete Modal Handlers ---
    function confirmDelete(id, daySummary) {
        document.getElementById('deleteScheduleId').value = id;
        document.getElementById('deleteDay').textContent = daySummary;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // --- 4. Cards Day Filter ---
    function filterScheduleDay(day) {
        const buttons = document.querySelectorAll('.schedule-day-btn');
        buttons.forEach(btn => {
            if (btn.dataset.day === day) {
                btn.classList.add('active', 'bg-[#0B2E22]', 'text-white');
                btn.classList.remove('bg-slate-100', 'text-slate-700');
            } else {
                btn.classList.remove('active', 'bg-[#0B2E22]', 'text-white');
                btn.classList.add('bg-slate-100', 'text-slate-700');
            }
        });

        const cards = document.querySelectorAll('.schedule-item-card');
        let visible = 0;
        cards.forEach(card => {
            if (day === 'all' || card.dataset.day === day) {
                card.style.display = 'flex';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        const counter = document.getElementById('visibleScheduleCount');
        if (counter) counter.textContent = visible;
    }

    // --- Modal Overlay & Escape Key Handlers ---
    ['addModal', 'postponeModal', 'deleteModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (id === 'addModal') closeAddModal();
                    if (id === 'postponeModal') closePostponeModal();
                    if (id === 'deleteModal') closeDeleteModal();
                }
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closePostponeModal();
            closeDeleteModal();
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>