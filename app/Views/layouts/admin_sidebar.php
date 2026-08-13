<?php
// Get current user info from session
$fullName = $_SESSION['user_name'] ?? 'Secretary Rose';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Admin';
$role = $_SESSION['user_role'] ?? 'Admin User';

// Determine active page for highlighting
$currentUri = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($currentUri) {
    if ($path === '/admin') {
        return (strpos($currentUri, '/admin') !== false && strpos($currentUri, '/admin/') === false) || 
               strpos($currentUri, '/admin/dashboard') !== false;
    }
    return strpos($currentUri, $path) !== false;
};

// Fetch pending reports count for badge
$db = new Database();
$db->query("SELECT COUNT(*) as count FROM reports r JOIN report_statuses rs ON r.status_id = rs.status_id WHERE rs.status_name = 'Pending'");
$pendingCount = (int)($db->single()['count'] ?? 0);

// Check if currently on Reports page
$isReportsPage = $isActive('/admin/reports') || strpos($currentUri, '/admin/viewReport') !== false;

// If visiting the reports page, store that current pending reports have been viewed
if ($isReportsPage) {
    $_SESSION['reports_seen_count'] = $pendingCount;
}

$seenCount = (int)($_SESSION['reports_seen_count'] ?? 0);
$showPendingBadge = ($pendingCount > 0) && ($pendingCount > $seenCount) && !$isReportsPage;
?>

<!-- Mobile Topbar Header with Hamburger Menu Toggle -->
<div class="lg:hidden bg-[#041a14] border-b border-emerald-900/40 px-4 py-3 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#083528] border border-emerald-500/30 shadow-[0_0_12px_rgba(16,185,129,0.3)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div>
            <p class="text-base font-extrabold text-white tracking-tight leading-none">Wastewatch</p>
            <p class="text-[10px] font-medium text-emerald-400/80 leading-tight">Waste Management Admin</p>
        </div>
    </div>
    <button type="button" id="adminSidebarToggle" onclick="document.getElementById('adminSidebarDrawer').classList.toggle('hidden')" class="p-2 rounded-xl bg-white/5 text-emerald-300 hover:bg-white/10 hover:text-white transition focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<!-- Main Admin Sidebar Component -->
<aside id="adminSidebarDrawer" class="hidden lg:flex w-full lg:w-72 bg-[#041a14] text-white px-4 py-5 lg:sticky lg:top-0 lg:h-screen flex-col flex-shrink-0 border-r border-emerald-950/60 z-40">
    <!-- Brand Header -->
    <div class="flex items-center gap-3 px-2 py-1 flex-shrink-0">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#083528] border border-emerald-500/30 shadow-[0_0_18px_rgba(16,185,129,0.35)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-extrabold text-white tracking-tight leading-snug">Wastewatch</h1>
            <p class="text-xs font-medium text-emerald-400/70 leading-none">Waste Management Admin</p>
        </div>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="mt-6 flex-1 overflow-y-auto space-y-6 pr-1 custom-scrollbar">
        <!-- Section: CORE -->
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">CORE</p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <?php $activeDash = $isActive('/admin') && !$isActive('/admin/reports') && !$isActive('/admin/accounts') && !$isActive('/admin/gis') && !$isActive('/admin/schedule') && !$isActive('/admin/announcements') && !$isActive('/admin/report_summaries') && !$isActive('/admin/settings') && !$isActive('/admin/auditLogs') && !$isActive('/admin/profile') && !$isActive('/admin/createStaff'); ?>
                <a href="/brgy-waste-app-v3/public/admin" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeDash ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeDash): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeDash ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Reports -->
                <?php $activeReports = $isReportsPage; ?>
                <a href="/brgy-waste-app-v3/public/admin/reports" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeReports ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeReports): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeReports ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    <span>Reports</span>
                    <?php if ($showPendingBadge): ?>
                        <span class="ml-auto rounded-full bg-[#FF4D4D] text-white text-[10px] font-black px-2 py-0.5 shadow-sm"><?php echo $pendingCount; ?></span>
                    <?php endif; ?>
                </a>

                <!-- User Mgmt -->
                <?php if (in_array($_SESSION['user_role'] ?? 'administrator', ['secretary', 'administrator'])): ?>
                <?php $activeUsers = $isActive('/admin/accounts'); ?>
                <a href="/brgy-waste-app-v3/public/admin/accounts" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeUsers ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeUsers): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeUsers ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>User Mgmt</span>
                </a>
                <?php endif; ?>

                <!-- Settings -->
                <?php $activeSettings = $isActive('/settings'); ?>
                <a href="/brgy-waste-app-v3/public/settings" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSettings ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeSettings): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeSettings ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.10a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- Section: MANAGEMENT -->
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">MANAGEMENT</p>
            <div class="space-y-1">
                <!-- Create Staff -->
                <?php $activeStaff = $isActive('/admin/createStaff'); ?>
                <a href="/brgy-waste-app-v3/public/admin/createStaff" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeStaff ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeStaff): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeStaff ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                    <span>Create Staff</span>
                </a>

                <!-- GIS Monitor -->
                <?php $activeGis = $isActive('/admin/gis'); ?>
                <a href="/brgy-waste-app-v3/public/admin/gis" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeGis ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeGis): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeGis ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/>
                    </svg>
                    <span>GIS Monitor</span>
                </a>

                <!-- Schedule -->
                <?php $activeSched = $isActive('/admin/schedule') || strpos($currentUri, '/admin/editSchedule') !== false; ?>
                <a href="/brgy-waste-app-v3/public/admin/schedule" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSched ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeSched): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeSched ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                    <span>Schedule</span>
                </a>

                <!-- Announcements -->
                <?php $activeAnnounce = $isActive('/admin/announcements'); ?>
                <a href="/brgy-waste-app-v3/public/admin/announcements" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAnnounce ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeAnnounce): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeAnnounce ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                    </svg>
                    <span>Announcements</span>
                </a>
            </div>
        </div>

        <!-- Section: SYSTEM -->
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">SYSTEM</p>
            <div class="space-y-1">
                <!-- Analytics -->
                <?php $activeAnalytics = $isActive('/admin/report_summaries'); ?>
                <a href="/brgy-waste-app-v3/public/admin/report_summaries" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAnalytics ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeAnalytics): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeAnalytics ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <span>Analytics</span>
                </a>

                <!-- Audit Logs -->
                <?php $activeAudit = $isActive('/admin/auditLogs'); ?>
                <a href="/brgy-waste-app-v3/public/admin/auditLogs" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAudit ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeAudit): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeAudit ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/>
                    </svg>
                    <span>Audit Logs</span>
                </a>

                <!-- Profile -->
                <?php $activeProfile = $isActive('/admin/profile'); ?>
                <a href="/brgy-waste-app-v3/public/admin/profile" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeProfile ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeProfile): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeProfile ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/>
                    </svg>
                    <span>Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom User Profile Card (Matching Screenshot) -->
    <div class="mt-4 pt-3 flex-shrink-0">
        <div class="bg-[#0B3326]/90 border border-emerald-500/20 p-3 rounded-2xl flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#084232] border border-emerald-400/40 text-[#10B981] shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm text-white leading-tight truncate"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[11px] font-medium text-emerald-300/70 leading-tight truncate"><?php echo ucfirst(htmlspecialchars($role)); ?></p>
                </div>
            </div>
            <a href="/brgy-waste-app-v3/public/auth/logout" title="Logout" class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
    </div>
</aside>