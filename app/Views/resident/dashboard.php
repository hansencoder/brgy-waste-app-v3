<?php include __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    body, * { font-family: 'Inter', sans-serif !important; }
    .dashboard-map { height: 340px; border-radius: 12px; overflow: hidden; }
</style>
<?php
$fullName      = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName     = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';
$purok         = $_SESSION['user_purok'] ?? 'Purok 1';
$unreadCount   = $data['unread_count'] ?? 0;

$stats         = $data['stats'] ?? [];
$total         = (int)($stats['total']       ?? 0);
$pending       = (int)($stats['pending']     ?? $stats['Pending']     ?? 0);
$verified      = (int)($stats['verified']    ?? $stats['Verified']    ?? 0);
$inProgress    = (int)($stats['in_progress'] ?? $stats['In Progress'] ?? 0);
$resolved      = (int)($stats['resolved']    ?? $stats['Resolved']    ?? 0);
$resolutionRate = $total > 0 ? round(($resolved / $total) * 100) : 0;
$supported_count = $data['supported_count'] ?? 0;

function getStatusBadge($status) {
    $map = [
        'pending'     => ['bg' => '#FEF3C7', 'text' => '#92400E', 'border' => '#FDE68A', 'label' => 'Pending'],
        'verified'    => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'border' => '#BFDBFE', 'label' => 'Verified'],
        'in_progress' => ['bg' => '#EDE9FE', 'text' => '#4C1D95', 'border' => '#DDD6FE', 'label' => 'In Progress'],
        'resolved'    => ['bg' => '#D1FAE5', 'text' => '#065F46', 'border' => '#A7F3D0', 'label' => 'Resolved'],
        'rejected'    => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#FECACA', 'label' => 'Rejected'],
    ];
    return $map[strtolower($status)] ?? ['bg' => '#F3F4F6', 'text' => '#4B5563', 'border' => '#E5E7EB', 'label' => ucfirst($status)];
}
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

        <div class="flex-1 min-w-0 overflow-x-hidden">
            <main class="  pb-24 lg:pb-8 max-w-10x1 mx-auto space-y-6">

                <!-- ===== HERO HEADER ===== -->
                <section class="relative  bg-[#0B2E22] text-white p-6 sm:p-8">
                    <!-- Top row: greeting + action buttons -->
                    <div class="relative flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <!-- Greeting -->
                        <div>
                            <h1 class="text-2xl sm:text-[28px] font-bold tracking-tight">
                                Welcome back, <?php echo htmlspecialchars($fullName); ?>
                            </h1>
                            <p class="mt-1 text-sm font-medium text-emerald-300">
                                Barangay Dulong Bayan &bull; Resident &bull; <?php echo htmlspecialchars($purok); ?>
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center gap-3 flex-shrink-0 mt-1 sm:mt-0">
                            <a href="/brgy-waste-app-v3/public/resident/submit"
                               class="inline-flex items-center gap-1.5 rounded-full bg-[#10B981] hover:bg-emerald-500 px-5 py-2 text-sm font-bold text-white shadow-lg shadow-emerald-900/40 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                                Report Waste Issue
                            </a>
                            <a href="/brgy-waste-app-v3/public/resident/announcements"
                               class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 hover:bg-white/20 px-5 py-2 text-sm font-semibold text-white transition-all backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                Announcements
                                <?php if ($unreadCount > 0): ?>
                                    <span class="rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold leading-none"><?php echo $unreadCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="relative mt-7 border-t border-white/10 pt-6">
                        <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/50 mb-4">Your Activity Overview</p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2 md:gap-3">
                            <?php
                            $statsItems = [
                                ['label' => 'Submitted',       'value' => $total,              'color' => '#10B981', 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>'],
                                ['label' => 'Pending',         'value' => $pending,            'color' => '#F59E0B', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                                ['label' => 'Verified',        'value' => $verified,           'color' => '#3B82F6', 'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                                ['label' => 'In Progress',     'value' => $inProgress,         'color' => '#8B5CF6', 'icon' => '<path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/>'],
                                ['label' => 'Resolved',        'value' => $resolved,           'color' => '#10B981', 'icon' => '<polyline points="20 6 9 17 4 12"/>'],
                                ['label' => 'Resolution Rate', 'value' => $resolutionRate.'%', 'color' => '#8B5CF6', 'icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>'],
                                ['label' => 'Supported Reports','value' => $supported_count,   'color' => '#EC4899', 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'],
                            ];
                            foreach ($statsItems as $s): ?>
                            <div class="flex flex-col items-center gap-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition py-3 md:py-4">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full" style="background: <?php echo $s['color']; ?>20;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="<?php echo $s['color']; ?>" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <?php echo $s['icon']; ?>
                                    </svg>
                                </div>
                                <p class="text-xl sm:text-2xl font-black text-white leading-none mt-1"><?php echo $s['value']; ?></p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-white/50 text-center leading-tight"><?php echo $s['label']; ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <!-- ===== URGENT NOTICE + ECO TIP ===== -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 px-6">

                    <!-- Urgent Notice -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap mb-1">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-red-600 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    Urgent
                                </span>
                                <span class="text-xs font-medium text-slate-400">July 22, 2026</span>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 leading-snug">Special collection day — July 26, 2026</h2>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">Due to the national holiday, waste collection in Zones A, B, and C is rescheduled to Saturday, July 26. Please place bins at the curb no later than 6:00 AM.</p>
                            <a href="/brgy-waste-app-v3/public/resident/announcements"
                               class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">
                                Read all notices
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Eco Tip -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-emerald-600">Eco Tip</span>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 mt-1">Segregate before bin day</h2>
                            <p class="mt-2 text-sm text-slate-500 leading-relaxed">Proper segregation speeds up sorting at the Materials Recovery Facility and boosts barangay compliance scores.</p>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-sm font-semibold mb-2">
                                <span class="text-slate-700">Zone A Compliance</span>
                                <span class="text-emerald-600">84%</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-2.5 w-[84%] rounded-full bg-emerald-500 transition-all"></div>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">Above barangay average (71%)</p>
                        </div>
                    </div>
                </div>

                <!-- ===== WASTE REPORTING TIPS ===== -->
                <section class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mx-6">
                    <h2 class="flex items-center gap-2 text-sm font-bold text-slate-800 mb-4">
                        <span>💡 Waste Reporting Tips</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                        <?php
                        $tips = [
                            ['n' => 1, 'title' => 'Check for existing reports first',  'desc' => 'Before submitting, check if someone already reported the same issue nearby.'],
                            ['n' => 2, 'title' => 'Upload clear photos',                'desc' => 'Good photos help barangay officials verify and prioritize your report.'],
                            ['n' => 3, 'title' => 'Select the correct category',        'desc' => 'Choosing the right waste category helps route your report efficiently.'],
                            ['n' => 4, 'title' => 'Enable GPS for accuracy',            'desc' => 'Allow location access so the system can detect your exact location automatically.'],
                        ];
                        foreach ($tips as $tip): ?>
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 text-lg font-black text-emerald-500 leading-none"><?php echo $tip['n']; ?></span>
                            <div>
                                <p class="text-[15px] font-bold text-slate-800 leading-snug"><?php echo $tip['title']; ?></p>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed"><?php echo $tip['desc']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- ===== RECENT REPORT ACTIVITY ===== -->
                <section class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mx-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
                        <div>
                            <h2 class="flex items-center gap-2 text-base font-bold text-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                Recent Report Activity
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Your latest submitted waste reports</p>
                        </div>
                        <a href="/brgy-waste-app-v3/public/resident/my_report"
                           class="flex-shrink-0 inline-flex items-center gap-1 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">
                            View All Reports
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    </div>

                    <!-- Table (desktop) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-400">Report ID</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-400">Waste Category</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-400">Date Submitted</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-400">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($data['reports'])): ?>
                                    <?php foreach (array_slice($data['reports'], 0, 5) as $report):
                                        $badge = getStatusBadge($report['status'] ?? 'pending'); ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 font-mono text-sm font-bold text-emerald-600">WR-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></td>
                                        <td class="px-6 py-4 text-slate-500"><?php echo date('M j, Y', strtotime($report['submission_date'])); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold border"
                                                  style="background:<?php echo $badge['bg']; ?>;color:<?php echo $badge['text']; ?>;border-color:<?php echo $badge['border']; ?>">
                                                <?php echo $badge['label']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>"
                                               class="inline-flex items-center gap-1 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">
                                                View Details
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                <p class="text-sm font-medium text-slate-500">You have no recent reports yet.</p>
                                                <p class="text-xs text-slate-400">Start by submitting a waste report.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile cards -->
                    <div class="md:hidden divide-y divide-slate-100">
                        <?php if (!empty($data['reports'])): ?>
                            <?php foreach (array_slice($data['reports'], 0, 5) as $report):
                                $badge = getStatusBadge($report['status'] ?? 'pending'); ?>
                            <a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>"
                               class="flex items-center justify-between px-4 py-4 hover:bg-slate-50 transition-colors">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-mono text-sm font-bold text-emerald-600">WR-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></p>
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold border"
                                              style="background:<?php echo $badge['bg']; ?>;color:<?php echo $badge['text']; ?>;border-color:<?php echo $badge['border']; ?>">
                                            <?php echo $badge['label']; ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-700 mt-0.5 truncate"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></p>
                                    <p class="text-xs text-slate-400 mt-0.5"><?php echo date('M j, Y', strtotime($report['submission_date'])); ?></p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0 ml-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="px-4 py-10 text-center text-slate-400 text-sm">No recent reports.</div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- ===== WASTE REPORTS MAP ===== -->
                <section class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mx-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
                        <div>
                            <h2 class="flex items-center gap-2 text-base font-bold text-slate-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                Waste Reports Map
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Nearby reports and barangay boundaries</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>Pending</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>Verified</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>Resolved</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>Rejected</span>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="h-[340px] sm:h-[380px] rounded-xl overflow-hidden border border-slate-100">
                            <div id="dashboardMap" class="h-full w-full"></div>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- Mobile bottom nav -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-white/90 backdrop-blur-sm border-t border-slate-200 px-2 py-2 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        <div class="mx-auto flex max-w-md items-center justify-between gap-1">
            <?php
            $navItems = [
                ['href' => '/brgy-waste-app-v3/public/resident',                  'label' => 'Home',    'active' => true,  'icon' => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>'],
                ['href' => '/brgy-waste-app-v3/public/resident/my_report',        'label' => 'Reports', 'active' => false, 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>'],
                ['href' => '/brgy-waste-app-v3/public/resident/submit',           'label' => 'Report',  'active' => false, 'icon' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>', 'primary' => true],
                ['href' => '/brgy-waste-app-v3/public/resident/announcements',    'label' => 'News',    'active' => false, 'icon' => '<path d="M4 4h16v12H7l-3 3z"/>'],
                ['href' => '/brgy-waste-app-v3/public/resident/profile',          'label' => 'Profile', 'active' => false, 'icon' => '<path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/>'],
            ];
            foreach ($navItems as $nav):
                if (!empty($nav['primary'])): ?>
                    <a href="<?php echo $nav['href']; ?>" class="flex-1 flex flex-col items-center justify-center rounded-full bg-[#10B981] py-2 text-[9px] font-black text-white shadow-lg shadow-emerald-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><?php echo $nav['icon']; ?></svg>
                        <?php echo $nav['label']; ?>
                    </a>
                <?php else:
                    $activeClass = $nav['active'] ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500 bg-transparent'; ?>
                    <a href="<?php echo $nav['href']; ?>" class="flex-1 flex flex-col items-center justify-center rounded-xl py-2 text-[9px] font-semibold <?php echo $activeClass; ?> transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><?php echo $nav['icon']; ?></svg>
                        <?php echo $nav['label']; ?>
                    </a>
                <?php endif; endforeach; ?>
        </div>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapCenter = [15.558, 120.803];
    const map = L.map('dashboardMap', {
        zoomControl: true,
        dragging: window.innerWidth >= 768,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: window.innerWidth >= 768
    }).setView(mapCenter, 15);

    const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics',
        maxZoom: 19
    });
    const labelsMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: '',
        maxZoom: 19
    });
    const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
    });

    satelliteMap.addTo(map);
    labelsMap.addTo(map);

    L.control.layers({
        "Satellite (Homes & Buildings)": L.layerGroup([satelliteMap, labelsMap]),
        "Street Map": streetMap
    }, null, { position: 'topright' }).addTo(map);

    // Barangay boundary
    const barangayGeoJSON = {
        "type": "FeatureCollection",
        "features": [{ "type": "Feature", "properties": {}, "geometry": { "type": "Polygon", "coordinates": [[
            [120.8013517,15.5699279],[120.8008898,15.569572],[120.8008276,15.5686578],
            [120.8006126,15.5685788],[120.8005542,15.5678398],[120.8001844,15.5672858],
            [120.8000725,15.5668847],[120.8001665,15.566531],[120.7995785,15.5663685],
            [120.7989717,15.5657033],[120.7987031,15.5658025],[120.7984537,15.5654243],
            [120.7980956,15.5652],[120.7977553,15.5652043],[120.7975135,15.5652862],
            [120.7971285,15.5652259],[120.7964691,15.5648604],[120.7961709,15.5643821],
            [120.795562,15.5643993],[120.7951681,15.5637567],[120.7953561,15.5632478],
            [120.7952523,15.562581],[120.7950598,15.5617529],[120.7950416,15.5611835],
            [120.7945939,15.5608471],[120.7946431,15.5603295],[120.7943504,15.5596467],
            [120.7937415,15.5597848],[120.7930393,15.55916],[120.7928646,15.5570187],
            [120.7921781,15.555107],[120.7912123,15.554853],[120.7913399,15.5543176],
            [120.7915605,15.5533236],[120.7918092,15.5534046],[120.8001316,15.5478115],
            [120.8011058,15.5481325],[120.8021398,15.5484701],[120.8027807,15.5485113],
            [120.8032508,15.5489723],[120.8030798,15.5500426],[120.8038043,15.5501365],
            [120.8044282,15.5502517],[120.8049495,15.550614],[120.8058211,15.5508445],
            [120.8062911,15.551569],[120.8071584,15.5520964],[120.8076635,15.5520903],
            [120.8081181,15.5524005],[120.8083454,15.5523519],[120.8085979,15.5525708],
            [120.8088668,15.5528807],[120.8118007,15.5512389],[120.8126332,15.550257],
            [120.8153176,15.5523838],[120.817434,15.549628],[120.8219183,15.5518119],
            [120.8232918,15.5522367],[120.8253946,15.5516159],[120.8260956,15.5512188],
            [120.8281375,15.5526533],[120.8298546,15.5518644],[120.8310955,15.5519514],
            [120.8335885,15.5541358],[120.8325752,15.5557229],[120.8326161,15.5574083],
            [120.8332704,15.5602447],[120.8283841,15.5650646],[120.8236492,15.5703491],
            [120.82189,15.5689622],[120.8219651,15.5676998],[120.8203353,15.5645562],
            [120.8205697,15.5594636],[120.8185042,15.5617437],[120.8149287,15.5609879],
            [120.8126889,15.5623097],[120.8092582,15.5595308],[120.8032464,15.5673914],
            [120.8014669,15.5699463],[120.8013517,15.5699279]
        ]] } }]
    };
    L.geoJSON(barangayGeoJSON, { style: { color: '#10b981', weight: 2.5, fillColor: '#d1fae5', fillOpacity: 0.12, dashArray: '6 5' } }).addTo(map);

    const statusColors = {
        1: { color: '#f59e0b', bg: '#fef3c7', txt: '#92400e', label: 'Pending' },
        2: { color: '#3b82f6', bg: '#dbeafe', txt: '#1e40af', label: 'Verified' },
        3: { color: '#8b5cf6', bg: '#ede9fe', txt: '#4c1d95', label: 'In Progress' },
        4: { color: '#10b981', bg: '#d1fae5', txt: '#065f46', label: 'Resolved' },
        5: { color: '#ef4444', bg: '#fee2e2', txt: '#991b1b', label: 'Rejected' }
    };

    const popupStyle = document.createElement('style');
    popupStyle.innerHTML = `
        .map-pw { font-family: 'Inter', sans-serif; width: 220px; }
        .map-pi  { width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 8px; background: #f1f5f9; }
        .map-pp  { width: 100%; height: 70px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border-radius: 8px; margin-bottom: 8px; color: #94a3b8; font-size: 11px; }
        .map-pb  { display: inline-block; padding: 2px 9px; border-radius: 99px; font-size: 10px; font-weight: 700; letter-spacing: .04em; margin-bottom: 5px; }
        .map-pc  { font-size: 13px; font-weight: 800; color: #1e293b; margin: 0 0 3px; }
        .map-pd  { font-size: 11px; color: #64748b; margin: 0 0 7px; line-height: 1.5; }
        .map-pdt { font-size: 10px; color: #94a3b8; margin: 0 0 7px; }
        .map-pa  { display: block; text-align: center; background: #0B2E22; color: #fff; border-radius: 8px; padding: 6px 0; font-size: 11px; font-weight: 700; text-decoration: none; }
        .map-pa:hover { background: #10b981; }
        .leaflet-popup-content-wrapper { border-radius: 14px !important; box-shadow: 0 8px 24px rgba(0,0,0,0.14) !important; padding: 0 !important; overflow: hidden; }
        .leaflet-popup-content { margin: 12px !important; }
    `;
    document.head.appendChild(popupStyle);

    const mapPins = <?php echo json_encode($data['map_pins'] ?? []); ?>;
    mapPins.forEach(pin => {
        const cfg = statusColors[pin.status_id] || { color: '#9ca3af', bg: '#f3f4f6', txt: '#4b5563', label: 'Unknown' };
        const icon = L.divIcon({
            html: `<div style="background:${cfg.color};width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>`,
            className: '', iconSize: [16,16], iconAnchor: [8,8]
        });
        const desc = (pin.description || '').trim();
        const shortDesc = desc.length > 80 ? desc.substring(0, 80) + '…' : desc;
        const dateStr = pin.submission_date
            ? new Date(pin.submission_date).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' }) : '';
        const imgHtml = pin.photo_path
            ? `<img class="map-pi" src="/brgy-waste-app-v3/public/uploads/${pin.photo_path}" alt="" onerror="this.style.display='none'">`
            : `<div class="map-pp">📷 No photo</div>`;

        const popup = `<div class="map-pw">${imgHtml}<span class="map-pb" style="background:${cfg.bg};color:${cfg.txt}">${cfg.label}</span><p class="map-pc">${pin.category_name||'Waste Report'}</p>${shortDesc?`<p class="map-pd">${shortDesc}</p>`:''} ${dateStr?`<p class="map-pdt">📅 ${dateStr}</p>`:''}<a class="map-pa" href="/brgy-waste-app-v3/public/resident/view_report/${pin.id}">View Details →</a></div>`;
        L.marker([pin.latitude, pin.longitude], { icon }).addTo(map).bindPopup(popup, { maxWidth: 240, minWidth: 220 });
    });

    try {
        const bounds = L.geoJSON(barangayGeoJSON).getBounds();
        map.fitBounds(bounds, { padding: [16, 16] });
    } catch(e) { map.setView(mapCenter, 15); }

    setTimeout(() => map.invalidateSize(), 150);
    window.addEventListener('resize', () => map.invalidateSize());
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>