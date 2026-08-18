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

// Read server-side cookie for initial collapsed state (0ms latency, zero flicker)
$isCollapsedCookie = (isset($_COOKIE['admin_sidebar_collapsed']) && $_COOKIE['admin_sidebar_collapsed'] === '1');
$initialCollapsedClass = $isCollapsedCookie ? 'is-collapsed' : '';

// Fetch system branding & barangay customization
try {
    $db->query("SELECT system_name, system_short_name, system_logo, barangay_name, municipality, province FROM barangays LIMIT 1");
    $brgyBranding = $db->single();
} catch (Exception $e) {
    $brgyBranding = null;
}
$sysShortName = !empty($brgyBranding['system_short_name']) ? $brgyBranding['system_short_name'] : 'WasteWatch';
$brgyName = !empty($brgyBranding['barangay_name']) ? $brgyBranding['barangay_name'] : 'Dulong Bayan';
$brgyMuni = !empty($brgyBranding['municipality']) ? $brgyBranding['municipality'] : 'Talavera';
$brgyProv = !empty($brgyBranding['province']) ? $brgyBranding['province'] : 'Nueva Ecija';
$sysMotto = "Brgy. {$brgyName}, {$brgyMuni}";
$sysLogo = format_asset_url($brgyBranding['system_logo'] ?? '');
?>

<style>
    /* Collapsible Sidebar & Mobile Drawer Styles */
    /* Mobile backdrop overlay */
    #mobileSidebarBackdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 45;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        transition: opacity 0.25s ease;
    }
    #mobileSidebarBackdrop.open { display: block; }
    /* Mobile sidebar drawer */
    #adminSidebarDrawer.mobile-open {
        display: flex !important;
        transform: translateX(0) !important;
    }
    /* Collapsible Sidebar Styles */
    #adminSidebarDrawer {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
    }
    #adminSidebarDrawer.is-collapsed {
        width: 5rem !important; /* w-20 = 80px */
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-text,
    #adminSidebarDrawer.is-collapsed .sidebar-brand-text,
    #adminSidebarDrawer.is-collapsed .sidebar-section-title,
    #adminSidebarDrawer.is-collapsed .sidebar-user-details {
        display: none !important;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-link {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        padding: 2px 5px;
        font-size: 9px;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-user-card {
        justify-content: center !important;
        padding: 0.5rem !important;
    }
</style>

<!-- Mobile Backdrop Overlay -->
<div id="mobileSidebarBackdrop" onclick="closeMobileSidebar()"></div>

<!-- Main Admin Sidebar Component -->
<aside id="adminSidebarDrawer" class="hidden lg:flex fixed lg:relative inset-y-0 left-0 w-72 lg:w-72 bg-[#041a14] text-white px-4 py-5 lg:sticky lg:top-0 lg:h-screen flex-col flex-shrink-0 border-r border-emerald-950/60 z-50 lg:z-40 transition-transform duration-300 lg:transform-none <?php echo $initialCollapsedClass; ?>">
    <!-- Immediate Inline Execution to prevent layout flicker -->
    <script>
        (function() {
            var savedState = localStorage.getItem('admin_sidebar_collapsed');
            var sidebar = document.getElementById('adminSidebarDrawer');
            if (savedState === 'true' && sidebar && !sidebar.classList.contains('is-collapsed')) {
                sidebar.classList.add('is-collapsed');
            } else if (savedState === 'false' && sidebar && sidebar.classList.contains('is-collapsed')) {
                sidebar.classList.remove('is-collapsed');
            }
        })();
    </script>
    
    <!-- Brand Header & Collapse Toggle Button -->
    <div class="flex items-center justify-between px-2 py-1 flex-shrink-0">
        <a href="<?php echo app_url('admin'); ?>" class="flex items-center gap-3 min-w-0">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#083528] border-2 border-emerald-500/40 shadow-[0_0_18px_rgba(16,185,129,0.35)] overflow-hidden">
                <?php if (!empty($sysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div class="sidebar-brand-text min-w-0">
                <h1 class="text-xl font-extrabold text-white tracking-tight leading-snug truncate"><?php echo htmlspecialchars($sysShortName); ?></h1>
                <p class="text-xs font-medium text-emerald-400/70 leading-none truncate"><?php echo htmlspecialchars($sysMotto); ?></p>
            </div>
        </a>

        <!-- Desktop Collapse / Expand Toggle Button -->
        <button type="button" id="sidebarCollapseBtn" onclick="toggleDesktopSidebar()" 
                class="hidden lg:flex items-center justify-center h-8 w-8 rounded-xl bg-white/5 text-emerald-400/80 hover:text-white hover:bg-white/10 transition"
                title="Collapse Sidebar">
            <svg id="collapseIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="mt-6 flex-1 overflow-y-auto space-y-6 pr-1 custom-scrollbar">
        
        <!-- Section: CORE -->
        <div>
            <p class="sidebar-section-title text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">CORE</p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <?php $activeDash = $isActive('/admin') && !$isActive('/admin/reports') && !$isActive('/admin/accounts') && !$isActive('/admin/gis') && !$isActive('/admin/schedule') && !$isActive('/admin/announcements') && !$isActive('/admin/report_summaries') && !$isActive('/admin/settings') && !$isActive('/admin/auditLogs') && !$isActive('/admin/profile') && !$isActive('/admin/createStaff'); ?>
                <a href="<?php echo app_url('admin'); ?>" title="Dashboard" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeDash ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeDash): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeDash ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <!-- Reports -->
                <?php $activeReports = $isReportsPage; ?>
                <a href="<?php echo app_url('admin/reports'); ?>" title="Reports" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeReports ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeReports): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeReports ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    <span class="sidebar-text">Reports</span>
                    <?php if ($showPendingBadge): ?>
                        <span class="sidebar-badge ml-auto rounded-full bg-[#FF4D4D] text-white text-[10px] font-black px-2 py-0.5 shadow-sm"><?php echo $pendingCount; ?></span>
                    <?php endif; ?>
                </a>

                <!-- User Mgmt -->
                <?php if (in_array($_SESSION['user_role'] ?? 'administrator', ['secretary', 'administrator'])): ?>
                <?php $activeUsers = $isActive('/admin/accounts'); ?>
                <a href="<?php echo app_url('admin/accounts'); ?>" title="User Management" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeUsers ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeUsers): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeUsers ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="sidebar-text">User Mgmt</span>
                </a>
                <?php endif; ?>

                <!-- Settings -->
                <?php $activeSettings = $isActive('/settings'); ?>
                <a href="<?php echo app_url('settings/barangay'); ?>" title="Settings" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSettings ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeSettings): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSettings ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.10a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span class="sidebar-text">Settings</span>
                </a>
            </div>
        </div>

        <!-- Section: MANAGEMENT -->
        <div>
            <p class="sidebar-section-title text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">MANAGEMENT</p>
            <div class="space-y-1">
                <!-- Create Staff -->
                <?php $activeStaff = $isActive('/admin/createStaff'); ?>
                <a href="<?php echo app_url('admin/createStaff'); ?>" title="Create Staff" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeStaff ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeStaff): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeStaff ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                    <span class="sidebar-text">Create Staff</span>
                </a>

                <!-- GIS Monitor -->
                <?php $activeGis = $isActive('/admin/gis'); ?>
                <a href="<?php echo app_url('admin/gis'); ?>" title="GIS Monitor" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeGis ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeGis): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeGis ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/>
                    </svg>
                    <span class="sidebar-text">GIS Monitor</span>
                </a>

                <!-- Schedule -->
                <?php $activeSched = $isActive('/admin/schedule') || strpos($currentUri, '/admin/editSchedule') !== false; ?>
                <a href="<?php echo app_url('admin/schedule'); ?>" title="Schedule" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSched ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeSched): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSched ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span class="sidebar-text">Schedule</span>
                </a>

                <!-- Announcements -->
                <?php $activeAnnounce = $isActive('/admin/announcements'); ?>
                <a href="<?php echo app_url('admin/announcements'); ?>" title="Announcements" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAnnounce ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeAnnounce): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAnnounce ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                    </svg>
                    <span class="sidebar-text">Announcements</span>
                </a>
            </div>
        </div>

        <!-- Section: SYSTEM -->
        <div>
            <p class="sidebar-section-title text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">SYSTEM</p>
            <div class="space-y-1">
                <!-- Analytics -->
                <?php $activeAnalytics = $isActive('/admin/report_summaries'); ?>
                <a href="<?php echo app_url('admin/report_summaries'); ?>" title="Analytics" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAnalytics ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeAnalytics): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAnalytics ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <span class="sidebar-text">Analytics</span>
                </a>

                <!-- Audit Logs -->
                <?php $activeAudit = $isActive('/admin/auditLogs'); ?>
                <a href="<?php echo app_url('admin/auditLogs'); ?>" title="Audit Logs" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAudit ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeAudit): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAudit ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/>
                    </svg>
                    <span class="sidebar-text">Audit Logs</span>
                </a>

                <!-- Profile -->
                <?php $activeProfile = $isActive('/admin/profile'); ?>
                <a href="<?php echo app_url('admin/profile'); ?>" title="Profile" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeProfile ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeProfile): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeProfile ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/>
                    </svg>
                    <span class="sidebar-text">Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom User Profile Card -->
    <div class="mt-4 pt-3 flex-shrink-0">
        <div class="sidebar-user-card bg-[#0B3326]/90 border border-emerald-500/20 p-3 rounded-2xl flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#084232] border border-emerald-400/40 text-[#10B981] shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="sidebar-user-details min-w-0">
                    <p class="font-bold text-sm text-white leading-tight truncate"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[11px] font-medium text-emerald-300/70 leading-tight truncate"><?php echo ucfirst(htmlspecialchars($role)); ?></p>
                </div>
            </div>
            <a href="<?php echo app_url('auth/logout'); ?>" title="Logout" class="sidebar-user-details p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </a>
        </div>
    </div>
</aside>

<script>
    // --- Desktop Sidebar Collapse/Expand Handler ---
    function toggleDesktopSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const icon = document.getElementById('collapseIcon');
        const btn = document.getElementById('sidebarCollapseBtn');
        if (!sidebar) return;

        const isCollapsed = sidebar.classList.toggle('is-collapsed');
        
        // Save state in BOTH Cookie AND LocalStorage for 100% consistency across all pages
        document.cookie = "admin_sidebar_collapsed=" + (isCollapsed ? "1" : "0") + "; path=/; max-age=31536000";
        localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');

        if (icon) {
            icon.style.transform = isCollapsed ? 'rotate(180deg)' : 'rotate(0deg)';
        }
        if (btn) {
            btn.title = isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar';
        }
    }

    // --- Mobile Sidebar Drawer Toggle ---
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar) return;

        const isOpen = sidebar.classList.contains('mobile-open');
        if (isOpen) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    }

    function openMobileSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar) return;
        sidebar.classList.remove('hidden');
        sidebar.classList.add('mobile-open');
        if (backdrop) backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar) return;
        sidebar.classList.remove('mobile-open');
        if (backdrop) backdrop.classList.remove('open');
        document.body.style.overflow = '';
        // Delay hiding so transition plays
        setTimeout(() => {
            if (!sidebar.classList.contains('mobile-open')) {
                sidebar.classList.add('hidden');
            }
        }, 280);
    }

    // --- Sync Icon Orientation on Load ---
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const icon = document.getElementById('collapseIcon');
        const btn = document.getElementById('sidebarCollapseBtn');

        if (sidebar && sidebar.classList.contains('is-collapsed')) {
            if (icon) icon.style.transform = 'rotate(180deg)';
            if (btn) btn.title = 'Expand Sidebar';
        }
    });
</script>