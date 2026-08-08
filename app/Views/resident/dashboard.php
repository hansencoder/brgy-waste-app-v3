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
// Retrieve user info from session if available
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';
$purok = $_SESSION['user_purok'] ?? 'Purok 1';
$unreadCount = $data['unread_count'] ?? 0;

// Pull the real resident stats payload with backward-compatible fallbacks
$stats = $data['stats'] ?? [];
$total = (int)($stats['total'] ?? 0);
$pending = (int)($stats['pending'] ?? $stats['Pending'] ?? 0);
$verified = (int)($stats['verified'] ?? $stats['Verified'] ?? 0);
$inProgress = (int)($stats['in_progress'] ?? $stats['In Progress'] ?? 0);
$resolved = (int)($stats['resolved'] ?? $stats['Resolved'] ?? 0);
$resolutionRate = $total > 0 ? round(($resolved / $total) * 100) : 0;

// Get supported reports count
$supported_count = $data['supported_count'] ?? 0;

function getStatusBadge($status) {
    $map = [
        'pending' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Pending'],
        'verified' => ['bg' => '#DCFCE7', 'text' => '#15803D', 'label' => 'Verified'],
        'in_progress' => ['bg' => '#E0F2FE', 'text' => '#0369A1', 'label' => 'In Progress'],
        'resolved' => ['bg' => '#E0F2FE', 'text' => '#0369A1', 'label' => 'Resolved'],
        'rejected' => ['bg' => '#FEE2E2', 'text' => '#B91C1C', 'label' => 'Rejected'],
    ];
    return $map[$status] ?? ['bg' => '#F3F4F6', 'text' => '#4B5563', 'label' => ucfirst($status)];
}
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800">
    <div class="lg:flex">
        <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

        <div class="flex-1 min-w-0">
            <!-- Enhanced Header -->
            

            <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-4 py-6 pb-24 sm:py-8 sm:pb-24 lg:py-4 lg:pb-4">
                <div class="space-y-6 lg:space-y-8">

                <!-- Hero Card -->
                <section class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-[#07281E] via-[#0B3024] to-[#1A4D3A] p-6 sm:p-8 lg:p-10 text-white shadow-2xl shadow-[#07281E]/30">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-400/5 rounded-full blur-2xl translate-y-1/2 -translate-x-1/4"></div>
                    
                    <div class="relative flex flex-col gap-8">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-[0.35em] text-emerald-200 border border-white/5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Resident Dashboard
                            </div>
                            <h2 class="mt-4 text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                                <?php echo htmlspecialchars($fullName); ?>
                            </h2>
                            <p class="mt-2 text-sm sm:text-base text-emerald-100/80 max-w-xl">
                                Barangay Dulong Bayan · Resident · <?php echo htmlspecialchars($purok); ?>
                            </p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <a href="/brgy-waste-app-v3/public/resident/submit" 
                                   class="inline-flex items-center gap-2 rounded-full bg-[#10B981] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 hover:shadow-emerald-500/50 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                    Report Waste Issue
                                </a>
                                <a href="/brgy-waste-app-v3/public/resident/announcements" 
                                   class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 backdrop-blur-sm px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/20 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                    Announcements
                                    <?php if ($unreadCount > 0): ?>
                                        <span class="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-bold"><?php echo $unreadCount; ?></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-[0.35em] text-emerald-200/70">Your Activity Overview</span>
                                <span class="h-px flex-1 bg-white/10"></span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 sm:gap-4">
                            <?php 
                            $statsItems = [
                                ['label' => 'Submitted', 'value' => $total, 'color' => 'emerald', 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>'],
                                ['label' => 'Pending', 'value' => $pending, 'color' => 'amber', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                                ['label' => 'Verified', 'value' => $verified, 'color' => 'sky', 'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                                ['label' => 'In Progress', 'value' => $inProgress, 'color' => 'violet', 'icon' => '<path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/>'],
                                ['label' => 'Resolved', 'value' => $resolved, 'color' => 'emerald', 'icon' => '<polyline points="20 6 9 17 4 12"/>'],
                                ['label' => 'Resolution Rate', 'value' => $resolutionRate.'%', 'color' => 'purple', 'icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>'],
                                ['label' => 'Supported Reports', 'value' => $supported_count ?? 0, 'color' => 'purple', 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>']

                            ];
                            $colorMap = [
                                'emerald' => ['bg' => 'bg-emerald-500/30', 'text' => 'text-emerald-300', 'border' => 'border-emerald-500/30'],
                                'amber' => ['bg' => 'bg-amber-500/30', 'text' => 'text-amber-300', 'border' => 'border-amber-500/30'],
                                'sky' => ['bg' => 'bg-sky-500/30', 'text' => 'text-sky-300', 'border' => 'border-sky-500/30'],
                                'violet' => ['bg' => 'bg-violet-500/30', 'text' => 'text-violet-300', 'border' => 'border-violet-500/30'],
                                'purple' => ['bg' => 'bg-purple-500/30', 'text' => 'text-purple-300', 'border' => 'border-purple-500/30']
                            ];
                            foreach ($statsItems as $item): 
                                $color = $colorMap[$item['color']];
                            ?>
                            <div class="rounded-lg bg-white/15 backdrop-blur-sm border <?php echo $color['border']; ?> p-4 sm:p-5 text-center hover:bg-white/25 transition-all duration-200 hover:scale-[1.02] shadow-lg">
                                <div class="flex h-11 w-11 sm:h-14 sm:w-14 items-center justify-center rounded-lg <?php echo $color['bg']; ?> <?php echo $color['text']; ?> mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><?php echo $item['icon']; ?></svg>
                                </div>
                                <p class="mt-2.5 text-xl sm:text-2xl lg:text-3xl font-black text-white drop-shadow-lg"><?php echo $item['value']; ?></p>
                                <p class="text-[9px] sm:text-[10px] lg:text-[11px] font-bold uppercase tracking-[0.25em] text-white/90 mt-1"><?php echo $item['label']; ?></p>
                            </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Two Column Section: Urgent Notice + Eco Tip -->
                <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-6">
                    <!-- Urgent Notice Card -->
                    <div class="relative overflow-hidden rounded-lg bg-gradient-to-br from-red-50 to-red-100/50 border border-red-200 p-6 sm:p-7 lg:p-8 shadow-sm">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-red-200/30 rounded-full blur-2xl"></div>
                        <div class="relative flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-red-500/15 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-500/15 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-[0.3em] text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                        Urgent
                                    </span>
                                    <span class="text-xs text-red-500/70 font-medium">July 22, 2026</span>
                                </div>
                                <h3 class="mt-2 text-xl font-black text-slate-900">Special collection day — July 26, 2026</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600 max-w-xl">Due to the national holiday, waste collection in Zones A, B, and C is rescheduled to Saturday, July 26. Please place bins at the curb no later than 6:00 AM.</p>
                                <a href="/brgy-waste-app-v3/public/resident/announcements" class="mt-4 inline-flex items-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                                    Read all notices <span aria-hidden="true" class="text-emerald-600">→</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Eco Tip Card -->
                    <div class="rounded-lg bg-white border border-slate-200 p-6 sm:p-7 lg:p-8 shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 3 4 7v6c0 5.5 3.8 8.7 7 10 3.2-1.3 7-4.5 7-10V7l-7-4Z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-emerald-600">Eco Tip</p>
                                <h3 class="text-lg font-black text-slate-900">Segregate before bin day</h3>
                            </div>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">Proper segregation speeds up sorting at the Materials Recovery Facility and boosts barangay compliance scores.</p>
                        <div class="mt-4 rounded-lg bg-slate-50 p-4 border border-slate-100">
                            <div class="flex items-center justify-between text-sm font-semibold">
                                <span class="text-slate-700">Zone A Compliance</span>
                                <span class="text-emerald-600">84%</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-2 w-[84%] rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500"></div>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500">Above barangay average (71%)</p>
                        </div>
                    </div>
                    
                </div>

                <!-- Reporting Tips Section -->
                <section class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 sm:p-7">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">💡 Waste Reporting Tips</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition">
                            <span class="text-emerald-600 font-bold text-lg">1</span>
                            <div>
                                <p class="font-semibold text-slate-800">Check for existing reports first</p>
                                <p class="text-slate-500 text-xs">Before submitting, check if someone already reported the same issue nearby.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition">
                            <span class="text-emerald-600 font-bold text-lg">2</span>
                            <div>
                                <p class="font-semibold text-slate-800">Upload clear photos</p>
                                <p class="text-slate-500 text-xs">Good photos help barangay officials verify and prioritize your report.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition">
                            <span class="text-emerald-600 font-bold text-lg">3</span>
                            <div>
                                <p class="font-semibold text-slate-800">Select the correct category</p>
                                <p class="text-slate-500 text-xs">Choosing the right waste category helps route your report efficiently.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition">
                            <span class="text-emerald-600 font-bold text-lg">4</span>
                            <div>
                                <p class="font-semibold text-slate-800">Enable GPS for accuracy</p>
                                <p class="text-slate-500 text-xs">Allow location access so the system can detect your exact location automatically.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Recent Reports Section -->
                <section class="rounded-lg bg-white border border-slate-200 shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-200">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                Recent Report Activity
                            </h3>
                            <p class="text-sm text-slate-500">Your latest submitted waste reports</p>
                        </div>
                        <a href="/brgy-waste-app-v3/public/resident/my_report" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                            View All Reports
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    </div>

                    <!-- Desktop Table -->
                    <div class="overflow-x-auto hidden md:block">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50/80">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Report ID</th>
                                    <th class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Waste Category</th>
                                    <th class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Date Submitted</th>
                                    <th class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Status</th>
                                    <th class="px-6 py-3.5 text-left text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <?php if (!empty($data['reports'])): ?>
                                    <?php $recent = array_slice($data['reports'], 0, 5); ?>
                                    <?php foreach ($recent as $report): ?>
                                        <?php $badge = getStatusBadge($report['status'] ?? 'pending'); ?>
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4 font-mono text-sm font-bold text-emerald-600">WR-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                            <td class="px-6 py-4 font-medium text-slate-700"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></td>
                                            <td class="px-6 py-4 text-slate-500"><?php echo date('M j, Y', strtotime($report['submission_date'])); ?></td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;">
                                                    <?php echo $badge['label']; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>" class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                                                    View Details
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                            <div class="flex flex-col items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                <p class="font-medium">You have no recent reports yet.</p>
                                                <p class="text-sm">Start by submitting a waste report.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden divide-y divide-slate-100">
                        <?php if (!empty($data['reports'])): ?>
                            <?php $recent = array_slice($data['reports'], 0, 5); ?>
                            <?php foreach ($recent as $report): ?>
                                <?php $badge = getStatusBadge($report['status'] ?? 'pending'); ?>
                                <a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>" class="block px-4 py-4 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-mono text-sm font-bold text-emerald-600">WR-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></p>
                                                <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;">
                                                    <?php echo $badge['label']; ?>
                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-700 mt-0.5"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></p>
                                            <p class="text-xs text-slate-400 mt-0.5"><?php echo date('M j, Y', strtotime($report['submission_date'])); ?></p>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="px-4 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    <p class="font-medium">No recent reports</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Map Section -->
                <section class="rounded-lg bg-white border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    Waste Reports Map
                                </h3>
                                <p class="text-sm text-slate-500">Nearby reports and barangay boundaries</p>
                            </div>
                                <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Pending</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>Verified</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Resolved</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Rejected</span>
                                </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="h-[280px] sm:h-[380px] rounded-lg overflow-hidden border border-slate-200 bg-slate-50">
                            <div id="dashboardMap" class="h-full w-full"></div>
                        </div>
                    </div>
                </section>

                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur-sm px-2 py-2.5 md:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        <div class="mx-auto flex max-w-md items-center justify-between gap-0.5">
            <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex-1 rounded-lg bg-emerald-50 px-2 py-2 text-center text-[9px] font-bold text-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                Home
            </a>
            <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex-1 rounded-lg bg-white px-2 py-2 text-center text-[9px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                Reports
            </a>
            <a href="/brgy-waste-app-v3/public/resident/submit" class="flex-1 rounded-full bg-[#10B981] px-3 py-2.5 text-center text-[9px] font-black text-white shadow-lg shadow-emerald-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Report
            </a>
            <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex-1 rounded-lg bg-white px-2 py-2 text-center text-[9px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M4 4h16v12H7l-3 3z"/></svg>
                News
            </a>
            <a href="/brgy-waste-app-v3/public/resident/profile" class="flex-1 rounded-lg bg-white px-2 py-2 text-center text-[9px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
                Profile
            </a>
        </div>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapCenter = [15.558, 120.803];

    const map = L.map('dashboardMap', {
        zoomControl: window.innerWidth >= 768,
        dragging: window.innerWidth >= 768,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: window.innerWidth >= 768
    }).setView(mapCenter, 15);

    // Clean CartoDB light basemap
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>, &copy; CartoDB',
        maxZoom: 19
    }).addTo(map);

    // Precise 66-point barangay boundary (GeoJSON)
    var barangayGeoJSON = {
        "type": "FeatureCollection",
        "features": [{
            "type": "Feature",
            "properties": {},
            "geometry": {
                "type": "Polygon",
                "coordinates": [[
                    [120.8013517, 15.5699279],[120.8008898, 15.569572],[120.8008276, 15.5686578],
                    [120.8006126, 15.5685788],[120.8005542, 15.5678398],[120.8001844, 15.5672858],
                    [120.8000725, 15.5668847],[120.8001665, 15.566531],[120.7995785, 15.5663685],
                    [120.7989717, 15.5657033],[120.7987031, 15.5658025],[120.7984537, 15.5654243],
                    [120.7980956, 15.5652],[120.7977553, 15.5652043],[120.7975135, 15.5652862],
                    [120.7971285, 15.5652259],[120.7964691, 15.5648604],[120.7961709, 15.5643821],
                    [120.795562, 15.5643993],[120.7951681, 15.5637567],[120.7953561, 15.5632478],
                    [120.7952523, 15.562581],[120.7950598, 15.5617529],[120.7950416, 15.5611835],
                    [120.7945939, 15.5608471],[120.7946431, 15.5603295],[120.7943504, 15.5596467],
                    [120.7937415, 15.5597848],[120.7930393, 15.55916],[120.7928646, 15.5570187],
                    [120.7921781, 15.555107],[120.7912123, 15.554853],[120.7913399, 15.5543176],
                    [120.7915605, 15.5533236],[120.7918092, 15.5534046],[120.8001316, 15.5478115],
                    [120.8011058, 15.5481325],[120.8021398, 15.5484701],[120.8027807, 15.5485113],
                    [120.8032508, 15.5489723],[120.8030798, 15.5500426],[120.8038043, 15.5501365],
                    [120.8044282, 15.5502517],[120.8049495, 15.550614],[120.8058211, 15.5508445],
                    [120.8062911, 15.551569],[120.8071584, 15.5520964],[120.8076635, 15.5520903],
                    [120.8081181, 15.5524005],[120.8083454, 15.5523519],[120.8085979, 15.5525708],
                    [120.8088668, 15.5528807],[120.8118007, 15.5512389],[120.8126332, 15.550257],
                    [120.8153176, 15.5523838],[120.817434, 15.549628],[120.8219183, 15.5518119],
                    [120.8232918, 15.5522367],[120.8253946, 15.5516159],[120.8260956, 15.5512188],
                    [120.8281375, 15.5526533],[120.8298546, 15.5518644],[120.8310955, 15.5519514],
                    [120.8335885, 15.5541358],[120.8325752, 15.5557229],[120.8326161, 15.5574083],
                    [120.8332704, 15.5602447],[120.8283841, 15.5650646],[120.8236492, 15.5703491],
                    [120.82189, 15.5689622],[120.8219651, 15.5676998],[120.8203353, 15.5645562],
                    [120.8205697, 15.5594636],[120.8185042, 15.5617437],[120.8149287, 15.5609879],
                    [120.8126889, 15.5623097],[120.8092582, 15.5595308],[120.8032464, 15.5673914],
                    [120.8014669, 15.5699463],[120.8013517, 15.5699279]
                ]]
            }
        }]
    };

    L.geoJSON(barangayGeoJSON, {
        style: {
            color: '#10b981',
            weight: 2.5,
            fillColor: '#d1fae5',
            fillOpacity: 0.12,
            dashArray: '6 5'
        }
    }).addTo(map);

    // Status config
    const statusColors = {
        1: { color: '#f59e0b', label: 'Pending' },
        2: { color: '#3b82f6', label: 'Verified' },
        3: { color: '#8b5cf6', label: 'In Progress' },
        4: { color: '#10b981', label: 'Resolved' },
        5: { color: '#ef4444', label: 'Rejected' }
    };

    const mapPins = <?php echo json_encode($data['map_pins'] ?? []); ?>;

    // Popup styles (injected once)
    const popupStyle = document.createElement('style');
    popupStyle.innerHTML = `
        .map-popup-wrap { font-family: 'Lato', sans-serif; width: 220px; }
        .map-popup-img  { width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 8px; background: #f1f5f9; }
        .map-popup-img-placeholder { width: 100%; height: 70px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 8px; margin-bottom: 8px; color: #94a3b8; font-size: 11px; }
        .map-popup-badge { display: inline-block; padding: 2px 9px; border-radius: 99px; font-size: 10px; font-weight: 700; letter-spacing: .04em; margin-bottom: 5px; }
        .map-popup-cat  { font-size: 13px; font-weight: 800; color: #1e293b; margin: 0 0 3px; }
        .map-popup-desc { font-size: 11px; color: #64748b; margin: 0 0 7px; line-height: 1.5; }
        .map-popup-date { font-size: 10px; color: #94a3b8; margin: 0 0 7px; }
        .map-popup-btn  { display: block; text-align: center; background: #07281E; color: #fff; border-radius: 8px; padding: 6px 0; font-size: 11px; font-weight: 700; text-decoration: none; }
        .map-popup-btn:hover { background: #0b3d2d; }
        .leaflet-popup-content-wrapper { border-radius: 14px !important; box-shadow: 0 8px 24px rgba(0,0,0,0.14) !important; padding: 0 !important; overflow: hidden; }
        .leaflet-popup-content { margin: 12px !important; }
        .leaflet-popup-tip-container { margin-top: -1px; }
    `;
    document.head.appendChild(popupStyle);

    mapPins.forEach(pin => {
        const cfg   = statusColors[pin.status_id] || { color: '#9ca3af', label: 'Unknown' };
        const color = cfg.color;

        // Dot marker
        const markerHtml = `
            <div style="
                background-color:${color};
                width:16px; height:16px;
                border-radius:50%;
                border:3px solid white;
                box-shadow:0 2px 6px rgba(0,0,0,0.3);
                transition: transform .15s;
            "></div>`;
        const icon = L.divIcon({ html: markerHtml, className: '', iconSize: [16,16], iconAnchor: [8,8] });

        // Badge colours
        const badgeBg   = { 1:'#fef3c7', 2:'#dbeafe', 3:'#ede9fe', 4:'#d1fae5', 5:'#fee2e2' }[pin.status_id] || '#f3f4f6';
        const badgeTxt  = { 1:'#92400e', 2:'#1e40af', 3:'#4c1d95', 4:'#065f46', 5:'#991b1b' }[pin.status_id] || '#374151';

        // Description (max 80 chars)
        const desc = (pin.description || '').trim();
        const shortDesc = desc.length > 80 ? desc.substring(0, 80) + '…' : desc;

        // Date
        const dateStr = pin.submission_date
            ? new Date(pin.submission_date).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' })
            : '';

        // Image
        const imgHtml = pin.photo_path
            ? `<img class="map-popup-img" src="/brgy-waste-app-v3/public/uploads/${pin.photo_path}" alt="Report photo" onerror="this.style.display='none'">`
            : `<div class="map-popup-img-placeholder">📷 No photo</div>`;

        const popupHtml = `
            <div class="map-popup-wrap">
                ${imgHtml}
                <span class="map-popup-badge" style="background:${badgeBg};color:${badgeTxt}">${cfg.label}</span>
                <p class="map-popup-cat">${pin.category_name || 'Waste Report'}</p>
                ${shortDesc ? `<p class="map-popup-desc">${shortDesc}</p>` : ''}
                ${dateStr   ? `<p class="map-popup-date">📅 ${dateStr}</p>` : ''}
                <a class="map-popup-btn" href="/brgy-waste-app-v3/public/resident/view_report/${pin.id}">View Details →</a>
            </div>`;

        L.marker([pin.latitude, pin.longitude], { icon })
            .addTo(map)
            .bindPopup(popupHtml, { maxWidth: 240, minWidth: 220 });
    });

    // Fit boundary then keep user view
    try {
        var bounds = L.geoJSON(barangayGeoJSON).getBounds();
        map.fitBounds(bounds, { padding: [12, 12] });
    } catch(e) { map.setView(mapCenter, 15); }

    setTimeout(() => map.invalidateSize(), 150);
    window.addEventListener('resize', () => map.invalidateSize());
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>